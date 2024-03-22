<?php
/**
 * Category button
 */

defined( 'ABSPATH' ) || exit;
extract( $args );

if ( empty( $settings['button_text'] ) ) {
	return;
}
?>
<div class="tm-button-wrapper">
	<div class="tm-button style-bottom-line">
		<div class="button-content-wrapper">
			<span class="button-text"><?php echo esc_html( $settings['button_text'] ); ?></span>
		</div>
	</div>
</div>
