<?php
/**
 * Text on top bar
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$text    = $args['text'];
$classes = 'top-bar-text';

if ( ! empty( $args['style'] ) ) {
	$classes .= ' style-' . $args['style'];
}
?>
<div class="<?php echo esc_attr( $classes ); ?>">
	<?php echo wp_kses( $text, 'minimog-default' ); ?>
</div>
