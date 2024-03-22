<?php
/**
 * Cart data using in JS
 */

defined( 'ABSPATH' ) || exit;

$qty = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

$data = [
	'count'                    => $qty,
	'free_shipping_class_only' => \Minimog\Woo\Free_Shipping_Label::instance()->cart_contains_only_items_free_shipping_class() ? 1 : 0,
];
?>
<div class="cart-data-js">
	<div data-value="<?php echo esc_attr( wp_json_encode( $data ) ); ?>" class="cart-data-info"></div>
</div>
