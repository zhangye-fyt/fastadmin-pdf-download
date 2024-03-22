<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Archive_Product {
	protected static $instance = null;

	const SIDEBAR_FILTERS = 'shop_sidebar';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'body_class', [ $this, 'body_classes' ] );

		add_filter( 'minimog/page_sidebar/1/off_sidebar/enable', [ $this, 'sidebar1_off_canvas_enable' ] );
		add_filter( 'minimog/page_sidebar/1/off_sidebar/toggle_text', [ $this, 'sidebar1_off_canvas_text' ] );
		add_filter( 'minimog/page_sidebar/2/off_sidebar/enable', [ $this, 'sidebar2_off_canvas_enable' ] );
		add_filter( 'minimog/page_sidebar/2/off_sidebar/toggle_text', [ $this, 'sidebar2_off_canvas_text' ] );

		add_filter( 'minimog/page_sidebar/widgets_collapsible', [ $this, 'turn_sidebar_widget_collapsible' ] );
		add_filter( 'minimog/page_sidebar/single_width', [ $this, 'set_sidebar_single_width' ] );
		add_filter( 'minimog/page_sidebar/single_offset', [ $this, 'set_sidebar_single_offset' ] );
		add_filter( 'minimog/page_sidebar/class', [ $this, 'add_sidebar_css_class' ] );

		// Different title bar for category page.
		add_filter( 'minimog/title_bar/type', [ $this, 'set_title_bar_for_category_page' ], 20 );

		add_filter( 'woocommerce_product_get_rating_html', [ $this, 'change_star_rating_html' ], 10, 3 );

		add_filter( 'woocommerce_catalog_orderby', [ $this, 'custom_product_sorting' ] );

		add_filter( 'woocommerce_pagination_args', [ $this, 'override_pagination_args' ] );

		add_action( 'init', [ $this, 'setup_product_loop' ] );

		add_action( 'wp', [ $this, 'shop_toolbars' ], 99 );

		add_action( 'minimog/shop_archive/actions_toolbar_left/before', [ $this, 'output_open_off_sidebar' ] );
	}

	public function set_title_bar_for_category_page( $type ) {
		if ( is_product_category() ) {
			$new_type = \Minimog::setting( 'product_category_title_bar_layout' );

			return '' !== $new_type ? $new_type : $type;
		}

		return $type;
	}

	public function body_classes( $classes ) {
		$classes[] = 'woocommerce';

		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			$classes[] = 'archive-shop';
		}

		return $classes;
	}

	public function page_content_container_class() {
		$container = \Minimog::setting( 'shop_archive_site_layout' );

		return \Minimog_Site_Layout::instance()->get_container_class( $container );
	}

	public function sidebar1_off_canvas_enable( $enable ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			return \Minimog::setting( 'product_archive_off_sidebar' );
		}

		return $enable;
	}

	public function sidebar1_off_canvas_text( $text ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			$new_text = \Minimog::setting( 'product_archive_page_sidebar_1_off_canvas_toggle_text' );

			return '' !== $new_text ? $new_text : __( 'Filters', 'minimog' );
		}

		return $text;
	}

	public function sidebar2_off_canvas_enable( $enable ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			return \Minimog::setting( 'product_archive_page_sidebar_2_off_canvas_enable' );
		}

		return $enable;
	}

	public function sidebar2_off_canvas_text( $text ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			$new_text = \Minimog::setting( 'product_archive_page_sidebar_2_off_canvas_toggle_text' );

			return '' !== $new_text ? $new_text : __( 'Filters', 'minimog' );
		}

		return $text;
	}

	public function add_sidebar_css_class( $classes ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			$sidebar_style = \Minimog::setting( 'product_archive_sidebar_style' );

			if ( ! empty( $sidebar_style ) ) {
				$classes[] = 'style-' . $sidebar_style;
			}
		}

		return $classes;
	}

	public function turn_sidebar_widget_collapsible( $enable ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			return true;
		}

		return $enable;
	}

	public function set_sidebar_single_width( $width ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			$new_width = \Minimog::setting( 'product_archive_single_sidebar_width' );

			if ( isset( $new_width['width'] ) && '' !== $new_width['width'] ) {
				return $new_width['width'];
			}
		}

		return $width;
	}

	public function set_sidebar_single_offset( $offset ) {
		if ( \Minimog_Woo::instance()->is_product_archive() ) {
			$new_offset = \Minimog::setting( 'product_archive_single_sidebar_offset' );

			if ( isset( $new_offset['width'] ) && '' !== $new_offset['width'] ) {
				/**
				 * Redux - Unit is included in dimensions type
				 * return $new_offset['width'] . 'px';
				 */
				return $new_offset['width'];
			}
		}

		return $offset;
	}

	public function change_star_rating_html( $rating_html, $rating, $count ) {
		$rating_html = \Minimog_Templates::render_rating( $rating, [ 'echo' => false ] );

		return $rating_html;
	}

	/**
	 * Change text of select options.
	 *
	 * @param $sorting_options
	 *
	 * @return mixed
	 */
	public function custom_product_sorting( $sorting_options ) {
		if ( isset( $sorting_options['menu_order'] ) ) {
			$sorting_options['menu_order'] = esc_html__( 'Default sorting', 'minimog' );
		}

		if ( isset( $sorting_options['popularity'] ) ) {
			$sorting_options['popularity'] = esc_html__( 'Popularity', 'minimog' );
		}

		if ( isset( $sorting_options['rating'] ) ) {
			$sorting_options['rating'] = esc_html__( 'Average rating', 'minimog' );
		}

		if ( isset( $sorting_options['date'] ) ) {
			$sorting_options['date'] = esc_html__( 'Latest', 'minimog' );
		}

		if ( isset( $sorting_options['price'] ) ) {
			$sorting_options['price'] = esc_html__( 'Price: low to high', 'minimog' );
		}

		if ( isset( $sorting_options['price-desc'] ) ) {
			$sorting_options['price-desc'] = esc_html__( 'Price: high to low', 'minimog' );
		}

		return $sorting_options;
	}

	public function override_pagination_args( $args ) {
		$args['prev_text'] = \Minimog_Templates::get_pagination_prev_text();
		$args['next_text'] = \Minimog_Templates::get_pagination_next_text();

		return $args;
	}

	/**
	 * Custom product title instead of default product title
	 * Change H2 => H3
	 * Add link
	 *
	 * @see woocommerce_template_loop_product_title()
	 */
	public function template_loop_product_title() {
		?>
		<h3 class="<?php echo esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title post-title-2-rows' ) ); ?>">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<?php
	}

	function shop_toolbars() {
		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked wc_print_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		// @hooked wc_print_notices - 10
		add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_begin_wrapper' ], 15 );
		// @hooked woocommerce_result_count - 20
		add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_right_toolbar_begin_wrapper' ], 25 );

		/**
		 * Change order template priority 30 -> 40.
		 */
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		if ( '1' === \Minimog::setting( 'shop_archive_sorting' ) ) {
			add_action( 'woocommerce_before_shop_loop', [ $this, 'wc_catalog_ordering' ], 40 );
		}

		if ( 'toolbar_right' === \Minimog::setting( 'shop_archive_filtering' )
		     && '1' === \Minimog::setting( 'product_archive_off_sidebar' )
		     && is_active_sidebar( self::SIDEBAR_FILTERS ) ) {
			add_action( 'woocommerce_before_shop_loop', [
				$this,
				'output_button_open_off_sidebar',
			], 30 );
		}

		add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_right_toolbar_end_wrapper' ], 90 );

		add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_action_end_wrapper' ], 100 );

		add_action( 'woocommerce_before_shop_loop', [ $this, 'add_shop_filter_actives_bar' ], 150 );

		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
		add_action( 'woocommerce_after_shop_loop', [ $this, 'pagination' ], 10 );
	}

	public function add_shop_action_begin_wrapper() {
		echo '<div id="archive-shop-actions" class="archive-shop-actions"><div class="row-flex items-center justify-space-between">';
	}

	public function add_shop_action_end_wrapper() {
		echo '</div></div>';
	}

	public function add_shop_action_right_toolbar_begin_wrapper() {
		echo '<div class="shop-actions-toolbar shop-actions-toolbar-right col"><div class="inner">';

		do_action( 'minimog/shop_archive/actions_toolbar_right/before' );
	}

	public function add_shop_action_right_toolbar_end_wrapper() {
		do_action( 'minimog/shop_archive/actions_toolbar_right/after' );

		echo '</div></div>';
	}

	public function output_open_off_sidebar() {
		if ( 'toolbar_left' === \Minimog::setting( 'shop_archive_filtering' )
		     && '1' === \Minimog::setting( 'product_archive_off_sidebar' )
		     && is_active_sidebar( self::SIDEBAR_FILTERS ) ) {
			$this->output_button_open_off_sidebar();
		}
	}

	public function output_button_open_off_sidebar() {
		if ( $this->wc_shortcode_products_has_paginate() ) {
			return;
		}
		?>
		<a href="#" class="btn-js-open-off-sidebar btn-open-shop-off-sidebar"
		   data-sidebar-target="primary">
			<span class="button-text"><?php esc_html_e( 'Filters', 'minimog' ); ?></span>
			<span class="button-icon far fa-angle-down"></span>
		</a>
		<?php
	}

	/**
	 * For some reasons Elementor ignore remove_action.
	 * We moved these hooks in action wp_init to avoid Elementor Editor ignore
	 */
	public function setup_product_loop() {
		// Remove thumbnail & sale flash. then use custom.
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );

		// Remove star rating then re-add to specify position layout.
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		// Remove price then re-add to specify position in layout.
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

		// Add link to the product title of loop.
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		add_action( 'woocommerce_shop_loop_item_title', [ $this, 'template_loop_product_title' ], 10 );
	}

	public function add_shop_filter_actives_bar() {
		if ( $this->wc_shortcode_products_has_paginate() ) {
			return;
		}
		?>
		<div id="active-filters-bar" class="active-filters-bar">
			<?php echo \Minimog_Woo::instance()->get_remove_active_filter_links( $_GET ); ?>
		</div>
		<?php
	}

	public function pagination() {
		if ( $this->wc_shortcode_products_has_paginate() ) {
			woocommerce_pagination();
		} else {
			\Minimog_Woo::instance()->get_pagination();
		}

	}

	public function wc_shortcode_products_has_paginate() {
		return wc_get_loop_prop( 'is_shortcode' ) && wc_get_loop_prop( 'is_paginated' );
	}

	public function wc_catalog_ordering() {
		/**
		 * Disable ordering for shortcode mode
		 */
		if ( $this->wc_shortcode_products_has_paginate() ) {
			return;
		}

		woocommerce_catalog_ordering();
	}
}

Archive_Product::instance()->initialize();
