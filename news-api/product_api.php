<?php
/*
add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/product_layout', array(
      'methods' => 'POST',
      'callback' => 'product_layout_handler',
    ) );
  });
  function product_layout_handler($data){
    
    $term_id = (isset($data['term_id'])) ? $data['term_id'] : 0; 
    $layout = (isset($data['layout'])) ? $data['layout'] : 0; 
  
    
    global $wpdb;
    $table_price =  $wpdb->prefix . 'cprice';;
    $sql = "SELECT * From ".$table_price." WHERE product_id='".$pid."' AND customer_id='".$cid."'";    
    $results = $wpdb->get_results($sql,ARRAY_A);
    

    return "dasdasdas";

    if(!empty($results)){  
      return $results;
  
    }else{
      return 0;
    } 
   
  }
  */