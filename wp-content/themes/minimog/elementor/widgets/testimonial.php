<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Testimonial extends Base {
	public function get_name() {
		return 'tm-testimonial';
	}

	public function get_title() {
		return __( 'Modern Testimonial', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-testimonial';
	}

	public function get_keywords() {
		return [ 'testimonial' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		$this->add_style_section();

		$this->add_image_style_section();

		$this->add_content_style_section();

		$this->add_footer_style_section();
	}

	protected function add_content_section() {
		$this->start_controls_section( 'testimonial_section', [
			'label' => esc_html__( 'Testimonial', 'minimog' ),
		] );

		$this->add_control( 'layout', [
			'label'   => esc_html__( 'Layout', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '01',
			'options' => [
				'01' => '01',
				'02' => '02',
				'03' => '03',
				'04' => '04',
			],
		] );

		$this->add_control( 'image_heading', [
			'label'     => esc_html__( 'Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'image', [
			'label'   => esc_html__( 'Choose Image', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => \Minimog_Templates::get_image_placeholder_url( 120, 120 ),
			],
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'image',
			// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
			'default'   => 'thumbnail',
			'separator' => 'none',
			'condition' => [
				'image[url]!' => '',
			],
		] );

		$this->add_control( 'content_heading', [
			'label'     => esc_html__( 'Content', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'rating', [
			'label' => esc_html__( 'Rating', 'minimog' ),
			'type'  => Controls_Manager::NUMBER,
			'min'   => 0,
			'max'   => 5,
			'step'  => 0.1,
		] );

		$this->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'condition'   => [
				'layout' => [ '04' ],
			],
		] );

		$this->add_control( 'content', [
			'label'   => esc_html__( 'Content', 'minimog' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'minimog' ),
		] );

		$this->add_control( 'name', [
			'label'   => esc_html__( 'Name', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'John Doe', 'minimog' ),
		] );

		$this->add_control( 'position', [
			'label'   => esc_html__( 'Position', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'default' => esc_html__( 'CEO', 'minimog' ),
		] );

		$this->add_control( 'product_heading', [
			'label'     => esc_html__( 'Product', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'layout' => '01',
			],
		] );

		$this->add_control( 'product_id', [
			'label'        => esc_html__( 'Choose Product', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'label_block'  => true,
			'multiple'     => false,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_POST,
				'query'  => [
					'post_type' => 'product',
				],
			],
			'condition'    => [
				'layout' => [ '01','04' ],
			],
		] );

		$this->end_controls_section();
	}

	protected function add_style_section() {
		$this->start_controls_section( 'testimonial_style_section', [
			'label' => esc_html__( 'Testimonial', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_max_width', [
			'label'      => esc_html__( 'Width', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-testimonial__wrapper' => 'width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'layout' => '03',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'left',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-testimonial' => 'display: flex; justify-content: {{VALUE}}',
			],
			'condition'  		   => [
				'layout' => '03',
			],
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

		// Title
		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .tm-testimonial__title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .tm-testimonial__title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'title_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-testimonial__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'{{WRAPPER}} .tm-testimonial--layout-04 .tm-testimonial__cite' => 'margin-top: {{SIZE}}{{UNIT}};',
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
				'layout' => [ '01', '04' ],
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

		$this->add_render_attribute( 'wrapper', 'class', [
			'tm-testimonial',
			'tm-testimonial--layout-' . $settings['layout'],
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="tm-testimonial__wrapper">
				<?php
				switch( $settings['layout'] ){
					case '02' :
					case '03' :
						$this->print_layout_02( $settings );
						break;
					case '04' :
						$this->print_layout_03( $settings );
						break;
					default : // Style 01
						$this->print_layout_01( $settings );
						break;
				}
				?>
			</div>
		</div>
		<?php
	}

	protected function print_star( array $settings ) {
		if ( empty( $settings['rating'] ) ) {
			return;
		}
		\Minimog_Templates::render_rating( $settings['rating'] );
	}

	protected function print_title( array $settings ) {
		if ( empty( $settings['title'] ) ) {
			return;
		}

		?>
		<h3 class="tm-testimonial__title">
			<?php echo esc_html( $settings['title'] ); ?>
		</h3>
		<?php
	}

	protected function print_text( array $settings ) {
		if ( empty( $settings['content'] ) ) {
			return;
		}

		?>
		<div class="tm-testimonial__text">
			<?php echo wp_kses( $settings['content'], 'minimog-default' ); ?>
		</div>
		<?php
	}

	protected function print_cite( array $settings ) {
		if ( empty( $settings['name'] ) && empty( $settings['position'] ) ) {
			return;
		}

		$this->add_render_attribute( 'name', 'class', 'tm-testimonial__name' );
		$this->add_render_attribute( 'position', 'class', 'tm-testimonial__position' );

		?>
		<div class="tm-testimonial__cite">
			<?php if ( ! empty( $settings['name'] ) ) : ?>
				<h4 <?php $this->print_render_attribute_string( 'name' ); ?>><?php echo esc_html( $settings['name'] ); ?></h4>
			<?php endif; ?>

			<?php if ( ! empty( $settings['position'] ) ) : ?>
				<span <?php $this->print_render_attribute_string( 'position' ); ?>><?php echo esc_html( $settings['position'] ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function print_avatar( array $settings ) {
		if ( empty( $settings['image']['url'] ) ) {
			return;
		}
		?>
		<div class="tm-testimonial__image">
			<?php
			echo \Minimog_Image::get_elementor_attachment( [
				'settings' => $settings,
			] );
			?>
		</div>
		<?php
	}

	protected function print_product( array $settings ) {
		if ( ! function_exists( 'wc_get_product' ) || empty( $settings['product_id'] ) ) {
			return false;
		}

		$product = wc_get_product( $settings['product_id'] );

		if ( ! $product ) {
			return false;
		}

		switch( $settings['layout'] ) {
			case '04' :
				$size = '50x50';
				break;
			default : // Style 01
				$size = '40x53';
				break;
		}

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

	protected function print_layout_01( array $settings ) {
		?>
		<div class="tm-testimonial__content">
			<div class="tm-testimonial__summary">
				<?php $this->print_cite( $settings ); ?>
				<?php $this->print_star( $settings ); ?>
				<?php $this->print_text( $settings ); ?>
			</div>

			<?php $this->print_avatar( $settings ); ?>
		</div>

		<?php if ( $this->print_product( $settings ) ) : ?>
			<div class="tm-testimonial__footer"><?php echo '' . $this->print_product( $settings ); ?></div>
		<?php endif; ?>
		
		<?php
	}

	// Style 02-03
	protected function print_layout_02( array $settings ) {
		?>
		<div class="tm-testimonial__content">
			<?php $this->print_avatar( $settings ); ?>

			<div class="tm-testimonial__summary">
				<?php $this->print_star( $settings ); ?>
				<?php $this->print_text( $settings ); ?>
				<?php $this->print_cite( $settings ); ?>
			</div>
		</div>
		<?php
	}

	// Style 04
	protected function print_layout_03( array $settings ) {
		?>
		<div class="tm-testimonial__content">
			<?php $this->print_avatar( $settings ); ?>

			<div class="tm-testimonial__summary">
				<?php $this->print_star( $settings ); ?>
				<?php $this->print_title( $settings ); ?>
				<?php $this->print_text( $settings ); ?>
				<?php $this->print_cite( $settings ); ?>
			</div>

			<?php if ( $this->print_product( $settings ) ) : ?>
				<div class="tm-testimonial__footer"><?php echo '' . $this->print_product( $settings ); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
