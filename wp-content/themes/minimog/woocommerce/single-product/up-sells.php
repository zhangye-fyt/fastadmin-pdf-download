<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
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
 * @version       3.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $upsells ) ) {
	return;
}

$loop_style         = Minimog::setting( 'single_product_loop_style' );
$loop_caption_style = Minimog::setting( 'single_product_loop_caption_style' );
?>
<div class="up-sells upsells products entry-product-section">
	<div class="<?php echo Minimog\Woo\Single_Product::instance()->page_content_container_class(); ?>">
		<div class="entry-product-block">
			<?php
			$heading = Minimog_Woo::instance()->get_upsells_products_heading();

			if ( $heading ) : ?>
				<h2 class="entry-product-section-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php wc_get_template( 'custom/before-shop-loop-slider.php' ); ?>

			<?php foreach ( $upsells as $upsell ) : ?>

				<?php
				$post_object = get_post( $upsell->get_id() );
				setup_postdata( $GLOBALS['post'] =& $post_object );
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
