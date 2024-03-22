<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

class Enqueue {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 20 );

		/**
		 * Remove custom css. Use theme styling.
		 * Priority 99 to make sure run after plugin core
		 *
		 * @see \WeDevs\DokanPro\Modules\ColorSchemeCustomizer\Module::init_hooks()
		 */
		add_action( 'init', [ $this, 'remove_custom_css' ], 99 );
	}

	public function remove_custom_css() {
		minimog_remove_filters_for_anonymous_class( 'wp_head', 'WeDevs\DokanPro\Modules\ColorSchemeCustomizer\Module', 'load_styles' );
	}

	/**
	 * @see \WeDevs\Dokan\Assets::enqueue_front_scripts()
	 */
	public function frontend_scripts() {
		$min = \Minimog_Enqueue::instance()->get_min_suffix();
		$rtl = \Minimog_Enqueue::instance()->get_rtl_suffix();

		wp_deregister_style( 'dokan-style' );

		// Fix duplicate style.
		wp_dequeue_style( 'dokan-fontawesome' );
		wp_dequeue_style( 'dokan-select2-css' );

		// Used theme version for better performance.
		wp_dequeue_style( 'dokan-style' );

		// This asset depends on dokan-fontawesome then we need remove it.
		wp_dequeue_style( 'dokan-follow-store' );

		/**
		 * Used dokan prefix instead of theme prefix to fix duplicate assets depends on it.
		 */
		wp_register_style( 'dokan-style', MINIMOG_THEME_URI . "/assets/css/dokan/frontend{$min}.css", null, MINIMOG_THEME_VERSION );

		wp_register_script( 'minimog-dokan-store', MINIMOG_THEME_URI . "/assets/js/dokan/single-store{$min}.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );

		// Load dokan style on every pages. requires for shortcodes in other pages.
		if ( defined( 'DOKAN_LOAD_STYLE' ) && DOKAN_LOAD_STYLE ) {
			wp_enqueue_style( 'dokan-style' );
		}

		if ( dokan_is_store_page() ) {
			wp_enqueue_script( 'minimog-accordion' );
			wp_enqueue_script( 'minimog-dokan-store' );
		}

		if ( ! is_user_logged_in() ) {
			wp_register_script( 'speaking-url', MINIMOG_THEME_URI . "/assets/libs/speakingurl/speakingurl.min.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );

			wp_register_script( 'minimog-dokan-vendor-registration', MINIMOG_THEME_URI . "/assets/js/dokan/vendor-registration{$min}.js", [
				'jquery',
				'minimog-login',
				'speaking-url',
			], MINIMOG_THEME_VERSION, true );

			wp_enqueue_script( 'minimog-dokan-vendor-registration' );
		}
	}
}

Enqueue::instance()->initialize();
