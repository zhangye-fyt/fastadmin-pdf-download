<?php
/**
 * Define constants.
 */
$theme = wp_get_theme();

if ( ! empty( $theme['Template'] ) ) {
	$theme = wp_get_theme( $theme['Template'] );
}

define( 'MINIMOG_THEME_NAME', $theme['Name'] );
define( 'MINIMOG_THEME_VERSION', $theme['Version'] );
define( 'MINIMOG_THEME_DIR', get_template_directory() );
define( 'MINIMOG_THEME_URI', get_template_directory_uri() );
define( 'MINIMOG_THEME_ASSETS_DIR', get_template_directory() . '/assets' );
define( 'MINIMOG_THEME_ASSETS_URI', get_template_directory_uri() . '/assets' );
define( 'MINIMOG_THEME_IMAGE_URI', MINIMOG_THEME_ASSETS_URI . '/images' );
define( 'MINIMOG_THEME_SVG_DIR', MINIMOG_THEME_ASSETS_DIR . '/svg' );
define( 'MINIMOG_FRAMEWORK_DIR', get_template_directory() . DIRECTORY_SEPARATOR . 'framework' );
define( 'MINIMOG_WIDGETS_DIR', get_template_directory() . DIRECTORY_SEPARATOR . 'widgets' );
define( 'MINIMOG_PROTOCOL', is_ssl() ? 'https' : 'http' );

define( 'MINIMOG_ELEMENTOR_DIR', get_template_directory() . DIRECTORY_SEPARATOR . 'elementor' );
define( 'MINIMOG_ELEMENTOR_URI', get_template_directory_uri() . '/elementor' );
define( 'MINIMOG_ELEMENTOR_ASSETS', get_template_directory_uri() . '/elementor/assets' );

/**
 * Load required functions
 */

/**
 * @param string $file_path File path to valid.
 *
 * @return mixed
 */
function minimog_valid_file_path( $file_path ) {
	return str_replace( '/', DIRECTORY_SEPARATOR, $file_path );
}

/**
 * @param string $file_path File path to include.
 */
function minimog_require_file_once( $file_path ) {
	$file_path = minimog_valid_file_path( $file_path );

	require_once $file_path;
}

/**
 * Define a constant if it is not already defined.
 *
 * @param string $name  Constant name.
 * @param mixed  $value Value.
 */
function minimog_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
 * Load Frameworks.
 */
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-functions.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-helper.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-debug.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-ajax.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-google-manager.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-font-awesome-manager.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-svg-manager.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-template-loader.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-site-layout.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-aqua-resizer.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-performance.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-static.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-init.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-global.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-actions-filters.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-kses.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-cookie-notice.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-promo-popup.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-admin.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-nav-menu-item.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-nav-menu.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-enqueue.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-attachment.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-image.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-logo.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-color.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-import.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-metabox.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-plugins.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-custom-css.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-templates.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-language-switcher.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-walker-nav-menu.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-walker-nav-menu-extra-items.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-widget.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-widgets.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-top-bar.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-header.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-title-bar.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-sidebar.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-footer.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-post-type-blog.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-woo.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/dokan/class-utils.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/tgm-plugin-activation.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/tgm-plugin-registration.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-tha.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-hcaptcha.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-login-register.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-wpforms.php' );
minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-instagram.php' );

minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-entry.php' );

minimog_require_file_once( MINIMOG_THEME_DIR . '/theme-options/main.php' );

/**
 * Init the theme
 */
Minimog_Init::instance()->initialize();
