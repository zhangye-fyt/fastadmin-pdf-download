<?php
/**
 * Live View Visitors
 *
 * @since   1.0.0
 * @version 2.5.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="live-viewing-visitors" class="live-viewing-visitors"
     data-settings="<?php echo esc_attr( json_encode( $settings ) ); ?>">
	<span class="icon minimog-animate-pulse far fa-eye"></span>
	<div class="text">
		<?php echo sprintf(
			esc_html( _n( '%s person is viewing this right now', '%s people are viewing this right now', $total_visitors, 'minimog' ) ),
			'<span class="count">' . $total_visitors . '</span>'
		); ?>
	</div>
</div>
