<?php
/*
Plugin Name: Controle de estoque
Plugin URI: #
description: Um plugin para gerenciamento de estoque
Version: 1.0
Author: Marcos Macedo
Author URI: #
License: GPL2
*/

require_once(plugin_dir_path(__FILE__) . 'inc/add-to-cart.php');
require_once(plugin_dir_path(__FILE__) . 'inc/create-order.php');
require_once(plugin_dir_path(__FILE__) . 'inc/generate-list.php');
require_once(plugin_dir_path(__FILE__) . 'inc/import-product.php');
require_once(plugin_dir_path(__FILE__) . 'inc/report.php');
require_once( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' );

function estoque_scripts() {
    wp_enqueue_style( 'estoque-style' , plugin_dir_url( __FILE__ ) . '/assets/css/main.css' );
    wp_enqueue_script( 'estoque-script', plugin_dir_url( __FILE__ ) . '/assets/js/app.js', array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'estoque-script', 'wpurl',
    array( 
        'ajax' => admin_url( 'admin-ajax.php' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'estoque_scripts' );
?>