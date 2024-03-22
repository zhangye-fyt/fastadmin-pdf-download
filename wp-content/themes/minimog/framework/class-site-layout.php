<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Site_Layout' ) ) {

	class Minimog_Site_Layout {

		const CONTAINER_NORMAL       = 'normal';
		const CONTAINER_EXTENDED     = 'extended';
		const CONTAINER_BROAD        = 'broad';
		const CONTAINER_LARGE        = 'large';
		const CONTAINER_WIDE         = 'wide';
		const CONTAINER_WIDER        = 'wider';
		const CONTAINER_FULL         = 'full';
		const CONTAINER_FULL_GAP_0   = 'full-gap-0';
		const CONTAINER_FULL_GAP_80  = 'full-gap-80';
		const CONTAINER_FULL_GAP_100 = 'full-gap-100';

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {

		}

		public function get_container_wide_list( $default_option = false, $default_text = '' ) {
			$options = [
				'normal'       => 'Normal ( 1170px )',
				'extended'     => 'Extended ( 1280px )',
				'broad'        => 'Broad ( 1340px )',
				'large'        => 'Large ( 1410px )',
				'wide'         => 'Wide ( 1620px )',
				'wider'        => 'Wider ( 1720px )',
				'full'         => 'Full Wide',
				'full-gap-100' => 'Full Wide - Gap 100px',
				'full-gap-80'  => 'Full Wide - Gap 80px',
				'full-gap-0'   => 'Full Wide - No Gap',
			];

			if ( $default_option ) {
				$default_text = ! empty( $default_text ) ? $default_text : esc_html__( 'Default', 'minimog' );

				$options = array( '' => $default_text ) + $options;
			}

			return $options;
		}

		public function get_container_class( $content_width = '' ) {
			switch ( $content_width ) {
				case 'extended':
					return 'container-extended';
				case 'broad':
					return 'container-broad';
				case 'large':
					return 'container-large';
				case 'wide':
					return 'container-wide';
				case 'wider':
					return 'container-wider';
				case 'full':
					return 'container-fluid';
				case 'full-gap-100':
					return 'container-fluid container-gap-100';
				case 'full-gap-80':
					return 'container-fluid container-gap-80';
				case 'full-gap-0':
					return 'container-fluid container-gap-0';
				default:
					return 'container';
			}
		}
	}

	Minimog_Site_Layout::instance()->initialize();
}
