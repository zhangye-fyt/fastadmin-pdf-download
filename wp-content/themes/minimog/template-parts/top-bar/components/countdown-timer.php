<?php
/**
 * Text on top bar
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$countdown_settings = [
	'datetime' => $args['datetime'],
	'labels'   => [
		'days'    => esc_html__( 'Day', 'minimog' ),
		'hours'   => esc_html__( 'Hrs', 'minimog' ),
		'minutes' => esc_html__( 'Min', 'minimog' ),
		'seconds' => esc_html__( 'Sec', 'minimog' ),
	],
];
?>
<div class="top-bar-countdown-timer" data-countdown="<?php echo esc_attr( wp_json_encode( $countdown_settings ) ); ?>">
	<?php if ( ! empty( $args['text_before'] ) ) : ?>
		<div class="countdown-text-before">
			<?php echo wp_kses_post( $args['text_before'] ); ?>
		</div>
	<?php endif; ?>

	<div class="countdown-timer"></div>

	<?php if ( ! empty( $args['button_text'] ) && ! empty( $args['button_url'] ) ) : ?>
		<a class="countdown-button" href="<?php esc_url( $args['button_url'] ); ?>">
			<?php echo esc_html( $args['button_text'] ); ?>
		</a>
	<?php endif; ?>
</div>
