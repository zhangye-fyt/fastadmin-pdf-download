<?php
/**
 * Mini cart button on search popup
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
$link_class = "mini-cart__button has-badge hint--bounce hint--bottom popup-search-icon";
$qty        = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

$cart_badge_html = '<div class="icon-badge mini-cart-badge" data-count="' . $qty . '">' . $qty . '</div>';

$icon_style = Minimog::setting( 'header_cart_icon_style' );
$icon_key   = 'shopping-bag';
switch ( $icon_style ) :
	case 'icon-set-02':
		$icon_key = 'shopping-bag-light';
		break;
	case 'icon-set-03':
		$icon_key = 'phr-shopping-bag';
		break;
	case 'icon-set-04':
		$icon_key = 'shopping-bag-solid';
		break;
	case 'icon-set-05':
		$icon_key = 'phb-shopping-cart-simple';
		break;
	case 'icon-circle-price-01':
		$icon_key = 'shopping-bag';
		break;
	case 'icon-circle-price-02':
		$icon_key = 'shopping-basket';
		break;
endswitch;

$cart_html .= '<div class="icon">' . Minimog_SVG_Manager::instance()->get( $icon_key ) . $cart_badge_html . '</div>';
?>
<a href="<?php echo esc_url( $cart_url ); ?>" class="<?php echo esc_attr( $link_class ); ?>"
   aria-label="<?php esc_attr_e( 'Cart', 'minimog' ); ?>">
	<?php echo '' . $cart_html; ?>
</a>
