<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Promo_Popup' ) ) {

	class Minimog_Promo_Popup {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
			add_action( 'wp_footer', [ $this, 'output_promo_popup' ] );
		}

		public function is_active() {
			if ( '1' === Minimog::setting( 'promo_popup_enable' ) ) {
				return true;
			}

			return false;
		}

		public function frontend_scripts() {
			global $post;
			$min = Minimog_Enqueue::instance()->get_min_suffix();

			if ( $this->is_active() ) {
				$includes_by = Minimog::setting( 'promo_popup_show_on_pages' );

				$post_id        = isset( $post, $post->ID ) ? $post->ID : 0;
				$passConditions = true;
				if ( ! empty( $includes_by ) && ! in_array( $post_id, $includes_by ) ) {
					$passConditions = false;
				}

				if ( '1' === Minimog::setting( 'promo_popup_rule_hide_by_logged_in' ) && is_user_logged_in() ) {
					$passConditions = false;
				}

				if ( $passConditions ) {
					wp_register_script( 'minimog-promo-popup', MINIMOG_THEME_URI . "/assets/js/promo-popup{$min}.js", [
						'minimog-modal',
						'minimog-script',
					], MINIMOG_THEME_VERSION, true );

					$js_variables = array(
						'onLoad'      => [
							'enable' => Minimog::setting( 'promo_popup_trigger_on_load' ),
							'delay'  => floatval( Minimog::setting( 'promo_popup_trigger_on_load_delay' ) ),
						],
						'onScrolling' => [
							'enable'    => Minimog::setting( 'promo_popup_trigger_on_scrolling' ),
							'direction' => Minimog::setting( 'promo_popup_trigger_scrolling_direction' ),
							'offset'    => intval( Minimog::setting( 'promo_popup_trigger_scrolling_offset' ) ),
						],
						'onClick'     => [
							'enable'     => Minimog::setting( 'promo_popup_trigger_on_click' ),
							'clickTimes' => Minimog::setting( 'promo_popup_trigger_click_times' ),
						],
						'rules'       => [
							'byTimes'     => [
								'enable' => Minimog::setting( 'promo_popup_rule_by_times' ),
								'times'  => Minimog::setting( 'promo_popup_rule_times_up_to' ),
							],
							'byPageViews' => [
								'enable' => Minimog::setting( 'promo_popup_rule_show_by_page_views' ),
								'reach'  => Minimog::setting( 'promo_popup_rule_page_views_reach' ),
							],
						],
					);
					wp_localize_script( 'minimog-promo-popup', '$minimogPopup', $js_variables );

					wp_enqueue_script( 'minimog-promo-popup' );
				}
			}
		}

		public function output_promo_popup() {
			if ( $this->is_active() ) {
				minimog_load_template( 'promo-popup' );
			}
		}
	}

	Minimog_Promo_Popup::instance()->initialize();
}
