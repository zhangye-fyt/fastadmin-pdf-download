<?php

Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'  => esc_html__( 'Login/Register Popup', 'minimog' ),
	'id'     => 'panel_login_popup',
	'icon'   => 'eicon-user-circle-o',
	'fields' => array(
		array(
			'id'      => 'login_popup_enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Show Popup', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'login_popup_enable' ),
			'on'      => esc_html__( 'Yes', 'minimog' ),
			'off'     => esc_html__( 'No', 'minimog' ),
		),
		array(
			'id'       => 'section_start_form_login',
			'type'     => 'tm_heading',
			'title'    => esc_html__( 'Login Form', 'minimog' ),
			'indent'   => true,
			'collapse' => 'show',
		),
		array(
			'id'      => 'login_redirect',
			'type'    => 'select',
			'title'   => esc_html__( 'Login Redirect', 'minimog' ),
			'options' => array(
				'current'   => esc_html__( 'Current Page', 'minimog' ),
				'home'      => esc_html__( 'Home', 'minimog' ),
				'dashboard' => esc_html__( 'My Account', 'minimog' ),
				'custom'    => esc_html__( 'Custom', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'login_redirect' ),
		),
		array(
			'id'       => 'custom_login_redirect',
			'type'     => 'text',
			'title'    => esc_html__( 'Custom Url', 'minimog' ),
			'default'  => '',
			'required' => array( 'login_redirect', '=', 'custom' ),
		),
		array(
			'id'       => 'section_start_form_register',
			'type'     => 'tm_heading',
			'title'    => esc_html__( 'Register Form', 'minimog' ),
			'indent'   => true,
			'collapse' => 'show',
		),
		array(
			'id'      => 'page_for_terms_and_conditions',
			'type'    => 'select',
			'title'   => esc_html__( 'Terms and conditions', 'minimog' ),
			'options' => Minimog_Helper::get_all_pages(),
			'default' => Minimog_Redux::get_default_setting( 'page_for_terms_and_conditions' ),
		),
		array(
			'id'          => 'register_form_acceptance_text',
			'type'        => 'textarea',
			'title'       => esc_html__( 'Acceptance text', 'minimog' ),
			'default'     => Minimog_Redux::get_default_setting( 'register_form_acceptance_text' ),
			'description' => '{privacy} will replace with Privacy Policy page link. <a href="' . esc_url( admin_url( 'options-privacy.php' ) ) . '">Select page</a><br/> {terms} will replace with Terms of Conditions page link.',
		),
	),
) );
