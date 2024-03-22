<?php
/**
 * @package Minimog
 * @since   1.0.0
 * @version 2.5.1
 */
defined( 'ABSPATH' ) || exit;

global $post, $product;

$slides_html = '';

$total_attachments = count( $attachment_ids );

$slider_loop = false; // $total_attachments > 1;
?>
<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
	<?php
	foreach ( $attachment_ids as $attachment_id ) {
		$attachment_info = Minimog_Image::get_attachment_info( $attachment_id );

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
			'id'    => $attachment_id,
			'size'  => $main_image_size,
			'alt'   => $product->get_name(),
			'class' => $attachment_id === $thumbnail_id ? 'wp-post-image' : '',
		) );
		$main_image_html = '<div class="woocommerce-product-gallery__image">' . $main_image_html . '</div>';
		$main_image_html = apply_filters( 'woocommerce_single_product_image_thumbnail_html', $main_image_html, intval( $attachment_id ) );
		$slides_html     .= sprintf( '<div %1$s>%2$s%3$s</div>', $attributes_string, $main_image_html, $video_play_html . $video_html );
	}
	?>
	<div class="tm-swiper minimog-main-swiper nav-style-02"
	     data-items-desktop="1"
	     data-gutter-desktop="10"
	     data-nav="1"
	     data-auto-height="1"
		<?php if ( $slider_loop ) : ?>
			data-loop="1"
			data-looped-slides="3"<?php /* This setting required to make slideTo working properly.  */ ?>
		<?php endif; ?>
		<?php if ( $open_gallery ) : ?>
			data-simulate-touch="0"
		<?php endif; ?>
	>
		<div class="swiper-inner">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php echo '' . $slides_html; ?>
				</div>
			</div>
		</div>
	</div>
</div>
