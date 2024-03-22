<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product instanceof WC_Product || ! $product->is_visible() ) {
	return;
}

$product_id = $product->get_id();

$thumbnail_size = Minimog_Woo::instance()->get_loop_product_image_size();
if ( isset( $settings ) ) { // Override size with Elementor.
	$thumbnail_size = Minimog_Image::elementor_parse_image_size( $settings, $thumbnail_size );
}

$item_class[] = 'grid-item';

$has_hover_thumbnail = false;

if ( '1' === Minimog::setting( 'shop_archive_hover_image' ) && ! Minimog::is_handheld() ) {
	$gallery_ids = $product->get_gallery_image_ids();

	if ( $gallery_ids && ! empty( $gallery_ids ) ) {
		$has_hover_thumbnail = true;

		$item_class[] = 'has-hover-thumbnail';
	}
}
?>
<div <?php wc_product_class( $item_class, $product ); ?>>
	<div class="product-wrapper">
		<div class="product-thumbnail">
			<?php
			if ( function_exists( 'woocommerce_show_product_loop_sale_flash' ) ) {
				woocommerce_show_product_loop_sale_flash();
			}
			?>

			<div class="thumbnail">
				<?php woocommerce_template_loop_product_link_open(); ?>

				<div class="product-main-image">
					<?php echo Minimog_Woo::instance()->get_product_image( $product, $thumbnail_size ); ?>
				</div>

				<?php if ( $has_hover_thumbnail ) { ?>
					<div class="product-hover-image">
						<?php
						Minimog_Image::the_attachment_by_id( array(
							'id'   => $gallery_ids[0],
							'size' => $thumbnail_size,
						) );
						?>
					</div>
				<?php } ?>

				<?php woocommerce_template_loop_product_link_close(); ?>
			</div>
		</div>

		<div class="product-info">
			<?php
			do_action( 'woocommerce_before_shop_loop_item_title' );

			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );

			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>

			<?php woocommerce_template_loop_price(); ?>

			<?php wc_get_template( 'custom/add-to-cart-variation.php' ); ?>
		</div>
	</div>
</div>
