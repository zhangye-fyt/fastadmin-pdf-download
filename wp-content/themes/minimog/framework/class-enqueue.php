<?php
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue scripts and styles.
 */
if ( ! class_exists( 'Minimog_Enqueue' ) ) {
	class Minimog_Enqueue {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Set priority 4 to make it run before elementor register scripts.
			add_action( 'wp_enqueue_scripts', [ $this, 'register_swiper' ], 4 );

			//add_action( 'wp_enqueue_scripts', [ $this, 'polyfill_script' ], 1 );

			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
			add_action( 'elementor/frontend/before_register_scripts', [ $this, 'fix_out_date_smartmenus' ], 1 );

			/**
			 * Make it run after main style & components.
			 */
			add_action( 'wp_enqueue_scripts', [ $this, 'rtl_styles' ], 20 );

			// Disable all contact form 7 scripts.
			add_filter( 'wpcf7_load_js', '__return_false' );
			add_filter( 'wpcf7_load_css', '__return_false' );
		}

		/**
		 * Register swiper lib.
		 * Use on wp_enqueue_scripts action.
		 */
		public function register_swiper() {
			if ( wp_script_is( 'minimog-swiper-wrapper', 'registered' ) ) {
				return;
			}

			$min = $this->get_min_suffix();

			wp_register_style( 'swiper', MINIMOG_THEME_URI . '/assets/libs/swiper/css/swiper.min.css', null, '5.4.1' );
			wp_register_script( 'swiper', MINIMOG_THEME_URI . "/assets/libs/swiper/js/swiper{$min}.js", array(), '5.4.1', true );

			wp_register_script( 'minimog-swiper-wrapper', MINIMOG_THEME_URI . "/assets/js/swiper-wrapper{$min}.js", array(
				'jquery',
				'swiper',
			), MINIMOG_THEME_VERSION, true );

			$minimog_swiper_js = array(
				'prevText' => esc_html__( 'Prev', 'minimog' ),
				'nextText' => esc_html__( 'Next', 'minimog' ),
			);
			wp_localize_script( 'minimog-swiper-wrapper', '$minimogSwiper', $minimog_swiper_js );
		}

		public function register_grid_layout() {
			if ( wp_script_is( 'minimog-grid-layout', 'registered' ) ) {
				return;
			}

			$min = $this->get_min_suffix();

			$grid_layout_depends = [
				'jquery',
				'matchheight',
				'isotope-masonry',
				'isotope-packery',
			];

			if ( ! Minimog::setting( 'image_lazy_load_enable' ) ) {
				$grid_layout_depends[] = 'imagesloaded';
			}

			wp_register_script( 'isotope-masonry', MINIMOG_THEME_URI . "/assets/libs/isotope/js/isotope.pkgd{$min}.js", array( 'jquery' ), MINIMOG_THEME_VERSION, true );
			wp_register_script( 'isotope-packery', MINIMOG_THEME_URI . "/assets/libs/packery-mode/packery-mode.pkgd{$min}.js", array( 'jquery' ), MINIMOG_THEME_VERSION, true );

			wp_register_script( 'minimog-grid-layout', MINIMOG_THEME_ASSETS_URI . "/js/grid-layout{$min}.js", $grid_layout_depends, null, true );
		}

		/**
		 * Fix Elementor load out-date version 1.0.1
		 */
		public function fix_out_date_smartmenus() {
			wp_register_script( 'smartmenus', MINIMOG_THEME_URI . '/assets/libs/smartmenus/jquery.smartmenus.min.js', array( 'jquery' ), '1.1.1', true );
		}

		/**
		 * Fix elementor broken on old browsers
		 * Safari < 12.1
		 * Firefox < 55
		 * Chrome < 51
		 */
		public function polyfill_script() {
			wp_enqueue_script( 'intersection-observer', MINIMOG_THEME_URI . '/assets/libs/polyfill/intersection-observer.min.js', null, null, true );
		}

		/**
		 * Enqueue scripts & styles for frond-end.
		 *
		 * @access public
		 */
		public function frontend_scripts() {
			$min = $this->get_min_suffix();
			$rtl = $this->get_rtl_suffix();

			wp_dequeue_style( 'classic-theme-styles' );

			/*
			 * Begin register scripts & styles to be enqueued later.
			 */
			/**
			 * We need remove font awesome soon for better performance.
			 */
			wp_register_style( 'font-awesome-pro', MINIMOG_THEME_URI . '/assets/fonts/awesome/css/all.min.css', null, '5.15.4' );
			wp_enqueue_style( 'font-awesome-pro' );

			wp_register_style( 'justifiedGallery', MINIMOG_THEME_URI . '/assets/libs/justifiedGallery/css/justifiedGallery.min.css', null, '3.7.0' );
			wp_register_script( 'justifiedGallery', MINIMOG_THEME_URI . '/assets/libs/justifiedGallery/js/jquery.justifiedGallery.min.js', array( 'jquery' ), '3.7.0', true );

			wp_register_style( 'lightgallery', MINIMOG_THEME_URI . '/assets/libs/lightGallery/css/lightgallery.min.css', null, '1.6.12' );
			wp_register_script( 'lightgallery', MINIMOG_THEME_URI . "/assets/libs/lightGallery/js/lightgallery-all{$min}.js", array(
				'jquery',
			), '1.6.12', true );

			wp_register_script( 'matchheight', MINIMOG_THEME_URI . '/assets/libs/matchHeight/jquery.matchHeight-min.js', array( 'jquery' ), MINIMOG_THEME_VERSION, true );
			wp_register_script( 'jquery-smooth-scroll', MINIMOG_THEME_URI . '/assets/libs/smooth-scroll/jquery.smooth-scroll.min.js', array( 'jquery' ), MINIMOG_THEME_VERSION, true );
			wp_register_script( 'smartmenus', MINIMOG_THEME_URI . '/assets/libs/smartmenus/jquery.smartmenus.min.js', array( 'jquery' ), '1.1.1', true );
			wp_register_script( 'validate', MINIMOG_THEME_URI . '/assets/libs/validate/jquery.validate.min.js', [ 'jquery' ], '1.19.5', true );
			wp_register_script( 'smooth-scroll', MINIMOG_THEME_URI . '/assets/libs/smooth-scroll-for-web/SmoothScroll.min.js', array(
				'jquery',
			), '1.4.9', true );

			// Fix Wordpress old version not registered this script.
			if ( ! wp_script_is( 'imagesloaded', 'registered' ) ) {
				wp_register_script( 'imagesloaded', MINIMOG_THEME_URI . '/assets/libs/imagesloaded/imagesloaded.min.js', array( 'jquery' ), null, true );
			}

			$this->register_swiper();

			wp_register_script( 'hc-sticky', MINIMOG_THEME_URI . "/assets/js/hc-sticky{$min}.js", array(
				'jquery',
			), MINIMOG_THEME_VERSION, true );

			wp_register_script( 'picturefill', MINIMOG_THEME_URI . '/assets/libs/picturefill/picturefill.min.js', array( 'jquery' ), null, true );

			wp_register_script( 'mousewheel', MINIMOG_THEME_URI . '/assets/libs/mousewheel/jquery.mousewheel.min.js', array( 'jquery' ), MINIMOG_THEME_VERSION, true );

			wp_register_script( 'readmore', MINIMOG_THEME_URI . "/assets/libs/readmore/readmore{$min}.js", array( 'jquery' ), '3.0.0', true );

			wp_register_script( 'countdown', MINIMOG_ELEMENTOR_URI . '/assets/libs/jquery.countdown/js/jquery.countdown.min.js', array( 'jquery' ), MINIMOG_THEME_VERSION, true );

			wp_register_script( 'spritespin', MINIMOG_THEME_URI . "/assets/libs/spritespin/spritespin{$min}.js", array( 'jquery' ), MINIMOG_THEME_VERSION, true );

			$google_api_key = $this->get_google_api_key();
			if ( ! empty( $google_api_key ) ) {
				wp_register_script( 'minimog-gmap3', MINIMOG_THEME_URI . '/assets/libs/gmap3/gmap3.min.js', array( 'jquery' ), MINIMOG_THEME_VERSION, true );
				wp_register_script( 'minimog-maps', MINIMOG_PROTOCOL . '://maps.google.com/maps/api/js?key=' . $google_api_key . '&amp;language=en' );
			}

			wp_register_script( 'laziestloader', MINIMOG_THEME_URI . "/assets/js/laziesloader{$min}.js", [ 'jquery' ], '0.7.2', true );

			if ( Minimog::setting( 'image_lazy_load_enable' ) ) {
				wp_enqueue_script( 'laziestloader' );
			}

			$this->register_grid_layout();

			wp_deregister_script( 'perfect-scrollbar' );
			wp_deregister_style( 'perfect-scrollbar' );

			wp_register_style( 'perfect-scrollbar', MINIMOG_THEME_URI . '/assets/libs/perfect-scrollbar/perfect-scrollbar.min.css' );
			wp_register_script( 'perfect-scrollbar', MINIMOG_THEME_URI . '/assets/js/perfect-scrollbar.min.js', array( 'jquery' ), MINIMOG_THEME_VERSION, true );

			wp_register_script( 'minimog-common-archive', MINIMOG_THEME_URI . "/assets/js/common-archive{$min}.js", [
				'jquery',
				'perfect-scrollbar',
			], MINIMOG_THEME_VERSION, true );

			wp_register_script( 'minimog-quantity-button', MINIMOG_THEME_URI . "/assets/js/woo/quantity-button{$min}.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );

			wp_register_script( 'minimog-tab-panel', MINIMOG_THEME_URI . "/assets/js/tab-panel{$min}.js", [
				'jquery',
				'perfect-scrollbar',
			], MINIMOG_THEME_VERSION, true );
			wp_register_script( 'minimog-countdown-timer', MINIMOG_THEME_URI . "/assets/js/countdown-timer{$min}.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );
			wp_register_script( 'minimog-accordion', MINIMOG_THEME_URI . "/assets/js/accordion{$min}.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );
			wp_register_script( 'minimog-nice-select', MINIMOG_THEME_URI . "/assets/js/nice-select{$min}.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );
			wp_register_script( 'minimog-modal', MINIMOG_THEME_URI . "/assets/js/modal{$min}.js", [ 'jquery' ], MINIMOG_THEME_VERSION, true );
			wp_register_script( 'minimog-one-page', MINIMOG_THEME_URI . "/assets/js/one-page{$min}.js", [
				'jquery',
				'jquery-smooth-scroll',
				'minimog-script',
			], MINIMOG_THEME_VERSION, true );

			/*
			 * End register scripts
			 */

			wp_enqueue_style( 'swiper' );

			/*
			 * Enqueue the theme's style.css.
			 * This is recommended because we can add inline styles there
			 * and some plugins use it to do exactly that.
			 */
			wp_enqueue_style( 'minimog-style', get_template_directory_uri() . "/style{$rtl}{$min}.css" );

			if ( Minimog::setting( 'smooth_scroll_enable' ) ) {
				wp_enqueue_script( 'smooth-scroll' );
			}

			/**
			 * Use theme version fixed jquery deprecated.
			 */
			wp_deregister_script( 'elementor-waypoints' );

			// Use waypoints lib edited by Elementor to avoid duplicate the script.
			if ( ! wp_script_is( 'elementor-waypoints', 'registered' ) ) {
				wp_register_script( 'elementor-waypoints', MINIMOG_THEME_URI . '/assets/libs/elementor-waypoints/jquery.waypoints.min.js', array( 'jquery' ), null, true );
			}

			wp_enqueue_script( 'elementor-waypoints' );

			wp_enqueue_script( 'minimog-swiper-wrapper' );

			if ( is_search() ) {
				wp_enqueue_script( 'minimog-grid-layout' );
			}

			wp_enqueue_script( 'smartmenus' );

			wp_enqueue_style( 'perfect-scrollbar' );
			wp_enqueue_script( 'perfect-scrollbar' );

			$top_bar_components = Minimog_Top_Bar::instance()->get_active_components();
			if ( in_array( 'countdown', $top_bar_components, true ) ) {
				wp_enqueue_script( 'countdown' );
			}

			/*
			 * The comment-reply script.
			 */
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				$post_type = get_post_type();

				switch ( $post_type ) {
					case 'post':
						if ( Minimog::setting( 'single_post_comment_enable' ) === '1' ) {
							wp_enqueue_script( 'comment-reply' );
						}
						break;
					default:
						wp_enqueue_script( 'comment-reply' );
						break;
				}
			}

			/*
			 * Enqueue main JS
			 */
			wp_enqueue_script( 'minimog-script', MINIMOG_THEME_URI . "/assets/js/main{$min}.js", array(
				'jquery',
				'minimog-grid-layout', // Popup Search.
			), MINIMOG_THEME_VERSION, true );

			$js_variables = array(
				'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
				'minimog_ajax_url'          => Minimog_AJAX::get_endpoint( '%%endpoint%%' ),
				'nonce'                     => wp_create_nonce( 'minimog-security' ),
				'header_sticky_enable'      => Minimog::setting( 'header_sticky_enable' ),
				'scroll_top_enable'         => Minimog::setting( 'scroll_top_enable' ),
				'light_gallery_auto_play'   => Minimog::setting( 'light_gallery_auto_play' ),
				'light_gallery_download'    => Minimog::setting( 'light_gallery_download' ),
				'light_gallery_full_screen' => Minimog::setting( 'light_gallery_full_screen' ),
				'light_gallery_zoom'        => Minimog::setting( 'light_gallery_zoom' ),
				'light_gallery_thumbnail'   => Minimog::setting( 'light_gallery_thumbnail' ),
				'light_gallery_share'       => Minimog::setting( 'light_gallery_share' ),
				'mobile_menu_breakpoint'    => Minimog::setting( 'mobile_menu_breakpoint' ),
				'isSingle'                  => is_singular(),
				'postID'                    => is_singular() ? get_the_ID() : 0,
				'postType'                  => get_post_type(),
				'search'                    => [
					'delay' => Minimog::setting( 'popup_search_ajax_auto_delay' ),
				],
				'i18l'                      => [
					'noMatchesFound' => esc_html( _x( 'No matches found', 'enhanced select', 'minimog' ) ),
					'all'            => esc_html__( 'All %s', 'minimog' ),
				],
			);
			wp_localize_script( 'minimog-script', '$minimog', $js_variables );

			$one_page_enable = Minimog_Helper::get_post_meta( 'menu_one_page', '' );
			if ( '1' === $one_page_enable ) {
				wp_enqueue_script( 'minimog-one-page' );
			}

			/**
			 * Custom JS
			 */
			if ( Minimog::setting( 'custom_js_enable' ) ) {
				wp_add_inline_script( 'minimog-script', html_entity_decode( Minimog::setting( 'custom_js' ) ) );
			}
		}

		public function rtl_styles() {
			$min = $this->get_min_suffix();

			wp_register_style( 'minimog-style-rtl-custom', MINIMOG_THEME_URI . "/style-rtl-custom$min.css", [ 'minimog-style' ], MINIMOG_THEME_VERSION );

			if ( is_rtl() ) {
				wp_enqueue_style( 'minimog-style-rtl-custom' );
			}
		}

		public function get_google_api_key() {
			if ( defined( 'MINIMOG_GOOGLE_MAP_API_KEY' ) && ! empty( MINIMOG_GOOGLE_MAP_API_KEY ) ) {
				return MINIMOG_GOOGLE_MAP_API_KEY;
			}

			$google_api_key = Minimog::setting( 'google_api_key' );

			if ( ! empty( $google_api_key ) ) {
				return $google_api_key;
			}

			return false;
		}

		/**
		 * @return string
		 */
		public function get_min_suffix() {
			return defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ? '' : '.min';
		}

		public function get_rtl_suffix() {
			return is_rtl() ? '-rtl' : '';
		}

		public function update_handle_src( $handle, $new_src ) {
			global $wp_scripts;

			if ( false != $wp_scripts->queue ) {
				foreach ( $wp_scripts->queue as $script ) {
					if ( $handle === $script && isset( $wp_scripts->registered[ $script ] ) ) {
						$wp_scripts->registered[ $script ]->src = $new_src;

						return;
					}
				}
			}
		}
	}

	Minimog_Enqueue::instance()->initialize();
}
