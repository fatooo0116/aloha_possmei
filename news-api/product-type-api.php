<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/get_ptype', array(
      'methods' => 'POST',
      'callback' => 'product_ptype_handler',
    ) );
  });
  function product_ptype_handler($data){
    
    $page = (isset($data['page'])) ? $data['page'] : 0; 
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product_type';;
  
    
    $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
    // $sql .= ' order by product_id ASC';
    $results = $wpdb->get_results($sql);
    if(!empty($results)){  
         return $results;

    }else{
      return 0;
    }
   
  }






/*  ===========   DEL  ===========  */
add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/del_ptype', array(
      'methods' => 'POST',
      'callback' => 'del_ptype_handler',
    ) );
  });
  function del_ptype_handler($data){
    
    $pid = (isset($data['checked'])) ? $data['checked'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product_type';;
    
    // $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
   // $result  = $wpdb->delete( $table_name, array( 'id' => $pid ) );
   $result = 0;
    foreach($pid as $in){
        $result  = $wpdb->delete( $table_name, array( 'id' => $in) );
    }

    return $result;
  }






  /*  ===========   Create  ===========  */
 add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/create_ptype', array(
      'methods' => 'POST',
      'callback' => 'create_ptype_handler',
    ) );
  });


  function create_ptype_handler($data){

    global $wpdb;
    $table_name =  $wpdb->prefix . 'product_type';

   //  $dep_id = (isset($data['dep_id'])) ? $data['dep_id'] : 0; 
   //  $dep_name = (isset($data['dep_name'])) ? $data['dep_name'] : 0;
   //  $dep_eng_name = (isset($data['dep_eng_name'])) ? $data['dep_eng_name'] : 0;
   //  $dep_other = (isset($data['dep_other'])) ? $data['dep_other'] : 0;
   //  print_r($dep_id);


   $obj = array(
    'type_id' => (isset($data['type_id'])) ? $data['type_id'] : 0,
    'type_name' => (isset($data['type_name'])) ? $data['type_name'] : '',
    'type_eng_name' => (isset($data['type_eng_name'])) ? $data['type_eng_name'] : '',
    'stock_account' => (isset($data['stock_account'])) ? $data['stock_account'] : '',
    'out_account' => (isset($data['out_account'])) ? $data['out_account'] : '',
    'in_account' => (isset($data['in_account'])) ? $data['in_account'] : '',
  );

    
    $result = $wpdb->insert($table_name , $obj);
    return   $obj;
  }














    /*  ===========   Edit  ===========  */
 add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/edit_ptype', array(
      'methods' => 'POST',
      'callback' => 'edit_ptype_handler',
    ) );
  });


  function edit_ptype_handler($data){

    // print_r($data);
    
    $obj = array(
      'type_id' => (isset($data['fields']['type_id'])) ? $data['fields']['type_id'] : 0,
      'type_name' => (isset($data['fields']['type_name'])) ? $data['fields']['type_name'] : '',
      'type_eng_name' => (isset($data['fields']['type_eng_name'])) ? $data['fields']['type_eng_name'] : '',
      'stock_account' => (isset($data['fields']['stock_account'])) ? $data['fields']['stock_account'] : '',
      'out_account' => (isset($data['fields']['out_account'])) ? $data['fields']['out_account'] : '',
      'in_account' => (isset($data['fields']['in_account'])) ? $data['fields']['in_account'] : '',
    );

    global $wpdb;
    $table_name =  $wpdb->prefix . 'product_type';;

      $result = $wpdb->update( $table_name, $obj, array('id' => $data['cur_id']) );
      return $data;
      
  }