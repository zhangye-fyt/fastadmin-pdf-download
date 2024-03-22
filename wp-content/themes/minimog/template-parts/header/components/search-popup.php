<?php
/**
 * Search button open search popup on header
 *
 * @package Minimog
 * @since   1.0.0
 * @version 2.0.0
 */

defined( 'ABSPATH' ) || exit;

$icon_display = Minimog::setting( 'header_icons_display' );
$icon_style   = Minimog::setting( 'header_icons_style' );
$classes      = "page-open-popup-search hint--bounce hint--bottom header-icon {$icon_style} icon-display--{$icon_display}";

if ( ! empty( $args['extra_class'] ) ) {
	$classes .= ' ' . $args['extra_class'];
}
?>
<a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>"
   class="<?php echo esc_attr( $classes ); ?>"
   aria-label="<?php esc_attr_e( 'Search', 'minimog' ); ?>">
	<div class="icon">
		<?php
		switch ( $icon_style ) :
			case 'icon-set-02':
				$icon_key = 'search-light';
				break;
			case 'icon-set-03':
				$icon_key = 'phr-magnifying-glass';
				break;
			case 'icon-set-04':
				$icon_key = 'search-solid';
				break;
			case 'icon-set-05':
				$icon_key = 'phb-magnifying-glass';
				break;
			default:
				$icon_key = 'search';
				break;
		endswitch;
		echo Minimog_SVG_Manager::instance()->get( $icon_key );
		?>
	</div>
	<?php if ( ! empty( $args['show_text'] ) ) : ?>
		<div class="text"><?php esc_html_e( 'Search', 'minimog' ); ?></div>
	<?php endif; ?>
</a>
