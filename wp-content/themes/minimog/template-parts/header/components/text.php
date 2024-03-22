<?php
/**
 * Text on header
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$text = $args['text'];
?>
<div class="header-text">
	<?php echo wp_kses( $text, 'minimog-default' ); ?>
</div>
