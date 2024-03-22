<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Product Quantity', 'minimog' ),
	'id'         => 'product_quantity',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'product_quantity_type',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Type', 'minimog' ),
			'options' => array(
				'input'  => esc_html__( 'Input (Default)', 'minimog' ),
				'select' => esc_html__( 'Select', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'product_quantity_type' ),
		),
		array(
			'id'          => 'product_quantity_ranges',
			'type'        => 'textarea',
			'title'       => esc_html__( 'Values', 'minimog' ),
			'description' => esc_html__( 'These values will be used for select type. Enter each value in one line and can use the range e.g "1-5".', 'minimog' ),
		),
	),
) );
