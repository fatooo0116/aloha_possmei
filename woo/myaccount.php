<?php


/*
 * Step 2. Register Permalink Endpoint
 */
add_action( 'init', 'misha_add_endpoint' );
function misha_add_endpoint() {
 
	// WP_Rewrite is my Achilles' heel, so please do not ask me for detailed explanation	
	add_rewrite_endpoint( 'customer-info', EP_PAGES );
}

add_action( 'woocommerce_account_customer-info_endpoint', 'misha_my_customerinfo_endpoint_content' );
function misha_my_customerinfo_endpoint_content() {
	?>
	
	<div class="mywish_list">
		<h1>Customer Info</h1>
		<?php 
			// echo do_shortcode('[yith_wcwl_wishlist]'); 
			
			$user_id = get_current_user_id();
			print_r($user_id);

			
			global $wpdb;
			$table_name =  $wpdb->prefix . 'customer_info';;

			$table_name =  $wpdb->prefix . 'customer_info';;
			$sql = "SELECT * FROM $table_name where woo_id=".$user_id;
			$results = $wpdb->get_results($sql,ARRAY_A);
			print_r($results);


			$sql = "SELECT * FROM $table_name";
			$results = $wpdb->get_results($sql,ARRAY_A);
			
			print_r($results[0]);			
			echo $_POST['custom_sn'];

		?>
		<form action="/my-account/customer-info/" method="post">
			<table>
				<thead>
					<tr>
						<th>客戶編號</th>
						<td><input name="customer_id" type="text" value="<?php echo $results[0]['customer_id']; ?>" /></td>
						<th>類別</th>
						<td><input name="custom_sn" type="text" /></td>
					</tr>
					<tr>
						<th>客戶全名</th>
						<td> <input name="cname" type="text" value="<?php echo $results[0]['cname']; ?>" /> </td>
						<th>區域</th>
						<td> <input name="custom_sn" type="text" /> </td>
					</tr>
					<tr>
						<th>款項歸屬</th>
						<td> <input name="custom_sn" type="text" /> </td>
						<th>幣別</th>
						<td> <input name="custom_sn" type="text" /> </td>
					</tr>	
					<tr>
						<td colspan="2">
							<label for=""><input type="radio" value="臨時"/> 臨時</label>
							<label for=""><input type="radio" value="外商"/> 外商</label>
							<label for=""><input type="radio" value="會員"/> 會員</label>
						</td>
						<td colspan="2">
							麥頭上傳
						</td>
					</tr>							
				</thead>
			</table>
			<div class="action">
				<button type="submit">save</button>
			</div>
		</form>
	</div>
	
	<?php
}





add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
 

	/* edit */


 
	unset( $menu_links['dashboard'] ); // Remove Dashboard
	//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	//unset( $menu_links['orders'] ); // Remove Orders
	unset( $menu_links['downloads'] ); // Disable Downloads
	unset( $menu_links['edit-account'] ); // Remove Account details tab
	//unset( $menu_links['customer-logout'] ); // Remove Logout link
 

	$menuOrder = array(		
		'orders'             => __( '訂單', 'woocommerce' ),					
		'customer-info' =>'客戶資料',
		// 'edit-account'    	=> __( '帳戶詳細資料', 'woocommerce' ),
		'customer-logout'    => __( 'Logout', 'woocommerce' ),	   
	);

	return $menuOrder ;
}


add_filter( 'woocommerce_checkout_fields', 'bbloomer_remove_woo_checkout_fields' );
  
function bbloomer_remove_woo_checkout_fields( $fields ) {
    unset( $fields['billing']['billing_last_name'] ); 
    return $fields;
}