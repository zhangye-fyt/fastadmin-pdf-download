<?php
defined( 'ABSPATH' ) || exit;

/**
 * Helper functions.
 *
 * Some functions don't need to name prefix because they checked with function_exists.
 */

/**
 * Compatible back with old WP version.
 */
if ( ! function_exists( 'wp_readonly' ) ) {
	function wp_readonly( $readonly, $current = true, $echo = true ) {
		return __checked_selected_helper( $readonly, $current, $echo, 'readonly' );
	}
}


/**
 * Returns '0'.
 *
 * Useful for returning 0 to filters easily.
 *
 * @return string '0'.
 */
if ( ! function_exists( '__return_zero_string' ) ) {
	function __return_zero_string() {
		return '0';
	}
}

if ( ! function_exists( 'html_class' ) ) {
	function html_class( $class = '' ) {
		$classes = array();

		if ( is_admin_bar_showing() ) {
			$classes[] = 'has-admin-bar';
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters( 'html_class', $classes, $class );

		if ( ! empty( $classes ) ) {
			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}
	}
}

/**
 * Hook in wp 5.2
 * Backwards Compatibility with old versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Loads a template part into a template with prefix folder given.
 *
 * @see get_template_part()
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array  $args Optional. Additional arguments passed to the template.
 *                     Default empty array.
 *
 * @return void|false Void on success, false if the template does not exist.
 */
function minimog_load_template( $slug, $name = null, $args = array() ) {
	get_template_part( "template-parts/{$slug}", $name, $args );
}

/**
 * Admin notice waning minimum plugin version required.
 *
 * @param string $plugin_name
 * @param string $plugin_version
 * @param bool   $show_link
 */
function minimog_notice_required_plugin_version( $plugin_name, $plugin_version, $show_link = false ) {
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}

	$message = sprintf(
		esc_html__( '%1$s requires %2$s version %3$s or greater.', 'minimog' ),
		'<strong>' . MINIMOG_THEME_NAME . '</strong>',
		'<strong>' . $plugin_name . '</strong>',
		$plugin_version
	);

	$link = $show_link ? '<a href="' . admin_url( 'admin.php?page=insight-core-plugins' ) . '">View update</a>' : '';

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s %2$s</p></div>', $message, $link );
}

/**
 * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name :)
 *
 * @see https://github.com/herewithme/wp-filters-extras/blob/master/wp-filters-extras.php
 *
 * @param string $hook_name   The action hook to which the function to be removed is hooked.
 * @param string $class_name  The class name of contain function which should be removed.
 * @param string $method_name The name of the function which should be removed.
 * @param int    $priority    Optional. The priority of the function. Default 10.
 *
 * @return bool
 */
function minimog_remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 10 ) {
	global $wp_filter;

	// Take only filters on right hook name and priority
	if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
		return false;
	}

	// Loop on filters registered
	foreach ( (array) $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
		// Test if filter is an array ! (always for class/method)
		if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {
			// Test if object is a class, class and method is equal to param !
			if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) == $class_name && $filter_array['function'][1] == $method_name ) {
				// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/)
				if ( is_a( $wp_filter[ $hook_name ], 'WP_Hook' ) ) {
					unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
				} else {
					unset( $wp_filter[ $hook_name ][ $priority ][ $unique_id ] );
				}
			}
		}

	}

	return false;
}

function minimog_has_elementor_template( $location ) {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
		return true;
	}

	return false;
}

/**
 * @see wc_get_template_part();
 * Edition version of wc_get_template_part
 * Added third param
 *
 * Get template part (for templates like the shop-loop).
 *
 * WC_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
 *
 * @param mixed  $slug Template slug.
 * @param string $name Template name (default: '').
 * @param array  $args Optional. Additional arguments passed to the template.
 *                     Default empty array.
 */
function minimog_get_wc_template_part( $slug, $name = '', $args = array() ) {
	$cache_key = sanitize_key( implode( '-', array(
		'template-part',
		$slug,
		$name,
		Automattic\Jetpack\Constants::get_constant( 'WC_VERSION' ),
	) ) );
	$template  = (string) wp_cache_get( $cache_key, 'woocommerce' );

	if ( ! $template ) {
		if ( $name ) {
			$template = WC_TEMPLATE_DEBUG_MODE ? '' : locate_template(
				array(
					"{$slug}-{$name}.php",
					WC()->template_path() . "{$slug}-{$name}.php",
				)
			);

			if ( ! $template ) {
				$fallback = WC()->plugin_path() . "/templates/{$slug}-{$name}.php";
				$template = file_exists( $fallback ) ? $fallback : '';
			}
		}

		if ( ! $template ) {
			// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
			$template = WC_TEMPLATE_DEBUG_MODE ? '' : locate_template(
				array(
					"{$slug}.php",
					WC()->template_path() . "{$slug}.php",
				)
			);
		}

		// Don't cache the absolute path so that it can be shared between web servers with different paths.
		$cache_path = wc_tokenize_path( $template, wc_get_path_define_tokens() );

		wc_set_template_cache( $cache_key, $cache_path );
	} else {
		// Make sure that the absolute path to the template is resolved.
		$template = wc_untokenize_path( $template, wc_get_path_define_tokens() );
	}

	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'wc_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false, $args );
	}
}

