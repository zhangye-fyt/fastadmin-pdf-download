<?php
/**
 * Template display product categories on Shop catalog and category page.
 *
 * @package   Minimog
 * @since     1.0.0
 * @version   2.0.0
 */
defined( 'ABSPATH' ) || exit;

$shop_page_display = Minimog_Woo::instance()->get_shop_display();

if ( ! in_array( $shop_page_display, [ 'subcategories', 'both' ] ) ) {
	return;
}

/**
 * @see woocommerce_output_product_categories();
 */
$parent_id          = is_product_category() ? get_queried_object_id() : 0;
$product_categories = woocommerce_get_product_subcategories( $parent_id );

if ( ! $product_categories ) {
	return false;
}

$style      = Minimog_Woo::instance()->get_shop_categories_setting( 'style' );
$layout     = Minimog_Woo::instance()->get_shop_categories_setting( 'layout' );
$lg_columns = Minimog_Woo::instance()->get_shop_categories_setting( 'lg_columns' );
$md_columns = Minimog_Woo::instance()->get_shop_categories_setting( 'md_columns' );
$sm_columns = Minimog_Woo::instance()->get_shop_categories_setting( 'sm_columns' );
$lg_gutter  = Minimog_Woo::instance()->get_shop_categories_setting( 'lg_gutter' );
$md_gutter  = Minimog_Woo::instance()->get_shop_categories_setting( 'md_gutter' );
$sm_gutter  = Minimog_Woo::instance()->get_shop_categories_setting( 'sm_gutter' );

$classes = [
	"minimog-product-categories style-{$style}",
];

$hover_effect = Minimog::setting( 'shop_category_hover_effect' );
if ( ! empty( $hover_effect ) ) {
	$classes[] = 'minimog-animation-' . $hover_effect;
}

if ( 'grid' === $layout ) {
	$classes[] = 'minimog-grid-wrapper';

	$component_args = [
		'type'               => 'grid',
		'columns'            => $lg_columns,
		'columnsTabletExtra' => $md_columns,
		'columnsMobileExtra' => $sm_columns,
		'gutter'             => $lg_gutter,
	];

	if ( '' !== $md_gutter ) {
		$component_args['gutterTabletExtra'] = $md_gutter;
	}

	if ( '' !== $sm_gutter ) {
		$component_args['gutterMobileExtra'] = $sm_gutter;
	}
} else {
	$classes[] = 'tm-swiper tm-slider nav-style-01';

	$component_args = [
		'data-nav'                => 1,
		'data-items-desktop'      => $lg_columns,
		'data-items-tablet-extra' => $md_columns,
		'data-items-mobile-extra' => $sm_columns,
		'data-gutter-desktop'     => $lg_gutter,
	];

	if ( '' !== $md_gutter ) {
		$component_args['data-gutter-tablet-extra'] = $md_gutter;
	}

	if ( '' !== $sm_gutter ) {
		$component_args['data-gutter-mobile-extra'] = $sm_gutter;
	}
}

$loop_settings = [
	'layout'         => $layout,
	'style'          => $style,
	'show_count'     => '1' === Minimog::setting( 'shop_category_show_count' ) ? 1 : 0,
	'show_min_price' => '1' === Minimog::setting( 'shop_category_show_min_price' ) ? 1 : 0,
	'button_text'    => __( 'Shop Now', 'minimog' ),
];

ob_start();
foreach ( $product_categories as $category ) :
	minimog_get_wc_template_part( 'content-product-cat', '', [
		'settings' => $loop_settings,
		'category' => $category,
	] );
endforeach;
$content_cat_html = ob_get_clean();
?>
<?php if ( 'grid' === $layout ) : ?>
	<?php
	$grid_args_style = \Minimog_Helper::grid_args_to_html_style( $component_args );
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-grid="<?php echo esc_attr( wp_json_encode( $component_args ) ); ?>"
		<?php if ( ! empty( $grid_args_style ) ) { ?>
			style="<?php echo $grid_args_style; ?>"
		<?php } ?>
	>
		<div class="minimog-grid lazy-grid">
			<div class="grid-sizer"></div>
			<?php echo $content_cat_html; ?>
		</div>
	</div>
<?php else : ?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo Minimog_Helper::slider_args_to_html_attr( $component_args ); ?>>
		<div class="swiper-inner">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php echo $content_cat_html; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif;
