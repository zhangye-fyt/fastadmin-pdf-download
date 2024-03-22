<?php
/**
 * Marque list on top bar
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

$marque_list = $args['marque_list'];
?>
<div class="top-bar-marque-list tm-slider tm-swiper"
     data-items-desktop="auto"
     data-items-tablet-extra="auto"
     data-items-mobile-extra="auto"
     data-gutter-desktop="100"
     data-gutter-tablet="70"
     data-gutter-mobile="40"
     data-loop="1"
     data-looped-slides="4"
     data-centered="1"
     data-autoplay="1"
     data-simulate-touch="0"
     data-allow-touch-move="0"
     data-speed="8000"
>
	<div class="swiper-inner">
		<div class="swiper-container">
			<div class="swiper-wrapper">
				<?php foreach ( $marque_list as $item ) : ?>
					<?php
					if ( empty( $item['text'] ) ) {
						continue;
					}

					$tag        = 'div';
					$attributes = [
						'class' => 'top-bar-marque-text',
					];

					if ( ! empty( $item['url'] ) ) {
						$tag                = 'a';
						$attributes['href'] = $item['url'];
					}
					?>
					<div class="swiper-slide">
						<?php printf( '<%1$s %2$s>%3$s</%1$s>', $tag, Minimog_Helper::convert_array_html_attributes_to_string( $attributes ), $item['text'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
