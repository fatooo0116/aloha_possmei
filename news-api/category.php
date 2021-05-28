<?php
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/category_api', array(
    'methods' => 'POST',
    'callback' => 'category_api_handler',
  ) );
});
function category_api_handler($data){
  
  $ck1 = (isset($data['check1'])) ? $data['check1'] : 0; 
  $ck2 = (isset($data['check2'])) ? $data['check2'] : 0; 
  
  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;
  $error = [];

  foreach($ck1 as $item){
    // echo $item;
     
    $sql = "SELECT * FROM $table_name WHERE product_id='".$item."'";
    $results = $wpdb->get_results($sql,ARRAY_A);
    if(count($results)>0){
       //  print_r($ck2);
        
       if($results[0]['woo_id']>0){
        if($ck2){
            wp_set_object_terms( $results[0]['woo_id'], $ck2,'product_cat');
        }
       }else{
            $error[]= array( 'info'=> $item.'-woo_id為0', 'data'=> $results);
       }

        
    }else{
        $error[]= array( 'info'=>$item.'-找不到產品', 'data'=> $results);
    }
  }


  
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