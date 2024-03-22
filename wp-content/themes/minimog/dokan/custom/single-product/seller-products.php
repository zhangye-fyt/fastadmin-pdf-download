<?php
/**
 * More products from current seller
 *
 * This template can be overridden by copying it to yourtheme/dokan/custom/single-product/seller-products.php.
 *
 * @package     Minimog/Dokan/Templates
 * @version     1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $seller_products ) ) {
	return;
}

$loop_style         = Minimog::setting( 'single_product_loop_style' );
$loop_caption_style = Minimog::setting( 'single_product_loop_caption_style' );
?>
<div class="seller products entry-product-section">
	<div class="<?php echo Minimog\Woo\Single_Product::instance()->page_content_container_class(); ?>">
		<div class="entry-product-block">
			<?php
			$heading = apply_filters( 'woocommerce_product_seller_products_heading', __( 'More products', 'minimog' ) );

			if ( $heading ) :
				?>
				<h2 class="entry-product-section-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php wc_get_template( 'custom/before-shop-loop-slider.php' ); ?>

			<?php foreach ( $seller_products as $seller_product ) : ?>

				<?php
				$post_object = get_post( $seller_product->get_id() );
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
