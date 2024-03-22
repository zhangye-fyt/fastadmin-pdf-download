<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Icons_Manager;

class Widget_Product_Filter extends Base {
	public function get_name() {
		return 'tm-product-filter';
	}

	public function get_title() {
		return esc_html__( 'Product Filter', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-filter';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'product', 'filter', 'shop', 'catalog' ];
	}

	public function get_script_depends() {
		return [ 'minimog-widget-product-filter' ];
	}

	protected function register_controls() {
		$this->add_product_filter_content_section();

		$this->add_product_filter_style_section();

		$this->register_common_button_style_section();
	}

	protected function add_product_filter_content_section() {
		$this->start_controls_section( 'product_filter_section', [
			'label' => esc_html__( 'Product Filter', 'minimog' ),
		] );

		// Product Filter
		$this->product_filter_control();

		$this->add_control( 'button_heading', [
			'label'     => esc_html__( 'Button', 'minimog' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'button_style', [
			'label'   => esc_html__( 'Style', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'flat',
			'options' => \Minimog_Helper::get_button_style_options(),
		] );

		$this->add_control( 'button_text', [
			'label'       => esc_html__( 'Text', 'minimog' ),
			'type'        => Controls_Manager::TEXT,
			'dynamic'     => [
				'active' => true,
			],
			'default'     => '',
			'placeholder' => esc_html__( 'Find Product', 'minimog' ),
		] );

		$this->add_control( 'button_icon', [
			'label'       => esc_html__( 'Icon', 'minimog' ),
			'type'        => Controls_Manager::ICONS,
			'label_block' => true,
		] );

		$this->add_control( 'button_icon_align', [
			'label'       => esc_html__( 'Position', 'minimog' ),
			'type'        => Controls_Manager::CHOOSE,
			'options'     => [
				'left'  => [
					'title' => esc_html__( 'Left', 'minimog' ),
					'icon'  => 'eicon-h-align-left',
				],
				'right' => [
					'title' => esc_html__( 'Right', 'minimog' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'     => 'right',
			'toggle'      => false,
			'label_block' => false,
			'condition'   => [
				'button_icon[value]!' => '',
			],
		] );

		$this->add_control( 'button_icon_hover', [
			'label'        => esc_html__( 'Icon Hover Effect', 'minimog' ),
			'type'         => Controls_Manager::SELECT,
			'default'      => '',
			'options'      => [
				''                 => esc_html__( 'None', 'minimog' ),
				'fade'             => esc_html__( 'Fade', 'minimog' ),
				'slide-from-left'  => esc_html__( 'Slide From Left', 'minimog' ),
				'slide-from-right' => esc_html__( 'Slide From Right', 'minimog' ),
			],
			'prefix_class' => 'minimog-button-icon-animation--',
			'condition'    => [
				'button_icon[value]!' => '',
			],
		] );

		$this->end_controls_section();
	}

	protected function add_product_filter_style_section() {
		$this->start_controls_section( 'product_filter_style_section', [
			'label' => esc_html__( 'Product Filter', 'minimog' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'box_text_align', [
			'label'                => esc_html__( 'Alignment', 'minimog' ),
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_text_align_full(),
			'selectors'            => [
				'{{WRAPPER}} .tm-product-filter' => 'text-align: {{VALUE}};',
			],
			'selectors_dictionary' => [
				'left'  => 'start',
				'right' => 'end',
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
				'{{WRAPPER}} .tm-product-filter' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_responsive_control( 'box_horizontal_alignment', [
			'label'                => esc_html__( 'Horizontal Alignment', 'minimog' ),
			'label_block'          => true,
			'type'                 => Controls_Manager::CHOOSE,
			'options'              => Widget_Utils::get_control_options_horizontal_alignment(),
			'default'              => 'center',
			'toggle'               => false,
			'selectors_dictionary' => [
				'left'  => 'flex-start',
				'right' => 'flex-end',
			],
			'selectors'            => [
				'{{WRAPPER}} .elementor-widget-container' => 'display: flex; justify-content: {{VALUE}}',
			],
		] );

		$this->add_responsive_control( 'filter_spacing', [
			'label'      => esc_html__( 'Filter Spacing', 'minimog' ),
			'type'       => Controls_Manager::SLIDER,
			'default'    => [
				'unit' => 'px',
			],
			'size_units' => [ 'px', '%', 'em' ],
			'range'      => [
				'%'  => [
					'min' => 0,
					'max' => 100,
				],
				'px' => [
					'min' => 0,
					'max' => 300,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .tm-product-filter__filter + .tm-product-filter__filter' => 'margin-top: {{SIZE}}{{UNIT}};',
			],
		] );

		// Attribute

		$this->end_controls_section();
	}

	protected function product_filter_control() {
		$filter_by  = $this->get_filter_by();
		$attributes = $this->get_product_attributes();

		$repeater = new Repeater();

		$repeater->add_control( 'filter_by', [
			'label'       => esc_html__( 'Filter By', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => '',
			'options'     => $filter_by,
			'label_block' => true,
		] );

		$repeater->add_control( 'limit', [
			'label'       => esc_html__( 'Limit', 'minimog' ),
			'description' => esc_html__( 'Set 0 to show all terms', 'minimog' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 100,
			'step'        => 1,
			'default'     => 0,
		] );

		// Product Attribute
		$repeater->add_control( 'product_attribute', [
			'label'     => __( 'Attributes', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => $attributes,
			'condition' => [
				'filter_by' => 'attribute',
			],
		] );

		$repeater->add_control( 'product_attribute_query_type', [
			'label'     => esc_html__( 'Query Type', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'or',
			'options'   => [
				'or'  => esc_html__( 'OR', 'minimog' ),
				'and' => esc_html__( 'AND', 'minimog' ),
			],
			'condition' => [
				'filter_by' => 'attribute',
			],
		] );

		$repeater->add_control( 'product_attribute_multiple', [
			'label'     => esc_html__( 'Selection Type', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'single',
			'options'   => [
				'single'   => esc_html__( 'Single select', 'minimog' ),
				'multiple' => esc_html__( 'Multiple select', 'minimog' ),
			],
			'condition' => [
				'filter_by' => 'attribute',
			],
		] );

		$this->add_control( 'filters', [
			'label'       => esc_html__( 'Filters', 'minimog' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [],
			'title_field' => '{{{ filter_by }}}',
		] );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$url = wc_get_page_permalink( 'shop' );

		$this->add_render_attribute( 'wrapper', 'class', 'tm-product-filter' );

		$this->add_render_attribute( 'form', [
			'class'  => 'tm-product-filter__form',
			'action' => $url,
			'method' => 'get',
		] );


		?>
		<div <?php $this->print_attributes_string( 'wrapper' ); ?>>
			<form <?php $this->print_attributes_string( 'form' ); ?>>
				<?php $this->hidden_input(); ?>
				<?php $this->filter_by_html( $settings ); ?>
				<?php $this->print_button(); ?>
			</form>
		</div>
		<?php
	}

	protected function hidden_input() {
		?>
		<input type="hidden" name="filtering" value="1">
		<?php
	}

	protected function print_button() {
		$settings = $this->get_settings_for_display();

		$button_text = ! empty( $settings['button_text'] ) ? $settings['button_text'] : esc_html__( 'Find Product', 'minimog' );

		if ( empty( $button_text ) && empty( $settings['button_icon']['value'] ) ) {
			return;
		}

		$classes = [
			'tm-button',
			'tm-button style-' . $settings['button_style'],
		];

		$this->add_render_attribute( 'button', 'class', 'tm-button style-' . $settings['button_style'] );

		if ( ! empty( $settings['button_size'] ) ) {
			$classes[] = 'tm-button-' . $settings['button_size'];
		}

		$has_icon = false;

		if ( ! empty( $settings['button_icon']['value'] ) ) {
			$has_icon  = true;
			$classes[] = 'icon-' . $settings['button_icon_align'];

			$this->add_render_attribute( 'button-icon', 'class', 'button-icon' );
		}

		$this->add_render_attribute( 'button', [
			'class' => $classes,
			'href'  => '#',
		] );
		?>
		<div class="tm-product-filter__button tm-button-wrapper">
			<a <?php $this->print_attributes_string( 'button' ); ?>>
				<div class="button-content-wrapper">
					<?php if ( $has_icon && 'left' === $settings['button_icon_align'] ) : ?>
						<span <?php $this->print_attributes_string( 'button-icon' ); ?>>
							<?php Icons_Manager::render_icon( $settings['button_icon'] ); ?>
						</span>
					<?php endif; ?>

					<?php if ( ! empty( $button_text ) ): ?>
						<span class="button-text"><?php echo esc_html( $button_text ); ?></span>

						<?php if ( $settings['button_style'] === 'bottom-line-winding' ): ?>
							<span class="line-winding">
								<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
								     xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd"
									      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
									      fill="#E8C8B3"/>
								</svg>
							</span>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( $has_icon && 'right' === $settings['button_icon_align'] ) : ?>
						<span <?php $this->print_attributes_string( 'button-icon' ); ?>>
								<?php Icons_Manager::render_icon( $settings['button_icon'] ); ?>
							</span>
					<?php endif; ?>
				</div>
			</a>
		</div>
		<?php
	}

	protected function get_product_attributes() {
		$attributes = [];

		// Getting attribute taxonomies.
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		foreach ( $attribute_taxonomies as $taxonomy ) {
			$attributes[ $taxonomy->attribute_name ] = $taxonomy->attribute_label;
		}

		return $attributes;
	}

	protected function product_attribute_html( array $filter ) {
		$attribute = $filter['product_attribute'];
		$taxonomy  = wc_attribute_taxonomy_name( $attribute );

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'number'     => $filter['limit'],
			'hide_empty' => 0
		) );

		if ( 0 === count( $terms ) ) {
			return;
		}

		$taxonomy_id   = wc_attribute_taxonomy_id_by_name( $taxonomy );
		$taxonomy_info = wc_get_attribute( $taxonomy_id );

		$type = isset( $taxonomy_info->type ) ? $taxonomy_info->type : 'select';

		$attribute_classes = [
			'tm-product-filter__filter',
			'tm-product-filter__attribute',
			'tm-product-filter__' . $taxonomy,
			'tm-product-filter__' . $type,
			'tm-product-filter__' . $filter['product_attribute_multiple'],
		];

		$filter_name = sanitize_title( str_replace( 'pa_', '', $taxonomy ) );

		echo '<div class="' . esc_attr( implode( ' ', $attribute_classes ) ) . '">';

		switch ( $type ) :
			case 'color':
				foreach ( $terms as $term ) :
					$term_class = 'term-link hint--top';

					$val     = get_term_meta( $term->term_id, 'sw_color', true ) ? : '#fff';
					$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;
					?>
					<a href="#" aria-label="<?php echo esc_attr( $tooltip ); ?>"
					   class="<?php echo esc_attr( $term_class ); ?>"
					   data-term="<?php echo esc_attr( $term->slug ); ?>">
						<div class="term-shape">
							<span style="background: <?php echo esc_attr( $val ); ?>" class="term-shape-bg"></span>
							<span class="term-shape-border"></span>
						</div>
						<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
					</a>
				<?php
				endforeach;
				break;
			case 'image':
				foreach ( $terms as $term ) :
					$term_class = 'term-link hint--top';

					$val     = get_term_meta( $term->term_id, 'sw_image', true );
					$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;

					if ( ! empty( $val ) ) {
						$image_url = wp_get_attachment_thumb_url( $val );
					} else {
						$image_url = wc_placeholder_img_src();
					}
					?>
					<a href="#" aria-label="<?php echo esc_attr( $tooltip ); ?>"
					   class="<?php echo esc_attr( $term_class ); ?>"
					   data-term="<?php echo esc_attr( $term->slug ); ?>">
						<div class="term-shape">
							<span style="background-image: url(<?php echo esc_attr( $image_url ); ?>);"
							      class="term-shape-bg"></span>
							<span class="term-shape-border"></span>
						</div>
						<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
					</a>
				<?php
				endforeach;
				break;
			default:
				foreach ( $terms as $term ) :
					$term_class = 'term-link hint--top';

					$tooltip = get_term_meta( $term->term_id, 'sw_tooltip', true ) ? : $term->name;

					?>
					<a href="#" aria-label="<?php echo esc_attr( $tooltip ); ?>"
					   class="<?php echo esc_attr( $term_class ); ?>"
					   data-term="<?php echo esc_attr( $term->slug ); ?>">
						<div class="term-name"><?php echo esc_html( $term->name ); ?></div>
					</a>
				<?php
				endforeach;
				break;
		endswitch;


		echo '<input class="filter-attribute-input" type="hidden" name="filter_' . esc_attr( $filter_name ) . '" value="" disabled>';

		if ( 'multiple' === $filter['product_attribute_multiple'] && 'or' === $filter['product_attribute_query_type'] ) {
			echo '<input type="hidden" name="query_type_' . esc_attr( $filter_name ) . '" value="or" disabled>';
		}

		echo '</div>';
	}

	/**
	 * Product Taxonomies, Price, Rating and Product Group will be added in future
	 *
	 * @return array
	 */
	protected function get_filter_by() {
		$filters = [
			'attribute' => esc_html__( 'Attributes', 'minimog' ),
		];

		return $filters;
	}

	protected function filter_by_html( array $settings ) {
		$filters = $settings['filters'];

		if ( ! empty( $filters ) ) {
			foreach ( $filters as $filter ) {
				switch ( $filter['filter_by'] ) :
					case 'attribute' :
						$this->product_attribute_html( $filter );
						break;
					default:
						break;
				endswitch;
			}
		}
	}
}
