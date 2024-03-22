<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with Elementor
 *
 * @see https://wordpress.org/plugins/elementor/
 */
class Elementor_P {

	private static $instance = null;

	/**
	 * Elementor pro setting that override default mini cart template.
	 *
	 * @var bool
	 */
	private $use_mini_cart_template = false;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		$this->use_mini_cart_template = 'yes' === get_option( 'elementor_use_mini_cart_template', 'no' );

		add_action( 'init', [ $this, 'prevent_elementor_edit_fragments' ] );
	}

	public function prevent_elementor_edit_fragments() {
		if ( ! $this->use_mini_cart_template ) {
			minimog_remove_filters_for_anonymous_class( 'woocommerce_add_to_cart_fragments', 'ElementorPro\Modules\Woocommerce\Module', 'e_cart_count_fragments' );
		}
	}
}

Elementor_P::instance()->initialize();
