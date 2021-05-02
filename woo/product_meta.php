<?php

// Display Fields
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
function woocommerce_product_custom_fields()
{

    global $wpdb, $post;
    $table_name =  $wpdb->prefix . 'product';;
    $sql = "SELECT * FROM $table_name where woo_id=".$post->ID;
    $results = $wpdb->get_results($sql,ARRAY_A);

    //  echo $sql;
    //  print_r($results);

    global $woocommerce, $post;
    echo '<div class="product_custom_field">';
    // Custom Product Text Field

    woocommerce_wp_text_input(
        array(
            'id' => 'product_id',           
            'label' => __('產品編號', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['product_id']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'product_eng_name',           
            'label' => __('產品英文名', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['product_eng_name']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'cuft',           
            'label' => __('CUFT', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['cuft']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'net_weight',           
            'label' => __('淨重', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['net_weight']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'gross_weight',           
            'label' => __('總重', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['gross_weight']
        )
    );



    echo '</div>';
}


// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields_save($post_id)
{
    // Custom Product Text Field
    $woocommerce_custom_product_text_field = $_POST['_custom_product_text_field'];
    if (!empty($woocommerce_custom_product_text_field))
        update_post_meta($post_id, '_custom_product_text_field', esc_attr($woocommerce_custom_product_text_field));
    // Custom Product Number Field
    $woocommerce_custom_product_number_field = $_POST['_custom_product_number_field'];
    if (!empty($woocommerce_custom_product_number_field))
        update_post_meta($post_id, '_custom_product_number_field', esc_attr($woocommerce_custom_product_number_field));
    // Custom Product Textarea Field
    $woocommerce_custom_procut_textarea = $_POST['_custom_product_textarea'];
    if (!empty($woocommerce_custom_procut_textarea))
        update_post_meta($post_id, '_custom_product_textarea', esc_html($woocommerce_custom_procut_textarea));
}