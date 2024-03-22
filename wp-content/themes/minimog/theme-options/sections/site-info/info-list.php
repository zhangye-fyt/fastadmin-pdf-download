<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Info List', 'minimog' ),
	'id'         => 'section_info_list',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'           => 'info_list',
			'type'         => 'repeater',
			'title'        => esc_html__( 'Info List', 'minimog' ),
			'item_name'    => esc_html__( 'Item', 'minimog' ),
			'bind_title'   => 'text',
			'group_values' => true,
			'fields'       => array(
				array(
					'id'    => 'text',
					'type'  => 'textarea',
					'title' => esc_html__( 'Text', 'minimog' ),
				),
				array(
					'id'    => 'url',
					'type'  => 'text',
					'title' => esc_html__( 'Link Url', 'minimog' ),
				),
				array(
					'id'    => 'icon_class',
					'title' => esc_html__( 'Icon CSS class', 'minimog' ),
					'type'  => 'text',
				),
			),
			'default'      => [
				'Redux_repeater_data' => [
					[
						'title' => '',
					],
					[
						'title' => '',
					],
				],
				'text'                => [
					'100k Followers',
					'300k Followers',
				],
				'url'                 => [
					'https://instagram.com',
					'https://facebook.com',
				],
				'icon_class'          => [
					'fab fa-instagram',
					'fab fa-facebook',
				],
			],
		),
	),
) );
