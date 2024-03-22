<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

abstract class Posts_Carousel_Base extends Carousel_Base {

	/**
	 * @var \WP_Query
	 */
	private $_query      = null;
	private $_query_args = null;

	abstract protected function get_post_type();

	public function query_posts() {
		$settings          = $this->get_settings_for_display();
		$post_type         = $this->get_post_type();
		$this->_query      = Module_Query_Base::instance()->get_query( $settings, $post_type );
		$this->_query_args = Module_Query_Base::instance()->get_query_args();
	}

	protected function get_query() {
		return $this->_query;
	}

	protected function get_query_args() {
		return $this->_query_args;
	}

	protected function get_query_author_object() {
		return Module_Query_Base::QUERY_OBJECT_AUTHOR;
	}

	abstract protected function print_slide( array $settings );

	protected function register_controls() {
		parent::register_controls();

		$this->register_query_section();
	}

	protected function get_query_orderby_options() {
		$options = [
			'date'           => esc_html__( 'Date', 'minimog' ),
			'ID'             => esc_html__( 'Post ID', 'minimog' ),
			'author'         => esc_html__( 'Author', 'minimog' ),
			'title'          => esc_html__( 'Title', 'minimog' ),
			'modified'       => esc_html__( 'Last modified date', 'minimog' ),
			'parent'         => esc_html__( 'Post/page parent ID', 'minimog' ),
			'comment_count'  => esc_html__( 'Number of comments', 'minimog' ),
			'menu_order'     => esc_html__( 'Menu order/Page Order', 'minimog' ),
			'meta_value'     => esc_html__( 'Meta value', 'minimog' ),
			'meta_value_num' => esc_html__( 'Meta value number', 'minimog' ),
			'rand'           => esc_html__( 'Random order', 'minimog' ),
			'views'          => esc_html__( 'Views', 'minimog' ),
		];

		$post_type = $this->get_post_type();

		if ( 'product' === $post_type ) {
			$options = array_merge( $options, [
				'woo_featured'     => esc_html__( 'Featured Products', 'minimog' ),
				'woo_best_selling' => esc_html__( 'Best Selling Products', 'minimog' ),
				'woo_on_sale'      => esc_html__( 'On Sale Products', 'minimog' ),
				'woo_top_rated'    => esc_html__( 'Top Rated Products', 'minimog' ),
			] );
		}

		return $options;
	}

	protected function register_query_section() {
		$this->start_controls_section( 'query_section', [
			'label' => esc_html__( 'Query', 'minimog' ),
		] );

		$this->add_control( 'query_source', [
			'label'   => esc_html__( 'Source', 'minimog' ),
			'type'    => Controls_Manager::SELECT,
			'options' => array(
				'custom_query'  => esc_html__( 'Custom Query', 'minimog' ),
				'current_query' => esc_html__( 'Current Query', 'minimog' ),
			),
			'default' => 'custom_query',
		] );

		$this->start_controls_tabs( 'query_args_tabs', [
			'condition' => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->start_controls_tab( 'query_include_tab', [
			'label' => esc_html__( 'Include', 'minimog' ),
		] );

		$this->add_control( 'query_include', [
			'label'       => esc_html__( 'Include By', 'minimog' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'options'     => [
				'terms'   => esc_html__( 'Term', 'minimog' ),
				'authors' => esc_html__( 'Author', 'minimog' ),
			],
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_include_term_ids', [
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'options'      => [],
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => [
				'object'  => Module_Query_Base::QUERY_OBJECT_TAX,
				'display' => 'detailed',
				'query'   => [
					'post_type' => $this->get_post_type(),
				],
			],
			'condition'    => [
				'query_include' => 'terms',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_include_authors', [
			'label'        => esc_html__( 'Author', 'minimog' ),
			'label_block'  => true,
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'multiple'     => true,
			'default'      => [],
			'options'      => [],
			'autocomplete' => [
				'object' => $this->get_query_author_object(),
			],
			'condition'    => [
				'query_include' => 'authors',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'query_exclude_tab', [
			'label' => esc_html__( 'Exclude', 'minimog' ),
		] );

		$this->add_control( 'query_exclude', [
			'label'       => esc_html__( 'Exclude By', 'minimog' ),
			'label_block' => true,
			'type'        => Controls_Manager::SELECT2,
			'multiple'    => true,
			'options'     => [
				'terms'   => esc_html__( 'Term', 'minimog' ),
				'authors' => esc_html__( 'Author', 'minimog' ),
			],
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_exclude_term_ids', [
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'options'      => [],
			'label_block'  => true,
			'multiple'     => true,
			'autocomplete' => [
				'object'  => Module_Query_Base::QUERY_OBJECT_TAX,
				'display' => 'detailed',
				'query'   => [
					'post_type' => $this->get_post_type(),
				],
			],
			'condition'    => [
				'query_exclude' => 'terms',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_exclude_authors', [
			'label'        => esc_html__( 'Author', 'minimog' ),
			'label_block'  => true,
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'multiple'     => true,
			'default'      => [],
			'options'      => [],
			'autocomplete' => [
				'object' => $this->get_query_author_object(),
			],
			'condition'    => [
				'query_exclude' => 'authors',
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control( 'query_number', [
			'label'       => esc_html__( 'Items per page', 'minimog' ),
			'description' => esc_html__( 'Number of items to show per page. Input "-1" to show all posts. Leave blank to use global setting.', 'minimog' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => -1,
			'max'         => 100,
			'step'        => 1,
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
			'separator'   => 'before',
		] );

		$this->add_control( 'query_offset', [
			'label'       => esc_html__( 'Offset', 'minimog' ),
			'description' => esc_html__( 'Number of items to displace or pass over.', 'minimog' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 0,
			'max'         => 100,
			'step'        => 1,
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_orderby', [
			'label'       => esc_html__( 'Order by', 'minimog' ),
			'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'minimog' ),
			'type'        => Controls_Manager::SELECT,
			'options'     => $this->get_query_orderby_options(),
			'default'     => 'date',
			'condition'   => [
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_sort_meta_key', [
			'label'     => esc_html__( 'Meta key', 'minimog' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [
				'query_orderby' => [
					'meta_value',
					'meta_value_num',
				],
				'query_source!' => [ 'current_query' ],
			],
		] );

		$this->add_control( 'query_order', [
			'label'     => esc_html__( 'Sort order', 'minimog' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => array(
				'DESC' => esc_html__( 'Descending', 'minimog' ),
				'ASC'  => esc_html__( 'Ascending', 'minimog' ),
			),
			'default'   => 'DESC',
			'condition' => [
				'query_source!'  => [ 'current_query' ],
				'query_orderby!' => [
					'views',
					'woo_best_selling',
					'woo_top_rated',
				],
			],
		] );

		$this->end_controls_section();
	}

	protected function print_slides( array $settings ) {
		$settings = $this->get_settings_for_display();
		$this->query_posts();
		/**
		 * @var $query \WP_Query
		 */
		$query = $this->get_query();
		?>
		<?php if ( $query->have_posts() ) : ?>

			<?php $this->before_loop(); ?>

			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<?php $this->print_slide( $settings ); ?>
			<?php endwhile; ?>

			<?php $this->after_loop(); ?>

		<?php endif;
		wp_reset_postdata();
	}

	protected function before_loop() {
	}

	protected function after_loop() {
	}
}
