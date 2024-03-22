<?php
/**
 * Wishlist button on mobile tabs
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverWoosw' ) ) {
	return;
}

$link_classes = 'mobile-tab-link has-badge wishlist-link';
$wishlist_url = WPCleverWoosw::get_url();
$count        = WPCleverWoosw::get_count();
$icon_type    = Minimog::setting( 'header_wishlist_icon_type' );
$icon_key     = 'heart' === $icon_type ? 'heart' : 'star';
?>
<a href="<?php echo esc_url( $wishlist_url ) ?>" class="<?php echo esc_attr( $link_classes ); ?>" aria-label="<?php esc_attr_e( 'Wishlist', 'minimog' ); ?>">
	<div class="icon">
		<?php echo Minimog_SVG_Manager::instance()->get( $icon_key ); ?>
		<span class="icon-badge"><?php echo esc_html( $count ); ?></span>
	</div>
</a>
