<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to minimog-child/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

$order_notes_is_enable        = apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) );
$order_notes_show_in_checkout = '1' === Minimog::setting( 'checkout_page_modal_customer_notes_enable' );
?>
<div id="woocommerce-checkout-review-order-table" class="woocommerce-order-details woocommerce-checkout-review-order-table">
	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

	<h3 id="order_review_heading"
	    class="checkout-order-review-heading"><?php esc_html_e( 'Order summary', 'minimog' ); ?></h3>
	<table class="wc-checkout-review-order-table">
		<tbody>
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			/**
			 * @var WC_Product $_product
			 */
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<td class="product-info">
						<div class="product-wrapper">
							<div class="product-thumbnail">
								<?php
								$product_image = Minimog_Woo::instance()->get_product_image( $_product, Minimog_Woo::instance()->get_loop_product_image_size( 80 ), [ 'lazy_load' => false ] );

								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $product_image, $cart_item, $cart_item_key );
								echo '' . $thumbnail;
								?>
							</div>

							<div class="product-caption">
								<h3 class="product-name">
									<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
									<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <span class="product-quantity">' . sprintf( 'x%s', $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</h3>
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
					</td>
					<td class="product-total">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
		</tbody>

		<tfoot>

		<tr class="cart-footer-actions-row">
			<td colspan="2">
				<div class="cart-footer-actions">
					<?php do_action( 'minimog/cart_actions/before' ); ?>

					<?php if ( $order_notes_is_enable && $order_notes_show_in_checkout ) : ?>
						<a href="#"
						   data-minimog-toggle="modal"
						   data-minimog-target="#modal-cart-order-notes"
						>
						<span class="icon">
						<svg class="w-[20px] h-[20px]" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
						     viewBox="0 0 19 19">
							<path fill="currentColor"
							      d="M17.3672 2.21875c.4453.44531.668.98437.668 1.61719 0 .60937-.2227 1.13672-.668 1.58203L4.99219 17.793l-4.007815.457H.878906c-.257812 0-.46875-.0938-.632812-.2812-.164063-.1876-.234375-.4102-.210938-.668l.457032-4.0078L12.8672.917969C13.3125.472656 13.8398.25 14.4492.25c.6328 0 1.1719.222656 1.6172.667969l1.3008 1.300781zM4.46484 16.7383l9.28126-9.28127-2.918-2.91797-9.28122 9.28124-.35157 3.2695 3.26953-.3515zM16.5938 4.60938c.2109-.21094.3164-.46875.3164-.77344 0-.32813-.1055-.59766-.3164-.8086l-1.336-1.33593c-.2109-.21094-.4805-.31641-.8086-.31641-.3047 0-.5625.10547-.7734.31641l-2.0391 2.03906 2.918 2.91797 2.0391-2.03906z"></path>
						</svg>
						</span>
							<span><?php esc_html_e( 'Note', 'minimog' ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
						<a href="#"
						   data-minimog-toggle="modal"
						   data-minimog-target="#modal-cart-shipping-calculator"
						>
						<span class="icon">
						<svg class="w-[22px] h-[22px]" fill="currentColor" stroke="currentColor"
						     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
							<path
								d="M280 192c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H40c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h240zm352 192h-24V275.9c0-16.8-6.8-33.3-18.8-45.2l-83.9-83.9c-11.8-12-28.3-18.8-45.2-18.8H416V78.6c0-25.7-22.2-46.6-49.4-46.6H113.4C86.2 32 64 52.9 64 78.6V96H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h240c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8H96V78.6c0-8.1 7.8-14.6 17.4-14.6h253.2c9.6 0 17.4 6.5 17.4 14.6V384H207.6C193 364.7 170 352 144 352c-18.1 0-34.6 6.2-48 16.4V288H64v144c0 44.2 35.8 80 80 80s80-35.8 80-80c0-5.5-.6-10.8-1.6-16h195.2c-1.1 5.2-1.6 10.5-1.6 16 0 44.2 35.8 80 80 80s80-35.8 80-80c0-5.5-.6-10.8-1.6-16H632c4.4 0 8-3.6 8-8v-16c0-4.4-3.6-8-8-8zm-488 96c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm272-320h44.1c8.4 0 16.7 3.4 22.6 9.4l83.9 83.9c.8.8 1.1 1.9 1.8 2.8H416V160zm80 320c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-96h-16.4C545 364.7 522 352 496 352s-49 12.7-63.6 32H416v-96h160v96zM256 248v-16c0-4.4-3.6-8-8-8H8c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8h240c4.4 0 8-3.6 8-8z"></path>
						</svg>
						</span>
							<span><?php esc_html_e( 'Shipping', 'minimog' ); ?></span>
						</a>
					<?php endif; ?>

					<?php if ( wc_coupons_enabled() ) : ?>
						<a href="#"
						   data-minimog-toggle="modal"
						   data-minimog-target="#modal-cart-coupon"
						>
						<span class="icon">
						<svg class="w-[22px] h-[22px]" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
						     viewBox="0 0 21 14">
							<path fill="currentColor"
							      d="M15.2812 3.875c.2344 0 .4336.08203.5977.24609.1641.16407.2461.36328.2461.59766v5.0625c0 .23435-.082.43355-.2461.59765-.1641.1641-.3633.2461-.5977.2461H5.71875c-.23437 0-.43359-.082-.59766-.2461-.16406-.1641-.24609-.3633-.24609-.59765v-5.0625c0-.23438.08203-.43359.24609-.59766.16407-.16406.36329-.24609.59766-.24609h9.56245zM15 9.5V5H6v4.5h9zm4.5-3.375c-.3047 0-.5742.11719-.8086.35156-.2109.21094-.3164.46875-.3164.77344s.1055.57422.3164.80859c.2344.21094.5039.31641.8086.31641h1.125v3.9375c0 .4687-.1641.8672-.4922 1.1953-.3281.3281-.7266.4922-1.1953.4922H2.0625c-.46875 0-.86719-.1641-1.195312-.4922C.539062 13.1797.375 12.7812.375 12.3125V8.375H1.5c.30469 0 .5625-.10547.77344-.31641.23437-.23437.35156-.5039.35156-.80859s-.11719-.5625-.35156-.77344C2.0625 6.24219 1.80469 6.125 1.5 6.125H.375V2.1875c0-.46875.164062-.86719.492188-1.195312C1.19531.664063 1.59375.5 2.0625.5h16.875c.4687 0 .8672.164063 1.1953.492188.3281.328122.4922.726562.4922 1.195312V6.125H19.5zm0 3.375c-.6094 0-1.1367-.22266-1.582-.66797-.4453-.44531-.668-.97265-.668-1.58203s.2227-1.13672.668-1.58203C18.3633 5.22266 18.8906 5 19.5 5V2.1875c0-.16406-.0586-.29297-.1758-.38672-.0937-.11719-.2226-.17578-.3867-.17578H2.0625c-.16406 0-.30469.05859-.42188.17578-.09374.09375-.14062.22266-.14062.38672V5c.60938 0 1.13672.22266 1.58203.66797.44531.44531.66797.97265.66797 1.58203s-.22266 1.13672-.66797 1.58203C2.63672 9.27734 2.10938 9.5 1.5 9.5v2.8125c0 .1641.04688.3047.14062.4219.11719.0937.25782.1406.42188.1406h16.875c.1641 0 .293-.0469.3867-.1406.1172-.1172.1758-.2578.1758-.4219V9.5z"></path>
						</svg>
						</span>
							<span><?php esc_html_e( 'Coupon', 'minimog' ); ?></span>
						</a>
					<?php endif; ?>

					<?php do_action( 'minimog/cart_actions/after' ); ?>
				</div>
			</td>
		</tr>

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
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="cart-totals-row cart-totals-fee fee">
				<th class="cart-totals-label"><?php echo esc_html( $fee->name ); ?></th>
				<td class="cart-totals-value"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="cart-totals-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th class="cart-totals-label"><?php echo esc_html( $tax->label ); ?></th>
						<td class="cart-totals-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="cart-totals-row tax-total">
					<th class="cart-totals-label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td class="cart-totals-value"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="cart-totals-row order-total">
			<th class="cart-totals-label"><?php esc_html_e( 'Total', 'minimog' ); ?></th>
			<td class="cart-totals-value"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		</tfoot>
	</table>
</div>
