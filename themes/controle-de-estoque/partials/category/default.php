<?php
/**
 * @author Marcos Macedo
 */

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post(); 
    	the_title();
?>
		
    <?php
    endwhile;
    wp_reset_postdata();
endif; ?>
