<?php





add_action( 'wp_ajax_cart_num_action', 'cart_num_stuff' ); // 針對已登入的使用者
add_action( 'wp_ajax_nopriv_cart_num_action', 'cart_num_stuff' ); // 針對未登入的使用者

function cart_num_stuff() {
  
  global $woocommerce;        
  echo $woocommerce->cart->cart_contents_count;

  die(); // 一定要加這行，才會完整的處理ajax請求
}
