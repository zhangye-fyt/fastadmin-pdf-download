<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

class Widget_Blockquote extends Base {

	public function get_name() {
		return 'tm-blockquote';
	}

	public function get_title() {
		return esc_html__( 'Blockquote', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-blockquote';
	}

	public function get_keywords() {
		return [ 'blockquote', 'quote', 'paragraph', 'testimonial', 'text' ];
	}

	protected function register_controls() {
		$this->add_layout_section();

		$this->add_content_section();

		$this->add_box_style_section();
	}

	private function add_layout_section() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'style-01' => '01',
				'style-02' => '02',
			),
			'default' => 'style-01',
		] );

		$this->end_controls_section();
	}

	private function add_content_section() {
		$this->start_controls_section( 'blockquote_section', [
			'label' => esc_html__( 'BlockQuote', 'minimog' ),
		] );

		$this->add_control( 'icon_type', [
			'label'   => __( 'Icon Type', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'icon'  => __( 'Icon', 'minimog' ),
				'image' => __( 'Image', 'minimog' ),
			],
			'default' => 'icon',
		] );

		$this->add_control( 'icon', [
			'label'      => esc_html__( 'Icon', 'minimog' ),
			'show_label' => false,
			'type'       => Controls_Manager::ICONS,
			'default'    => [],
			'condition'  => [
				'icon_type' => 'icon',
			],
		] );

		$this->add_control( 'image', [
			'label'     => __( 'Choose Image', 'minimog' ),
			'type'      => Controls_Manager::MEDIA,
			'dynamic'   => [
				'active' => true,
			],
			'default'   => [],
			'condition' => [
				'icon_type' => 'image',
			],
		] );

		$this->add_control( 'blockquote_content', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXTAREA,
			'dynamic'     => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'Enter your quote', 'minimog' ),
			'default'     => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'minimog' ),
			'separator'   => 'before',
		] );

		$this->add_control( 'author_name', [
			'label'   => esc_html__( 'Author', 'minimog' ),
			'type'    => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
			'default' => '',
		] );

		$this->add_control( 'author_position', [
			'label'     => esc_html__( 'Position', 'minimog' ),
			'type'      => Controls_Manager::TEXT,
			'dynamic'   => [
				'active' => true,
			],
			'default'   => '',
			'separator' => 'before',
		] );

		$this->end_controls_section();
	}

	private function add_box_style_section() {
		$this->start_controls_section( 'box_style_section', [
			'label' => esc_html__( 'Box', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors'            => [
				'{{WRAPPER}} .tm-blockquote' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'alignment', [
			'label'                => esc_html__( 'Content Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-blockquote' => 'justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'box_padding', [
			'label'      => esc_html__( 'Padding', 'minimog' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [
				'body:not(.rtl) {{WRAPPER}} .tm-blockquote' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'body.rtl {{WRAPPER}} .tm-blockquote'       => 'padding: {{TOP}}{{UNIT}} {{LEFT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{RIGHT}}{{UNIT}};',
			],
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
				'{{WRAPPER}} blockquote' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'box_background',
			'selector' => '{{WRAPPER}} .tm-blockquote',
		] );

		$this->add_group_control( Group_Control_Advanced_Border::get_type(), [
			'name'     => 'box_border',
			'selector' => '{{WRAPPER}} .tm-blockquote',
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'box_shadow',
			'selector' => '{{WRAPPER}} .tm-blockquote',
		] );

		$this->add_control( 'blockquote_icon_style', [
			'label'     => esc_html__( 'Blockquote Icon', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'blockquote_color',
			[
				'label'     => esc_html__( 'Color', 'minimog' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tm-blockquote__icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control( 'blockquote_icon_size', [
			'label'     => esc_html__( 'Size', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__icon' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'icon_rotate', [
			'label'     => esc_html__( 'Rotate', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'default'   => [
				'unit' => 'deg',
			],
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__icon .blockquote-icon' => 'transform: rotate({{SIZE}}{{UNIT}});',
			],
		] );

		$this->add_responsive_control( 'blockquote_icon_spacing', [
			'label'     => esc_html__( 'Spacing', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 200,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'blockquote_content_style', [
			'label'     => esc_html__( 'Blockquote Content', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'blockquote_content_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__content' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'blockquote_content_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-blockquote__content',
		] );

		$this->add_responsive_control( 'blockquote_content_spacing_top', [
			'label'     => esc_html__( 'Spacing Top', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__content' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'blockquote_content_spacing_bottom', [
			'label'     => esc_html__( 'Spacing Bottom', 'minimog' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [
					'min' => 0,
					'max' => 500,
				],
			],
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__content' => 'margin-bottom: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_control( 'author_name_heading', [
			'label'     => esc_html__( 'Author Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'author_name_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__author-name' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'author_name_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-blockquote__author-name',
		] );

		$this->add_control( 'author_position_heading', [
			'label'     => esc_html__( 'Author Position', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'author_position_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .tm-blockquote__author-position' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'author_position_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .tm-blockquote__author-position',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-blockquote' );
		$this->add_render_attribute( 'wrapper', 'class', 'tm-blockquote--' . $settings['style'] );
		$this->add_render_attribute( 'blockquote_content', 'class', 'tm-blockquote__content' );
		$this->add_render_attribute( 'author_name', 'class', 'tm-blockquote__author-name' );
		$this->add_render_attribute( 'author_position', 'class', 'tm-blockquote__author-position' );

		$this->add_inline_editing_attributes( 'blockquote_content', 'basic' );
		$this->add_inline_editing_attributes( 'author_name', 'none' );
		$this->add_inline_editing_attributes( 'author_position', 'none' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<blockquote>
				<?php
				if ( 'image' == $settings['icon_type'] ) {
					$this->print_image( $settings );
				} else {
					$this->print_icon( $settings );
				}
				?>
				<p <?php $this->print_render_attribute_string( 'blockquote_content' ) ?>>
					<?php echo wp_kses( $settings['blockquote_content'], 'minimog-default' ); ?>
				</p>
				<?php if ( ! empty( $settings['author_name'] ) ) : ?>
					<div class="tm-blockquote__footer">
						<cite <?php $this->print_render_attribute_string( 'author_name' ); ?>>
							<?php echo esc_html( $settings['author_name'] ); ?>
						</cite>
						<?php if ( ! empty( $settings['author_position'] ) ) : ?>
							<span <?php $this->print_render_attribute_string( 'author_position' ); ?>>
								<?php echo esc_html( $settings['author_position'] ); ?>
							</span>
						<?php endif ?>
					</div>
				<?php endif ?>
			</blockquote>
		</div>
		<?php
	}

	private function print_icon( array $settings ) {
		if ( empty( $settings['icon']['value'] ) ) {
			return;
		}

		$this->add_render_attribute( 'icon', 'class', [
			'blockquote-icon',
		] );

		$is_svg = isset( $settings['icon']['library'] ) && 'svg' === $settings['icon']['library'] ? true : false;

		if ( $is_svg ) {
			$this->add_render_attribute( 'icon', 'class', [
				'svg-icon',
			] );
		}

		echo '<div class="tm-blockquote__icon">';

		?>
		<div <?php $this->print_attributes_string( 'icon' ); ?>>
			<?php $this->render_icon( $settings, $settings['icon'], [ 'aria-hidden' => 'true' ], $is_svg, 'icon' ); ?>
		</div>
		<?php

		echo '</div>';
	}

	private function print_image( array $settings ) {
		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		echo '<div class="tm-blockquote__image"><img src="' . esc_url( $settings['image']['url'] ) . '" alt=""></div>';
	}

	protected function content_template() {
		// @formatter:off

		?>
		<#
		view.addRenderAttribute( 'wrapper', 'class', [
			'tm-blockquote',
			'tm-blockquote--' + settings.style
		]);

		view.addRenderAttribute( 'blockquote_content', 'class', 'tm-blockquote__content' );
		view.addRenderAttribute( 'author_name', 'class', 'tm-blockquote__author-name' );
		view.addRenderAttribute( 'author_position', 'class', 'tm-blockquote__author-position' );
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<blockquote>
				<# if ( 'image' === settings.icon_type ) { #>
					<div class="tm-blockquote__image"><img src="{{ settings.image.url }}" alt=""></div>
				<# } else { #>
					<#
					view.addRenderAttribute( 'blockquote_icon', 'class', 'blockquote-icon');

					if ( 'svg' === settings.icon.library ) {
						view.addRenderAttribute( 'blockquote_icon', 'class', 'svg-icon' );
					}

					var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );

					#>
					<# if ( iconHTML.rendered ) { #>
					<div class="tm-blockquote__icon">
						<span {{{ view.getRenderAttributeString( 'blockquote_icon' ) }}}>{{{ iconHTML.value }}}</span>
					</div>
					<# } #>
				<# } #>
				<p {{{ view.getRenderAttributeString( 'blockquote_content' ) }}}>{{{ settings.blockquote_content }}}</p>

				<# if ( settings.author_name ) { #>
					<div class="tm-blockquote__footer">
						<cite {{{ view.getRenderAttributeString( 'author_name' ) }}}>{{{ settings.author_name }}}</cite>
						<span {{{ view.getRenderAttributeString( 'author_position' ) }}}>{{{ settings.author_position }}}</span>
					</div>
				<# } #>
				
			</blockquote>
		</div>
		<?php
		// @formatter:off
	}
}
