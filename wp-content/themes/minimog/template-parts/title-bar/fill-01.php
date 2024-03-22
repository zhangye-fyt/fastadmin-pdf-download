<?php
/**
 * Template display info of current category in title bar.
 *
 * @package   Minimog
 * @since     2.0.0
 * @version   2.0.0
 */

$background_image = '';

if ( function_exists( 'is_product_category' ) && is_product_category() ) {
	$category = get_queried_object();

	$image_id = get_term_meta( $category->term_id, 'banner_id', true );
	if ( $image_id ) {
		$image_url = \Minimog_Image::get_attachment_url_by_id( [
			'id' => $image_id,
		] );

		$background_image .= 'background-image: url(' . $image_url . ')';
	}
}
?>
<div id="page-title-bar" <?php Minimog_Title_Bar::instance()->the_wrapper_class(); ?>>
	<div class="page-title-bar-bg"
		<?php if ( ! empty( $background_image ) ): ?>
			style="<?php echo $background_image; ?>"
		<?php endif; ?>
	></div>

	<div class="page-title-bar-inner">
		<div class="page-title-bar-content">
			<?php minimog_load_template( 'breadcrumb' ); ?>

			<div <?php Minimog_Title_Bar::instance()->the_container_class(); ?>>

				<?php Minimog_THA::instance()->title_bar_heading_before(); ?>

				<?php Minimog_Title_Bar::instance()->render_title(); ?>

				<?php if ( ! empty( $category ) && ! empty( $category->description ) && '1' === Minimog::setting( 'shop_category_title_bar_show_description' ) ) : ?>
					<div class="page-title-bar-category-wrap">
						<div class="page-title-bar-category-desc">
							<?php echo $category->description; ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if (
					( function_exists( 'is_shop' ) && is_shop() && 'inside_title_bar' === Minimog::setting( 'shop_sub_categories_position' ) ) ||
					( function_exists( 'is_product_category' ) && is_product_category() && 'inside_title_bar' === Minimog::setting( 'product_category_sub_categories_position' ) )
				): ?>
					<?php wc_get_template_part( 'custom/product-categories' ); ?>
				<?php endif; ?>

				<?php Minimog_THA::instance()->title_bar_heading_after(); ?>
			</div>
		</div>
	</div>
</div>
