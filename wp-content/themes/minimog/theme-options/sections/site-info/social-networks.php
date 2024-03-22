<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Social Networks', 'minimog' ),
	'id'         => 'section_social_networks',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'social_link_target',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Open link in a new tab.', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'No', 'minimog' ),
				'1' => esc_html__( 'Yes', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'social_link_target' ),
		),
		array(
			'id'           => 'social_link',
			'type'         => 'repeater',
			'title'        => esc_html__( 'Social Networks', 'minimog' ),
			'item_name'    => esc_html__( 'Item', 'minimog' ),
			'bind_title'   => 'tooltip',
			'group_values' => true,
			'fields'       => array(
				array(
					'id'          => 'tooltip',
					'type'        => 'text',
					'title'       => esc_html__( 'Tooltip', 'minimog' ),
					'description' => esc_html__( 'Enter your hint text for your icon', 'minimog' ),
				),
				array(
					'id'    => 'link_url',
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
					[ 'title' => '' ],
					[ 'title' => '' ],
					[ 'title' => '' ],
					[ 'title' => '' ],
				],
				'tooltip'             => [
					'Twitter',
					'Facebook',
					'Instagram',
					'Linkedin',
				],
				'link_url'            => [
					'https://twitter.com',
					'https://facebook.com',
					'https://instagram.com',
					'https://linkedin.com',
				],
				'icon_class'          => [
					'fab fa-twitter',
					'fab fa-facebook',
					'fab fa-instagram',
					'fab fa-linkedin',
				],
			],
		),
	),
) );
