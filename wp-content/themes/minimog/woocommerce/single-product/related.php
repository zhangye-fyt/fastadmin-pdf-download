<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $related_products ) ) {
	return;
}

$loop_style         = Minimog::setting( 'single_product_loop_style' );
$loop_caption_style = Minimog::setting( 'single_product_loop_caption_style' );
?>
<div class="related products entry-product-section">
	<div class="<?php echo Minimog\Woo\Single_Product::instance()->page_content_container_class(); ?>">
		<div class="entry-product-block">
			<?php
			$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'minimog' ) );

			if ( $heading ) : ?>
				<h2 class="entry-product-section-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php wc_get_template( 'custom/before-shop-loop-slider.php' ); ?>

			<?php foreach ( $related_products as $related_product ) : ?>
				<?php
				$post_object = get_post( $related_product->get_id() );
				setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
				?>
				<?php minimog_get_wc_template_part( 'content-product', $loop_style, [
					'settings' => [
						'layout'        => 'slider',
						'style'         => $loop_style,
						'caption_style' => $loop_caption_style,
					],
				] ); ?>
			<?php endforeach; ?>
			<?php wp_reset_postdata(); ?>

			<?php wc_get_template( 'custom/after-shop-loop-slider.php' ); ?>
		</div>
	</div>
</div>
