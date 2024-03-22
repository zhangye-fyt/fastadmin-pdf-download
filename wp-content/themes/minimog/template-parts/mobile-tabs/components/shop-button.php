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
$link_url     = get_permalink( wc_get_page_id( 'shop' ) );
?>
<a href="<?php echo esc_url( $link_url ) ?>" class="<?php echo esc_attr( $link_classes ); ?>" aria-label="<?php esc_attr_e( 'Shop', 'minimog' ); ?>">
	<div class="icon">
		<?php echo Minimog_SVG_Manager::instance()->get( 'grid' ); ?>
	</div>
</a>
