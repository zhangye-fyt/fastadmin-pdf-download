<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Top_Bar' ) ) {

	class Minimog_Top_Bar {
		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {

		}

		/**
		 * @return array List top bar types include id & name.
		 */
		public function get_type() {
			return array(
				'01' => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
			);
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_list( $default_option = false, $default_text = '' ) {
			$options = array(
				'none' => esc_html__( 'Hide', 'minimog' ),
			);

			$options += $this->get_type();

			if ( $default_option ) {
				$default_text = ! empty( $default_text ) ? $default_text : esc_html__( 'Default', 'minimog' );

				$options = array( '' => $default_text ) + $options;
			}

			return $options;
		}

		public function get_support_components() {
			$list = [
				'widget'            => esc_html__( 'Widget', 'minimog' ),
				'text'              => esc_html__( 'Text', 'minimog' ),
				'language_switcher' => esc_html__( 'Language Switcher', 'minimog' ),
				'info_list'         => esc_html__( 'Info List', 'minimog' ),
				'user_link'         => esc_html__( 'User Link', 'minimog' ),
				'social_links'      => esc_html__( 'Social Links', 'minimog' ),
				'marque_list'       => esc_html__( 'Marque List', 'minimog' ),
				//'countdown'         => esc_html__( 'Countdown Timer', 'minimog' ),
				'currency_switcher' => esc_html__( 'Currency Switcher', 'minimog' ),
			];

			$list = apply_filters( 'minimog/top_bar/supported_components', $list );

			return $list;
		}

		public function get_active_components() {
			$type = Minimog_Global::instance()->get_top_bar_type();

			$layout = Minimog::setting( "top_bar_style_{$type}_layout" );

			$active_components = [];

			$left_components   = Minimog::setting( "top_bar_style_{$type}_left_components" );
			$center_components = Minimog::setting( "top_bar_style_{$type}_center_components" );
			$right_components  = Minimog::setting( "top_bar_style_{$type}_right_components" );
			$has_left_column   = $has_center_column = $has_right_column = false;

			switch ( $layout ) {
				case '1l':
					$has_left_column = true;
					break;
				case '1c':
					$has_center_column = true;
					break;
				case '1r':
					$has_right_column = true;
					break;
				case '2':
					$has_left_column  = true;
					$has_right_column = true;
					break;
				case '3':
					$has_left_column   = true;
					$has_center_column = true;
					$has_right_column  = true;
					break;
			}

			if ( $has_left_column ) {
				foreach ( $left_components as $component => $is_active ) {
					if ( ! empty( $is_active ) ) {
						$active_components [] = $component;
					}
				}
			}

			if ( $has_center_column ) {
				foreach ( $center_components as $component => $is_active ) {
					if ( ! empty( $is_active ) ) {
						$active_components [] = $component;
					}
				}
			}

			if ( $has_right_column ) {
				foreach ( $right_components as $component => $is_active ) {
					if ( ! empty( $is_active ) ) {
						$active_components [] = $component;
					}
				}
			}

			return $active_components;
		}

		/**
		 * Add classes to the top barr.
		 *
		 * @var string $class Custom class.
		 */
		public function get_wrapper_class( $class = '' ) {
			$classes = array( 'page-top-bar' );

			$type = Minimog_Global::instance()->get_top_bar_type();

			$classes[] = "top-bar-{$type}";

			$layout = Minimog::setting( "top_bar_style_{$type}_layout" );

			$classes[] = "top-bar-layout-{$layout}";

			$visibility = Minimog::setting( "top_bar_style_{$type}_visibility" );

			switch ( $visibility ) {
				case 'hide_on_mobile';
					$classes[] = 'hide-on-mobile';
					break;
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

			$classes = apply_filters( 'minimog/top_bar/class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function get_container_class( $class = '' ) {
			$classes = [];

			$type          = Minimog_Global::instance()->get_top_bar_type();
			$content_width = Minimog::setting( "top_bar_style_{$type}_content_width" );

			if ( is_singular() ) {
				$custom = Minimog_Helper::get_post_meta( 'top_bar_content_width', '' );

				$content_width = ! empty( $custom ) ? $custom : $content_width;
			}

			$classes[] = Minimog_Site_Layout::instance()->get_container_class( $content_width );

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = apply_filters( 'minimog/top_bar/container_class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function render() {
			$type = Minimog_Global::instance()->get_top_bar_type();

			if ( 'none' !== $type ) {
				minimog_load_template( 'top-bar/top-bar', $type );
			}
		}

		public function print_components( $position = 'left' ) {
			$type       = Minimog_Global::instance()->get_top_bar_type();
			$components = Minimog::setting( "top_bar_style_{$type}_{$position}_components" );

			if ( empty( $components ) ) {
				return;
			}

			foreach ( $components as $component => $is_active ) {
				if ( empty( $is_active ) ) {
					continue;
				}

				switch ( $component ) {
					case 'text' :
						$this->print_text();
						break;
					case 'widget' :
						$this->print_widgets();
						break;
					case 'language_switcher' :
						$this->print_language_switcher();
						break;
					case 'info_list' :
						$this->print_info_list();
						break;
					case 'marque_list':
						$this->print_marque_list();
						break;
					case 'user_link' :
						$this->print_user_link();
						break;
					case 'social_links' :
						$this->print_social_links();
						break;
					case 'countdown' :
						$this->print_countdown_timer();
						break;
					case 'currency_switcher' :
						$this->print_currency_switcher();
						break;
					default:
						do_action( 'minimog/top_bar/print_components_' . $component );
						break;
				}
			}
		}

		public function print_text() {
			$text       = Minimog::setting( 'top_bar_text' );
			$text_style = '';

			if ( is_singular() ) {
				$custom_text = Minimog_Helper::get_post_meta( 'top_bar_text', '' );

				$text = '' !== $custom_text ? $custom_text : $text;

				$custom_text_style = Minimog_Helper::get_post_meta( 'top_bar_text_style', '' );
				$text_style        = '' !== $custom_text_style ? $custom_text_style : $text_style;
			}

			minimog_load_template( 'top-bar/components/text', null, $args = [
				'text'  => $text,
				'style' => $text_style,
			] );
		}

		public function print_language_switcher() {
			minimog_load_template( 'top-bar/components/language-switcher' );
		}

		public function print_user_link() {
			minimog_load_template( 'top-bar/components/user-links' );
		}

		public function print_social_links() {
			minimog_load_template( 'top-bar/components/socials' );
		}

		public function print_countdown_timer() {
			$countdown_type = Minimog::setting( 'top_bar_countdown_type' );
			$datetime       = '';

			if ( Minimog_Helper::is_demo_site() ) {
				$datetime = Minimog_Helper::get_sample_countdown_date();
			} else {
				switch ( $countdown_type ) {
					case 'due_date':
						$due_date = Minimog::setting( 'top_bar_countdown_due_date' );
						if ( ! empty( $due_date ) ) {
							// Handle timezone ( we need to set GMT time )
							$gmt      = get_gmt_from_date( $due_date . ' 00:00:00' );
							$datetime = date( 'm/d/Y H:i:s', strtotime( $gmt ) );
						}
						break;
					case 'daily':
						$now      = strtotime( current_time( 'm/d/Y H:i:s' ) );
						$endOfDay = strtotime( "tomorrow", $now ) - 1;

						$datetime = date( 'm/d/Y H:i:s', $endOfDay );
						break;
				}
			}

			if ( empty( $datetime ) ) {
				return;
			}

			$args = [
				'datetime'    => $datetime,
				'text_before' => Minimog::setting( 'top_bar_countdown_prefix_text' ),
				'button_url'  => Minimog::setting( 'top_bar_countdown_button_url' ),
				'button_text' => Minimog::setting( 'top_bar_countdown_button_text' ),
			];

			minimog_load_template( 'top-bar/components/countdown-timer', null, $args );
		}

		public function print_currency_switcher() {
			echo apply_filters( 'minimog/top_bar/components/currency_switcher/output', '' );
		}

		public function print_widgets() {
			minimog_load_template( 'top-bar/components/widgets' );
		}

		public function print_info_list() {
			$info_list = Minimog_Helper::parse_redux_repeater_field_values( Minimog::setting( 'info_list' ) );

			if ( ! empty( $info_list ) ) {
				minimog_load_template( 'top-bar/components/info-list', null, $args = [ 'info_list' => $info_list ] );
			}
		}

		public function print_marque_list() {
			$marque_list = Minimog_Helper::parse_redux_repeater_field_values( Minimog::setting( 'top_bar_marque_list' ) );

			if ( ! empty( $marque_list ) ) {
				minimog_load_template( 'top-bar/components/marque-list', null, $args = [ 'marque_list' => $marque_list ] );
			}
		}
	}

	Minimog_Top_Bar::instance()->initialize();
}
