<?php


add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/price_upload', array(
    'methods' => 'POST',
    'callback' => 'price_upload_handler',
  ) );
});

function price_upload_handler($data){
    $file = (isset($_FILES['myPrice'])) ? $_FILES['myPrice'] : 0; 
  
    $tmpfname = $_FILES['myPrice']['tmp_name'];
    include 'Classes/PHPExcel/IOFactory.php';
    $inputFileName = $tmpfname;
    date_default_timezone_set('PRC');
    // 讀取excel檔案
    try {
      $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
      $objReader = PHPExcel_IOFactory::createReader($inputFileType);
      $objPHPExcel = $objReader->load($inputFileName);
    } catch(Exception $e) {
    die(‘載入檔案發生錯誤：”‘.pathinfo($inputFileName,PATHINFO_BASENAME).'”: '.$e->getMessage());
    }
    // 確定要讀取的sheet，什麼是sheet，看excel的右下角，真的不懂去百度吧
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

  $output = array();
  $success = array();
  $error = array();
  $error_msg = array();

  $test = array();

  global $wpdb;



  for ($row = 2; $row <= $highestRow; $row++  ){
      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

      $customer_id =  $rowData[0][0]; /*  cid */
      $product_id =  $rowData[0][1];  /* pid */
      $pname =  $rowData[0][2];
      $price =  $rowData[0][3];  /*  Price */

      /*  get woo_pid */
      $ptable_name = $wpdb->prefix . 'product';;
      $sql = "SELECT * FROM $ptable_name WHERE product_id='".trim($product_id)."'";
      $obj = $wpdb->get_results($sql);
      $woopid = $obj[0]->woo_id;
      $pid = $obj[0]->id; /*  pid  是 table id */


      $ctable_name = $wpdb->prefix . 'customer_info';;
      $sql = "SELECT * FROM $ctable_name WHERE customer_id='".trim($customer_id)."'";
      $obj2 = $wpdb->get_results($sql);
      $woocid = $obj2[0]->woo_id;
      $cid = $obj2[0]->id; /*  pid  是 table id */

      
   // print_r($obj);

    // $test[] = $cid." => ".$pid;

     
      if($woopid>0 & $woocid>0){      
          if($cid>0 & $pid>0){

            $table_name =  $wpdb->prefix . 'cprice';;
            $sql = "SELECT count(*) FROM $table_name WHERE product_id=".$pid." AND customer_id=".$cid;
            $count = $wpdb->get_var($sql);
            if($count>0){
            
              $result =  $wpdb->update(
                              $table_name,
                              array(
                                'price' => $price,  
                                'woo_pid'=>$woopid,
                                'woo_cid'=>$woocid,                     
                              ), 
                              array(
                                'product_id'=> $pid,
                                'customer_id'=> $cid,
                              ));
            }else{
              
                    $data =  array(
                    'product_id'=> $pid,
                    'price' => $price,      
                    'customer_id'=> $cid,
                    'woo_pid'=>$woopid,
                    'woo_cid'=>$woocid,
                  );     
                  $result = $wpdb->insert($table_name,$data);
            }   
            

            $success[] =  array(
              'status' => $result, 
              'product_id' => $product_id,
              'pname' => $pname,
              'customer_id' => $customer_id,
              'price' => $price,          
              'woopid' => $woopid,   
              'woocid'=>$woocid,   
              '$count'=>$count   
            ); 
          }else{


            $error_msg = '';
            $error_msg = $pid.'='.$product_id.'=>'.$woopid; 

            $error[] =  array(
              'status' => 2, 
              'product_id' => $product_id,
              'pname' => $pname,
              'customer_id' => $customer_id,
              'price' => $price,          
              'woopid' => $woopid,   
              'woocid'=>$woocid, 
              'msg'=> $error_msg    
            ); 
          }
      }else{

        $error_msg = $pid.'='.$product_id.'=>'.$woopid; 

          $error[] =  array(
            'status' => 0, 
            'product_id' => $product_id,
            'pname' => $pname,
            'customer_id' => $customer_id,
            'price' => $price,          
            '$woopid' => $woopid,   
            '$woocid'=>$woocid,  
            'msg'=> $error_msg    
          ); 
      }  
  }


  // return $test;



  $output  = array(
         
                'success' => $success,
                'error' => $error,
              );
  
   return json_encode($output);
}







add_action( 'rest_api_init', function () {
  register_rest_route( 'cargo/v1', '/import_price', array(
    'methods' => 'POST',
    'callback' => 'import_update_price_handler',
  ) );
});

function import_update_handler(){

}













add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/get_price', array(
      'methods' => 'POST',
      'callback' => 'get_staff_price_handler',
    ) );
  });
  function get_staff_price_handler($data){
    
    $cid = (isset($data['cid'])) ? $data['cid'] : 0; 
    $pid = (isset($data['pid'])) ? $data['pid'] : 0; 
  
    global $wpdb;
  
    $table_price =  $wpdb->prefix . 'cprice';;
  
  
  
    $sql = "SELECT * From ".$table_price." WHERE product_id='".$pid."' AND customer_id='".$cid."'";
    // $sql .= ' order by product_id ASC';
    $results = $wpdb->get_results($sql,ARRAY_A);
  
    // return $sql;

    if(!empty($results)){  
      return $results;
  
    }else{
      return 0;
    } 
   
  }






  add_action( 'rest_api_init', function () {
    register_rest_route( 'cargo/v1', '/update_price', array(
      'methods' => 'POST',
      'callback' => 'update_price_handler',
    ) );
  });

  function update_price_handler($data){
    $cid = (isset($data['cid'])) ? $data['cid'] : 0; 
    $pid = (isset($data['pid'])) ? $data['pid'] : 0; 
    $price = (isset($data['price'])) ? $data['price'] : 0; 

    $woocid = (isset($data['cid'])) ? $data['woo_cid'] : 0; 
    $woopid = (isset($data['pid'])) ? $data['woo_pid'] : 0; 

   //  $sql = "SELECT * FROM $table_name WHERE product_id=".$pid." AND customer_id=".$cid;
  // $sql .= ' order by product_id ASC';
    
  global $wpdb;

    if($cid!=0 | $pid!=0){

      $table_name =  $wpdb->prefix . 'cprice';;
      $sql = "SELECT * FROM $table_name WHERE product_id=".$pid." AND customer_id=".$cid;
      $count = $wpdb->get_var($sql);
      if($count>0){
        /*  Update */
      $result =   $wpdb->update(
                      $table_name,
                      array(
                        'price' => $price,  
                        'woo_pid'=>$woopid,
                        'woo_cid'=>$woocid,                     
                      ), 
                      array(
                        'product_id'=> $pid,
                        'customer_id'=> $cid,
                      ));

      }else{
        /* Insert */
          $data =  array(
          'product_id'=> $pid,
          'price' => $price,      
          'customer_id'=> $cid,
          'woo_pid'=>$woopid,
          'woo_cid'=>$woocid,
        );     
        $result = $wpdb->insert($table_name,$data);
      }    
      
    }
    
   // $results = $wpdb->get_results($sql);
   if($result){
    return $price;
   }else{
    return 0;     
   }

   // return $result ;
  }






/* 
*    get price by  
     @ product id
     @ customer id
*/
