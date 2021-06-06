<?php 



  /* 
   * ###################   bind_woo_products By Page  ###################  
  */

  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/bind_woo_prod_by_page', array(
      'methods' => 'POST',
      'callback' => 'bind_woo_prod_page_handler',
    ) );
  });
  function bind_woo_prod_page_handler($data){

    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';;

    $pid = (isset($data['checked'])) ? $data['checked'] : 0; 


    foreach($pid as $item){
     
        if(FALSE === get_post_status( $item['woo_id'])){
          /*  create woo product  [start] */
            $post_id = wp_insert_post( array( 
              'post_title' => $item['product_name'],
              'post_type' => 'product',
              'post_status' => 'publish'
            ));         
            wp_set_object_terms( $post_id, 'simple', 'product_type' );        
            update_post_meta( $post_id, '_regular_price', '8888' );
            update_post_meta( $post_id, '_price', '8888' );

           
            wp_set_object_terms( $post_id, $item['type_name'],'product_cat');


            if($post_id){    
              // global $wpdb;
              $table_name =  $wpdb->prefix . 'product';;
              $updated = $wpdb->update( $table_name,
                      array('woo_id' => $post_id), 
                      array('id' => $item['id']));
              $out[] =  $post_id;       
            }  
            /*  create woo product  [end] */
        }

    }
  }




add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/get_products_ajax', array(
    'methods' => 'POST',
    'callback' => 'get_products_ajax_handler',
  ) );
});
function get_products_ajax_handler($data){
  
  $page = (isset($data['page'])) ? $data['page'] : 0; 
  $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;

  $sql = "SELECT * FROM $table_name order by id DESC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
  // $sql .= ' order by product_id ASC';
  $results = $wpdb->get_results($sql,ARRAY_A);




  foreach($results as $key => $item){
    
      
      if($item['woo_id']){
         $results[$key]['img'] = get_the_post_thumbnail_url($item['woo_id'],'full');
          
          if(FALSE === get_post_status( $item['woo_id'])){
              $results[$key]['woo_id'] = 0;
          }else{
            $cat = get_the_terms($item['woo_id'],'product_cat');   
            
            $text = array();
            if($cat){
              foreach($cat as $ct){
                $text[]= $ct->name;
              }
            }
            $results[$key]['cat'] = implode(' > ',$text);
          }
      }        
  }

  $sql2 = "SELECT count(*) FROM $table_name";
  $result_count = $wpdb->get_var($sql2);

  $out = array(
    'results' => $results,
    'count' => $result_count
  );


  if(!empty($results)){  
      return $out;
    // return  $page.' '.$post_per_page ;
     // return $sql;
  }else{
    return 0;
  }   
}






add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/sort_products_ajax', array(
    'methods' => 'POST',
    'callback' => 'sort_products_ajax_handler',
  ) );
});


function sort_products_ajax_handler($data){

  $column = (isset($data['column'])) ? $data['column'] : ''; 
  $dir = (isset($data['dir'])) ? $data['dir'] : 0; 
  $post_per_page = (isset($data['perpage'])) ? $data['perpage'] : 0; 
  $page = (isset($data['page'])) ? $data['page'] : 0; 

  $filterText = (isset($data['filterText'])) ? $data['filterText'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;

  if($filterText){  /* 搜尋模式 */
    // $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
    // $sql = "SELECT * FROM ".$table_name." WHERE MATCH(product_id,product_name) AGAINST('".$filterText."' IN BOOLEAN MODE )order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
    $sql =  "SELECT * FROM  ".$table_name." WHERE  product_id LIKE '%".$filterText."%' OR product_name LIKE '%".$filterText."%'  OR product_id LIKE '".$filterText."%'  OR product_name LIKE '".$filterText."%'order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
  }else{  /* 一般模式 */

    if($column){    
      $sql = "SELECT * FROM $table_name order by ".$column." ".$dir." Limit ".($page-1)*$post_per_page.', '.$post_per_page;  
    }else{
      $sql = "SELECT * FROM $table_name order by id DESC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
    }  
  }

   // return $sql;

  // $sql .= ' order by product_id ASC';
  $results = $wpdb->get_results($sql,ARRAY_A);



  foreach($results as $key => $item){          
    if($item['woo_id']){
        
      $results[$key]['img'] = get_the_post_thumbnail_url($item['woo_id'],'full');

        
        if(FALSE === get_post_status( $item['woo_id'])){
            $results[$key]['woo_id'] = 0;
        }else{
          $cat = get_the_terms($item['woo_id'],'product_cat');   
          
          $text = array();
          if($cat){
            foreach($cat as $ct){
              $text[]= $ct->name;
            }
            }
            $results[$key]['cat'] = implode(' > ',$text);
          }
      }        
  }

  if($filterText){
    $sql2 = "SELECT count(*) FROM  ".$table_name." WHERE  product_id LIKE '%".$filterText."%' OR product_name LIKE '%".$filterText."%'";
  }else{
    $sql2 = "SELECT count(*) FROM $table_name";
  }
  
  $result_count = $wpdb->get_var($sql2);

  $out = array(
    'results' => $results,
    'count' => $result_count
  );



  if(!empty($results)){  
    return $out;

  }else{
    return 0;
  }  
}






add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/search_products_ajax', array(
    'methods' => 'POST',
    'callback' => 'search_products_ajax_handler',
  ) );
});


function search_products_ajax_handler(){
  

  $page = (isset($data['page'])) ? $data['page'] : 0; 
  $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 
  $post_per_page = (isset($data['perpage'])) ? $data['perpage'] : 0; 


  $page = 1;

  $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
  // $sql .= ' order by product_id ASC';
  $results = $wpdb->get_results($sql,ARRAY_A);


  if(!empty($results)){  
    return $out;

  }else{
    return 0;
  }  
}