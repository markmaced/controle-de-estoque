<?php
/**
 * --------------------------------------------------------------
 * ADMIN PANEL CONFIGURATION 
 * --------------------------------------------------------------
 *
 * @package Marcos Macedo
 * 
 *
 */

/* CONFIG */

function admin_agency() {
    return 'Marcos Macedo';
} 
function admin_website_url() { 
    return '#';
}
function admin_year() { 
    return '2023';
}
function admin_site_name() { 
    return 'Marcos Macedo';
}
function admin_contact_email() { 
    return 'marcosmacedo.fogao@gmail.com';
}

/**************/


function wpb_custom_logo() {
    echo '
    <style type="text/css">
        #menu-dashboard:before {
        content: "";
        background-image: url(' .  get_template_directory_uri() . '/assets/images/custom-admin-logo.png) !important;
        width: 100%;
        height: 40px;
        background-size: contain;
        position: absolute;
        top: 0;
        background-repeat: no-repeat;
    }
    </style>
    ';
}

// hook into the administrative header output
add_action('wp_before_admin_bar_render', 'wpb_custom_logo');


function remove_footer_admin () {
    echo "&copy;". date( 'Y' ) . ' - ' . get_bloginfo( 'name' ) . " - Todos os Direitos Reservados.";
}
add_filter('admin_footer_text', 'remove_footer_admin');

/*
 * Remove widgets dashboard.
 */
function admin_remove_dashboard_widgets() {
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', 'admin_remove_dashboard_widgets' );

/*
 * Adicionar box no dashboard
 */

add_action('wp_dashboard_setup', 'mycustom_dashboard_widgets');
function mycustom_dashboard_widgets() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget('custom_help_widget', 'Bem vindo ao painel do site '.admin_agency().'', 'custom_dashboard_help');
}
function custom_dashboard_help() {
    echo '<p>Aqui você poderá gerenciar todo o conteúdo do site.</p><p>Qualquer dúvida, entre em contato através do email '.admin_contact_email().'</p><p>Este site é mantido com a tecnologia do sistema WordPress e foi desenvolvido por <a href="'.admin_website_url().'" target="_blank">'.admin_agency().'</a></p>';
}
/*
 * Remove version from footer.
 */
function change_footer_version() {
    return 'Desenvolvido com <i class="icon-heart"></i> e <i class="icon-coffee"></i> por <a href="'.admin_website_url().'" target="_blank" title="'.admin_agency().'">'.admin_agency().'</a>';
}
add_filter( 'update_footer', 'change_footer_version', 9999 );


/*
 * Remove Welcome Panel.
 */
remove_action( 'welcome_panel', 'wp_welcome_panel' );


// function remove_admin_menu() {
//    if ( get_currentuserinfo()->user_email != 'teste@teste.com' ) // Hide to specific user
//         remove_menu_page( 'edit.php' );
//         remove_menu_page( 'upload.php' );
//         remove_menu_page( 'edit.php?post_type=page' );
//         remove_menu_page( 'edit-comments.php');
//         remove_menu_page( 'themes.php');
//         remove_menu_page( 'plugins.php');
//         remove_menu_page( 'users.php');
//         remove_menu_page( 'tools.php');
//         remove_menu_page( 'options-general.php');
//         remove_menu_page( 'edit.php?post_type=acf-field-group');
       
        
// }
// add_action( 'admin_menu', 'remove_admin_menu' );


?>