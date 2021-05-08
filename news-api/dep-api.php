<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/get_dep', array(
      'methods' => 'POST',
      'callback' => 'get_dep_handler',
    ) );
  });
  function get_dep_handler($data){
    
    $page = (isset($data['page'])) ? $data['page'] : 0; 
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'comp_dep';;
  
    
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
    register_rest_route( 'cargo/v1', '/del_dep', array(
      'methods' => 'POST',
      'callback' => 'del_dep_handler',
    ) );
  });
  function del_dep_handler($data){
    
    $pid = (isset($data['checked'])) ? $data['checked'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'comp_dep';;
    

    foreach($pid as $in){
        $result  = $wpdb->delete( $table_name, array( 'id' => $in['id']) );
    }


    if($result){        
      return $result;  
        
    }else{
        return 0;
    }
  }



  /*  ===========   Create  ===========  */
 add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/create_dep', array(
      'methods' => 'POST',
      'callback' => 'create_dep_handler',
    ) );
  });


  function create_dep_handler($data){

    global $wpdb;
    $table_name =  $wpdb->prefix . 'comp_dep';;

  //  $dep_id = (isset($data['dep_id'])) ? $data['dep_id'] : 0; 
  //  $dep_name = (isset($data['dep_name'])) ? $data['dep_name'] : 0;
  //  $dep_eng_name = (isset($data['dep_eng_name'])) ? $data['dep_eng_name'] : 0;
  //  $dep_other = (isset($data['dep_other'])) ? $data['dep_other'] : 0;
  
    // print_r($dep_id );

    
    $result = $wpdb->insert($table_name , array(     
        'dep_id' => (isset($data['dep_id'])) ? $data['dep_id'] : 0,
        'dep_name' => (isset($data['dep_name'])) ? $data['dep_name'] : ''      
    ));
    return  $result;
  }











    /*  ===========   Edit  Get ===========  */

    add_action( 'rest_api_init', function () {
        register_rest_route( 'cargo/v1', '/get_dep_by', array(
          'methods' => 'POST',
          'callback' => 'get_dep_by_handler',
        ) );
      });
    
    
      function get_dep_by_handler($data){
         
          $pid = (isset($data['pid'])) ? $data['pid'] : 0; 
         
        
          global $wpdb;
          $table_name =  $wpdb->prefix . 'comp_dep';;
        
          
          $sql = "SELECT * FROM $table_name where id=".$pid;
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


    /*  ===========   Edit  ===========  */
 add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/edit_dep', array(
      'methods' => 'POST',
      'callback' => 'edit_dep_handler',
    ) );
  });


  function edit_dep_handler($data){

    // print_r($data);
    
    $obj = array(
      'dep_id' => (isset($data['fields']['dep_id'])) ? $data['fields']['dep_id'] : 0,
      'dep_name' => (isset($data['fields']['dep_name'])) ? $data['fields']['dep_name'] : ''
    );

    global $wpdb;
    $table_name =  $wpdb->prefix . 'comp_dep';;

      $result = $wpdb->update( $table_name, $obj, array('id' => $data['cur_id']) );
      return $data;
      
  }