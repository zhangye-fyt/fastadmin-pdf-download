<?php
/**
 * Template part for displaying search product content
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Minimog
 * @since   1.0.0
 */

$products_output    = '';
$rest_output        = '';
$product_grid_style = Minimog::setting( 'shop_archive_grid_style' );

if ( have_posts() ) : ?>
	<?php
	while ( have_posts() ) {
		the_post();

		$post_type = get_post_type();

		ob_start();

		if ( 'product' === $post_type ) {
			minimog_load_template( 'content-search-product' );

			$products_output .= ob_get_clean();
		} else {
			minimog_load_template( 'content-search-rest' );

			$rest_output .= ob_get_clean();
		}
	}
	?>

	<?php if ( ! empty( $products_output ) ): ?>
		<div class="search-results-section product-search-results">
			<h2 class="search-results-heading"><?php esc_html_e( 'We found some products for you.', 'minimog' ); ?></h2>
			<?php
			$classes = [
				'minimog-main-post',
				'minimog-grid-wrapper',
				'minimog-product',
			];

			$classes[] = str_replace( 'grid-', 'group-style-', $product_grid_style );
			$classes[] = "style-{$product_grid_style}";

			$grid_class = 'minimog-grid lazy-grid';
			$lg_columns = intval( Minimog::setting( 'shop_archive_lg_columns' ) );
			$md_columns = Minimog::setting( 'shop_archive_md_columns' );
			$sm_columns = Minimog::setting( 'shop_archive_sm_columns' );

			$lg_gutter = Minimog::setting( 'shop_archive_lg_gutter' );
			$md_gutter = Minimog::setting( 'shop_archive_md_gutter' );
			$sm_gutter = Minimog::setting( 'shop_archive_sm_gutter' );

			$grid_options = [
				'type'               => 'grid',
				'columns'            => $lg_columns,
				'columnsTabletExtra' => $md_columns,
				'columnsMobileExtra' => $sm_columns,
				'gutter'             => $lg_gutter,
			];

			if ( '' !== $md_gutter ) {
				$grid_options['gutterTabletExtra'] = $md_gutter;
			}

			if ( '' !== $sm_gutter ) {
				$grid_options['gutterMobileExtra'] = $sm_gutter;
			}
			?>
			<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
				<?php echo Minimog_Helper::grid_args_to_html_attr( $grid_options ); ?>
			>
				<div class="<?php echo esc_attr( $grid_class ); ?>">
					<div class="grid-sizer"></div>

					<?php echo '' . $products_output; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $rest_output ) ) : ?>
		<div class="search-results-section all-search-results">
			<h2 class="search-results-heading"><?php esc_html_e( 'We found some other results for you.', 'minimog' ); ?></h2>

			<?php
			$style      = 'grid';
			$is_masonry = Minimog::setting( 'blog_archive_masonry' );
			$classes    = [
				'minimog-main-post',
				'minimog-grid-wrapper',
				'minimog-blog',
				'minimog-animation-zoom-in',
				"minimog-blog-" . $style,
				'minimog-blog-caption-style-04',
			];
			$lg_columns = $md_columns = $sm_columns = 1;

			// Handle Columns.
			switch ( $style ) {
				case 'grid':
					$lg_columns = 3;
					$md_columns = 2;
					$sm_columns = 1;
					break;
			}

			$grid_options = [
				'type'               => ! empty( $is_masonry ) ? 'masonry' : 'grid',
				'columns'            => $lg_columns,
				'columnsTabletExtra' => $md_columns,
				'columnsMobileExtra' => $sm_columns,
				'gutter'             => 30,
			];

			$caption_style = Minimog::setting( 'blog_archive_grid_caption_style' );
			$classes[]     = 'minimog-blog-caption-style-' . $caption_style;
			?>
			<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>"
				<?php echo Minimog_Helper::grid_args_to_html_attr( $grid_options ); ?>
			>
				<div class="minimog-grid lazy-grid">
					<?php if ( in_array( $style, array( 'grid', 'grid-02' ) ) ) : ?>
						<div class="grid-sizer"></div>
					<?php endif; ?>

					<?php echo '' . $rest_output; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="minimog-grid-pagination">
		<?php Minimog_Templates::paging_nav(); ?>
	</div>
<?php else : minimog_load_template( 'content', 'none' ); ?>
<?php endif; ?>
