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

/**
 * @var array $args Elementor widget settings
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product instanceof WC_Product || ! $product->is_visible() ) {
	return;
}

$product_id = $product->get_id();

$default_settings = [
	'style'               => Minimog::setting( 'shop_archive_grid_style' ),
	'caption_style'       => Minimog::setting( 'shop_archive_grid_caption_style' ),
	'show_price'          => Minimog::setting( 'shop_archive_show_price' ),
	'show_variation'      => Minimog::setting( 'shop_archive_show_variation' ),
	'show_rating'         => Minimog::setting( 'shop_archive_show_rating' ),
	'show_category'       => Minimog::setting( 'shop_archive_show_category' ),
	'show_brand'          => Minimog::setting( 'shop_archive_show_brand' ),
	'show_stock_bar'      => Minimog::setting( 'shop_archive_show_stock_bar' ),
	'show_availability'   => Minimog::setting( 'shop_archive_show_availability' ),
	'layout'              => 'grid',
	'badges_in_thumbnail' => true,
];
$settings         = isset( $args['settings'] ) ? $args['settings'] : array();
$settings         = wp_parse_args( $settings, $default_settings );

$style_part = explode( '-', $settings['style'] );
$loop_style = isset( $style_part[1] ) ? $style_part[1] : '01';

$settings['loop_style']     = $loop_style;
$settings['thumbnail_size'] = ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : Minimog_Woo::instance()->get_loop_product_image_size();

$item_class   = array();
$item_class[] = 'slider' === $settings['layout'] ? 'swiper-slide' : 'grid-item';

$has_hover_thumbnail = false;

if ( '1' === Minimog::setting( 'shop_archive_hover_image' ) && ! Minimog::is_handheld() ) {
	$gallery_ids = $product->get_gallery_image_ids();

	if ( $gallery_ids && ! empty( $gallery_ids ) ) {
		$has_hover_thumbnail = true;

		$item_class[] = 'has-hover-thumbnail';
	}
}

switch ( $settings['loop_style'] ) {
	case '05':
		$settings['caption_has_button'] = true;
		break;
	case '06':
	case '07':
		$settings['background_has_button'] = true;
		break;
	case '08':
		$settings['caption_has_button']  = true;
		$settings['badges_in_thumbnail'] = false;
		break;
	case '10':
		$settings['caption_has_button']  = true;
		$settings['badges_in_thumbnail'] = false;
}
?>
<div <?php wc_product_class( $item_class, $product ); ?>>
	<div class="product-wrapper">
		<?php if ( ! $settings['badges_in_thumbnail'] && function_exists( 'woocommerce_show_product_loop_sale_flash' ) ) : ?>
			<?php woocommerce_show_product_loop_sale_flash(); ?>
		<?php endif; ?>

		<div class="product-thumbnail">
			<?php if ( $settings['badges_in_thumbnail'] && function_exists( 'woocommerce_show_product_loop_sale_flash' ) ) : ?>
				<?php woocommerce_show_product_loop_sale_flash(); ?>
			<?php endif; ?>

			<div class="thumbnail">
				<?php woocommerce_template_loop_product_link_open(); ?>

				<div class="product-main-image">
					<?php echo Minimog_Woo::instance()->get_product_image( $product, $settings['thumbnail_size'] ); ?>
				</div>

				<?php if ( $has_hover_thumbnail ) { ?>
					<div class="product-hover-image">
						<?php
						Minimog_Image::the_attachment_by_id( array(
							'id'   => $gallery_ids[0],
							'size' => $settings['thumbnail_size'],
						) );
						?>
					</div>
				<?php } ?>

				<?php woocommerce_template_loop_product_link_close(); ?>
			</div>
			<?php minimog_get_wc_template_part( 'loop/product-actions', $loop_style, [
				'settings' => $settings,
			] ); ?>
		</div>

		<div class="product-info">
			<?php minimog_get_wc_template_part( 'loop/caption', $settings['caption_style'], [
				'settings' => $settings,
			] ); ?>

			<?php if ( ! empty( $settings['show_list_view'] ) ) : ?>
				<?php wc_get_template( 'custom/content-product-view-list.php' ); ?>
			<?php endif; ?>
		</div>
	</div>
	<?php if ( in_array( $loop_style, [ '02', '03', '06', '07', '09', '10' ] ) ) : ?>
		<div class="background-color-expand">
			<?php
			if ( ! empty( $settings['background_has_button'] ) ) {
				woocommerce_template_loop_add_to_cart();
			}
			?>
		</div>
	<?php endif; ?>
</div>
