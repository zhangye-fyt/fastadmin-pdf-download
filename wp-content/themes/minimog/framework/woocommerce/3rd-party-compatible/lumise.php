<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with Lumise - Product Designer Tool plugin.
 *
 * @see https://lumise.com/
 */
class Lumise {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activate() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
	}

	public function is_activate() {
		return class_exists( 'LumiseWoo' );
	}

	public function frontend_scripts() {
		$min = \Minimog_Enqueue::instance()->get_min_suffix();

		wp_register_style( 'minimog-wc-lumise', MINIMOG_THEME_URI . "/assets/css/wc/lumise{$min}.css", null, MINIMOG_THEME_VERSION );
		wp_enqueue_style( 'minimog-wc-lumise' );
	}
}

Lumise::instance()->initialize();
