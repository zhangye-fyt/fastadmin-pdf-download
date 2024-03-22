<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Mobile Menu', 'minimog' ),
	'id'         => 'mobile_menu',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'mobile_menu_login_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Login', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'mobile_menu_login_enable' ),
		),
		array(
			'id'      => 'mobile_menu_wishlist_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Wishlist', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'mobile_menu_wishlist_enable' ),
		),
		array(
			'id'      => 'mobile_menu_info_list_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Info List', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'mobile_menu_info_list_enable' ),
		),
		array(
			'id'      => 'mobile_menu_social_networks_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Social Networks', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'mobile_menu_social_networks_enable' ),
		),
		array(
			'id'      => 'mobile_menu_language_switcher_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Language Switcher', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'mobile_menu_language_switcher_enable' ),
		),
		array(
			'id'            => 'mobile_menu_breakpoint',
			'title'         => esc_html__( 'Breakpoint', 'minimog' ),
			'description'   => esc_html__( 'Controls the breakpoint of the mobile menu.', 'minimog' ),
			'type'          => 'slider',
			'default'       => Minimog_Redux::get_default_setting( 'mobile_menu_breakpoint' ),
			'min'           => 500,
			'max'           => 1300,
			'step'          => 1,
			'display_value' => 'text',
		),
		array(
			'id'      => 'mobile_menu_open_animation',
			'type'    => 'select',
			'title'   => esc_html__( 'Open Animation', 'minimog' ),
			'options' => [
				'slide' => esc_html__( 'Slide', 'minimog' ),
				'push'  => esc_html__( 'Push', 'minimog' ),
			],
			'default' => Minimog_Redux::get_default_setting( 'mobile_menu_open_animation' ),
		),
		array(
			'id'      => 'mobile_menu_background',
			'title'   => esc_html__( 'Background', 'minimog' ),
			'type'    => 'background',
			'default' => Minimog_Redux::get_default_setting( 'mobile_menu_background' ),
		),
		array(
			'id'          => 'mobile_menu_overlay_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Background Overlay', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'       => 'section_start_mobile_menu_nav_level_1',
			'type'     => 'tm_heading',
			'collapse' => 'show',
			'title'    => esc_html__( 'Level 1', 'minimog' ),
			'indent'   => true,
		),
		array(
			'id'             => 'mobile_menu_nav_level_1_padding',
			'type'           => 'spacing',
			'mode'           => 'padding',
			'all'            => false,
			'units'          => array( 'em', 'px', '%' ),
			'units_extended' => true,
			'title'          => esc_html__( 'Item Padding', 'minimog' ),
			'default'        => Minimog_Redux::get_default_setting( 'mobile_menu_nav_level_1_padding' ),
		),
	),
) );
