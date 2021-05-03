<?php
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/get_customers_addr', array(
    'methods' => 'POST',
    'callback' => 'get_customers_addr_handler',
  ) );
});
function get_customers_addr_handler($data){
  
  $cid = (isset($data['cid'])) ? $data['cid'] : 0; 
  // $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_address';;

  
  $sql = "SELECT * FROM $table_name WHERE customer_id='".$cid."'";
  // $sql .= ' order by product_id ASC';
  $results = $wpdb->get_results($sql);
  // return $sql;

  if(!empty($results)){  
     return $results;
    // return  $page.' '.$post_per_page ;
     // return $sql;
  }else{
    return 0;
  }
 
}


add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/del_customers_addr', array(
    'methods' => 'POST',
    'callback' => 'del_customers_addr_handler',
  ) );
});
function del_customers_addr_handler($data){

  $pid = (isset($data['checked'])) ? $data['checked'] : 0; 
  
  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_address';;

  // $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
  // foreach($pid as $in){
    $result  = $wpdb->delete( $table_name, array( 'id' => $pid) );
  // }

  return $result;
}



add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/create_customers_addr', array(
    'methods' => 'POST',
    'callback' => 'create_customers_addr_handler',
  ) );
});

function create_customers_addr_handler($data){
  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_address';;


      
  $result = $wpdb->insert($table_name , array(
    'customer_id' => (isset($data['customer_id'])) ? $data['customer_id'] : '', /* 客戶編號 */
    'addr_id' => (isset($data['addr_id'])) ? $data['addr_id'] : '', /* 帳款歸屬 */
    'address_text' => (isset($data['address_text'])) ? $data['address_text'] : '',  /* 客戶全稱 */
    'zip' => (isset($data['zip'])) ? $data['zip'] : '', /* 類別編號 */
    'contact'=> (isset($data['contact'])) ? $data['contact'] : '',
    'contact_title'=> (isset($data['contact_title'])) ? $data['contact_title'] : '',
    'contact_phone'=> (isset($data['contact_phone'])) ? $data['contact_phone'] : '',
    'contact_fax'=> (isset($data['contact_fax'])) ? $data['contact_fax'] : '',    
  ));

  return  $result;
}



add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/edit_customers_addr', array(
    'methods' => 'POST',
    'callback' => 'edit_customers_addr_handler',
  ) );
});
function edit_customers_addr_handler($data){
  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_address';;

  $tdata = array(
    'customer_id' => (isset($data['customer_id'])) ? $data['customer_id'] : '', /* 客戶編號 */
    'addr_id' => (isset($data['addr_id'])) ? $data['addr_id'] : '', /* 帳款歸屬 */
    'address_text' => (isset($data['address_text'])) ? $data['address_text'] : '',  /* 客戶全稱 */
    'zip' => (isset($data['zip'])) ? $data['zip'] : '', /* 類別編號 */
    'contact'=> (isset($data['contact'])) ? $data['contact'] : '',
    'contact_title'=> (isset($data['contact_title'])) ? $data['contact_title'] : '',
    'contact_phone'=> (isset($data['contact_phone'])) ? $data['contact_phone'] : '',
    'contact_fax'=> (isset($data['contact_fax'])) ? $data['contact_fax'] : '',     
  );

  
  $wpdb->update( $table_name, $tdata, array('id' => $data['id']) );
}