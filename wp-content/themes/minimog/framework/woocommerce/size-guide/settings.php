<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Size_Guide_Settings' ) ) {
	class Minimog_Size_Guide_Settings {

		/**
		 * Instance
		 *
		 * @var $instance
		 */
		private static $instance;


		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		const POST_TYPE   = 'minimog_size_guide';
		const OPTION_NAME = 'minimog_size_guide';

		public function initialize() {
			add_filter( 'woocommerce_get_sections_products', array( $this, 'size_guide_section' ), 10, 2 );
			add_filter( 'woocommerce_get_settings_products', array( $this, 'size_guide_settings' ), 10, 2 );

			// Make sure the post types are loaded for imports
			add_action( 'import_start', array( $this, 'register_post_type' ) );

			if ( get_option( 'minimog_size_guide' ) != 'yes' ) {
				return;
			}

			add_action( 'init', [ $this, 'register_post_type' ], 1 );

			// Handle post columns
			add_filter( sprintf( 'manage_%s_posts_columns', self::POST_TYPE ), array( $this, 'edit_admin_columns' ) );
			add_action( sprintf( 'manage_%s_posts_custom_column', self::POST_TYPE ), array(
				$this,
				'manage_custom_columns',
			), 10, 2 );

			// Add meta boxes.
			add_action( 'add_meta_boxes', array( $this, 'meta_boxes' ), 1 );
			add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );

			// Enqueue style and javascript
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// Add JS templates to footer.
			add_action( 'admin_print_scripts', array( $this, 'templates' ) );

			// Add options to product.
			add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tab' ] );
			add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panel' ] );
			add_action( 'woocommerce_process_product_meta', [ $this, 'process_product_meta' ] );
		}

		/**
		 * Add Size Guide settings section to the Products setting tab.
		 *
		 * @since 1.0.0
		 *
		 * @param array $sections
		 *
		 * @return array
		 */
		public function size_guide_section( $sections ) {
			$sections['minimog_size_guide'] = __( 'Size Guide', 'minimog' );

			return $sections;
		}

		/**
		 * Adds a new setting field to products tab.
		 *
		 * @see   \WC_Admin_Settings::output_fields()
		 * @since 1.0.0
		 *
		 * @param array $settings
		 *
		 * @return array
		 */
		public function size_guide_settings( $settings, $section ) {
			if ( 'minimog_size_guide' != $section ) {
				return $settings;
			}

			$attribute_taxonomies = wc_get_attribute_taxonomies();
			$attribute_array      = array();

			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$attribute_name = wc_attribute_taxonomy_name( $tax->attribute_name );

					$attribute_array[ $attribute_name ] = $tax->attribute_label;
				}
			}

			$settings_size_guide = array(
				array(
					'name' => esc_html__( 'Size Guide', 'minimog' ),
					'type' => 'title',
					'id'   => self::OPTION_NAME . '_options',
				),
				array(
					'name'          => esc_html__( 'Enable Size Guide', 'minimog' ),
					'desc'          => esc_html__( 'Enable product size guides', 'minimog' ),
					'id'            => self::OPTION_NAME,
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'start',
				),
				array(
					'desc'          => esc_html__( 'Enable on variable products only', 'minimog' ),
					'id'            => self::OPTION_NAME . '_variable_only',
					'default'       => 'no',
					'type'          => 'checkbox',
					'checkboxgroup' => 'end',
				),
				array(
					'name'    => esc_html__( 'Guide Display', 'minimog' ),
					'id'      => self::OPTION_NAME . '_display',
					'default' => 'tab',
					'class'   => 'wc-enhanced-select',
					'type'    => 'select',
					'options' => array(
						'tab'   => esc_html__( 'In product tabs', 'minimog' ),
						'popup' => esc_html__( 'Popup by clicking on a button', 'minimog' ),
					),
				),
				array(
					'name'    => esc_html__( 'Button Position', 'minimog' ),
					'id'      => self::OPTION_NAME . '_button_position',
					'default' => 'bellow_summary',
					'class'   => 'wc-enhanced-select',
					'type'    => 'select',
					'options' => array(
						'bellow_summary'   => esc_html__( 'Below short description', 'minimog' ),
						'bellow_price'     => esc_html__( 'Below price', 'minimog' ),
						'above_button'     => esc_html__( 'Above Add To Cart button', 'minimog' ),
						'beside_attribute' => esc_html__( 'Beside an attribute', 'minimog' ),
					),
				),
				array(
					'name'    => esc_html__( 'Attach to attributes', 'minimog' ),
					'id'      => self::OPTION_NAME . '_button_beside_attribute',
					'class'   => 'wc-enhanced-select',
					'type'    => 'multiselect',
					'options' => $attribute_array,
				),
				array(
					'name'    => esc_html__( 'Button Text', 'minimog' ),
					'id'      => self::OPTION_NAME . '_button_text',
					'default' => esc_html__( 'Size Chart', 'minimog' ),
					'type'    => 'text',
				),
				array(
					'type' => 'sectionend',
					'id'   => self::OPTION_NAME . '_options',
				),
			);

			return $settings_size_guide;
		}

		/**
		 * Register size guide post type
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function register_post_type() {
			if ( post_type_exists( self::POST_TYPE ) ) {
				return;
			}
			register_post_type( self::POST_TYPE, array(
				'description'         => esc_html__( 'Product size guide', 'minimog' ),
				'labels'              => array(
					'name'                  => esc_html__( 'Size Guide', 'minimog' ),
					'singular_name'         => esc_html__( 'Size Guide', 'minimog' ),
					'menu_name'             => esc_html__( 'Size Guides', 'minimog' ),
					'all_items'             => esc_html__( 'Size Guides', 'minimog' ),
					'add_new'               => esc_html__( 'Add New', 'minimog' ),
					'add_new_item'          => esc_html__( 'Add New Size Guide', 'minimog' ),
					'edit_item'             => esc_html__( 'Edit Size Guide', 'minimog' ),
					'new_item'              => esc_html__( 'New Size Guide', 'minimog' ),
					'view_item'             => esc_html__( 'View Size Guide', 'minimog' ),
					'search_items'          => esc_html__( 'Search size guides', 'minimog' ),
					'not_found'             => esc_html__( 'No size guide found', 'minimog' ),
					'not_found_in_trash'    => esc_html__( 'No size guide found in Trash', 'minimog' ),
					'filter_items_list'     => esc_html__( 'Filter size guides list', 'minimog' ),
					'items_list_navigation' => esc_html__( 'Size guides list navigation', 'minimog' ),
					'items_list'            => esc_html__( 'Size guides list', 'minimog' ),
				),
				'supports'            => array( 'title', 'editor' ),
				'rewrite'             => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_rest'        => false,
				'show_in_menu'        => 'edit.php?post_type=product',
				'menu_position'       => 20,
				'capability_type'     => 'page',
				'map_meta_cap'        => true,
				'exclude_from_search' => true,
				'hierarchical'        => false,
				'has_archive'         => false,
				'show_in_nav_menus'   => true,
				'taxonomies'          => array( 'product_cat' ),
			) );
		}

		/**
		 * Add custom column to size guides management screen
		 * Add Thumbnail column
		 *
		 * @since 1.0.0
		 *
		 * @param  array $columns Default columns
		 *
		 * @return array
		 */
		public function edit_admin_columns( $columns ) {
			$columns = array_merge( $columns, array(
				'apply_to' => esc_html__( 'Apply to Category', 'minimog' ),
			) );

			return $columns;
		}

		/**
		 * Handle custom column display
		 *
		 * @since 1.0.0
		 *
		 * @param  string $column
		 * @param  int    $post_id
		 */
		public function manage_custom_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'apply_to':
					$cats     = get_post_meta( $post_id, 'size_guide_category', true );
					$selected = is_array( $cats ) ? 'custom' : $cats;
					$selected = $selected ? $selected : 'none';

					switch ( $selected ) {
						case 'none':
							esc_html_e( 'No Category', 'minimog' );
							break;

						case 'all':
							esc_html_e( 'All Categories', 'minimog' );
							break;

						case 'custom':
							$links = array();

							if ( is_array( $cats ) ) {
								foreach ( $cats as $cat_id ) {
									$cat = get_term( $cat_id, 'product_cat' );

									if ( ! is_wp_error( $cat ) && $cat ) {
										$links[] = sprintf( '<a href="%s">%s</a>', esc_url( get_edit_term_link( $cat_id, 'product_cat', 'product' ) ), $cat->name );
									}
								}
							} else {
								$links[] = esc_html__( 'No Category', 'minimog' );
							}

							echo implode( ', ', $links );
							break;
					}
					break;
			}
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
		 * Add meta boxes
		 *
		 * @param object $post
		 */
		public function meta_boxes( $post ) {
			add_meta_box( 'minimog-size-guide-category', esc_html__( 'Apply to Categories', 'minimog' ), array(
				$this,
				'category_meta_box',
			), self::POST_TYPE, 'side' );
			add_meta_box( 'minimog-size-guide-tables', esc_html__( 'Table', 'minimog' ), array(
				$this,
				'tables_meta_box',
			), self::POST_TYPE, 'advanced', 'high' );
		}

		/**
		 * Category meta box.
		 *
		 * @since 1.0.0
		 *
		 * @param object $post
		 *
		 * @return void
		 */
		public function category_meta_box( $post ) {
			$cats     = get_post_meta( $post->ID, 'size_guide_category', true );
			$selected = is_array( $cats ) ? 'custom' : $cats;
			$selected = $selected ? $selected : 'none';
			?>
			<p>
				<label>
					<input type="radio" name="_size_guide_category" value="none" <?php checked( 'none', $selected ) ?>>
					<?php esc_html_e( 'No category', 'minimog' ); ?>
				</label>
			</p>

			<p>
				<label>
					<input type="radio" name="_size_guide_category" value="all" <?php checked( 'all', $selected ) ?>>
					<?php esc_html_e( 'All Categories', 'minimog' ); ?>
				</label>
			</p>

			<p>
				<label>
					<input type="radio" name="_size_guide_category"
					       value="custom" <?php checked( 'custom', $selected ) ?>>
					<?php esc_html_e( 'Select Categories', 'minimog' ); ?>
				</label>
			</p>

			<div class="taxonomydiv" style="display: none;">
				<div class="tabs-panel">
					<ul class="categorychecklist">
						<?php
						wp_terms_checklist( $post->ID, array(
							'taxonomy' => 'product_cat',
						) );
						?>
					</ul>
				</div>
			</div>

			<?php
		}

		/**
		 * Table meta box.
		 * Content will be filled by js.
		 *
		 * @since 1.0.0
		 *
		 * @param object $post
		 */
		public function tables_meta_box( $post ) {
			$tables = get_post_meta( $post->ID, 'size_guides', true );
			$tables = $tables ? $tables : array(
				'names'        => array( '' ),
				'tables'       => array( '[["",""],["",""]]' ),
				'descriptions' => array( '' ),
				'information'  => array( '' ),
			);
			wp_localize_script( 'minimog-size-guide', 'minimogSizeGuideTables', $tables );
			?>

			<div id="minimog-size-guide-tabs" class="minimog-size-guide-tabs"></div>
			<?php
		}

		/**
		 * Save meta box content.
		 *
		 * @since 1.0.0
		 *
		 * @param int    $post_id
		 * @param object $post
		 *
		 * @return void
		 */
		public function save_post( $post_id, $post ) {
			// If not the flex post.
			if ( self::POST_TYPE != $post->post_type ) {
				return;
			}

			// Check if user has permissions to save data.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Check if not an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return;
			}

			if ( ! empty( $_POST['_size_guide_category'] ) ) {
				if ( 'custom' == $_POST['_size_guide_category'] && ! empty( $_POST['tax_input'] ) && ! empty( $_POST['tax_input']['product_cat'] ) ) {
					$cat_ids = array_map( 'intval', $_POST['tax_input']['product_cat'] );
					update_post_meta( $post_id, 'size_guide_category', $cat_ids );

					wp_set_post_terms( $post_id, $cat_ids, 'product_cat' );
				} else {
					update_post_meta( $post_id, 'size_guide_category', $_POST['_size_guide_category'] );
				}
			}

			if ( ! empty( $_POST['_size_guides'] ) ) {
				update_post_meta( $post_id, 'size_guides', $_POST['_size_guides'] );
			}
		}

		/**
		 * Load scripts and style in admin area
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function admin_scripts( $hook ) {
			$screen = get_current_screen();

			if ( in_array( $hook, array( 'post-new.php', 'post.php' ) ) && self::POST_TYPE == $screen->post_type ) {
				wp_enqueue_style( 'minimog-size-guide', MINIMOG_THEME_URI . '/assets/admin/css/size-guide.min.css' );
				wp_enqueue_script( 'minimog-size-guide', MINIMOG_THEME_URI . '/assets/admin/js/size-guide/size-guide.js', array( 'jquery' ), '1.0', true );
			}

			if ( in_array( $hook, array( 'post-new.php', 'post.php' ) ) && 'product' == $screen->post_type ) {
				wp_enqueue_script( 'minimog-product-size-guide', MINIMOG_THEME_URI . '/assets/admin/js/size-guide/product-size-guide.js', array( 'jquery' ), '1.0', true );
			}

			if ( 'woocommerce_page_wc-settings' == $screen->base && ! empty( $_GET['section'] ) && 'minimog_size_guide' == $_GET['section'] ) {
				wp_enqueue_script( 'minimog-size-guide-settings', MINIMOG_THEME_URI . '/assets/admin/js/size-guide/size-guide-settings.js', array( 'jquery' ), '1.0', true );
			}
		}

		/**
		 * Tab templates
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function templates() {
			?>
			<script type="text/html" id="tmpl-minimog-size-guide-panel">
				<div class="minimog-size-guide-table-editor" data-tab="{{data.index}}">
					<p>
						<label>
							<?php esc_html_e( 'Name', 'minimog' ); ?><br/>
							<input type="text" name="_size_guides[names][]" class="widefat" value="{{data.name}}">
						</label>
					</p>

					<p>
						<label>
							<?php esc_html_e( 'Description', 'minimog' ) ?>
							<textarea name="_size_guides[descriptions][]" class="widefat"
							          rows="6">{{data.description}}</textarea>
						</label>
					</p>

					<p><label><?php esc_html_e( 'Table', 'minimog' ) ?></label></p>

					<textarea name="_size_guides[tables][]" class="widefat minimog-size-guide-table hidden">{{{data.table}}}</textarea>

					<p>
						<label>
							<?php esc_html_e( 'Additional Information', 'minimog' ) ?>
							<textarea name="_size_guides[information][]" class="widefat"
							          rows="6">{{{data.information}}}</textarea>
						</label>
					</p>
				</div>
			</script>

			<?php
		}

		/**
		 * Add new product data tab for size guide
		 *
		 * @param array $tabs
		 *
		 * @return array
		 */
		public function product_data_tab( $tabs ) {
			$tabs['minimog_size_guide'] = array(
				'label'    => __( 'Size Guide', 'minimog' ),
				'target'   => 'minimog-size-guide',
				'class'    => array( 'minimog-size-guide', ),
				'priority' => 62,
			);

			return $tabs;
		}


		/**
		 * Outputs the size guide panel
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function product_data_panel() {
			global $post, $thepostid, $product_object;

			$thepostid        = empty( $thepostid ) ? $post->ID : $thepostid;
			$default_display  = get_option( self::OPTION_NAME . '_display', 'tab' );
			$default_position = get_option( self::OPTION_NAME . '_button_position', 'bellow_summary' );

			$display_options = array(
				'tab'   => __( 'In product tabs', 'minimog' ),
				'popup' => __( 'Popup by clicking on a button', 'minimog' ),
			);

			$button_options = array(
				'bellow_summary'   => __( 'Below short description', 'minimog' ),
				'bellow_price'     => __( 'Below price', 'minimog' ),
				'above_button'     => __( 'Above Add To Cart button', 'minimog' ),
				'beside_attribute' => __( 'Beside an attribute', 'minimog' ),
			);

			$product_size_guide = get_post_meta( $thepostid, 'minimog_size_guide', true );
			$product_size_guide = wp_parse_args( $product_size_guide, array(
				'guide'           => '',
				'display'         => '',
				'button_position' => '',
			) );

			$guides = get_posts( array(
				'post_type'      => self::POST_TYPE,
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			) );

			$guide_options = array(
				''     => __( 'Default', 'minimog' ),
				'none' => __( 'No Size Guide', 'minimog' ),
			);

			foreach ( $guides as $guide ) {
				$guide_options[ $guide ] = get_post_field( 'post_title', $guide );
			}
			?>

			<div id="minimog-size-guide" class="panel woocommerce_options_panel hidden"
			     data-nonce="<?php echo esc_attr( wp_create_nonce( 'minimog_size_guide' ) ); ?>">
				<div class="options_group">
					<?php
					woocommerce_wp_select( array(
						'id'      => 'minimog_size_guide-guide',
						'name'    => 'minimog_size_guide[guide]',
						'value'   => $product_size_guide['guide'],
						'label'   => __( 'Size Guide', 'minimog' ),
						'options' => $guide_options,
					) );
					?>
				</div>

				<div class="options_group">
					<?php
					woocommerce_wp_select( array(
						'id'      => 'minimog_size_guide-display',
						'name'    => 'minimog_size_guide[display]',
						'value'   => $product_size_guide['display'],
						'label'   => __( 'Size Guide Display', 'minimog' ),
						'options' => array_merge( array( '' => __( 'Default', 'minimog' ) . ' (' . $display_options[ $default_display ] . ')' ), $display_options ),
					) );

					woocommerce_wp_select( array(
						'id'      => 'minimog_size_guide-button_position',
						'name'    => 'minimog_size_guide[button_position]',
						'value'   => $product_size_guide['button_position'],
						'label'   => __( 'Button Position', 'minimog' ),
						'options' => array_merge( array( '' => __( 'Default', 'minimog' ) . ' (' . $button_options[ $default_position ] . ')' ), $button_options ),
					) );
					?>
				</div>
			</div>

			<?php
		}

		/**
		 * Save product data of selected size guide
		 *
		 * @param int $post_id
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function process_product_meta( $post_id ) {
			if ( isset( $_POST['minimog_size_guide'] ) ) {
				update_post_meta( $post_id, 'minimog_size_guide', $_POST['minimog_size_guide'] );
			}
		}
	}

	Minimog_Size_Guide_Settings::instance()->initialize();
}
