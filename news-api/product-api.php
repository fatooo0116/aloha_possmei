<?php
add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/get_products', array(
      'methods' => 'POST',
      'callback' => 'get_products_handler',
    ) );
  });
  function get_products_handler($data){
    
    $page = (isset($data['page'])) ? $data['page'] : 0; 
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';;
  
    $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
    // $sql .= ' order by product_id ASC';
    $results = $wpdb->get_results($sql,ARRAY_A);


    foreach($results as $key => $item){
        
        if($item['woo_id']){
            
            if(FALSE === get_post_status( $item['woo_id'])){
                $results[$key]['woo_id'] = 0;
            }
        }        
    }


  
    if(!empty($results)){  
        return $results;
      // return  $page.' '.$post_per_page ;
       // return $sql;
    }else{
      return 0;
    }   
  }







  /* 
   * ###################   bind_woo_products By Page  ###################  
  */

  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/bind_woo_prod_by_page', array(
      'methods' => 'POST',
      'callback' => 'bind_woo_prod_page_handler',
    ) );
  });
  function bind_woo_prod_page_handler($data){

    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';;

    $pid = (isset($data['checked'])) ? $data['checked'] : 0; 


    foreach($pid as $item){
     
        if(FALSE === get_post_status( $item['woo_id'])){
          /*  create woo product  [start] */
            $post_id = wp_insert_post( array( 
              'post_title' => $item['product_name'],
              'post_type' => 'product',
              'post_status' => 'publish'
            ));         
            wp_set_object_terms( $post_id, 'simple', 'product_type' );        
            update_post_meta( $post_id, '_regular_price', '0' );

            if($post_id){    
              // global $wpdb;
              $table_name =  $wpdb->prefix . 'product';;
              $updated = $wpdb->update( $table_name,
                      array('woo_id' => $post_id), 
                      array('id' => $item['id']));
              $out[] =  $post_id;       
            }  
            /*  create woo product  [end] */
        }

    }
  }








  /* 
   * ###################   bind_woo_products ###################  
   * */

  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/bind_woo_products', array(
      'methods' => 'POST',
      'callback' => 'bind_woo_products_handler',
    ) );
  });
  function bind_woo_products_handler($data){
    
 
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';;
    $sql = "SELECT * FROM ".$table_name;
    $results = $wpdb->get_results($sql);
    $out = array();

    $i=1;
    foreach($results as $item){

      if($i<1000){
        /*  create woo product  [start] */
          $post_id = wp_insert_post( array( 
            'post_title' => $item->product_name,
            'post_type' => 'product',
            'post_status' => 'publish'
          ));         
          wp_set_object_terms( $post_id, 'simple', 'product_type' );        
          update_post_meta( $post_id, '_regular_price', '0' );

          if($post_id){    
            // global $wpdb;
            $table_name =  $wpdb->prefix . 'product';;
            $updated = $wpdb->update( $table_name,
                    array('woo_id' => $post_id), 
                    array('id' => $item->id));
            $out[] =  $post_id;       
          }  
        /*  create woo product  [end] */

      }
      $i++;   
    }

    return $out;
    
  }


/* =======================   ==========================   */



















/*  ===========   DEL  ===========  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/del_product', array(
    'methods' => 'POST',
    'callback' => 'del_product_handler',
  ) );
});
function del_product_handler($data){
  
  $pid = (isset($data['checked'])) ? $data['checked'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;
  
   // $sql = "SELECT woo_id FROM $table_name WHERE  id=".$in;
   // $result  = $wpdb->delete( $table_name, array( 'id' => $pid ) );

  foreach($pid as $in){
   
    if($in['woo_id']>0){
      $result = wp_delete_post($in['woo_id']);
      
      
      if($result){
        $wpdb->delete( $table_name, array( 'id' => $in['id'] ));
      }
      
    }else{ /*  woo_id == 0 */
         $wpdb->delete( $table_name, array( 'id' => $in['id']) );
    }
  }


}






