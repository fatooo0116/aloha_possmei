<?php



include './Classes/PHPExcel/IOFactory.php';
$inputFileName = './file.xls';
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
$sheet = $objPHPExcel->getSheet(2);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
// 獲取一行的資料

echo '$highestRow => '.$highestRow;





$table_name = 'ca_customer_info';
$dbhost = 'localhost:3306';  // mysql伺服器主機地址
$dbuser = 'root';            // mysql使用者名稱
$dbpass = 'root';          // mysql使用者名稱密碼




$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn ){
  die('連線失敗: ' . mysqli_error($conn));
}else{
    echo '連線成功';
}
mysqli_query($conn , "set names utf8");

mysqli_select_db( $conn, 'cargo');

for ($row = 2; $row <= $highestRow; $row++  ){
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

   

     var_dump($rowData);

     $sql = "INSERT INTO ".$table_name.
     " ( customer_id,
         account_id,         
         cname,
         customer_category_id,
         addr_id,
         staff_id,
         dollar_mark,
         is_temp,
         is_global,
         simple_name,
         sn,
         boss,
         capital,
         contact,
         contact_job,
         contact_tel1,
         contact_tel2,
         contact_tel3,
         contact_mobile,
         contact_fax,
         contact_email,
         invoice_cht,
         invoice_eng_long,
         invoice_eng_short
         ) ".
     "VALUES ".
     "('".$rowData[0][0]."','".
          addslashes($rowData[0][1])."','".
          addslashes($rowData[0][2])."','".
          addslashes($rowData[0][3])."','".  /* customer_category_id */
          addslashes($rowData[0][4])."','". /* addr_id */
          addslashes($rowData[0][5])."','". /* staff_id */
          addslashes($rowData[0][6])."','". /* dollar_mark, */
          addslashes($rowData[0][7])."','".
          addslashes($rowData[0][8])."','".          
          addslashes($rowData[0][9])."','".  /* simple_name  */

          addslashes($rowData[0][10])."','".  /* sn  */
          addslashes($rowData[0][11])."','". /*  boss */

          addslashes($rowData[0][12])."','".  /*  capital */
          
          addslashes($rowData[0][13])."','".  /*  contact */
          addslashes($rowData[0][14])."','".  /*  contact job */
          addslashes($rowData[0][15])."','".  /*  contact tel1 */
          addslashes($rowData[0][16])."','".  /*  contact tel2 */        
          addslashes($rowData[0][17])."','".  /*  contact tel3 */

          addslashes($rowData[0][18])."','".  /*  contact mobile */
          addslashes($rowData[0][19])."','".  /*  contact fax */
          addslashes($rowData[0][22])."','".  /*  contact email */
          
          addslashes($rowData[0][28])."','".
          addslashes($rowData[0][30])."','".
          addslashes($rowData[0][31]).              
          "')";


     echo $sql;


    
     $retval = mysqli_query( $conn, $sql );
     if(! $retval )
     {
       die('無法插入資料: ' . mysqli_error($conn));
     }
     echo "資料插入成功\n";

}
