<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

class Widget_Client_Box extends Base {

	public function get_name() {
		return 'tm-client-box';
	}

	public function get_title() {
		return esc_html__( 'Client Box', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-icon-box';
	}

	public function get_keywords() {
		return [ 'client box', 'box client', 'client', 'box' ];
	}

	protected function register_controls() {
		$this->add_content_section();

		// Style
		$this->add_style_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'client_box_section', [
			'label' => esc_html__( 'Client Box', 'minimog' ),
		] );

		$this->add_control( 'image', [
			'label'   => esc_html__( 'Logo', 'minimog' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		] );

		$this->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'placeholder' => esc_html__( 'Enter your title', 'minimog' ),
			'label_block' => true,
		] );

		$this->add_control( 'title_size', [
			'label'   => esc_html__( 'HTML Tag', 'minimog' ),
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

		$this->add_control( 'review', [
			'label'       => esc_html__( 'Review', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis.', 'minimog' ),
			'placeholder' => esc_html__( 'Enter your description', 'minimog' ),
		] );

		$this->add_control( 'reverse_content', [
			'label'     => esc_html__( 'Reverse Content', 'minimog' ),
			'type'      => Controls_Manager::SWITCHER,
			'default'   => '',
			'label_on'  => esc_html__( 'Yes', 'minimog' ),
			'label_off' => esc_html__( 'No', 'minimog' ),
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	/**
	 * Style Section
	 *
	 * @return void
	 */
	private function add_style_section() {
		$this->start_controls_section( 'client_box_style_section', [
			'label' => esc_html__( 'Client Box', 'minimog' ),
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
				'{{WRAPPER}} .tm-client-box__wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'selectors'            => [
				'{{WRAPPER}} .tm-client-box' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => '',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-client-box' => 'justify-content: {{VALUE}}',
			],
		] );

		// Image
		$this->add_control( 'image_style_heading', [
			'label'     => esc_html__( 'Image', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'image_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-client-box__content'                                  => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: 0;',
				'{{WRAPPER}} .tm-client-box--content-reversed .tm-client-box__content' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
			],
		] );

		// Title
		$this->add_control( 'title_style_heading', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'selector' => '{{WRAPPER}} .tm-client-box__title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-client-box__title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'title_spacing', [
			'label'      => esc_html__( 'Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [],
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-client-box__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		// Review
		$this->add_control( 'review_style_heading', [
			'label'     => esc_html__( 'Review', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'review_typography',
			'selector' => '{{WRAPPER}} .tm-client-box__review',
		] );

		$this->add_control( 'review_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-client-box__review' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'box', 'class', 'tm-client-box' );

		if ( 'yes' === $settings['reverse_content'] ) {
			$this->add_render_attribute( 'box', 'class', 'tm-client-box--content-reversed' );
		}

		$this->add_render_attribute( 'title', 'class', 'tm-client-box__title' );
		$this->add_render_attribute( 'review', 'class', 'tm-client-box__review' );

		$this->add_inline_editing_attributes( 'title', 'none' );
		$this->add_inline_editing_attributes( 'review', 'basic' );
		?>

		<div <?php $this->print_render_attribute_string( 'box' ) ?>>
			<div class="tm-client-box__wrapper">
				<div class="tm-client-box__content">
					<?php
					if ( ! empty( $settings['title'] ) ) {
						printf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title' ), esc_html( $settings['title'] ) );
					}

					if ( ! empty( $settings['review'] ) ) {
						printf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( 'review' ), $settings['review'] );
					}
					?>

				</div>

				<?php if ( ! empty( $settings['image']['url'] ) ) : ?>
					<div class="tm-client-box__image">
						<?php echo \Minimog_Image::get_elementor_attachment( [
							'settings' => $settings,
						] ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		view.addRenderAttribute( 'box', 'class', 'tm-client-box' );

		if ( 'yes' === settings.reverse_content ) {
			view.addRenderAttribute( 'box', 'class', 'tm-client-box--content-reversed' );
		}
		#>
		<div {{{ view.getRenderAttributeString( 'box' ) }}}>
			<div class="tm-client-box__wrapper">
				<div class="tm-client-box__content">
					<#
					view.addRenderAttribute( 'title', 'class', 'tm-client-box__title' );
					view.addRenderAttribute( 'review', 'class', 'tm-client-box__review' );

					view.addInlineEditingAttributes( 'title', 'none' );
					view.addInlineEditingAttributes( 'review', 'basic' );
					#>
					<# if ( settings.title ) { #>
						<{{{ settings.title_size }}} {{{ view.getRenderAttributeString( 'title' ) }}}>
							{{{ settings.title }}}
						</{{{ settings.title_size }}}>
					<# } #>

					<# if ( settings.review ) { #>
						<div {{{ view.getRenderAttributeString( 'review' ) }}}>{{{ settings.review }}}</div>
					<# } #>
				</div>

				<# if ( '' !== settings.image.url ) { #>
					<div class="tm-client-box__image">
						<img src="{{{ settings.image.url }}}" >
					</div>
				<# } #>
			</div>
		</div>
		<?php
		// @formatter:off
	}
}
