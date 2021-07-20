<?php

add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v2', '/get_products', array(
      'methods' => 'POST',
      'callback' => 'get_products_v2_handler',
    ) );
  });
  function get_products_v2_handler($data){
    
    $page = (isset($data['cur_page'])) ? $data['cur_page'] : 0; 
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 

    /*  is_has_customer_id  */
    $customer_id = (isset($data['customer_id'])) ? $data['customer_id'] : 0;     
    $price_only_mode = (isset($data['price_only_mode'])) ? $data['price_only_mode'] : 0; 
    
    $cur_user = (isset($data['cur_user'])) ? $data['cur_user'] : 0; 

  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'product';;
    $table_name2 =  $wpdb->prefix . 'cprice';;
  
    if($cur_user){ /*  使用者登入 */
      if($price_only_mode){
        /*  有價錢的全部商品  */
        $sql = "SELECT t1.*,t2.price FROM $table_name t1  LEFT JOIN $table_name2 t2 ON  t1.id=t2.product_id AND t2.woo_cid=$cur_user  WHERE t2.price>0   order by t1.id DESC Limit ".(int)($page)*$post_per_page.', '.$post_per_page;
      }else{
        /*  全部商品  */
        $sql = "SELECT t1.*,t2.price FROM $table_name t1  LEFT JOIN $table_name2 t2 ON  t1.id=t2.product_id AND t2.woo_cid=$cur_user     order by t1.id DESC Limit ".(int)($page)*$post_per_page.', '.$post_per_page;
      }  
    }else{
        $sql = "SELECT * FROM $table_name order by id DESC Limit ".(int)($page)*$post_per_page.', '.$post_per_page;
    }


 
     
    // $sql = "SELECT * FROM $table_name order by id DESC Limit ".($page)*$post_per_page.', '.$post_per_page;
    // $sql .= ' order by product_id ASC';
    $results = $wpdb->get_results($sql,ARRAY_A);
  
  
  
   
    foreach($results as $key => $item){
        $results[$key]['is_open'] = (get_post_status( $item['woo_id'])=="publish")? true : false;
        
        if($item['woo_id']){
           $results[$key]['img'] = get_the_post_thumbnail_url($item['woo_id'],'full');
           $results[$key]['woo_link'] = get_the_permalink($item['woo_id']);

            if(FALSE === get_post_status( $item['woo_id'])){
                $results[$key]['woo_id'] = 0;
            }else{
              $cat = get_the_terms($item['woo_id'],'product_cat');   
              
              $text = array();
              if($cat){
                foreach($cat as $ct){
                  $text[]= array( 
                                  "tid" => $ct->term_id,
                                  "name" => $ct->name
                                );
                }
              }
              $results[$key]['cat'] = $text;
            }
        }    
          
    }


   /*  全部商品 */
   if($price_only_mode){
    $sql2 =  "SELECT count(*) FROM $table_name t1  LEFT JOIN $table_name2 t2 ON  t1.id=t2.product_id AND t2.customer_id=486 WHERE t2.price>0" ;
   }else{
    $sql2 = "SELECT count(*) FROM $table_name";
   }
    
   /*  有價錢的全部商品 */ 
   //  $sql2 =  "SELECT count(*) FROM $table_name t1  LEFT JOIN $table_name2 t2 ON  t1.id=t2.product_id AND t2.customer_id=486 WHERE t2.price>0" ;

    $result_count = $wpdb->get_var($sql2);
  
    $out = array(
      'results' => $results,
      'count' => $result_count
    );
  
  
     // return $sql3 ;

    if(!empty($results)){  
        return $out;
      // return  $page.' '.$post_per_page ;
       // return $sql;
    }else{
      return 0;
    }   
  }









  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v2', '/get_products_by_cat', array(
      'methods' => 'POST',
      'callback' => 'get_products_by_cat_handler',
    ) );
  });
  function get_products_by_cat_handler($data){
    $cat = (isset($data['cat'])) ? $data['cat'] : 0; 
    $page = (isset($data['cur_page'])) ? $data['cur_page'] : 0; 
    $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 

    // $cur_user = (isset($data['cur_user'])) ? $data['cur_user'] : 0; 
    $price_only_mode = (isset($data['price_only_mode'])) ? $data['price_only_mode'] : 0; 
    $cur_user = (isset($data['cur_user'])) ? $data['cur_user'] : 0;

    /*  is_has_customer_id  */
    $post_in = array();
    $post_price = array();
      if($cur_user){
     //  $cur_user = (isset($data['cur_user'])) ? $data['cur_user'] : 0;
      global $wpdb;  
      $table_name2 =  $wpdb->prefix . 'cprice';;
      $sql_price = "SELECT * FROM $table_name2 WHERE  woo_cid=$cur_user AND price>0";
    
      $result_price = $wpdb->get_results($sql_price,ARRAY_A);
      
     
      foreach($result_price as $postin){
        $post_in[] = $postin['woo_pid'];
        $post_price[$postin['woo_pid']] = $postin['price'];
      }
    }
   // print_r($post_in);
    


    $args_all = array(
      'posts_per_page' => -1,
      'paged' => ($page+1),     
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

  if($cur_user  && $price_only_mode){
    $args_all['post__in'] = $post_in;
  }
  $the_query_all = new WP_Query( $args_all );








      $args = array(
        'posts_per_page' => $post_per_page,
        'paged' => ($page+1),            
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
    if($cur_user  && $price_only_mode){
      $args['post__in'] = $post_in;
    }
    $the_query = new WP_Query( $args );
    


    $out = array();

    // The Loop
    if ( $the_query->have_posts() ) {
      
      while ( $the_query->have_posts() ) {
          $the_query->the_post();
          // echo '<li>' . get_the_title() . '</li>';



             
    
          $pd = get_product_meta(get_the_ID());

          if(in_array(get_the_ID(),$post_in)){
            $pd[0]['price'] = $post_price[get_the_ID()];
          }

          $pd[0]['img'] = get_the_post_thumbnail_url(get_the_ID(),'full');
          $pd[0]['woo_link'] = get_the_permalink(get_the_ID());

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








add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v2', '/search_products_ajax', array(
    'methods' => 'POST',
    'callback' => 'search_products_ajax_handler',
  ) );
});


function search_products_ajax_handler($data){

  $post_per_page = (isset($data['perpage'])) ? $data['perpage'] : 0; 
  $page = (isset($data['page'])) ? $data['page'] : 0; 
  $filterText = (isset($data['filterText'])) ? $data['filterText'] : 0; 
  
  $price_only_mode = (isset($data['price_only_mode'])) ? $data['price_only_mode'] : 0; 
  $cur_user = (isset($data['cur_user'])) ? $data['cur_user'] : 0;

  global $wpdb;
  $table_name =  $wpdb->prefix . 'product';;
  // $sql =  "SELECT * FROM  ".$table_name." WHERE  product_id LIKE '%".$filterText."%' OR product_name LIKE '%".$filterText."%'  OR product_id LIKE '".$filterText."%'  OR product_name LIKE '".$filterText."%' order by id ASC Limit ".($page)*$post_per_page.', '.$post_per_page;

  if($cur_user){    
    $table_name2 =  $wpdb->prefix . 'cprice';;


    if($price_only_mode){
      $sql = "SELECT t1.*,t2.price FROM $table_name t1  LEFT JOIN $table_name2 t2 ON  t1.id=t2.product_id AND t2.woo_cid=$cur_user  
       WHERE   
       ( t1.product_id LIKE '%".$filterText."%' OR 
       t1.product_name LIKE '%".$filterText."%'  OR 
       t1.product_eng_name LIKE '%".$filterText."%'  OR 
       t1.product_eng_name LIKE '".$filterText."%'  OR 
       t1.product_id LIKE '".$filterText."%'  OR 
       t1.product_name LIKE '".$filterText."%' )
       AND t2.price>0 
       order by t1.id DESC Limit ".(int)($page)*$post_per_page.', '.$post_per_page;
    }else{
      $sql = "SELECT t1.*,t2.price FROM $table_name t1  LEFT JOIN $table_name2 t2 ON  t1.id=t2.product_id AND t2.woo_cid=$cur_user 
       WHERE   
       ( t1.product_id LIKE '%".$filterText."%' OR 
       t1.product_name LIKE '%".$filterText."%'  OR 
       t1.product_eng_name LIKE '%".$filterText."%'  OR 
       t1.product_eng_name LIKE '".$filterText."%'  OR        
       t1.product_id LIKE '".$filterText."%'  OR 
       t1.product_name LIKE '".$filterText."%' )       
       order by t1.id DESC Limit ".(int)($page)*$post_per_page.', '.$post_per_page;
    }

  }else{
    $sql =  "SELECT * FROM  ".$table_name." WHERE  product_id LIKE '%".$filterText."%' OR product_name LIKE '%".$filterText."%'  OR product_id LIKE '".$filterText."%'  OR product_name LIKE '".$filterText."%' order by id ASC Limit ".($page)*$post_per_page.', '.$post_per_page;  
  }

  // return $sql;
  

  $results = $wpdb->get_results($sql,ARRAY_A);

  
  
  foreach($results as $key => $item){
        $results[$key]['is_open'] = (get_post_status( $item['woo_id'])=="publish")? true : false;
        
        if($item['woo_id']){
           $results[$key]['img'] = get_the_post_thumbnail_url($item['woo_id'],'full');
           $results[$key]['woo_link'] = get_the_permalink($item['woo_id']);

            if(FALSE === get_post_status( $item['woo_id'])){
                $results[$key]['woo_id'] = 0;
            }else{
              $cat = get_the_terms($item['woo_id'],'product_cat');   
              
              $text = array();
              if($cat){
                foreach($cat as $ct){
                  $text[]= array( 
                                  "tid" => $ct->term_id,
                                  "name" => $ct->name
                                );
                }
              }
              $results[$key]['cat'] = $text;
            }
        }              
  }

  /*
  if($cur_user){  

  }else{

  }
  */

  if($price_only_mode){
    $table_name2 =  $wpdb->prefix . 'cprice';;
    $sql2 = "SELECT count(*) FROM $table_name t1  LEFT JOIN $table_name2 t2 ON  t1.id=t2.product_id AND t2.woo_cid=$cur_user  WHERE t2.price>0  AND  t1.product_id LIKE '%".$filterText."%' OR t1.product_name LIKE '%".$filterText."%'  OR t1.product_id LIKE '".$filterText."%'  OR t1.product_name LIKE '".$filterText."%' order by t1.id DESC Limit ".(int)($page)*$post_per_page.', '.$post_per_page;
  }else{
    $sql2 = "SELECT count(*) FROM  ".$table_name." WHERE  product_id LIKE '%".$filterText."%' OR product_name LIKE '%".$filterText."%'";
  }

  // $sql2 = "SELECT count(*) FROM  ".$table_name." WHERE  product_id LIKE '%".$filterText."%' OR product_name LIKE '%".$filterText."%'";
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
