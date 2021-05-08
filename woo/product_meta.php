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
            'id' => 'meta_product_id',           
            'label' => __('產品編號', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['product_id']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'meta_product_eng_name',           
            'label' => __('產品英文名', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['product_eng_name']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'meta_cuft',           
            'label' => __('CUFT', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['cuft']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'meta_net_weight',           
            'label' => __('淨重', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['net_weight']
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => 'meta_gross_weight',           
            'label' => __('總重', 'woocommerce'),
            'desc_tip' => 'true',
            'value' => $results[0]['gross_weight']
        )
    );



    echo '</div>';


       /*  SAVE woo_id  is   customer not exist  */
   global $wpdb, $post;
   $table_name =  $wpdb->prefix . 'product';;
   $result = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE woo_id = '$post->ID'");
   print_r($result);
}


// Save Fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields_save($post_id)
{
    $update_obj = array();
    // Custom Product Text Field
    $meta_product_id = $_POST['meta_product_id'];
    if (!empty($meta_product_id)){
        $update_obj['product_id'] = $meta_product_id;
    }

    $meta_product_eng_name = $_POST['meta_product_eng_name'];
    if (!empty($meta_product_eng_name)){
        $update_obj['product_eng_name'] = $meta_product_eng_name;
    }

    $meta_cuft = $_POST['meta_cuft'];
    if (!empty($meta_cuft)){
        $update_obj['cuft'] = $meta_cuft;
    }

    $meta_net_weight = $_POST['meta_net_weight'];
    if (!empty($meta_net_weight)){
        $update_obj['net_weight'] = $meta_net_weight;
    }


    $meta_gross_weight = $_POST['meta_gross_weight'];
    if (!empty($meta_gross_weight)){
        $update_obj['gross_weight'] = $meta_gross_weight;
    }

   global $wpdb, $post;
   $table_name =  $wpdb->prefix . 'product';;

   $count = $wpdb->get_var("SELECT COUNT(*) FROM ".$table_name." where woo_id=".$post_id); 
   if($count){
     /*  Woo update  => Update React  */

    $sql = "SELECT * FROM $table_name where woo_id=".$post_id;
    $results = $wpdb->update(
        $table_name,
        $update_obj,
        array('woo_id' => $post_id));
   }else{
    /*  Woo 新增  => 新增 React  */
    $wpdb->insert(
        $table_name, 
        array(
            'product_name' => get_the_title($post), 
            'woo_id' => $post->ID
        )
    );
   }

   // print_r($results);

}












/*  
 *    Woo  刪除同步
 */

add_action( 'admin_init', 'wpdocs_codex_sync_del');
function wpdocs_codex_sync_del() {
    add_action( 'delete_post', 'wpdocs_codex_sync', 10 );
}
 
function wpdocs_codex_sync($pid){

    global $wpdb;
    $table_name =  $wpdb->prefix.'product';;  
    $wpdb->delete(
        $table_name, // table to delete from
        array(
            'woo_id' => $pid // value in column to target for deletion
        ));    
}



/**
 *    react 新增  => Woo
 * 
 */



 /**
 *    react 刪除 => Woo  
 * 
 */