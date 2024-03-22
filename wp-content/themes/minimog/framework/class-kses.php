<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Kses' ) ) {
	class Minimog_Kses {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 2, 99 );
		}

		public function wp_kses_allowed_html( $allowedtags, $context ) {

			$basic_atts = array(
				'id'    => array(),
				'class' => array(),
				'style' => array(),
			);

			switch ( $context ) {
				case 'minimog-img':
					$allowedtags = array(
						'img' => array(
							'id'     => array(),
							'class'  => array(),
							'style'  => array(),
							'src'    => array(),
							'width'  => array(),
							'height' => array(),
							'alt'    => array(),
							'srcset' => array(),
							'sizes'  => array(),
						),
					);
					break;
				case 'minimog-a':
					$allowedtags = array(
						'a' => array(
							'id'     => array(),
							'class'  => array(),
							'style'  => array(),
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
							'title'  => array(),
						),
					);
					break;
				case 'minimog-default' :
					$allowedtags = array(
						'a'      => array(
							'id'     => array(),
							'class'  => array(),
							'style'  => array(),
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
							'title'  => array(),
						),
						'img'    => array(
							'id'     => array(),
							'class'  => array(),
							'style'  => array(),
							'src'    => array(),
							'width'  => array(),
							'height' => array(),
							'alt'    => array(),
							'srcset' => array(),
							'sizes'  => array(),
						),
						'br'     => array(),
						'ul'     => array(
							'id'    => array(),
							'class' => array(),
							'style' => array(),
							'type'  => array(),
						),
						'ol'     => array(
							'id'    => array(),
							'class' => array(),
							'style' => array(),
							'type'  => array(),
						),
						'li'     => $basic_atts,
						'h1'     => $basic_atts,
						'h2'     => $basic_atts,
						'h3'     => $basic_atts,
						'h4'     => $basic_atts,
						'h5'     => $basic_atts,
						'h6'     => $basic_atts,
						'div'    => $basic_atts,
						'p'      => $basic_atts,
						'strong' => $basic_atts,
						'b'      => $basic_atts,
						'span'   => $basic_atts,
						'mark'   => $basic_atts,
						'i'      => $basic_atts,
						'del'    => $basic_atts,
						'ins'    => $basic_atts,
						'svg'   => array(
							'class'           => true,
							'aria-hidden'     => true,
							'aria-labelledby' => true,
							'role'            => true,
							'xmlns'           => true,
							'width'           => true,
							'height'          => true,
							'viewbox'         => true // <= Must be lower case!
						),
						'g'     => array( 'fill' => true ),
						'title' => array( 'title' => true ),
						'path'  => array(
							'd'               => true,
							'fill'            => true
						)
					);
					break;
			}

			return $allowedtags;
		}
	}

	Minimog_Kses::instance()->initialize();
}
