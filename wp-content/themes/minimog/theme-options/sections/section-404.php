<?php
$sidebar_positions   = Minimog_Helper::get_list_sidebar_positions();
$registered_sidebars = Minimog_Helper::get_registered_sidebars();

Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'  => esc_html__( '404 Page', 'minimog' ),
	'id'     => 'panel_404',
	'icon'   => 'eicon-error-404',
	'fields' => array(
		array(
			'id'          => 'error404_page_header_type',
			'type'        => 'select',
			'title'       => esc_html__( 'Header Style', 'minimog' ),
			'description' => esc_html__( 'Select default header style that displays on 404 page.', 'minimog' ),
			'placeholder' => esc_html__( 'Use Global Setting', 'minimog' ),
			'options'     => Minimog_Header::instance()->get_list( true ),
		),
		array(
			'id'          => 'error404_page_header_overlay',
			'type'        => 'select',
			'title'       => esc_html__( 'Header Overlay', 'minimog' ),
			'placeholder' => esc_html__( 'Use Global Setting', 'minimog' ),
			'options'     => Minimog_Header::instance()->get_overlay_list(),
			'default'     => Minimog_Redux::get_default_setting( 'error404_page_header_overlay' ),
		),
		array(
			'id'          => 'error404_page_header_skin',
			'type'        => 'select',
			'title'       => esc_html__( 'Header Skin', 'minimog' ),
			'placeholder' => esc_html__( 'Use Global Setting', 'minimog' ),
			'options'     => Minimog_Header::instance()->get_skin_list(),
		),
		array(
			'id'      => 'error404_page_header_background',
			'type'    => 'select',
			'title'   => esc_html__( 'Header Background', 'minimog' ),
			'options' => Minimog_Header::instance()->get_background_list(),
			'default' => Minimog_Redux::get_default_setting( 'error404_page_header_background' ),
		),
		array(
			'id'      => 'error404_page_header_shadow',
			'type'    => 'select',
			'title'   => esc_html__( 'Header Shadow', 'minimog' ),
			'options' => Minimog_Header::instance()->get_shadow_list(),
			'default' => Minimog_Redux::get_default_setting( 'error404_page_header_shadow' ),
		),
		array(
			'id'      => 'error404_page_background_body',
			'title'   => esc_html__( 'Background', 'minimog' ),
			'type'    => 'background',
			'default' => Minimog_Redux::get_default_setting( 'error404_page_background_body' ),
		),
		array(
			'id'      => 'error404_page_image',
			'type'    => 'media',
			'title'   => esc_html__( 'Image', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'error404_page_image' ),
		),
		array(
			'id'      => 'error404_page_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Title', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'error404_page_title' ),
		),
		array(
			'id'      => 'error404_page_sub_title',
			'type'    => 'text',
			'title'   => esc_html__( 'Sub Title', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'error404_page_sub_title' ),
		),
		array(
			'id'    => 'error404_page_text',
			'type'  => 'textarea',
			'title' => esc_html__( 'Text', 'minimog' ),
		),
		array(
			'id'      => 'error404_page_search_enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Search Form', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'error404_page_search_enable' ),
		),
		array(
			'id'      => 'error404_page_buttons_enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Buttons', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'error404_page_buttons_enable' ),
		),
	),
) );
