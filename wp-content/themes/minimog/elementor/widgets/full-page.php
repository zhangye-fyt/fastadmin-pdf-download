<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Core\Base\Document;
use Elementor\Plugin;

defined( 'ABSPATH' ) || exit;

class Widget_Full_Page extends Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'minimog-widget-fullpage', MINIMOG_THEME_URI . '/assets/libs/fullpage/fullpage.min.css', null, '3.0.5' );

		// Fullpage.
		wp_register_script( 'fullpage', MINIMOG_THEME_URI . '/assets/libs/fullpage/fullpage.js', array(), null, true );
		//wp_register_script( 'fullpage-extensions', MINIMOG_THEME_URI . '/assets/libs/fullpage/fullpage.extensions.min.js', array(), null, true );
		wp_register_script( 'scrolloverflow', MINIMOG_THEME_URI . '/assets/libs/fullpage/vendors/scrolloverflow.js', array(), null, true );
		wp_register_script( 'minimog-widget-fullpage', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-fullpage.js', array(
			'elementor-frontend',
			'fullpage',
			'scrolloverflow',
		), null, true );
	}

	public function get_name() {
		return 'tm-full-page';
	}

	public function get_title() {
		return esc_html__( 'Fullpage', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-slider-vertical';
	}

	public function get_keywords() {
		return [ 'one page', 'scroll', 'full page' ];
	}

	public function get_script_depends() {
		return [ 'minimog-widget-fullpage' ];
	}

	public function get_style_depends() {
		return [ 'minimog-widget-fullpage' ];
	}

	public function is_reload_preview_required() {
		return false;
	}

	protected function register_controls() {
		$this->add_slides_section();

		$this->add_fullpage_options_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'tm-full-page' );

		$fp_settings = array(
			'numbers' => $settings['numbers'],
			'sharing' => $settings['sharing'],
		);

		if ( 'yes' === $settings['dots'] ) {
			$fp_settings['dots']       = '1';
			$fp_settings['dots_align'] = $settings['dots_alignment'];
		}

		$this->add_render_attribute( 'wrapper', 'data-fp-settings', wp_json_encode( $fp_settings ) );
		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<?php if ( $settings['slides'] && count( $settings['slides'] ) > 0 ) {
				foreach ( $settings['slides'] as $key => $slide ) {
					if ( empty( $slide['template_id'] ) ) {
						continue;
					}

					if ( 'publish' !== get_post_status( $slide['template_id'] ) ) {
						continue;
					}

					$slide_id = $slide['_id'];
					$item_key = 'item_' . $slide_id;

					$this->add_render_attribute( $item_key, [
						'class'        => [
							'section',
							'elementor-repeater-item-' . $slide_id,
						],
						'data-skin'    => $slide['skin'],
						'data-anchor'  => $slide['anchor'],
						'data-tooltip' => $slide['title'],
					] );

					if ( 'yes' === $slide['auto_height'] ) {
						$this->add_render_attribute( $item_key, 'class', 'fp-auto-height' );
					}
					?>
					<div <?php $this->print_attributes_string( $item_key ); ?>>
						<?php echo Plugin::instance()->frontend->get_builder_content_for_display( $slide['template_id'] ); ?>
					</div>
					<?php
				}
			}
			?>
		</div>

		<?php $this->print_share( $settings ); ?>

		<?php $this->print_slide_numbers( $settings ); ?>

		<?php
	}

	private function add_slides_section() {
		$this->start_controls_section( 'slides_section', [
			'label' => esc_html__( 'Slides', 'minimog' ),
		] );

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'repeater_slides_tabs' );

		$repeater->start_controls_tab( 'repeater_slides_content_tab', [ 'label' => esc_html__( 'Content', 'minimog' ) ] );

		$document_types = Plugin::instance()->documents->get_document_types( [
			'show_in_library' => true,
		] );

		$repeater->add_control( 'template_id', [
			'label'        => esc_html__( 'Choose Template', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'label_block'  => true,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_LIBRARY_TEMPLATE,
				'query'  => [
					'meta_query' => [
						[
							'key'     => Document::TYPE_META_KEY,
							'value'   => array_keys( $document_types ),
							'compare' => 'IN',
						],
					],
				],
			],
		] );

		$repeater->add_control( 'title', [
			'label'       => esc_html__( 'Title', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Title', 'minimog' ),
			'label_block' => true,
		] );

		$repeater->add_control( 'anchor', [
			'label' => esc_html__( 'Anchor', 'minimog' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$repeater->add_control( 'skin', [
			'label'       => esc_html__( 'Skin', 'minimog' ),
			'description' => esc_html__( 'Controls the skin of pagination & header when view this section.', 'minimog' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => [
				'light' => [
					'text' => esc_html__( 'Light', 'minimog' ),
				],
				'dark'  => [
					'text' => esc_html__( 'Dark', 'minimog' ),
				],
			],
			'toggle'      => false,
			'default'     => 'light',
		] );

		$repeater->add_control( 'auto_height', [
			'label' => esc_html__( 'Auto Height', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'repeater_slides_background_tab', [ 'label' => esc_html__( 'Background', 'minimog' ) ] );

		$repeater->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'background',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}}',
			'separator' => 'before',
		] );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control( 'slides', [
			'label'       => esc_html__( 'Slides', 'minimog' ),
			'show_label'  => false,
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				[
					'title' => 'Slide #1',
				],
				[
					'title' => 'Slide #2',
				],
			],
			'title_field' => '{{{ title }}}',
		] );

		$this->end_controls_section();
	}

	private function add_fullpage_options_section() {
		$this->start_controls_section( 'fullpage_options_section', [
			'label' => esc_html__( 'Fullpage Options', 'minimog' ),
		] );

		$this->add_control( 'dots', [
			'label' => esc_html__( 'Dots Enable', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'dots_alignment', [
			'label'     => esc_html__( 'Dots Alignment', 'minimog' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'  => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'toggle'    => false,
			'default'   => 'right',
			'condition' => [
				'dots' => 'yes',
			],
		] );

		$this->add_control( 'numbers', [
			'label' => esc_html__( 'Numbers Enable', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'sharing', [
			'label' => esc_html__( 'Share Enable', 'minimog' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->end_controls_section();
	}

	private function print_share( array $settings ) {
		if ( 'yes' !== $settings['sharing'] ) {
			return;
		}
		?>
		<div id="full-page-sharing" class="full-page-share-list">
			<div class="stalk">
				<?php esc_html_e( 'Stalk', 'minimog' ); ?>
			</div>
			<?php \Minimog_Templates::get_sharing_list( array(
				'tooltip_position' => 'top-left',
			) ); ?>
		</div>
		<?php
	}

	private function print_slide_numbers( array $settings ) {
		if ( 'yes' !== $settings['numbers'] ) {
			return;
		}

		$total = count( $settings['slides'] );
		?>
		<div id="full-page-numbers" class="full-page-numbers">
			<div class="numbers">
				<div class="current">1</div>
				<div class="total"><?php echo esc_html( $total ); ?></div>
			</div>
			<div class="title">
				<?php esc_html_e( 'Projects', 'minimog' ); ?>
			</div>
		</div>
		<?php
	}
}
