<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Mobile Tabs', 'minimog' ),
	'id'         => 'mobile_tabs',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'mobile_tabs_enable',
			'type'    => 'switch',
			'title'   => esc_html__( 'Enable', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'mobile_tabs_enable' ),
		),
		array(
			'id'      => 'mobile_tabs',
			'type'    => 'sortable',
			'mode'    => 'toggle',
			'title'   => esc_html__( 'Tab Items', 'minimog' ),
			'options' => [
				'home'     => esc_attr__( 'Home Link', 'minimog' ),
				'shop'     => esc_attr__( 'Shop Link', 'minimog' ),
				'login'    => esc_attr__( 'Login Popup', 'minimog' ),
				'wishlist' => esc_attr__( 'Wishlist Page', 'minimog' ),
				'cart'     => esc_attr__( 'Cart Popup', 'minimog' ),
				'search'   => esc_attr__( 'Search Popup', 'minimog' ),
			],
			'default' => Minimog_Redux::get_default_setting( 'mobile_tabs' ),
		),
	),
) );
