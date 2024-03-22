<?php
/**
 * Search button open search popup on mobile tabs
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$classes = "page-open-popup-search mobile-tab-link";
?>
<a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" class="<?php echo esc_attr( $classes ); ?>" aria-label="<?php esc_attr_e( 'Search', 'minimog' ); ?>">
	<div class="icon">
		<?php echo Minimog_SVG_Manager::instance()->get( 'search' ); ?>
	</div>
</a>
