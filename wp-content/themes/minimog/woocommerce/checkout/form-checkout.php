<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to minimog-child/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$left_column_class  = apply_filters( 'minimog/checkout/left_column/css_class', [
	'col-md-7',
	'col-checkout-forms-wrap',
] );
$right_column_class = apply_filters( 'minimog/checkout/right_column/css_class', [
	'col-md-5',
	'col-checkout-review-order',
] );
?>
	<div class="checkout-content-wrap">
		<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

		<?php
		// If checkout registration is disabled and not logged in, the user cannot checkout.
		if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
			echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'minimog' ) ) );

			return;
		}
		?>

		<form name="checkout" method="post" class="checkout woocommerce-checkout"
		      action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

			<div class="row row-checkout-wrap">
				<div class="<?php echo esc_attr( implode( ' ', $left_column_class ) ); ?>">
					<?php if ( $checkout->get_checkout_fields() ) : ?>

						<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

						<div id="customer_details">
							<?php do_action( 'woocommerce_checkout_billing' ); ?>

							<?php do_action( 'woocommerce_checkout_shipping' ); ?>
						</div>

						<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

					<?php endif; ?>
				</div>
				<div class="<?php echo esc_attr( implode( ' ', $right_column_class ) ); ?>">
					<div class="inner">

						<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

						<div id="order_review" class="woocommerce-checkout-review-order">
							<?php do_action( 'woocommerce_checkout_order_review' ); ?>
						</div>

						<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
					</div>
				</div>
			</div>

		</form>

		<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
	</div>
<?php
$order_notes_is_enable        = apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) );
$order_notes_show_in_checkout = '1' === Minimog::setting( 'checkout_page_modal_customer_notes_enable' );

if ( $order_notes_is_enable && $order_notes_show_in_checkout ) {
	wc_get_template( 'cart/modals/modal-order-notes.php' );
}

if ( 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
	wc_get_template( 'cart/modals/modal-shipping-calculator.php' );
}

if ( wc_coupons_enabled() ) {
	wc_get_template( 'cart/modals/modal-coupon.php' );
}