/*  ===========   Create  ===========  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/create_product', array(
    'methods' => 'POST',
    'callback' => 'create_product_handler',
  ) );
});


function create_product_handler($data){

  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;

  $in_data = array(
    'product_id' => (isset($data['fields']['product_id'])) ? $data['fields']['product_id'] : '', 
    'type_name' => (isset($data['fields']['type_name'])) ? $data['fields']['type_name'] : '', 
    'unit_sn' => (isset($data['fields']['unit_sn'])) ? $data['fields']['unit_sn'] : '', 
    'unit_sn_cht' => (isset($data['fields']['unit_sn_cht'])) ? $data['fields']['unit_sn_cht'] : '', 
    'product_name'=> (isset($data['fields']['product_name'])) ? $data['fields']['product_name'] : '', 
    'invoice_name'=> (isset($data['fields']['invoice_name'])) ? $data['fields']['invoice_name'] : '', 
    'product_eng_name'=> (isset($data['fields']['product_eng_name'])) ? $data['fields']['product_eng_name'] : '', 
    'money_type'=> (isset($data['fields']['money_type'])) ? $data['fields']['money_type'] : '', 
    'price'=> (isset($data['fields']['price'])) ? $data['fields']['price'] : '', 
    'out_pack'=> (isset($data['fields']['out_pack'])) ? $data['fields']['out_pack'] : '', 
    'out_pack_unit'=> (isset($data['fields']['out_pack_unit'])) ? $data['fields']['out_pack_unit'] : '', 
    'in_pack'=> (isset($data['fields']['in_pack'])) ? $data['fields']['in_pack'] : '', 
    'in_pack_unit'=> (isset($data['fields']['in_pack_unit'])) ? $data['fields']['in_pack_unit'] : '', 
    'cuft'=> (isset($data['fields']['cuft'])) ? $data['fields']['cuft'] : '', 
    'net_weight'=> (isset($data['fields']['net_weight'])) ? $data['fields']['net_weight'] : '', 
    'gross_weight'=> (isset($data['fields']['gross_weight'])) ? $data['fields']['gross_weight'] : '', 
    'weight_unit'=> (isset($data['fields']['weight_unit'])) ? $data['fields']['weight_unit'] : '', 
    'meant'=> (isset($data['fields']['meant'])) ? $data['fields']['meant'] : '', 
    // 'woo_id'=> (isset($data['woo_id'])) ? $data['woo_id'] : 0, 
  ); 

  
  $result = $wpdb->insert($table_name ,$in_data );
  $new_id = $wpdb->insert_id;

  if($new_id){

        /*  create woo product  [start] */
        $post_id = wp_insert_post( array( 
          'post_title' => (isset($data['fields']['product_name'])) ? $data['fields']['product_name'] : '', 
          'post_type' => 'product',
          'post_status' => 'publish'
        ));         
        wp_set_object_terms( $post_id, 'simple', 'product_type' );        
        update_post_meta( $post_id, '_regular_price', '0' );

        if($post_id){    
          // global $wpdb;
          $table_name =  $wpdb->prefix . 'product';;
          $updated = $wpdb->update( $table_name,
                  array('woo_id' => $post_id), 
                  array('id' => $new_id));      
                  

            /*  update image */        
            if($data['attachment_id']){
              $result =  set_post_thumbnail( $post_id, $data['attachment_id'] );
            }
            if($data['ptype_checked']){
              wp_set_object_terms( $post_id, $data['ptype_checked'],'product_cat');
            }
        }  

       /*  create woo product  [end] */
  }




  if($result){
    return $new_id;
  }else{
    return 0;
  }  
}




  /*  ===========   Edit  ===========  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/edit_product', array(
    'methods' => 'POST',
    'callback' => 'edit_product_handler',
  ) );
});




function edit_product_handler($data){

  global $wpdb;
  $obj = array(
    'product_id' => (isset($data['fields']['product_id'])) ? $data['fields']['product_id'] : 0, /* 客戶編號 */
    'type_name' => (isset($data['fields']['type_name'])) ? $data['fields']['type_name'] : '', /* 帳款歸屬 */
    'unit_sn' => (isset($data['fields']['unit_sn'])) ? $data['fields']['unit_sn'] : '',  /* 客戶全稱 */
    'unit_sn_cht' => (isset($data['fields']['unit_sn_cht'])) ? $data['fields']['unit_sn_cht'] : '', 
    'product_name'=> (isset($data['fields']['product_name'])) ? $data['fields']['product_name'] : '', 
    'invoice_name'=> (isset($data['fields']['invoice_name'])) ? $data['fields']['invoice_name'] : '', 
    'product_eng_name'=> (isset($data['fields']['product_eng_name'])) ? $data['fields']['product_eng_name'] : '', 
    'money_type'=> (isset($data['fields']['money_type'])) ? $data['fields']['money_type'] : '', 
    'price'=> (isset($data['fields']['price'])) ? $data['fields']['price'] : '', 
    'out_pack'=> (isset($data['fields']['out_pack'])) ? $data['fields']['out_pack'] : '', 
    'out_pack_unit'=> (isset($data['fields']['out_pack_unit'])) ? $data['fields']['out_pack_unit'] : '', 
    'in_pack'=> (isset($data['in_pack'])) ? $data['in_pack'] : '', 
    'in_pack_unit'=> (isset($data['fields']['in_pack_unit'])) ? $data['fields']['in_pack_unit'] : '', 
    'cuft'=> (isset($data['fields']['cuft'])) ? $data['fields']['cuft'] : '', 
    'net_weight'=> (isset($data['fields']['net_weight'])) ? $data['fields']['net_weight'] : '', 
    'gross_weight'=> (isset($data['fields']['gross_weight'])) ? $data['fields']['gross_weight'] : '', 
    'weight_unit'=> (isset($data['fields']['weight_unit'])) ? $data['fields']['weight_unit'] : '', 
    'meant'=> (isset($data['fields']['meant'])) ? $data['fields']['meant'] : '',      
    'woo_id'=> (isset($data['fields']['woo_id'])) ? $data['fields']['woo_id'] : '', 
  );
    $table_name =  $wpdb->prefix . 'product';;
    $result = $wpdb->update( $table_name, $obj, array('id' => $data['cur_id']) );

    /*  Update the woo post  */
    if($data['fields']['woo_id']){
      $post_id = $data['fields']['woo_id'];
      $checked_ptype = $data['ptype_checked'];
      if($post_id){
        if($checked_ptype){
          wp_set_object_terms( $post_id, $checked_ptype,'product_cat');
        }else{
          wp_set_object_terms( $post_id, array(),'product_cat');
        }
      }      
      
    }
    


    return $result;
}







