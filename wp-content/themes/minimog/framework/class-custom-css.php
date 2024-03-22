<?php
defined( 'ABSPATH' ) || exit;

/**
 * Enqueue custom styles.
 */
if ( ! class_exists( 'Minimog_Custom_Css' ) ) {
	class Minimog_Custom_Css {

		protected static $instance = null;

		const TRANSIENT_ROOT_CSS = 'minimog_css_root';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'custom_inline_css' ] );
			add_filter( 'minimog/options/transients_to_clear', [ $this, 'add_transient_clear' ] );
		}

		public function custom_inline_css() {
			$this->frontend_root_css();
			$this->extra_css();
			$this->custom_code_css();
		}

		public function add_transient_clear( $transients ) {
			global $wpdb;

			$sql   = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE %s";
			$query = $wpdb->prepare( $sql, '%' . $wpdb->esc_like( '_transient_' . self::TRANSIENT_ROOT_CSS ) . '%' );

			$results = $wpdb->get_results( $query );
			if ( ! empty( $results ) ) {
				// We need remove _transient_ from option name.
				$prefix        = '_transient_';
				$prefix_length = strlen( $prefix );
				foreach ( $results as $record ) {
					if ( substr( $record->option_name, 0, $prefix_length ) == $prefix ) {
						$transient_name = substr( $record->option_name, $prefix_length );
						$transients[]   = $transient_name;
					}
				}
			}

			return $transients;
		}

		public function get_root_css() {
			$primary_color     = Minimog::setting( 'primary_color' );
			$primary_color_rgb = Minimog_Color::hex2rgb_string( $primary_color );
			$secondary_color   = Minimog::setting( 'secondary_color' );

			$text_color             = Minimog::setting( 'body_color' );
			$text_bit_lighten_color = '#7e7e7e';
			$text_lighten_color     = Minimog::setting( 'body_lighten_color' );
			$heading_color          = Minimog::setting( 'heading_color' );
			$link_color             = ! empty( Minimog::setting( 'link_color' ) ) ? Minimog::setting( 'link_color' ) : $heading_color;
			$link_hover_color       = ! empty( Minimog::setting( 'link_hover_color' ) ) ? Minimog::setting( 'link_hover_color' ) : $primary_color;
			$box_white_border       = '#ededed';
			$box_white_bg           = '#fff';
			$box_grey_bg            = '#f8f8f8';
			$box_light_grey_bg      = '#f9f9fb';
			$box_separator          = '#eee';
			$box_border             = '#eee';
			$box_border_lighten     = '#ededed';
			$sub_menu_background    = Minimog::setting( 'navigation_dropdown_bg_color', '#fff' );
			$sub_menu_background    = ! empty( $sub_menu_background ) ? $sub_menu_background : '#fff';
			$sub_menu_border        = '#ededed';

			$form_text             = Minimog::setting( 'form_text_color' );
			$form_border           = Minimog::setting( 'form_border_color' );
			$form_background       = Minimog::setting( 'form_background_color' );
			$form_focus_text       = Minimog::setting( 'form_focus_text_color' );
			$form_focus_border     = Minimog::setting( 'form_focus_border_color' );
			$form_focus_background = Minimog::setting( 'form_focus_background_color' );

			$form_box_shadow       = Minimog::setting( 'form_box_shadow' );
			$form_focus_box_shadow = Minimog::setting( 'form_focus_box_shadow' );
			$form_box_shadow       = empty( $form_box_shadow ) ? 'none' : $form_box_shadow;
			$form_focus_box_shadow = empty( $form_focus_box_shadow ) ? 'none' : $form_focus_box_shadow;

			$button_text             = Minimog::setting( 'button_text_color' );
			$button_border           = Minimog::setting( 'button_border_color' );
			$button_background       = Minimog::setting( 'button_background_color' );
			$button_hover_text       = Minimog::setting( 'button_hover_text_color' );
			$button_hover_border     = Minimog::setting( 'button_hover_border_color' );
			$button_hover_background = Minimog::setting( 'button_hover_background_color' );

			$button2_text             = Minimog::setting( 'button2_text_color' );
			$button2_border           = Minimog::setting( 'button2_border_color' );
			$button2_background       = Minimog::setting( 'button2_background_color' );
			$button2_hover_text       = Minimog::setting( 'button2_hover_text_color' );
			$button2_hover_border     = Minimog::setting( 'button2_hover_border_color' );
			$button2_hover_background = Minimog::setting( 'button2_hover_background_color' );

			$body_font        = Minimog::setting( 'typography_body' );
			$body_font_family = empty( $body_font['font-family'] ) ? 'sans-serif' : $body_font['font-family'];
			$body_font_size   = empty( $body_font['font-size'] ) ? 16 : $body_font['font-size'];
			$body_line_height = empty( $body_font['line-height'] ) ? 28 : $body_font['line-height'];
			$body_font_weight = isset( $body_font['font-weight'] ) ? $body_font['font-weight'] : 400;

			$heading_font           = Minimog::setting( 'typography_heading' );
			$heading_font_family    = empty( $heading_font['font-family'] ) ? 'inherit' : $heading_font['font-family'];
			$heading_font_weight    = isset( $heading_font['font-weight'] ) ? $heading_font['font-weight'] : 400;
			$heading_text_transform = isset( $heading_font['text-transform'] ) ? $heading_font['text-transform'] : 'none';
			$heading_letter_spacing = isset( $heading_font['letter-spacing'] ) && '' !== $heading_font['letter-spacing'] ? $heading_font['letter-spacing'] : 'none';
			$heading_font_weight2   = Minimog::setting( 'typography_heading_weight_2' );

			$heading2_font        = Minimog::setting( 'typography_heading2' );
			$heading2_font_family = empty( $heading2_font['font-family'] ) ? 'inherit' : $heading2_font['font-family'];
			$heading2_font_weight = empty( $heading2_font['font-weight'] ) ? $body_font_weight : $heading2_font['font-weight'];
			$heading2_font_weight = 'regular' === $heading2_font_weight ? 400 : $heading2_font_weight; // Fix regular is not valid weight.

			$form_font_inherit = Minimog::setting( 'form_typography_inherit' );
			if ( '1' === $form_font_inherit ) {
				$form_font_custom = Minimog::setting( 'form_typography_custom' );
				$form_font_weight = Minimog::setting( 'form_typography_custom_weight' );
				$form_font_size   = empty( $form_font_custom['font-size'] ) ? $body_font_size : $form_font_custom['font-size'];
				$form_font_weight = empty( $form_font_weight ) ? $body_font_weight : $form_font_weight;
				$form_font_family = $body_font_family;
			} else {
				$form_font        = Minimog::setting( 'form_typography' );
				$form_font_family = empty( $form_font['font-family'] ) ? $body_font_family : $form_font['font-family'];
				$form_font_size   = empty( $form_font['font-size'] ) ? $body_font_size : $form_font['font-size'];
				$form_font_weight = empty( $form_font['font-weight'] ) ? $body_font_weight : $form_font['font-weight'];
			}

			$button_font_inherit = Minimog::setting( 'button_typography_inherit' );
			if ( '1' === $button_font_inherit ) {
				$button_font_custom    = Minimog::setting( 'button_typography_custom' );
				$button_font_weight    = Minimog::setting( 'button_typography_custom_weight' );
				$button_font_family    = $body_font_family;
				$button_font_weight    = empty( $button_font_weight ) ? $body_font_weight : $button_font_weight;
				$button_font_size      = empty( $button_font_custom['font-size'] ) ? $body_font_size : $button_font_custom['font-size'];
				$button_text_transform = empty( $button_font_custom['text-transform'] ) ? 'none' : $button_font_custom['text-transform'];
				$button_letter_spacing = empty( $button_font_custom['letter-spacing'] ) ? '0' : $button_font_custom['letter-spacing'];
			} else {
				$button_font           = Minimog::setting( 'button_typography' );
				$button_font_family    = empty( $button_font['font-family'] ) ? $body_font_family : $button_font['font-family'];
				$button_font_size      = empty( $button_font['font-size'] ) ? $body_font_size : $button_font['font-size'];
				$button_font_weight    = empty( $button_font['font-weight'] ) ? $body_font_weight : $button_font['font-weight'];
				$button_text_transform = empty( $button_font['text-transform'] ) ? 'none' : $button_font['text-transform'];
				$button_letter_spacing = empty( $button_font['letter-spacing'] ) ? '0' : $button_font['letter-spacing'];
			}

			$small_rounded = $normal_rounded = $semi_rounded = $large_rounded = 0;

			if ( Minimog::setting( 'box_rounded' ) ) {
				$small_rounded  = Minimog::setting( 'small_rounded' );
				$normal_rounded = Minimog::setting( 'normal_rounded' );
				$semi_rounded   = Minimog::setting( 'semi_rounded' );
				$large_rounded  = Minimog::setting( 'large_rounded' );

				$small_rounded  = '' !== $small_rounded ? "{$small_rounded}px" : '3px';
				$normal_rounded = '' !== $normal_rounded ? "{$normal_rounded}px" : '5px';
				$semi_rounded   = '' !== $semi_rounded ? "{$semi_rounded}px" : '8px';
				$large_rounded  = '' !== $large_rounded ? "{$large_rounded}px" : '10px';
			}

			$form_input_border_thickness = Minimog::setting( 'form_input_normal_border_thickness' );
			$form_input_normal_rounded   = intval( Minimog::setting( 'form_input_normal_rounded' ) );
			$form_input_small_rounded    = intval( Minimog::setting( 'form_input_small_rounded' ) );
			$form_textarea_rounded       = intval( Minimog::setting( 'form_textarea_rounded' ) );
			$button_rounded              = intval( Minimog::setting( 'button_rounded' ) );
			$button_small_rounded        = intval( Minimog::setting( 'button_small_rounded' ) );
			$button_large_rounded        = intval( Minimog::setting( 'button_large_rounded' ) );

			$form_input_border_thickness = $form_input_border_thickness > 0 ? $form_input_border_thickness . 'px' : 0;
			$form_input_normal_rounded   = $form_input_normal_rounded > 0 ? $form_input_normal_rounded . 'px' : 0;
			$form_input_small_rounded    = $form_input_small_rounded > 0 ? $form_input_small_rounded . 'px' : 0;
			$form_textarea_rounded       = $form_textarea_rounded > 0 ? $form_textarea_rounded . 'px' : 0;
			$button_rounded              = $button_rounded > 0 ? $button_rounded . 'px' : 0;
			$button_small_rounded        = $button_small_rounded > 0 ? $button_small_rounded . 'px' : 0;
			$button_large_rounded        = $button_large_rounded > 0 ? $button_large_rounded . 'px' : 0;

			$css = ":root {
				--minimog-typography-body-font-family: {$body_font_family};
				--minimog-typography-body-font-size: {$body_font_size};
				--minimog-typography-body-font-weight: {$body_font_weight};
				--minimog-typography-body-line-height: {$body_line_height};
				--minimog-typography-headings-font-family: {$heading_font_family};
				--minimog-typography-headings-font-weight: {$heading_font_weight};
				--minimog-typography-headings-font-weight-secondary: {$heading_font_weight2};
				--minimog-typography-headings-text-transform: {$heading_text_transform};
				--minimog-typography-headings-letter-spacing: {$heading_letter_spacing};
				--minimog-typography-headings-2-font-family: {$heading2_font_family};
				--minimog-typography-headings-2-font-weight: {$heading2_font_weight};
				--minimog-typography-button-font-family: {$button_font_family};
				--minimog-typography-button-font-size: {$button_font_size};
				--minimog-typography-button-font-weight: {$button_font_weight};
				--minimog-typography-button-text-transform: {$button_text_transform};
				--minimog-typography-button-letter-spacing: {$button_letter_spacing};
				--minimog-typography-form-font-family: {$form_font_family};
				--minimog-typography-form-font-size: {$form_font_size};
				--minimog-typography-form-font-weight: {$form_font_weight};
				--minimog-color-primary: {$primary_color};
				--minimog-color-primary-rgb: {$primary_color_rgb};
				--minimog-color-secondary: {$secondary_color};
				--minimog-color-text: {$text_color};
				--minimog-color-text-bit-lighten: {$text_bit_lighten_color};
				--minimog-color-text-lighten: {$text_lighten_color};
				--minimog-color-heading: {$heading_color};
				--minimog-color-link: {$link_color};
				--minimog-color-link-hover: {$link_hover_color};
				--minimog-color-box-white-background: {$box_white_bg};
				--minimog-color-box-white-border: {$box_white_border};
				--minimog-color-box-grey-background: {$box_grey_bg};
				--minimog-color-box-light-grey-background: {$box_light_grey_bg};
				--minimog-color-box-fill-separator: {$box_separator};
				--minimog-color-box-border: {$box_border};
				--minimog-color-box-border-lighten: {$box_border_lighten};
				--minimog-color-button-text: {$button_text};
				--minimog-color-button-border: {$button_border};
				--minimog-color-button-background: {$button_background};
				--minimog-color-button-hover-text: {$button_hover_text};
				--minimog-color-button-hover-border: {$button_hover_border};
				--minimog-color-button-hover-background: {$button_hover_background};
				--minimog-color-button2-text: {$button2_text};
				--minimog-color-button2-border: {$button2_border};
				--minimog-color-button2-background: {$button2_background};
				--minimog-color-button2-hover-text: {$button2_hover_text};
				--minimog-color-button2-hover-border: {$button2_hover_border};
				--minimog-color-button2-hover-background: {$button2_hover_background};
				--minimog-color-form-text: {$form_text};
				--minimog-color-form-border: {$form_border};
				--minimog-color-form-background: {$form_background};
				--minimog-color-form-shadow: {$form_box_shadow};
				--minimog-color-form-focus-text: {$form_focus_text};
				--minimog-color-form-focus-border: {$form_focus_border};
				--minimog-color-form-focus-background: {$form_focus_background};
				--minimog-color-form-focus-shadow: {$form_focus_box_shadow};
				--minimog-color-sub-menu-border: {$sub_menu_border};
				--minimog-color-sub-menu-background: {$sub_menu_background};
				--minimog-small-rounded: {$small_rounded};
				--minimog-normal-rounded: {$normal_rounded};
				--minimog-semi-rounded: {$semi_rounded};
				--minimog-large-rounded: {$large_rounded};
				--minimog-form-input-normal-border-thickness: {$form_input_border_thickness};
				--minimog-form-input-normal-rounded: {$form_input_normal_rounded};
				--minimog-form-input-small-rounded: {$form_input_small_rounded};
				--minimog-form-textarea-rounded: {$form_textarea_rounded};
				--minimog-button-rounded: {$button_rounded};
				--minimog-button-small-rounded: {$button_small_rounded};
				--minimog-button-large-rounded: {$button_large_rounded};
			}";

			return $css;
		}

		public function frontend_root_css() {
			$transient_key = self::TRANSIENT_ROOT_CSS;
			$transient_key .= ! empty( $_GET['site_settings_preset'] ) ? '-' . $_GET['site_settings_preset'] : '';
			$transient_key .= ! empty( $_GET['settings_preset'] ) ? '-' . $_GET['settings_preset'] : '';

			// Skip cache in dev mode.
			$css = ! Minimog_Helper::is_dev_mode() ? get_transient( $transient_key ) : false;

			if ( false === $css ) {
				$css = $this->get_root_css();
				set_transient( $transient_key, $css, 7 * DAY_IN_SECONDS );
			}

			wp_add_inline_style( 'minimog-style', html_entity_decode( $css, ENT_QUOTES ) );
		}

		/**
		 * Responsive styles.
		 *
		 * @access public
		 */
		public function extra_css() {
			$extra_style = '';

			$logo_width        = Minimog::setting( 'logo_width' );
			$tablet_logo_width = Minimog::setting( 'tablet_logo_width' );
			$mobile_logo_width = Minimog::setting( 'mobile_logo_width' );
			$sticky_logo_width = Minimog::setting( 'sticky_logo_width' );

			$extra_style .= "body { 
				--minimog-branding-size: {$logo_width}px;
				--minimog-tablet-branding-size: {$tablet_logo_width}px;
				--minimog-mobile-branding-size: {$mobile_logo_width}px;
				--minimog-sticky-branding-size: {$sticky_logo_width}px;
			}";

			$tmp = '';

			$site_background_color = Minimog_Helper::get_post_meta( 'site_background_color', '' );
			if ( $site_background_color !== '' ) {
				$tmp .= "background-color: $site_background_color !important;";
			}

			$site_background_image = Minimog_Helper::get_post_meta( 'site_background_image', '' );
			if ( $site_background_image !== '' ) {
				$site_background_repeat = Minimog_Helper::get_post_meta( 'site_background_repeat', '' );
				$tmp                    .= "background-image: url( $site_background_image ) !important; background-repeat: $site_background_repeat !important;";
			}

			$site_background_position = Minimog_Helper::get_post_meta( 'site_background_position', '' );
			if ( $site_background_position !== '' ) {
				$tmp .= "background-position: $site_background_position !important;";
			}

			$site_background_size = Minimog_Helper::get_post_meta( 'site_background_size', '' );
			if ( $site_background_size !== '' ) {
				$tmp .= "background-size: $site_background_size !important;";
			}

			$site_background_attachment = Minimog_Helper::get_post_meta( 'site_background_attachment', '' );
			if ( $site_background_attachment !== '' ) {
				$tmp .= "background-attachment: $site_background_attachment !important;";
			}

			if ( $tmp !== '' ) {
				$extra_style .= "body { $tmp; }";
			}

			$extra_style .= $this->top_bar_css();
			$extra_style .= $this->header_css();
			$extra_style .= $this->sidebar_css();
			$extra_style .= $this->title_bar_css();

			wp_add_inline_style( 'minimog-style', html_entity_decode( $extra_style, ENT_QUOTES ) );
		}

		public function top_bar_css() {
			$css         = '';
			$top_bar_css = '';

			$background = Minimog_Helper::get_post_meta( 'top_bar_background_color', '' );
			if ( ! empty( $background ) ) {
				$top_bar_css .= "background-color: {$background};";
			}

			$text_color = Minimog_Helper::get_post_meta( 'top_bar_text_color', '' );
			if ( ! empty( $text_color ) ) {
				$top_bar_css .= "color: {$text_color};";
			}

			$link_color = Minimog_Helper::get_post_meta( 'top_bar_link_color', '' );
			if ( ! empty( $link_color ) ) {
				$css .= "#page-top-bar a { color: $link_color; }";
			}

			$link_hover_color = Minimog_Helper::get_post_meta( 'top_bar_link_hover_color', '' );
			if ( ! empty( $link_hover_color ) ) {
				$css .= "#page-top-bar a:hover { color: $link_hover_color; }";
			}

			$button_text             = Minimog_Helper::get_post_meta( 'top_bar_button_text_color', '' );
			$button_background       = Minimog_Helper::get_post_meta( 'top_bar_button_background_color', '' );
			$button_border           = Minimog_Helper::get_post_meta( 'top_bar_button_border_color', '' );
			$button_hover_text       = Minimog_Helper::get_post_meta( 'top_bar_button_hover_text_color', '' );
			$button_hover_background = Minimog_Helper::get_post_meta( 'top_bar_button_hover_background_color', '' );
			$button_hover_border     = Minimog_Helper::get_post_meta( 'top_bar_button_hover_border_color', '' );
			$button_css              = '';
			$button_hover_css        = '';

			if ( ! empty( $button_text ) ) {
				$button_css .= "color: {$button_text} !important;";
			}

			if ( ! empty( $button_background ) ) {
				$button_css .= "background: {$button_background};";
			}

			if ( ! empty( $button_border ) ) {
				$button_css .= "border-color: {$button_border};";
			}

			if ( ! empty( $button_hover_text ) ) {
				$button_hover_css .= "color: {$button_hover_text} !important;";
			}

			if ( ! empty( $button_hover_background ) ) {
				$button_hover_css .= "background: {$button_hover_background};";
			}

			if ( ! empty( $button_hover_border ) ) {
				$button_hover_css .= "border-color: {$button_hover_border};";
			}

			if ( ! empty( $button_css ) ) {
				$css .= "#page-top-bar .top-bar-countdown-timer .countdown-button,
				        #page-top-bar .top-bar-tag{ $button_css }";
			}

			if ( ! empty( $button_hover_css ) ) {
				$css .= "#page-top-bar .top-bar-countdown-timer .countdown-button:hover { $button_hover_css }";
			}

			if ( ! empty( $top_bar_css ) ) {
				$css .= "#page-top-bar { $top_bar_css }";
			}

			return $css;
		}

		public function header_css() {
			$shadow           = Minimog_Global::instance()->get_header_shadow();
			$background       = Minimog_Global::instance()->get_header_background();
			$header_dimension = Minimog::setting( 'header_height' );
			$css              = $header_inner_css = '';

			if ( isset( $header_dimension['height'], $header_dimension['units'] ) && '' !== $header_dimension['height'] ) {
				$value = intval( $header_dimension['height'] );
				$css   .= ".page-header:not(.header-pinned) { --header-height: {$value}{$header_dimension['units']} }";
			}

			if ( 'none' === $shadow ) {
				$header_inner_css .= 'box-shadow: none !important;';
			}

			if ( 'none' === $background ) {
				$header_inner_css .= 'background: none !important;';
			}

			if ( ! empty( $header_inner_css ) ) {
				$css .= ".page-header:not(.header-pinned) #page-header-inner { {$header_inner_css} }";
			}

			$category_menu_rounded = Minimog::setting( 'header_category_menu_link_rounded' );
			if ( '' !== $category_menu_rounded ) {
				$css .= ".header-categories-nav { --link-rounded: {$category_menu_rounded}px; }";
			}

			$category_menu_shadow = Minimog::setting( 'header_category_menu_link_hover_shadow' );
			if ( ! empty( $category_menu_shadow ) ) {
				$css .= ".header-categories-nav { --link-hover-shadow: 0 10px 20px rgba(0, 0, 0, 0.12); }";
			}

			return $css;
		}

		public function title_bar_css() {
			$css = $title_bar_tmp = $overlay_tmp = $minimal_01_css = '';

			$minimal_01_text_align = Minimog::setting( 'title_bar_minimal_01_text_align' );
			$minimal_01_css        .= "--breadcrumb-align: {$minimal_01_text_align};";
			$minimal_01_dimension  = Minimog::setting( 'title_bar_minimal_01_breadcrumb_min_height' );

			if ( isset( $minimal_01_dimension['height'] ) && isset( $minimal_01_dimension['units'] ) ) {
				$value          = intval( $minimal_01_dimension['height'] );
				$minimal_01_css .= "--breadcrumb-height: {$value}{$minimal_01_dimension['units']}";
			}

			$title_bar_css     = '';
			$heading_font_size = Minimog::setting( 'title_bar_heading_font_size' );
			if ( '' !== $heading_font_size ) {
				$title_bar_css .= "--heading-font-size: {$heading_font_size}px;";
			}

			if ( '' !== $title_bar_css ) {
				$css .= ".page-title-bar { $title_bar_css }";
			}

			if ( '' !== $minimal_01_css ) {
				$css .= ".page-title-bar-minimal-01{ {$minimal_01_css} }";
			}

			$bg_color   = Minimog_Helper::get_post_meta( 'page_title_bar_background_color', '' );
			$bg_image   = Minimog_Helper::get_post_meta( 'page_title_bar_background', '' );
			$bg_overlay = Minimog_Helper::get_post_meta( 'page_title_bar_background_overlay', '' );

			if ( $bg_color !== '' ) {
				$title_bar_tmp .= "background-color: {$bg_color}!important;";
			}

			if ( '' !== $bg_image ) {
				$title_bar_tmp .= "background-image: url({$bg_image})!important;";
			}

			if ( '' !== $bg_overlay ) {
				$overlay_tmp .= "background-color: {$bg_overlay}!important;";
			}

			if ( '' !== $title_bar_tmp ) {
				$css .= ".page-title-bar-bg{ {$title_bar_tmp} }";
			}

			if ( '' !== $overlay_tmp ) {
				$css .= ".page-title-bar-bg:before{ {$overlay_tmp} }";
			}

			$bottom_spacing = Minimog_Helper::get_post_meta( 'page_title_bar_bottom_spacing', '' );
			if ( '' !== $bottom_spacing ) {
				$css .= "#page-title-bar{ margin-bottom: {$bottom_spacing}; }";
			}

			return $css;
		}

		public function sidebar_css() {
			$css = '';

			$page_sidebar1 = Minimog_Global::instance()->get_sidebar_1();
			$page_sidebar2 = Minimog_Global::instance()->get_sidebar_2();

			if ( 'none' !== $page_sidebar1 ) {
				$sidebars_breakpoint = 991;

				$sidebars_below = Minimog::setting( 'sidebars_below_content_mobile' );

				$sidebar1_is_drawer = '1' === apply_filters( 'minimog/page_sidebar/1/off_sidebar/enable', '0' );
				$content_width      = 100;

				if ( 'none' !== $page_sidebar2 ) {
					$dual_sidebar_width = Minimog::setting( 'dual_sidebar_width' );
					$dual_sidebar_width = is_array( $dual_sidebar_width ) && isset( $dual_sidebar_width['width'] ) ? $dual_sidebar_width['width'] : 25;
					$sidebar_width      = intval( apply_filters( 'minimog/page_sidebar/dual_width', $dual_sidebar_width ) );

					$dual_sidebar_offset = Minimog::setting( 'dual_sidebar_offset' );
					$dual_sidebar_offset = is_array( $dual_sidebar_offset ) && isset( $dual_sidebar_offset['width'] ) ? $dual_sidebar_offset['width'] : 0;
					$sidebar_offset      = apply_filters( 'minimog/page_sidebar/dual_offset', $dual_sidebar_offset );

					if ( ! $sidebar1_is_drawer ) {
						$content_width -= $sidebar_width;
					}

					$sidebar2_is_drawer = '1' === apply_filters( 'minimog/page_sidebar/2/off_sidebar/enable', '0' );
					if ( ! $sidebar2_is_drawer ) {
						$content_width -= $sidebar_width;
					}

					if ( ! $sidebar1_is_drawer && ! $sidebar2_is_drawer ) {
						$sidebars_breakpoint = 1199;
					}
				} else {
					$sidebar_width = $sidebar_offset = '';

					if ( is_single() ) {
						$sidebar_width  = Minimog_Helper::get_post_meta( 'page_single_sidebar_width', '' );
						$sidebar_offset = Minimog_Helper::get_post_meta( 'page_single_sidebar_offset', '' );
					}

					if ( '' === $sidebar_width ) {
						$global_sidebar_width = Minimog::setting( 'single_sidebar_width' );
						$global_sidebar_width = is_array( $global_sidebar_width ) && isset( $global_sidebar_width['width'] ) ? $global_sidebar_width['width'] : 25;
						$sidebar_width        = apply_filters( 'minimog/page_sidebar/single_width', $global_sidebar_width );
					}

					$sidebar_width = floatval( $sidebar_width );

					if ( '' === $sidebar_offset ) {
						$global_sidebar_offset = Minimog::setting( 'single_sidebar_offset' );
						$global_sidebar_offset = $global_sidebar_offset['width'] ? $global_sidebar_offset['width'] : '0';
						$sidebar_offset        = apply_filters( 'minimog/page_sidebar/single_offset', $global_sidebar_offset );
					}

					if ( ! $sidebar1_is_drawer ) {
						$content_width -= $sidebar_width;
					}
				}

				if ( $content_width < 100 ) {
					/**
					 * Fix Redux Framework return width without unit when setting not saved.
					 */
					if ( '' !== $sidebar_offset && stripos( $sidebar_offset, 'px' ) === false ) {
						$sidebar_offset .= 'px';
					}

					$_min_width_breakpoint = $sidebars_breakpoint + 1;

					$css .= "
					@media (min-width: {$_min_width_breakpoint}px) {
						.page-sidebar {
							flex: 0 0 $sidebar_width%;
							max-width: $sidebar_width%;
						}
						.page-main-content {
							flex: 0 0 $content_width%;
							max-width: $content_width%;
						}
					}";

					if ( '' !== $sidebar_offset ) {
						if ( is_rtl() ) {
							$css .= "@media (min-width: 1200px) {
								.page-sidebar-left .page-sidebar-inner {
									padding-left: $sidebar_offset;
								}
								.page-sidebar-right .page-sidebar-inner {
									padding-right: $sidebar_offset;
								}
							}";
						} else {
							$css .= "@media (min-width: 1200px) {
								.page-sidebar-left .page-sidebar-inner {
									padding-right: $sidebar_offset;
								}
								.page-sidebar-right .page-sidebar-inner {
									padding-left: $sidebar_offset;
								}
							}";
						}
					}

					if ( $sidebars_below === '1' ) {
						$css .= "@media (max-width: {$sidebars_breakpoint}px) {
							.page-sidebar {
								margin-top: 50px;
							}
						
							.page-main-content {
								-webkit-order: -1;
								-moz-order: -1;
								order: -1;
							}
						}";
					}
				}
			}

			return $css;
		}

		public function custom_code_css() {
			if ( empty( Minimog::setting( 'custom_css_enable' ) ) ) {
				return;
			}

			$custom_css = Minimog::setting( 'custom_css' );

			if ( empty( $custom_css ) ) {
				return;
			}

			wp_add_inline_style( 'minimog-style', html_entity_decode( $custom_css, ENT_QUOTES ) );
		}
	}

	Minimog_Custom_Css::instance()->initialize();
}
