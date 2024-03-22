<?php
/**
 * Gift Card product add to cart
 *
 * @author    Yithemes
 * @package   yith-woocommerce-gift-cards\templates\single-product\add-to-cart
 *
 * @updated   Minimog
 * @edited    Add Buy now + missing quantity hook
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$product_id = $product->get_id();

$add_to_cart_button_class = 'single_add_to_cart_button gift_card_add_to_cart_button button alt';

if ( '1' === Minimog::setting( 'single_product_buy_now_enable' ) ) {
	$add_to_cart_button_class .= ' button-2';
}
?>
<div class="gift_card_template_button variations_button entry-product-quantity-wrapper">
	<?php if ( ! $product->is_sold_individually() ) : ?>
		<?php do_action( 'woocommerce_before_add_to_cart_quantity' ); ?>
		<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); //phpcs:ignore --nonce and wc function?>
		<?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>
	<?php endif; ?>
	<button type="submit"
	        class="<?php echo esc_attr( $add_to_cart_button_class ); ?> <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>"><?php echo esc_html( apply_filters( 'ywgc_add_to_cart_button_text', $product->single_add_to_cart_text() ) ); ?></button>
	<?php if ( '1' === Minimog::setting( 'single_product_buy_now_enable' ) ) : ?>
		<button type="submit" name="minimog-buy-now" value="<?php echo esc_attr( $product->get_id() ); ?>"
		        class="single_add_to_cart_button gift_card_add_to_cart_button button alt button-buy-now <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ); ?>"
		        data-redirect="<?php echo esc_url( wc_get_checkout_url() ); ?>">
			<span><?php echo esc_html( \Minimog\Woo\Buy_Now::instance()->get_button_text() ); ?></span></button>
	<?php endif; ?>
	<input type="hidden" name="add-to-cart" value="<?php echo absint( $product_id ); ?>"/>
	<input type="hidden" name="product_id" value="<?php echo absint( $product_id ); ?>"/>
</div>
