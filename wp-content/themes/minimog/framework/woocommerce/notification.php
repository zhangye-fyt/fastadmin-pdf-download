<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Notification {
	protected static $instance = null;

	const RECOMMEND_PLUGIN_VERSION = '1.1.3';

	private $settings;

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

		// Check old version installed.
		if ( defined( 'WPCSN_VERSION' ) && version_compare( WPCSN_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_plugin_version' ] );
		}

		$this->settings = get_option( 'wpcsn_opts' );

		add_filter( 'WPCSN/virtual_orders/item_title', [ $this, 'update_title_for_virtual_orders' ], 10, 4 );
		add_filter( 'WPCSN/virtual_orders/item_content', [ $this, 'add_product_price_for_virtual_orders' ], 10, 3 );
		add_filter( 'WPCSN/virtual_orders/item_time', [ $this, 'update_time_for_virtual_orders' ], 10, 3 );
	}

	public function is_activate() {
		return defined( 'WPCSN_VERSION' );
	}

	public function admin_notice_minimum_plugin_version() {
		minimog_notice_required_plugin_version( 'Smart Notification for WooCommerce', self::RECOMMEND_PLUGIN_VERSION );
	}

	/**
	 * @param $html
	 * @param $name
	 * @param $address
	 * @param $data
	 *
	 * @return string
	 */
	public function update_title_for_virtual_orders( $html, $name, $address, $data ) {
		return sprintf( esc_html__( '%s has purchased', 'minimog' ), '<strong>' . esc_html( $name ) . '</strong>' );
	}

	/**
	 * @param string      $html
	 * @param \WC_Product $product
	 * @param array       $data
	 *
	 * @return mixed
	 */
	public function add_product_price_for_virtual_orders( $html, $product, $data ) {
		ob_start(); ?>
		<h3 class="notification-product-title post-title-1-row">
			<a href="<?php echo esc_url( $product->get_permalink() ); ?>"
				<?php if ( $this->settings['options']['link'] ): ?>
					target="_blank"
				<?php endif; ?>
			>
				<?php echo '' . $product->get_name(); ?>
			</a>
		</h3>
		<?php
		$title = ob_get_clean();

		return $title . $product->get_price_html();
	}

	/**
	 * @param string      $html
	 * @param \WC_Product $product
	 * @param array       $data
	 *
	 * @return mixed
	 */
	public function update_time_for_virtual_orders( $html, $product, $data ) {
		$time_ago = mt_rand( 60, absint( $data['within'] ) );

		if ( $time_ago < HOUR_IN_SECONDS ) {
			$time_str = sprintf( _n( '%d minute', '%d minutes', round( $time_ago / MINUTE_IN_SECONDS ), 'minimog' ), round( $time_ago / MINUTE_IN_SECONDS ) );
		} elseif ( $time_ago < DAY_IN_SECONDS ) {
			$time_str = sprintf( _n( '%d hour', '%d hours', round( $time_ago / HOUR_IN_SECONDS ), 'minimog' ), round( $time_ago / HOUR_IN_SECONDS ) );
		} else {
			$time_str = sprintf( _n( '%d day', '%d days', round( $time_ago / DAY_IN_SECONDS ), 'minimog' ), round( $time_ago / DAY_IN_SECONDS ) );
		}

		$time_str = sprintf( esc_html__( '%s ago', 'minimog' ), $time_str );

		$address = $data['address'][ mt_rand( 0, count( $data['address'] ) - 1 ) ];

		return $time_str . ' - ' . $address;
	}
}

Notification::instance()->initialize();
