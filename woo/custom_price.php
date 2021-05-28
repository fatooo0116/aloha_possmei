<?php
function get_product_meta($woo_pid){
    global $wpdb;
    $table_price =  $wpdb->prefix . 'product';
    $sql = "SELECT * From ".$table_price." WHERE woo_id=".$woo_pid;
    $results = $wpdb->get_results($sql,ARRAY_A);
    return $results;
}


function get_customer_info($woo_cid){
    global $wpdb;
    $table_price =  $wpdb->prefix . 'customer_info';
    $sql = "SELECT * From ".$table_price." WHERE woo_id=".$woo_cid;
    $results = $wpdb->get_results($sql,ARRAY_A);
    return $results;
}


function get_customer_address($cid){
    global $wpdb;
    $table =  $wpdb->prefix . 'customer_address';
    $sql = "SELECT * From ".$table." WHERE customer_id='".$cid."'";

    $results = $wpdb->get_results($sql,ARRAY_A);
    return $results;
}



 function get_price_by_customer($cid,$pid){
  
        global $wpdb;
        $table_price =  $wpdb->prefix . 'cprice';;
        $sql = "SELECT * From ".$table_price." WHERE woo_pid='".$pid."' AND woo_cid='".$cid."'";  
        $results = $wpdb->get_results($sql,ARRAY_A);

        if($results){
            return $results[0]['price'];
        }else{
            return 0;
        }
   }






add_filter( 'woocommerce_get_price_html', 'bbloomer_alter_price_display2', 9999, 2 );
 
function bbloomer_alter_price_display2( $price_html, $product ) {
    
    // ONLY ON FRONTEND
    // if ( is_admin() ) return $price_html;
    
    // ONLY IF PRICE NOT NULL
   //  if ( '' === $product->get_price() ) return $price_html;
    $cid = get_current_user_id();
    $price = get_price_by_customer($cid,$product->id);


    

    // IF CUSTOMER LOGGED IN, APPLY 20% DISCOUNT   
   //  if ( wc_current_user_has_role( 'customer' ) ) {
         $orig_price = wc_get_price_to_display( $product );
         $price_html = wc_price( $price);
    // }

    return $price_html;
}
 


/**
 * @snippet       Alter Product Pricing Part 2 - WooCommerce Cart/Checkout
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 4.1
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_action( 'woocommerce_before_calculate_totals', 'bbloomer_alter_price_cart2', 9999 );
 
function bbloomer_alter_price_cart2( $cart ) {
 
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
 
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) return;
 
    // IF CUSTOMER NOT LOGGED IN, DONT APPLY DISCOUNT
    if ( ! wc_current_user_has_role( 'customer' ) ) return;
 
    $cid = get_current_user_id();
    
    // LOOP THROUGH CART ITEMS & APPLY 20% DISCOUNT
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];
        $price = $product->get_price();
        
        $price = get_price_by_customer($cid,$product->id);

        $cart_item['data']->set_price($price);
    }
 
}