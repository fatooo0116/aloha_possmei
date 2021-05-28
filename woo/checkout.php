<?php
/*  儲存選擇的地址 */
add_action( 'woocommerce_checkout_update_order_meta', 'add_order_delivery_address' , 10, 1);

function add_order_delivery_address ( $order_id ) {

	if ( isset( $_POST ['company_address_select'] ) &&  '' != $_POST ['company_address_select'] ) {
		add_post_meta( $order_id, 'company_address_select',  sanitize_text_field( $_POST ['company_address_select'] ) );
	}
  
	if ( isset( $_POST ['company_address'] ) &&  '' != $_POST ['company_address'] ) {
		add_post_meta( $order_id, 'company_address',  sanitize_text_field( $_POST ['company_address'] ) );
	}
}












add_action( 'edit_user_profile', 'extra_profile_fields', 10 );
add_action( 'show_user_profile', 'extra_profile_fields', 10 );
function extra_profile_fields() {    
    ?>            
        <style>
          #hide_woo ~ h2,
          #fieldset-billing ~ h2,
          #fieldset-billing,
          #fieldset-shipping ~ h2,
          #fieldset-shipping{display:none ! important;;}
        </style>
        <div id="hide_woo"></div>
    <?php
}






function wc_remove_checkout_fields( $fields ) {

  //  wp_enqueue_script( 'jquery-ui-datepicker' );
  
    // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
 //   wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
 //   wp_enqueue_style( 'jquery-ui' );
   
  
  
      // Billing fields
       unset( $fields['billing']['billing_company'] );
         unset( $fields['billing']['billing_email'] );
         unset( $fields['billing']['billing_phone'] );
       unset( $fields['billing']['billing_state'] );
       unset( $fields['billing']['billing_first_name'] );
      unset( $fields['billing']['billing_last_name'] );
      unset( $fields['billing']['billing_address_1'] );
       unset( $fields['billing']['billing_address_2'] );
       unset( $fields['billing']['billing_city'] );
       unset( $fields['billing']['billing_postcode'] );
       unset( $fields['billing']['billing_country'] );
  
      // Shipping fields
      
      unset( $fields['shipping']['shipping_company'] );
      unset( $fields['shipping']['shipping_phone'] );
      unset( $fields['shipping']['shipping_state'] );
      unset( $fields['shipping']['shipping_first_name'] );
      unset( $fields['shipping']['shipping_last_name'] );
      unset( $fields['shipping']['shipping_address_1'] );
      unset( $fields['shipping']['shipping_address_2'] );
      unset( $fields['shipping']['shipping_city'] );
      unset( $fields['shipping']['shipping_postcode'] );
      
  
  
      // unset( $fields['order']['order_comments'] );
  
      return $fields;
  }
  add_filter( 'woocommerce_checkout_fields', 'wc_remove_checkout_fields' );
  
  