<?php
/**
 * Caption style 06
 *
 * @package   Minimog
 * @since     1.0.0
 * @version   2.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
extract( $args );
?>

<?php if ( ! empty( $settings['show_variation'] ) ) : ?>
	<?php wc_get_template( 'loop/variation-form.php', [ 'thumbnail_size' => $settings['thumbnail_size'] ] ); ?>
<?php endif; ?>

<?php if ( ! empty( $settings['show_category'] ) ) : ?>
	<?php wc_get_template( 'loop/category.php' ); ?>
<?php endif; ?>

<?php if ( ! empty( $settings['show_brand'] ) ) : ?>
	<?php wc_get_template( 'loop/brand.php' ); ?>
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

<?php if ( ! empty( $settings['show_rating'] ) ) : ?>
	<?php woocommerce_template_loop_rating(); ?>
<?php endif; ?>

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

<?php if ( ! empty( $settings['show_stock_bar'] ) ) : ?>
	<?php wc_get_template( 'loop/stock-progress-bar.php' ); ?>
<?php endif; ?>

<?php if ( ! empty( $settings['caption_has_button'] ) ) : ?>
	<?php woocommerce_template_loop_add_to_cart(); ?>
<?php endif; ?>
