<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Product_Price_Filter' ) ) {
	class Minimog_WP_Widget_Product_Price_Filter extends Minimog_WC_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-product-price-filter';
			$this->widget_cssclass    = 'minimog-wp-widget-product-price-filter minimog-wp-widget-filter';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Product Price Filter', 'minimog' ) );
			$this->widget_description = esc_html__( 'Display a price range list to filter products in your store.', 'minimog' );
			$this->settings           = array(
				'title'            => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Price Filter', 'minimog' ),
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'display_type'     => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Display type', 'minimog' ),
					'options' => array(
						'list'   => esc_html__( 'List', 'minimog' ),
						'inline' => esc_html__( 'Inline', 'minimog' ),
					),
				),
				'list_style'       => array(
					'type'    => 'select',
					'std'     => 'normal',
					'label'   => esc_html__( 'List Style', 'minimog' ),
					'options' => array(
						'normal' => esc_html__( 'Normal List', 'minimog' ),
						'radio'  => esc_html__( 'Radio List', 'minimog' ),
					),
				),
				'enable_collapsed' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Collapsed ?', 'minimog' ),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			if ( ! is_shop() && ! is_product_taxonomy() ) {
				return;
			}

			// If there are not posts and we're not filtering, hide the widget.
			if ( ! \Minimog\Woo\Product_Query::is_main_query_has_post() && ! isset( $_GET['min_price'] ) && ! isset( $_GET['max_price'] ) ) { // WPCS: input var ok, CSRF ok.
				return;
			}

			$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
			$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';

			// Find min and max price in current result set.
			$prices = $this->get_min_max_prices();

			$min = floor( $prices->min_price );
			$max = ceil( $prices->max_price );

			if ( $min === $max ) {
				return;
			}

			$this->widget_start( $args, $instance );

			/**
			 * Adjust max if the store taxes are not displayed how they are stored.
			 * Min is left alone because the product may not be taxable.
			 * Kicks in when prices excluding tax are displayed including tax.
			 */
			if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
				$tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
				$class_max   = $max;

				foreach ( $tax_classes as $tax_class ) {
					if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
						$class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );
					}
				}

				$max = $class_max;
			}

			$links = $this->generate_price_links( $min, $max, $min_price, $max_price );

			$display_type = $this->get_value( $instance, 'display_type' );
			$list_style   = $this->get_value( $instance, 'list_style' );

			$class = 'minimog-product-price-filter';
			$class .= ' show-display-' . $display_type;
			$class .= ' list-style-' . $list_style;
			$class .= ' single-choice';

			if ( ! empty( $links ) ) {
				?>
				<ul class="<?php echo esc_attr( $class ); ?>">
					<?php foreach ( $links as $link ) : ?>
						<li class="<?php echo esc_attr( $link['item_class'] ); ?>">
							<a href="<?php echo esc_url( $link['href'] ); ?>"
							   class="filter-link"><?php echo '' . $link['title']; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php
			}
			$this->widget_end( $args, $instance );
		}

		private function generate_price_links( $min, $max, $min_price, $max_price ) {
			$links = array();

			$link          = $this->get_current_page_url();
			$link_no_price = remove_query_arg( 'min_price', $link );
			$link_no_price = remove_query_arg( 'max_price', $link_no_price );

			$need_more = false;

			$steps = apply_filters( 'minimog/widget/product_price_filter/total_steps', 5 );

			$step_value = $max / $steps;

			if ( $step_value < 10 ) {
				$step_value = 10;
			}

			$step_value = round( $step_value, -1 );

			// Link to all prices
			$all_link = array(
				'href'       => $link_no_price,
				'title'      => esc_html__( 'All', 'minimog' ),
				'item_class' => '',
			);

			if ( empty( $min_price ) && empty( $max_price ) ) {
				$all_link['item_class'] = 'chosen';
			}

			$links[] = $all_link;

			for ( $i = 0; $i < (int) $steps; $i++ ) {

				$step_class = $href = '';

				$step_min = $step_value * $i;

				$step_max = $step_value * ( $i + 1 );

				if ( $step_max > $max ) {
					$need_more = true;
					$i++;
					break;
				}

				$href = add_query_arg( 'min_price', $step_min, $link );
				$href = add_query_arg( 'max_price', $step_max, $href );
				$href = add_query_arg( 'filtering', '1', $href );

				$step_title = wc_format_price_range( $step_min, $step_max );

				if ( ! empty( $min_price ) && ! empty( $max_price )
				     && ( $min_price >= $step_min && $max_price <= $step_max )
				     || ( $i == 0 && ! empty( $max_price ) && $max_price <= $step_max ) ) {
					$step_class = 'chosen';
				}

				$count = $this->get_filtered_product_count( $step_min, $step_max );

				// Skip link that has no product price in.
				if ( 0 >= $count ) {
					continue;
				}

				$links[] = array(
					'href'       => $href,
					'title'      => $step_title,
					'item_class' => $step_class,
					'count'      => $count,
				);
			}

			if ( $max > $step_max ) {
				$need_more = true;
				$step_min  = $step_value * $i;
			}

			if ( $need_more ) {

				$step_class = $href = '';

				$href = add_query_arg( 'min_price', $step_min, $link );
				$href = add_query_arg( 'max_price', $max, $href );
				$href = add_query_arg( 'filtering', '1', $href );

				$step_title = wc_price( $step_min ) . ' +';

				if ( $min_price >= $step_min && $max_price <= $max ) {
					$step_class = 'chosen';
				}

				$count = $this->get_filtered_product_count( $step_min, $max );

				// Skip link that has no product price in.
				if ( $count > 0 ) {
					$links[] = array(
						'href'       => $href,
						'title'      => $step_title,
						'item_class' => $step_class,
						'count'      => $count,
					);
				}
			}

			return $links;
		}

		/**
		 * @return array
		 */
		protected function get_min_max_prices() {
			global $wpdb;

			$sql = "SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}";

			return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
		}

		/**
		 * Count products after other filters have occurred by adjusting the main query.
		 *
		 * @param  float $min_price
		 * @param  float $max_price
		 *
		 * @return int
		 */
		protected function get_filtered_product_count( $min_price, $max_price ) {
			global $wpdb;

			$tax_query  = \Minimog\Woo\Product_Query::get_main_tax_query();
			$meta_query = \Minimog\Woo\Product_Query::get_main_meta_query();

			$meta_query      = new WP_Meta_Query( $meta_query );
			$tax_query       = new WP_Tax_Query( $tax_query );
			$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
			$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );
			$price_query_sql = \Minimog\Woo\Product_Query::instance()->get_main_price_query_sql( $min_price, $max_price );

			$sql = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) FROM {$wpdb->posts} ";
			$sql .= $tax_query_sql['join'] . $meta_query_sql['join'] . $price_query_sql['join'];
			$sql .= " WHERE {$wpdb->posts}.post_type = 'product' AND {$wpdb->posts}.post_status = 'publish' ";
			$sql .= $tax_query_sql['where'] . $meta_query_sql['where'] . $price_query_sql['where'];

			$search = \Minimog\Woo\Product_Query::get_main_search_query_sql();
			if ( $search ) {
				$sql .= ' AND ' . $search;
			}

			return absint( $wpdb->get_var( $sql ) ); // WPCS: unprepared SQL ok.
		}
	}
}
