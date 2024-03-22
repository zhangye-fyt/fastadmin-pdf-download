<?php
/**
 * Mini cart button on mobile tabs
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! Minimog_Woo::instance()->is_activated() ) {
	return;
}

global $woocommerce;
$cart_url = isset( $woocommerce ) ? wc_get_cart_url() : '/cart';

$cart_html  = '';
$link_class = "mini-cart__button has-badge mobile-tab-link";
$qty        = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

$cart_badge_html = '<div class="icon-badge mini-cart-badge" data-count="' . $qty . '">' . $qty . '</div>';

$svg       = Minimog_SVG_Manager::instance()->get( 'shopping-bag' );
$cart_html .= '<div class="icon">' . $svg . $cart_badge_html . '</div>';
?>
<a href="<?php echo esc_url( $cart_url ); ?>" class="<?php echo esc_attr( $link_class ); ?>" aria-label="<?php esc_attr_e( 'Cart', 'minimog' ); ?>">
	<?php echo '' . $cart_html; ?>
</a>
