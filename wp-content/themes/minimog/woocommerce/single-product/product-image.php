<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.1
 *
 * @theme-version 1.11.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$thumbnail_id   = 0;

if ( has_post_thumbnail() ) {
	$thumbnail_id = (int) get_post_thumbnail_id();
	array_unshift( $attachment_ids, $thumbnail_id );
}

if ( empty( $attachment_ids ) ) {
	echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_attr__( 'Placeholder', 'minimog' ) ), $product->get_id() );

	return;
}

$is_quick_view = apply_filters( 'minimog/quick_view/is_showing', false );

$wrapper_classes = 'woo-single-gallery woocommerce-product-gallery';

$feature_style = Minimog_Woo::instance()->get_single_product_images_style();

if ( true === $is_quick_view ) {
	$feature_style = 'slider-02';
}

$wrapper_classes .= " feature-style-$feature_style";

$open_gallery = apply_filters( 'minimog/single_product/open_gallery', true );
if ( $open_gallery ) {
	$wrapper_classes .= ' has-light-gallery';
}

wc_get_template( "single-product/product-image-{$feature_style}.php", [
	'thumbnail_id'     => $thumbnail_id,
	'attachment_ids'   => $attachment_ids,
	'is_quick_view'    => $is_quick_view,
	'wrapper_classes'  => $wrapper_classes,
	'main_image_size'  => Minimog_Woo::instance()->get_single_product_image_size_by_feature_style( $feature_style, $is_quick_view ),
	'thumb_image_size' => Minimog_Woo::instance()->get_single_product_thumb_size(),
	'open_gallery'     => $open_gallery,
] );
