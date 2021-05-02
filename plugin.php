<?php
/*
Plugin Name: Product Calculate and Shipping Cargo
Plugin URI: https://aloha-tech.com/
Description: Mike Hsu
Author: Mike Hsu
Version: 1.7.2
Author URI: https://aloha-tech.com/
*/

// require 'woo/product_meta.php';
 /*  style  */
 function wpdocs_theme_name_scripts() {   
    wp_enqueue_style('style-aloha-css', esc_url( plugins_url( 'assets/css/main.css', __FILE__ )), array(), rand(0,9999), 'all');
    wp_enqueue_script( 'script-aloha-js', esc_url( plugins_url( 'assets/js/script.js', __FILE__ )), array(), rand(0,9999), true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );




register_activation_hook( __FILE__, 'product_meta_db' );
require "db/db.php";


/* API */
// require 'news-api/api.php';
require 'news-api/dep-api.php';
require 'news-api/customer-api.php';
require 'news-api/customer-addr.php';
require 'news-api/customer-type-api.php';
require 'news-api/product-api.php';
require 'news-api/product-type-api.php';
require 'news-api/staff-api.php';


/*  product_metabox  */
require 'metabox/product.php';

/*  wow  */
require 'woo/product_meta.php';
require 'woo/product_cat_field.php';
require 'woo/disable_woo.php';
require 'woo/myaccount.php';
require 'woo/footer.php';




/*
add_filter( 'woocommerce_add_to_cart_redirect', 'misha_skip_cart_redirect_checkout' );
 
function misha_skip_cart_redirect_checkout( $url ) {
    return wc_get_checkout_url();
}
*/

/*
add_filter( 'woocommerce_loop_add_to_cart_link', function( $html, $product ) {
    if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
        $html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="wcb2b-quantity" method="post" enctype="multipart/form-data">';
        $html .= woocommerce_quantity_input( array(), $product, false );
        $html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
        $html .= '</form>';
    }
    return $html;
}, 10, 2 );
*/

add_filter( 'woocommerce_loop_add_to_cart_link', 'quantity_inputs_for_loop_ajax_add_to_cart', 10, 2 );
function quantity_inputs_for_loop_ajax_add_to_cart( $html, $product ) {
    if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
        // Get the necessary classes
        $class = implode( ' ', array_filter( array(
            'button',
            'product_type_' . $product->get_type(),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
        ) ) );

        // Embedding the quantity field to Ajax add to cart button
        $html = sprintf( '%s<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
            woocommerce_quantity_input( array(), $product, false ),
            esc_url( $product->add_to_cart_url() ),
            esc_attr( isset( $quantity ) ? $quantity : 1 ),
            esc_attr( $product->get_id() ),
            esc_attr( $product->get_sku() ),
            esc_attr( isset( $class ) ? $class : 'button' ),
            esc_html( $product->add_to_cart_text() )
        );
    }
    return $html;
}

add_action( 'wp_footer' , 'archives_quantity_fields_script' );
function archives_quantity_fields_script(){
    ?>
    <script type='text/javascript'>
        jQuery(function($){
            // Update data-quantity
            $(document.body).on('change', 'input.qty', function() {
                $(this).parent().parent().find('a.ajax_add_to_cart').attr('data-quantity', $(this).val());
                $(".added_to_cart").remove(); // Optional: Removing other previous "view cart" buttons
            }).on('click', '.add_to_cart_button', function(){
                var button = $(this);
                setTimeout(function(){
                    button.parent().find('.quantity > input.qty').val(1); // reset quantity to 1
                }, 1000); // After 1 second

            });
        });
    </script>
    <?php
}



add_action( 'wp_footer' , 'custom_quantity_fields_script' );
function custom_quantity_fields_script(){
    ?>
    <script type='text/javascript'>
    jQuery( function( $ ) {
        if ( ! String.prototype.getDecimals ) {
            String.prototype.getDecimals = function() {
                var num = this,
                    match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
                if ( ! match ) {
                    return 0;
                }
                return Math.max( 0, ( match[1] ? match[1].length : 0 ) - ( match[2] ? +match[2] : 0 ) );
            }
        }
        // Quantity "plus" and "minus" buttons
        $( document.body ).on( 'click', '.plus, .minus', function() {
            var $qty        = $( this ).closest( '.quantity' ).find( '.qty'),
                currentVal  = parseFloat( $qty.val() ),
                max         = parseFloat( $qty.attr( 'max' ) ),
                min         = parseFloat( $qty.attr( 'min' ) ),
                step        = $qty.attr( 'step' );

            // Format values
            if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
            if ( max === '' || max === 'NaN' ) max = '';
            if ( min === '' || min === 'NaN' ) min = 0;
            if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

            // Change the value
            if ( $( this ).is( '.plus' ) ) {
                if ( max && ( currentVal >= max ) ) {
                    $qty.val( max );
                } else {
                    $qty.val( ( currentVal + parseFloat( step )).toFixed( step.getDecimals() ) );
                }
            } else {
                if ( min && ( currentVal <= min ) ) {
                    $qty.val( min );
                } else if ( currentVal > 0 ) {
                    $qty.val( ( currentVal - parseFloat( step )).toFixed( step.getDecimals() ) );
                }
            }

            // Trigger change event
            $qty.trigger( 'change' );
        });
    });
    </script>
    <?php
}