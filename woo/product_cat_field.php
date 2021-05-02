<?php 

add_action( 'product_cat_add_form_fields', 'product_cat_add_term_fields' );
 
function product_cat_add_term_fields( $taxonomy ) {
 
	echo '<div class="form-field">
	<label for="misha-text">Text Field</label>
	<input type="text" name="misha-text" id="misha-text" />
	<p>Field description may go here.</p>
	</div>';
 
}

add_action( 'product_cat_edit_form_fields', 'product_cat_edit_term_fields', 10, 2 );
 
function product_cat_edit_term_fields( $term, $taxonomy ) {
 
	$value = get_term_meta( $term->term_id, 'misha-text', true );
	$layout = get_term_meta( $term->term_id, 'misha-layout', true );

	?>
	<tr class="form-field">
		<th>
			<label for="misha-text">Menu Name</label>
		</th>
		<td>
			<input name="misha-text" id="misha-text" type="text" value="<?php echo esc_attr( $value ); ?>" />
			<p class="description">Field description may go here.</p>
		</td>
	</tr>
	<tr class="form-field">
		<th>
			<label for="misha-text">產品排版</label>
		</th>
		<td>
			<select  name="misha-layout" id="product_layout">
				<option value="grid" <?php if($layout=='grid'){ echo 'selected'; } ?> >塊狀</option>	
				<option value="list" <?php if($layout=='list'){ echo 'selected'; } ?> >列表</option>				
			</select>			
		</td>
	</tr>
	<?php
 
}

add_action( 'created_product_cat', 'product_cat_save_term_fields' );
add_action( 'edited_product_cat', 'product_cat_save_term_fields' );
 
function product_cat_save_term_fields( $term_id ) {
 
	update_term_meta(
		$term_id,
		'misha-text',
		sanitize_text_field( $_POST[ 'misha-text' ] )
	);
 
	update_term_meta(
		$term_id,
		'misha-layout',
		sanitize_text_field( $_POST[ 'misha-layout' ] )
	);

}