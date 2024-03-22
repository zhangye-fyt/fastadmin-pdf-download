<?php
/**
 * @package Minimog
 * @since   1.0.0
 * @version 2.5.1
 */
defined( 'ABSPATH' ) || exit;

global $post, $product;

$grid_class = 'minimog-grid lazy-grid';

$lg_columns = Minimog_Woo::instance()->get_product_setting( 'single_product_image_grid_lg_columns', 2 );
$md_columns = Minimog_Woo::instance()->get_product_setting( 'single_product_image_grid_md_columns', $lg_columns );
$sm_columns = Minimog_Woo::instance()->get_product_setting( 'single_product_image_grid_sm_columns', $md_columns );

$lg_gutter = Minimog_Woo::instance()->get_product_setting( 'single_product_image_grid_lg_gutter', 10 );
$md_gutter = Minimog_Woo::instance()->get_product_setting( 'single_product_image_grid_md_gutter', $lg_gutter );
$sm_gutter = Minimog_Woo::instance()->get_product_setting( 'single_product_image_grid_sm_gutter', $md_gutter );

$grid_options = [
	'type'               => 'grid',
	'columns'            => $lg_columns,
	'columnsTablet'      => $md_columns,
	'columnsMobileExtra' => $sm_columns,
	'gutter'             => $lg_gutter,
];

if ( '' !== $md_gutter ) {
	$grid_options['gutterTablet'] = $md_gutter;
}

if ( '' !== $sm_gutter ) {
	$grid_options['gutterMobileExtra'] = $sm_gutter;
}

