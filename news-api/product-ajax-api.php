<?php 


/*  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/open_hide_product', array(
    'methods' => 'POST',
    'callback' => 'open_hide_product_handler',
  ) );
});

function open_hide_product_handler($data){
  $xstate = $data['xstate']; 
  $pid = (isset($data['pid'])) ? $data['pid'] : 0; 

  echo $state;
  echo $pid;

  if($pid>0){
    $current_post = get_post( $pid, 'ARRAY_A' );
    $current_post['post_status'] = ($xstate)?'publish':'draft';
    return wp_update_post($current_post);
  }else{
    return 0;
  }
  
  // unhook this function so it doesn't loop infinitely
}





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
      $results[$key]['is_open'] = (get_post_status( $item['woo_id'])=="publish")? true : false;
      
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
    
    $results[$key]['is_open'] = (get_post_status( $item['woo_id'])=="publish")? true : false;

    
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
    'callback' => 'search_products_fe_ajax_handler',
  ) );
});


function search_products_fe_ajax_handler(){
  

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




/**   Fix  Product Price  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/product_fix_price', array(
    'methods' => 'POST',
    'callback' => 'product_fix_price_handler',
  ) );
});

function product_fix_price_handler(){  


  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;


  $out = array();
  $data = array();
  $px = array();
  $i = 0;

  $args = array(
    'post_type' => 'product',
    'posts_per_page' => 99999
    );
  $loop = new WP_Query( $args );
  if ( $loop->have_posts() ) {
    while ( $loop->have_posts() ) : $loop->the_post();

      // wc_get_template_part( 'content', 'product' );
      // update_post_meta( $post_id, '_regular_price', '8888' );


      $sql = "SELECT * FROM $table_name WHERE woo_id=".get_the_ID();
      $results = $wpdb->get_results($sql,ARRAY_A);



      if(get_post_meta( get_the_ID(), '_regular_price',true)!='8888'){
        $data[get_the_ID()] = get_post_meta( get_the_ID(), '_regular_price',true);       
      }

      // $px[] = $results[0]["product_id"];            
      $kv_edited_post = array(
        'ID'           =>  get_the_ID(),       
        'post_content' => "編號：".$results[0]["product_id"]
      );
      wp_update_post( $kv_edited_post);
      


      $i++;

    endwhile;
  } else {
    // echo __( 'No products found' );
  }
  wp_reset_postdata();

  $out = array(
    'data' => $data, 
    'total_num' => $i ,
    'px'=> $px
  );


  return $out; 
}











/* Product Clone  */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/product_clone', array(
    'methods' => 'POST',
    'callback' => 'product_clone_handler',
  ) );
});

function product_clone_handler($data){  
  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;
  $pid = (isset($data['pid'])) ? $data['pid'] : 0; 


  $sql = "SELECT * FROM $table_name WHERE id=".$pid['id'];
  $results = $wpdb->get_results($sql,ARRAY_A);

  $product_cat = get_the_terms($results[0]['woo_id'],'product_cat');





  // return $product_cat;

  $in_data = array(
    'product_id' => '複製-'.$results[0]['product_id'],
    'product_name' => '複製-'.$results[0]['product_name'],
    'invoice_name' => $results[0]['invoice_name'],
    'product_eng_name' => $results[0]['product_eng_name'],
    'money_type' => $results[0]['money_type'],
    'unit_sn' => $results[0]['unit_sn'],
    'unit_sn_cht' => $results[0]['unit_sn_cht'],
    'out_pack' => $results[0]['out_pack'],
    'out_pack_unit' => $results[0]['out_pack_unit'],
    'in_pack' => $results[0]['in_pack'],
    'in_pack_unit' => $results[0]['in_pack_unit'],
    'cuft' => $results[0]['cuft'],
    'net_weight' => $results[0]['net_weight'],
    'gross_weight' => $results[0]['gross_weight'],
    'weight_unit' => $results[0]['weight_unit'],
    'meant' => $results[0]['meant'],
  );


    $result = $wpdb->insert($table_name ,$in_data );
    $new_id = $wpdb->insert_id;

    if($new_id){

          //  create woo product  [start] 
          $post_id = wp_insert_post( array( 
            'post_title' => $results[0]['product_name'].'-copy', 
            'post_type' => 'product',
            'post_status' => 'publish'
          ));         
          wp_set_object_terms( $post_id, 'simple', 'product_type' );        
          update_post_meta( $post_id, '_regular_price', '8888' );

          if($post_id){    
            // global $wpdb;
            $table_name =  $wpdb->prefix . 'product';;
            $updated = $wpdb->update( $table_name,
                    array('woo_id' => $post_id), 
                    array('id' => $new_id));      
                    

              //  update image 
              $copy_attached = get_post_thumbnail_id($results[0]['woo_id']);
              if(get_post_thumbnail_id($results[0]['woo_id'])){
                set_post_thumbnail( $post_id, $copy_attached );
              }


              if($product_cat){
                $temp_arr = array();
                foreach($product_cat as $xcat){
                  $temp_arr[] = ($xcat->name);                  
                } 
                wp_set_object_terms( $post_id, $temp_arr,'product_cat');              
              }
          }  

        //  create woo product  [end] 
    }
    



    if($result){
      return $new_id;
    }else{
      return 0;
    }    

}




