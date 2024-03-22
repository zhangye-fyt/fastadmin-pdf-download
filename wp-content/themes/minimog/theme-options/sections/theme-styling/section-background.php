<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Site Background', 'minimog' ),
	'id'         => 'site_background',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'body_background',
			'title'   => esc_html__( 'Body Background', 'minimog' ),
			'type'    => 'background',
			'default' => array(
				'background-color' => '#fff',
			),
		),
		array(
			'id'      => 'page_blocks_style',
			'type'    => 'select',
			'title'   => esc_html__( 'Page Blocks Style', 'minimog' ),
			'options' => [
				'normal'       => esc_html__( 'Normal', 'minimog' ),
				'border-block' => esc_html__( 'Border Block', 'minimog' ),
			],
			'default' => Minimog_Redux::get_default_setting( 'page_blocks_style' ),
		),
	),
) );
