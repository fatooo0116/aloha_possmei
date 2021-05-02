<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

$text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2>
	<?php
	if ( $sent_to_admin ) {
		$before = '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">';
		$after  = '</a>';
	} else {
		$before = '';
		$after  = '';
	}
	/* translators: %s: Order ID. */
	echo wp_kses_post( $before . sprintf( __( '[Order #%s]', 'woocommerce' ) . $after . ' (<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ) );
	?>
</h2>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
		<thead>
			<tr>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			echo wc_get_email_order_items( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$order,
				array(
					'show_sku'      => $sent_to_admin,
					'show_image'    => false,
					'image_size'    => array( 32, 32 ),
					'plain_text'    => $plain_text,
					'sent_to_admin' => $sent_to_admin,
				)
			);
			?>
		</tbody>
		<tfoot>
			<?php
			$item_totals = $order->get_order_item_totals();

			if ( $item_totals ) {
				$i = 0;
				foreach ( $item_totals as $total ) {
					$i++;
					?>
					<tr>
						<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['label'] ); ?></th>
						<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo wp_kses_post( $total['value'] ); ?></td>
					</tr>
					<?php
				}
			}
			if ( $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
				</tr>
				<?php
			}
			?>
		</tfoot>
	</table>
</div>



<h4><?php _e( '報名資訊' ); ?></h4>
<table  id="addresses" cellspacing="0" cellpadding="0" style="width: 100%; vertical-align: top; margin-bottom: 40px; padding:0;" border="0">
	<tr>
<?php
	$th='color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:1px';
	$td="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word";

          echo '<tr ><th  style="'.$th.'">' . __( '姓名' ) . ':</th><td  style="'.$th.'">' . $order->get_billing_last_name() . '</td></tr>';
          echo '<tr ><th style="'.$th.'">' . __( '生日' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'birthday', true ) . '</td></tr>';
          echo '<tr><th style="'.$th.'">' . __( '性別' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'sex', true ) . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '行動電話' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'mobile', true ) . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '聯絡電話' ) . ':</th><td style="'.$td.'">' . $order->get_billing_phone() . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( 'Line ID' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'line_id', true ) . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( 'Email' ) . ':</th><td style="'.$td.'">' . $order->get_billing_email() . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '地址' ) . ':</th><td style="'.$td.'">' . $order->get_billing_address_1() . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '可上課星期' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'week_class', true ) . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '可上課時段' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'time_class', true ) . '</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '學習目的' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'why_po', true ).' '.get_post_meta( $order->id, 'why_po_other', true ).'</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '日語學習經歷' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'jp_exp', true ).' '.get_post_meta( $order->id, 'jp_exp_other', true ).'</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '日語學習機構' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'jp_school', true ) .' '.get_post_meta( $order->id, 'jp_school_other', true ).'</td></tr>'; 
          echo '<tr><th style="'.$th.'">' . __( '日語檢定(JLPT)' ) . ':</th><td style="'.$td.'">' . get_post_meta( $order->id, 'jp_exam', true ) . '</td></tr>'; 
?>
	</tr>
</table>	



<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>
