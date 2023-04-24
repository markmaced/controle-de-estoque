<?php 
/**
 * Optimization Functions and definitions
 *
 * @package Marcos Macedo
 */

/**
 * Cleanup wp_head().
 */
function head_cleanup() {
    // category feeds.
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    // post and comment feeds.
    remove_action( 'wp_head', 'feed_links', 2 );
    // EditURI link.
    remove_action( 'wp_head', 'rsd_link' );
    // windows live writer.
    remove_action( 'wp_head', 'wlwmanifest_link' );
    // index link.
    remove_action( 'wp_head', 'index_rel_link' );
    // previous link.
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    // start link.
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    // links for adjacent posts.
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    // WP version.
    remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'head_cleanup' );

/**
 * Remove WP version from RSS.
 */
add_filter( 'the_generator', '__return_false' );

/**
 * Disable wp-json
 */
remove_action('wp_head', 'rest_output_link_wp_head', 10);


// REMOVE WP EMOJI
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');


?>