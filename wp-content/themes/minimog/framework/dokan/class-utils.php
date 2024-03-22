<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Utils' ) ) {
	class Utils {

		protected static $instance = null;

		public $custom_store_url = '';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function define_constants() {
			define( 'MINIMOG_DOKAN_DIR', get_template_directory() . DIRECTORY_SEPARATOR . 'dokan' );
			define( 'MINIMOG_DOKAN_CORE_DIR', MINIMOG_FRAMEWORK_DIR . DIRECTORY_SEPARATOR . 'dokan' );
		}

		public function initialize() {
			$this->define_constants();

			// Do nothing if Dokan plugin not activated.
			if ( ! $this->is_activated() ) {
				return;
			}

			$this->custom_store_url = dokan_get_option( 'custom_store_url', 'dokan_general', 'store' );

			minimog_require_file_once( MINIMOG_DOKAN_CORE_DIR . '/admin-settings.php' );
			minimog_require_file_once( MINIMOG_DOKAN_CORE_DIR . '/vendor-registration.php' );
			minimog_require_file_once( MINIMOG_DOKAN_CORE_DIR . '/vendor-policies.php' );
			minimog_require_file_once( MINIMOG_DOKAN_CORE_DIR . '/vendor-biography.php' );
			minimog_require_file_once( MINIMOG_DOKAN_CORE_DIR . '/enqueue.php' );
			minimog_require_file_once( MINIMOG_DOKAN_CORE_DIR . '/single-store.php' );
			minimog_require_file_once( MINIMOG_DOKAN_CORE_DIR . '/single-product.php' );

			add_filter( 'insight_core_breadcrumb_arr', [ $this, 'breadcrumb' ], 10, 2 );
		}

		/**
		 * Check Dokan plugin active
		 *
		 * @return boolean true if plugin activated
		 */
		public function is_activated() {
			return class_exists( 'WeDevs_Dokan' );
		}

		public function is_pro_activated() {
			return class_exists( 'Dokan_Pro' );
		}

		public function breadcrumb( $breadcrumb_arr, $args ) {
			if ( ! dokan_is_store_page() ) {
				return $breadcrumb_arr;
			}

			$author      = get_query_var( $this->custom_store_url );
			$seller_info = get_user_by( 'slug', $author );

			if ( ! $seller_info ) {
				return $breadcrumb_arr;
			}

			$store_info    = dokan_get_store_info( $seller_info->ID );
			$store_listing = dokan_get_option( 'store_listing', 'dokan_pages', 0 );
			$listing_page  = get_post( $store_listing );

			if ( $listing_page instanceof \WP_Post ) {
				$breadcrumb_arr[] = array(
					'title' => $listing_page->post_title,
					'link'  => get_permalink( $listing_page ),
				);
			} else {
				$breadcrumb_arr[] = array(
					'title' => ucwords( $this->custom_store_url ),
					'link'  => site_url() . '/' . $this->custom_store_url,
				);
			}

			$breadcrumb_arr[] = array(
				'title' => $store_info['store_name'],
				'link'  => dokan_get_store_url( $seller_info->data->ID ),
			);

			return $breadcrumb_arr;
		}
	}

	Utils::instance()->initialize();
}
