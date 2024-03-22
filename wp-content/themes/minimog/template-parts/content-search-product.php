<?php
/**
 * Template part for displaying search product single loop item
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimog
 * @since   1.0.0
 */

$product_grid_style = Minimog::setting( 'shop_archive_grid_style' );

/**
 * Hook: woocommerce_shop_loop.
 *
 * @hooked WC_Structured_Data::generate_product_data() - 10
 */
do_action( 'woocommerce_shop_loop' );

wc_get_template_part( 'content-product', $product_grid_style );
