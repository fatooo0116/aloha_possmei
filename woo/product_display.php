<?php
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options –> Reading
  // Return the number of products you wanna show per page.
  $cols = (esc_attr( get_option('per_page_product') )) ? esc_attr( get_option('per_page_product') ) : 12;
  return $cols;
}