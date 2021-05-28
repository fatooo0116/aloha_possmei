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
        <style>
            .order_table2{
                width: 100%;
                border-collapse: collapse;
                font-size:16px;
            }
            .order_table2 td, .order_table2 th {
                border: 1px solid #444;
                padding:5px;
                text-align:center;
            }
            #customer_info{
                padding:15px 5px;                
            }
            #customer_info .item{
                
                display: flex;
                font-size: 16px;
                margin-bottom: 10px;
                border-bottom: 1px dashed #999;
                padding-bottom: 5px;;
            }
            #customer_info .item label{
                font-weight:bold;
                margin-right:10px;
                display:block;
                width:70px;
            }
            .wc-order-bulk-actions.wc-order-data-row-toggle .add-line-item{
                        display:none;
                    }
        </style>


	    <table class="order_table2" cellspacing="0">
            <thead>
                <tr>
                    <th class="product-seq">項次</th>
                    <th class="product-id">產品編號</th>				
                    <th class="product-name">品名</th>
                    <th class="product-net">淨重</th>
                    <th class="product-gross">毛重</th>
                    <th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>                    
                    <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php 
                global $post;
                    $order = wc_get_order( $post->ID );
                    $user_id   = $order->get_user_id();                          
                    $user_meta = get_customer_info($user_id);   
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

                      
                        echo '<tr>';   
                        echo '<td>'.$i.'</td>';                                       
                        echo '<td class="seq">'.$spec[0]['product_id'].'</td>';
                        echo '<td class="seq">'.$spec[0]['product_name'].'</td>';
                        echo '<td>'.$spec[0]['net_weight'].'</td>';
                        echo '<td>'.$spec[0]['gross_weight'].'</td>';
                        echo '<td>'.$item->get_quantity().'</td>';
                        echo '<td>'.$price.'</td>';
                        echo '<td>'.$item->get_subtotal().'</td>';
                        echo '</tr>';     
                        $i++;
                    }            
                ?>

                <tr>                    
                    <td colspan="3" rowspan="4" >
                        <?php 
                            if($user_meta[0]['trade_mark']){
                                echo '<img src="'.$user_meta[0]['trade_mark'].'"  class="trade_mark" />';
                            }; 
                        ?>
                    </td>				
                    <td colspan="2">
                        材積 CUFT:
                    </td >
                    <td colspan="3">
                        <?php
                            echo $all_cuft." CUFT<br/>"; 
                            $cbm = round($all_cuft / 35.315,4);
                            echo "(".$cbm." CBM)<br/>";
                            echo "約需<b>".ceil($cbm/25).'</b>個20呎貨櫃<br/>';
                            if(ceil($cbm/25)>1){
                                echo "約需<b>".ceil($cbm/52).'</b>個40呎貨櫃<br/>';
                            }
                            
                        ?>
                    </td>
                </tr>


                <tr>
                    <td colspan="2">
                        TERM OF PAYMENT:
                    </td>
                    <td colspan="3">	
                        <?php echo $user_meta[0]['termofpayment']; ?>		
                    </td>
			    </tr>

			    <tr>
                    <td colspan="2">
                        重量 WEIGHT:
                    </td>
                    <td colspan="3">
                        <?php echo $gross_wt." KG" ?>				
                    </td>
			    </tr>

                <tr>
                    <td colspan="2">
                    總金額
                    </td>
                    <td colspan="3">
                    <?php 			
                           echo  $order->get_total();
                        ?>				
                    </td>
                </tr>                
            </tbody>
        </table>    

         <?php      

				$adderss = get_customer_address($user_meta[0]['customer_id']);

				if($user_id){
				?>
				<div id="customer_info">
					<div class="c1">
						<div class="item">
							<label for="">客戶名稱</label>
							<div class="cname"><?php  echo $user_meta[0]['cname']; ?></div>
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
					</div>

					<div class="c1">
						<div class="item">
							<label for="">傳真</label>
							<div class="cname"><?php  echo $user_meta[0]['contact_fax']; ?></div>
						</div>
					</div>

					<div class="c1">
						<div class="item" style="display:block;">
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
				</div>

				<?php 
				}
				?>

        <?php

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