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
$sheet = $objPHPExcel->getSheet(4);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
// 獲取一行的資料

echo '$highestRow => '.$highestRow;





$table_name = 'ca_product';
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
     " (
        product_id, 
        type_name, 
        unit_sn,
        unit_sn_cht,
        product_name,
        invoice_name,
        product_eng_name,
        money_type,
        price,
        out_pack,
        out_pack_unit,
        in_pack,
        in_pack_unit,
        cuft,
        net_weight,
        gross_weight,
        weight_unit,
        meant,
        woo_id
        ) ".
     "VALUES ".
     "('".$rowData[0][0]."','".
        addslashes($rowData[0][1])."','".
        addslashes($rowData[0][3])."','". /* unit_sn */
        addslashes($rowData[0][4])."','".  /* unit_sn_cht */
        addslashes($rowData[0][5])."','".
        addslashes($rowData[0][6])."','".
        addslashes($rowData[0][7])."','". /* invoice_eng_name  */
        addslashes($rowData[0][8])."','". /* money_type  */
        addslashes($rowData[0][9])."','". /* price */
        addslashes($rowData[0][16])."','". /* out_pack  */
        addslashes($rowData[0][49])."','". /* out_pack  */

        addslashes($rowData[0][18])."','". /* in_pack  */
        addslashes($rowData[0][19])."','". /* in_pack_unit  */
        addslashes($rowData[0][61])."','". /*  cuft  */
        addslashes($rowData[0][63])."','". /*  net_weight  */
        addslashes($rowData[0][65])."','". /*  gross_weight  */
        addslashes($rowData[0][64])."','". /*  weight unit  */

        addslashes($rowData[0][67])."','0')";


     echo $sql;


    
     $retval = mysqli_query( $conn, $sql );
     if(! $retval )
     {
       die('無法插入資料: ' . mysqli_error($conn));
     }
     echo "資料插入成功\n";

}
