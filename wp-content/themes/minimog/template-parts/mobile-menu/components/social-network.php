<?php
/**
 * Social network buttons on mobile menu
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$defaults = array(
	'style' => 'icons',
);

$args       = wp_parse_args( $args, $defaults );
$el_classes = 'mobile-menu-social-networks';

if ( ! empty( $args['style'] ) ) {
	$el_classes .= " style-{$args['style']}";
}
?>
	<div class="<?php echo esc_attr( $el_classes ); ?>">
		<div class="inner">
			<?php
			$args = [
				'tooltip_enable' => false
			];

			Minimog_Templates::social_icons( $args );
			?>
		</div>
	</div>
<?php
