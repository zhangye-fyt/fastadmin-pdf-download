<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Shoppable_Images {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 15 );
	}

	public function frontend_scripts() {
		/**
		 * Used custom script.
		 */
		wp_dequeue_script( 'mabel-shoppable-images-lite' );
	}
}

Shoppable_Images::instance()->initialize();