function minimog_get_login_url() {
	return apply_filters( 'minimog/user_login/url', wp_login_url() );
}

function minimog_get_user_profile_url() {
	return apply_filters( 'minimog/user_profile/url', wp_logout_url() );
}

/**
 * @noted This is a clone wp function
 * that skip object cache.
 * Because maybe run some functions before object cache setup then caused transient not working properly
 *
 * Deletes a transient.
 *
 * @since 2.8.0
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped.
 *
 * @return bool True if the transient was deleted, false otherwise.
 */
function minimog_delete_transient( $transient ) {

	/**
	 * Fires immediately before a specific transient is deleted.
	 *
	 * The dynamic portion of the hook name, `$transient`, refers to the transient name.
	 *
	 * @since 3.0.0
	 *
	 * @param string $transient Transient name.
	 */
	do_action( "delete_transient_{$transient}", $transient );

	/**
	 * Minimog disable object cache. Because it not set up on same case.
	 */
	//if ( wp_using_ext_object_cache() || wp_installing() ) {
	//	$result = wp_cache_delete( $transient, 'transient' );
	//} else {
	$option_timeout = '_transient_timeout_' . $transient;
	$option         = '_transient_' . $transient;
	$result         = delete_option( $option );

	if ( $result ) {
		delete_option( $option_timeout );
	}
	//}

	if ( $result ) {

		/**
		 * Fires after a transient is deleted.
		 *
		 * @since 3.0.0
		 *
		 * @param string $transient Deleted transient name.
		 */
		do_action( 'deleted_transient', $transient );
	}

	return $result;
}

/**
 * @noted This is a clone wp function
 * that skip object cache.
 * Because maybe run some functions before object cache setup then caused transient not working properly
 *
 * Retrieves the value of a transient.
 *
 * If the transient does not exist, does not have a value, or has expired,
 * then the return value will be false.
 *
 * @since 2.8.0
 *
 * @param string $transient Transient name. Expected to not be SQL-escaped.
 *
 * @return mixed Value of transient.
 */
function minimog_get_transient( $transient ) {

	/**
	 * Filters the value of an existing transient before it is retrieved.
	 *
	 * The dynamic portion of the hook name, `$transient`, refers to the transient name.
	 *
	 * Returning a truthy value from the filter will effectively short-circuit retrieval
	 * and return the passed value instead.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 The `$transient` parameter was added
	 *
	 * @param mixed  $pre_transient The default value to return if the transient does not exist.
	 *                              Any value other than false will short-circuit the retrieval
	 *                              of the transient, and return that value.
	 * @param string $transient     Transient name.
	 */
	$pre = apply_filters( "pre_transient_{$transient}", false, $transient );

	if ( false !== $pre ) {
		return $pre;
	}

	/**
	 * Minimog disable object cache. Because it not set up on same case.
	 */
	//if ( wp_using_ext_object_cache() || wp_installing() ) {
	//	$value = wp_cache_get( $transient, 'transient' );
	//} else {
	$transient_option = '_transient_' . $transient;
	if ( ! wp_installing() ) {
		// If option is not in alloptions, it is not autoloaded and thus has a timeout.
		$alloptions = wp_load_alloptions();
		if ( ! isset( $alloptions[ $transient_option ] ) ) {
			$transient_timeout = '_transient_timeout_' . $transient;
			$timeout           = get_option( $transient_timeout );
			if ( false !== $timeout && $timeout < time() ) {
				delete_option( $transient_option );
				delete_option( $transient_timeout );
				$value = false;
			}
		}
	}

	if ( ! isset( $value ) ) {
		$value = get_option( $transient_option );
	}
	//}

	/**
	 * Filters an existing transient's value.
	 *
	 * The dynamic portion of the hook name, `$transient`, refers to the transient name.
	 *
	 * @since 2.8.0
	 * @since 4.4.0 The `$transient` parameter was added
	 *
	 * @param mixed  $value     Value of transient.
	 * @param string $transient Transient name.
	 */
	return apply_filters( "transient_{$transient}", $value, $transient );
}

