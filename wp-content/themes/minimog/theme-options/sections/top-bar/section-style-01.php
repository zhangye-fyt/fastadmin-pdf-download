<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
	'id'         => 'top_bar_style_01',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'top_bar_style_01_content_width',
			'type'    => 'select',
			'title'   => esc_html__( 'Content Width', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'top_bar_style_01_content_width' ),
			'options' => Minimog_Site_Layout::instance()->get_container_wide_list(),
		),
		array(
			'id'      => 'top_bar_style_01_layout',
			'type'    => 'select',
			'title'   => esc_html__( 'Layout', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'top_bar_style_01_layout' ),
			'options' => [
				'1l' => esc_html__( '1 Column - Left', 'minimog' ),
				'1c' => esc_html__( '1 Column - Center', 'minimog' ),
				'1r' => esc_html__( '1 Column - Right', 'minimog' ),
				'2'  => esc_html__( '2 Columns', 'minimog' ),
				'3'  => esc_html__( '3 Columns', 'minimog' ),
			],
		),
		array(
			'id'       => 'top_bar_style_01_left_components',
			'type'     => 'sortable',
			'mode'     => 'toggle',
			'title'    => esc_html__( 'Left Components', 'minimog' ),
			'default'  => Minimog_Redux::get_default_setting( 'top_bar_style_01_left_components' ),
			'options'  => Minimog_Top_Bar::instance()->get_support_components(),
			'required' => array(
				array(
					'top_bar_style_01_layout',
					'=',
					[ '1l', '2', '3' ],
				),
			),
		),
		array(
			'id'       => 'top_bar_style_01_center_components',
			'type'     => 'sortable',
			'mode'     => 'toggle',
			'title'    => esc_html__( 'Center Components', 'minimog' ),
			'default'  => Minimog_Redux::get_default_setting( 'top_bar_style_01_center_components' ),
			'options'  => Minimog_Top_Bar::instance()->get_support_components(),
			'required' => array(
				array(
					'top_bar_style_01_layout',
					'=',
					[ '1c', '3' ],
				),
			),
		),
		array(
			'id'       => 'top_bar_style_01_right_components',
			'type'     => 'sortable',
			'mode'     => 'toggle',
			'title'    => esc_html__( 'Right Components', 'minimog' ),
			'default'  => Minimog_Redux::get_default_setting( 'top_bar_style_01_right_components' ),
			'options'  => Minimog_Top_Bar::instance()->get_support_components(),
			'required' => array(
				array(
					'top_bar_style_01_layout',
					'=',
					[ '1r', '2', '3' ],
				),
			),
		),
		array(
			'id'      => 'top_bar_style_01_visibility',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Visibility', 'minimog' ),
			'options' => array(
				'hide_on_mobile' => esc_html__( 'Hide on Mobile', 'minimog' ),
				'1'              => esc_html__( 'Always Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'top_bar_style_01_visibility' ),
		),
		array(
			'id'          => 'top_bar_style_01_bg_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Background Color', 'minimog' ),
			'default'     => Minimog_Redux::get_default_setting( 'top_bar_style_01_bg_color' ),
			'color_alpha' => true,
		),
		array(
			'id'    => 'top_bar_style_01_border',
			'type'  => 'border',
			'title' => esc_html__( 'Border', 'minimog' ),
			'all'   => false,
			'top'   => false,
			'left'  => false,
			'right' => false,
		),
		array(
			'id'                => 'top_bar_style_01_text_typography',
			'type'              => 'typography',
			'title'             => esc_html__( 'Text Typography', 'minimog' ),
			'google'            => true,
			'font_family_clear' => true,
			'default'           => Minimog_Redux::get_default_setting( 'top_bar_style_01_text_typography' ),
			'text-align'        => false,
			'letter-spacing'    => true,
			'text-transform'    => true,
			'preview'           => false,
		),
		array(
			'id'          => 'top_bar_style_01_link_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Link Color', 'minimog' ),
			'color_alpha' => true,
			'default'     => Minimog_Redux::get_default_setting( 'top_bar_style_01_link_color' ),
		),
		array(
			'id'          => 'top_bar_style_01_link_hover_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Link Hover Color', 'minimog' ),
			'color_alpha' => true,
			'default'     => Minimog_Redux::get_default_setting( 'top_bar_style_01_link_hover_color' ),
		),
		array(
			'id'          => 'top_bar_style_01_tag_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Tag Color', 'minimog' ),
			'color_alpha' => true,
			'default'     => Minimog_Redux::get_default_setting( 'top_bar_style_01_tag_color' ),
		),
		array(
			'id'          => 'top_bar_style_01_tag_background',
			'type'        => 'color',
			'title'       => esc_html__( 'Tag Background', 'minimog' ),
			'color_alpha' => true,
			'default'     => Minimog_Redux::get_default_setting( 'top_bar_style_01_tag_background' ),
		),
	),
) );
