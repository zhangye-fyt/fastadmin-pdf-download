<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'General', 'minimog' ),
	'id'         => 'top_bar_general',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'global_top_bar',
			'type'    => 'select',
			'title'   => esc_html__( 'Default Top Bar', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'global_top_bar' ),
			'options' => Minimog_Top_Bar::instance()->get_list(),
		),
		array(
			'id'       => 'section_start_top_bar_components',
			'type'     => 'tm_heading',
			'collapse' => 'show',
			'title'    => esc_html__( 'Components', 'minimog' ),
			'indent'   => true,
		),
		array(
			'id'      => 'top_bar_text',
			'type'    => 'textarea',
			'title'   => esc_html__( 'Text', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'top_bar_text' ),
		),
		array(
			'id'           => 'top_bar_marque_list',
			'type'         => 'repeater',
			'title'        => esc_html__( 'Marque List', 'minimog' ),
			'item_name'    => esc_html__( 'Item', 'minimog' ),
			'bind_title'   => 'text',
			'group_values' => true,
			'fields'       => array(
				array(
					'id'    => 'text',
					'title' => esc_html__( 'Text', 'minimog' ),
					'type'  => 'textarea',
				),
				array(
					'id'    => 'url',
					'title' => esc_html__( 'Url', 'minimog' ),
					'type'  => 'text',
				),
			),
			'default'      => [
				'Redux_repeater_data' => [
					[ 'title' => '' ],
					[ 'title' => '' ],
					[ 'title' => '' ],
				],
				'text'                => [
					'<strong class="marque-tag-line">MINIMOG </strong> New Collection in Town <span class="marque-arrow-icon far fa-arrow-right"></span>',
					'<strong class="marque-tag-line">MINIMOG </strong> New Collection in Town <span class="marque-arrow-icon far fa-arrow-right"></span>',
					'<strong class="marque-tag-line">MINIMOG </strong> New Collection in Town <span class="marque-arrow-icon far fa-arrow-right"></span>',
				],
				'url'                 => [
					'#',
					'#',
					'#',
				],
			],
		),
	),
) );
