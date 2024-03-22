<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Product Question', 'minimog' ),
	'id'         => 'product_question',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'single_product_question_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Enable', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'No', 'minimog' ),
				'1' => esc_html__( 'Yes', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_product_question_enable' ),
		),
		array(
			'id'       => 'product_ask_question_role',
			'type'     => 'select',
			'title'    => esc_html__( 'Who can ask question?', 'minimog' ),
			'options'  => [
				'all'             => esc_html__( 'Everyone', 'minimog' ),
				'logged_in_users' => esc_html__( 'Logged in users', 'minimog' ),
			],
			'default'  => Minimog_Redux::get_default_setting( 'product_ask_question_role' ),
			'required' => array(
				[ 'single_product_question_enable', '=', '1' ],
			),
		),
		array(
			'id'       => 'product_reply_question_role',
			'type'     => 'select',
			'title'    => esc_html__( 'Who can reply question?', 'minimog' ),
			'options'  => [
				'all'             => esc_html__( 'Everyone', 'minimog' ),
				'logged_in_users' => esc_html__( 'Logged in users', 'minimog' ),
				'administrators'           => esc_html__( 'Only Administrators', 'minimog' ),
			],
			'default'  => Minimog_Redux::get_default_setting( 'product_reply_question_role' ),
			'required' => array(
				[ 'single_product_question_enable', '=', '1' ],
			),
		),
	),
) );
