<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.4.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>
	<div class="woocommerce-cart-form"> <?php // Change form to div to prevent form submit on enter quantity. ?>
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div id="cart-table-wrap" class="woocommerce-cart-form-wrapper">
			<?php wc_get_template( 'cart/cart-content.php' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>

		<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

		<div id="cart-collaterals" class="cart-collaterals">
			<?php
			/**
			 * Cart collaterals hook.
			 *
			 * @hooked woocommerce_cross_sell_display
			 * @hooked woocommerce_cart_totals - 10
			 */
			do_action( 'woocommerce_cart_collaterals' );
			?>
		</div>
	</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

<?php
if ( '1' === Minimog::setting( 'shopping_cart_modal_customer_notes_enable' ) && apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) {
	wc_get_template( 'cart/modals/modal-order-notes.php' );
}

if ( 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
	wc_get_template( 'cart/modals/modal-shipping-calculator.php' );
}

if ( wc_coupons_enabled() ) {
	wc_get_template( 'cart/modals/modal-coupon.php' );
}
