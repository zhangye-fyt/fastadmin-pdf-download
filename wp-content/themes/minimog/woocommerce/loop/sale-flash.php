<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
 */

defined( 'ABSPATH' ) || exit;

global $post, $product;
$_html = '';

if ( ! $product->is_in_stock() ) {
	$_html .= '<div class="out-of-stock"><span>' . wp_kses( __( 'Sold <br />out', 'minimog' ), [ 'br' => array() ] ) . '</span></div>';
} else {

	if ( '1' === Minimog::setting( 'shop_badge_best_selling' ) && Minimog_Woo::instance()->is_product_in_best_selling() ) {
		$_html .= '<div class="best-seller"><span>' . wp_kses( __( 'Best <br />Seller', 'minimog' ), [ 'br' => array() ] ) . '</span></div>';
	}

	if ( $product->is_featured() && '1' === Minimog::setting( 'shop_badge_hot' ) ) {
		$_html .= '<div class="hot"><span>' . esc_html__( 'Hot', 'minimog' ) . '</span></div>';
	}

	if ( $product->is_on_sale() && '1' === Minimog::setting( 'shop_badge_sale' ) ) {
		$badge_classes = 'onsale';

		if ( ! empty( $product->get_date_on_sale_from( 'edit' ) ) && ! empty( $product->get_date_on_sale_to( 'edit' ) ) ) {
			$badge_classes = 'flash-sale has-icon';
		}

		$sale_badge_text = Minimog_Woo::instance()->get_product_sale_badge_text( $product );
		$_html           .= apply_filters( 'woocommerce_sale_flash', '<div class="' . $badge_classes . '"><span>' . $sale_badge_text . '</span></div>', $post, $product );
	}

	$badge_new_on = Minimog::setting( 'shop_badge_new' );

	if ( '1' === $badge_new_on ) {
		$new_arrivals    = intval( Minimog::setting( 'shop_badge_new_range' ) );
		$postdate        = get_the_time( 'Y-m-d', $product->get_id() );
		$post_date_stamp = strtotime( $postdate );

		if ( ( time() - ( 60 * 60 * 24 * $new_arrivals ) ) < $post_date_stamp ) {
			$_html .= '<div class="new"><span>' . esc_html__( 'New', 'minimog' ) . '</span></div>';
		}
	}
}

$_html = apply_filters( 'minimog/product_badge/html', $_html );

if ( $_html !== '' ) {
	$badges_style = Minimog::setting( 'shop_badges_style' );
	echo '<div class="product-badges product-badges-' . $badges_style . '">' . $_html . '</div>';
}
