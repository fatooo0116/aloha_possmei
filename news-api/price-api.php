<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/get_price', array(
      'methods' => 'POST',
      'callback' => 'get_staff_price_handler',
    ) );
  });
  function get_staff_price_handler($data){
    
    $cid = (isset($data['cid'])) ? $data['cid'] : 0; 

    
    // $page = (isset($data['page'])) ? $data['page'] : 0; 
    // $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'cprice';;
  
    
    $sql = "SELECT * FROM $table_name WHERE product_id=".$cid;
    // $sql .= ' order by product_id ASC';
    
    $results = $wpdb->get_results($sql);
    if(!empty($results)){  
        return $results;
      // return  $page.' '.$post_per_page ;
       // return $sql;
    }else{
      return 0;
    }
   
  }



  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/update_price', array(
      'methods' => 'POST',
      'callback' => 'update_price_handler',
    ) );
  }
  function get_staff_price_handler($data){
    $cid = (isset($data['cid'])) ? $data['cid'] : 0; 
    $product_id = (isset($data['product_id'])) ? $data['product_id'] : 0; 
    
  }