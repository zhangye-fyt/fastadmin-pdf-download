<?php
/**
 * Shop link on mobile tabs
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! Minimog_Woo::instance()->is_activated() ) {
	return;
}

$link_classes = 'mobile-tab-link';
?>
<a href="<?php echo esc_url( home_url() ) ?>" class="<?php echo esc_attr( $link_classes ); ?>" aria-label="<?php esc_attr_e( 'Home', 'minimog' ); ?>">
	<div class="icon">
		<?php echo Minimog_SVG_Manager::instance()->get( 'home-alt' ); ?>
	</div>
</a>
