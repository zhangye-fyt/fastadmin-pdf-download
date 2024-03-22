<?php
/**
 * Before Product Loop Slider Start
 * Used for blocks: Related, Up-sells, Recent Viewed
 */

defined( 'ABSPATH' ) || exit;

$loop_style = Minimog::setting( 'single_product_loop_style' );
$classes    = [ 'tm-swiper tm-slider minimog-product' ];
$classes[]  = str_replace( 'carousel-', 'group-style-', $loop_style );
$classes[]  = 'style-' . $loop_style;

$loop_caption_style = Minimog::setting( 'single_product_loop_caption_style' );
$classes[]          = "caption-style-{$loop_caption_style}";

$site_layout = Minimog_Woo::instance()->get_single_product_site_layout();

$lg_columns = Minimog::setting( 'single_product_loop_lg_columns' );
$md_columns = Minimog::setting( 'single_product_loop_md_columns' );
$sm_columns = Minimog::setting( 'single_product_loop_sm_columns' );

$lg_gutter = Minimog::setting( 'single_product_loop_lg_gutter' );
$md_gutter = Minimog::setting( 'single_product_loop_md_gutter' );
$sm_gutter = Minimog::setting( 'single_product_loop_sm_gutter' );

$slide_args = [
	'data-items-desktop'      => $lg_columns,
	'data-items-tablet-extra' => $md_columns,
	'data-items-mobile-extra' => $sm_columns,
	'data-gutter-desktop'     => $lg_gutter,
	'data-nav'                => '1',
];

if ( '' !== $md_gutter ) {
	$slide_args['data-gutter-tablet-extra'] = $md_gutter;
}

if ( '' !== $sm_gutter ) {
	$slide_args['data-gutter-mobile-extra'] = $sm_gutter;
}

if ( in_array( $site_layout, [ 'full', 'full-gap-100', 'full-gap-80' ], true ) ) {
	$slide_args['data-items-wide-screen'] = '5';
}
?>
<div
	class="<?php echo esc_attr( implode( ' ', $classes ) ) ?>" <?php echo Minimog_Helper::slider_args_to_html_attr( $slide_args ); ?>>
	<div class="swiper-inner">
		<div class="swiper-container">
			<div class="swiper-wrapper">
