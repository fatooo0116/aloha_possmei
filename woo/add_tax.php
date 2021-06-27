<?php
// Dynamic add Tax By User Info
add_action( 'woocommerce_cart_calculate_fees','auto_add_tax_for_room', 10, 1 );
function auto_add_tax_for_room( $cart ) {
    if ( is_admin() && ! defined('DOING_AJAX') ) return;
  
    $percent = 0;

    $user_id = get_current_user_id();

    if( $user_id){ /*  目前是否有人登入 */
        global $wpdb;
        $table_name =  $wpdb->prefix . 'customer_info';;
        $sql = "SELECT * FROM $table_name WHERE woo_id='".$user_id."'";
        $results = $wpdb->get_results($sql);
        if($results[0]->tax){
            $percent = $results[0]->tax;
            $surcharge = ( $cart->cart_contents_total + $cart->shipping_total ) * $percent / 100;
            $cart->add_fee( __( 'TAX', 'woocommerce')." ($percent%)", $surcharge, false );
        }
    }

    

  

    // Calculation
  //  $surcharge = ( $cart->cart_contents_total + $cart->shipping_total ) * $percent / 100;

    // Add the fee (tax third argument disabled: false)
  //  $cart->add_fee( __( 'TAX', 'woocommerce')." ($percent%)", $surcharge, false );
}

?>