<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with Woongkir plugin.
 *
 * @see https://wordpress.org/plugins/woongkir/
 */
class Woongkir {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * Enqueue assets on all pages for Cart Drawer working.
		 */
		add_filter( 'woongkir_enqueue_frontend_assets', '__return_true' );
	}
}

Woongkir::instance()->initialize();
