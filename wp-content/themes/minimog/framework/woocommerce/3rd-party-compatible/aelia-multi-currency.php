<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with Aelia Currency Switcher for WooCommerce plugin.
 *
 * @see https://aelia.co/shop/currency-switcher-woocommerce/
 */
class Aelia_Multi_Currency {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

		add_filter( 'minimog/top_bar/components/currency_switcher/output', [ $this, 'get_currency_switcher_html' ] );
		add_filter( 'minimog/header/components/currency_switcher/output', [ $this, 'get_currency_switcher_html' ] );
	}

	/**
	 * Check whether the plugin activated
	 *
	 * @return boolean true if plugin activated
	 */
	public function is_activated() {
		return class_exists( '\Aelia\WC\CurrencySwitcher\WC_Aelia_CurrencySwitcher' );
	}

	public function frontend_scripts() {
		$min = \Minimog_Enqueue::instance()->get_min_suffix();

		wp_register_style( 'minimog-aelia-currency-switcher', MINIMOG_THEME_URI . "/assets/css/wc/aelia-currency-switcher{$min}.css", null, MINIMOG_THEME_VERSION );
		wp_enqueue_style( 'minimog-aelia-currency-switcher' );
	}

	public function get_currency_switcher_html() {
		ob_start();
		?>
		<div class="currency-switcher-menu-wrap aelia">
			<?php echo do_shortcode( '[aelia_currency_selector_widget widget_type="dropdown_flags" currency_display_mode="show_currency_code"]' ); ?>
		</div>
		<?php
		return ob_get_clean();
	}
}

Aelia_Multi_Currency::instance()->initialize();
