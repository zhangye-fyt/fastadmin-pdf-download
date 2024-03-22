<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Checkout Page', 'minimog' ),
	'id'         => 'checkout_page',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'checkout_page_modal_customer_notes_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Customer Notes Modal', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'checkout_page_modal_customer_notes_enable' ),
		),
	),
) );
