<?php
/*   display  product Zoom */
add_action( 'after_setup_theme', 'remove_wp_theme_support', 100 );
 
function remove_wp_theme_support() { 
remove_theme_support( 'wc-product-gallery-zoom' );
}