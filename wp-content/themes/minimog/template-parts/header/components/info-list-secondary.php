<?php
/**
 * Icon list on header
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$info_list = $args['info_list'];
?>
<div class="header-info-list header-info-list-secondary">
	<ul class="info-list">
		<?php
		foreach ( $info_list as $item ) {
			$url  = isset( $item['url'] ) ? $item['url'] : '';
			$icon = isset( $item['icon_class'] ) ? $item['icon_class'] : '';
			$text = isset( $item['text'] ) ? $item['text'] : '';

			$link_attrs = [
				'class' => 'info-link',
			];
			$link_tag   = 'div';

			if ( ! empty( $url ) ) {
				$link_tag           = 'a';
				$link_attrs['href'] = $url;
			}

			$link_text = '<span class="info-text">' . $text . '</span>';
			$link_icon = '';

			if ( $icon !== '' ) {
				$link_icon = '<i class="info-icon ' . esc_attr( $icon ) . '"></i>';
			}
			?>
			<li class="info-item">
				<?php printf( '<%1$s %2$s>%3$s</%1$s>', $link_tag, Minimog_Helper::convert_array_html_attributes_to_string( $link_attrs ), $link_icon . $link_text ); ?>
			</li>
		<?php } ?>
	</ul>
</div>
