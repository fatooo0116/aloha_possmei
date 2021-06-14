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
  
    
    $sql = "SELECT * FROM $table_name order by id Desc Limit ".($page-1)*$post_per_page.', '.$post_per_page;
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
    'staff_id'=> (isset($data['staff_id'])) ? $data['staff_id'] : '', 
    'dep_id'=> (isset($data['dep_id'])) ? $data['dep_id'] : '', 
    'staff_name'=> (isset($data['staff_name'])) ? $data['staff_name'] : '', 
    'staff_eng_name'=> (isset($data['staff_eng_name'])) ? $data['staff_eng_name'] : '', 
    'xgroup'=> (isset($data['xgroup'])) ? $data['xgroup'] : '', 
    'email'=> (isset($data['email'])) ? $data['email'] : '', 
    // 'woo_id'=> (isset($data['woo_id'])) ? $data['woo_id'] : 0, 
  ); 

  
  $result = $wpdb->insert($table_name , $in_data);

  if($result){
    return $new_id;
  }else{
    return 0;
  }  
}



    /*  ===========   Edit  ===========  */
    add_action( 'rest_api_init', function () {
      register_rest_route( 'cargo/v1', '/edit_staff', array(
        'methods' => 'POST',
        'callback' => 'cedit_staff_handler',
      ) );
    });
  
  
    function cedit_staff_handler($data){
  
      // print_r($data);
      
      $obj = array(    
        'staff_id'=> (isset($data['fields']['staff_id'])) ? $data['fields']['staff_id'] : '', 
        'dep_id'=> (isset($data['fields']['dep_id'])) ? $data['fields']['dep_id'] : '', 
        'staff_name'=> (isset($data['fields']['staff_name'])) ? $data['fields']['staff_name'] : '', 
        'staff_eng_name'=> (isset($data['fields']['staff_eng_name'])) ? $data['fields']['staff_eng_name'] : '', 
        'xgroup'=> (isset($data['fields']['xgroup'])) ? $data['fields']['xgroup'] : '', 
        'email'=> (isset($data['fields']['email'])) ? $data['fields']['email'] : '', 
      );
  
      global $wpdb;
      $table_name =  $wpdb->prefix . 'comp_staff';;
  
        $result = $wpdb->update( $table_name, $obj, array('id' => $data['cur_id']) );
        return $data;
        
    }