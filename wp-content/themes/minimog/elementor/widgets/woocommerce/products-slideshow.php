<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

class Widget_Products_Slideshow extends Carousel_Base {

	private $loop_settings = array();

	public function get_name() {
		return 'tm-products-slideshow';
	}

	public function get_title() {
		return esc_html__( 'Products Slideshow', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-post-slider';
	}

	public function get_keywords() {
		return [ 'products', 'carousel', 'slideshow' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_products_section();

		$this->add_feature_image_style_section();

		$this->add_products_style_section();

		$this->add_caption_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'reverse', [
			'label'        => esc_html__( 'Reverse', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '',
		] );

		$this->add_control( 'style', [
			'label'       => esc_html__( 'Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => \Minimog_Woo::instance()->get_shop_loop_carousel_style_options(),
			'default'     => 'carousel-01',
			'render_type' => 'template',
			'separator'   => 'before',
		] );

		$this->add_control( 'caption_style', [
			'label'       => esc_html__( 'Caption Style', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => \Minimog_Woo::instance()->get_shop_loop_caption_style_options(),
			'default'     => '01',
			'render_type' => 'template',
		] );

		$this->add_control( 'show_price', [
			'label'        => esc_html__( 'Show Price', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_variation', [
			'label'        => esc_html__( 'Show Variation', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
		] );

		$this->add_control( 'show_category', [
			'label'        => esc_html__( 'Show Category', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_brand', [
			'label'        => esc_html__( 'Show Brand', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_rating', [
			'label'        => esc_html__( 'Show Rating', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_availability', [
			'label'        => esc_html__( 'Show Availability', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'show_stock_bar', [
			'label'        => esc_html__( 'Show Stock Bar', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
		] );

		$this->add_control( 'thumbnail_default_size', [
			'label'        => esc_html__( 'Use Default Thumbnail Size', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'default'      => '1',
			'return_value' => '1',
			'separator'    => 'before',
		] );

		$this->add_group_control( Group_Control_Image_Size::get_type(), [
			'name'      => 'thumbnail',
			'default'   => 'full',
			'condition' => [
				'thumbnail_default_size!' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function add_products_section() {
		$this->start_controls_section( 'product_section', [
			'label' => esc_html__( 'Products', 'minimog' ),
		] );

		$this->add_control( 'product_section_heading', [
			'label'       => esc_html__( 'Heading', 'minimog' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'Featured Products', 'minimog' ),
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'Title HTML Tag', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'h1'   => 'H1',
				'h2'   => 'H2',
				'h3'   => 'H3',
				'h4'   => 'H4',
				'h5'   => 'H5',
				'h6'   => 'H6',
				'div'  => 'div',
				'span' => 'span',
				'p'    => 'p',
			],
			'default' => 'h3',
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'product_id', [
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
		] );

		$repeater->add_control( 'feature_image', [
			'label' => esc_html__( 'Feature Image', 'minimog' ),
			'type'  => Controls_Manager::MEDIA,
		] );

		$this->add_control( 'products', [
			'label'   => esc_html__( 'Products', 'minimog' ),
			'type'    => Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => [],
		] );

		$this->end_controls_section();
	}

	private function add_feature_image_style_section() {
		$this->start_controls_section( 'feature_image_style_section', [
			'label' => esc_html__( 'Feature Image', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'feature_image_height', [
			'label'      => esc_html__( 'Min Height', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .minimog-thumbs-swiper' => 'min-height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'feature_image_width', [
			'label'       => esc_html__( 'Width', 'minimog' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => [
				'unit' => '%',
			],
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .tm-products-slideshow' => '--feature-image-w: {{SIZE}}{{UNIT}};',
			],
			'render_type' => 'template',
		] );

		$this->end_controls_section();
	}

	private function add_products_style_section() {
		$this->start_controls_section( 'products_style_section', [
			'label' => esc_html__( 'Products Section', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'products_section',
			'selector' => '{{WRAPPER}} .minimog-main-swiper-wrapper',
		] );

		$this->add_responsive_control( 'products_section_padding', [
			'label'       => esc_html__( 'Padding', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'size_units'  => [ 'px', '%', 'em' ],
			'placeholder' => [
				'top'    => '',
				'bottom' => '',
				'left'   => '',
				'right'  => '',
			],
			'selectors'   => [
				'body:not(.rtl) {{WRAPPER}} .minimog-main-swiper-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-main-swiper-wrapper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'render_type' => 'template',
		] );

		$this->add_responsive_control( 'products_section_width', [
			'label'       => esc_html__( 'Width', 'minimog' ),
			'type'        => Controls_Manager::SLIDER,
			'default'     => [
				'unit' => 'px',
			],
			'size_units'  => [ 'px', '%' ],
			'range'       => [
				'%'  => [
					'min' => 1,
					'max' => 100,
				],
				'px' => [
					'min' => 1,
					'max' => 1000,
				],
			],
			'selectors'   => [
				'{{WRAPPER}} .minimog-main-swiper' => 'width: {{SIZE}}{{UNIT}};',
			],
			'render_type' => 'template',
		] );

		$this->add_control( 'heading_style_hr', [
			'label'     => esc_html__( 'Heading', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'products_section_heading',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .product-section-heading',
		] );

		$this->add_control( 'products_section_heading_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-section-heading' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'products_section_heading_bottom_spacing', [
			'label'      => esc_html__( 'Bottom Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 1,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .product-section-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();
	}

	private function add_caption_style_section() {
		$this->start_controls_section( 'caption_style_section', [
			'label' => esc_html__( 'Product Caption', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'caption_text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .product-info' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'caption_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .product-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .product-info'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'caption_title_heading', [
			'label'     => esc_html__( 'Product Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .woocommerce-loop-product__title',
		] );

		$this->start_controls_tabs( 'caption_title_tabs' );

		$this->start_controls_tab( 'caption_title_normal_tab', [
			'label' => esc_html__( 'Normal', 'minimog' ),
		] );

		$this->add_control( 'caption_title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_title_hover_tab', [
			'label' => esc_html__( 'Hover', 'minimog' ),
		] );

		$this->add_control( 'caption_title_hover_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-loop-product__title a:hover' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'caption_price_heading', [
			'label'     => esc_html__( 'Product Price', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'caption_price_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .minimog-product div.price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-product div.price'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'caption_price_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .product-info .price, {{WRAPPER}} .product-info .amount',
		] );

		$this->start_controls_tabs( 'caption_price_tabs' );

		$this->start_controls_tab( 'caption_regular_price_tab', [
			'label' => esc_html__( 'Regular', 'minimog' ),
		] );

		$this->add_control( 'caption_regular_price_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .price'  => 'color: {{VALUE}};',
				'{{WRAPPER}} .product-info .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'caption_sale_price_tab', [
			'label' => esc_html__( 'Sale', 'minimog' ),
		] );

		$this->add_control( 'caption_sale_regular_price_color', [
			'label'     => esc_html__( 'Regular Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .price del'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .price del .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'caption_sale_price_color', [
			'label'     => esc_html__( 'Sale Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product.sale ins'         => 'color: {{VALUE}};',
				'{{WRAPPER}} .product.sale ins .amount' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'caption_category_heading', [
			'label'     => esc_html__( 'Product Category', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_responsive_control( 'caption_category_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .loop-product-category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .loop-product-category'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'show_category' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_category_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .product-info .loop-product-category a',
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_category_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .loop-product-category a' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_category_hover_color', [
			'label'     => esc_html__( 'Hover Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .product-info .loop-product-category a:hover' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_category' => '1',
			],
		] );

		$this->add_control( 'caption_availability_heading', [
			'label'     => esc_html__( 'Product Availability', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->add_responsive_control( 'caption_availability_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .loop-product-availability' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .loop-product-availability'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
			'condition'  => [
				'show_availability' => '1',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'caption_availability_typography',
			'label'     => esc_html__( 'Typography', 'minimog' ),
			'selector'  => '{{WRAPPER}} .loop-product-availability',
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->add_control( 'caption_availability_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .loop-product-availability' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_availability' => '1',
			],
		] );

		$this->end_controls_section();
	}

	private function update_controls() {
		$this->remove_control( 'swiper_centered' );
		$this->remove_control( 'swiper_centered_highlight' );
		$this->remove_control( 'swiper_free_mode' );
		$this->remove_control( 'swiper_inner_heading' );
		$this->remove_control( 'swiper_inner_margin' );
		$this->remove_control( 'swiper_container_heading' );
		$this->remove_control( 'swiper_container_padding' );
		$this->remove_control( 'swiper_content_alignment_heading' );
		$this->remove_control( 'swiper_content_horizontal_align' );
		$this->remove_control( 'swiper_content_vertical_align' );
		$this->remove_control( 'swiper_slides_width' );
		$this->remove_control( 'swiper_slides_max_width' );

		$this->update_responsive_control( 'swiper_items', [
			'type'                 => Controls_Manager::HIDDEN,
			'default'              => 1,
			'tablet_default'       => 1,
			'tablet_extra_default' => 1,
			'mobile_extra_default' => 1,
			'mobile_default'       => 1,
		] );

		$this->update_responsive_control( 'swiper_items_per_group', [
			'type'    => Controls_Manager::HIDDEN,
			'default' => 1,
		] );

		$this->update_responsive_control( 'swiper_gutter', [
			'type'    => Controls_Manager::HIDDEN,
			'default' => 30,
		] );
	}

	protected function print_slides( array $settings ) {
		$products    = $settings['products'];
		$product_ids = [];

		if ( empty( $products ) ) {
			return;
		}

		foreach ( $products as $product ) {
			$product_ids[] = $product['product_id'];
		}

		if ( empty( $product_ids ) ) {
			return;
		}

		$query_args = [
			'post_type'      => 'product',
			'posts_per_page' => count( $settings['products'] ),
			'post_status'    => 'publish',
			'post__in'       => $product_ids,
			'orderby'        => 'post__in',
		];

		$query = new \WP_Query( $query_args );

		?>
		<?php if ( $query->have_posts() ) : ?>

			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<?php $this->print_slide( $settings ); ?>
			<?php endwhile; ?>

		<?php endif;
		wp_reset_postdata();
	}

	protected function print_slide( array $settings ) {
		minimog_get_wc_template_part( 'content-product', $settings['style'], [
			'settings' => $this->loop_settings,
		] );
	}

	protected function print_images( array $settings ) {
		$products = $settings['products'];

		if ( empty( $products ) ) {
			return;
		}

		$key = 'images_' . $this->get_slider_key();

		$images_settings = [
			'class'               => [ 'tm-swiper tm-slider-widget use-elementor-breakpoints minimog-thumbs-swiper' ],
			'data-items-desktop'  => '1',
			'data-gutter-desktop' => '0',
			'data-effect'         => 'fade',
		];

		if ( ! empty( $settings['swiper_speed'] ) ) {
			$images_settings['data-speed'] = $settings['swiper_speed'];
		}

		if ( ! empty( $settings['swiper_loop'] ) && 'yes' === $settings['swiper_loop'] ) {
			$images_settings['data-loop'] = '1';
		}

		$this->add_render_attribute( $key, $images_settings );

		?>
		<div <?php $this->print_attributes_string( $key ); ?>>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php foreach ( $products as $product ) : ?>
							<?php $this->print_image( $product ); ?>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	protected function print_image( $product ) {
		$_product = wc_get_product( $product['product_id'] );

		if ( empty( $_product ) ) {
			return;
		}

		$image_url = '';

		$gallery_ids = $_product->get_gallery_image_ids();
		$image_id    = ! empty( $gallery_ids ) ? $gallery_ids[0] : $_product->get_image_id();

		if ( ! empty( $product['feature_image']['url'] ) ) {
			$image_url = $product['feature_image']['url'];
		} elseif ( $image_id ) {
			$image_url = wp_get_attachment_url( $image_id );
		}

		$key               = $product['_id'];
		$wrapper_key       = 'slide_wrapper_' . $key;
		$product_image_key = 'product_image_wrapper_' . $key;

		$this->add_render_attribute( $wrapper_key, 'class', [
			'swiper-slide',
		] );

		$this->add_render_attribute( $product_image_key, 'class', 'product-feature-image' );

		if ( ! empty( $image_url ) ) {
			if ( $this->has_lazy_loading() ) {
				$this->add_render_attribute( $product_image_key, [
					'class'    => 'll-background ll-background-unload',
					'data-src' => $image_url,
				] );
			} else {
				$this->add_render_attribute( $product_image_key, [
					'style' => "background-image:url($image_url)",
				] );
			}
		}

		?>
		<div <?php $this->print_attributes_string( $wrapper_key ) ?>>
			<div <?php $this->print_attributes_string( $product_image_key ) ?>></div>
		</div>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		// Loop Settings
		$style         = ! empty( $settings['style'] ) ? $settings['style'] : 'carousel-01';
		$caption_style = ! empty( $settings['caption_style'] ) ? $settings['caption_style'] : '01';

		$classes = [
			str_replace( 'carousel-', 'group-style-', $style ),
			'minimog-product',
			'style-' . $style,
			'caption-style-' . $caption_style,
			'minimog-main-swiper',
		];

		$this->add_render_attribute( $this->get_slider_key(), 'class', $classes );

		$this->loop_settings = [
			'layout'            => 'slider',
			'style'             => $style,
			'caption_style'     => $caption_style,
			'show_price'        => ! empty( $settings['show_price'] ) ? 1 : 0,
			'show_variation'    => ! empty( $settings['show_variation'] ) ? 1 : 0,
			'show_category'     => ! empty( $settings['show_category'] ) ? 1 : 0,
			'show_brand'        => ! empty( $settings['show_brand'] ) ? 1 : 0,
			'show_rating'       => ! empty( $settings['show_rating'] ) ? 1 : 0,
			'show_availability' => ! empty( $settings['show_availability'] ) ? 1 : 0,
			'show_stock_bar'    => ! empty( $settings['show_stock_bar'] ) ? 1 : 0,
		];

		if ( isset( $settings['thumbnail_default_size'] ) && '1' !== $settings['thumbnail_default_size'] ) {
			$this->loop_settings['thumbnail_size'] = \Minimog_Image::elementor_parse_image_size( $settings );
		} // End Loop Settings

		$this->add_render_attribute( 'slide_show_wrapper', 'class', [
			'tm-products-slideshow',
			'minimog-swiper-slideshow',
		] );

		if ( '1' === $settings['reverse'] ) {
			$this->add_render_attribute( 'slide_show_wrapper', 'class', [
				'tm-products-slideshow--reverse',
			] );
		}

		$this->add_render_attribute( 'main_slider_wrapper', 'class', [
			'minimog-main-swiper-wrapper',
		] );
		?>

		<div <?php $this->print_attributes_string( 'slide_show_wrapper' ) ?>>

			<?php $this->print_images( $settings ); ?>

			<div <?php $this->print_attributes_string( 'main_slider_wrapper' ) ?>>
				<?php $this->print_heading( $settings ); ?>
				<?php $this->print_slider( $settings ); ?>
			</div>
		</div>

		<?php
	}

	protected function print_heading( array $settings ) {
		if ( empty( $settings['product_section_heading'] ) ) {
			return;
		}

		$this->add_render_attribute( 'product_section_heading', 'class', 'product-section-heading' );

		printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'product_section_heading' ), $settings['product_section_heading'] );
	}
}
