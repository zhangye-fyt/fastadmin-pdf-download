<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

class Control_Init {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-font-awesome-pro.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/class-font-elementor.php' );

		/**
		 * Register Controls.
		 */
		add_action( 'elementor/controls/controls_registered', array( $this, 'init_controls' ) );

		/**
		 * Add custom shape divider
		 */
		add_filter( 'elementor/shapes/additional_shapes', [ $this, 'add_custom_shape_divider' ] );
	}

	public function add_custom_shape_divider( $additional_shapes ) {
		$additional_shapes['center-curve'] = [
			'title'        => esc_html__( 'Curve Alt', 'minimog' ),
			'has_negative' => true,
			'height_only'  => true,
			'url'          => get_template_directory_uri() . '/assets/shape-divider/center-curve.svg',
			'path'         => get_template_directory() . '/assets/shape-divider/center-curve.svg',
		];

		$additional_shapes['tilt-curve'] = [
			'title'       => esc_html__( 'Tile Curve', 'minimog' ),
			'has_flip'    => true,
			'height_only' => true,
			'url'         => get_template_directory_uri() . '/assets/shape-divider/curve-tilt.svg',
			'path'        => get_template_directory() . '/assets/shape-divider/curve-tilt.svg',
		];

		$additional_shapes['mountain-alt'] = [
			'title'       => esc_html__( 'Mountain Alt', 'minimog' ),
			'has_flip'    => true,
			'height_only' => true,
			'url'         => get_template_directory_uri() . '/assets/shape-divider/mountain-alt.svg',
			'path'        => get_template_directory() . '/assets/shape-divider/mountain-alt.svg',
		];

		return $additional_shapes;
	}

	/**
	 * @param \Elementor\Controls_Manager $controls_manager
	 *
	 * Include controls files and register them
	 */
	public function init_controls( $controls_manager ) {
		// Include controls files.
		require_once MINIMOG_ELEMENTOR_DIR . '/controls/control-autocomplete.php';

		// Include controls files.
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/controls/group-control-text-gradient.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/controls/group-control-text-stroke.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/controls/group-control-advanced-border.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/controls/group-control-button.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/controls/group-control-tooltip.php' );

		$controls_manager->register( new Control_Autocomplete() );

		// Group Control.
		$controls_manager->add_group_control( Group_Control_Text_Gradient::get_type(), new Group_Control_Text_Gradient() );
		$controls_manager->add_group_control( Group_Control_Text_Stroke::get_type(), new Group_Control_Text_Stroke() );
		$controls_manager->add_group_control( Group_Control_Advanced_Border::get_type(), new Group_Control_Advanced_Border() );
		$controls_manager->add_group_control( Group_Control_Button::get_type(), new Group_Control_Button() );
		$controls_manager->add_group_control( Group_Control_Tooltip::get_type(), new Group_Control_Tooltip() );
	}
}

Control_Init::instance()->initialize();
