<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>

	<div class="cross-sells products">
		<?php
		$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may also like', 'minimog' ) );

		if ( $heading ) :
			?>
			<h2 class="cross-sells-products-heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>

		<?php
		$slider_args = [
			'data-items-desktop'       => '4',
			'data-items-tablet-extra'  => '3',
			'data-items-mobile-extra'  => '2',
			'data-gutter-desktop'      => '30',
			'data-gutter-tablet-extra' => '20',
			'data-gutter-mobile-extra' => '16',
			'data-nav'                 => '1',
		];
		?>
		<div
			class="tm-swiper tm-slider minimog-product group-style-01 style-carousel-01" <?php echo Minimog_Helper::slider_args_to_html_attr( $slider_args ); ?>>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php foreach ( $cross_sells as $cross_sell ) : ?>
							<?php
							$post_object = get_post( $cross_sell->get_id() );
							setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
							?>
							<?php minimog_get_wc_template_part( 'content-product', 'carousel-01', [
								'settings' => [
									'layout' => 'slider',
								],
							] ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

	</div>

<?php endif;

wp_reset_postdata();

