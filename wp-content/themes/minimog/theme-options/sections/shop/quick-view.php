<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Quick View', 'minimog' ),
	'id'         => 'quick_view',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'shop_quick_view_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Quick View', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Disable', 'minimog' ),
				'1' => esc_html__( 'Enable', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'shop_quick_view_enable' ),
		),
		array(
			'id'      => 'shop_quick_view_product_description',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Product Description', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'shop_quick_view_product_description' ),
		),
		array(
			'id'      => 'shop_quick_view_product_badges',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Product Badges', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'shop_quick_view_product_badges' ),
		),
		array(
			'id'      => 'shop_quick_view_product_meta',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Product Meta', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'shop_quick_view_product_meta' ),
		),
	),
) );
