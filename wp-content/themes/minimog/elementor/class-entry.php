<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

/**
 * Main Elementor Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Entry {

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_VERSION = '3.5.0';

	/**
	 * Recommended Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const RECOMMENDED_VERSION = '3.7.5';

	/**
	 * Minimum Elementor Pro Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_PRO_VERSION = '3.3.7';

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * The real constructor to initialize
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 */
	public function initialize() {
		// Check if Elementor installed and activated.
		if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return;
		}

		if ( version_compare( ELEMENTOR_VERSION, self::RECOMMENDED_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_recommend_version' ] );
		}

		// Check for required Elementor version.
		if ( version_compare( ELEMENTOR_VERSION, self::MINIMUM_VERSION, '<' ) ) {
			return;
		}

		// Notice if Elementor Pro used but low version.
		if ( defined( 'ELEMENTOR_PRO_VERSION' ) && version_compare( ELEMENTOR_PRO_VERSION, self::MINIMUM_PRO_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_pro_version' ] );
		}

		add_action( 'elementor/theme/register_locations', [ $this, 'register_theme_locations' ] );

		add_action( 'after_switch_theme', [ $this, 'add_cpt_support' ] );

		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-template-api.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-fonts.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/module-query.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-control-init.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-widget-utils.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-widget-init.php' );

		/**
		 * WPML supported.
		 */
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/wpml/class-wpml-translatable-nodes.php' );
	}

	public function enqueue_editor_styles() {
		wp_enqueue_style( 'minimog-elementor-editor', MINIMOG_ELEMENTOR_ASSETS . '/css/editor.css' );
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_script( 'minimog-elementor-editor', MINIMOG_ELEMENTOR_ASSETS . '/js/editor.js', [
			//'jquery',
			'backbone-marionette',
			'elementor-common',
			'elementor-editor-modules',
			'elementor-editor-document',
		], null, true );
	}

	/**
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager
	 *
	 * Register theme locations
	 */
	public function register_theme_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location( 'header' );
		$elementor_theme_manager->register_location( 'footer' );
		$elementor_theme_manager->register_location( 'single' );
		$elementor_theme_manager->register_location( 'archive' );
	}

	/**
	 * Enable default Elementor Editor for custom post type.
	 */
	public function add_cpt_support() {
		// If exists, assign to $cpt_support var.
		$cpt_support = get_option( 'elementor_cpt_support' );

		// Check if option DOESN'T exist in db.
		if ( ! $cpt_support ) {
			// Create array of our default supported post types.
			$cpt_support = [
				'page',
				'post',
				'ic_mega_menu',
			];
			update_option( 'elementor_cpt_support', $cpt_support );
		} else {
			if ( ! in_array( 'ic_mega_menu', $cpt_support ) ) {
				$cpt_support[] = 'ic_mega_menu';
				update_option( 'elementor_cpt_support', $cpt_support );
			}
		}
	}

	/**
	 * Warning when the site doesn't have Elementor installed or activated.
	 */
	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			'%1$s requires %2$s to be installed and activated.',
			'<strong>' . MINIMOG_THEME_NAME . '</strong>',
			'<strong>Elementor</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	public function admin_notice_recommend_version() {
		minimog_notice_required_plugin_version( 'Elementor', self::RECOMMENDED_VERSION );
	}

	public function admin_notice_minimum_pro_version() {
		minimog_notice_required_plugin_version( 'Elementor Pro', self::MINIMUM_PRO_VERSION );
	}
}

Entry::instance()->initialize();
