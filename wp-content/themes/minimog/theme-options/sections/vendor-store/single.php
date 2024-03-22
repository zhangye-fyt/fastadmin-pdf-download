<?php
$sidebar_positions   = Minimog_Helper::get_list_sidebar_positions();
$registered_sidebars = Minimog_Redux::instance()->get_registered_widgets_options();

Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Single Store', 'minimog' ),
	'id'         => 'panel_single_store',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'     => 'section_start_store_single_sidebar',
			'type'     => 'tm_heading',
			'title'  => esc_html__( 'Sidebar Settings', 'minimog' ),
			'indent' => true,
		),
		/*array(
			'id'      => 'store_page_sidebar_1',
			'type'    => 'select',
			'title'   => esc_html__( 'Sidebar 1', 'minimog' ),
			'options' => $registered_sidebars,
			'default' => Minimog_Redux::get_default_setting( 'store_page_sidebar_1' ),
		),
		array(
			'id'      => 'store_page_sidebar_2',
			'type'    => 'select',
			'title'   => esc_html__( 'Sidebar 2', 'minimog' ),
			'options' => $registered_sidebars,
			'default' => Minimog_Redux::get_default_setting( 'store_page_sidebar_2' ),
		),
		array(
			'id'      => 'store_page_sidebar_position',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Sidebar Position', 'minimog' ),
			'options' => $sidebar_positions,
			'default' => Minimog_Redux::get_default_setting( 'store_page_sidebar_position' ),
		),*/
		array(
			'id'             => 'store_page_single_sidebar_width',
			'type'           => 'dimensions',
			'units'          => array( '%' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Single Sidebar Width', 'minimog' ),
			'description'    => esc_html__( 'Controls the width of the sidebar when only one sidebar is present. Leave blank to use global setting.', 'minimog' ),
			'height'         => false,
			'default'        => Minimog_Redux::get_default_setting( 'store_page_single_sidebar_width' ),
		),
		array(
			'id'             => 'store_page_single_sidebar_offset',
			'type'           => 'dimensions',
			'units'          => array( 'px' ),
			'units_extended' => 'false',
			'title'          => esc_html__( 'Single Sidebar Offset', 'minimog' ),
			'description'    => esc_html__( 'Controls the offset of the sidebar when only one sidebar is present. Leave blank to use global setting.', 'minimog' ),
			'height'         => false,
			'default'        => Minimog_Redux::get_default_setting( 'store_page_single_sidebar_offset' ),
		),
		array(
			'id'      => 'store_page_sidebar_style',
			'type'    => 'select',
			'title'   => esc_html__( 'Sidebar Style', 'minimog' ),
			'options' => Minimog_Sidebar::instance()->get_supported_style_options(),
			'default' => Minimog_Redux::get_default_setting( 'store_page_sidebar_style' ),
		),
		array(
			'id'     => 'section_start_store_single_layout',
			'type'     => 'tm_heading',
			'title'  => esc_html__( 'Layout Settings', 'minimog' ),
			'indent' => true,
		),
		array(
			'id'      => 'single_store_site_layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Site Layout', 'minimog' ),
			'options' => Minimog_Site_Layout::instance()->get_container_wide_list(),
			'default' => Minimog_Redux::get_default_setting( 'single_store_site_layout' ),
		),
	),
) );
