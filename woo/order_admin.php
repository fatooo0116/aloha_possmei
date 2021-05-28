<?php
function custom_order_before_calculate_totals($and_taxes, $order ) {
    // The loop to get the order items which are WC_Order_Item_Product objects since WC 3+
    // loop all products and calculate total deposit
    $total_deposit = 0;
    foreach( $order->get_items() as $item_id => $item ) {
        // get the WC_Product object
        //  $product = $item->get_product();
      //   print_r($item);
    }


  
}
add_action( 'woocommerce_order_before_calculate_totals', "custom_order_before_calculate_totals", 10, 3);