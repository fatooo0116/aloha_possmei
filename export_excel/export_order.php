<?php
     


/* output  excel  */
if(isset($_POST['download_erp']) & $_POST['download_erp']>0) {
    add_action('admin_init', 'output_forum_xls');
}     


function output_forum_xls(){
    if(current_user_can('edit_files')) {



        require_once 'Classes/PHPExcel.php';

        // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");




        $excel_col = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA',
            'AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA',
            'BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ');








        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);



        $my_query = new WP_Query($args);


        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($excel_col[0].'1', 'S/C#' )
        ->setCellValue($excel_col[1].'1', 'Date' )
        ->setCellValue($excel_col[2].'1',  'Cust PO#'  )
        ->setCellValue($excel_col[3].'1',  'Cust NO' )
        /* ->setCellValue($excel_col[4].'1', '價錢' ) */
        ->setCellValue($excel_col[4].'1','CURR')
        ->setCellValue($excel_col[5].'1','ExchRate')
        ->setCellValue($excel_col[6].'1','ATTN')
        ->setCellValue($excel_col[7].'1','TERM')
        ->setCellValue($excel_col[8].'1','TermContent')
        ->setCellValue($excel_col[9].'1','Mark')
        ->setCellValue($excel_col[10].'1','Sales')
        ->setCellValue($excel_col[11].'1','DEPART')
        ->setCellValue($excel_col[12].'1','Address')
        ->setCellValue($excel_col[13].'1','Under Value')
        ->setCellValue($excel_col[14].'1','Sub Desc')
        ->setCellValue($excel_col[15].'1','Agent')
        ->setCellValue($excel_col[16].'1','WAY')
        ->setCellValue($excel_col[17].'1','Shipper')
        ->setCellValue($excel_col[18].'1','PerSS')
        ->setCellValue($excel_col[19].'1','FROM')
        ->setCellValue($excel_col[20].'1','TO')
        ->setCellValue($excel_col[21].'1','VIA')
        ->setCellValue($excel_col[22].'1','TARIFF')
        ->setCellValue($excel_col[23].'1','Freight')
        ->setCellValue($excel_col[24].'1','PAYMENT')
        ->setCellValue($excel_col[25].'1','SHIPMENT')
        ->setCellValue($excel_col[26].'1','CloseDate')
        ->setCellValue($excel_col[27].'1','預交貨日')
        ->setCellValue($excel_col[28].'1','TOP')
        ->setCellValue($excel_col[29].'1','END')
        ->setCellValue($excel_col[30].'1','Memo')
        ->setCellValue($excel_col[31].'1','SC種類')
        ->setCellValue($excel_col[32].'1','TOP')
        ->setCellValue($excel_col[33].'1','END')
        ->setCellValue($excel_col[34].'1','條文名稱')
        ->setCellValue($excel_col[35].'1','簽回')
        ->setCellValue($excel_col[36].'1','佣金率')
        ->setCellValue($excel_col[37].'1','佣金金額')
        ->setCellValue($excel_col[38].'1','CASE BY CASE')
        ->setCellValue($excel_col[39].'1','TEL')
        ->setCellValue($excel_col[40].'1','TEL')
        ->setCellValue($excel_col[41].'1','自定欄一')
        ->setCellValue($excel_col[42].'1','自定欄二')
        ->setCellValue($excel_col[43].'1','來源別')
        ->setCellValue($excel_col[44].'1','來源單號')
        ->setCellValue($excel_col[45].'1','單況')
        ->setCellValue($excel_col[46].'1','Project')
        ->setCellValue($excel_col[47].'1','加扣項合計')
        ->setCellValue($excel_col[48].'1','數量合計')
        ->setCellValue($excel_col[49].'1','轉廠交易')
        ->setCellValue($excel_col[50].'1','多角原始S/C')
        ->setCellValue($excel_col[51].'1','淨重單位')
        ->setCellValue($excel_col[52].'1','毛重單位')
        ->setCellValue($excel_col[53].'1','材積單位')
        ->setCellValue($excel_col[54].'1','帳款歸屬')
        ->setCellValue($excel_col[55].'1','客戶訂單')
        ->setCellValue($excel_col[56].'1','ITEM NO')
        ->setCellValue($excel_col[57].'1','DESCRIPTION')
        ->setCellValue($excel_col[58].'1',"Qty")
        ->setCellValue($excel_col[59].'1','基本數量')
        ->setCellValue($excel_col[60].'1','輔助數量')
        ->setCellValue($excel_col[61].'1','PRICE')
        ->setCellValue($excel_col[62].'1','ReportPrice')
        ->setCellValue($excel_col[63].'1','AMOUNT')
        ->setCellValue($excel_col[64].'1','預交日期')
        ->setCellValue($excel_col[65].'1','SUB DESCRIPTION')
        ->setCellValue($excel_col[66].'1','材積')
        ->setCellValue($excel_col[67].'1','材積小計')
        ->setCellValue($excel_col[68].'1','N.W')
        ->setCellValue($excel_col[69].'1','N.W小計')
        ->setCellValue($excel_col[70].'1','G.W')
        ->setCellValue($excel_col[71].'1','G.W小計')
        ->setCellValue($excel_col[72].'1','裝箱數量')
        ->setCellValue($excel_col[73].'1','MARK')
        ->setCellValue($excel_col[74].'1','Assembler Qty')
        ->setCellValue($excel_col[75].'1',"分錄備註")
        ->setCellValue($excel_col[76].'1',"贈品");


        global $post;

        $order_id = $_POST['download_oid'];
        $order = new WC_Order($order_id);
        $user_id   = $order->get_user_id();

        $j = 2;
        foreach ( $order->get_items() as $item_id => $item ) {

            $qty = $item->get_quantity();
            $product_id = $item->get_product_id();
            $price = get_price_by_customer($user_id,$product_id);

            $objPHPExcel->setActiveSheetIndex(0)
            //  ->setCellValue($excel_col[0].$j, substr($orderdata['order_date'],0,10) )
            ->setCellValue($excel_col[0].$j, substr($order->get_order_number(),0) )
            ->setCellValue($excel_col[1].$j, (date("Y",strtotime($order->order_date)) - 1911)."/".date("m/d",strtotime($order->order_date)) )
            ->setCellValue($excel_col[57].$j, $item->get_name())
            ->setCellValue($excel_col[58].$j, ' '.$qty )
            ->setCellValue($excel_col[61].$j, $price);
            
             $j++;
        }



        /*
        $order = new WC_Order($order_id);
        $order->populate($customer_order);
        $orderdata = (array) $order;
        $all_products = '';
        $qty='';
        $total=0;


        $j=0;
        foreach ($order->get_items() as $xitem){


              $user_info = get_userdata($order->get_user_id());
              $product_name ="";

              $price=0;


              //foreach ($order->get_items() as $xitem){
                  $product_name  = $xitem['name'];
                //  $total += (int)$xitem['item_meta']['_line_subtotal'][0];

             // }



             $orderx = new WC_Order($order_id);
           //  $billing_email = get_post_meta( $order->id, '_billing_email');
           //  $billing_phone = get_post_meta( $order->id, '_billing_phone');


              $variate_name ="";
              $product_s = wc_get_product( $xitem['product_id'] );
               if ($product_s->product_type == 'variable') {
                   $args = array(
                       'post_parent' => $plan->ID,
                       'post_type'   => 'product_variation',
                       'numberposts' => -1,
                   );
                   $variations = $product_s->get_available_variations();

                   $all_variate = array_keys($variations[0]['attributes']);
                   foreach(  $all_variate as $item){
                     $temp  =  str_replace('attribute_','',$item);
                     if(array_key_exists($temp,$xitem)){
                         $variate_name =  $xitem[$temp];
                         $product_name  = $xitem['name']." - ".$variate_name;
                     }
                   }
               }else{
                   $product_name  = $xitem['name'];
               }

              $qty .= $xitem['item_meta']['_qty'][0]."\n";

              $all_products .= $product_name."\n";



              $objPHPExcel->setActiveSheetIndex(0)
              //  ->setCellValue($excel_col[0].$j, substr($orderdata['order_date'],0,10) )
               ->setCellValue($excel_col[0].$j, substr($order->get_order_number(),1) );
             //   ->setCellValue($excel_col[1].$j, ' '.$all_products )
                // ->setCellValue($excel_col[3].$j, ' '.$qty )
             //   ->setCellValueExplicit($excel_col[2].$j, $qty, PHPExcel_Cell_DataType::TYPE_STRING);

              //  ->setCellValue($excel_col[4].$j, ' '.$price )
               // ->setCellValue($excel_col[4].$j, $total )
               // ->setCellValue($excel_col[5].$j, $billing_name)
               // ->setCellValue($excel_col[6].$j,   $billing_email[0] )
               // ->setCellValueExplicit($excel_col[7].$j, $billing_phone[0], PHPExcel_Cell_DataType::TYPE_STRING)
                // ->setCellValue($excel_col[8].$j, $billing_phone[0] )
              //  ->setCellValue($excel_col[8].$j, $order->get_shipping() )
               // ->setCellValue($excel_col[9].$j, $order->get_billing_address() )
               // ->setCellValue($excel_col[10].$j, $orderdata['customer_note'] );
            $j++;
          } /*  End  product  */



          /*
        $args = array(
            'post_type' => 'shop_order',
            'post_status' => 'publish',           
            'posts_per_page' => '-1',
        );


        $customer_orders = $my_query->posts;

        $i=0;
        foreach ($customer_orders as $customer_order) {

            
           



              $total = $order->get_total();



          $j =$i+2;
            $col=$excel_col[0].$i;
            $objPHPExcel->setActiveSheetIndex(0)
          //  ->setCellValue($excel_col[0].$j, substr($orderdata['order_date'],0,10) )
            ->setCellValue($excel_col[0].$j, substr($order->get_order_number(),1) )
            ->setCellValue($excel_col[1].$j, ' '.$all_products )
            // ->setCellValue($excel_col[3].$j, ' '.$qty )
            ->setCellValueExplicit($excel_col[2].$j, $qty, PHPExcel_Cell_DataType::TYPE_STRING)

          //  ->setCellValue($excel_col[4].$j, ' '.$price )
            ->setCellValue($excel_col[4].$j, $total )
            ->setCellValue($excel_col[5].$j, $billing_name)
            ->setCellValue($excel_col[6].$j,   $billing_email[0] )
            ->setCellValueExplicit($excel_col[7].$j, $billing_phone[0], PHPExcel_Cell_DataType::TYPE_STRING)
            // ->setCellValue($excel_col[8].$j, $billing_phone[0] )
           ->setCellValue($excel_col[8].$j, $order->get_shipping() )
            ->setCellValue($excel_col[9].$j, $order->get_billing_address() )
            ->setCellValue($excel_col[10].$j, $orderdata['customer_note'] );



            $i +=1;
        }
        */





            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('erp匯入');


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="erp匯入.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');





            exit;
            die();
        }
        else{
            wp_die(__('You do not have sufficient permissions to export the content of this site.'));
        }
}




?>