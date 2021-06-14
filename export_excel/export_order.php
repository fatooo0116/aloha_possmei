<?php
     


/* output  excel  */
if(isset($_POST['download'])=='true' ) {
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
            'BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP');








        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);



        $my_query = new WP_Query($args);


        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($excel_col[0].'1', '日期' )
        ->setCellValue($excel_col[1].'1', '訂單編號' )
        ->setCellValue($excel_col[2].'1',  '產品'  )
        ->setCellValue($excel_col[3].'1',  '數量' )
        /* ->setCellValue($excel_col[4].'1', '價錢' ) */
        ->setCellValue($excel_col[4].'1','總價')
        ->setCellValue($excel_col[5].'1','訂購者名字')
                  ->setCellValue($excel_col[6].'1','Email')
        ->setCellValue($excel_col[7].'1','電話')
        ->setCellValue($excel_col[8].'1','運費')
        ->setCellValue($excel_col[9].'1','地址')
        ->setCellValue($excel_col[10].'1','客戶備註');



        $args = array(
            'post_type' => 'shop_order',
            'post_status' => 'publish',           
            'posts_per_page' => '-1',
        );


        $customer_orders = $my_query->posts;

        $i=0;
        foreach ($customer_orders as $customer_order) {



            $order = new WC_Order();


            $order->populate($customer_order);
            $orderdata = (array) $order;
            $all_products = '';
            $qty='';
            $total=0;

            foreach ($order->get_items() as $xitem){



                      $billing_first_name =  get_post_meta($order->id,'_billing_first_name',true);
                      $billing_last_name = get_post_meta($order->id,'_billing_last_name',true);
                      $billing_name = $billing_last_name ." ".$billing_first_name;



                  $user_info = get_userdata($order->get_user_id());


                  $product_name ="";

                  $price=0;


                  //foreach ($order->get_items() as $xitem){
                      $product_name  = $xitem['name'];
                    //  $total += (int)$xitem['item_meta']['_line_subtotal'][0];

                 // }



                 $orderx = new WC_Order( $order->id  );
                 $billing_email = get_post_meta( $order->id, '_billing_email');
                 $billing_phone = get_post_meta( $order->id, '_billing_phone');


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
              } /*  End  product  */


              $total = $order->get_total();



          $j =$i+2;
            $col=$excel_col[0].$i;
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($excel_col[0].$j, substr($orderdata['order_date'],0,10) )
            ->setCellValue($excel_col[1].$j, substr($order->get_order_number(),1) )
            ->setCellValue($excel_col[2].$j, ' '.$all_products )
            // ->setCellValue($excel_col[3].$j, ' '.$qty )
            ->setCellValueExplicit($excel_col[3].$j, $qty, PHPExcel_Cell_DataType::TYPE_STRING)

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





            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('訂單資料查詢');


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);


            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="訂單資料查詢.xlsx"');
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