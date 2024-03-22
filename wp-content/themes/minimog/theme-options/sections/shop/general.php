<?php
$shop_badge_days_options = [
	'1',
	'2',
	'3',
	'4',
	'5',
	'6',
	'7',
	'8',
	'9',
	'10',
	'15',
	'20',
	'25',
	'30',
	'60',
	'90',
];

$day_options = [];

foreach ( $shop_badge_days_options as $day ) {
	$day_options[ $day ] = esc_html( sprintf( _n( '%s day', '%s days', intval( $day ), 'minimog' ), $day ) );
}

Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'General Settings', 'minimog' ),
	'id'         => 'shop_general',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'          => 'hide_icon_badges_on_empty',
			'type'        => 'button_set',
			'title'       => esc_html__( 'Hide Icon Badges On Empty', 'minimog' ),
			'description' => 'Hide icon\'s badge on header, search popup, mobile tabs... when empty',
			'options'     => array(
				''  => esc_html__( 'No', 'minimog' ),
				'1' => esc_html__( 'Yes', 'minimog' ),
			),
		),
		array(
			'id'       => 'section_shop_general_product_badge',
			'type'     => 'tm_heading',
			'collapse' => 'show',
			'title'    => esc_html__( 'Product Badges', 'minimog' ),
			'indent'   => true,
		),
		array(
			'id'      => 'shop_badges_style',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Badges Style', 'minimog' ),
			'options' => array(
				'label'                   => 'Rounded Label',
				'label-02'                => 'Rounded Label 02',
				'semi-round-border-label' => 'Semi Rounded & Border Label',
				'square-label'            => 'Square Label',
			),
			'default' => Minimog_Redux::get_default_setting( 'shop_badges_style' ),
		),
		array(
			'id'          => 'shop_badge_sale',
			'type'        => 'button_set',
			'title'       => 'Sale Badge',
			'description' => 'Show a "Sale" label or "-20%" label when product on sale.',
			'options'     => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default'     => '1',
			'class'       => 'redux-row-field-parent redux-row-field-first-parent',
		),
		array(
			'id'          => 'shop_badge_sale_text_color',
			'type'        => 'color',
			'title'       => 'Sale Badge Text Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_sale', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_sale_background_color',
			'type'        => 'color',
			'title'       => 'Sale Badge Background Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_sale', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_new',
			'type'        => 'button_set',
			'title'       => 'New Badge',
			'description' => 'If the product was published within the newness time frame display the new badge.',
			'options'     => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default'     => '1',
			'class'       => 'redux-row-field-parent',
		),
		array(
			'id'       => 'shop_badge_new_range',
			'type'     => 'select',
			'title'    => 'New Arrivals Range',
			'options'  => $day_options,
			'default'  => '7',
			'required' => array(
				[ 'shop_badge_new', '=', '1' ],
			),
			'class'    => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_new_text_color',
			'type'        => 'color',
			'title'       => 'New Badge Text Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_new', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_new_background_color',
			'type'        => 'color',
			'title'       => 'New Badge Background Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_new', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_hot',
			'type'        => 'button_set',
			'title'       => 'Hot Badge',
			'description' => 'Show a "Hot" label when product set as featured.',
			'options'     => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default'     => '0',
			'class'       => 'redux-row-field-parent',
		),
		array(
			'id'          => 'shop_badge_hot_text_color',
			'type'        => 'color',
			'title'       => 'Hot Badge Text Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_hot', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_hot_background_color',
			'type'        => 'color',
			'title'       => 'Hot Badge Background Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_hot', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_best_selling',
			'type'        => 'button_set',
			'title'       => 'Best Selling Badge',
			'description' => 'Show a "Best Seller" label when product in of best selling list.',
			'options'     => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default'     => '0',
			'class'       => 'redux-row-field-parent',
		),
		array(
			'id'            => 'shop_best_selling_list_number',
			'title'         => esc_html__( 'Best Selling Number Items', 'minimog' ),
			'description'   => esc_html__( 'How many max of products do you want to add to best selling list', 'minimog' ),
			'type'          => 'slider',
			'default'       => 10,
			'min'           => 1,
			'max'           => 100,
			'step'          => 1,
			'display_value' => 'text',
			'required'      => array(
				[ 'shop_badge_best_selling', '=', '1' ],
			),
			'class'         => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_best_selling_text_color',
			'type'        => 'color',
			'title'       => 'Best Selling Badge Text Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_best_selling', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),
		array(
			'id'          => 'shop_badge_best_selling_background_color',
			'type'        => 'color',
			'title'       => 'Best Selling Badge Background Color',
			'default'     => '',
			'color_alpha' => true,
			'required'    => array(
				[ 'shop_badge_best_selling', '=', '1' ],
			),
			'class'       => 'redux-row-field-child',
		),

		array(
			'id'       => 'section_shop_general_product_price',
			'type'     => 'tm_heading',
			'collapse' => 'show',
			'title'    => esc_html__( 'Product Price', 'minimog' ),
			'indent'   => true,
		),
		array(
			'id'          => 'price_regular_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Regular Price', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'price_old_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Old Price', 'minimog' ),
			'color_alpha' => true,
		),
		array(
			'id'          => 'price_sale_color',
			'type'        => 'color',
			'title'       => esc_html__( 'Sale Price', 'minimog' ),
			'color_alpha' => true,
		),
	),
) );
