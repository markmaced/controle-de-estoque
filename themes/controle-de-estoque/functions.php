<?php
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );
ini_set('display_errors', 'On');
header("Content-Type: text/html; charset=UTF-8");
/**
 * @package Marcos Macedo
 */

/**
 * Sets up theme default paths
 */
if(!defined('WP_SITE_URL'))   { define('WP_SITE_URL', get_bloginfo('url')); }
if(!defined('WP_THEME_URL'))  { define('WP_THEME_URL', get_stylesheet_directory_uri()); }
if(!defined('WP_SCRIPT_URL')) { define('WP_SCRIPT_URL', WP_THEME_URL . '/assets/js'); }
if(!defined('WP_STYLE_URL'))  { define('WP_STYLE_URL', WP_THEME_URL . '/assets/css'); }
if(!defined('WP_IMAGE_URL'))  { define('WP_IMAGE_URL', WP_THEME_URL . '/assets/images'); }

$functions_path = get_template_directory() . '/functions/';

/**
 * Require functions partials.
 */
require_once($functions_path . 'general.php');
require_once($functions_path . 'optimize.php');
require_once($functions_path . 'support.php');
require_once($functions_path . 'admin.php');
require_once($functions_path . 'login.php');
require_once($functions_path . 'theme-options.php');
require_once($functions_path . 'api.php');

?>