/*  ============================= update_product_img   =============================  */
add_action( 'rest_api_init', function ($data) {
  register_rest_route( 'cargo/v1', '/upload_product_img', array(
    'methods' => 'POST',
    'callback' => 'upload_product_img_handler',
  ) );
});


function upload_product_img_handler($data){

 
  $result = 0;

  if((isset($data['woo_post_id'])) & (isset($data['attachment_id']))){
    $result =  set_post_thumbnail($data['woo_post_id'],$data['attachment_id'] );
  }

  $img = get_the_post_thumbnail_url($data['woo_post_id'],'full');
 
  if($result){
    return $img;
  }else{
    return $result;
  }  
}





/*  ============================= update_product_img   =============================  */
add_action( 'rest_api_init', function ($data) {
  register_rest_route( 'cargo/v1', '/get_product_img_and_cat', array(
    'methods' => 'POST',
    'callback' => 'get_product_img_and_cat_handler',
  ) );
});
function get_product_img_and_cat_handler($data){

  $terms = get_the_terms( $data['woo_id'], 'product_cat' );
  
  foreach($terms as $term){
    $all_terms_names[] = $term->name;
  }

  $out = array(
    'post_img' => get_the_post_thumbnail_url($data['woo_id'],'full'),
    'product_cat' =>  $all_terms_names
  );

  // return $data['woo_id'];
  return $out;
}




/*   =============================   類別取得   =============================  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/terms', array(
    'methods' => 'POST',
    'callback' => 'get_product_cat_func',
  ) );
} );

function get_product_cat_func( $data ) {

  $terms = get_terms( array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
  )); 


  
  
    $output = array();
    $all = array();
    $dangling = array();


    foreach ($terms as $entry) {

      $temp  = array(
        "term_id" => $entry -> term_id,
        "term_name" => $entry -> name,
        "parent" => $entry -> parent,
        "count" => $entry -> count
      );

      $id = $entry->term_id;
     
      if($entry->parent == "0") {
         $all[$id] = $temp;
        $output[] =& $all[$id];  
      } else {
         $dangling[$id] = $temp;
      }
    }

    
    while (count($dangling) > 0) {
      foreach($dangling as $entry) {
          $id = $entry['term_id'];
          $pid = $entry['parent'];


          if (isset($all[$pid])) {            
              $all[$id] = $entry;
              $all[$pid]['children'][] =& $all[$id]; 
              unset($dangling[$entry['term_id']]);
          }
      }
    }
  


  return $output;
} 