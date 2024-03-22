<?php
/**
 * Mobile menu toggle button on header
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$class = 'header-icon page-open-mobile-menu';
$class .= ' style-' . $args['style'];

$menu_settings = [
	'direction' => $args['direction'],
	'animation' => $args['animation'],
];
?>
<div id="page-open-mobile-menu" class="<?php echo esc_attr( $class ); ?>"
     data-menu-settings="<?php echo esc_attr( wp_json_encode( $menu_settings ) ); ?>">
	<div class="icon">
		<?php echo Minimog_SVG_Manager::instance()->get( 'bars' ); ?>
	</div>
</div>
