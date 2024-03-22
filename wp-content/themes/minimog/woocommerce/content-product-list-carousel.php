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
	'style'             => '01',
	'caption_style'     => '01',
	'show_price'        => true,
	'show_rating'       => true,
	'show_category'     => true,
	'show_availability' => false,
];
$settings         = isset( $args['settings'] ) ? $args['settings'] : array();
$settings         = wp_parse_args( $settings, $default_settings );

$style_part = explode( '-', $settings['style'] );
$loop_style = isset( $style_part[1] ) ? $style_part[1] : '01';

$settings['loop_style']     = $loop_style;
$settings['thumbnail_size'] = ! empty( $settings['thumbnail_size'] ) ? $settings['thumbnail_size'] : Minimog_Woo::instance()->get_loop_product_image_size( 100 );
?>
<div <?php wc_product_class( '', $product ); ?>>
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
					<?php echo Minimog_Woo::instance()->get_product_image( $product, $settings['thumbnail_size'] ); ?>
				</div>

				<?php woocommerce_template_loop_product_link_close(); ?>
			</div>
		</div>

		<div class="product-info">
			<?php if ( ! empty( $settings['show_rating'] ) ) : ?>
				<?php woocommerce_template_loop_rating(); ?>
			<?php endif; ?>

			<?php if ( ! empty( $settings['show_category'] ) ) : ?>
				<?php wc_get_template( 'loop/category.php' ); ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>

			<?php
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			do_action( 'woocommerce_shop_loop_item_title' );
			?>

			<?php if ( ! empty( $settings['show_price'] ) ) : ?>
				<?php woocommerce_template_loop_price(); ?>
			<?php endif; ?>

			<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>

			<?php if ( ! empty( $settings['show_availability'] ) ) : ?>
				<?php wc_get_template( 'loop/availability.php' ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
