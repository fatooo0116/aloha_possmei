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
     