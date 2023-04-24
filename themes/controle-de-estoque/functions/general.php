<?php
/**
 * ------------------------------------------------------------------------- *
 * GENERAL CONFIGURATION (styles and scripts) *
 * ------------------------------------------------------------------------- *
 *
 * @package Marcos Macedo
 */

/**
 * Load scripts and style.
 */
function enqueue_scripts() {

  wp_enqueue_style('main-css', WP_STYLE_URL . '/main.css', array(), null, false);
  wp_enqueue_style('grid', WP_STYLE_URL . '/grid.css', array(), null, false);


  wp_register_script('app', WP_SCRIPT_URL . '/app.js', array('jquery'), null, true);
  wp_enqueue_script('app');
   
}
add_action('wp_enqueue_scripts', 'enqueue_scripts');

//Feature image support
add_theme_support( 'post-thumbnails' );


// admin styles
$current_user = wp_get_current_user();
if ($current_user->user_login != '') {
    function custom_wp_admin_style() {
        wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/assets/admin/admin.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wp_admin_css' );

    }
    add_action( 'admin_enqueue_scripts', 'custom_wp_admin_style' );
}

// login styles
function custom_login_page() {
    wp_enqueue_style('login', get_template_directory_uri() . '/assets/login/login.css', false, '1.0.0' ); 
}
add_action( 'login_enqueue_scripts', 'custom_login_page', 10 );

function images($url, $theme = NULL){
$template = get_template_directory_uri();
$images = '/assets/img/';

  if($theme == NULL){
    return $url;
  }
  else {
    return $template . '/' . $images . '/' . $url;
  }
}

// ACF GOOLE MAP key
function ACF_GOOGLE_KEY() {
  acf_update_setting('google_api_key', 'AIzaSyBgtPPzikTR7Cw15AjqUGKDM0pfdp2MCgs');
}
add_action('acf/init', 'ACF_GOOGLE_KEY');

// Login via email ou username

function login_with_email_address( &$username ) {
	$user = get_user_by( 'email', $username );
    if ( !empty( $user->user_login ) )
	$username = $user->user_login;

	return $username;
	
}

add_action( 'wp_authenticate','login_with_email_address');



// Custom Excerpt

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);

  if (count($excerpt) >= $limit) {
      array_pop($excerpt);
      $excerpt = implode(" ", $excerpt) . '...';
  } else {
      $excerpt = implode(" ", $excerpt);
  }

  $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);

  return $excerpt;
}


function slugify($text)
{
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, '-');
  $text = preg_replace('~-+~', '-', $text);
  $text = strtolower($text);

  if (empty($text)) {
    return '';
  }

  return $text;
}