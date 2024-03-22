<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

defined( 'ABSPATH' ) || exit;

$is_quick_view = apply_filters( 'minimog/quick_view/is_showing', false );

$wishlist_btn = '';

if ( '1' === Minimog::setting( 'single_product_wishlist_enable' ) ) {
	ob_start();
	Minimog\Woo\Wishlist::output_button( [
		'style'            => '02',
		'show_tooltip'     => true,
		'tooltip_position' => 'left',
	] );
	$wishlist_btn = ob_get_clean();
}

if ( $is_quick_view ) {
	the_title( '<div class="product-title-wrap"><h3 class="product_title entry-title"><a href="' . esc_url( get_the_permalink() ) . '" class="link-in-title">', '</a></h3>' . $wishlist_btn . '</div>' );

} else {
	the_title( '<div class="product-title-wrap"><h1 class="product_title entry-title"><span>', '</span></h1>' . $wishlist_btn . '</div>' );
}
