<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Auction {
	protected static $instance = null;

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

		add_action( 'init', [ $this, 'wp_init' ] );
		add_action( 'init', [ $this, 'hooks' ], 99 );

		add_filter( 'minimog/product_badge/html', [ $this, 'winning_badge' ] );
	}

	public function is_activate() {
		return class_exists( 'WooCommerce_simple_auction' );
	}

	public function wp_init() {
		add_action( 'woocommerce_single_product_summary', [ $this, 'auction_hooks' ], 27 );
	}

	/**
	 * Remove all hooks then re-add
	 */
	public function hooks() {
		if ( function_exists( 'woocommerce_auction_ajax_conteiner_start' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_ajax_conteiner_start', 21 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_ajax_conteiner_start' );
		}

		if ( function_exists( 'woocommerce_auction_condition' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_condition', 23 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_condition' );
		}

		if ( function_exists( 'woocommerce_auction_countdown' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_countdown', 24 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_countdown' );
		}

		if ( function_exists( 'woocommerce_auction_dates' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_dates', 24 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_dates' );
		}

		if ( function_exists( 'woocommerce_auction_reserve' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_reserve', 25 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_reserve' );
		}

		if ( function_exists( 'woocommerce_auction_sealed' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_sealed', 25 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_sealed' );
		}

		if ( function_exists( 'woocommerce_auction_max_bid' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_max_bid', 25 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_max_bid' );
		}

		if ( function_exists( 'woocommerce_auction_bid_form' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_bid_form', 25 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_bid_form' );
		}

		if ( function_exists( 'woocommerce_auction_ajax_conteiner_end' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_auction_ajax_conteiner_end', 27 );
			add_action( 'minimog_single_product_auction', 'woocommerce_auction_ajax_conteiner_end' );
		}

		minimog_remove_filters_for_anonymous_class( 'woocommerce_before_shop_loop_item_title', 'WooCommerce_simple_auction', 'add_winning_bage', 60 );
	}

	public function auction_hooks() {
		do_action( 'minimog_single_product_auction' );
	}

	public function winning_badge( $html ) {
		global $product;

		if ( is_user_logged_in() && 'auction' === $product->get_type() ) {
			ob_start();
			wc_get_template( 'loop/winning-bage.php' );
			$html .= ob_get_clean();
		}

		return $html;
	}
}

Auction::instance()->initialize();
