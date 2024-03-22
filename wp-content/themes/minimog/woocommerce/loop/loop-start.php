<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.3.0
 */

defined( 'ABSPATH' ) || exit;

$archive_grid_style = Minimog::setting( 'shop_archive_grid_style' );

$classes   = [
	'minimog-grid-wrapper',
	'minimog-product',
];
$classes[] = str_replace( 'grid-', 'group-style-', $archive_grid_style );
$classes[] = "style-{$archive_grid_style}";

$caption_style = Minimog::setting( 'shop_archive_grid_caption_style' );
$classes[]     = "caption-style-{$caption_style}";

$grid_class = 'minimog-grid lazy-grid';
$lg_columns = wc_get_loop_prop( 'columns' );
$md_columns = 3;
$sm_columns = 2;

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

