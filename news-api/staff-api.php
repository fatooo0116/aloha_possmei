<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/get_staff', array(
      'methods' => 'POST',
      'callback' => 'get_staff_handler',
    ) );
  });
  function get_staff_handler($data){
    
    $page = (isset($data['page'])) ? $data['page'] : 0; 
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'comp_staff';;
  
    
    $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
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



  /*  ===========   DEL  ===========  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/del_staff', array(
    'methods' => 'POST',
    'callback' => 'del_staff_handler',
  ) );
});
function del_staff_handler($data){
  
  $pid = (isset($data['checked'])) ? $data['checked'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'comp_staff';;
  
   // $sql = "SELECT woo_id FROM $table_name WHERE  id=".$in;
   // $result  = $wpdb->delete( $table_name, array( 'id' => $pid ) );

  foreach($pid as $in){
   
         $wpdb->delete( $table_name, array( 'id' => $in['id']) );
    
  }


}






/*  ===========   Create  ===========  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/create_staff', array(
    'methods' => 'POST',
    'callback' => 'create_staff_handler',
  ) );
});


function create_staff_handler($data){

  global $wpdb;
  $table_name =  $wpdb->prefix . 'comp_staff';;

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

  

  if($result){
    return $new_id;
  }else{
    return 0;
  }  
}
