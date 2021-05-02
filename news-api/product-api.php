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
          $post_id = wp_insert_post( array( 
            'post_title' => $item->product_name,
            'post_type' => 'product',
            'post_status' => 'publish'
          ));
          // update_post_meta( $post_id, '_eng_name', $data['product_eng_name'] );
          wp_set_object_terms( $post_id, 'simple', 'product_type' );
        
          update_post_meta( $post_id, '_price', '8888' );

          if($post_id){    
            // global $wpdb;
            $table_name =  $wpdb->prefix . 'product';;
            $updated = $wpdb->update( $table_name,
                    array('woo_id' => $post_id), 
                    array('id' => $item->id));
            $out[] =  $post_id;       
          }   
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
  
  // $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
  // $result  = $wpdb->delete( $table_name, array( 'id' => $pid ) );

  foreach($pid as $in){
    $result  = $wpdb->delete( $table_name, array( 'id' => $in) );
  }

  return $result;
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
    'product_id' => (isset($data['product_id'])) ? $data['product_id'] : '', 
    'type_name' => (isset($data['type_name'])) ? $data['type_name'] : '', 
    'unit_sn' => (isset($data['unit_sn'])) ? $data['unit_sn'] : '', 
    'unit_sn_cht' => (isset($data['unit_sn_cht'])) ? $data['unit_sn_cht'] : '', 
    'product_name'=> (isset($data['product_name'])) ? $data['product_name'] : '', 
    'invoice_name'=> (isset($data['invoice_name'])) ? $data['invoice_name'] : '', 
    'product_eng_name'=> (isset($data['product_eng_name'])) ? $data['product_eng_name'] : '', 
    'money_type'=> (isset($data['money_type'])) ? $data['money_type'] : '', 
    'price'=> (isset($data['price'])) ? $data['price'] : '', 
    'out_pack'=> (isset($data['out_pack'])) ? $data['out_pack'] : '', 
    'out_pack_unit'=> (isset($data['out_pack_unit'])) ? $data['out_pack_unit'] : '', 
    'in_pack'=> (isset($data['in_pack'])) ? $data['in_pack'] : '', 
    'in_pack_unit'=> (isset($data['in_pack_unit'])) ? $data['in_pack_unit'] : '', 
    'cuft'=> (isset($data['cuft'])) ? $data['cuft'] : '', 
    'net_weight'=> (isset($data['net_weight'])) ? $data['net_weight'] : '', 
    'gross_weight'=> (isset($data['gross_weight'])) ? $data['gross_weight'] : '', 
    'weight_unit'=> (isset($data['weight_unit'])) ? $data['weight_unit'] : '', 
    'meant'=> (isset($data['meant'])) ? $data['meant'] : '', 
    // 'woo_id'=> (isset($data['woo_id'])) ? $data['woo_id'] : 0, 
  ); 


  $result = $wpdb->insert($table_name ,$in_data );

  if($result){
    return $wpdb->insert_id;
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
    'woo_id'=> (isset($data['woo_id'])) ? $data['woo_id'] : '', 
  );
    $table_name =  $wpdb->prefix . 'product';;
    $result = $wpdb->update( $table_name, $obj, array('id' => $data['cur_id']) );
    return $result;
}