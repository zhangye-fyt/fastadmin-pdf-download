<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

class Fonts {

	private static $_instance = null;

	const FONT_GROUP = 'minimog';

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		/**
		 * Add custom font groups
		 */
		add_filter( 'elementor/fonts/groups', [ $this, 'add_custom_font_groups' ], 10, 999 );

		/**
		 * Add custom font.
		 */
		add_filter( 'elementor/fonts/additional_fonts', [ $this, 'add_custom_fonts' ], 10, 999 );
	}

	public function add_custom_font_groups( $font_groups ) {
		$additional_groups = [
			self::FONT_GROUP => esc_html__( 'By Minimog', 'minimog' ),
		];

		return $additional_groups + $font_groups;
	}

	public function add_custom_fonts( $fonts ) {
		$additional_fonts = [
			'NewYork'             => self::FONT_GROUP,
			'Butler'              => self::FONT_GROUP,
			'Gordita'             => self::FONT_GROUP,
			'WorthbitesRough'     => self::FONT_GROUP,
			'ClashGrotesk'        => self::FONT_GROUP,
			'Futura'              => self::FONT_GROUP,
			'Gilroy'              => self::FONT_GROUP,
			'Jellee'              => self::FONT_GROUP,
			'GeneralSans'         => self::FONT_GROUP,
			'Sentient'            => self::FONT_GROUP,
			'Satoshi'             => self::FONT_GROUP,
			'GeezaPro'            => self::FONT_GROUP,
			'Open Sans Condensed' => self::FONT_GROUP,
		];

		return array_merge( $fonts, $additional_fonts );
	}
}

Fonts::instance()->initialize();
