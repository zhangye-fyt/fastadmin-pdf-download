<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

class Font_Awesome_Pro {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		//add_filter( 'elementor/icons_manager/native', [ $this, 'replace_font_awesome_pro' ], 10 );

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'deregister_style' ], 20 );
		add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_font' ], 11 );
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_font' ], 11 );
	}

	public function replace_font_awesome_pro( $settings ) {
		$json_url = MINIMOG_ELEMENTOR_ASSETS . '/libs/font-awesome-pro/%s.json';
		$version  = '5.10.0-pro';

		$icons['fa-regular'] = [
			'name'          => 'fa-regular',
			'label'         => esc_html__( 'Font Awesome - Regular Pro', 'minimog' ),
			'url'           => false,
			'enqueue'       => false,
			'prefix'        => 'fa-',
			'displayPrefix' => 'far',
			'labelIcon'     => 'fab fa-font-awesome-alt',
			'ver'           => $version,
			'fetchJson'     => sprintf( $json_url, 'regular' ),
			'native'        => true,
		];

		$icons['fa-solid'] = [
			'name'          => 'fa-solid',
			'label'         => esc_html__( 'Font Awesome - Solid Pro', 'minimog' ),
			'url'           => false,
			'enqueue'       => false,
			'prefix'        => 'fa-',
			'displayPrefix' => 'fas',
			'labelIcon'     => 'fab fa-font-awesome',
			'ver'           => $version,
			'fetchJson'     => sprintf( $json_url, 'solid' ),
			'native'        => true,
		];

		$icons['fa-brands'] = [
			'name'          => 'fa-brands',
			'label'         => esc_html__( 'Font Awesome - Brands Pro', 'minimog' ),
			'url'           => false,
			'enqueue'       => false,
			'prefix'        => 'fa-',
			'displayPrefix' => 'fab',
			'labelIcon'     => 'fab fa-font-awesome-flag',
			'ver'           => $version,
			'fetchJson'     => sprintf( $json_url, 'brands' ),
			'native'        => true,
		];

		$icons['fa-light'] = [
			'name'          => 'fa-light',
			'label'         => esc_html__( 'Font Awesome - Light Pro', 'minimog' ),
			'url'           => false,
			'enqueue'       => false,
			'prefix'        => 'fa-',
			'displayPrefix' => 'fal',
			'labelIcon'     => 'fal fa-flag',
			'ver'           => $version,
			'fetchJson'     => sprintf( $json_url, 'light' ),
			'native'        => true,
		];

		// Remove old from plugin.
		unset(
			$settings['fa-solid'],
			$settings['fa-regular'],
			$settings['fa-brands'],
			$settings['fa-light']
		);

		return array_merge( $icons, $settings );
	}

	public function deregister_style() {
		foreach ( [ 'solid', 'regular', 'brands' ] as $style ) {
			wp_deregister_style( 'elementor-icons-fa-' . $style );
		}
	}

	public function enqueue_font() {
		wp_enqueue_style( 'font-awesome-pro', MINIMOG_THEME_URI . '/assets/fonts/awesome/css/all.min.css', null, '5.15.4' );
	}
}

Font_Awesome_Pro::instance()->initialize();
