<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/imgupload', array(
      'methods' => 'POST',
      'callback' => 'imgupload_api_handler',
    ) );
  });
  function imgupload_api_handler($data){
    

    $ck1 = (isset($data['check1'])) ? $data['check1'] : 0; 
    // $ck2 = (isset($data['check2'])) ? $data['check2'] : 0; 
    
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';;
    $error = [];
  
    foreach($ck1 as $item){
      // echo $item;
       
      $sql = "SELECT * FROM $table_name WHERE product_id='".$item."'";
      $results = $wpdb->get_results($sql,ARRAY_A);
      if(count($results)>0){
         //  print_r($ck2);
        
        $file = "http://cargo.com/products/".$results[0]['product_id']."%20".$results[0]['product_name']."%20.jpg";

        echo $file;

        if($results[0]['woo_id']){

            // $attachmentId = media_handle_sideload($file, $results[0]['woo_id']);
        }
        


          
      }else{
          $error[]= array( 'info'=>$item.'-找不到產品', 'data'=> $results);
      }
    }

   
   // print_r($data);
   if(count($error)>0){
    $out = array(
        'status' => 0,
            'error' => $error,
        );
    }else{
        $out = array(
            'status' => 1        
        );
    }   
    return  $out;
  }

