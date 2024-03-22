<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

class Widget_Testimonial_Slideshow extends Static_Carousel {

	public function get_name() {
		return 'tm-testimonial-slideshow';
	}

	public function get_title() {
		return esc_html__( 'Testimonial Slideshow', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-testimonial-carousel';
	}

	public function get_keywords() {
		return [ 'testimonial', 'carousel' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_content_style_section();

		$this->add_box_style_section();

		parent::register_controls();

		$this->update_controls();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'        => esc_html__( 'Style', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '1',
			'options'      => [
				'1' => sprintf( esc_html__( 'Style %s', 'minimog' ), '01' ),
				'2' => sprintf( esc_html__( 'Style %s', 'minimog' ), '02' ),
			],
			'render_type'  => 'template',
			'prefix_class' => 'minimog-testimonial-slideshow-style-',
		] );

		$this->add_control( 'skin', [
			'label'        => esc_html__( 'Skin', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'light',
			'options'      => [
				'light' => esc_html__( 'Light', 'minimog' ),
				'dark'  => esc_html__( 'Dark', 'minimog' ),
			],
			'render_type'  => 'template',
			'prefix_class' => 'minimog-testimonial-slideshow-skin-',
		] );

		$this->add_control( 'layout', [
			'label'        => esc_html__( 'Layout', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => 'default',
			'options'      => [
				'default' => esc_html__( 'Default', 'minimog' ),
				'reverse' => esc_html__( 'Reverse', 'minimog' ),
			],
			'render_type'  => 'template',
			'prefix_class' => 'minimog-testimonial-slideshow-layout-',
		] );

		$this->add_control( 'cite_layout', [
			'label'        => esc_html__( 'Cite Layout', 'minimog' ),
			'label_block'  => false,
			'type'         => Controls_Manager::CHOOSE,
			'default'      => 'block',
			'options'      => [
				'block'  => [
					'title' => esc_html__( 'Default', 'minimog' ),
					'icon'  => 'eicon-editor-list-ul',
				],
				'inline' => [
					'title' => esc_html__( 'Inline', 'minimog' ),
					'icon'  => 'eicon-ellipsis-h',
				],
			],
			'prefix_class' => 'minimog-testimonial-slideshow-cite-layout-',
		] );

		$this->add_responsive_control( 'content_min_height', [
			'label'     => esc_html__( 'Min Height', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 1,
					'max' => 1600,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .minimog-testimonial-pagination'         => 'min-height: {{SIZE}}{{UNIT}};',
				'(mobile){{WRAPPER}} .minimog-testimonial-pagination' => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'content_width', [
			'label'       => esc_html__( 'Content Width', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'description' => esc_html__( 'This option is triggered if Stretch Section is Enable', 'minimog' ),
			'default'     => 'default',
			'options'     => [
				'default' => esc_html__( 'Default', 'minimog' ),
				'boxed'   => esc_html__( 'Boxed', 'minimog' ),
				'large'   => esc_html__( 'Large', 'minimog' ),
				'wide'    => esc_html__( 'Wide', 'minimog' ),
				'wider'   => esc_html__( 'Wider', 'minimog' ),
			],
			'separator'   => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_content_style_section() {
		$this->start_controls_section( 'content_style_section', [
			'label' => esc_html__( 'Main Content', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'main_content_padding', [
			'label'       => esc_html__( 'Padding', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'size_units'  => [ 'px', '%', 'em' ],
			'placeholder' => [
				'top'    => '100',
				'bottom' => '55',
				'left'   => '50',
				'right'  => '50',
			],
			'selectors'   => [
				'body:not(.rtl) {{WRAPPER}} .minimog-testimonial-slideshow .minimog-main-swiper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-testimonial-slideshow .minimog-main-swiper'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'main_content',
			'selector' => '{{WRAPPER}} .minimog-main-swiper',
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_max_width', [
			'label'      => esc_html__( 'Max Width', 'minimog' ),
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
				'{{WRAPPER}} .content-wrap' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .testimonial-main-content' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->add_control( 'box_text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'label_block'          => false,
			'type'                 => Controls_Manager::CHOOSE,
			'default'              => '',
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .content-wrap' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'       => esc_html__( 'Padding', 'minimog' ),
			'type'        => Controls_Manager::DIMENSIONS,
			'size_units'  => [ 'px', '%', 'em' ],
			'placeholder' => [
				'top'    => '',
				'bottom' => '',
				'left'   => '100',
				'right'  => '100',
			],
			'selectors'   => [
				'body:not(.rtl) {{WRAPPER}} .minimog-testimonial-slideshow .content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .minimog-testimonial-slideshow .content-wrap'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'star_rating_heading', [
			'label'     => esc_html__( 'Star Rating', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'star_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 1,
					'max' => 200,
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
				'{{WRAPPER}} .tm-star-rating .tm-star-full' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'star_empty_color', [
			'label'     => esc_html__( 'Empty', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-star-rating .tm-star-empty' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'star_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-star-rating' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-star-rating'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_responsive_control( 'title_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .title'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'text_heading', [
			'label'     => esc_html__( 'Text', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .text' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'text_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .text',
		] );

		$this->add_responsive_control( 'text_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .text'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'name_heading', [
			'label'     => esc_html__( 'Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'name_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .name' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'name_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .name',
		] );

		$this->add_responsive_control( 'name_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .name'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'position_heading', [
			'label'     => esc_html__( 'Position', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'position_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .position' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'position_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .position',
		] );

		$this->add_responsive_control( 'position_margin', [
			'label'      => esc_html__( 'Margin', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .position' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .position'       => 'margin: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
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
			'default' => 0,
		] );

		$this->update_control( 'slides', [
			'title_field' => '{{{ name }}}',
		] );

		// Inject
		$this->start_injection(
			[
				'type' => 'control',
				'at'   => 'after',
				'of'   => 'slides',
			]
		);
		$this->add_responsive_control(
			'background_size',
			[
				'label'     => _x( 'Background Size', 'Background Control', 'minimog' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'cover',
				'options'   => [
					'cover'   => _x( 'Cover', 'Background Control', 'minimog' ),
					'contain' => _x( 'Contain', 'Background Control', 'minimog' ),
					'auto'    => _x( 'Auto', 'Background Control', 'minimog' ),
				],
				'selectors' => [
					'{{WRAPPER}} .minimog-testimonial-pagination .slide-thumbnail' => 'background-size: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'background_position',
			[
				'label'     => esc_html__( 'Background Position', 'minimog' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''              => esc_html__( 'Default', 'minimog' ),
					'left top'      => esc_html__( 'Left Top', 'minimog' ),
					'left center'   => esc_html__( 'Left Center', 'minimog' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'minimog' ),
					'right top'     => esc_html__( 'Right Top', 'minimog' ),
					'right center'  => esc_html__( 'Right Center', 'minimog' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'minimog' ),
					'center top'    => esc_html__( 'Center Top', 'minimog' ),
					'center center' => esc_html__( 'Center Center', 'minimog' ),
					'center bottom' => esc_html__( 'Center Bottom', 'minimog' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .minimog-testimonial-pagination .slide-thumbnail' => 'background-position: {{VALUE}};',
				],

			]
		);
		$this->end_injection();
	}

	protected function add_repeater_controls( Repeater $repeater ) {
		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'label_block' => true,
			'type'        => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'content', [
			'label' => esc_html__( 'Content', 'minimog' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$repeater->add_control( 'image',
			[
				'label'     => esc_html__( 'Image', 'minimog' ),
				'type'      => Controls_Manager::MEDIA,
				'selectors' => [
					'{{WRAPPER}} .minimog-testimonial-pagination {{CURRENT_ITEM}} .slide-thumbnail' => 'background-image: url({{URL}})',
				],
			]
		);

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

		$repeater->add_control( 'rating', [
			'label' => esc_html__( 'Rating', 'minimog' ),
			'type'  => Controls_Manager::NUMBER,
			'min'   => 0,
			'max'   => 5,
			'step'  => 0.1,
		] );
	}

	protected function get_repeater_defaults() {
		return [
			[
				'title'    => 'The Testimonial Title #1',
				'content'  => 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				'name'     => 'Frankie Kao',
				'position' => 'Web Design',
				'image'    => [
					'url' => $this->get_image_placeholder_url( 1170, 1060 ),
				],
			],
			[
				'title'    => 'The Testimonial Title #2',
				'content'  => 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				'name'     => 'John DOE',
				'position' => 'Designer',
				'image'    => [
					'url' => $this->get_image_placeholder_url( 1170, 1060 ),
				],
			],
			[
				'title'    => 'The Testimonial Title #3',
				'content'  => 'I am slide content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
				'name'     => 'Jennifer C',
				'position' => 'Cofouder',
				'image'    => [
					'url' => $this->get_image_placeholder_url( 1170, 1060 ),
				],
			],
		];
	}

	protected function update_slider_settings( $settings, $slider_settings ) {
		$slider_settings['class'][] = 'minimog-main-swiper';

		if ( 'light' == $settings['skin'] ) {
			$slider_settings['class'][] = 'tm-swiper--light';
		}

		if ( 'boxed' === $settings['content_width'] ) {
			$content_width = 1170;
		} elseif ( 'large' === $settings['content_width'] ) {
			$content_width = 1410;
		} elseif ( 'wide' === $settings['content_width'] ) {
			$content_width = 1620;
		} elseif ( 'wider' === $settings['content_width'] ) {
			$content_width = 1720;
		} else {
			$content_width = 0;
		}

		$slider_settings['data-layout']        = $settings['layout'];
		$slider_settings['data-content-width'] = absint( $content_width );

		return $slider_settings;
	}

	protected function print_slide() {
		$item_key = $this->get_current_key();
		$this->add_render_attribute( $item_key . '-testimonial', [
			'class' => 'testimonial-item',
		] );
		?>
		<div <?php $this->print_attributes_string( $item_key . '-testimonial' ); ?>>
			<?php $this->print_testimonial_main_content(); ?>
		</div>
		<?php
	}

	private function print_testimonial_main_content() {
		?>
		<div class="testimonial-main-content">
			<div class="content-wrap">
				<?php $this->print_layout(); ?>
			</div>
		</div>
		<?php
	}

	private function print_testimonial_cite() {
		$slide = $this->get_current_slide();

		if ( empty( $slide['name'] ) && empty( $slide['position'] ) ) {
			return;
		}

		$html = '<div class="cite">';

		if ( ! empty( $slide['name'] ) ) {
			$html .= '<h6 class="name">' . $slide['name'] . '</h6>';
		}

		if ( ! empty( $slide['position'] ) ) {
			$html .= '<span class="position">' . $slide['position'] . '</span>';
		}
		$html .= '</div>';

		echo '' . $html;
	}

	private function print_layout() {
		$slide = $this->get_current_slide();
		?>

		<?php
		if ( ! empty( $slide['rating'] ) ):
			\Minimog_Templates::render_rating( $slide['rating'] );
		endif;
		?>

		<div class="content">
			<?php if ( ! empty( $slide['title'] ) ): ?>
				<h4 class="title"><?php echo esc_html( $slide['title'] ); ?></h4>
			<?php endif; ?>

			<div class="text">
				<?php echo wp_kses( $slide['content'], 'minimog-default' ); ?>
			</div>
		</div>

		<?php $this->print_testimonial_cite(); ?>
		<?php
	}

	/**
	 * Print Avatar Thumbs Slider
	 */
	protected function after_slider() {
		$settings = $this->get_active_settings();

		$testimonial_thumbs_template = '';

		foreach ( $settings['slides'] as $slide ) :
			if ( $slide['image']['url'] ) :
				$testimonial_thumbs_template .= '<div class="elementor-repeater-item-' . $slide['_id'] . ' swiper-slide"><div class="slide-thumbnail"></div></div>';
			endif;
		endforeach;

		if ( empty( $testimonial_thumbs_template ) ) {
			$this->add_render_attribute( '_wrapper', 'class', 'minimog-testimonial-slideshow--no-image' );

			return;
		}

		$paginate_settings = [
			'class'               => [ 'tm-swiper tm-slider-widget use-elementor-breakpoints minimog-testimonial-pagination minimog-thumbs-swiper' ],
			'data-items-desktop'  => '1',
			'data-gutter-desktop' => '0',
			'data-loop'           => '1',
			'data-effect'         => 'fade',
		];

		if ( ! empty( $settings['swiper_speed'] ) ) {
			$paginate_settings['data-speed'] = $settings['swiper_speed'];
		}

		$this->add_render_attribute( 'pagination', $paginate_settings );
		?>
		<div <?php $this->print_attributes_string( 'pagination' ); ?>>
			<div class="swiper-inner">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?php echo '' . $testimonial_thumbs_template; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'slide_show', 'class', [
			'minimog-testimonial-slideshow',
			'minimog-swiper-slideshow',
			'minimog-testimonial-slideshow--content-' . $settings['content_width'],
		] );
		?>

		<div <?php $this->print_attributes_string( 'slide_show' ) ?>>

			<?php
			$this->before_slider();

			$this->print_slider( $settings );

			$this->after_slider();
			?>

		</div>

		<?php
	}

	protected function get_image_placeholder_url( $width, $height ) {
		$src = 'https://via.placeholder.com/' . $width . 'x' . $height . '?text=' . esc_attr__( 'No+Image', 'minimog' );

		return $src;
	}
}
