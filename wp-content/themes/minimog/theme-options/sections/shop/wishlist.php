<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Wishlist', 'minimog' ),
	'id'         => 'wishlist',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'       => 'wishlist_general_settings',
			'type'     => 'tm_heading',
			'title'    => esc_html__( 'General', 'minimog' ),
			'indent'   => true,
			'collapse' => 'show',
		),
		array(
			'id'      => 'wishlist_icon_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Wishlist Icon', 'minimog' ),
			'options' => [
				'star'  => esc_html__( 'Star', 'minimog' ),
				'heart' => esc_html__( 'Heart', 'minimog' ),
			],
			'default' => Minimog_Redux::get_default_setting( 'wishlist_icon_type' ),
		),
		array(
			'id'       => 'wishlist_single_product_settings',
			'type'     => 'tm_heading',
			'title'    => esc_html__( 'Single Product', 'minimog' ),
			'indent'   => true,
			'collapse' => 'show',
		),
		array(
			'id'      => 'single_product_wishlist_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Show button', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'No', 'minimog' ),
				'1' => esc_html__( 'Yes', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_product_wishlist_enable' ),
		),
	),
) );
