<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$add_to_cart_button_class = 'single_add_to_cart_button ajax_add_to_cart button alt';

if ( '1' === Minimog::setting( 'single_product_buy_now_enable' ) ) {
	$add_to_cart_button_class .= ' button-2';
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart"
	      action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>"
	      method="post" enctype='multipart/form-data'>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="entry-product-quantity-wrapper">
			<?php
			do_action( 'woocommerce_before_add_to_cart_quantity' );

			Minimog_Woo::instance()->output_add_to_cart_quantity_html( array(
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
				// WPCS: CSRF ok, input var ok.
			) );

			do_action( 'woocommerce_after_add_to_cart_quantity' );
			?>

			<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"
			        class="<?php echo esc_attr( $add_to_cart_button_class ); ?> <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>">
				<span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span></button>

			<?php if ( '1' === Minimog::setting( 'single_product_buy_now_enable' ) ) : ?>
				<button type="submit" name="minimog-buy-now" value="<?php echo esc_attr( $product->get_id() ); ?>"
				        class="single_add_to_cart_button ajax_add_to_cart button alt button-buy-now <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>"
				        data-redirect="<?php echo esc_url( wc_get_checkout_url() ); ?>">
					<span><?php echo esc_html( \Minimog\Woo\Buy_Now::instance()->get_button_text() ); ?></span></button>
			<?php endif; ?>

			<?php
			/**
			 * This hidden make buy now button working properly.
			 */
			?>
			<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>"/>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
