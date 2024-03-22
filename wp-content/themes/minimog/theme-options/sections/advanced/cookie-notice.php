<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Cookie Notice', 'minimog' ),
	'id'         => 'cookie_notice',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'notice_cookie_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Cookie Notice', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'notice_cookie_enable' ),
		),
		array(
			'id'          => 'notice_cookie_messages',
			'type'        => 'textarea',
			'title'       => esc_html__( 'Messages', 'minimog' ),
			'description' => esc_html__( 'Enter the messages that displays for cookie notice.', 'minimog' ),
			'default'     => esc_html__( 'We use cookies to ensure that we give you the best experience on our website. If you continue to use this site we will assume that you are happy with it.', 'minimog' ),
		),
		array(
			'id'      => 'notice_cookie_button_text',
			'type'    => 'text',
			'title'   => esc_html__( 'Button Text', 'minimog' ),
			'default' => esc_html__( 'Ok, got it!', 'minimog' ),
		),
	),
) );
