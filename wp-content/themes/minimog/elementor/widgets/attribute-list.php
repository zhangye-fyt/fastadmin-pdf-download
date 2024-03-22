<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

class Widget_Attribute_List extends Base {

	public function get_name() {
		return 'tm-attribute-list';
	}

	public function get_title() {
		return esc_html__( 'Attribute List', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-columns';
	}

	public function get_keywords() {
		return [ 'list', 'attribute' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'List', 'minimog' ),
		] );

		$this->add_control( 'style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'01' => '01',
			],
			'default' => '01',
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'name', [
			'label'       => esc_html__( 'Name', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Name', 'minimog' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'value', [
			'label' => esc_html__( 'Value', 'minimog' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'name'  => 'Attribute #1',
					'value' => 'Value #1',
				],
				[
					'name'  => 'Attribute #2',
					'value' => 'Value #2',
				],
			],
			'title_field' => '{{{ name }}}',
		] );

		$this->end_controls_section();

		$this->add_styling_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-attribute-list' );

		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<div class="list">
				<?php if ( $settings['items'] && count( $settings['items'] ) > 0 ) {
					foreach ( $settings['items'] as $key => $attribute ) {
						?>
						<div class="item">
							<?php if ( ! empty( $attribute['name'] ) ) : ?>
								<h6 class="name"><?php echo esc_html( $attribute['name'] ); ?></h6>
							<?php endif; ?>

							<?php if ( ! empty( $attribute['value'] ) ) : ?>
								<div class="value"><?php echo wp_kses( $attribute['value'], 'minimog-default' ); ?></div>
							<?php endif; ?>
						</div>
						<?php
					}
				}
				?>
			</div>
		</div>
		<?php
	}

	private function add_styling_section() {
		$this->start_controls_section( 'styling_section', [
			'label' => esc_html__( 'List', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'alignment', [
			'label'     => esc_html__( 'Alignment', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .list' => 'justify-content: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .item' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_responsive_control( 'item_spacing', [
			'label'      => esc_html__( 'Item Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 200,
					'step' => 1,
				],
			],
			'default' 	 => [
				'unit' => 'px',
				'size' => 25,
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-attribute-list' => '--list-item-spacing: {{SIZE}}{{UNIT}}',
			],
		] );

		$item_spacing = 'var(--list-item-spacing)';

		$this->add_responsive_control( 'layout', [
			'label'          	   => esc_html__( 'Layout', 'minimog' ),
			'type'          	   => Controls_Manager::CHOOSE,
			'default'        	   => 'block',
			'options'        	   => [
				'block'   => [
					'title' => esc_html__( 'Default', 'minimog' ),
					'icon'  => 'eicon-editor-list-ul',
				],
				'inline'  => [
					'title' => esc_html__( 'Inline', 'minimog' ),
					'icon'  => 'eicon-ellipsis-h',
				],
			],
			'label_block'    	   => false,
			'selectors_dictionary' => [
				'block'   => sprintf( '--list-display: block; --list-item-margin-top: %s; --list-item-margin-left: 0;', $item_spacing ),
				'inline'  => sprintf( '--list-display: flex; --list-item-margin-top: 0; --list-item-margin-left: %s;', $item_spacing ),
			],
			'selectors'  		   => [
				'{{WRAPPER}} .tm-attribute-list' => '{{VALUE}}',
			],
		]);

		$this->add_control( 'item_style_heading', [
			'label'     => esc_html__( 'Item', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_responsive_control( 'value_spacing', [
			'label'      => esc_html__( 'Value Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [
					'max'  => 100,
					'step' => 1,
				],
			],
			'default' 	 => [
				'unit' => 'px',
				'size' => 10,
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-attribute-list' => '--list-item-value-spacing: {{SIZE}}{{UNIT}}',
			],
		] );

		$item_value_spacing = 'var(--list-item-value-spacing)';

		$this->add_responsive_control( 'item_layout', [
			'label'          	   => esc_html__( 'Layout', 'minimog' ),
			'type'           	   => Controls_Manager::CHOOSE,
			'default'        	   => 'block',
			'options'        	   => [
				'block'   => [
					'title' => esc_html__( 'Default', 'minimog' ),
					'icon'  => 'eicon-editor-list-ul',
				],
				'inline'  => [
					'title' => esc_html__( 'Inline', 'minimog' ),
					'icon'  => 'eicon-ellipsis-h',
				],
			],
			'label_block'    	   => false,
			'selectors_dictionary' => [
				'block'   => sprintf( '--list-item-display: block; --list-value-margin-top: %s; --list-value-margin-left: 0;', $item_value_spacing ),
				'inline'  => sprintf( '--list-item-display: flex; --list-value-margin-top: 0; --list-value-margin-left: %s;', $item_value_spacing ),
			],
			'selectors'            => [
				'{{WRAPPER}} .tm-attribute-list' => '{{VALUE}}',
			],
		]);

		$this->add_control( 'name_heading', [
			'label'     => esc_html__( 'Name', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'name_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .name',
		] );

		$this->add_control( 'name_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .name' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'value_heading', [
			'label'     => esc_html__( 'Value', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'value_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .value',
		] );

		$this->add_control( 'value_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .value' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}
}
