<?php
defined( 'ABSPATH' ) || exit;

/**
 * Custom filters that act independently of the theme templates
 */
if ( ! class_exists( 'Minimog_Actions_Filters' ) ) {
	class Minimog_Actions_Filters {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			/* Move post count inside the link */
			add_filter( 'wp_list_categories', array( $this, 'move_post_count_inside_link_category' ) );
			/* Move post count inside the link */
			add_filter( 'get_archives_link', array( $this, 'move_post_count_inside_link_archive' ) );

			// Change comment form fields order.
			add_filter( 'comment_form_fields', array( $this, 'move_comment_field_to_bottom' ) );

			add_filter( 'embed_oembed_html', array( $this, 'add_wrapper_for_video' ), 10, 3 );
			add_filter( 'video_embed_html', array( $this, 'add_wrapper_for_video' ) ); // Jetpack.

			add_filter( 'excerpt_length', array(
				$this,
				'custom_excerpt_length',
			), 999 ); // Change excerpt length is set to 55 words by default.

			// Adds custom classes to the array of body classes.
			add_filter( 'body_class', array( $this, 'body_classes' ) );

			if ( ! is_admin() ) {
				add_action( 'pre_get_posts', array( $this, 'alter_search_loop' ), 1 );
				add_filter( 'pre_get_posts', array( $this, 'search_filter' ) );
				add_filter( 'pre_get_posts', array( $this, 'empty_search_filter' ) );
			}

			add_filter( 'insightcore_bmw_nav_args', array( $this, 'add_extra_params_to_insightcore_bmw' ) );

			add_filter( 'user_contactmethods', [ $this, 'add_extra_user_info' ] );

			add_filter( 'insight_core_breadcrumb_default', [ $this, 'change_breadcrumb_text' ] );

			add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );
			add_action( 'wp', array( $this, 'cron_events' ) );
		}

		/**
		 * Override with text in theme.
		 *
		 * @param $args
		 *
		 * @return mixed
		 */
		function change_breadcrumb_text( $args ) {
			$args['home_label']   = esc_html__( 'Home', 'minimog' );
			$args['search_label'] = esc_html__( 'Search Result of &quot;%s&quot;', 'minimog' );
			$args['404_label']    = esc_html__( '404 Not Found', 'minimog' );

			return $args;
		}

		public function add_extra_user_info( $fields ) {
			$new_fields = array(
				array(
					'name'  => 'phone_number',
					'label' => esc_html__( 'Phone Number', 'minimog' ),
				),
				array(
					'name'  => 'email_address',
					'label' => esc_html__( 'Email Address', 'minimog' ),
				),
				array(
					'name'  => 'facebook',
					'label' => esc_html__( 'Facebook', 'minimog' ),
				),
				array(
					'name'  => 'twitter',
					'label' => esc_html__( 'Twitter', 'minimog' ),
				),
				array(
					'name'  => 'instagram',
					'label' => esc_html__( 'Instagram', 'minimog' ),
				),
				array(
					'name'  => 'linkedin',
					'label' => esc_html__( 'Linkedin', 'minimog' ),
				),
				array(
					'name'  => 'pinterest',
					'label' => esc_html__( 'Pinterest', 'minimog' ),
				),
				array(
					'name'  => 'youtube',
					'label' => esc_html__( 'Youtube', 'minimog' ),
				),
			);

			foreach ( $new_fields as $new_field ) {
				if ( ! isset( $fields[ $new_field['name'] ] ) ) {
					$fields[ $new_field['name'] ] = $new_field['label'];
				}
			}

			return $fields;
		}

		function add_extra_params_to_insightcore_bmw( $args ) {
			$args['link_before'] = '<div class="menu-item-wrap"><span class="menu-item-title">';
			$args['link_after']  = '</span></div>';

			return $args;
		}

		function move_post_count_inside_link_category( $links ) {
			// First remove span that added by woocommerce.
			$links = str_replace( '<span class="count">', '', $links );
			$links = str_replace( '</span>', '', $links );

			// Then add span again for both blog & shop.

			$links = str_replace( '</a> ', ' <span class="count">', $links );
			$links = str_replace( ')', ')</span></a>', $links );

			$links = preg_replace( '|\((\d+)\)|', '\\1', $links );

			return $links;
		}

		function move_post_count_inside_link_archive( $links ) {
			$links = str_replace( '</a>&nbsp;(', ' (', $links );
			$links = str_replace( ')', ')</a>', $links );

			$links = str_replace( '(', ' <span class="count">(', $links );
			$links = str_replace( ')', ')</span>', $links );

			$links = preg_replace( '|\((\d+)\)|', '\\1', $links );

			return $links;
		}

		function change_widget_tag_cloud_args( $args ) {
			$args['separator'] = ', ';

			return $args;
		}

		function move_comment_field_to_bottom( $fields ) {
			// Move comment field to bottom of fields.
			$comment_field = $fields['comment'];
			unset( $fields['comment'] );
			$fields['comment'] = $comment_field;

			// If comments cookies opt-in checkbox checked then move it below of comment field.
			if ( isset( $fields['cookies'] ) ) {
				$cookie_field = $fields['cookies'];
				unset( $fields['cookies'] );
				$fields['cookies'] = $cookie_field;
			}

			return $fields;
		}

		/**
		 * @param WP_Query $query Query instance.
		 */
		public function alter_search_loop( $query ) {
			if ( $query->is_main_query() && $query->is_search() ) {
				$number_results = Minimog::setting( 'search_page_number_results' );
				$query->set( 'posts_per_page', $number_results );
			}
		}

		/**
		 * @param WP_Query $query Query instance.
		 *
		 * @return WP_Query $query
		 *
		 * Apply filters to the search query.
		 * Determines if we only want to display posts/pages and changes the query accordingly
		 */
		public function search_filter( $query ) {
			if ( $query->is_main_query() && $query->is_search ) {
				$post_type = Minimog::setting( 'search_page_filter' );

				if ( ! empty( $post_type ) && 'all' !== $post_type ) {
					$query->set( 'post_type', $post_type );

					switch ( $post_type ) {
						case 'post':
							if ( ! empty( $_GET['category'] ) ) {
								$query->set( 'tax_query', array(
									array(
										'taxonomy' => 'category',
										'field'    => 'slug',
										'terms'    => array( sanitize_text_field( $_GET['category'] ) ),
									),
								) );
							}

							break;
						case 'product':
							if ( ! empty( $_GET['product_cat'] ) ) {
								$query->set( 'tax_query', array(
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'slug',
										'terms'    => array( sanitize_text_field( $_GET['product_cat'] ) ),
									),
								) );
							}
							break;
					}
				}
			}

			return $query;
		}

		/**
		 * Make wordpress respect the search template on an empty search
		 *
		 * @param \WP_Query $query
		 *
		 * @return \WP_Query $query
		 */
		public function empty_search_filter( $query ) {
			if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) && $query->is_main_query() ) {
				$query->is_search = true;
				$query->is_home   = false;
			}

			return $query;
		}

		public function custom_excerpt_length() {
			return 999;
		}

		/**
		 * Add responsive container to embeds
		 */
		public function add_wrapper_for_video( $html, $url ) {
			$array = array(
				'youtube.com',
				'wordpress.tv',
				'vimeo.com',
				'dailymotion.com',
				'hulu.com',
			);

			if ( Minimog_Helper::strposa( $url, $array ) ) {
				$html = '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
			}

			return $html;
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		public function body_classes( $classes ) {
			// Adds a class for mobile device.
			if ( Minimog::is_mobile() ) {
				$classes[] = 'mobile';
			}

			// Adds a class for tablet device.
			if ( Minimog::is_tablet() ) {
				$classes[] = 'tablet';
			}

			// Adds a class for handheld device.
			if ( Minimog::is_handheld() ) {
				$classes[] = 'handheld mobile-menu';
			}

			// Adds a class for desktop device.
			if ( Minimog::is_desktop() ) {
				$classes[] = 'desktop desktop-menu';
			}

			$classes[] = 'primary-nav-rendering';

			if ( ! is_home() && ( function_exists( 'elementor_location_exits' ) && elementor_location_exits( 'archive', true ) ) ) {
				$classes[] = 'elementor-archive-page';
			}

			if ( Minimog::setting( 'box_rounded' ) ) {
				$classes[] = 'minimog-rounded';
			}

			$header_sticky_enable = Minimog::setting( 'header_sticky_enable' );
			if ( intval( $header_sticky_enable ) ) {
				$classes[] = "header-sticky-enable";
			}

			$mobile_tabs_enable = Minimog::setting( 'mobile_tabs_enable' );
			if ( $mobile_tabs_enable ) {
				$classes[] = 'page-has-mobile-tabs';
			}

			$site_class = Minimog_Helper::get_post_meta( 'site_class', '' );
			if ( $site_class !== '' ) {
				$classes[] = $site_class;
			}

			$sidebar1       = Minimog_Global::instance()->get_sidebar_1();
			$sidebar_status = Minimog_Global::instance()->get_sidebar_status();

			if ( $sidebar_status === 'one' ) {
				$classes[] = 'page-has-sidebar page-one-sidebar';
			} elseif ( $sidebar_status === 'both' ) {
				$classes[] = 'page-has-sidebar page-both-sidebar';
			} else {
				$classes[] = 'page-has-no-sidebar';
			}

			if ( 'none' !== $sidebar1 ) {
				$sidebar1_canvas = apply_filters( 'minimog/page_sidebar/1/off_sidebar/enable', '0' );

				if ( '1' === $sidebar1_canvas ) {
					$classes[] = 'page-sidebar1-off';
				} elseif ( 'mobile' === $sidebar1_canvas ) {
					$classes[] = 'page-sidebar1-off-mobile';
				}

				$sidebar2 = Minimog_Global::instance()->get_sidebar_2();
				if ( 'none' !== $sidebar2 ) {
					$sidebar2_canvas = apply_filters( 'minimog/page_sidebar/2/off_sidebar/enable', '0' );

					if ( '1' === $sidebar2_canvas ) {
						$classes[] = 'page-sidebar2-off';
					} elseif ( 'mobile' === $sidebar2_canvas ) {
						$classes[] = 'page-sidebar2-off-mobile';
					}
				}
			}

			$page_block_style = Minimog::setting( 'page_blocks_style' );
			if ( ! empty( $page_block_style ) ) {
				$classes[] = "page-blocks-style-{$page_block_style}";
			}

			return $classes;
		}

		/**
		 * Add a new cron interval
		 *
		 * @param  array $schedules
		 *
		 * @return array
		 */
		public function add_cron_interval( $schedules ) {
			// add a 'monthly' interval
			$schedules['monthly'] = array(
				'interval' => 2505600, // 29 days.
				'display'  => esc_html__( 'Monthly', 'minimog' ),
			);

			return $schedules;
		}

		/**
		 * Schedule events
		 */
		public function cron_events() {
			if ( ! wp_next_scheduled( 'minimog_monthly_tasks' ) ) {
				wp_schedule_event( time(), 'monthly', 'minimog_monthly_tasks' );
			}
		}
	}

	Minimog_Actions_Filters::instance()->initialize();
}
