<?php

    // Adding Meta container admin shop_order pages
add_action( 'add_meta_boxes', 'mv_add_meta_boxes' );
if ( ! function_exists( 'mv_add_meta_boxes' ) )
{
    function mv_add_meta_boxes()
    {
        add_meta_box( 'mv_other_fields', __('訂單明細','woocommerce'), 'mv_add_other_fields_for_packaging', 'shop_order', 'normal', 'core' );
    }
}

// Adding Meta field in the meta container admin shop_order pages
if ( ! function_exists( 'mv_add_other_fields_for_packaging' ) )
{
    function mv_add_other_fields_for_packaging()
    {
       //  global $post;
        // $meta_field_data = get_post_meta( $post->ID, '_my_field_slug', true ) ? get_post_meta( $post->ID, '_my_field_slug', true ) : '';
       

        ?>
       
        <script>
                    function PrintElem(elem)
                        {
                            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

                            mywindow.document.write('<html><head><title></title>');
                            mywindow.document.write('</head><body >');                            
                            mywindow.document.write(document.getElementById(elem).innerHTML);
                            mywindow.document.write('</body></html>');

                            mywindow.document.close(); // necessary for IE >= 10
                            mywindow.focus(); // necessary for IE >= 10*/

                            mywindow.print();
                            mywindow.close();

                            return true;
                        }
                </script>

            <div class="toolbar">
                <button  onClick="PrintElem('shop_order_invoice')" >列印訂單</button>
            </div>

       <div id="shop_order_invoice">

       <style>

            @page { size: auto;  margin: 0mm; }
            #my_page{padding:15px 10px;}
            .order_table2{
                width: 100%;
                border-collapse: collapse;
                font-size:16px;
            }
            .order_table2 td, .order_table2 th {
                border: 1px solid #444;
                padding:5px;
                text-align:center;
                font-size:13px;
            }
            .order_table2 td.tl{ text-align:left; }
            #customer_info{
                padding:15px 5px;                
            }
            #customer_info .c1{ display:flex; }
            #customer_info .item{
                width:100%;
                display: flex;
                font-size: 13px;
                margin-bottom: 4px;
                          
            }
            #customer_info .item label{
                font-weight:bold;
                margin-right:10px;                
                display:block;                
                white-space:nowrap;
            }
            #customer_info .item label.llb2{
                width:100px;
            }

            #customer_info .item .cname{
                border-bottom: 1px solid #e0e0e0;
                width: 100%;;
                padding-bottom: 5px;;
            }
            .wc-order-bulk-actions.wc-order-data-row-toggle .add-line-item{
                        display:none;
                    }
             #top_header{
                 display:flex;
                 width:100%;
                 padding:20px 0 0;
             }      
             #top_header img{ width:100%; } 
             #top_header .logo{
                 width:30%;
             }
             
             #top_header .title{
                 width:40%;
                 text-align:center;
             }
             #top_header .title h3,
             #top_header .title p{margin:0;}
             #top_header .title h3{ 
                font-family: serif;
                font-size: 25px;
                line-height: 1em;
            }
             #top_header .info{
                 width:30%;
             }
             #order_table2{
                 font-size:13px;
             }
             .other_info ul li{ display:flex;align-items:center;padding-right:15px;}
             .other_info ul li b{padding-right:10px; }
        </style>
        <?php
            global $wpdb,$post;
            $order = wc_get_order( $post->ID );
            $user_id   = $order->get_user_id();  

            $user_meta = get_customer_info($user_id);   
            $adderss = get_customer_address($user_meta[0]['customer_id']);

   
          //  print_r($user_meta);

            $saleman = '';
            if($user_meta[0]['staff_id']){
                $table_name =  $wpdb->prefix . 'comp_staff';;
                $sql_staff = "SELECT * FROM $table_name order by id Desc";
                $results = $wpdb->get_results($sql_staff);
                foreach($results as $sale){
                    // print_r($sale);
                    if($sale->staff_id==$user_meta[0]['staff_id']){
                        $saleman = $sale->staff_name;

                        
                    }
                };
            }
            
            if($user_id){
            ?>


            <div id="my_page">
                <div id="top_header">
                    <div class="logo"><a href="#"><img src="https://bosime.31app.tw/wp-content/uploads/2021/06/pdf_logo-e1623394551444.jpg" /></a></div>
                    <div class="title">
                        <h3>PROFORMA INVOICE</h3>
                        <p>報價單(ORDER NO): <?php echo $post->ID; ?></p>
                    </div>
                    <div class="info">
                        <img src="https://bosime.31app.tw/wp-content/uploads/2021/06/圖片-1.jpg" />
                    </div>
                </div>    

                <div id="customer_info">
                    <div class="c1">
                        <div class="item">
                            <label for="">單據日期</label>
                            <div class="cname"><?php echo get_the_date('Y/m/d',$post->ID); ?></div>
                        </div>
                    </div>    
                    <div class="c1">
                        <div class="item">
                            <label for="">客戶名稱</label>
                            <div class="cname"><?php  echo $user_meta[0]['cname']; ?></div>
                        </div>
                        <div class="item">
                            <label for="" class="llb2">CUST PO:</label>
                            <div class="cname"></div>
                        </div>
                    </div>

                    <div class="c1">
                        <div class="item">
                            <label for="">聯絡人</label>
                            <div class="cname"><?php  echo $user_meta[0]['contact']; ?></div>
                        </div>
                    </div>

                    <div class="c1">
                        <div class="item">
                            <label for="">付款方式</label>
                            <div class="cname">			
                                <?php echo $user_meta[0]['payment']; ?>	
                            </div>
                        </div>	
                    </div>					

                    <div class="c1">
                        <div class="item">
                            <label for="">電話</label>
                            <div class="cname"><?php  echo $user_meta[0]['contact_tel1']; ?></div>
                        </div>	
                        <div class="item">
                            <label for="" class="llb2">傳真(FAX NO):</label>
                            <div class="cname"><?php  echo $user_meta[0]['contact_fax']; ?></div>
                        </div>
                    </div>             

                    <div class="c1">
                        <div class="item">
                            <label for="">公司地址</label>
                            <div class="cname">
                                <?php  
                                    $select_cur =  get_post_meta($post->ID,'company_address_select',true);

                                    // echo $select_cur;

                                    
                                    if($select_cur){

                                        global $wpdb;
                                        $table =  $wpdb->prefix . 'customer_address';
                                        $sql = "SELECT * From ".$table." WHERE id='".$select_cur."'";                                    
                                        $results = $wpdb->get_results($sql,ARRAY_A);
                                        echo $results[0]['zip']." ".$results[0]['address_text'];

                                    }else{
                                        echo get_post_meta($post->ID,'company_address',true);
                                    }

                                    // echo get_post_meta($post->ID,'company_address',true);
                                ?>
                            </div>
                        </div>
                    </div>


                    <div class="c1">
                        <div class="item">
                            <label for="">輸往地區(SHIPPING TO):</label>
                            <div class="cname"></div>
                        </div>	
                        <div class="item">
                            <label for="" class="llb2">付款方式(PAYMENT):</label>
                            <div class="cname"></div>
                        </div>
                    </div>  

                </div>
            

            <?php 
            }
            ?>


	    <table class="order_table2" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-seq" style="border: 1px solid #444;padding:5px;text-align:center;">項次<br/>Seq</th>                    			
                    <th class="product-name">品名<br/>Description/Standard</th>
                    <th class="product-net">淨重<br/>N/W</th>
                    <th class="product-gross">毛重<br/>G/W</th>
                    <th class="product-quantity">數量<br/>Quantity</th>
                    <th class="product-price">單價<br/>Unit</th>                    
                    <th class="product-subtotal">總金額<br/>Amount</th>
                    <th class="product-subtotal">體積/公分<br/>Volume/cm</th>
                    <th class="product-subtotal">箱號<br/>C/NO</th>
                    <th class="product-subtotal">備註<br/>NOTEs</th>
                </tr>
            </thead>
            <tbody>
            <?php 
               
                                            
                    
                    $items = $order->get_items();


                    $all_cuft = 0;
                    $gross_wt = 0;

                   
                    $i=1;
                    foreach ( $items as $item ) {
                        $product_name = $item->get_name();
                        $product_id = $item->get_product_id();
                        $spec = get_product_meta($item->get_product_id());

                       
                        $all_cuft+= $spec[0]['cuft'] * $item->get_quantity();
                        $gross_wt += $spec[0]['gross_weight'] * $item->get_quantity();
        
                        $price = get_price_by_customer($user_id,$product_id);

                      //  print_r($spec);

                      
                        echo '<tr>';   
                        echo '<td>'.$i.'</td>';                                                              
                        echo '<td class="seq">'.$spec[0]['product_id'].$spec[0]['product_name'].'</td>';
                        echo '<td>'.$spec[0]['net_weight'].'</td>';
                        echo '<td>'.$spec[0]['gross_weight'].'</td>';
                        echo '<td>'.$item->get_quantity().'</td>';
                        echo '<td>'.$price.'</td>';
                        echo '<td>'.$item->get_subtotal().'</td>';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td>'.$order->get_customer_note().'</td>';
                        echo '</tr>';     
                        $i++;
                    }            
                ?>



                <tr>
                    <td colspan="2" class="tl"><b>SHIPPING DATE: </b></td>
                    <td colspan="4"  class="tl">
                        <b>材積 CUFT:</b>
                        <?php 
                            echo $all_cuft." CUFT<br/>"; 
                            /*
                            $cbm = round($all_cuft / 35.315,4);
                            echo "(".$cbm." CBM)<br/>";
                            echo "約需<b>".ceil($cbm/25).'</b>個20呎貨櫃<br/>';
                            if(ceil($cbm/25)>1){
                                echo "約需<b>".ceil($cbm/52).'</b>個40呎貨櫃<br/>';
                            } 
                            */                           
                        ?>
                    </td>
                    <td colspan="5" rowspan="2" >
                        <b> 總金額(TOTAL AMOUNT)</b>
                        <?php 			
                           echo  $order->get_total();
                        ?>	                    
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="tl" ><b>TERM OF PAYMENT:</b> <?php echo $user_meta[0]['termofpayment']; ?></td>
                    <td colspan="4" ><b>重量 WEIGHT:</b> <?php echo $gross_wt." KG" ?>	</td>                    
                </tr>


                <tr>                    
                    <td colspan="4" class="tl" >
                        <?php 
                            if($user_meta[0]['trade_mark']){
                                echo '<img src="'.$user_meta[0]['trade_mark'].'"  class="trade_mark" />';
                            }; 
                        ?>
                    </td>				
                    <td colspan="7">
                        
                    </td >
                </tr>               
            </tbody>
        </table>    

         <div class="other_info">
            <ul>
                <li>
                    <b>業務人員</b> 
                    <div class="info"><?php echo $saleman; ?></div>
                </li>
            </ul>
         </div>

        </div>
        </div>
        <?php

        // print_r($user_meta);

    }
}

// Save the data of the Meta field
add_action( 'save_post', 'mv_save_wc_order_other_fields', 10, 1 );
if ( ! function_exists( 'mv_save_wc_order_other_fields' ) )
{

    function mv_save_wc_order_other_fields( $post_id ) {

        // We need to verify this with the proper authorization (security stuff).

        // Check if our nonce is set.
        if ( ! isset( $_POST[ 'mv_other_meta_field_nonce' ] ) ) {
            return $post_id;
        }
        $nonce = $_REQUEST[ 'mv_other_meta_field_nonce' ];

        //Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce ) ) {
            return $post_id;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST[ 'post_type' ] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        // --- Its safe for us to save the data ! --- //

        // Sanitize user input  and update the meta field in the database.
        update_post_meta( $post_id, '_my_field_slug', $_POST[ 'my_field_name' ] );
    }
}
?>