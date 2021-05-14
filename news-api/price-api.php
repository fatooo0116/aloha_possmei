<?php










add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/get_price', array(
      'methods' => 'POST',
      'callback' => 'get_staff_price_handler',
    ) );
  });
  function get_staff_price_handler($data){
    
    $cid = (isset($data['cid'])) ? $data['cid'] : 0; 
    $pid = (isset($data['pid'])) ? $data['pid'] : 0; 
  
    global $wpdb;
  
    $table_price =  $wpdb->prefix . 'cprice';;
  
  
  
    $sql = "SELECT * From ".$table_price." WHERE product_id='".$pid."' AND customer_id='".$cid."'";
    // $sql .= ' order by product_id ASC';
    $results = $wpdb->get_results($sql,ARRAY_A);
  
    // return $sql;

    if(!empty($results)){  
      return $results;
  
    }else{
      return 0;
    } 
   
  }






  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/update_price', array(
      'methods' => 'POST',
      'callback' => 'update_price_handler',
    ) );
  });

  function update_price_handler($data){
    $cid = (isset($data['cid'])) ? $data['cid'] : 0; 
    $pid = (isset($data['pid'])) ? $data['pid'] : 0; 
    $price = (isset($data['price'])) ? $data['price'] : 0; 

   //  $sql = "SELECT * FROM $table_name WHERE product_id=".$pid." AND customer_id=".$cid;
  // $sql .= ' order by product_id ASC';
    
  global $wpdb;

    if($cid!=0 | $pid!=0){

      $table_name =  $wpdb->prefix . 'cprice';;
      $sql = "SELECT * FROM $table_name WHERE product_id=".$pid." AND customer_id=".$cid;
      $count = $wpdb->get_var($sql);
      if($count>0){
        /*  Update */
      $result =   $wpdb->update(
                      $table_name,
                      array(
                        'price' => $price,                       
                      ), 
                      array(
                        'product_id'=> $pid,
                        'customer_id'=> $cid,
                      ));

      }else{
        /* Insert */
          $data =  array(
          'product_id'=> $pid,
          'price' => $price,      
          'customer_id'=> $cid,
        );     
        $result = $wpdb->insert($table_name,$data);
      }    
      
    }
    
   // $results = $wpdb->get_results($sql);
   if($result){
    return $price;
   }else{
    return 0;     
   }

   // return $result ;
  }






/* 
*    get price by  
     @ product id
     @ customer id
*/
  function get_price_by_customer($data){
    $cid = (isset($data['cid'])) ? $data['cid'] : 0; 
   // $pid = (isset($data['pid'])) ? $data['pid'] : 0; 


    if($cid!=0 | $pid!=0){
      $sql = "SELECT * FROM $table_name WHERE  customer_id=".$cid;
      $results = $wpdb->get_results($sql);
      return  $results;
    }else{
      return 0;
    }
  }