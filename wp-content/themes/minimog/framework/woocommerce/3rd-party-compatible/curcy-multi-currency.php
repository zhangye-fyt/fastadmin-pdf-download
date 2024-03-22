<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

/**
 * Compatible with CURCY - WooCommerce Multi Currency plugin.
 *
 * @see https://wordpress.org/plugins/woo-multi-currency/
 */
class Curcy_Multi_Currency {

	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'minimog/top_bar/components/currency_switcher/output', [ $this, 'get_currency_switcher_html' ] );
		add_filter( 'minimog/header/components/currency_switcher/output', [ $this, 'get_currency_switcher_html' ] );
	}

	/**
	 * Check whether the plugin activated
	 *
	 * @return boolean true if plugin activated
	 */
	public function is_activated() {
		return class_exists( 'WOOMULTI_CURRENCY' );
	}

	public function get_currency_switcher_html() {
		$settings = \WOOMULTI_CURRENCY_Data::get_ins();

		//$currencies       = $settings->get_list_currencies();
		$current_currency = $settings->get_current_currency();
		$links            = $settings->get_links();
		$currency_name    = get_woocommerce_currencies();
		ob_start();
		?>
		<div class="currency-switcher-menu-wrap curcy">
			<ul class="menu currency-switcher-menu woo-multi-currency-menu">
				<li class="menu-item-has-children">
					<a href="#">
						<span class="current-currency-text"><?php echo esc_html( $current_currency ); ?></span>
					</a>
					<ul class="sub-menu">
						<?php foreach ( $links as $code => $link ): ?>
							<?php
							if ( $code === $current_currency ) {
								continue;
							}

							if ( empty( $currency_name[ $code ] ) ) {
								continue;
							}

							$value = esc_url( $link );
							//$name    = $currency_name[ $code ];
							$current = '';
							?>
							<li>
								<a href="<?php echo esc_url( $value ) ?>"
								   class="<?php echo esc_attr( $current ); ?> currency-switcher-link">
									<span class="currency-text"><?php echo esc_html( $code ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</li>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}
}

Curcy_Multi_Currency::instance()->initialize();
