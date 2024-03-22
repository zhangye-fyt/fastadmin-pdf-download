<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$loop_display_mode = woocommerce_get_loop_display_mode();
?>
	<div id="page-content" class="page-content">
		<div class="<?php echo Minimog\Woo\Archive_Product::instance()->page_content_container_class(); ?>">
			<?php
			/**
			 * woocommerce_archive_description hook.
			 *
			 * @hooked woocommerce_taxonomy_archive_description - 10
			 * @hooked woocommerce_product_archive_description - 10
			 */
			//do_action( 'woocommerce_archive_description' );
			?>
			<?php if ( is_shop() && 'above_sidebar' === Minimog::setting( 'shop_sub_categories_position' )
			           || is_product_category() && 'above_sidebar' === Minimog::setting( 'product_category_sub_categories_position' )
			): ?>
				<?php wc_get_template_part( 'custom/product-categories' ); ?>
			<?php endif; ?>

			<?php
			/**
			 * Hook: woocommerce_before_shop_loop.
			 *
			 * @hooked wc_print_notices - 10
			 * @hooked woocommerce_result_count - 20
			 * @hooked woocommerce_catalog_ordering - 30
			 */
			if ( 'above-content-sidebar' === Minimog::setting( 'shop_archive_toolbar_position' ) ) {
				do_action( 'woocommerce_before_shop_loop' );
			}
			?>

			<div class="row">

				<?php Minimog_Sidebar::instance()->render( 'left' ); ?>

				<div class="page-main-content">
					<div class="shop-archive-block">

						<?php if ( '1' === Minimog::setting( 'shop_archive_page_title' ) ) : ?>
							<?php wc_get_template_part( 'custom/page-title' ); ?>
						<?php endif; ?>

						<?php if ( is_shop() && 'beside_sidebar' === Minimog::setting( 'shop_sub_categories_position' )
						           || is_product_category() && 'beside_sidebar' === Minimog::setting( 'product_category_sub_categories_position' )
						): ?>
							<?php wc_get_template_part( 'custom/product-categories' ); ?>
						<?php endif; ?>

						<?php if ( 'subcategories' !== $loop_display_mode ) : ?>
							<?php if ( have_posts() ) : ?>
								<?php if ( wc_get_loop_prop( 'total' ) ) { ?>

									<?php
									/**
									 * Hook: woocommerce_before_shop_loop.
									 *
									 * @hooked wc_print_notices - 10
									 * @hooked woocommerce_result_count - 20
									 * @hooked woocommerce_catalog_ordering - 30
									 */
									if ( 'above-content' === Minimog::setting( 'shop_archive_toolbar_position' ) ) {
										do_action( 'woocommerce_before_shop_loop' );
									}
									?>

									<?php
									wc_get_template_part( 'custom/before-shop-loop' );

									$archive_grid_style = Minimog::setting( 'shop_archive_grid_style' );

									while ( have_posts() ) {
										the_post();

										/**
										 * Hook: woocommerce_shop_loop.
										 */
										do_action( 'woocommerce_shop_loop' );

										minimog_get_wc_template_part( 'content-product', $archive_grid_style, [
											'settings' => [
												'show_list_view' => true,
											],
										] );
									}

									wc_get_template_part( 'custom/after-shop-loop' );
									?>
								<?php } ?>

								<?php
								/**
								 * woocommerce_after_shop_loop hook.
								 *
								 * @hooked woocommerce_pagination - 10
								 */
								do_action( 'woocommerce_after_shop_loop' );
								?>
							<?php else : ?>
								<?php
								/**
								 * Hook: woocommerce_no_products_found.
								 *
								 * @hooked wc_no_products_found - 10
								 */
								do_action( 'woocommerce_no_products_found' );
								?>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>

				<?php Minimog_Sidebar::instance()->render( 'right' ); ?>

			</div>
		</div>
	</div>
<?php
get_footer( 'shop' );
