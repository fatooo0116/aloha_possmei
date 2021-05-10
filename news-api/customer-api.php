<?php
add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/get_customers', array(
    'methods' => 'POST',
    'callback' => 'get_customers_handler',
  ) );
});
function get_customers_handler($data){
  
  $page = (isset($data['page'])) ? $data['page'] : 0; 
  $post_per_page = (isset($data['post_per_page'])) ? $data['post_per_page'] : 0; 

  global $wpdb;
  $table_name =  $wpdb->prefix . 'customer_info';;


  $sql = "SELECT * FROM $table_name order by id DESC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
  
  $results = $wpdb->get_results($sql);
  if(!empty($results)){  
      return $results;
   
  }else{
    return 0;
  }
 
}




/*  ===========   DEL  ===========  */
add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/del_customer', array(
      'methods' => 'POST',
      'callback' => 'del_customer_handler',
    ) );
  });
  function del_customer_handler($data){
    
    $pid = (isset($data['checked'])) ? $data['checked'] : 0; 
  
    global $wpdb;
    $table_name =  $wpdb->prefix . 'customer_info';;
    
    // $sql = "SELECT * FROM $table_name order by id ASC Limit ".($page-1)*$post_per_page.', '.$post_per_page;
    foreach($pid as $in){
   
      if($in['woo_id']>0){
        $result = wp_delete_post($woo_id);
        if($result){
          $wpdb->delete( $table_name, array( 'id' => $in['id'] ));
        }
      }else{ /*  woo_id == 0 */
           $wpdb->delete( $table_name, array( 'id' => $in['id']) );
      }
    }

    // return $result;
  }




  /*  ===========   Create  ===========  */
 add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/create_customer', array(
      'methods' => 'POST',
      'callback' => 'create_customer_handler',
    ) );
  });


  function create_customer_handler($data){

    global $wpdb;
    $table_name =  $wpdb->prefix . 'customer_info';;

  
    
    $result = $wpdb->insert($table_name , array(
        'customer_id' => (isset($data['customer_id'])) ? $data['customer_id'] : 0, /* 客戶編號 */
        'account_id' => (isset($data['account_id'])) ? $data['account_id'] : '', /* 帳款歸屬 */
        'cname' => (isset($data['cname'])) ? $data['cname'] : '',  /* 客戶全稱 */
        'customer_category_id' => (isset($data['customer_category_id'])) ? $data['customer_category_id'] : '', /* 類別編號 */
        'addr_id'=> (isset($data['addr_id'])) ? $data['addr_id'] : '',
        'staff_id'=> (isset($data['staff_id'])) ? $data['staff_id'] : '',
        'dollar_mark'=> (isset($data['dollar_mark'])) ? $data['dollar_mark'] : '',
        'is_temp'=> (isset($data['is_temp'])) ? $data['is_temp'] : '',
        'is_global'=> (isset($data['is_global'])) ? $data['is_global'] : '',
        'simple_name'=> (isset($data['simple_name'])) ? $data['simple_name'] : '',
        'sn'=> (isset($data['sn'])) ? $data['sn'] : '',
        'boss'=> (isset($data['boss'])) ? $data['boss'] : '',
        'capital'=> (isset($data['capital'])) ? $data['capital'] : '',
        'contact'=> (isset($data['contact'])) ? $data['contact'] : '',
        'contact_job'=> (isset($data['contact_job'])) ? $data['contact_job'] : '',
        'contact_tel1'=> (isset($data['contact_tel1'])) ? $data['contact_tel1'] : '',
        'contact_tel2'=> (isset($data['contact_tel2'])) ? $data['contact_tel2'] : '',
        'contact_tel3'=> (isset($data['contact_tel3'])) ? $data['contact_tel3'] : '',
        'contact_mobile'=> (isset($data['contact_mobile'])) ? $data['contact_mobile'] : '',
        'contact_fax'=> (isset($data['contact_fax'])) ? $data['contact_fax'] : '',
        'contact_email'=> (isset($data['contact_email'])) ? $data['contact_email'] : '',
        'invoice_cht'=> (isset($data['invoice_cht'])) ? $data['invoice_cht'] : '',
        'invoice_eng_long'=> (isset($data['invoice_eng_long'])) ? $data['invoice_eng_long'] : '',
        'invoice_eng_short'=> (isset($data['invoice_eng_short'])) ? $data['invoice_eng_short'] : '',
       
        'trade_mark'=> (isset($data['trade_mark'])) ? $data['trade_mark'] : '',
        'woo_id'=> (isset($data['woo_id'])) ? $data['woo_id'] : '',
    ));

    return  $result;
  }




    /*  ===========   Edit  ===========  */
 add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/edit_customer', array(
      'methods' => 'POST',
      'callback' => 'edit_customer_handler',
    ) );
  });

  


  function edit_customer_handler($data){

    global $wpdb;
    $tdata = array(
      'customer_id' => (isset($data['fields']['customer_id'])) ? $data['fields']['customer_id'] : 0, /* 客戶編號 */
      'account_id' => (isset($data['fields']['account_id'])) ? $data['fields']['account_id'] : '', /* 帳款歸屬 */
      'cname' => (isset($data['fields']['cname'])) ? $data['fields']['cname'] : '',  /* 客戶全稱 */
      'customer_category_id' => (isset($data['fields']['customer_category_id'])) ? $data['fields']['customer_category_id'] : '', /* 類別編號 */
      'addr_id'=> (isset($data['fields']['addr_id'])) ? $data['fields']['addr_id'] : '',
      'staff_id'=> (isset($data['fields']['staff_id'])) ? $data['fields']['staff_id'] : '',
      'dollar_mark'=> (isset($data['fields']['dollar_mark'])) ? $data['fields']['dollar_mark'] : '',
      'is_temp'=> (isset($data['fields']['is_temp'])) ? $data['fields']['is_temp'] : '',
      'is_global'=> (isset($data['fields']['is_global'])) ? $data['fields']['is_global'] : '',
      'simple_name'=> (isset($data['fields']['simple_name'])) ? $data['fields']['simple_name'] : '',
      'sn'=> (isset($data['fields']['sn'])) ? $data['fields']['sn'] : '',
      'boss'=> (isset($data['boss'])) ? $data['boss'] : '',
      'capital'=> (isset($data['fields']['capital'])) ? $data['fields']['capital'] : '',
      'contact'=> (isset($data['fields']['contact'])) ? $data['fields']['contact'] : '',
      'contact_job'=> (isset($data['fields']['contact_job'])) ? $data['fields']['contact_job'] : '',
      'contact_tel1'=> (isset($data['fields']['contact_tel1'])) ? $data['fields']['contact_tel1'] : '',
      'contact_tel2'=> (isset($data['fields']['contact_tel2'])) ? $data['fields']['contact_tel2'] : '',
      'contact_tel3'=> (isset($data['fields']['contact_tel3'])) ? $data['fields']['contact_tel3'] : '',
      'contact_mobile'=> (isset($data['fields']['contact_mobile'])) ? $data['fields']['contact_mobile'] : '',
      'contact_fax'=> (isset($data['fields']['contact_fax'])) ? $data['fields']['contact_fax'] : '',
      'contact_email'=> (isset($data['fields']['contact_email'])) ? $data['fields']['contact_email'] : '',
      'invoice_cht'=> (isset($data['fields']['invoice_cht'])) ? $data['fields']['invoice_cht'] : '',
      'invoice_eng_long'=> (isset($data['fields']['invoice_eng_long'])) ? $data['fields']['invoice_eng_long'] : '',
      'invoice_eng_short'=> (isset($data['fields']['invoice_eng_short'])) ? $data['fields']['invoice_eng_short'] : '',     
      'trade_mark'=> (isset($data['fields']['trade_mark'])) ? $data['fields']['trade_mark'] : '',
      'woo_id'=> (isset($data['fields']['woo_id'])) ? $data['fields']['woo_id'] : '',
    );
      $table_name =  $wpdb->prefix . 'customer_info';
      $wpdb->update( $table_name, $tdata, array('id' => $data['cur_id']) );
  }






    /*  ===========   Create woocommerce user  ===========  */
    add_action( 'rest_api_init', function () {
      register_rest_route( 'cargo/v1', '/bind_woo_customer_by_page', array(
        'methods' => 'POST',
        'callback' => 'bind_woo_customer_by_page_handler',
      ) );
    });
  

    function bind_woo_customer_by_page_handler($data){

        global $wpdb;
        $table_name =  $wpdb->prefix . 'customer_info';

        $pid = (isset($data['checked'])) ? $data['checked'] : 0; 

      
        foreach($pid as $item){     

      
          if(FALSE === get_post_status( $item['woo_id'])){
            // $user_id = wc_create_new_customer( $item['customer_id'], $item['customer_id'], rand(999,999999) );

            $useremail = "service@wdr.tw";
            $user_id = wc_create_new_customer($useremail, $item['customer_id'], rand(999,999999) );
           // return $user_id;


           /*  send reset email  [begin]  */
           if( $user_id){
              $user = get_user_by( 'email', $user_email );
            
              $username = $user->user_login;            
              $key = get_password_reset_key( $user );
              $reset_link = wp_login_url() . '?action=rp&key=' . $key . '&login=' . $username;
        
              $message = '<h2>' . __('Proceed to reset password : ', 'my_slug') . '</h2><br />' .
              __('Reset your password link : ', 'my_slug') . 
              '<a href="'. esc_url( $reset_link ) . '" title="' . __('Reset your password link : ', 'my_slug') .'" >' . 
              esc_url( $reset_link ) . '</a>';
        
              wp_mail( $useremail, "Please Reset User Email and Login", stripslashes( $message ), "Content-Type: text/html; charset=UTF-8" );
           }
          /*  send reset email  [end]  */

          }else{
            return 0;
          }
          

          // return $user_id;

          
          if($post_id){                
            $table_name =  $wpdb->prefix . 'product';;
            $updated = $wpdb->update( $table_name,
                    array('woo_id' => $post_id), 
                    array('id' => $item['id']));
            $out[] =  $post_id;       
          }                   
        }
        
    }