/*  後台 類別找產品 */
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/get_products_by_cat', array(
    'methods' => 'POST',
    'callback' => 'get_productsbk_by_cat_handler',
  ) );
});
function get_productsbk_by_cat_handler($data){
  $cat = (isset($data['cat'])) ? $data['cat'] : 0; 
  $cur_page = (isset($data['cur_page'])) ? $data['cur_page'] : 0; 
  $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 50; 


  //$cur_user = (isset($data['cur_user'])) ? $data['cur_user'] : 0;

  /*  is_has_customer_id  */
  $post_in = array();
  $post_price = array();

 // print_r($post_in);
  


  $args_all = array(
    'posts_per_page' => -1,
    // 'paged' => ($cur_page+1),     
    'tax_query' => array(
        // 'relation' => 'AND',
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'terms' => $cat 
        )
    ),
    'post_type' => 'product',
    'orderby' => 'title',
);


$the_query_all = new WP_Query( $args_all );



    $args = array(
      'posts_per_page' => $post_per_page,
      'paged' => ($cur_page),            
      'tax_query' => array(
          // 'relation' => 'AND',
          array(
              'taxonomy' => 'product_cat',
              'field' => 'id',
              'terms' => $cat 
          )
      ),
      'post_type' => 'product',
      'orderby' => 'title',
  );

  $the_query = new WP_Query( $args );
  


  $out = array();

  // The Loop
  if ( $the_query->have_posts() ) {
    
    while ( $the_query->have_posts() ) {
        $the_query->the_post();
        // echo '<li>' . get_the_title() . '</li>';


        $pd = get_product_meta(get_the_ID());


        $pd[0]['img'] = get_the_post_thumbnail_url(get_the_ID(),'full');
        $pd[0]['woo_link'] = get_the_permalink(get_the_ID());

        

        
        
          // $results[$key]['img'] = get_the_post_thumbnail_url($item['woo_id'],'full');
    
            
            if(FALSE === get_post_status( get_the_ID())){
                $results[$key]['woo_id'] = 0;
            }else{
              $cat = get_the_terms(get_the_ID(),'product_cat');   
              
              $text = array();
              if($cat){
                foreach($cat as $ct){
                  $text[]= $ct->name;
                }
                }
                $pd[0]['cat'] = implode(' > ',$text);
              }
          
          
          $out[] =  $pd[0];
                          
    }
    
  }else {
    // no posts found
  }    
  wp_reset_postdata();   



  $output = array(
    'results' => $out,
    'count' => $the_query_all->post_count
  );


  if(!empty($out)){  
    return $output;
   // return  $page.' '.$post_per_page ;
   // return $sql;
  }else{
    return 0;
  } 
}
