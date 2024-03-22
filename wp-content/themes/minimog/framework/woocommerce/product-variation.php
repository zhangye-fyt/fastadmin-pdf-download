<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Variation {

	protected static $instance = null;

	private static $gallery_size = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'woocommerce_ajax_variation_threshold', [ $this, 'increase_variation_threshold' ] );

		add_filter( 'woocommerce_available_variation', [ $this, 'update_gallery_image_src' ], 20, 3 );

		add_filter( 'woocommerce_product_variation_title_include_attributes', [
			$this,
			'product_variation_title_exclude_attributes',
		], 10, 2 );
	}

	public function get_gallery_image_size() {
		if ( null === self::$gallery_size ) {
			$thumbnail_size = \Minimog_Woo::instance()->get_single_product_image_size( 100 );
			$site_layout    = \Minimog_Woo::instance()->get_single_product_site_layout();

			if ( 'wide' === $site_layout ) {
				$thumbnail_size = \Minimog_Woo::instance()->get_single_product_image_size( 130 );
			}

			self::$gallery_size = apply_filters( 'minimog/single_product/feature_slider/thumbnail_size', $thumbnail_size );
		}

		return self::$gallery_size;
	}

	/**
	 * Change gallery thumbnail size
	 *
	 * @param                       $settings
	 * @param \WC_Product_Variable  $product
	 * @param \WC_Product_Variation $variation
	 *
	 * @return mixed
	 */
	public function update_gallery_image_src( $settings, $product, $variation ) {
		$attachment_id = $variation->get_image_id();

		$attachment = get_post( $attachment_id );

		if ( $attachment && 'attachment' === $attachment->post_type ) {
			$gallery_src = \Minimog_Image::get_attachment_url_by_id( [
				'id'   => $attachment_id,
				'size' => $this->get_gallery_image_size(),
			] );

			$settings['image']['gallery_thumbnail_src'] = $gallery_src;
		}

		ob_start();
		if ( $variation->is_in_stock() && $variation->is_on_sale() && '1' === \Minimog::setting( 'shop_badge_sale' ) ) {
			$badge_classes = 'onsale';

			if ( ! empty( $variation->get_date_on_sale_from( 'edit' ) ) && ! empty( $variation->get_date_on_sale_to( 'edit' ) ) ) {
				$badge_classes = 'flash-sale has-icon';
			}

			$sale_badge_text = \Minimog_Woo::instance()->get_product_sale_badge_text( $variation );
			echo '<div class="' . $badge_classes . '"><span>' . $sale_badge_text . '</span></div>';
		}
		$settings['sale_flash_html'] = ob_get_clean();

		return $settings;
	}

	/**
	 * Remove attribute label that appended to product name.
	 *
	 * @param $should_include_attributes
	 * @param $product
	 *
	 * @return bool
	 */
	public function product_variation_title_exclude_attributes( $should_include_attributes, $product ) {
		// Don't exclude when exporting.
		if ( isset( $_POST['action'] ) && 'woocommerce_do_ajax_product_export' === $_POST['action'] ) {
			return $should_include_attributes;
		}

		return false;
	}

	/**
	 * Default threshold is 30
	 *
	 * @return int
	 * @see woocommerce_variable_add_to_cart()
	 */
	public function increase_variation_threshold() {
		return 100;
	}
}

Product_Variation::instance()->initialize();
