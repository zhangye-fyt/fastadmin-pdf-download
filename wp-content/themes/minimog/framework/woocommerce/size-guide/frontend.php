<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Size_Guide_Frontend' ) ) {
	class Minimog_Size_Guide_Frontend {

		private static $instance;
		private static $current_size_guide_id = null; // Store found size guide id to avoid duplicate query

		const POST_TYPE   = 'minimog_size_guide';
		const OPTION_NAME = 'minimog_size_guide';

		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Display size guide.
			add_action( 'woocommerce_before_single_product', [ $this, 'display_size_guide' ] );
		}

		/**
		 * Get option of size guide.
		 *
		 * @since 1.0.0
		 *
		 * @param string $option
		 * @param mixed  $default
		 *
		 * @return mixed
		 */
		public function get_option( $option = '', $default = false ) {
			if ( ! is_string( $option ) ) {
				return $default;
			}

			if ( empty( $option ) ) {
				return get_option( self::OPTION_NAME, $default );
			}

			return get_option( sprintf( '%s_%s', self::OPTION_NAME, $option ), $default );
		}

		/**
		 * Hooks to display size guide.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function display_size_guide() {
			global $product;

			if ( 'yes' !== get_option( 'minimog_size_guide' ) ) {
				return;
			}

			if ( 'yes' === $this->get_option( 'variable_only' ) && ! $product->is_type( 'variable' ) ) {
				return;
			}

			$guide_id = $this->get_current_size_guide_id();

			if ( ! $guide_id ) {
				return;
			}

			$guide_settings = get_post_meta( $product->get_id(), 'minimog_size_guide', true );
			$display        = ( is_array( $guide_settings ) && ! empty( $guide_settings['display'] ) ) ? $guide_settings['display'] : $this->get_option( 'display' );

			if ( 'tab' == $display ) {
				add_filter( 'woocommerce_product_tabs', array( $this, 'size_guide_tab' ) );
			} else {
				$button_position = ( is_array( $guide_settings ) && ! empty( $guide_settings['button_position'] ) ) ? $guide_settings['button_position'] : $this->get_option( 'button_position' );

				switch ( $button_position ) {
					case 'bellow_summary':
						add_action( 'woocommerce_single_product_summary', array( $this, 'size_guide_button' ), 23 );
						break;

					case 'bellow_price':
						add_action( 'woocommerce_single_product_summary', array( $this, 'size_guide_button' ), 15 );
						break;

					case 'above_button':
						add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'size_guide_button' ), 8 );
						break;
					case 'beside_attribute':
						add_action( 'minimog/product_variation_attribute/label/after', [
							$this,
							'show_size_guide_beside_an_attribute',
						], 10, 1 );
				}

				add_action( 'woocommerce_after_single_product_summary', [ $this, 'output_modal_size_guide' ], 12 );
			}
		}

		/**
		 * Add size guide tab to product tabs.
		 *
		 * @since 1.0.0
		 *
		 * @param array $tabs
		 *
		 * @return array
		 */
		public function size_guide_tab( $tabs ) {
			$guide_id = $this->get_current_size_guide_id();

			if ( ! empty( $guide_id ) ) {
				$text        = $this->get_option( 'button_text' );
				$button_text = ! empty( $text ) ? $text : __( 'Size Chart', 'minimog' );

				$tabs['minimog_size_guide'] = array(
					'title'    => $button_text,
					'priority' => 50,
					'callback' => [ $this, 'size_guide_content' ],
				);
			}

			return $tabs;
		}

		/**
		 * Get HTML of size guide button
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		protected function get_size_guide_button() {
			$text        = $this->get_option( 'button_text' );
			$button_text = ! empty( $text ) ? $text : __( 'Size Chart', 'minimog' );

			return apply_filters(
				'minimog_size_guide_button',
				sprintf(
					'<p class="product-size-guide">
						<a href="#" data-minimog-toggle="modal" data-minimog-target="#minimog-size-guide" class="size-guide-button">
							<span class="svg-icon">
								<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 512 512"><path fill="currentColor" d="M448 288C483.3 288 512 316.7 512 352V448C512 483.3 483.3 512 448 512H64C30.86 512 3.608 486.8 .3306 454.5C.112 452.4 0 450.2 0 448V64C0 28.65 28.65 0 64 0H160C195.3 0 224 28.65 224 64V288H448zM192 192V128H128C119.2 128 112 120.8 112 112C112 103.2 119.2 96 128 96H192V64C192 46.33 177.7 32 160 32H64C46.33 32 32 46.33 32 64V448C32 449.1 32.06 450.2 32.17 451.3C33.8 467.4 47.45 480 64 480H448C465.7 480 480 465.7 480 448V352C480 334.3 465.7 320 448 320H416V384C416 392.8 408.8 400 400 400C391.2 400 384 392.8 384 384V320H320V384C320 392.8 312.8 400 304 400C295.2 400 288 392.8 288 384V320H224V384C224 392.8 216.8 400 208 400C199.2 400 192 392.8 192 384V320H128C119.2 320 112 312.8 112 304C112 295.2 119.2 288 128 288H192V224H128C119.2 224 112 216.8 112 208C112 199.2 119.2 192 128 192H192z"/></svg>
							</span>
							%s
						</a>
					</p>',
					esc_html( $button_text )
				)
			);
		}

		/**
		 * Display the button to open size guide.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function size_guide_button() {
			echo $this->get_size_guide_button();
		}

		/**
		 * Size guide panel.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function output_modal_size_guide() {
			global $product;

			$guide_settings = get_post_meta( $product->get_id(), 'minimog_size_guide', true );

			$display = ( is_array( $guide_settings ) && ! empty( $guide_settings['display'] ) ) ? $guide_settings['display'] : $this->get_option( 'display' );

			$classes = [
				'minimog-modal',
				'minimog-size-guide',
				'minimog-size-guide--modal',
				'minimog-size-guide--' . $display,
			];

			$classes = apply_filters( 'minimog_product_size_guide_modal_classes', $classes, $display );
			?>
			<div class="<?php echo esc_attr( implode( ' ', $classes ) ) ?>" id="minimog-size-guide">
				<div class="modal-overlay"></div>
				<div class="modal-content">
					<div class="button-close-modal" role="button" aria-label="<?php esc_attr_e( 'Close', 'minimog' ); ?>"></div>
					<div class="modal-content-wrap">
						<div class="modal-content-inner">
							<div class="modal-content-header">
								<h3 class="modal-title"><?php esc_html_e( 'Size Chart', 'minimog' ); ?></h3>
							</div>
							<div class="modal-content-body">
								<?php $this->size_guide_content(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Display product size guide as a tab.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function size_guide_content() {
			$guide_id = $this->get_current_size_guide_id();

			if ( ! $guide_id ) {
				return;
			}

			$guide_query = new WP_Query( [
				'p'           => $guide_id,
				'post_type'   => self::POST_TYPE,
				'post_status' => 'publish',
			] );

			if ( ! $guide_query->have_posts() ) {
				return;
			}

			echo '<div class="minimog-size-guide">';

			while ( $guide_query->have_posts() ) {
				$guide_query->the_post();

				ob_start();
				the_content();
				$content = ob_get_clean();

				if ( ! empty( $content ) ) {
					echo '<div class="minimog-size-guide--global-content">' . $content . '</div>';
				}

				$size_guides = get_post_meta( $guide_id, 'size_guides', true );

				if ( $size_guides && is_array( $size_guides ) && ! empty( $size_guides['tables'] ) ) {
					foreach ( $size_guides['tables'] as $index => $table ) {
						echo '<div class="minimog-size-guide__wrapper">';

						if ( ! empty( $size_guides['names'][ $index ] ) ) {
							echo '<h4 class="minimog-size-guide__name">' . wp_kses_post( $size_guides['names'][ $index ] ) . '</h4>';
						}

						if ( ! empty( $size_guides['descriptions'][ $index ] ) ) {
							echo '<div class="minimog-size-guide__description">' . wp_kses_post( $size_guides['descriptions'][ $index ] ) . '</div>';
						}

						if ( ! empty( $table ) ) {
							$table         = json_decode( $table, true );
							$tableHeadHTML = $tableBodyHTML = '';

							foreach ( $table as $row => $columns ) {
								$rowHTML   = '';
								$empty_row = true;

								$col_tag = ( 0 === $row ) ? 'th' : 'td';

								foreach ( $columns as $col_content ) {
									if ( ! empty( $col_content ) ) {
										$empty_row = false;
									}
									$rowHTML .= sprintf( '<%1$s>%2$s</%1$s>', $col_tag, $col_content );
								}

								if ( ! $empty_row ) {
									$rowHTML = "<tr>{$rowHTML}</tr>";
									if ( 0 === $row ) {
										$tableHeadHTML .= $rowHTML;
									} else {
										$tableBodyHTML .= $rowHTML;
									}
								}
							}

							if ( ! empty( $tableHeadHTML ) || ! empty( $tableBodyHTML ) ) {
								echo '<div class="minimog-size-guide__table-wrapper">';
								echo '<table class="minimog-size-guide__table">';
								if ( ! empty( $tableHeadHTML ) ) {
									echo "<thead>{$tableHeadHTML}</thead>";
								}

								if ( ! empty( $tableBodyHTML ) ) {
									echo "<tbody>{$tableBodyHTML}</tbody>";
								}
								echo '</table>';
								echo '</div>';
							}
						}

						if ( ! empty( $size_guides['information'][ $index ] ) ) {
							echo '<div class="minimog-size-guide__info">' . wp_kses_post( $size_guides['information'][ $index ] ) . '</div>';
						}

						echo '</div>';
					}
				}
			}
			wp_reset_postdata();

			echo '</div>';
		}

		/**
		 * Get assigned size guide of the product.
		 *
		 * @since 1.0.0
		 *
		 * @param int|object $object Product object
		 *
		 * @return int|bool
		 */
		public function get_product_size_guide_id( $object = 0 ) {
			global $product;

			$_product = ! empty( $object ) ? wc_get_product( $object ) : $product;

			if ( ! $_product ) {
				return false;
			}

			$size_guide = get_post_meta( $_product->get_id(), 'minimog_size_guide', true );

			// Return selected guide.
			if ( is_array( $size_guide ) ) {
				if ( 'none' == $size_guide['guide'] ) {
					return false;
				}

				if ( ! empty( $size_guide['guide'] ) ) {
					return $size_guide['guide'];
				}
			}

			global $wpdb;
			$categories = $_product->get_category_ids();

			/**
			 * Firstly, get size guide that assign for these categories directly.
			 *
			 * @note Used custom SQL instead of WP_Query to avoid some 3rd plugins change tax terms.
			 *       This caused wrong size chart display.
			 */
			if ( ! empty( $categories ) ) {
				$sql = "SELECT size_chart.ID 
					FROM $wpdb->posts AS size_chart 
					LEFT JOIN $wpdb->term_relationships AS term_rel ON (size_chart.ID = term_rel.object_id) 
					INNER JOIN $wpdb->postmeta AS size_chart_meta ON ( size_chart.ID = size_chart_meta.post_id )
					WHERE 1=1 
						AND term_rel.term_taxonomy_id IN (" . implode( ', ', $categories ) . ")
					    AND ( size_chart_meta.meta_key = 'size_guide_category' AND size_chart_meta.meta_value NOT IN ('none','all') )
					    AND size_chart.post_type = 'minimog_size_guide' 
						AND size_chart.post_status = 'publish'
					GROUP BY size_chart.ID
					ORDER BY size_chart.post_date DESC
					LIMIT 0, 1";

				$guides = absint( $wpdb->get_var( $sql ) );

				if ( ! empty( $guides ) ) {
					return $this->get_translated_object_id( $guides, self::POST_TYPE );
				}
			}

			/**
			 * Return global size guide if exist one.
			 */
			$sql = "SELECT size_chart.ID 
					FROM $wpdb->posts AS size_chart 
					INNER JOIN $wpdb->postmeta AS size_chart_meta 
						ON ( size_chart.ID = size_chart_meta.post_id )
					WHERE ( size_chart_meta.meta_key = 'size_guide_category' AND size_chart_meta.meta_value = 'all' ) 
						AND size_chart.post_type = 'minimog_size_guide' 
						AND size_chart.post_status = 'publish'
					GROUP BY size_chart.ID
					ORDER BY size_chart.post_date DESC
					LIMIT 0, 1";

			$guides = absint( $wpdb->get_var( $sql ) );

			if ( ! empty( $guides ) ) {
				return $this->get_translated_object_id( $guides, self::POST_TYPE );
			}

			return false;
		}

		public function get_current_size_guide_id() {
			if ( null === self::$current_size_guide_id ) {
				self::$current_size_guide_id = $this->get_product_size_guide_id();
			}

			return self::$current_size_guide_id;
		}

		/**
		 * Get translated object ID if the WPML plugin is installed
		 * Return the original ID if this plugin is not installed
		 *
		 * @since 1.0.0
		 *
		 * @param int    $id            The object ID
		 * @param string $type          The object type 'post', 'page', 'post_tag', 'category' or 'attachment'. Default is 'page'
		 * @param bool   $original      Set as 'true' if you want WPML to return the ID of the original language element if the translation is missing.
		 * @param bool   $language_code If set, forces the language of the returned object and can be different than the displayed language.
		 *
		 * @return mixed
		 */
		function get_translated_object_id( $id, $type = 'page', $original = true, $language_code = null ) {
			return apply_filters( 'wpml_object_id', $id, $type, $original, $language_code );
		}

		public function show_size_guide_beside_an_attribute( $attribute_name ) {
			$attributes_attached = $this->get_option( 'button_beside_attribute' );
			$attributes_attached = is_array( $attributes_attached ) ? $attributes_attached : array();

			if ( in_array( $attribute_name, $attributes_attached ) ) {
				$this->size_guide_button();
			}
		}
	}

	Minimog_Size_Guide_Frontend::instance()->initialize();
}
