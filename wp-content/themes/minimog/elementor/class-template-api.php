<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

class Template_API {

	private        $config;
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		/**
		 * Templates API: https://minimog-templates.thememove.com/wp-json/tm/v2/templates/
		 * Template API: https://minimog-templates.thememove.com/wp-json/tm/v2/templates/%d/
		 */
		$this->config = array(
			'base'      => 'https://minimog-templates.thememove.com/',
			'path'      => 'wp-json/tm/v2',
			'endpoints' => array(
				'templates'      => '/templates/',
				'template'       => '/templates/%d/',
				'template_types' => '/template_types/',
				'template_tags'  => '/template_tags/',
			),
		);

		add_filter( 'tm_addons/elementor/templates_info_api', [ $this, 'get_templates_info_api' ] );
		add_filter( 'tm_addons/elementor/template_data_api', [ $this, 'get_template_data_api' ] );
		add_filter( 'tm_addons/elementor/template_tags', [ $this, 'get_template_tags_api' ] );
	}

	public function get_api_url( $flag ) {
		$config = $this->config;

		if ( empty( $config['endpoints'][ $flag ] ) ) {
			return false;
		}

		return $config['base'] . $config['path'] . $config['endpoints'][ $flag ];
	}

	public function get_templates_info_api() {
		return $this->get_api_url( 'templates' );
	}

	public function get_template_data_api() {
		return $this->get_api_url( 'template' );
	}

	public function get_template_tags_api() {
		return $this->get_api_url( 'template_tags' );
	}
}

Template_API::instance();
