<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Redux' ) ) {
	class Minimog_Redux {

		const OPTION_NAME = 'minimog_options';
		protected static $instance = null;
		public static $default_settings = array();

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			$this->set_default_settings();
			$this->define_constants();

			minimog_require_file_once( MINIMOG_REDUX_DIR . '/presets.php' );
			minimog_require_file_once( MINIMOG_REDUX_DIR . '/dynamic-output.php' );
			minimog_require_file_once( MINIMOG_REDUX_DIR . '/font-loader.php' );

			add_action( 'update_option_' . self::OPTION_NAME, [ $this, 'option_changed' ], 20, 3 );

			// Remove unused native sections and controls.
			add_action( 'customize_register', [ $this, 'remove_customizer_sections' ] );

			if ( ! class_exists( 'Redux' ) ) {
				return;
			}

			$this->setup();
			$this->register_sections();

			add_action( 'admin_init', [ $this, 'remove_demo_link' ] );

			add_action( 'admin_init', [ $this, 'get_registered_widgets' ], 9999 );
			add_action( 'update_option_kungfu_sidebars', [ $this, 'clear_registered_sidebars_transient' ], 20, 3 );

			add_action( 'redux/extensions/' . self::OPTION_NAME . '/before', [
				$this,
				'redux_register_custom_extension_loader',
			], 0 );

			add_filter( 'redux/options/' . self::OPTION_NAME . '/options', [ $this, 'presets' ], 10, 2 );

			add_action( 'redux/page/' . self::OPTION_NAME . '/enqueue', [ $this, 'update_field_typography' ] );
		}

		/**
		 * Used theme js version to support all font weights for custom fonts.
		 */
		public function update_field_typography() {
			global $wp_scripts;

			if ( false != $wp_scripts->queue ) {
				foreach ( $wp_scripts->queue as $script ) {
					if ( 'redux-field-typography-js' === $script && isset( $wp_scripts->registered[ $script ] ) ) {
						$wp_scripts->registered[ $script ]->src = MINIMOG_REDUX_FIELD_URI . '/typography/redux-typography.js';

						return;
					}
				}
			}
		}

		public function define_constants() {
			define( 'MINIMOG_REDUX_DIR', get_template_directory() . DIRECTORY_SEPARATOR . 'theme-options' );
			define( 'MINIMOG_REDUX_SECTION_DIR', MINIMOG_REDUX_DIR . DIRECTORY_SEPARATOR . 'sections' );
			define( 'MINIMOG_REDUX_EXTENSION_URI', get_template_directory_uri() . '/theme-options/extensions' );
			define( 'MINIMOG_REDUX_FIELD_URI', get_template_directory_uri() . '/theme-options/fields' );
		}

		public function option_changed( $old_value, $value, $option ) {
			Minimog_Logo::instance()->regenerate_logo_dimensions( $old_value, $value, $option );

			$transients_to_clear = apply_filters( 'minimog/options/transients_to_clear', array() );

			foreach ( $transients_to_clear as $transient_name ) {
				delete_transient( $transient_name );
			}
		}

		public function register_sections() {
			if ( ! is_admin() && ! is_customize_preview() ) {
				$options = get_option( self::OPTION_NAME );
				if ( Minimog_Helper::is_demo_site() || Minimog_Helper::is_dev_mode() ) {
					$options = $this->presets( $options );
				}

				$GLOBALS[ self::OPTION_NAME ] = $options;

				return;
			}

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Theme Styling', 'minimog' ),
				'id'               => 'theme_styling',
				'customizer_width' => '400px',
				'icon'             => 'eicon-theme-style',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/theme-styling/section-background.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/theme-styling/section-color.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/theme-styling/section-typography.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/theme-styling/section-rounded.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Site Info', 'minimog' ),
				'id'               => 'panel_site_info',
				'customizer_width' => '400px',
				'icon'             => 'eicon-info-circle-o',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/site-info/info-list.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/site-info/social-networks.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/site-info/social-sharing.php' );

			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-logo.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Top Bar', 'minimog' ),
				'id'               => 'top_bar',
				'customizer_width' => '400px',
				'icon'             => 'eicon-v-align-top',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/top-bar/section-general.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/top-bar/section-style-01.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Header', 'minimog' ),
				'id'               => 'header',
				'customizer_width' => '400px',
				'icon'             => 'eicon-header',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-general.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/sticky.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/category-menu.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-01.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-02.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-03.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-04.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-05.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-06.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-07.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-08.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-09.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/header/section-style-10.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Navigation', 'minimog' ),
				'id'               => 'navigation',
				'customizer_width' => '400px',
				'icon'             => 'eicon-menu-bar',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/navigation/desktop-menu.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/navigation/mobile-menu.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/navigation/mobile-tabs.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Title Bar & Breadcrumb', 'minimog' ),
				'id'               => 'title_bar_n_breadcrumb',
				'customizer_width' => '400px',
				'icon'             => 'eicon-archive-title',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/title-bar/general.php' );
			//minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/title-bar/standard-01.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/title-bar/minimal-01.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/title-bar/fill-01.php' );

			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-footer.php' );

			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-sidebar.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Blog', 'minimog' ),
				'id'               => 'panel_blog',
				'customizer_width' => '400px',
				'icon'             => 'eicon-posts-group',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/blog/archive.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/blog/single.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Shop', 'minimog' ),
				'id'               => 'panel_shop',
				'customizer_width' => '400px',
				'icon'             => 'eicon-cart',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/general.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/archive.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/category.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/single.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/cart.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/checkout.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/wishlist.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/quick-view.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/product-quantity.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/shop/product-question.php' );

			if ( class_exists( '\Minimog\Dokan\Utils' ) && \Minimog\Dokan\Utils::instance()->is_activated() ) {
				Redux::set_section( Minimog_Redux::OPTION_NAME, [
					'title'            => esc_html__( 'Vendor Store', 'minimog' ),
					'id'               => 'panel_vendor_store',
					'customizer_width' => '400px',
					'icon'             => 'eicon-person',
				] );
				minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/vendor-store/single.php' );
			}

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Search', 'minimog' ),
				'id'               => 'panel_search',
				'customizer_width' => '400px',
				'icon'             => 'eicon-search',
			] );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/search/search-page.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/search/search-popup.php' );

			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-popup.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-login.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-404.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-pre-loader.php' );

			Redux::set_section( Minimog_Redux::OPTION_NAME, [
				'title'            => esc_html__( 'Advanced', 'minimog' ),
				'id'               => 'panel_advanced',
				'customizer_width' => '400px',
				'icon'             => 'eicon-cog',
			] );

			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/advanced/advanced.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/advanced/api-integrations.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/advanced/light-gallery.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/advanced/cookie-notice.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/advanced/performance.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-custom-code.php' );
			minimog_require_file_once( MINIMOG_REDUX_SECTION_DIR . '/section-presets.php' );
		}

		public function setup() {
			$args = array(
				// This is where your data is stored in the database and also becomes your global variable name.
				'opt_name'        => self::OPTION_NAME,

				// Name that appears at the top of your panel.
				'display_name'    => MINIMOG_THEME_NAME,

				// Version that appears at the top of your panel.
				'display_version' => MINIMOG_THEME_VERSION,

				// Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only).
				'menu_type'       => 'menu',

				// Show the sections below the admin menu item or not.
				'allow_sub_menu'  => true,

				// The text to appear in the admin menu.
				'menu_title'      => esc_html__( 'Theme Options', 'minimog' ),

				// The text to appear on the page title.
				'page_title'      => esc_html__( 'Theme Options', 'minimog' ),

				'intro_text'                => wp_kses( sprintf( __( 'Thank you for using our theme, please reward it a full five-star &#9733;&#9733;&#9733;&#9733;&#9733; rating. <br/> <a href="%s" target="_blank">Need support?</a> | <a href="%s">Check Update</a>', 'minimog' ), esc_url( 'https://thememove.ticksy.com/submit/#100019229' ), admin_url( 'admin.php?page=insight-core' ) ), array(
					'a'  => array(
						'target' => array(),
						'href'   => array(),
					),
					'br' => array(),
				) ),

				// Disable to create your own Google fonts loader.
				'disable_google_fonts_link' => false,

				// Show the panel pages on the admin bar.
				'admin_bar'                 => true,

				// Icon for the admin bar menu.
				'admin_bar_icon'            => 'dashicons-portfolio',

				// Priority for the admin bar menu.
				'admin_bar_priority'        => 50,

				// Sets a different name for your global variable other than the opt_name.
				'global_variable'           => '',

				// Show the time the page took to load, etc. (forced on while on localhost or when WP_DEBUG is enabled).
				'dev_mode'                  => false,

				// Enable basic customizer support.
				'customizer'                => true,

				// Allow the panel to open expanded.
				'open_expanded'             => false,

				// Disable the save warning when a user changes a field.
				'disable_save_warn'         => false,

				// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
				'page_priority'             => null,

				// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters.
				'page_parent'               => 'themes.php',

				// Permissions needed to access the options panel.
				'page_permissions'          => 'manage_options',

				// Specify a custom URL to an icon.
				'menu_icon'                 => '',

				// Force your panel to always open to a specific tab (by id).
				'last_tab'                  => '',

				// Icon displayed in the admin panel next to your menu_title.
				'page_icon'                 => 'icon-themes',

				// Page slug used to denote the panel, will be based off page title, then menu title, then opt_name if not provided.
				'page_slug'                 => self::OPTION_NAME,

				// On load save the defaults to DB before user clicks save.
				'save_defaults'             => true,

				// Display the default value next to each field when not set to the default value.
				'default_show'              => false,

				// What to print by the field's title if the value shown is default.
				'default_mark'              => '',

				// Shows the Import/Export panel when not used as a field.
				'show_import_export'        => true,

				// The time transients will expire when the 'database' arg is set.
				'transient_time'            => 60 * MINUTE_IN_SECONDS,

				// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output.
				'output'                    => false,

				// Allows dynamic CSS to be generated for customizer and google fonts,
				// but stops the dynamic CSS from going to the page head.
				'output_tag'                => true,

				// Disable the footer credit of Redux. Please leave if you can help it.
				'footer_credit'             => '',

				// If you prefer not to use the CDN for ACE Editor.
				// You may download the Redux Vendor Support plugin to run locally or embed it in your code.
				'use_cdn'                   => true,

				// Set the theme of the option panel.  Use 'wp' to use a more modern style, default is classic.
				'admin_theme'               => 'wp',

				// Enable or disable flyout menus when hovering over a menu with submenus.
				'flyout_submenus'           => true,

				// Mode to display fonts (auto|block|swap|fallback|optional)
				// See: https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display.
				'font_display'              => 'swap',

				// HINTS.
				'hints'                     => array(
					'icon'          => 'el el-question-sign',
					'icon_position' => 'right',
					'icon_color'    => 'lightgray',
					'icon_size'     => 'normal',
					'tip_style'     => array(
						'color'   => 'red',
						'shadow'  => true,
						'rounded' => false,
						'style'   => '',
					),
					'tip_position'  => array(
						'my' => 'top left',
						'at' => 'bottom right',
					),
					'tip_effect'    => array(
						'show' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'mouseover',
						),
						'hide' => array(
							'effect'   => 'slide',
							'duration' => '500',
							'event'    => 'click mouseleave',
						),
					),
				),

				// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
				// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
				'database'                  => '',
				'network_admin'             => true,
			);

			Redux::set_args( self::OPTION_NAME, $args );
		}

		/**
		 * Extension loader
		 *
		 * @param $ReduxFramework
		 */
		public function redux_register_custom_extension_loader( $ReduxFramework ) {
			$extensions = [
				'repeater',
				'tm_heading',
			];

			foreach ( $extensions as $extension ) {
				$extension_class = 'ReduxFramework_extension_' . $extension;

				if ( ! class_exists( $extension_class ) ) {
					// In case you wanted override your override, hah.
					$class_file = MINIMOG_REDUX_DIR . '/extensions/' . $extension . '/extension_' . $extension . '.php';
					//$class_file = apply_filters( 'redux/extension/' . $ReduxFramework->args['opt_name'] . '/' . $folder, $class_file );

					if ( file_exists( $class_file ) ) {
						require_once( $class_file );
					}
				}

				if ( ! isset( $ReduxFramework->extensions[ $extension ] ) ) {
					$ReduxFramework->extensions[ $extension ] = new $extension_class( $ReduxFramework );
				}
			}
		}

		/**
		 * Remove demo mode link
		 */
		public function remove_demo_link() {
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
				remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
			}
		}

		public function clear_registered_sidebars_transient() {
			minimog_delete_transient( 'minimog_registered_widgets' );
		}

		public function get_registered_widgets() {
			$cache_key = 'minimog_registered_widgets';

			$sidebars = minimog_get_transient( $cache_key );

			if ( false === $sidebars ) {
				global $wp_registered_sidebars;
				$sidebars = [];

				if ( ! empty( $wp_registered_sidebars ) ) {
					foreach ( $wp_registered_sidebars as $sidebar ) {
						$sidebars[ $sidebar['id'] ] = $sidebar['name'];
					}

					/**
					 * Only set transient when have sidebars
					 * To avoid empty array on Window.
					 */
					minimog_set_transient( $cache_key, $sidebars, HOUR_IN_SECONDS * 2 );
				}
			}

			return $sidebars;
		}

		public function get_registered_widgets_options( $default_option = false, $empty_option = true ) {
			$sidebars      = $this->get_registered_widgets();
			$extra_options = [];

			if ( $empty_option === true ) {
				$extra_options['none'] = esc_html__( 'No Sidebar', 'minimog' );
			}
			if ( $default_option === true ) {
				$extra_options['default'] = esc_html__( 'Default', 'minimog' );
			}

			$sidebars = $extra_options + $sidebars;

			return $sidebars;
		}

		public function get_presets() {
			$presets = [
				/**
				 * Demo settings per site used for multi sites
				 * This key must be before settings_preset to make priority working properly
				 */
				'site_settings_preset' => [
					'megamog'   => Minimog_Redux_Presets::get_settings( 'megamog' ),
					'supergear' => Minimog_Redux_Presets::get_settings( 'supergear' ),
					'megastore' => Minimog_Redux_Presets::get_settings( 'megastore' ),
					'autopart'  => Minimog_Redux_Presets::get_settings( 'autopart' ),
					'rtl'       => Minimog_Redux_Presets::get_settings( 'rtl' ),
				],
				'settings_preset'      => [
					'home-fashion-v9'     => Minimog_Redux_Presets::get_settings( 'home-fashion-v9' ),
					'home-watch'          => Minimog_Redux_Presets::get_settings( 'home-watch' ),
					'home-bra'            => Minimog_Redux_Presets::get_settings( 'home-bra' ),
					'home-case-phone'     => Minimog_Redux_Presets::get_settings( 'home-case-phone' ),
					'home-backpack'       => Minimog_Redux_Presets::get_settings( 'home-backpack' ),
					'home-drink'          => Minimog_Redux_Presets::get_settings( 'home-drink' ),
					'home-stationery'     => Minimog_Redux_Presets::get_settings( 'home-stationery' ),
					'home-sneaker'        => Minimog_Redux_Presets::get_settings( 'home-sneaker' ),
					'home-art'            => Minimog_Redux_Presets::get_settings( 'home-art' ),
					'home-toy'            => Minimog_Redux_Presets::get_settings( 'home-toy' ),
					'home-living'         => Minimog_Redux_Presets::get_settings( 'home-living' ),
					'home-glasses'        => Minimog_Redux_Presets::get_settings( 'home-glasses' ),
					'home-plants'         => Minimog_Redux_Presets::get_settings( 'home-plants' ),
					'home-coffee'         => Minimog_Redux_Presets::get_settings( 'home-coffee' ),
					'home-bedding'        => Minimog_Redux_Presets::get_settings( 'home-bedding' ),
					'home-print'          => Minimog_Redux_Presets::get_settings( 'home-print' ),
					'home-activewear'     => Minimog_Redux_Presets::get_settings( 'home-activewear' ),
					'home-furniture'      => Minimog_Redux_Presets::get_settings( 'home-furniture' ),
					'home-skateboard'     => Minimog_Redux_Presets::get_settings( 'home-skateboard' ),
					'home-pizza'          => Minimog_Redux_Presets::get_settings( 'home-pizza' ),
					'home-jewelry'        => Minimog_Redux_Presets::get_settings( 'home-jewelry' ),
					'home-supplyment'     => Minimog_Redux_Presets::get_settings( 'home-supplyment' ),
					'home-bag'            => Minimog_Redux_Presets::get_settings( 'home-bag' ),
					'home-nail-polish'    => Minimog_Redux_Presets::get_settings( 'home-nail-polish' ),
					'home-baby'           => Minimog_Redux_Presets::get_settings( 'home-baby' ),
					'home-socks'          => Minimog_Redux_Presets::get_settings( 'home-socks' ),
					'home-juice'          => Minimog_Redux_Presets::get_settings( 'home-juice' ),
					'home-barber'         => Minimog_Redux_Presets::get_settings( 'home-barber' ),
					'home-beauty'         => Minimog_Redux_Presets::get_settings( 'home-beauty' ),
					'home-mirror'         => Minimog_Redux_Presets::get_settings( 'home-mirror' ),
					'home-electronic'     => Minimog_Redux_Presets::get_settings( 'home-electronic' ),
					'home-houseware'      => Minimog_Redux_Presets::get_settings( 'home-houseware' ),
					'home-book'           => Minimog_Redux_Presets::get_settings( 'home-book' ),
					'home-hat'            => Minimog_Redux_Presets::get_settings( 'home-hat' ),
					'home-hand-santizer'  => Minimog_Redux_Presets::get_settings( 'home-hand-santizer' ),
					'home-bathroom'       => Minimog_Redux_Presets::get_settings( 'home-bathroom' ),
					'home-skincare'       => Minimog_Redux_Presets::get_settings( 'home-skincare' ),
					'home-candles'        => Minimog_Redux_Presets::get_settings( 'home-candles' ),
					'home-organic'        => Minimog_Redux_Presets::get_settings( 'home-organic' ),
					'home-pet'            => Minimog_Redux_Presets::get_settings( 'home-pet' ),
					'home-pan'            => Minimog_Redux_Presets::get_settings( 'home-pan' ),
					'home-paint'          => Minimog_Redux_Presets::get_settings( 'home-paint' ),
					'home-pod'            => Minimog_Redux_Presets::get_settings( 'home-pod' ),
					'home-gym-supplyment' => Minimog_Redux_Presets::get_settings( 'home-gym-supplyment' ),
					'home-speaker'        => Minimog_Redux_Presets::get_settings( 'home-speaker' ),
					'home-postcard'       => Minimog_Redux_Presets::get_settings( 'home-postcard' ),
					'home-christmas'      => Minimog_Redux_Presets::get_settings( 'home-christmas' ),
					'home-bfcm'           => Minimog_Redux_Presets::get_settings( 'home-bfcm' ),
					'home-surfboard'      => Minimog_Redux_Presets::get_settings( 'home-surfboard' ),
					'home-bike'           => Minimog_Redux_Presets::get_settings( 'home-bike' ),
					'home-ceramic'        => Minimog_Redux_Presets::get_settings( 'home-ceramic' ),
					'home-camping'        => Minimog_Redux_Presets::get_settings( 'home-camping' ),
					'home-cake'           => Minimog_Redux_Presets::get_settings( 'home-cake' ),
					'home-soap'           => Minimog_Redux_Presets::get_settings( 'home-soap' ),
					'home-floral'         => Minimog_Redux_Presets::get_settings( 'home-floral' ),
					'home-smart-light'    => Minimog_Redux_Presets::get_settings( 'home-smart-light' ),
					'home-puppies'        => Minimog_Redux_Presets::get_settings( 'home-puppies' ),
					'home-keyboard'       => Minimog_Redux_Presets::get_settings( 'home-keyboard' ),
					'home-halloween'      => Minimog_Redux_Presets::get_settings( 'home-halloween' ),
					'home-bfcm-coachella' => Minimog_Redux_Presets::get_settings( 'home-bfcm-coachella' ),
					'home-stroller'       => Minimog_Redux_Presets::get_settings( 'home-stroller' ),
				],
			];

			return apply_filters( 'minimog/theme_option/presets', $presets );
		}

		public function presets( $options ) {
			$site_slug = $this->get_current_site_slug();
			if ( '' !== $site_slug ) {
				$_GET['site_settings_preset'] = $site_slug;
			}

			$postID = 0;

			if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
				$http = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' );

				$url    = explode( '?', $http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
				$postID = url_to_postid( $url[0] );
			}

			if ( ! empty( $postID ) ) {
				$post = get_post( $postID );

				if ( $post instanceof WP_Post ) {
					switch ( $post->post_type ) {
						case 'post':
							$meta_key = 'insight_post_options';
							break;
						case 'product':
							$meta_key = 'insight_product_options';
							break;
						default:
							$meta_key = 'insight_page_options';
							break;
					}

					$page_options = unserialize( get_post_meta( $postID, $meta_key, true ) );
					if ( ! empty( $page_options['settings_preset'] ) ) {
						$_GET['settings_preset'] = $page_options['settings_preset'];
					}
				}
			}

			$presets = $this->get_presets();

			foreach ( $presets as $preset_name => $preset_values ) {
				if ( is_array( $preset_values ) ) {
					foreach ( $preset_values as $preset_value => $new_options ) {
						if ( isset( $_GET[ $preset_name ] ) && $_GET[ $preset_name ] === $preset_value && is_array( $new_options ) ) {
							foreach ( $new_options as $option_id => $new_value ) {
								$options[ $option_id ] = $new_value;
							}
						}
					}
				}
			}

			return $options;
		}

		/**
		 * Set default value for all settings here.
		 * It make theme working properly when Redux Framework not installed
		 */
		public function set_default_settings() {
			$default_settings = [
				/**
				 * Theme Colors
				 */
				'primary_color'                                    => Minimog::PRIMARY_COLOR,
				'secondary_color'                                  => Minimog::SECONDARY_COLOR,
				'link_color'                                       => '#000',
				'link_hover_color'                                 => '#999',
				'body_color'                                       => Minimog::TEXT_COLOR,
				'body_lighten_color'                               => Minimog::TEXT_LIGHTEN_COLOR,
				'heading_color'                                    => Minimog::HEADING_COLOR,
				'button_text_color'                                => '#fff',
				'button_background_color'                          => '#000',
				'button_border_color'                              => '#000',
				'button_hover_text_color'                          => '#fff',
				'button_hover_background_color'                    => '#000',
				'button_hover_border_color'                        => '#000',
				'button2_text_color'                               => '#000',
				'button2_background_color'                         => 'rgba(0,0,0,0)',
				'button2_border_color'                             => '#000',
				'button2_hover_text_color'                         => '#fff',
				'button2_hover_background_color'                   => '#000',
				'button2_hover_border_color'                       => '#000',
				'form_text_color'                                  => '#000',
				'form_background_color'                            => '#fff',
				'form_border_color'                                => '#d2d2d2',
				'form_box_shadow'                                  => '',
				'form_focus_text_color'                            => '#000',
				'form_focus_background_color'                      => '#fff',
				'form_focus_border_color'                          => '#000',
				'form_focus_box_shadow'                            => '',

				/**
				 * Theme Rounded
				 */
				'box_rounded'                                      => false,
				'small_rounded'                                    => 0,
				'normal_rounded'                                   => 0,
				'semi_rounded'                                     => 0,
				'large_rounded'                                    => 0,
				'form_input_small_rounded'                         => 5,
				'form_input_normal_border_thickness'               => 1,
				'form_input_normal_rounded'                        => 5,
				'form_textarea_rounded'                            => 5,
				'button_small_rounded'                             => 5,
				'button_rounded'                                   => 5,
				'button_large_rounded'                             => 5,

				/**
				 * Theme Typography
				 */
				'typography_body'                                  => array(
					'font-family' => Minimog::PRIMARY_FONT,
					'font-weight' => '400',
					'font-size'   => '16px',
					'line-height' => '28px',
				),
				'typography_heading'                               => array(
					'font-family' => Minimog::PRIMARY_FONT,
					'font-weight' => '400',
					'line-height' => '28px',
				),
				'typography_heading_weight_2'                      => '500',
				'button_typography_inherit'                        => '1',
				'button_typography_custom_weight'                  => '500',
				'button_typography_custom'                         => array(
					'font-size' => '16px',
				),
				'button_typography'                                => array(
					'font-family' => Minimog::PRIMARY_FONT,
					'font-weight' => '500',
					'font-size'   => '16px',
				),
				'form_typography_inherit'                          => '1',
				'form_typography_custom_weight'                    => '400',
				'form_typography_custom'                           => array(
					'font-size'   => '15px',
					'line-height' => '28px',
				),
				'form_typography'                                  => array(
					'font-family' => Minimog::PRIMARY_FONT,
					'font-weight' => '400',
					'font-size'   => '15px',
				),

				/**
				 * Top bar
				 */
				'global_top_bar'                                   => '01',
				'top_bar_text'                                     => 'Free Delivery on orders over $200. Don’t miss discount.',
				'top_bar_style_01_content_width'                   => Minimog_Site_Layout::CONTAINER_NORMAL,
				'top_bar_style_01_layout'                          => '1c',
				'top_bar_style_01_left_components'                 => [
					'social_links' => true,
				],
				'top_bar_style_01_center_components'               => [
					'text' => true,
				],
				'top_bar_style_01_right_components'                => [
					'language_switcher' => true,
				],
				'top_bar_style_01_visibility'                      => '1',
				'top_bar_style_01_bg_color'                        => '#DA3F3F',
				'top_bar_style_01_text_typography'                 => array(
					'font-family' => Minimog::PRIMARY_FONT,
					'font-weight' => '500',
					'font-size'   => '15px',
					'line-height' => '26px',
					'color'       => '#ffffff',
				),
				'top_bar_style_01_link_color'                      => '#fff',
				'top_bar_style_01_link_hover_color'                => 'rgba(255, 255, 255, 0.7)',

				/**
				 * Header
				 */
				'global_header'                                    => '01',
				'global_header_overlay'                            => '0',
				'global_header_skin'                               => 'dark',
				'header_button_link_target'                        => '0',
				'header_search_form_style'                         => 'normal',
				'header_style_navigation_typography'               => array(
					'font-family' => Minimog::PRIMARY_FONT,
					'font-weight' => '500',
					'font-size'   => '16px',
					'line-height' => '22px',
				),
				'header_icons_style'                               => 'icon-set-01',
				'header_cart_icon_style'                           => 'icon-set-01',
				'header_icons_display'                             => 'icon',
				'header_cart_icon_display'                         => 'icon',
				'header_wishlist_icon_type'                        => 'star',
				'header_icons_badge_size'                          => 'large',
				'header_navigation_item_hover_style'               => 'line',
				'header_button_style'                              => 'border',
				'header_text'                                      => 'Open Doors To A World Of Fashion<span class="separator"></span><a href="#">Discover More</a>',

				// Sticky.
				'header_sticky_enable'                             => '1',
				'header_sticky_logo'                               => 'dark',
				'header_sticky_background'                         => array(
					'background-color' => '#fff',
				),
				'header_category_menu_enable'                      => '0',
				'header_category_menu_sticky_homepage'             => '0',
				'header_category_menu_link_rounded'                => 5,
				// Header 01.
				'header_style_01_content_width'                    => Minimog_Site_Layout::CONTAINER_WIDE,
				'header_style_01_header_above_enable'              => '1',
				'header_style_01_search_enable'                    => 'popup',
				'header_style_01_login_enable'                     => '1',
				'header_style_01_wishlist_enable'                  => '1',
				'header_style_01_cart_enable'                      => '1',
				'header_style_01_info_list_enable'                 => '1',
				'header_style_01_currency_switcher_enable'         => '1',
				'header_style_01_language_switcher_enable'         => '1',
				'header_style_01_social_networks_enable'           => '0',
				'header_style_01_text_enable'                      => '1',
				'header_style_01_button_enable'                    => '0',
				'header_style_01_info_list_secondary_enable'       => '0',

				// Logo.
				'logo_dark'                                        => array(
					'url' => MINIMOG_THEME_IMAGE_URI . '/logo/dark-logo.png',
				),
				'logo_light'                                       => array(
					'url' => MINIMOG_THEME_IMAGE_URI . '/logo/light-logo.png',
				),
				'logo_width'                                       => 145,
				'tablet_logo_width'                                => 120,
				'mobile_logo_width'                                => 100,
				'sticky_logo_width'                                => 145,
				'logo_padding'                                     => array(
					'padding-top'    => '5',
					'padding-right'  => '0',
					'padding-bottom' => '5',
					'padding-left'   => '0',
					'units'          => 'px',
				),

				/**
				 * Mobile Menu.
				 */
				'mobile_menu_login_enable'                         => '1',
				'mobile_menu_wishlist_enable'                      => '0',
				'mobile_menu_info_list_enable'                     => '0',
				'mobile_menu_social_networks_enable'               => '0',
				'mobile_menu_language_switcher_enable'             => '0',
				'mobile_menu_breakpoint'                           => 1199,
				'mobile_menu_open_animation'                       => 'slide',
				'mobile_menu_background'                           => array(
					'background-color' => '#fff',
				),
				'mobile_menu_nav_level_1_padding'                  => array(
					'padding-top'    => '13',
					'padding-right'  => '0',
					'padding-bottom' => '13',
					'padding-left'   => '0',
				),

				/**
				 * Mobile Tabs
				 */
				'mobile_tabs_enable'                               => true,
				'mobile_tabs'                                      => [
					'home'     => true,
					'shop'     => true,
					'login'    => false,
					'wishlist' => true,
					'cart'     => true,
					'search'   => true,
				],

				/**
				 * Title Bar
				 */
				'title_bar_layout'                                 => Minimog_Title_Bar::DEFAULT_TYPE,
				'title_bar_search_title'                           => esc_html__( 'Search results for: ', 'minimog' ),
				'title_bar_home_title'                             => esc_html__( 'Blog', 'minimog' ),
				'title_bar_archive_category_title'                 => esc_html__( 'Category: ', 'minimog' ),
				'title_bar_archive_tag_title'                      => esc_html__( 'Tag: ', 'minimog' ),
				'title_bar_archive_author_title'                   => esc_html__( 'Author: ', 'minimog' ),
				'title_bar_archive_year_title'                     => esc_html__( 'Year: ', 'minimog' ),
				'title_bar_archive_month_title'                    => esc_html__( 'Month: ', 'minimog' ),
				'title_bar_archive_day_title'                      => esc_html__( 'Day: ', 'minimog' ),
				'title_bar_single_blog_title'                      => esc_html__( 'Blog', 'minimog' ),
				'title_bar_minimal_01_margin'                      => [
					'margin-bottom' => '60',
					'units'         => 'px',
				],
				'title_bar_minimal_01_text_align'                  => 'center',
				'title_bar_minimal_01_breadcrumb_min_height'       => [
					'height' => 0,
				],
				'title_bar_fill_01_breadcrumb_text_align'          => 'center',

				/**
				 * Sidebar
				 */
				'sidebars_below_content_mobile'                    => '1',
				'single_sidebar_width'                             => array(
					'width' => 25,
					'units' => '%',
				),
				'single_sidebar_offset'                            => array(
					'width' => 0,
					'units' => 'px',
				),
				'dual_sidebar_width'                               => array(
					'width' => 25,
					'units' => '%',
				),
				'dual_sidebar_offset'                              => array(
					'width' => 0,
					'units' => 'px',
				),

				/**
				 * Footer
				 */
				'footer_copyright_text'                            => esc_html__( 'Copyright &copy; 2022. All rights reserved.', 'minimog' ),
				'page_blocks_style'                                => 'normal',

				/**
				 * Blog Archive.
				 */
				'blog_archive_page_sidebar_1'                      => 'blog_sidebar',
				'blog_archive_page_sidebar_2'                      => 'none',
				'blog_archive_page_sidebar_position'               => 'right',
				'blog_archive_single_sidebar_width'                => array(
					'width' => 25,
					'units' => '%',
				),
				'blog_archive_single_sidebar_offset'               => array(
					'width' => 0,
					'units' => 'px',
				),
				'blog_archive_page_sidebar_style'                  => '01',
				'blog_archive_site_layout'                         => Minimog_Site_Layout::CONTAINER_NORMAL,
				'blog_archive_style'                               => 'grid',
				'blog_archive_masonry'                             => false,
				'blog_archive_grid_image_size'                     => '740x480',
				'blog_archive_grid_caption_style'                  => '01',
				'blog_archive_grid_caption_alignment'              => 'left',
				'blog_archive_lg_columns'                          => 3,
				'blog_archive_lg_gutter'                           => 30,
				'blog_archive_md_columns'                          => 2,
				'blog_archive_md_gutter'                           => 30,
				'blog_archive_sm_columns'                          => 1,
				'blog_archive_sm_gutter'                           => 30,
				'blog_archive_pagination_type'                     => '',
				'blog_archive_posts_per_page'                      => 12,

				/**
				 * Blog Single.
				 */
				'blog_single_title_bar_layout'                     => Minimog_Title_Bar::DEFAULT_MINIMAL_TYPE,
				'post_page_sidebar_1'                              => 'blog_sidebar',
				'post_page_sidebar_2'                              => 'none',
				'post_page_sidebar_position'                       => 'right',
				'post_page_sidebar_style'                          => '01',
				'single_post_related_enable'                       => '1',
				'single_post_related_number'                       => 5,
				'single_post_feature_enable'                       => '1',
				'single_post_title_enable'                         => '1',
				'single_post_categories_enable'                    => '1',
				'single_post_tags_enable'                          => '1',
				'single_post_date_enable'                          => '1',
				'single_post_author_enable'                        => '1',
				'single_post_share_enable'                         => '1',
				'single_post_author_box_enable'                    => '1',
				'single_post_pagination_enable'                    => '1',
				'single_post_comment_enable'                       => '1',

				// Shop General.
				'shop_badges_style'                                => 'label',

				// Product Quantity.
				'product_quantity_type'                            => 'input',

				// Quick View.
				'shop_quick_view_enable'                           => '1',
				'shop_quick_view_product_description'              => '1',
				'shop_quick_view_product_badges'                   => '1',
				'shop_quick_view_product_meta'                     => '1',

				/**
				 * Shop Archive.
				 */
				'product_archive_title_bar_title'                  => esc_html__( 'Shop', 'minimog' ),
				'product_archive_page_sidebar_1'                   => 'shop_sidebar',
				'product_archive_page_sidebar_2'                   => 'none',
				'product_archive_page_sidebar_position'            => 'left',
				'product_archive_sidebar_style'                    => '01',
				'product_archive_single_sidebar_width'             => array(
					'width' => 25,
				),
				'product_archive_single_sidebar_offset'            => array(
					'width' => 30,
				),
				'product_archive_off_sidebar'                      => 'mobile',
				'product_archive_page_sidebar_2_off_canvas_enable' => 'mobile',
				'shop_archive_page_title'                          => '0',
				'shop_archive_filtering'                           => 'toolbar_right',
				'shop_archive_site_layout'                         => 'wide',
				'shop_archive_grid_style'                          => 'grid-01',
				'shop_archive_grid_caption_style'                  => '01',
				'shop_archive_grid_alternating'                    => '0',
				'shop_archive_number_item'                         => 12,
				'shop_archive_lg_columns'                          => 4,
				'shop_archive_lg_gutter'                           => 30,
				'shop_archive_md_columns'                          => 3,
				'shop_archive_md_gutter'                           => 20,
				'shop_archive_sm_columns'                          => 2,
				'shop_archive_sm_gutter'                           => 16,
				'shop_archive_toolbar_position'                    => 'above-content',
				'shop_archive_result_count'                        => '1',
				'shop_archive_sorting'                             => '1',
				'shop_archive_pagination_type'                     => 'load-more',
				'shop_archive_layout_switcher'                     => '1',
				'shop_archive_hover_image'                         => '1',
				'shop_archive_compare'                             => '1',
				'shop_archive_wishlist'                            => '1',
				'shop_archive_show_price'                          => '1',
				'shop_archive_show_variation'                      => '1',
				'shop_archive_show_category'                       => '0',
				'shop_archive_show_brand'                          => '0',
				'shop_archive_show_rating'                         => '0',
				'shop_archive_show_availability'                   => '0',
				'shop_archive_show_stock_bar'                      => '0',

				/**
				 * Shop category
				 */
				'shop_category_hover_effect'                       => 'zoom-in',
				'shop_category_show_count'                         => '1',
				'shop_category_show_min_price'                     => '0',
				'shop_category_title_bar_show_description'         => '0',

				'shop_sub_categories_position'   => 'above_sidebar',
				'shop_sub_categories_style'      => '05',
				'shop_sub_categories_layout'     => 'slider',
				'shop_sub_categories_lg_columns' => 4,
				'shop_sub_categories_lg_gutter'  => 30,
				'shop_sub_categories_md_columns' => 3,
				'shop_sub_categories_md_gutter'  => 20,
				'shop_sub_categories_sm_columns' => 2,
				'shop_sub_categories_sm_gutter'  => 16,

				'product_category_sub_categories_position'              => 'above_sidebar',
				'product_category_sub_categories_style'                 => '05',
				'product_category_sub_categories_layout'                => 'slider',
				'product_category_sub_categories_lg_columns'            => 4,
				'product_category_sub_categories_lg_gutter'             => 30,
				'product_category_sub_categories_md_columns'            => 3,
				'product_category_sub_categories_md_gutter'             => 20,
				'product_category_sub_categories_sm_columns'            => 2,
				'product_category_sub_categories_sm_gutter'             => 16,

				/**
				 * Shop Single.
				 */
				'product_single_title_bar_layout'                       => Minimog_Title_Bar::DEFAULT_MINIMAL_TYPE,
				'product_page_sidebar_1'                                => 'none',
				'product_page_sidebar_2'                                => 'none',
				'product_page_sidebar_position'                         => 'left',
				'product_page_single_sidebar_width'                     => array(
					'width' => 25,
				),
				'product_page_single_sidebar_offset'                    => array(
					'width' => 30,
				),
				'product_page_sidebar_style'                            => '01',
				'single_product_site_layout'                            => Minimog_Site_Layout::CONTAINER_NORMAL,
				'single_product_summary_layout'                         => Minimog_Site_Layout::CONTAINER_NORMAL,
				'single_product_images_style'                           => 'slider',
				'single_product_slider_vertical'                        => '1',
				'single_product_slider_thumbnails_mobile_disable'       => '1',
				'single_product_image_grid_alternating'                 => '0',
				'single_product_image_grid_to_slider_on_mobile'         => '1',
				'single_product_image_grid_lg_columns'                  => 2,
				'single_product_image_grid_lg_gutter'                   => 10,
				'single_product_image_grid_md_columns'                  => 3,
				'single_product_image_grid_md_gutter'                   => 10,
				'single_product_image_grid_sm_columns'                  => 2,
				'single_product_image_grid_sm_gutter'                   => 10,
				'single_product_images_wide'                            => 'normal',
				'single_product_images_offset'                          => '0',
				'single_product_summary_offset'                         => '40',
				'single_product_sticky_enable'                          => '1',
				'single_product_sticky_bar_enable'                      => '0',
				'single_product_tabs_style'                             => 'tabs',
				'single_product_review_style'                           => '01',
				'single_product_question_enable'                        => '1',
				'product_ask_question_role'                             => 'all',
				'product_reply_question_role'                           => 'all',
				'single_product_short_description_enable'               => '0',
				'single_product_total_sales_enable'                     => '0',
				'single_product_low_stock_enable'                       => '1',
				'single_product_live_view_visitors_enable'              => '1',
				'single_product_live_view_visitors_range'               => array(
					1 => 20,
					2 => 30,
				),
				'single_product_trust_badge_enable'                     => '1',
				'single_product_trust_badge_image'                      => array(
					'url' => MINIMOG_THEME_ASSETS_URI . '/woocommerce/product-trust-badge.png',
				),
				'single_product_meta_enable'                            => '0',
				'single_product_buy_now_enable'                         => '1',
				'single_product_categories_enable'                      => '1',
				'single_product_tags_enable'                            => '1',
				'single_product_brands_tab_enable'                      => '1',
				'single_product_compare_enable'                         => '1',
				'single_product_sharing_enable'                         => '1',
				'single_product_shipping_class_enable'                  => '1',
				'single_product_shipping_estimated_enable'              => '1',
				'single_product_shipping_n_returns_enable'              => '1',
				'single_product_up_sells_enable'                        => '1',
				'single_product_up_sells_position'                      => 'below_product_tabs',
				'single_product_related_enable'                         => '1',
				'single_product_related_position'                       => 'below_product_tabs',
				'product_related_number'                                => 5,
				'single_product_recent_viewed_enable'                   => '1',
				'single_product_recent_viewed_position'                 => 'below_product_tabs',
				'recent_viewed_products_per_page'                       => 5,
				'single_product_loop_style'                             => 'carousel-01',
				'single_product_loop_caption_style'                     => '01',
				'single_product_loop_lg_columns'                        => 4,
				'single_product_loop_md_columns'                        => 3,
				'single_product_loop_sm_columns'                        => 2,
				'single_product_loop_lg_gutter'                         => 30,
				'single_product_loop_md_gutter'                         => 20,
				'single_product_loop_sm_gutter'                         => 16,

				// Wishlist.
				'single_product_wishlist_enable'                        => '1',
				'wishlist_icon_type'                                    => 'star',

				// Cart Features.
				'shopping_cart_countdown_enable'                        => '1',
				'shopping_cart_free_shipping_bar_enable'                => '1',
				'shopping_cart_countdown_loop_enable'                   => '1',
				'shopping_cart_countdown_length'                        => 5,
				// Cart Drawer.
				'add_to_cart_behaviour'                                 => 'open_cart_drawer',
				'shopping_cart_drawer_view_cart_button_enable'          => '1',
				'shopping_cart_drawer_modal_customer_notes_enable'      => '1',
				'shopping_cart_drawer_modal_shipping_calculator_enable' => '1',
				'shopping_cart_drawer_modal_coupon_enable'              => '1',

				// Cart page.
				'shopping_cart_modal_customer_notes_enable'             => '1',

				// Checkout page.
				'checkout_page_modal_customer_notes_enable'             => '1',

				// Single Store.
				'single_store_site_layout'                              => Minimog_Site_Layout::CONTAINER_NORMAL,

				// Search popup
				'section_popup_search_number_results'                   => 7,

				/**
				 * Advanced.
				 */
				'scroll_top_enable'                                     => false,
				'image_lazy_load_enable'                                => true,
				'retina_display_enable'                                 => false,
				'smooth_scroll_enable'                                  => false,
				// Performance.
				'disable_emoji'                                         => true,
				'disable_embeds'                                        => true,

				// 404.
				'error404_page_header_overlay'                          => '1',
				'error404_page_header_background'                       => 'none',
				'error404_page_header_shadow'                           => 'none',
				'error404_page_background_body'                         => array(
					'background-color' => '#f5f1ed',
				),
				'error404_page_image'                                   => array(
					'url' => MINIMOG_THEME_IMAGE_URI . '/404-image.png',
				),
				'error404_page_title'                                   => esc_html__( 'Oops!', 'minimog' ),
				'error404_page_sub_title'                               => esc_html__( 'Page not found!', 'minimog' ),
				'error404_page_search_enable'                           => false,
				'error404_page_buttons_enable'                          => true,

				'social_sharing_item_enable'              => [
					'facebook' => true,
					'twitter'  => true,
					'linkedin' => true,
					'tumblr'   => true,
					'email'    => true,
				],

				// Popup login/register.
				'login_popup_enable'                      => true,
				'register_form_acceptance_text'           => 'Yes, I agree with {privacy} and {terms}',
				'login_redirect'                          => 'current',
				'page_for_terms_and_conditions'           => '0',

				// Promo Popup.
				'promo_popup_enable'                      => '1',
				'promo_popup_style'                       => '01',
				'promo_popup_type'                        => 'subscribe',
				'promo_popup_heading'                     => __( 'Don’t Want To Miss Anything?', 'minimog' ),
				'promo_popup_description'                 => __( 'Be the first to see new arrivals, exclusive ideals and much more', 'minimog' ),
				'promo_popup_image'                       => array(
					'url' => MINIMOG_THEME_IMAGE_URI . '/promo-popup-image.jpg',
				),
				'promo_popup_button_style'                => 'flat',
				'promo_popup_button_text'                 => __( 'Get It Now', 'minimog' ),
				'promo_popup_button_url'                  => '#',
				'promo_popup_trigger_on_load'             => '1',
				'promo_popup_trigger_on_scrolling'        => '0',
				'promo_popup_trigger_scrolling_direction' => 'down',
				'promo_popup_trigger_scrolling_offset'    => 50,
				'promo_popup_trigger_on_click'            => '0',
				'promo_popup_trigger_click_times'         => 1,
				'promo_popup_rule_by_times'               => '1',
				'promo_popup_rule_times_up_to'            => 1,
				'promo_popup_rule_show_by_page_views'     => '0',
				'promo_popup_rule_page_views_reach'       => 3,
				'promo_popup_rule_hide_by_logged_in'      => '0',

				// Cookie notice.
				'notice_cookie_enable'                    => '0',

				// Social Networks.
				'social_link_target'                      => '1',

				// Pre Loader.
				'pre_loader_enable'                       => '0',
				'pre_loader_style'                        => 'gif-image',
				'pre_loader_image'                        => array(
					'url' => MINIMOG_THEME_IMAGE_URI . '/main-preloader.gif',
				),
				'pre_loader_image_width'                  => array(
					'width' => 100,
					'units' => 'px',
				),
			];

			self::$default_settings = $default_settings;
		}

		public static function get_default_setting( $setting_name, $default = '' ) {
			return isset( self::$default_settings [ $setting_name ] ) ? self::$default_settings [ $setting_name ] : $default;
		}

		public static function get_all_font_variations() {
			return [
				'100' => esc_html__( 'Thin 100', 'minimog' ),
				'200' => esc_html__( 'Extra Light 200', 'minimog' ),
				'300' => esc_html__( 'Light 300', 'minimog' ),
				'400' => esc_html__( 'Regular 400', 'minimog' ),
				'500' => esc_html__( 'Medium 500', 'minimog' ),
				'600' => esc_html__( 'Semi-Bold 600', 'minimog' ),
				'700' => esc_html__( 'Bold 700', 'minimog' ),
				'800' => esc_html__( 'Extra Bold 800', 'minimog' ),
				'900' => esc_html__( 'Black 900', 'minimog' ),
			];
		}

		public function get_current_site_slug() {
			$site_slug = '';

			if ( is_multisite() && function_exists( 'get_blog_details' ) ) {
				$path = get_blog_details()->path;
				$path = ltrim( $path, '/' );
				$path = rtrim( $path, '/' );
				$path = explode( '/', $path );
				if ( ! empty( $path ) ) {
					$site_slug = $path[ count( $path ) - 1 ];
				}
			}

			return ! empty( $site_slug ) ? $site_slug : '';
		}

		/**
		 * Remove unused native sections and controls
		 *
		 * @param WP_Customize_Manager $wp_customize
		 */
		public function remove_customizer_sections( $wp_customize ) {
			$wp_customize->remove_section( 'nav' );
			$wp_customize->remove_section( 'colors' );
			$wp_customize->remove_section( 'background_image' );
			$wp_customize->remove_section( 'header_image' );

			$wp_customize->get_section( 'title_tagline' )->priority = '100';

			$wp_customize->remove_control( 'display_header_text' );
		}
	}

	Minimog_Redux::instance()->initialize();
}
