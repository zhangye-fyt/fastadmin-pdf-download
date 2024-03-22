<?php
/**
 * Before Store Loop Start
 */

defined( 'ABSPATH' ) || exit;

$archive_grid_style = Minimog::setting( 'shop_archive_grid_style' );

$classes   = [
	'minimog-main-post',
	'minimog-grid-wrapper',
	'minimog-store-list',
];
$classes[] = str_replace( 'grid-', 'group-style-', $archive_grid_style );
$classes[] = "style-{$archive_grid_style}";

$caption_style = Minimog::setting( 'shop_archive_grid_caption_style' );
$classes[]     = "caption-style-{$caption_style}";

$grid_class = 'minimog-grid lazy-grid';
$lg_columns = Minimog::setting( 'shop_archive_lg_columns' );
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
<div id="minimog-main-post" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>" <?php echo Minimog_Helper::grid_args_to_html_attr( $grid_options ); ?>
>
	<div class="<?php echo esc_attr( $grid_class ); ?>">
		<div class="grid-sizer"></div>
