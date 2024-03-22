<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

//@todo Not compatible with WPML.

class Widget_Gradation extends Base {

	public function get_name() {
		return 'tm-gradation';
	}

	public function get_title() {
		return esc_html__( 'Gradation', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-navigation-horizontal';
	}

	public function get_keywords() {
		return [ 'gradation', 'step' ];
	}

	protected function register_controls() {
		$this->start_controls_section( 'layout_section', [
			'label' => esc_html__( 'Layout', 'minimog' ),
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Title', 'minimog' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'description', [
			'label' => esc_html__( 'Description', 'minimog' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$this->add_control( 'items', [
			'label'       => esc_html__( 'Items', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title'       => 'Step #1',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
				[
					'title'       => 'Step #2',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
				[
					'title'       => 'Step #3',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
				[
					'title'       => 'Step #4',
					'description' => 'Suspe ndisse suscipit sagittis leo sit met condimentum estibulum issim Lorem ipsum dolor sit amet, consectetur cium',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->end_controls_section();

		$this->add_styling_section();
	}

	private function add_styling_section() {
		$this->start_controls_section( 'styling_section', [
			'label' => esc_html__( 'Styling', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'text_align', [
			'label'                => esc_html__( 'Text Align', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align(),
			'default'              => '',
			'selectors'            => [
				'{{WRAPPER}} .item' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
			],
		] );

		$this->add_control( 'title_heading', [
			'label'     => esc_html__( 'Title', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'title_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .title',
		] );

		$this->add_control( 'title_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .title' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'description_heading', [
			'label'     => esc_html__( 'Description', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'description_typography',
			'label'    => esc_html__( 'Typography', 'minimog' ),
			'selector' => '{{WRAPPER}} .description',
		] );

		$this->add_control( 'description_color', [
			'label'     => esc_html__( 'Color', 'minimog' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .description' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-gradation' );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php
			$loop_count = 0;
			if ( $settings['items'] && count( $settings['items'] ) > 0 ) {
				foreach ( $settings['items'] as $key => $item ) {
					$loop_count++;
					?>
					<div class="item">

						<div class="count-wrap">
							<div class="count"><?php echo esc_html( $loop_count ); ?></div>
						</div>
						<div class="line"></div>

						<div class="content-wrap">
							<?php if ( isset( $item['title'] ) ) : ?>
								<h5 class="title"><?php echo esc_html( $item['title'] ); ?></h5>
							<?php endif; ?>

							<?php if ( isset( $item['description'] ) ) : ?>
								<div class="description"><?php echo esc_html( $item['description'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
		<?php
	}
}
