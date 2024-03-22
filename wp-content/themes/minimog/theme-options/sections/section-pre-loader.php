<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'  => esc_html__( 'Pre Loader', 'minimog' ),
	'id'     => 'panel_pre_loader',
	'icon'   => 'eicon-loading',
	'fields' => array(
		array(
			'id'      => 'pre_loader_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Pre Loader', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'pre_loader_enable' ),
		),
		array(
			'id'      => 'pre_loader_style',
			'type'    => 'select',
			'title'   => esc_html__( 'Style', 'minimog' ),
			'options' => [
				'rotating-plane' => esc_attr__( 'Rotating Plane', 'minimog' ),
				'circle'         => esc_attr__( 'Circle', 'minimog' ),
				'gif-image'      => esc_attr__( 'Gif Image', 'minimog' ),
			],
			'default' => Minimog_Redux::get_default_setting( 'pre_loader_style' ),
		),
		array(
			'id'          => 'pre_loader_background_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Background Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'pre_loader_shape_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Shape Color', 'minimog' ),
			'color_alpha' => true,
			'required'    => array(
				[ 'pre_loader_style', '!=', 'gif-image' ],
			),
		),
		array(
			'id'       => 'pre_loader_image',
			'type'     => 'media',
			'title'    => esc_html__( 'Gif Image', 'minimog' ),
			'default'  => Minimog_Redux::get_default_setting( 'pre_loader_image' ),
			'required' => array(
				[ 'pre_loader_style', '=', 'gif-image' ],
			),
		),
		array(
			'id'             => 'pre_loader_image_width',
			'type'           => 'dimensions',
			'units'          => array( 'em', 'px', '%' ),
			'units_extended' => 'true',
			'title'          => esc_html__( 'Image Width', 'minimog' ),
			'default'        => Minimog_Redux::get_default_setting( 'pre_loader_image_width' ),
			'height'         => false,
			'required'       => array(
				[ 'pre_loader_style', '=', 'gif-image' ],
			),
		),
	),
) );
