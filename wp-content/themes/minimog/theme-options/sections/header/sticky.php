<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Sticky', 'minimog' ),
	'id'         => 'header_sticky',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'header_sticky_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Enable', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'No', 'minimog' ),
				'1' => esc_html__( 'Yes', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'header_sticky_enable' ),
		),
		array(
			'id'      => 'header_sticky_logo',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Logo Version', 'minimog' ),
			'options' => array(
				'dark'  => esc_html__( 'Dark', 'minimog' ),
				'light' => esc_html__( 'Light', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'header_sticky_logo' ),
		),
		array(
			'id'      => 'header_sticky_background',
			'type'    => 'background',
			'default' => Minimog_Redux::get_default_setting( 'header_sticky_background' ),
			'title'   => esc_html__( 'Background', 'minimog' ),
		),
		array(
			'id'          => 'header_sticky_text_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Text Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_link_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Link Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_link_hover_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Link Hover Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_nav_link_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Nav Link Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_nav_link_hover_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Nav Link Hover Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_nav_line_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Nav Line Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_icon_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Icon Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_icon_hover_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Icon Hover Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_icon_badge_text_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Icon Badge Color', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'header_sticky_icon_badge_background_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Icon Badge Background', 'minimog' ),
			'color_alpha' => true,
		),
	),
) );
