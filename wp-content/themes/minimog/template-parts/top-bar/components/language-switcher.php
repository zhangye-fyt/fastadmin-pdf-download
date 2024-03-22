<?php
/**
 * Language switcher
 *
 * @package Minimog
 * @since   1.0.0
 * @version 2.5.4
 */

defined( 'ABSPATH' ) || exit;

$wrap_classes = apply_filters( 'minimog/top_bar/language_switcher/wrap_class', array( 'switcher-language-wrapper' ) );
?>
<div id="switcher-language-wrapper" class="<?php echo esc_attr( implode( ' ', $wrap_classes ) ); ?>">
	<?php do_action( 'minimog/top_bar/language_switcher' ); ?>
</div>
