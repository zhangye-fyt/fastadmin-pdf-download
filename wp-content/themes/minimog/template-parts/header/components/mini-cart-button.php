<?php
/**
 * Mini cart button on header
 *
 * @package Minimog
 * @since   1.0.0
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $woocommerce;
$cart_url = isset( $woocommerce ) ? wc_get_cart_url() : '/cart';

$icon_display = Minimog::setting( 'header_cart_icon_display' );
$icon_style   = Minimog::setting( 'header_cart_icon_style' );

$cart_html  = '';
$link_class = "mini-cart__button has-badge hint--bounce hint--bottom style-{$icon_style} icon-display--{$icon_display} header-icon";
$qty        = ! empty( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

ob_start();
wc_cart_totals_order_total_html();
$cart_total = ob_get_clean();

$cart_total_html = '<div class="mini-cart-total">' . $cart_total . '</div>';
$cart_badge_html = '<div class="icon-badge mini-cart-badge" data-count="' . $qty . '">' . $qty . '</div>';

switch ( $icon_style ) :
	case 'icon-set-02':
		$svg       = Minimog_SVG_Manager::instance()->get( 'shopping-bag-light' );
		$cart_html = '<div class="icon">' . $svg . $cart_badge_html . '</div>';
		break;
	case 'icon-set-03':
		$svg       = Minimog_SVG_Manager::instance()->get( 'phr-shopping-bag' );
		$cart_html = '<div class="icon">' . $svg . $cart_badge_html . '</div>';
		break;
	case 'icon-set-04':
		$svg       = Minimog_SVG_Manager::instance()->get( 'shopping-bag-solid' );
		$cart_html = '<div class="icon">' . $svg . $cart_badge_html . '</div>';
		break;
	case 'icon-set-05':
		$svg       = Minimog_SVG_Manager::instance()->get( 'phb-shopping-cart-simple' );
		$cart_html = '<div class="icon">' . $svg . $cart_badge_html . '</div>';
		break;
	case 'icon-circle-price-01':
		$svg        = Minimog_SVG_Manager::instance()->get( 'shopping-bag' );
		$cart_html  = $cart_total_html . '<div class="icon">' . $svg . $cart_badge_html . '</div>';
		$link_class .= ' header-icon-circle';
		break;
	case 'icon-circle-price-02':
		$svg        = Minimog_SVG_Manager::instance()->get( 'shopping-basket' );
		$cart_html  = '<div class="icon">' . $svg . $cart_badge_html . '</div>';
		$link_class .= ' header-icon-circle';
		break;
	default:
		$svg       = Minimog_SVG_Manager::instance()->get( 'shopping-bag' );
		$cart_html .= '<div class="icon">' . $svg . $cart_badge_html . '</div>';
		break;
endswitch;
?>
<a href="<?php echo esc_url( $cart_url ); ?>" class="<?php echo esc_attr( $link_class ); ?>"
   aria-label="<?php esc_attr_e( 'Cart', 'minimog' ); ?>">
	<?php echo '' . $cart_html; ?>
	<?php if ( in_array( $icon_display, [ 'text', 'icon-text' ], true ) ): ?>
		<span class="text"><?php esc_html_e( 'Cart', 'minimog' ); ?></span>
	<?php endif; ?>
</a>
