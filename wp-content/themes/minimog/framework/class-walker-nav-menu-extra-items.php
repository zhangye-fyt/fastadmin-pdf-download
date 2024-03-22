<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add extra fields to nav menu edit
 */
if ( ! class_exists( 'Minimog_Extra_Nav_Menu_Items' ) ) {
	class Minimog_Extra_Nav_Menu_Items {

		protected static $instance = null;

		function __construct() {

		}

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			// Allow SVG.
			add_filter( 'wp_check_filetype_and_ext', [ $this, 'check_svg_support' ], 10, 4 );
			add_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );

			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

			add_action( 'wp_edit_nav_menu_walker', [ $this, 'edit_nav_menu_walker' ] );
			add_action( 'wp_update_nav_menu_item', [ $this, 'update_nav_menu_item' ], 10, 3 );
			add_filter( 'wp_setup_nav_menu_item', [ $this, 'setup_nav_menu_item' ] );
		}

		public function enqueue_scripts() {
			$screen = get_current_screen();
			if ( 'nav-menus' === $screen->id ) {
				wp_enqueue_media();
				wp_enqueue_script( 'menu-icon', MINIMOG_THEME_ASSETS_URI . '/admin/js/menu-icon.js', array( 'jquery' ), null, true );
			}
		}

		function check_svg_support( $data, $file, $filename, $mimes ) {
			global $wp_version;
			if ( $wp_version !== '4.7.1' ) {
				return $data;
			}

			$filetype = wp_check_filetype( $filename, $mimes );

			return [
				'ext'             => $filetype['ext'],
				'type'            => $filetype['type'],
				'proper_filename' => $data['proper_filename'],
			];
		}

		function add_svg_support( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';

			return $mimes;
		}

		/**
		 * Change the admin menu walker class name.
		 *
		 * @param string $walker
		 *
		 * @return string
		 */
		public function edit_nav_menu_walker( $walker ) {
			minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/class-walker-nav-menu-edit.php' );

			// Swap the menu walker class only if it's the default wp class (just in case).
			if ( $walker === 'Walker_Nav_Menu_Edit' ) {
				$walker = 'Minimog_Walker_Nav_Menu_Edit';
			}

			return $walker;
		}

		public function update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
			if ( isset( $_REQUEST['menu-item-icon'] ) && is_array( $_REQUEST['menu-item-icon'] ) ) {
				$custom_value = $_REQUEST['menu-item-icon'][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_menu_item_icon', $custom_value );
			}
		}

		/**
		 * Setup icon for both backend + frontend
		 *
		 * @param $menu_item
		 *
		 * @return mixed
		 */
		public function setup_nav_menu_item( $menu_item ) {
			$menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );

			if ( ! empty( $menu_item->icon ) ) {
				$attachment_info = Minimog_Image::get_attachment_info( $menu_item->icon );
				$attachment_ext  = wp_check_filetype( $attachment_info['src'] );

				$menu_item->icon_url  = $attachment_info['src'];
				$menu_item->icon_type = $attachment_ext['ext'];

				if ( 'svg' === $attachment_ext['ext'] ) {
					$svg_file_path       = get_attached_file( $menu_item->icon, true );
					$svg_file_content    = Minimog_Helper::get_file_contents( $svg_file_path );
					$menu_item->icon_svg = $svg_file_content;
				}
			}

			return $menu_item;
		}
	}

	Minimog_Extra_Nav_Menu_Items::instance()->initialize();
}
