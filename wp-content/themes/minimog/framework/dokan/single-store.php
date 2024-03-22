<?php

namespace Minimog\Dokan;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Single_Store' ) ) {
	class Single_Store {

		const SIDEBAR_NAME        = 'store';
		const STORE_BANNER_WIDTH  = 1340;
		const STORE_BANNER_HEIGHT = 390;

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'minimog/title_bar/type', [ $this, 'title_bar_type' ] );

			add_filter( 'minimog/page_sidebar/single_width', [ $this, 'set_sidebar_single_width' ] );
			add_filter( 'minimog/page_sidebar/single_offset', [ $this, 'set_sidebar_single_offset' ] );
			add_filter( 'minimog/page_sidebar/class', [
				$this,
				'add_sidebar_css_class',
			] ); // Different style for sidebar.
			add_filter( 'minimog/page_sidebar/1', [ $this, 'set_sidebar_primary' ] );
			add_filter( 'minimog/page_sidebar/position', [ $this, 'set_sidebar_position' ] );
			add_action( 'minimog/page_sidebar/after_content', [ $this, 'add_default_sidebar_widgets' ] );

			/**
			 * @see \WeDevs\Dokan\Product\Hooks::store_products_orderby()
			 */
			minimog_remove_filters_for_anonymous_class( 'dokan_store_profile_frame_after', 'WeDevs\Dokan\Product\Hooks', 'store_products_orderby' );

			add_filter( 'dokan_store_banner_default_width', [ $this, 'update_banner_width' ] );
			add_filter( 'dokan_store_banner_default_height', [ $this, 'update_banner_height' ] );
			add_filter( 'dokan_admin_localize_script', [ $this, 'update_banner_size' ] );

			add_filter( 'dokan_profile_social_fields', [ $this, 'update_store_social_fields' ] );

			add_action( 'pre_get_posts', [ $this, 'fix_main_query_condition' ], 1 );
		}

		/**
		 * @param \WP_Query $query
		 */
		public function fix_main_query_condition( $query ) {
			if ( $query->is_main_query() && dokan_is_store_page() ) {
				$query->is_home = false;
			}
		}

		public function update_banner_width() {
			return self::STORE_BANNER_WIDTH;
		}

		public function update_banner_height() {
			return self::STORE_BANNER_HEIGHT;
		}

		/**
		 * Update Store Banner size when add vendor in admin
		 *
		 * @see \WeDevs\Dokan\Assets::get_admin_localized_scripts()
		 *
		 * @param $settings
		 *
		 * @return mixed
		 */
		public function update_banner_size( $settings ) {
			if ( isset( $settings['store_banner_dimension'] ) ) {
				if ( isset( $settings['store_banner_dimension']['width'] ) ) {
					$settings['store_banner_dimension']['width'] = self::STORE_BANNER_WIDTH;
				}

				if ( isset( $settings['store_banner_dimension']['height'] ) ) {
					$settings['store_banner_dimension']['height'] = self::STORE_BANNER_HEIGHT;
				}
			}

			return $settings;
		}

		public function update_store_social_fields( $socials ) {
			// Change font awesome icon square to normal.
			foreach ( $socials as $social => $setting ) {
				switch ( $social ) {
					case 'fb' :
						$socials[ $social ]['icon'] = 'facebook-f';
						break;
					case 'twitter' :
						$socials[ $social ]['icon'] = 'twitter';
						break;
					case 'pinterest' :
						$socials[ $social ]['icon'] = 'pinterest-p';
						break;
					case 'linkedin' :
						$socials[ $social ]['icon'] = 'linkedin-in';
						break;
					case 'youtube' :
						$socials[ $social ]['icon'] = 'youtube';
						break;
				}
			}

			return $socials;
		}

		public function title_bar_type( $type ) {
			if ( dokan_is_store_page() ) {
				return \Minimog_Title_Bar::DEFAULT_MINIMAL_TYPE;
			}

			return $type;
		}

		public function set_sidebar_primary( $sidebar ) {
			if ( dokan_is_store_page() ) {
				return self::SIDEBAR_NAME;
			}

			return $sidebar;
		}

		public function set_sidebar_position( $position ) {
			if ( dokan_is_store_page() ) {
				$new_position = get_theme_mod( 'store_layout', 'left' );

				return $new_position;
			}

			return $position;
		}

		public function set_sidebar_single_width( $width ) {
			if ( dokan_is_store_page() ) {
				$new_width = \Minimog::setting( 'store_page_single_sidebar_width' );

				if ( isset( $new_width['width'] ) && '' !== $new_width['width'] ) {
					return $new_width['width'];
				}
			}

			return $width;
		}

		public function set_sidebar_single_offset( $offset ) {
			if ( dokan_is_store_page() ) {
				$new_offset = \Minimog::setting( 'store_page_single_sidebar_offset' );

				if ( isset( $new_offset['width'] ) && '' !== $new_offset['width'] ) {
					/**
					 * Redux - Unit is included in dimensions type
					 * return $new_offset['width'] . 'px';
					 */
					return $new_offset['width'];
				}
			}

			return $offset;
		}

		public function add_sidebar_css_class( $classes ) {
			if ( dokan_is_store_page() ) {
				$sidebar_style = \Minimog::setting( 'store_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$classes[] = 'style-' . $sidebar_style;
				}
			}

			return $classes;
		}

		/**
		 * @see store-sidebar.php
		 */
		public function add_default_sidebar_widgets() {
			if ( ! dokan_is_store_page() ) {
				return;
			}

			if ( ! dynamic_sidebar( self::SIDEBAR_NAME ) ) {
				$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
				$store_info   = $store_user->get_shop_info();
				$map_location = $store_user->get_location();

				dokan_store_category_widget();

				if ( ! empty( $map_location ) ) {
					dokan_store_location_widget();
				}

				dokan_store_time_widget();
				dokan_store_contact_widget();
			}
		}
	}

	Single_Store::instance()->initialize();
}
