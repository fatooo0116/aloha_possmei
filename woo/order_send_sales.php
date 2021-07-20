<?php

function sender_filter_recipient( $recipient, $order ){
    
    global $wpdb;
    
    // $order = wc_get_order($order );
    $user_id   = $order->user_id;  
    $user_meta = get_customer_info($user_id);   
    $saleman_email = '';
    if($user_meta[0]['staff_id']){
        $table_name =  $wpdb->prefix . 'comp_staff';;
        $sql_staff = "SELECT * FROM $table_name order by id Desc";
        $results = $wpdb->get_results($sql_staff);
        foreach($results as $sale){
            // print_r($sale);
            if($sale->staff_id==$user_meta[0]['staff_id']){
                $saleman_email = $sale->email;                
            }
        };
    }


    if( $saleman_email){

   //     $recipient = 'stack@example.com';

        // Use this instead IF you wish to ADD this email to the default recipient.
        $recipient .= ', '.$saleman_email;
    }
    return $recipient;
}
add_filter( 'woocommerce_email_recipient_new_order', 'sender_filter_recipient', 10, 2 );


/**
 * Controls if new order emails can be resend multiple times.
 *
 * @since 5.0.0
 * @param bool $allows Defaults to true.
 */
if ( 'true' === $email_already_sent && ! apply_filters( 'woocommerce_new_order_email_allows_resend', false ) ) {
    return;
}
add_filter('woocommerce_new_order_email_allows_resend', '__return_true' );