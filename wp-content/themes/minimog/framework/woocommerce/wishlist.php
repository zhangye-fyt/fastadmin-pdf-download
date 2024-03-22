<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Wishlist {
	protected static $instance = null;

	protected static $added_products    = array();
	protected static $added_product_ids = array();
	protected static $localization      = array();

	const MINIMUM_PLUGIN_VERSION   = '4.2.1';
	const RECOMMEND_PLUGIN_VERSION = '4.6.0';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activate() ) {
			return;
		}

		if ( defined( 'WOOSW_VERSION' ) ) {
			if ( version_compare( WOOSW_VERSION, self::MINIMUM_PLUGIN_VERSION, '<' ) ) {
				return;
			}

			if ( version_compare( WOOSW_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_recommend_plugin_version' ] );
			}
		}

		add_filter( 'woosw_fragments', [ $this, 'add_wishlist_fragments' ] );

		add_action( 'init', [ $this, 'init' ] );

		add_filter( 'woosw_button_position_archive', '__return_zero_string' );
		add_filter( 'woosw_button_position_single', '__return_zero_string' );
	}

	public function is_activate() {
		return class_exists( 'WPCleverWoosw' );
	}

	public function admin_notice_recommend_plugin_version() {
		minimog_notice_required_plugin_version( 'WPC Smart Wishlist for WooCommerce', self::RECOMMEND_PLUGIN_VERSION, true );
	}

	public function init() {
		// Localization.
		self::$localization = (array) get_option( 'woosw_localization' );

		// Added products.
		$key = isset( $_COOKIE['woosw_key'] ) ? $_COOKIE['woosw_key'] : '#';

		if ( get_option( 'woosw_list_' . $key ) ) {
			self::$added_products = get_option( 'woosw_list_' . $key );

			self::$added_product_ids = array_keys( self::$added_products );
		}
	}

	public function get_localization( $key = '', $default = '' ) {
		$str = '';

		if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
			$str = self::$localization[ $key ];
		} elseif ( ! empty( $default ) ) {
			$str = $default;
		}

		return apply_filters( 'woosw_localization_' . $key, $str );
	}

	public static function output_button( $args = array() ) {
		if ( '1' !== \Minimog::setting( 'shop_archive_wishlist' ) || ! self::instance()->is_activate() ) {
			return;
		}

		global $product;

		$product_id = $product->get_id();

		$defaults  = array(
			'show_tooltip'     => false,
			'tooltip_position' => 'top',
			'tooltip_skin'     => '',
			'style'            => '02',
		);
		$args      = wp_parse_args( $args, $defaults );
		$icon_type = \Minimog::setting( 'wishlist_icon_type' );

		$_wrapper_classes = "product-action wishlist-btn style-{$args['style']} icon-{$icon_type}";

		if ( $args['show_tooltip'] === true ) {
			$_wrapper_classes .= ' hint--bounce';
			$_wrapper_classes .= " hint--{$args['tooltip_position']}";

			if ( ! empty( $args['tooltip_skin'] ) ) {
				$_wrapper_classes .= " hint--{$args['tooltip_skin']}";
			}
		}

		if ( in_array( $product_id, self::$added_product_ids, true ) ) {
			$text = self::instance()->get_localization( 'button_added', __( 'Browse wishlist', 'minimog' ) );
		} else {
			$text = self::instance()->get_localization( 'button', __( 'Add to wishlist', 'minimog' ) );
		}
		?>
		<div class="<?php echo esc_attr( $_wrapper_classes ); ?>"
		     data-hint="<?php echo esc_attr( $text ); ?>">
			<?php echo do_shortcode( '[woosw id="' . $product_id . '" type="link"]' ); ?>
		</div>
		<?php
	}

	public function can_edit( $key ) {
		if ( is_user_logged_in() ) {
			if ( get_user_meta( get_current_user_id(), 'woosw_key', true ) === $key ) {
				return true;
			}
		} else {
			if ( isset( $_COOKIE['woosw_key'] ) && ( $_COOKIE['woosw_key'] === $key ) ) {
				return true;
			}
		}

		return false;
	}

	public function add_wishlist_fragments( $fragments ) {
		$count = \WPCleverWoosw::get_count();

		$fragments['.wishlist-link .icon-badge']        = '<span class="icon-badge" data-count="' . $count . '">' . $count . '</span>';
		$fragments['.mobile-menu-wishlist-link .count'] = '<span class="count">' . $count . '</span>';

		return $fragments;
	}
}

Wishlist::instance()->initialize();
