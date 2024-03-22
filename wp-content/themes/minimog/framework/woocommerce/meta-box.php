<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Meta_Box {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'minimog/meta_box/page_options', [ $this, 'product_meta_box' ], 10, 2 );
	}

	/**
	 * @param array $meta_boxes
	 * @param array $general_options
	 *
	 * @return array
	 */
	public function product_meta_box( $meta_boxes, $general_options ) {
		$meta_boxes[] = array(
			'id'         => 'insight_product_options',
			'title'      => esc_html__( 'Page Options', 'minimog' ),
			'post_types' => array( 'product' ),
			'context'    => 'normal',
			'priority'   => 'high',
			'fields'     => array(
				array(
					'type'  => 'tabpanel',
					'items' => array_merge( array(
						array(
							'title'  => esc_html__( 'Product', 'minimog' ),
							'fields' => array(
								array(
									'id'      => 'single_product_site_layout',
									'type'    => 'select',
									'title'   => esc_html__( 'Site Layout', 'minimog' ),
									'default' => '',
									'options' => \Minimog_Site_Layout::instance()->get_container_wide_list( true ),
								),
								array(
									'id'      => 'single_product_summary_layout',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Summary Layout', 'minimog' ),
									'default' => '',
									'options' => \Minimog_Site_Layout::instance()->get_container_wide_list( true ),
								),
								array(
									'id'      => 'single_product_images_style',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Images Style', 'minimog' ),
									'default' => '',
									'options' => array(
										''         => esc_html__( 'Default', 'minimog' ),
										'slider'   => esc_html__( 'Slider', 'minimog' ),
										'carousel' => esc_html__( 'Carousel', 'minimog' ),
										'grid'     => esc_html__( 'Grid', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_images_wide',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Images Wide', 'minimog' ),
									'default' => '',
									'options' => array(
										''         => esc_html__( 'Default', 'minimog' ),
										'narrow'   => esc_html__( 'Narrow', 'minimog' ),
										'normal'   => esc_html__( 'Normal', 'minimog' ),
										'extended' => esc_html__( 'Extended', 'minimog' ),
										'wide'     => esc_html__( 'Wide', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_images_offset',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Images Offset', 'minimog' ),
									'default' => '',
									'options' => array(
										''   => esc_html__( 'Default', 'minimog' ),
										'0'  => esc_html__( 'No Offset', 'minimog' ),
										'20' => sprintf( esc_html__( 'Offset %s', 'minimog' ), '20px' ),
										'30' => sprintf( esc_html__( 'Offset %s', 'minimog' ), '30px' ),
									),
								),
								array(
									'id'      => 'single_product_summary_offset',
									'type'    => 'select',
									'title'   => esc_html__( 'Product Summary Offset', 'minimog' ),
									'default' => '',
									'options' => array(
										''   => esc_html__( 'Default', 'minimog' ),
										'0'  => esc_html__( 'No Offset', 'minimog' ),
										'20' => sprintf( esc_html__( 'Offset %s', 'minimog' ), '20px' ),
										'30' => sprintf( esc_html__( 'Offset %s', 'minimog' ), '30px' ),
									),
								),
								array(
									'id'      => 'single_product_sticky_enable',
									'type'    => 'select',
									'title'   => esc_html__( 'Sticky Images & Summary', 'minimog' ),
									'default' => '',
									'options' => array(
										''  => esc_html__( 'Default', 'minimog' ),
										'0' => esc_html__( 'Off', 'minimog' ),
										'1' => esc_html__( 'On', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_slider_vertical',
									'type'    => 'select',
									'title'   => esc_html__( 'Vertical Slider', 'minimog' ),
									'default' => '',
									'options' => array(
										''  => esc_html__( 'Default', 'minimog' ),
										'0' => esc_html__( 'Horizontal', 'minimog' ),
										'1' => esc_html__( 'Vertical', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_image_grid_alternating',
									'type'    => 'select',
									'title'   => esc_html__( 'Grid Alternating?', 'minimog' ),
									'default' => '',
									'options' => array(
										''   => esc_html__( 'Default', 'minimog' ),
										'0'  => esc_attr__( 'Disabled', 'minimog' ),
										'1'  => esc_attr__( 'Normal Alternating', 'minimog' ),
										'-1' => esc_attr__( 'Reverse Alternating', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_image_grid_lg_columns',
									'type'    => 'number',
									'title'   => esc_html__( 'Grid Columns', 'minimog' ),
									'default' => '',
									'min'     => 1,
									'max'     => 6,
								),
								array(
									'id'      => 'single_product_image_grid_lg_gutter',
									'type'    => 'number',
									'title'   => esc_html__( 'Grid Gutter', 'minimog' ),
									'default' => '',
									'min'     => 0,
								),
								array(
									'id'      => 'single_product_image_grid_md_columns',
									'type'    => 'number',
									'title'   => \Minimog_Helper::get_setting_md_label( esc_html__( 'Grid Columns', 'minimog' ) ),
									'desc'    => \Minimog_Helper::get_setting_md_tooltip( esc_html__( 'Grid Columns', 'minimog' ) ),
									'default' => '',
									'min'     => 1,
									'max'     => 6,
								),
								array(
									'id'      => 'single_product_image_grid_md_gutter',
									'type'    => 'number',
									'title'   => \Minimog_Helper::get_setting_md_label( esc_html__( 'Grid Gutter', 'minimog' ) ),
									'desc'    => \Minimog_Helper::get_setting_md_tooltip( esc_html__( 'Grid Gutter', 'minimog' ) ),
									'default' => '',
									'min'     => 0,
								),
								array(
									'id'      => 'single_product_image_grid_sm_columns',
									'type'    => 'number',
									'title'   => \Minimog_Helper::get_setting_sm_label( esc_html__( 'Grid Columns', 'minimog' ) ),
									'desc'    => \Minimog_Helper::get_setting_sm_tooltip( esc_html__( 'Grid Columns', 'minimog' ) ),
									'default' => '',
									'min'     => 1,
									'max'     => 6,
								),
								array(
									'id'      => 'single_product_image_grid_sm_gutter',
									'type'    => 'number',
									'title'   => \Minimog_Helper::get_setting_sm_label( esc_html__( 'Grid Gutter', 'minimog' ) ),
									'desc'    => \Minimog_Helper::get_setting_sm_tooltip( esc_html__( 'Grid Gutter', 'minimog' ) ),
									'default' => '',
									'min'     => 0,
								),
								array(
									'id'      => 'single_product_tabs_style',
									'type'    => 'select',
									'title'   => esc_html__( 'Tabs Style', 'minimog' ),
									'default' => '',
									'options' => array(
										''        => esc_html__( 'Default', 'minimog' ),
										'tabs'    => esc_html__( 'Tabs', 'minimog' ),
										'toggles' => esc_html__( 'Toggles', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_up_sells_enable',
									'type'    => 'select',
									'title'   => esc_html__( 'Show Up-sells Products', 'minimog' ),
									'default' => '',
									'options' => array(
										''  => esc_html__( 'Default', 'minimog' ),
										'0' => esc_html__( 'No', 'minimog' ),
										'1' => esc_html__( 'Yes', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_up_sells_position',
									'type'    => 'select',
									'title'   => esc_html__( 'Up-sells Products Position', 'minimog' ),
									'default' => '',
									'options' => array(
										''                       => esc_html__( 'Default', 'minimog' ),
										'in_linked_product_tabs' => esc_html__( 'In Linked Product Tabs', 'minimog' ),
										'below_product_tabs'     => esc_html__( 'Below Product Tabs', 'minimog' ),
										'below_product_images'   => esc_html__( 'Below Product Images', 'minimog' ),
										'below_product_summary'  => esc_html__( 'Below Product Summary', 'minimog' ),
									),
								),
								array(
									'id'      => 'single_product_up_sells_heading',
									'type'    => 'text',
									'title'   => esc_html__( 'Upsells Product Heading', 'minimog' ),
									'default' => '',
								),
							),
						),
					), $general_options ),
				),
			),
		);

		return $meta_boxes;
	}
}

Meta_Box::instance()->initialize();
