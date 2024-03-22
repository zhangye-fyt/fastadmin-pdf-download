<?php
defined( 'ABSPATH' ) || exit;

/**
 * Debugging functions for developers.
 */
if ( ! class_exists( 'Minimog_Debug' ) ) {
	class Minimog_Debug {
		/**
		 * @param mixed $log Anything to write to log.
		 *
		 * Make sure WP_DEBUG_LOG = true.
		 */
		public static function write_log( $log ) {
			if ( true === WP_DEBUG ) {
				if ( is_array( $log ) || is_object( $log ) ) {
					error_log( print_r( $log, true ) );
				} else {
					error_log( $log );
				}
			}
		}

		public static function clear_log() {
			if ( file_exists( WP_CONTENT_DIR . '/debug.log' ) ) {
				unlink( WP_CONTENT_DIR . '/debug.log' );
			}
		}

		/**
		 * Get all filters apply to this hook.
		 *
		 * @param string $hook Name of filter, for eg: the_content
		 *
		 * @return mixed
		 */
		public static function get_filters_for( $hook = '' ) {
			global $wp_filter;

			if ( ! empty( $hook ) && isset( $wp_filter[ $hook ] ) ) {
				/**
				 * @var WP_Hook $wp_filter [ $hook ]
				 */
				self::write_log( $wp_filter[ $hook ]->callbacks );
			}
		}
	}

	new Minimog_Debug();
}