$grid_alternating = Minimog_Woo::instance()->get_product_setting( 'single_product_image_grid_alternating' );
if ( '0' !== $grid_alternating ) {
	$grid_options['columnAlternating']       = intval( $grid_alternating );
	$grid_options['columnAlternatingMobile'] = 0;// Disable on mobile.
}
?>
<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
	<div class="minimog-main-post minimog-grid-wrapper"
	     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ); ?>" <?php echo Minimog_Helper::grid_args_to_html_attr( $grid_options ); ?>>
		<div class="<?php echo esc_attr( $grid_class ); ?>">
			<div class="grid-sizer"></div>

			<?php foreach ( $attachment_ids as $attachment_id ) : ?>
				<?php
				$attachment_info = Minimog_Image::get_attachment_info( $attachment_id );

				if ( ! $attachment_info['src'] ) {
					continue;
				}

				$grid_item_classes = array( 'zoom grid-item' );
				$video_play_html   = '';
				$video_html        = '';
				$attributes_string = '';

				$media_attach = get_post_meta( $attachment_id, 'minimog_product_attachment_type', true );
				switch ( $media_attach ) {
					case 'video':
						$video_url = get_post_meta( $attachment_id, 'minimog_product_video', true );
						if ( ! empty( $video_url ) ) {
							$grid_item_classes[] = 'has-video';
							$video_play_html     = '<div class="main-play-product-video"></div>';

							if ( strpos( $video_url, 'mp4' ) !== false ) {
								$html5_video_id = uniqid( 'product-video-' . $attachment_id );

								$attributes_string .= sprintf( ' data-html="%s"', '#' . $html5_video_id );

								$video_html .= '<div id="' . $html5_video_id . '" style="display:none;"><video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none" src="' . esc_url( $video_url ) . '"></video></div>';
							} else {
								$attributes_string .= sprintf( ' data-src="%s"', esc_url( $video_url ) );
							}

							$main_slide_suffix_html = $video_play_html . $video_html;
						}
						break;
					case '360':
						$sprite_image_id  = get_post_meta( $attachment_id, 'minimog_360_source_sprite', true );
						$sprite_image_url = Minimog_Image::get_attachment_url_by_id( [
							'id'   => $sprite_image_id,
							'size' => 'full',
						] );

						if ( ! empty( $sprite_image_url ) ) {
							$product_360_settings = [
								'source'  => $sprite_image_url,
								'frames'  => absint( get_post_meta( $attachment_id, 'minimog_360_total_frames', true ) ),
								'framesX' => absint( get_post_meta( $attachment_id, 'minimog_360_total_frames_per_row', true ) ),
								'width'   => 540,
								'height'  => Minimog_Woo::instance()->get_product_image_height_by_width( 540 ),
							];

							$grid_item_classes[] = 'btn-open-product-360';
							$attributes_string   .= ' data-spritespin-settings="' . esc_attr( wp_json_encode( $product_360_settings ) ) . '"';
						}
						break;

					default:
						$grid_item_classes[] = 'zoom';
						$attributes_string   .= sprintf( ' data-src="%s"', esc_url( $attachment_info['src'] ) );
						break;
				}

				if ( isset( $thumbnail_id ) && $attachment_id == $thumbnail_id ) {
					$grid_item_classes[] = 'product-main-image';
				}

				$attributes_string .= ' class="' . esc_attr( implode( ' ', $grid_item_classes ) ) . '"';

				if ( $open_gallery ) {
					$sub_html = '';

					if ( ! empty( $attachment_info['title'] ) ) {
						$sub_html .= "<h4>{$attachment_info['title']}</h4>";
					}

					if ( ! empty( $attachment_info['caption'] ) ) {
						$sub_html .= "<p>{$attachment_info['caption']}</p>";
					}

					if ( ! empty( $sub_html ) ) {
						$attributes_string .= ' data-sub-html="' . esc_attr( $sub_html ) . '"';
					}
				}

				$attributes_string .= ' data-image-id="' . $attachment_id . '"';

				$main_image_html = Minimog_Image::get_attachment_by_id( array(
					'id'   => $attachment_id,
					'size' => $main_image_size,
					'alt'  => $product->get_name(),
				) );
				$main_image_html = '<div class="woocommerce-product-gallery__image">' . $main_image_html . '</div>';
				$main_image_html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', $main_image_html, intval( $attachment_id ) );
				printf( '<div %1$s>%2$s%3$s</div>', $attributes_string, $main_image_html, $video_play_html . $video_html );
				?>
			<?php endforeach; ?>
		</div>
	</div>

	<?php if ( '1' === Minimog::setting( 'single_product_image_grid_to_slider_on_mobile' ) ): ?>
		<?php
		$slides_html = '';
		foreach ( $attachment_ids as $attachment_id ) {
			$attachment_info = wc_get_product_attachment_props( $attachment_id, $post );

			if ( ! $attachment_info['src'] ) {
				continue;
			}

			$main_slide_classes = array( 'zoom swiper-slide' );
			$video_play_html    = '';
			$video_html         = '';
			$attributes_string  = '';

			$video_url = get_post_meta( $attachment_id, 'minimog_product_video', true );
			if ( ! empty( $video_url ) ) {
				$main_slide_classes[] = 'has-video';
				$video_play_html      = '<div class="main-play-product-video"></div>';

				if ( strpos( $video_url, 'mp4' ) !== false ) {
					$html5_video_id = uniqid( 'product-video-' . $attachment_id );

					$attributes_string .= sprintf( ' data-html="%s"', '#' . $html5_video_id );

					$video_html .= '<div id="' . $html5_video_id . '" style="display:none;">
					<video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none"
					       src="' . esc_url( $video_url ) . '"></video>
				</div>';
				} else {
					$attributes_string .= sprintf( ' data-src="%s"', esc_url( $video_url ) );
				}
			} else {
				$attributes_string .= sprintf( ' data-src="%s"', esc_url( $attachment_info['src'] ) );
			}

			if ( isset( $thumbnail_id ) && $attachment_id == $thumbnail_id ) {
				$main_slide_classes[] = 'product-main-image';
			}

			$attributes_string .= ' class="' . esc_attr( implode( ' ', $main_slide_classes ) ) . '"';

			if ( $open_gallery ) {
				$sub_html = '';

				if ( ! empty( $attachment_info['title'] ) ) {
					$sub_html .= "<h4>{$attachment_info['title']}</h4>";
				}

				if ( ! empty( $attachment_info['caption'] ) ) {
					$sub_html .= "<p>{$attachment_info['caption']}</p>";
				}

				if ( ! empty( $sub_html ) ) {
					$attributes_string .= ' data-sub-html="' . esc_attr( $sub_html ) . '"';
				}
			}

			$attributes_string .= ' data-image-id="' . $attachment_id . '"';

			$main_image_html = Minimog_Image::get_attachment_by_id( array(
				'id'   => $attachment_id,
				'size' => $main_image_size,
				'alt'  => $product->get_name(),
			) );
			$slides_html     .= sprintf( '<div %1$s>%2$s%3$s</div>', $attributes_string, $main_image_html, $video_play_html . $video_html );
		}
		?>
		<div class="tm-slider tm-swiper nav-style-02"
		     data-items-desktop="1"
		     data-gutter-desktop="10"
		     data-nav="1"
		     data-auto-height="1"
		>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php echo '' . $slides_html; ?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
