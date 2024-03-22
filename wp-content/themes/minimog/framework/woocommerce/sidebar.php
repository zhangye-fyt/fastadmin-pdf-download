<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Sidebar {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'minimog/register_sidebars', [ $this, 'register_sidebars' ] );
	}

	public function register_sidebars( $defaults ) {
		register_sidebar( array_merge( $defaults, array(
			'id'          => 'shop_sidebar',
			'name'        => esc_html__( 'Shop Sidebar', 'minimog' ),
			'description' => esc_html__( 'Add widgets here.', 'minimog' ),
		) ) );

		register_sidebar( array_merge( $defaults, array(
			'id'          => 'single_shop_sidebar',
			'name'        => esc_html__( 'Single Product Sidebar', 'minimog' ),
			'description' => esc_html__( 'Add widgets here.', 'minimog' ),
		) ) );

		/**
		 * Alt sidebar for product page.
		 */
		register_sidebar( array_merge( $defaults, array(
			'id'          => 'single_shop_sidebar2',
			'name'        => esc_html__( 'Single Product Sidebar 2', 'minimog' ),
			'description' => esc_html__( 'Add widgets here.', 'minimog' ),
		) ) );
	}
}

Sidebar::instance()->initialize();
