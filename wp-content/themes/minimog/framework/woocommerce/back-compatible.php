<?php
/**
 * Back compatible with Woocommerce old version.
 */

/**
 * Given an element name, returns a class name.
 *
 * If the WP-related function is not defined, return empty string.
 *
 * @param string $element The name of the element.
 *
 * @since 7.1.0
 * @return string
 */
if ( ! function_exists( 'wc_wp_theme_get_element_class_name' ) ) {
	function wc_wp_theme_get_element_class_name( $element ) {
		if ( function_exists( 'wp_theme_get_element_class_name' ) ) {
			return wp_theme_get_element_class_name( $element );
		}

		return '';
	}
}
