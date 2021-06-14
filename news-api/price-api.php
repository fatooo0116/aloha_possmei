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

  
  for ($row = 2; $row <= $highestRow; $row++  ){
      $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

      var_dump($rowData);
  }
  
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
