<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

class Widget_Testimonial_Grid extends Base {

	private $current_item_key;
	private $current_item;

	protected function get_current_key() {
		return $this->current_item_key;
	}

	protected function get_current_item() {
		return $this->current_item;
	}

	public function get_name() {
		return 'tm-testimonial-grid';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Grid', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-posts-grid';
	}

	public function get_keywords() {
		return [ 'testimonial', 'grid' ];
	}

	public function get_script_depends() {
		return [ 'minimog-group-widget-grid' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_content_section();

		$this->add_grid_section();

		$this->add_box_style_section();

		$this->add_image_style_section();

		$this->add_content_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '01',
			'options' => [
				'01' => '01',
				'02' => '02',
			],
		] );

		$this->end_controls_section();
	}

	private function add_grid_section() {
		$this->start_controls_section( 'grid_options_section', [
			'label' => esc_html__( 'Grid Options', 'minimog' ),
		] );

		$this->add_responsive_control( 'grid_columns', [
			'label'          => esc_html__( 'Columns', 'minimog' ),
			'type'           => Controls_Manager::NUMBER,
			'min'            => 1,
			'max'            => 12,
			'step'           => 1,
			'default'        => 3,
			'tablet_default' => 2,
			'mobile_default' => 1,
		] );

		$this->add_responsive_control( 'grid_gutter', [
			'label'   => esc_html__( 'Gutter', 'minimog' ),
			'type'    => Controls_Manager::NUMBER,
			'min'     => 0,
			'max'     => 200,
			'step'    => 1,
			'default' => 30,
		] );

		$this->end_controls_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'content_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'rating', [
			'label' => esc_html__( 'Rating', 'minimog' ),
			'type'  => Controls_Manager::NUMBER,
			'min'   => 0,
			'max'   => 5,
			'step'  => 0.1,
		] );

		$repeater->add_control( 'content', [
			'label' => esc_html__( 'Content', 'minimog' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'image', [
			'label' => esc_html__( 'Avatar', 'minimog' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$repeater->add_control( 'name', [
			'label'   => esc_html__( 'Name', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'John Doe', 'minimog' ),
		] );

		$repeater->add_control( 'position', [
			'label'   => esc_html__( 'Position', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'CEO', 'minimog' ),
		] );

		$repeater->add_control( 'product_id', [
			'label'        => esc_html__( 'Choose Product', 'minimog' ),
			'description'  => esc_html__( 'This option just works if Layout is 01', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'label_block'  => true,
			'multiple'     => false,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_POST,
				'query'  => [
					'post_type' => 'product',
				],
			],
		] );

		$placeholder_image_src = Utils::get_placeholder_image_src();

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'content'  => 'I am content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'name'     => 'Frankie Kao',
					'position' => 'Web Design',
					'image'    => [ 'url' => $placeholder_image_src ],
				],
				[
					'content'  => 'I am content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'name'     => 'Frankie Kao',
					'position' => 'Web Design',
					'image'    => [ 'url' => $placeholder_image_src ],
				],
				[
					'content'  => 'I am content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
					'name'     => 'Frankie Kao',
					'position' => 'Web Design',
					'image'    => [ 'url' => $placeholder_image_src ],
				],
			],
			'separator'   => 'after',
			'title_field' => '{{{ name }}}',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'    => 'image_size',
			'default' => 'full',
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'item_style_section', [
			'label' => esc_html__( 'Item', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .tm-testimonial' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-testimonial__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-testimonial__wrapper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .tm-testimonial__wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box',
			'selector' => '{{WRAPPER}} .tm-testimonial__wrapper',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'background',
			'selector' => '{{WRAPPER}} .tm-testimonial__wrapper',
		] );

		$this->end_controls_section();
	}

	protected function add_image_style_section() {
		$this->start_controls_section( 'image_style_section', [
			'label' => esc_html__( 'Image', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'size_units' => [ 'px', '%' ],
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
				'%' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-testimonial__image img' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'image_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-testimonial__image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-testimonial__image'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Content', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		// Star Rating
		$this->add_control( 'star_rating_heading', [
			'label' => esc_html__( 'Star Rating', 'minimog' ),
			'type'  => Controls_Manager::HEADING,
		] );

		$this->add_responsive_control( 'star_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'star_full_color', [
			'label'     => esc_html__( 'Fill', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--fill: {{VALUE}}; --half: {{VALUE}};',
			],
		] );

		$this->add_control( 'star_empty_color', [
			'label'     => esc_html__( 'Empty', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => '--empty: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'star_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		// Content
		$this->add_control( 'text_heading', [
			'label'     => esc_html__( 'Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_typography',
			'selector' => '{{WRAPPER}} .tm-testimonial__text',
		] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-testimonial__text' => 'color: {{VALUE}};',
			],
		] );

		// Name
		$this->add_control( 'name_heading', [
			'label'     => esc_html__( 'Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'name_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-testimonial__name',
		] );

		$this->add_control( 'name_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-testimonial__name' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'cite_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-testimonial--layout-01 .tm-testimonial__cite' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .tm-testimonial--layout-02 .tm-testimonial__cite' => 'margin-top: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .tm-testimonial--layout-03 .tm-testimonial__cite' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		// Position
		$this->add_control( 'position_heading', [
			'label'     => esc_html__( 'Position', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'position_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-testimonial__position',
		] );

		$this->add_control( 'position_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-testimonial__position' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'position_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors' => [
				'body:not(.rtl) {{WRAPPER}} .tm-testimonial__position' => 'margin-left: {{SIZE}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-testimonial__position'       => 'margin-right: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_footer_style_section() {
		$this->start_controls_section( 'footer_style_section', [
			'label'     => esc_html__( 'Footer', 'minimog' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [
				'layout' => '01',
			],
		] );

		$this->add_responsive_control( 'footer_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-testimonial__footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-testimonial__footer'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'footer_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-testimonial__footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-testimonial__footer'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'product_image_style_heading', [
			'label'     => esc_html__( 'Product Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'product_image_rounded', [
			'label'      => esc_html__( 'Rounded', 'minimog' ),
			'size_units' => [ 'px', '%' ],
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
				'%' => [
					'min' => 0,
					'max' => 100,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .product-thumb img' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'product_image_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .product-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .product-thumb'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'product_title_style_heading', [
			'label'     => esc_html__( 'Product Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'product_title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .product-title',
		] );

		$this->start_controls_tabs( 'product_title_skin' );

		$this->start_controls_tab( 'product_title_color_normal', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'product_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .testimonial-product' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_title_color_hover', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'product_title_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .testimonial-product:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', [
			'class' => [
				'minimog-grid-wrapper',
				'tm-testimonial-grid',
				'tm-testimonial-grid--layout-' . $settings['layout'],
			],
		] );

		$this->add_render_attribute( 'content-wrapper', 'class', 'minimog-grid lazy-grid' );

		$grid_options = $this->get_grid_options( $settings );

		$this->add_render_attribute( 'wrapper', 'data-grid', wp_json_encode( $grid_options ) );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div <?php $this->print_attributes_string( 'content-wrapper' ); ?>>
				<div class="grid-sizer"></div>
				<?php foreach ( $settings['items'] as $item ) : ?>
					<?php
					$item_id                = $item['_id'];
					$this->current_item     = $item;
					$this->current_item_key = 'item_' . $item_id;

					$this->add_render_attribute( $this->get_current_key(), [
						'class' => [
							'grid-item',
							'elementor-repeater-item-' . $item_id,
							'minimog-box',
							'tm-testimonial',
							'tm-testimonial--layout-' . $settings['layout'],
						],
					] );
					?>
					<div <?php $this->print_attributes_string( $this->get_current_key() ); ?>>
						<div class="tm-testimonial__wrapper">
							<?php
								switch( $settings['layout'] ){
									case '02' :
									case '03' :
										$this->print_layout_02();
										break;
									default : // Style 01
										$this->print_layout_01();
										break;
								}
								?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function get_grid_options( array $settings ) {
		$grid_options = [
			'type' => 'grid',
		];

		$columns_settings = $this->parse_responsive_settings( $settings, 'grid_columns', 'columns' );
		$gutter_settings  = $this->parse_responsive_settings( $settings, 'grid_gutter', 'gutter' );

		$grid_options += $columns_settings + $gutter_settings;

		return $grid_options;
	}

	protected function print_star() {
		$slide = $this->get_current_item();

		if ( empty( $slide['rating'] ) ) {
			return;
		}
		\Minimog_Templates::render_rating( $slide['rating'] );
	}

	protected function print_text() {
		$slide = $this->get_current_item();

		if ( empty( $slide['content'] ) ) {
			return;
		}

		?>
		<div class="tm-testimonial__text">
			<?php echo wp_kses( $slide['content'], 'minimog-default' ); ?>
		</div>
		<?php
	}

	protected function print_cite() {
		$slide = $this->get_current_item();

		if ( empty( $slide['name'] ) && empty( $settings['position'] ) ) {
			return;
		}

		?>
		<div class="tm-testimonial__cite">
			<?php if ( ! empty( $slide['name'] ) ) : ?>
				<h4 class="tm-testimonial__name"><?php echo esc_html( $slide['name'] ); ?></h4>
			<?php endif; ?>

			<?php if ( ! empty( $slide['position'] ) ) : ?>
				<span class="tm-testimonial__position"><?php echo esc_html( $slide['position'] ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	private function print_avatar() {
		$settings = $this->get_settings_for_display();
		$slide    = $this->get_current_item();

		if ( empty( $slide['image']['url'] ) ) {
			return;
		}
		?>
		<div class="minimog-image tm-testimonial__image">
			<?php echo \Minimog_Image::get_elementor_attachment( [
				'settings'       => $slide,
				'size_settings'  => $settings,
				'image_size_key' => 'image_size',
			] ); ?>
		</div>
		<?php
	}

	protected function print_product() {
		$slide = $this->get_current_item();

		if ( ! function_exists( 'wc_get_product' ) || empty( $slide['product_id'] ) ) {
			return false;
		}

		$product = wc_get_product( $slide['product_id'] );

		if ( ! $product ) {
			return false;
		}

		$size = '40x53';

		return sprintf( 
			'<a href="%s" class="product testimonial-product">
				<span class="product-thumb">%s</span>
				<span class="product-title">%s</span>
			</a>',
			esc_url( $product->get_permalink() ),
			\Minimog_Woo::instance()->get_product_image( $product, $size ),
			esc_html( $product->get_name() )
		);
	}

	protected function print_layout_01() {
		?>
		<div class="tm-testimonial__content">
			<div class="tm-testimonial__summary">
				<?php $this->print_cite(); ?>
				<?php $this->print_star(); ?>
				<?php $this->print_text(); ?>
			</div>

			<?php $this->print_avatar(); ?>
		</div>

		<?php if ( $this->print_product() ) : ?>
			<div class="tm-testimonial__footer"><?php echo '' . $this->print_product(); ?></div>
		<?php endif; ?>
		
		<?php
	}

	// Style 02
	protected function print_layout_02() {
		?>
		<div class="tm-testimonial__content">
			<?php $this->print_avatar(); ?>

			<div class="tm-testimonial__summary">
				<?php $this->print_star(); ?>
				<?php $this->print_text(); ?>
				<?php $this->print_cite(); ?>
			</div>
		</div>
		<?php
	}
}
