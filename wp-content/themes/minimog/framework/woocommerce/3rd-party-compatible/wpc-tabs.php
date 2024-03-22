<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Custom_Tabs {
	protected static $instance = null;

	const MINIMUM_PLUGIN_VERSION   = '1.5.0';
	const RECOMMEND_PLUGIN_VERSION = '2.0.3';

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

		if ( version_compare( WOOST_VERSION, self::MINIMUM_PLUGIN_VERSION, '<' ) ) {
			return;
		}

		if ( version_compare( WOOST_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_recommend_plugin_version' ] );
		}
	}

	public function is_activate() {
		return defined( 'WOOST_VERSION' );
	}

	public function admin_notice_recommend_plugin_version() {
		minimog_notice_required_plugin_version( 'WPC Product Tabs for WooCommerce', self::RECOMMEND_PLUGIN_VERSION, true );
	}
}

Custom_Tabs::instance()->initialize();
