<?php

/**
 * @author Marcos Macedo
 */
global $post;
$post_slug = $post->post_name;

get_header(); ?>

    <?php 
    $obj = get_queried_object();
    $postType = $obj->post_type;
    $slug = $obj->post_name;
    $default = get_template_directory() . '/partials/archive/default.php';
    $page = get_template_directory() . '/partials/archive/' . $slug . '.php';

    if ( file_exists( $page ) ) {
        include($page);
    }       
    else { 
        include($default);
    }

    ?>

<?php get_footer(); ?>