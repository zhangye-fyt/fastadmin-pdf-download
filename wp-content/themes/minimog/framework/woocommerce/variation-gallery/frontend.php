<?php

namespace Minimog\Woo\Variation_Gallery;

defined( 'ABSPATH' ) || exit;

class Frontend {
	protected static $instance = null;

	const META_KEY = 'gallery_images';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'woocommerce_available_variation', [ $this, 'get_available_variation_gallery' ], 90, 3 );
	}

	/**
	 * @param array                 $settings
	 * @param \WC_Product           $product
	 * @param \WC_Product_Variation $variation
	 *
	 * @return mixed
	 */
	public function get_available_variation_gallery( $settings, $product, $variation ) {
		$variation_id = $variation->get_id();
		$gallery_ids  = $this->get_variation_gallery_ids( $variation_id );

		if ( ! empty( $gallery_ids ) ) {
			if ( ! empty( $settings['image_id'] ) ) {
				array_unshift( $gallery_ids, $settings['image_id'] );
			}

			$gallery_ids = array_unique( $gallery_ids );

			$open_gallery  = apply_filters( 'minimog/single_product/open_gallery', true );
			$is_quick_view = apply_filters( 'minimog/quick_view/is_showing', false );
			$feature_style = $is_quick_view ? 'slider-02' : \Minimog_Woo::instance()->get_single_product_images_style();

			$variation_gallery_output = \Minimog_Woo::instance()->get_product_image_slider_slide_html( $gallery_ids, [
				'product'          => $product,
				'thumbnail_id'     => 0,
				'open_gallery'     => $open_gallery,
				'main_image_size'  => \Minimog_Woo::instance()->get_single_product_image_size_by_feature_style( $feature_style, $is_quick_view ),
				'thumb_image_size' => \Minimog_Woo::instance()->get_single_product_thumb_size(),
				'image_lazy_load'  => false,
			] );

			$settings['variation_gallery_main_slides_html']  = $variation_gallery_output['main_slides_html'];
			$settings['variation_gallery_thumb_slides_html'] = $variation_gallery_output['thumb_slides_html'];
		}

		return $settings;
	}

	public function get_variation_gallery_ids( $variation_id ) {
		$gallery_ids = get_post_meta( $variation_id, 'gallery_images', true );

		if ( ! empty( $gallery_ids ) && is_string( $gallery_ids ) ) {
			return explode( ',', $gallery_ids );
		}

		return array();
	}
}

Frontend::instance()->initialize();
