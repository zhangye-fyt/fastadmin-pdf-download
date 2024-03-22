<?php
/**
 * @package Minimog
 * @since   1.0.0
 * @version 2.5.1
 */
defined( 'ABSPATH' ) || exit;

global $post, $product;

$slider_args = [
	'data-items-desktop'  => '2',
	'data-gutter-desktop' => '10',
	'data-effect'         => 'slide',
	'data-auto-height'    => '1',
	'data-pagination'     => '1',
	'data-simulate-touch' => ! $open_gallery,
];
?>
<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
	<div
		class="tm-swiper minimog-main-swiper bullets-v-align-below nav-style-01 pagination-style-08" <?php echo Minimog_Helper::slider_args_to_html_attr( $slider_args ); ?>>
		<div class="swiper-inner">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php
					foreach ( $attachment_ids as $attachment_id ) {
						$attachment_info = Minimog_Image::get_attachment_info( $attachment_id );

						if ( ! $attachment_info['src'] ) {
							continue;
						}

						$main_slide_classes = array( 'swiper-slide' );
						$video_play_html    = '';
						$video_html         = '';
						$attributes_string  = '';

						$media_attach = get_post_meta( $attachment_id, 'minimog_product_attachment_type', true );
						switch ( $media_attach ) {
							case 'video':
								$video_url = get_post_meta( $attachment_id, 'minimog_product_video', true );
								if ( ! empty( $video_url ) ) {
									$main_slide_classes[] = 'zoom has-video';
									$video_play_html      = '<div class="main-play-product-video"></div>';

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

									$main_slide_classes[] = 'btn-open-product-360';
									$attributes_string    .= ' data-spritespin-settings="' . esc_attr( wp_json_encode( $product_360_settings ) ) . '"';
								}
								break;

							default:
								$main_slide_classes[] = 'zoom';
								$attributes_string    .= sprintf( ' data-src="%s"', esc_url( $attachment_info['src'] ) );
								break;
						}

						if ( isset( $thumbnail_id ) && $attachment_id == $thumbnail_id ) {
							$main_slide_classes[] = 'product-main-image';
						}

						$attributes_string .= 'class="' . esc_attr( implode( ' ', $main_slide_classes ) ) . '"';

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
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
