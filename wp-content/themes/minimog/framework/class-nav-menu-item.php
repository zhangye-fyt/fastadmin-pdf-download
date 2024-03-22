<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Nav_Menu_Item' ) ) {
	class Minimog_Nav_Menu_Item {

		protected static $instance = null;
		const MENU_ITEM_ICON_CLASS = '_menu_item_icon_class';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			add_action( 'wp_nav_menu_item_custom_fields', [ $this, 'add_fields_to_nav_menu' ], 10, 5 );

			add_action( 'wp_update_nav_menu_item', [ $this, 'update_nav_menu_item' ], 10, 3 );

			add_filter( 'wp_setup_nav_menu_item', [ $this, 'setup_nav_menu_item' ] );
		}

		/**
		 * Add input field to add icon class.
		 *
		 * @param int      $item_id the menu item ID
		 * @param WP_Post  $item    Menu item data object.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 * @param stdClass $args    An object of menu item arguments.
		 * @param int      $id      the Navigation Menu ID
		 */
		public function add_fields_to_nav_menu( $item_id, $item, $depth, $args, $id ) {
			?>
			<p class="field-icon-class description description-thin">
				<label for="edit-menu-item-icon-class-<?php echo esc_attr( $item_id ); ?>">
					<?php esc_html_e( 'Icon Class (optional)', 'minimog' ); ?><br/>
					<input type="text" id="edit-menu-item-icon-class-<?php echo esc_attr( $item_id ); ?>"
					       class="widefat code edit-menu-item-icon-class"
					       name="menu-item-icon-class[<?php echo esc_attr( $item_id ); ?>]"
					       value="<?php echo esc_attr( $item->icon_class ); ?>"/>
				</label>
			</p>
			<?php
		}

		public function update_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
			if ( isset( $_REQUEST['menu-item-icon-class'] ) && is_array( $_REQUEST['menu-item-icon-class'] ) ) {
				if ( isset( $_REQUEST['menu-item-icon-class'][ $menu_item_db_id ] ) ) {
					$new_value = $_REQUEST['menu-item-icon-class'][ $menu_item_db_id ];
					update_post_meta( $menu_item_db_id, self::MENU_ITEM_ICON_CLASS, $new_value );
				}
			} else {
				update_post_meta( $menu_item_db_id, self::MENU_ITEM_ICON_CLASS, '' );
			}
		}

		public function setup_nav_menu_item( $menu_item ) {
			$icon_class = get_post_meta( $menu_item->ID, self::MENU_ITEM_ICON_CLASS, true );
			if ( empty( $icon_class ) ) {
				$icon_class = '';
			}

			$menu_item->icon_class = $icon_class;

			return $menu_item;
		}
	}

	Minimog_Nav_Menu_Item::instance()->initialize();
}