/**
 * @noted This is a clone wp function
 * that skip object cache.
 * Because maybe run some functions before object cache setup then caused transient not working properly
 *
 * Sets/updates the value of a transient.
 *
 * You do not need to serialize values. If the value needs to be serialized,
 * then it will be serialized before it is set.
 *
 * @since 2.8.0
 *
 * @param string $transient  Transient name. Expected to not be SQL-escaped.
 *                           Must be 172 characters or fewer in length.
 * @param mixed  $value      Transient value. Must be serializable if non-scalar.
 *                           Expected to not be SQL-escaped.
 * @param int    $expiration Optional. Time until expiration in seconds. Default 0 (no expiration).
 *
 * @return bool True if the value was set, false otherwise.
 */
function minimog_set_transient( $transient, $value, $expiration = 0 ) {

	$expiration = (int) $expiration;

	/**
	 * Filters a specific transient before its value is set.
	 *
	 * The dynamic portion of the hook name, `$transient`, refers to the transient name.
	 *
	 * @since 3.0.0
	 * @since 4.2.0 The `$expiration` parameter was added.
	 * @since 4.4.0 The `$transient` parameter was added.
	 *
	 * @param mixed  $value      New value of transient.
	 * @param int    $expiration Time until expiration in seconds.
	 * @param string $transient  Transient name.
	 */
	$value = apply_filters( "pre_set_transient_{$transient}", $value, $expiration, $transient );

	/**
	 * Filters the expiration for a transient before its value is set.
	 *
	 * The dynamic portion of the hook name, `$transient`, refers to the transient name.
	 *
	 * @since 4.4.0
	 *
	 * @param int    $expiration Time until expiration in seconds. Use 0 for no expiration.
	 * @param mixed  $value      New value of transient.
	 * @param string $transient  Transient name.
	 */
	$expiration = apply_filters( "expiration_of_transient_{$transient}", $expiration, $value, $transient );

	/**
	 * Minimog disable object cache. Because it not set up on same case.
	 */
	//if ( wp_using_ext_object_cache() || wp_installing() ) {
	//	$result = wp_cache_set( $transient, $value, 'transient', $expiration );
	//} else {
	$transient_timeout = '_transient_timeout_' . $transient;
	$transient_option  = '_transient_' . $transient;

	if ( false === get_option( $transient_option ) ) {
		$autoload = 'yes';
		if ( $expiration ) {
			$autoload = 'no';
			add_option( $transient_timeout, time() + $expiration, '', 'no' );
		}
		$result = add_option( $transient_option, $value, '', $autoload );
	} else {
		// If expiration is requested, but the transient has no timeout option,
		// delete, then re-create transient rather than update.
		$update = true;

		if ( $expiration ) {
			if ( false === get_option( $transient_timeout ) ) {
				delete_option( $transient_option );
				add_option( $transient_timeout, time() + $expiration, '', 'no' );
				$result = add_option( $transient_option, $value, '', 'no' );
				$update = false;
			} else {
				update_option( $transient_timeout, time() + $expiration );
			}
		}

		if ( $update ) {
			$result = update_option( $transient_option, $value );
		}
	}
	//}

	if ( $result ) {

		/**
		 * Fires after the value for a specific transient has been set.
		 *
		 * The dynamic portion of the hook name, `$transient`, refers to the transient name.
		 *
		 * @since 3.0.0
		 * @since 3.6.0 The `$value` and `$expiration` parameters were added.
		 * @since 4.4.0 The `$transient` parameter was added.
		 *
		 * @param mixed  $value      Transient value.
		 * @param int    $expiration Time until expiration in seconds.
		 * @param string $transient  The name of the transient.
		 */
		do_action( "set_transient_{$transient}", $value, $expiration, $transient );

		/**
		 * Fires after the value for a transient has been set.
		 *
		 * @since 3.0.0
		 * @since 3.6.0 The `$value` and `$expiration` parameters were added.
		 *
		 * @param string $transient  The name of the transient.
		 * @param mixed  $value      Transient value.
		 * @param int    $expiration Time until expiration in seconds.
		 */
		do_action( 'setted_transient', $transient, $value, $expiration );
	}

	return $result;
}

/**
 * @param $name
 *
 * @return array Get all transients like $name
 */
function minimog_get_transient_like( $name ) {
	global $wpdb;
	$transients = array();
	$sql        = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE %s";
	$query      = $wpdb->prepare( $sql, '%' . $wpdb->esc_like( '_transient_' . $name ) . '%' );

	$results = $wpdb->get_results( $query );
	if ( ! empty( $results ) ) {
		// We need remove _transient_ from option name.
		$prefix        = '_transient_';
		$prefix_length = strlen( $prefix );
		foreach ( $results as $record ) {
			if ( substr( $record->option_name, 0, $prefix_length ) == $prefix ) {
				$transient_name = substr( $record->option_name, $prefix_length );
				$transients[]   = $transient_name;
			}
		}
	}

	return $transients;
}
