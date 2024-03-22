<?php
/**
 * Icon list on mobile menu
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$info_list = $args['info_list'];
?>
<div class="mobile-menu-info-list">
	<ul class="info-list">
		<?php foreach ( $info_list as $item ) : ?>
			<?php
			$icon = isset( $item['icon_class'] ) ? $item['icon_class'] : '';
			$text = isset( $item['text'] ) ? $item['text'] : '';

			$link_class = 'info-link';
			$item_tag   = 'div';
			$item_attrs = [
				'class' => 'info-link',
			];

			if ( ! empty( $item['url'] ) ) {
				$item_tag           = 'a';
				$item_attrs['href'] = $item['url'];
			}
			?>
			<li class="info-item">
				<?php printf( '<%1$s %2$s>', $item_tag, Minimog_Helper::convert_array_html_attributes_to_string( $item_attrs ) ); ?>

				<?php if ( $icon !== '' ) : ?>
					<i class="info-icon <?php echo esc_attr( $icon ); ?>"></i>
				<?php endif; ?>

				<?php echo '<span class="info-text">' . $text . '</span>'; ?>

				<?php printf( '</%1$s>', $item_tag ); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
