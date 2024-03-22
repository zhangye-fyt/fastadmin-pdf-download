<?php
/**
 * Category Caption Style 05
 * Info with button only
 */

defined( 'ABSPATH' ) || exit;
extract( $args );
?>
<div class="category-info">
	<?php Minimog_Templates::render_button( [
		'link'  => [
			'url' => $link,
		],
		'style' => ! empty( $settings['overlay_button_style'] ) ? $settings['overlay_button_style'] : 'flat',
		'text'  => esc_html( $category->name ),
	] ); ?>
</div>
