<?php
defined( 'ABSPATH' ) || exit;

/**
 * Initialize Global Variables
 */
if ( ! class_exists( 'Minimog_Global' ) ) {
	class Minimog_Global {

		protected static $instance          = null;
		protected static $slider            = '';
		protected static $slider_position   = 'below';
		protected static $top_bar_type      = '01';
		protected static $header_type       = '01';
		protected static $header_overlay    = '0';
		protected static $header_skin       = 'dark';
		protected static $header_background = '';
		protected static $header_shadow     = '';
		protected static $title_bar_type    = 'standard-01';
		protected static $sidebar_1         = '';
		protected static $sidebar_2         = '';
		protected static $sidebar_position  = '';
		protected static $sidebar_status    = 'none';
		protected static $popup_search      = false;
		protected static $fly_cart          = false;
		protected static $footer            = '';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/**
			 * Use hook wp instead of init because we need post meta setup.
			 * then we must wait for post loaded.
			 * But this hook can't load global variables in Ajax request.
			 */
			add_action( 'wp', array( $this, 'init_global_variable' ) );

			/**
			 * Setup global variables.
			 * Used priority 13 to wait override settings setup.
			 *
			 * @see Minimog_Customize::setup_override_settings()
			 */
			add_action( 'wp', array( $this, 'setup_global_variables' ), 13 );
		}

		public function init_global_variable() {
			global $minimog_page_options;
			if ( is_singular( 'post' ) ) {
				$minimog_page_options = unserialize( get_post_meta( get_the_ID(), 'insight_post_options', true ) );
			} elseif ( is_singular( 'page' ) ) {
				$minimog_page_options = unserialize( get_post_meta( get_the_ID(), 'insight_page_options', true ) );
			} elseif ( is_singular( 'product' ) ) {
				$minimog_page_options = unserialize( get_post_meta( get_the_ID(), 'insight_product_options', true ) );
			}
			if ( function_exists( 'is_shop' ) && is_shop() ) {
				// Get page id of shop.
				$page_id              = wc_get_page_id( 'shop' );
				$minimog_page_options = unserialize( get_post_meta( $page_id, 'insight_page_options', true ) );
			}
		}

		public function setup_global_variables() {
			$this->set_slider();
			$this->set_top_bar_type();
			$this->set_header_options();
			$this->set_title_bar_type();
			$this->set_sidebars();
			$this->set_popup_search();
			$this->set_fly_cart();
			$this->set_footer();
		}

		function set_slider() {
			$alias    = Minimog_Helper::get_post_meta( 'revolution_slider', '' );
			$position = Minimog_Helper::get_post_meta( 'slider_position', '' );

			self::$slider          = $alias;
			self::$slider_position = $position;
		}

		function get_slider_alias() {
			return self::$slider;
		}

		function get_slider_position() {
			return self::$slider_position;
		}

		function set_top_bar_type() {
			$type = Minimog_Helper::get_post_meta( 'top_bar_type', '' );

			if ( '' === $type ) {
				$type = Minimog::setting( 'global_top_bar' );
			}

			$type = apply_filters( 'minimog/top_bar/type', $type );

			self::$top_bar_type = $type;
		}

		function get_top_bar_type() {
			return self::$top_bar_type;
		}

		function set_header_options() {
			$header_type       = Minimog_Helper::get_post_meta( 'header_type', '' );
			$header_overlay    = Minimog_Helper::get_post_meta( 'header_overlay', '' );
			$header_skin       = Minimog_Helper::get_post_meta( 'header_skin', '' );
			$header_shadow     = Minimog_Helper::get_post_meta( 'header_shadow', '' );
			$header_background = Minimog_Helper::get_post_meta( 'header_background', '' );

			if ( Minimog_Woo::instance()->is_woocommerce_page_without_product() ) {

				if ( $header_type === '' ) {
					$header_type = Minimog::setting( 'product_archive_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Minimog::setting( 'product_archive_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Minimog::setting( 'product_archive_header_skin' );
				}

			} elseif ( is_archive() ) {

				if ( $header_type === '' ) {
					$header_type = Minimog::setting( 'blog_archive_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Minimog::setting( 'blog_archive_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Minimog::setting( 'blog_archive_header_skin' );
				}

			} elseif ( is_singular( 'post' ) ) {

				if ( $header_type === '' ) {
					$header_type = Minimog::setting( 'blog_single_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Minimog::setting( 'blog_single_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Minimog::setting( 'blog_single_header_skin' );
				}
			} elseif ( is_singular( 'product' ) ) {

				if ( $header_type === '' ) {
					$header_type = Minimog::setting( 'product_single_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Minimog::setting( 'product_single_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Minimog::setting( 'product_single_header_overlay' );
				}

			} elseif ( is_singular( 'page' ) ) {

				if ( $header_type === '' ) {
					$header_type = Minimog::setting( 'page_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Minimog::setting( 'page_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Minimog::setting( 'page_header_skin' );
				}

			} elseif ( is_404() ) {
				if ( $header_type === '' ) {
					$header_type = Minimog::setting( 'error404_page_header_type' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Minimog::setting( 'error404_page_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Minimog::setting( 'error404_page_header_skin' );
				}

				if ( $header_background === '' ) {
					$header_background = Minimog::setting( 'error404_page_header_background' );
				}

				if ( $header_shadow === '' ) {
					$header_shadow = Minimog::setting( 'error404_page_header_shadow' );
				}

			} else {

				if ( $header_type === '' ) {
					$header_type = Minimog::setting( 'global_header' );
				}

				if ( $header_overlay === '' ) {
					$header_overlay = Minimog::setting( 'global_header_overlay' );
				}

				if ( $header_skin === '' ) {
					$header_skin = Minimog::setting( 'global_header_skin' );
				}
			}

			if ( '' === $header_type ) {
				$header_type = Minimog::setting( 'global_header' );
			}

			if ( '' === $header_overlay ) {
				$header_overlay = Minimog::setting( 'global_header_overlay' );
			}

			if ( '' === $header_skin ) {
				$header_skin = Minimog::setting( 'global_header_skin' );
			}

			$header_type       = apply_filters( 'minimog/header/type', $header_type );
			$header_overlay    = apply_filters( 'minimog/header/overlay', $header_overlay );
			$header_skin       = apply_filters( 'minimog/header/skin', $header_skin );
			$header_background = apply_filters( 'minimog/header/background', $header_background );
			$header_shadow     = apply_filters( 'minimog/header/shadow', $header_shadow );

			self::$header_type       = $header_type;
			self::$header_overlay    = $header_overlay;
			self::$header_skin       = $header_skin;
			self::$header_background = $header_background;
			self::$header_shadow     = $header_shadow;
		}

		function get_header_type() {
			return self::$header_type;
		}

		function get_header_overlay() {
			return self::$header_overlay;
		}

		/**
		 * @return string dark|light
		 */
		function get_header_skin() {
			return self::$header_skin;
		}

		function get_header_background() {
			return self::$header_background;
		}

		function get_header_shadow() {
			return self::$header_shadow;
		}

		function set_title_bar_type() {
			$type = Minimog_Helper::get_post_meta( 'page_title_bar_layout', '' );

			if ( $type === '' ) {
				if ( Minimog_Woo::instance()->is_woocommerce_page_without_product() ) {
					$type = Minimog::setting( 'product_archive_title_bar_layout' );
				} elseif ( is_archive() || is_home() ) {
					$type = Minimog::setting( 'blog_archive_title_bar_layout' );
				} elseif ( is_singular( 'post' ) ) {
					$type = Minimog::setting( 'blog_single_title_bar_layout' );
				} elseif ( is_singular( 'page' ) ) {
					$type = Minimog::setting( 'page_title_bar_layout' );
				} elseif ( is_singular( 'product' ) ) {
					$type = Minimog::setting( 'product_single_title_bar_layout' );
				} else {
					$type = Minimog::setting( 'title_bar_layout' );
				}

				if ( $type === '' ) {
					$type = Minimog::setting( 'title_bar_layout' );
				}
			}

			$type = apply_filters( 'minimog/title_bar/type', $type );

			self::$title_bar_type = $type;
		}

		function get_title_bar_type() {
			return self::$title_bar_type;
		}

		function set_sidebars() {
			$sidebar_position = 'right';

			if ( Minimog_Woo::instance()->is_product_archive() ) {
				$page_sidebar1    = Minimog::setting( 'product_archive_page_sidebar_1' );
				$page_sidebar2    = Minimog::setting( 'product_archive_page_sidebar_2' );
				$sidebar_position = Minimog::setting( 'product_archive_page_sidebar_position' );
			} elseif ( is_archive() || is_home() ) {
				$page_sidebar1    = Minimog::setting( 'blog_archive_page_sidebar_1' );
				$page_sidebar2    = Minimog::setting( 'blog_archive_page_sidebar_2' );
				$sidebar_position = Minimog::setting( 'blog_archive_page_sidebar_position' );
			} elseif ( is_singular() ) {
				$post_type = get_post_type();

				// Get values from page options.
				$page_sidebar1    = Minimog_Helper::get_post_meta( 'page_sidebar_1', 'default' );
				$page_sidebar2    = Minimog_Helper::get_post_meta( 'page_sidebar_2', 'default' );
				$sidebar_position = Minimog_Helper::get_post_meta( 'page_sidebar_position', 'default' );

				switch ( $post_type ) {
					case 'post' :
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Minimog::setting( 'post_page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Minimog::setting( 'post_page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Minimog::setting( 'post_page_sidebar_position' );
						}
						break;
					case 'product' :
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Minimog::setting( 'product_page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Minimog::setting( 'product_page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Minimog::setting( 'product_page_sidebar_position' );
						}
						break;

					default:
						if ( $page_sidebar1 === 'default' ) {
							$page_sidebar1 = Minimog::setting( 'page_sidebar_1' );
						}

						if ( $page_sidebar2 === 'default' ) {
							$page_sidebar2 = Minimog::setting( 'page_sidebar_2' );
						}

						if ( $sidebar_position === 'default' ) {
							$sidebar_position = Minimog::setting( 'page_sidebar_position' );
						}

						break;
				}
			}

			if ( empty( $page_sidebar1 ) || ! is_active_sidebar( $page_sidebar1 ) ) {
				$page_sidebar1 = 'none';
			}

			if ( empty( $page_sidebar2 ) || ! is_active_sidebar( $page_sidebar2 ) ) {
				$page_sidebar2 = 'none';
			}

			$page_sidebar1    = apply_filters( 'minimog/page_sidebar/1', $page_sidebar1 );
			$page_sidebar2    = apply_filters( 'minimog/page_sidebar/2', $page_sidebar2 );
			$sidebar_position = apply_filters( 'minimog/page_sidebar/position', $sidebar_position );

			self::$sidebar_1        = $page_sidebar1;
			self::$sidebar_2        = $page_sidebar2;
			self::$sidebar_position = $sidebar_position;

			if ( $page_sidebar1 !== 'none' || $page_sidebar2 !== 'none' ) {
				self::$sidebar_status = 'one';
			}

			if ( $page_sidebar1 !== 'none' && $page_sidebar2 !== 'none' ) {
				self::$sidebar_status = 'both';
			}
		}

		function get_sidebar_1() {
			return self::$sidebar_1;
		}

		function get_sidebar_2() {
			return self::$sidebar_2;
		}

		/**
		 * @return string left|right
		 */
		function get_sidebar_position() {
			return self::$sidebar_position;
		}

		/**
		 * @return string one|both|none
		 */
		function get_sidebar_status() {
			return self::$sidebar_status;
		}

		function set_popup_search() {
			$header_type = $this->get_header_type();
			$type        = Minimog::setting( "header_style_{$header_type}_search_enable" );

			if ( '0' !== $type ) {
				self::$popup_search = true;
			}
		}

		function get_popup_search() {
			return self::$popup_search;
		}

		function set_fly_cart() {
			$header_type = $this->get_header_type();
			$type        = Minimog::setting( "header_style_{$header_type}_cart_enable" );

			if ( '0' !== $type ) {
				self::$fly_cart = true;
			}
		}

		function get_fly_cart() {
			return self::$fly_cart;
		}

		function set_footer() {
			$footer = Minimog_Helper::get_post_meta( 'footer_enable', '' );

			$footer = apply_filters( 'minimog/footer/type', $footer );

			self::$footer = $footer;
		}

		function get_footer() {
			return self::$footer;
		}
	}

	Minimog_Global::instance()->initialize();
}
