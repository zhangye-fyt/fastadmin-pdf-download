<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;
?>
<table class="cart-totals-table">
	<tfoot>
	<tr class="cart-totals-row cart-subtotal">
		<th class="cart-totals-label"><?php esc_html_e( 'Subtotal', 'minimog' ); ?></th>
		<td class="cart-totals-value"><?php wc_cart_totals_subtotal_html(); ?></td>
	</tr>

	<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
		<?php
		$coupon_label = wc_cart_totals_coupon_label( $coupon, false );
		?>
		<tr class="cart-totals-row cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
			<th class="cart-totals-label"><?php echo esc_html( $coupon_label ); ?></th>
			<td class="cart-totals-value" data-title="<?php echo esc_attr( $coupon_label ); ?>"><?php Minimog_Woo::instance()->cart_totals_coupon_html( $coupon ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
		<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

		<?php wc_cart_totals_shipping_html(); ?>

		<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
	<?php endif; ?>

	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<tr class="cart-totals-row cart-totals-fee fee">
			<th class="cart-totals-label"><?php echo esc_html( $fee->name ); ?></th>
			<td class="cart-totals-value"><?php wc_cart_totals_fee_html( $fee ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php
	if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
		$taxable_address = WC()->customer->get_taxable_address();
		$estimated_text  = '';

		if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
			/* translators: %s location. */
			$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'minimog' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
		}

		if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
			foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
				?>
				<tr class="cart-totals-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th class="cart-totals-label"><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
					<td class="cart-totals-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
				</tr>
				<?php
			}
		} else {
			?>
			<tr class="cart-totals-row tax-total">
				<th
					class="cart-totals-label"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
				<td class="cart-totals-value"><?php wc_cart_totals_taxes_total_html(); ?></td>
			</tr>
			<?php
		}
	}
	?>

	<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

	<tr class="cart-totals-row order-total">
		<th class="cart-totals-label"><?php esc_html_e( 'Total', 'minimog' ); ?></th>
		<td class="cart-totals-value"><?php wc_cart_totals_order_total_html(); ?></td>
	</tr>

	<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
	</tfoot>
</table>
