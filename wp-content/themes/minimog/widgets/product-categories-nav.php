<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Product_Categories_Layered_Nav' ) ) {
	class Minimog_WP_Widget_Product_Categories_Layered_Nav extends Minimog_WC_Widget_Base {

		const TAXONOMY_NAME = 'product_cat';
		const FILTER_NAME   = 'filter_' . self::TAXONOMY_NAME;

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-product-categories-layered-nav';
			$this->widget_cssclass    = 'minimog-wp-widget-product-categories-layered-nav minimog-wp-widget-filter';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Product Categories Layered Nav', 'minimog' ) );
			$this->widget_description = esc_html__( 'Shows categories in a widget which lets you narrow down the list of products when viewing products.', 'minimog' );
			$this->settings           = array(
				'title'             => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter By Categories', 'minimog' ),
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'orderby'           => array(
					'type'    => 'select',
					'std'     => 'name',
					'label'   => __( 'Order by', 'minimog' ),
					'options' => array(
						'order' => __( 'Category order', 'minimog' ),
						'name'  => __( 'Name', 'minimog' ),
					),
				),
				'display_type'      => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'Display type', 'minimog' ),
					'options' => array(
						'list'     => esc_html__( 'Vertical List', 'minimog' ),
						'inline'   => esc_html__( 'Horizontal List', 'minimog' ),
						'dropdown' => esc_html__( 'Dropdown', 'minimog' ),
					),
				),
				'list_style'        => array(
					'type'      => 'select',
					'std'       => 'normal',
					'label'     => esc_html__( 'List Style', 'minimog' ),
					'options'   => array(
						'normal'   => esc_html__( 'Normal List', 'minimog' ),
						'checkbox' => esc_html__( 'Check List', 'minimog' ),
					),
					'condition' => [
						'display_type' => [
							'value' => [ 'list', 'inline' ],
						],
					],
				),
				'items_count'       => array(
					'type'    => 'select',
					'std'     => 'on',
					'label'   => esc_html__( 'Show items count', 'minimog' ),
					'options' => array(
						'on'  => esc_html__( 'ON', 'minimog' ),
						'off' => esc_html__( 'OFF', 'minimog' ),
					),
				),
				'show_hierarchy'    => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show hierarchy', 'minimog' ),
				),
				'enable_scrollable' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Enable scrollable', 'minimog' ),
				),
				'enable_collapsed'  => array(
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

			if ( ! taxonomy_exists( self::TAXONOMY_NAME ) ) {
				return;
			}

			$orderby = $this->get_value( $instance, 'orderby' );

			$term_args = [
				'taxonomy'   => self::TAXONOMY_NAME,
				'hide_empty' => '1',
				'parent'     => 0,
				'menu_order' => false,
			];

			if ( 'order' === $orderby ) {
				$term_args['orderby']  = 'meta_value_num';
				$term_args['meta_key'] = 'order';
			}

			// Get only parent terms. Methods will recursively retrieve children.
			$terms = get_terms( $term_args );

			if ( empty( $terms ) || is_wp_error( $terms ) ) {
				return;
			}

			$display_type = isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

			ob_start();

			$this->widget_start( $args, $instance );

			if ( 'dropdown' === $display_type ) {
				wp_enqueue_script( 'selectWoo' );
				wp_enqueue_style( 'select2' );
				$found = $this->layered_nav_dropdown( $terms, $instance );
			} else {
				$found = $this->layered_nav_list( $terms, $instance );
			}

			$this->widget_end( $args, $instance );

			// Force found when option is selected - do not force found on taxonomy attributes.
			$_chosen_attributes = \Minimog\Woo\Product_Query::get_layered_nav_chosen_attributes();
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( self::TAXONOMY_NAME, $_chosen_attributes ) ) {
				$found = true;
			}

			echo ob_get_clean();
		}

		protected function layered_nav_dropdown( $terms, $instance, $depth = 0 ) {
			$found          = false;
			$items_count    = $this->get_value( $instance, 'items_count' );
			$show_hierarchy = $this->get_value( $instance, 'show_hierarchy' );

			if ( self::TAXONOMY_NAME !== $this->get_current_taxonomy() ) {
				$query_type = 'or';
				$multiple   = 'or' === $query_type;
				$any_label  = __( 'Any Category', 'minimog' );

				$current_values = $this->get_chosen_terms( self::FILTER_NAME );
				$form_action    = $this->get_current_page_url();
				$form_action    = remove_query_arg( [
					self::FILTER_NAME,
				], $form_action );
				?>
				<?php if ( 0 === $depth ) : ?>
					<form method="get" action="<?php echo esc_url( $form_action ); ?>" class="minimog-wp-widget-product-layered-nav-form">
					<select data-placeholder="<?php echo esc_attr( $any_label ); ?>"
					name="<?php echo self::FILTER_NAME; ?>"
					class="filter-name minimog-wp-widget-product-layered-nav-dropdown minimog-product-categories-dropdown-layered-nav"
					<?php echo( $multiple ? ' multiple="multiple"' : '' ); ?>
					>
					<option value=""><?php echo esc_html( $any_label ); ?></option>
				<?php endif; ?>
				<?php
				foreach ( $terms as $term ) {
					// If on a term page, skip that term in widget list.
					if ( $term->term_id === $this->get_current_term_id() ) {
						continue;
					}

					$option_is_set = in_array( $term->term_id, $current_values );

					$child_ids = get_terms( [
						'taxonomy' => self::TAXONOMY_NAME,
						'child_of' => $term->term_id, // Get all descendants.
						'fields'   => 'ids',
					] );

					$child_ids[] = $term->term_id;
					$count       = $this->get_filtered_term_product_counts_for_hierarchy_tax( $child_ids, self::TAXONOMY_NAME, $query_type );

					// Only show options with count > 0
					if ( 0 < $count ) {
						$found = true;
					} elseif ( 0 === $count && ! $option_is_set ) {
						continue;
					}

					$count_html = 'on' === $items_count ? ' (' . $count . ')' : '';

					echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( $option_is_set, true, false ) . '>' . str_repeat( '-', 2 * $depth ) . esc_html( $term->name ) . $count_html . '</option>';

					if ( $show_hierarchy ) {
						$child_terms = get_terms( array(
							'taxonomy'   => self::TAXONOMY_NAME,
							'hide_empty' => 1,
							'parent'     => $term->term_id,
						) );

						if ( ! empty( $child_terms ) ) {
							$found |= $this->layered_nav_dropdown( $child_terms, $instance, $depth + 1 );
						}
					}
				}
				?>
				<?php if ( 0 === $depth ) : ?>
					</select>
					</form>
				<?php endif; ?>
				<?php
			}

			return $found;
		}

		protected function layered_nav_list( $terms, $instance, $depth = 0 ) {
			$items_count    = $this->get_value( $instance, 'items_count' );
			$display_type   = $this->get_value( $instance, 'display_type' );
			$list_style     = $this->get_value( $instance, 'list_style' );
			$show_hierarchy = $this->get_value( $instance, 'show_hierarchy' );
			$chosen_terms   = $this->get_chosen_terms( self::FILTER_NAME );
			$found          = false;

			$class = [
				'show-display-' . $display_type,
				'show-items-count-' . $items_count,
				'list-style-' . $list_style,
			];

			if ( $depth > 0 ) {
				$class[] = 'children';
			}

			echo '<ul class="' . esc_attr( implode( ' ', $class ) ) . '">';

			if ( self::TAXONOMY_NAME !== $this->get_current_taxonomy() ) {
				$base_link = $this->get_current_page_url();

				foreach ( $terms as $term ) {
					$option_is_set = in_array( $term->term_id, $chosen_terms );
					$child_ids     = get_terms( [
						'taxonomy' => self::TAXONOMY_NAME,
						'child_of' => $term->term_id, // Get all descendants.
						'fields'   => 'ids',
					] );

					$child_ids[] = $term->term_id;
					$count       = $this->get_filtered_term_product_counts_for_hierarchy_tax( $child_ids, self::TAXONOMY_NAME, 'or' );

					// Only show options with count > 0.
					if ( $count > 0 ) {
						$found = true;
					} else {
						continue;
					}

					$current_filter = isset( $_GET[ self::FILTER_NAME ] ) ? explode( ',', wc_clean( $_GET[ self::FILTER_NAME ] ) ) : array();
					$current_filter = array_map( 'intval', $current_filter );

					if ( ! in_array( $term->term_id, $current_filter ) ) {
						$current_filter[] = $term->term_id;
					}

					$link = remove_query_arg( self::FILTER_NAME, $base_link );

					// Add current filters to URL.
					foreach ( $current_filter as $key => $value ) {
						// Exclude query arg for current term archive term
						/*if ( $value === $this->get_current_term_id() ) {
							unset( $current_filter[ $key ] );
						}*/

						// Exclude self so filter can be unset on click.
						if ( $option_is_set && $value === $term->term_id ) {
							unset( $current_filter[ $key ] );
						}
					}

					if ( ! empty( $current_filter ) ) {
						$link = add_query_arg( array(
							'filtering'       => '1',
							self::FILTER_NAME => implode( ',', $current_filter ),
						), $link );
					}

					$item_class = [ 'wc-layered-nav-term' ];
					$link_class = 'filter-link';

					if ( $option_is_set ) {
						$item_class[] = 'chosen';
					}

					$count_html = 'on' === $items_count ? '<span class="count">(' . $count . ')</span>' : '';

					echo '<li class="' . esc_attr( implode( ' ', $item_class ) ) . '">';

					printf(
						'<a href="%1$s" class="%2$s">%3$s %4$s</a>',
						esc_url( $link ),
						esc_attr( $link_class ),
						esc_html( $term->name ),
						$count_html
					);

					if ( $show_hierarchy ) {
						$child_terms = get_terms( [
							'taxonomy'   => self::TAXONOMY_NAME,
							'hide_empty' => 1,
							'parent'     => $term->term_id,
						] );

						if ( ! empty( $child_terms ) ) {
							$found |= $this->layered_nav_list( $child_terms, $instance, $depth + 1 );
						}
					}

					echo '</li>';
				}
			} else {
				$found = true;

				foreach ( $terms as $term ) {
					$option_is_set = in_array( $term->term_id, $chosen_terms );
					$term_link     = get_term_link( $term );
					$link          = $term_link;

					$item_class = [ 'wc-layered-nav-term' ];
					$link_class = 'item-link';

					if ( $option_is_set ) {
						$item_class[] = 'chosen';
					}

					echo '<li class="' . esc_attr( implode( ' ', $item_class ) ) . '">';

					printf(
						'<a href="%1$s" class="%2$s">%3$s</a>',
						esc_url( $link ),
						esc_attr( $link_class ),
						esc_html( $term->name )
					);

					if ( $show_hierarchy ) {
						$child_terms = get_terms( [
							'taxonomy'   => self::TAXONOMY_NAME,
							'hide_empty' => 1,
							'parent'     => $term->term_id,
						] );

						if ( ! empty( $child_terms ) ) {
							$this->layered_nav_list( $child_terms, $instance, $depth + 1 );
						}
					}

					echo '</li>';
				}
			}
			echo '</ul>';

			return $found;
		}
	}
}
