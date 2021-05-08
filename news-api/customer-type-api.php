<?php
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/get_customers_type', array(
    'methods' => 'POST',
    'callback' => 'get_customers_type_handler',
  ) );
});
function get_customers_type_handler($data){
  
  $page = (isset($data['page'])) ? $data['page'] : 0; 
  $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_type';;

  
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
  register_rest_route( 'cargo/v1', '/del_customers_type', array(
    'methods' => 'POST',
    'callback' => 'del_customers_type_handler',
  ) );
});
function del_customers_type_handler($data){
  
  $pid = (isset($data['checked'])) ? $data['checked'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_type';;
  
  // $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
  foreach($pid as $in){

    $wpdb->delete( $table_name, array( 'id' => $in['id']) );    

  }

  // return $result;
}






  /*  ===========   Create  ===========  */
  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/create_customers_type', array(
      'methods' => 'POST',
      'callback' => 'create_customers_type_handler',
    ) );
  });


  function create_customers_type_handler($data){

    global $wpdb;
    $table_name =  $wpdb->prefix . 'customer_type';;


    $result = $wpdb->insert($table_name , array(     
        'customer_catgory_id' => (isset($data['customer_catgory_id'])) ? $data['customer_catgory_id'] : 0,
        'customer_catgory_name' => (isset($data['customer_catgory_name'])) ? $data['customer_catgory_name'] : '',
        'customer_catgory_eng_name' => (isset($data['customer_catgory_eng_name'])) ? $data['customer_catgory_eng_name'] : '',
        'other' => (isset($data['other'])) ? $data['other'] : ''      
    ));

    return  $result;
  }








/*  ===========   Edit  ===========  */
 add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/edit_customers_type', array(
    'methods' => 'POST',
    'callback' => 'edit_customers_handler',
  ) );
});

function edit_customers_handler($data){

  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_type';;
  
  $obj = array(
    'customer_catgory_id' => (isset($data['fields']['customer_catgory_id'])) ? $data['fields']['customer_catgory_id'] : 0,
    'customer_catgory_name' => (isset($data['fields']['customer_catgory_name'])) ? $data['fields']['customer_catgory_name'] : '',
    'customer_catgory_eng_name' => (isset($data['fields']['customer_catgory_eng_name'])) ? $data['fields']['customer_catgory_eng_name'] : '',
    'other' => (isset($data['fields']['other'])) ? $data['fields']['other'] : ''      
  );

  $result = $wpdb->update( $table_name, $obj, array('id' => $data['cur_id']) );
  return $data;
    
}


