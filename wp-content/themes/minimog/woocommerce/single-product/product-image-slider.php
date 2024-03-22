<?php
/**
 * @package Minimog
 * @since   1.0.0
 * @version 2.1.0
 */
defined( 'ABSPATH' ) || exit;

global $post, $product;

$is_vertical_slider = Minimog_Woo::instance()->get_product_setting( 'single_product_slider_vertical' );
$is_vertical_slider = $args['vertical_slider'] ?? $is_vertical_slider;

$show_gallery = '1';
$show_gallery = $args['show_gallery'] ?? $show_gallery;

$looped_slides = 3;
$slider_loop   = false; // Disable loop mode to avoid duplicate items on light gallery.

if ( true === $is_quick_view ) {
	$is_vertical_slider = '0';
}

$number_attachments = count( $attachment_ids );

$wrapper_classes .= ' has-thumbs-slider';
$wrapper_classes .= '1' === $is_vertical_slider ? ' thumbs-slider-vertical' : ' thumbs-slider-horizontal';

$output = Minimog_Woo::instance()->get_product_image_slider_slide_html( $attachment_ids, [
	'thumbnail_id'     => $thumbnail_id,
	'open_gallery'     => $open_gallery,
	'main_image_size'  => $main_image_size,
	'thumb_image_size' => $thumb_image_size,
] );
?>
	<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
		<?php
		$main_slider_settings = [
			'data-items-desktop'  => 1,
			'data-gutter-desktop' => 10,
			'data-nav'            => '1',
			'data-simulate-touch' => ! $open_gallery,
		];

		if ( $slider_loop ) {
			$main_slider_settings['data-loop'] = '1';

			if ( '1' === $is_vertical_slider ) {
				$main_slider_settings['data-looped-slides'] = $looped_slides;
			}
		}
		?>
		<div class="tm-swiper minimog-main-swiper nav-style-02" <?php echo Minimog_Helper::slider_args_to_html_attr( $main_slider_settings ); ?>>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php echo '' . $output['main_slides_html']; ?>
					</div>
				</div>
			</div>
		</div>

		<?php if ( '1' === $show_gallery ) { ?>
			<?php
			$thumb_slider_defaults = [
				'data-slide-to-clicked-slide' => '1',
				'data-freemode'               => '1',
			];

			if ( '1' === $is_vertical_slider ) {
				$thumb_slider_settings = [
					'data-items-desktop'  => 'auto',
					'data-gutter-desktop' => 10,
					'data-vertical'       => '1',
					'data-freemode'       => '1',
				];

				if ( $slider_loop ) {
					$thumb_slider_settings['data-looped-slides'] = $looped_slides;
				}
			} else {
				$thumb_slider_settings = [
					'data-items-desktop'  => '5',
					'data-items-mobile'   => '4',
					'data-gutter-desktop' => 10,
				];
			}

			$thumb_slider_settings = wp_parse_args( $thumb_slider_settings, $thumb_slider_defaults );
			?>
			<div class="minimog-thumbs-swiper-wrap">
				<div
					class="tm-swiper minimog-thumbs-swiper" <?php echo Minimog_Helper::slider_args_to_html_attr( $thumb_slider_settings ); ?>>
					<div class="swiper-inner">
						<div class="swiper-container">
							<div class="swiper-wrapper">
								<?php echo '' . $output['thumb_slides_html']; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
<?php if ( 'variable' === $product->get_type() ): ?>
	<div class="gallery-main-slides-o-html" style="display: none"><?php echo $output['main_slides_html']; ?></div>
	<div class="gallery-thumb-slides-o-html" style="display: none"><?php echo $output['thumb_slides_html']; ?></div>
<?php endif;
