<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Product_Layered_Nav' ) ) {
	class Minimog_WP_Widget_Product_Layered_Nav extends Minimog_WC_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-product-layered-nav';
			$this->widget_cssclass    = 'minimog-wp-widget-product-layered-nav minimog-wp-widget-filter';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Product Attribute Layered Nav', 'minimog' ) );
			$this->widget_description = esc_html__( 'Display a list of attributes to filter products in your store.', 'minimog' );
			$this->settings           = array(
				'title'             => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by', 'minimog' ),
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'attribute'         => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Attribute', 'minimog' ),
					'options' => [],
				),
				'selection_mode'    => array(
					'type'    => 'select',
					'std'     => 'multi',
					'label'   => esc_html__( 'Selection Mode', 'minimog' ),
					'options' => array(
						'single' => esc_html__( 'Single Choice', 'minimog' ),
						'multi'  => esc_html__( 'Multi Choice', 'minimog' ),
					),
				),
				'query_type'        => array(
					'type'      => 'select',
					'std'       => 'and',
					'label'     => esc_html__( 'Query type', 'minimog' ),
					'options'   => array(
						'and' => esc_html__( 'AND', 'minimog' ),
						'or'  => esc_html__( 'OR', 'minimog' ),
					),
					'condition' => [
						'selection_mode' => [
							'value' => 'multi',
						],
					],
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
						'swatches' => esc_html__( 'Swatches List', 'minimog' ),
					),
					'condition' => [
						'display_type' => [
							'value' => [ 'list', 'inline' ],
						],
					],
				),
				'labels'            => array(
					'type'      => 'select',
					'std'       => 'on',
					'label'     => esc_html__( 'Show labels', 'minimog' ),
					'options'   => array(
						'on'  => esc_html__( 'ON', 'minimog' ),
						'off' => esc_html__( 'OFF', 'minimog' ),
					),
					'condition' => [
						'display_type' => [
							'value'    => 'dropdown',
							'operator' => '!',
						],
						'list_style'   => [
							'value' => 'swatches',
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

		public function set_form_settings() {
			$attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_label;
				}
			}

			$this->settings['attribute']['options'] = $attribute_array;
		}

		function widget( $args, $instance ) {

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			$_chosen_attributes = \Minimog\Woo\Product_Query::get_layered_nav_chosen_attributes();
			$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : '';
			$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : 'and';
			$display_type       = isset( $instance['display_type'] ) ? $instance['display_type'] : 'list';

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = [
				'taxonomy'   => $taxonomy,
				'hide_empty' => '1',
			];

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name' :
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
					break;
				case 'id' :
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
					break;
				case 'menu_order' :
					$get_terms_args['menu_order'] = 'ASC';
					break;
			}

			$terms = get_terms( $get_terms_args );

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			switch ( $orderby ) {
				case 'name_num' :
					usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
					break;
				case 'parent' :
					usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
					break;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			if ( 'dropdown' === $display_type ) {
				wp_enqueue_script( 'selectWoo' );
				wp_enqueue_style( 'select2' );
				$found = $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
			} else {
				$found = $this->layered_nav_list( $terms, $taxonomy, $query_type, $instance );
			}

			$this->widget_end( $args, $instance );

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
		}

		/**
		 * Show dropdown layered nav.
		 *
		 * @param  array  $terms      Terms.
		 * @param  string $taxonomy   Taxonomy.
		 * @param  string $query_type Query Type.
		 *
		 * @return bool Will nav display?
		 */
		protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {
			global $wp;
			$found = false;

			if ( $taxonomy !== $this->get_current_taxonomy() ) {
				$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
				$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
				$taxonomy_filter_name = wc_attribute_taxonomy_slug( $taxonomy );
				$taxonomy_label       = wc_attribute_label( $taxonomy );

				/* translators: %s: taxonomy name */
				$any_label      = apply_filters( 'woocommerce_layered_nav_any_label', sprintf( __( 'Any %s', 'minimog' ), $taxonomy_label ), $taxonomy_label, $taxonomy );
				$multiple       = 'or' === $query_type;
				$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();

				$form_action = $this->get_current_page_url();
				$form_action = remove_query_arg( [
					'query_type_' . $taxonomy_filter_name,
					'filter_' . $taxonomy_filter_name,
				], $form_action );
				?>

				<form method="get" action="<?php echo esc_url( $form_action ); ?>" class="minimog-wp-widget-product-layered-nav-form">
					<select data-placeholder="<?php echo esc_attr( $any_label ); ?>"
					        name="<?php echo 'filter_' . esc_attr( $taxonomy_filter_name ); ?>"
					        class="filter-name minimog-wp-widget-product-layered-nav-dropdown dropdown_layered_nav_<?php echo esc_attr( $taxonomy_filter_name ); ?>"
						<?php echo( $multiple ? ' multiple="multiple"' : '' ); ?>
					>
						<option value=""><?php echo esc_html( $any_label ); ?></option>
						<?php
						foreach ( $terms as $term ) {

							// If on a term page, skip that term in widget list.
							if ( $term->term_id === $this->get_current_term_id() ) {
								continue;
							}

							// Get count based on current view.
							$option_is_set = in_array( $term->slug, $current_values, true );
							$count         = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

							// Only show options with count > 0.
							if ( 0 < $count ) {
								$found = true;
							} elseif ( 0 === $count && ! $option_is_set ) {
								continue;
							}

							echo '<option value="' . esc_attr( urldecode( $term->slug ) ) . '" ' . selected( $option_is_set, true, false ) . '>' . esc_html( $term->name ) . '</option>';
						}
						?>
					</select>
					<?php if ( 'or' === $query_type ) : ?>
						<input type="hidden" name="query_type_<?php echo esc_attr( $taxonomy_filter_name ); ?>" value="or" class="filter-query-type"/>
					<?php endif; ?>
				</form>
				<?php
			}

			return $found;
		}

		/**
		 * Show list based layered nav.
		 *
		 * @param  array  $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return bool   Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $query_type, $instance ) {
			$labels           = $this->get_value( $instance, 'labels' );
			$items_count      = $this->get_value( $instance, 'items_count' );
			$display_type     = $this->get_value( $instance, 'display_type' );
			$list_style       = $this->get_value( $instance, 'list_style' );
			$selection_mode   = $this->get_value( $instance, 'selection_mode' );
			$is_single_choice = 'single' === $selection_mode;

			$ul_class = 'show-labels-' . $labels;
			$ul_class .= ' show-display-' . $display_type;
			$ul_class .= ' show-items-count-' . $items_count;
			$ul_class .= ' ' . $taxonomy;

			if ( $is_single_choice ) {
				$ul_class   .= ' single-choice';
				$query_type = 'or';
			}

			$attr_id   = wc_attribute_taxonomy_id_by_name( $taxonomy );
			$attr_info = wc_get_attribute( $attr_id );

			if ( in_array( $display_type, [ 'list', 'inline' ] ) ) {
				if ( 'swatches' === $list_style ) {
					switch ( $attr_info->type ) {
						case 'color':
							$ul_class .= ' list-style-color';
							break;
						case 'image':
							$ul_class .= ' list-style-image';
							break;
						case 'text':
						default:
							$ul_class .= ' list-style-text';
							break;
					}
				} elseif ( 'checkbox' === $list_style ) {
					$ul_class .= $is_single_choice ? ' list-style-radio' : ' list-style-checkbox';
				} else {
					$ul_class .= ' list-style-' . $list_style;
				}
			}

			// List display.
			echo '<ul class="' . $ul_class . '">';

			$term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes   = \Minimog\Woo\Product_Query::get_layered_nav_chosen_attributes();
			$found                = false;
			$base_link            = $this->get_current_page_url();
			$taxonomy_filter_name = wc_attribute_taxonomy_slug( $taxonomy );
			$filter_name          = 'filter_' . $taxonomy_filter_name;
			$filtered_values      = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( wp_unslash( $_GET[ $filter_name ] ) ) ) : array(); // WPCS: input var ok, CSRF ok.
			$filtered_values      = array_map( 'sanitize_title', $filtered_values );
			$taxonomy_label       = wc_attribute_label( $taxonomy );
			$any_label            = apply_filters( 'woocommerce_layered_nav_any_label', sprintf( __( 'Any %s', 'minimog' ), $taxonomy_label ), $taxonomy_label, $taxonomy );
			$list_items           = [];

			// Add all link for single choice mode.
			if ( $is_single_choice && 'swatches' !== $list_style ) {
				$item_class = ! isset( $_GET[ $filter_name ] ) ? [ 'chosen' ] : [];
				$all_link   = remove_query_arg( [
					'filtering',
					$filter_name,
					'query_type_' . $taxonomy_filter_name,
				], $base_link );
				$count_html = '';
				$term_html  = '<a href="' . esc_url( $all_link ) . '" class="filter-link term-link" aria-label="' . esc_attr( $any_label ) . '"><span class="term-name">' . esc_html( $any_label ) . '</span>' . $count_html . '</a>';

				$list_items[] = '<li class="wc-layered-nav-term ' . esc_attr( implode( ' ', $item_class ) ) . '">' . $term_html . '</li>';
			}

			foreach ( $terms as $term ) {
				$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set  = in_array( $term->slug, $current_values );

				$count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// Skip the term for the current archive.
				/*if ( $this->get_current_term_id() === $term->term_id ) {
					continue;
				}*/

				// Only show options with count > 0.
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$link = remove_query_arg( $filter_name, $base_link );

				if ( $is_single_choice ) {
					$current_filter = ! $option_is_set ? array( $term->slug ) : array();
				} else {
					$current_filter = $filtered_values;
					if ( ! in_array( $term->slug, $current_filter, true ) ) {
						$current_filter[] = $term->slug;
					}

					// Add current filters to URL.
					foreach ( $current_filter as $key => $value ) {
						// Exclude query arg for current term archive term.
						if ( $value === $this->get_current_term_slug() ) {
							unset( $current_filter[ $key ] );
						}

						// Exclude self so filter can be unset on click.
						if ( $option_is_set && $value === $term->slug ) {
							unset( $current_filter[ $key ] );
						}
					}
				}

				if ( ! empty( $current_filter ) ) {
					asort( $current_filter );
					$link = add_query_arg( 'filtering', '1', $link );
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );

					// Add Query type Arg to URL.
					if ( 'or' === $query_type && ! ( 1 === count( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . wc_attribute_taxonomy_slug( $taxonomy ), 'or', $link );
					}
					$link = str_replace( '%2C', ',', $link );
				}

				$item_class      = $option_is_set ? [ 'chosen' ] : [];
				$item_link_class = 'filter-link term-link';
				$swatch_span     = '';

				if ( 'swatches' === $list_style ) :
					switch ( $attr_info->type ) :
						case 'color':
							$color           = get_term_meta( $term->term_id, 'sw_color', true ) ? : '#fff';
							$item_link_class .= ' hint--bounce hint--top';
							$swatch_span     = '<div class="term-shape"><span style="background: ' . $color . '" class="term-shape-bg"></span><span class="term-shape-border"></span></div>';

							break;
						case 'image':
							$val             = get_term_meta( $term->term_id, 'sw_image', true );
							$item_link_class .= ' hint--bounce hint--top';

							if ( ! empty( $val ) ) {
								$image_url = wp_get_attachment_thumb_url( $val );
							} else {
								$image_url = wc_placeholder_img_src();
							}

							$swatch_span = '<div class="term-shape"><span style="background-image: url(' . esc_attr( $image_url ) . ');" class="term-shape-bg"></span><span class="term-shape-border"></span></div>';

							break;
						case 'text':
						default:
							break;
					endswitch;
				endif;

				$count_html = '';
				if ( 'on' === $items_count ) {
					$count_html = ' ' . apply_filters(
							'woocommerce_layered_nav_count',
							'<span class="count">(' . absint( $count ) . ')</span>',
							$count,
							$term );
				}

				if ( $count > 0 || $option_is_set ) {
					$link      = apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy );
					$term_html = '<a href="' . esc_url( $link ) . '" class="' . esc_attr( $item_link_class ) . '" aria-label="' . esc_attr( $term->name ) . '">' . $swatch_span . '<span class="term-name">' . esc_html( $term->name ) . '</span>' . $count_html . '</a>';
				} else {
					$link      = false;
					$term_html = '<span>' . $swatch_span . '<span class="term-name">' . esc_html( $term->name ) . '</span>' . $count_html;
				}

				/**
				 * Use other hook instead of original hook to ignore wrong update html of Woo Brand.
				 */
				$term_html = apply_filters( 'minimog/widget/product_layered_nav/term_html', $term_html, $term, $link, $count );

				$list_items[] = '<li class="wc-layered-nav-term ' . esc_attr( implode( ' ', $item_class ) ) . '">' . $term_html . '</li>';
			}

			echo implode( '', $list_items );

			echo '</ul>';

			return $found;
		}
	}
}
