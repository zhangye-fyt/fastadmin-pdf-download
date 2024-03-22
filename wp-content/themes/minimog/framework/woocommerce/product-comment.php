<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Product_Comment {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * Priority 1 to run before comment attachment
		 *
		 * @see \DCO_CA::init_hooks()
		 * @see \DCO_CA::display_attachment()
		 */
		add_filter( 'comment_text', [ $this, 'add_wrapper_for_collapsible' ], 1 );

		// Change review avatar size.
		add_filter( 'woocommerce_review_gravatar_size', [ $this, 'woocommerce_review_gravatar_size' ] );
	}

	public function add_wrapper_for_collapsible( $comment_content ) {
		if ( ! is_product() ) {
			return $comment_content;
		}

		return '<div class="js-text-collapsible comment-text-collapsible">' . $comment_content . '</div>';
	}

	public function woocommerce_review_gravatar_size() {
		return \Minimog::COMMENT_AVATAR_SIZE;
	}
}

Product_Comment::instance()->initialize();
