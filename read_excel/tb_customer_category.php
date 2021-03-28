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
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
// 獲取一行的資料

echo '$highestRow => '.$highestRow;





$table_name = 'ca_aloha_customer_catgory';
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
     " (customer_catgory_id, customer_catgory_name, customer_catgory_eng_name, other) ".
     "VALUES ".
     "('".$rowData[0][0]."','".$rowData[0][1]."','".$rowData[0][2]."','".$rowData[0][3]."')";


    // echo $sql;


    
     $retval = mysqli_query( $conn, $sql );
     if(! $retval )
     {
       die('無法插入資料: ' . mysqli_error($conn));
     }
     echo "資料插入成功\n";

}
