<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Comments extends \WC_Comments {
	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'preprocess_comment', [ $this, 'check_comment_title' ], 0 );

		add_action( 'comment_post', [ $this, 'add_comment_title' ] );

		add_action( 'woocommerce_review_before_comment_text', [ $this, 'add_comment_title_html' ] );

		add_action( 'add_meta_boxes', [ $this, 'add_comment_title_meta_box' ], 30 );
		// Save Rating Meta Boxes.
		add_filter( 'wp_update_comment_data', [ $this, 'save_comment_title_meta_box' ], 1 );
	}

	public function add_comment_title_meta_box() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		// Comment rating.
		if ( 'comment' === $screen_id && isset( $_GET['c'] ) && metadata_exists( 'comment', wc_clean( wp_unslash( $_GET['c'] ) ), 'rating' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			add_meta_box( 'product-review-title', __( 'Title', 'minimog' ), [
				$this,
				'meta_box_review_title_output',
			], 'comment', 'normal', 'high' );
		}
	}

	/**
	 * Save meta box data
	 *
	 *
	 * @param mixed $data Data to save.
	 *
	 * @return mixed
	 */
	public static function save_comment_title_meta_box( $data ) {
		// Not allowed, return regular value without updating meta.
		if ( ! isset( $_POST['minimog_meta_nonce'], $_POST['comment_title'] ) || ! wp_verify_nonce( wp_unslash( $_POST['minimog_meta_nonce'] ), 'minimog_save_data' ) ) { // WPCS: input var ok, sanitization ok.
			return $data;
		}

		if ( '' === $_POST['comment_title'] ) { // WPCS: input var ok.
			return $data;
		}

		$comment_id = $data['comment_ID'];

		update_comment_meta( $comment_id, 'comment_title', wp_unslash( $_POST['comment_title'] ) ); // WPCS: input var ok.

		// Return regular value after updating.
		return $data;
	}

	public static function meta_box_review_title_output( $comment ) {
		wp_nonce_field( 'minimog_save_data', 'minimog_meta_nonce' );

		$current = get_comment_meta( $comment->comment_ID, 'comment_title', true );
		?>
		<input type="text" id="minimog_comment_title" value="<?php echo esc_attr( $current ); ?>"
		       name="comment_title" style="width: 100%" tabindex="1"/>
		<?php
	}

	public function check_comment_title( $comment_data ) {
		// If posting a comment (not trackback etc) and not logged in.
		if ( ! is_admin() && isset( $_POST['comment_post_ID'], $_POST['title'], $comment_data['comment_type'] ) && 'product' === get_post_type( absint( $_POST['comment_post_ID'] ) ) && empty( $_POST['comment_title'] ) ) { // WPCS: input var ok, CSRF ok.
			wp_die( esc_html__( 'Please add review title.', 'minimog' ) );
			exit;
		}

		return $comment_data;
	}

	/**
	 * Title field for comments.
	 *
	 * @param int $comment_id Comment ID.
	 */
	public function add_comment_title( $comment_id ) {
		if ( ! empty( $_POST['comment_title'] ) && isset( $_POST['comment_post_ID'] ) && 'product' === get_post_type( absint( $_POST['comment_post_ID'] ) ) ) { // WPCS: input var ok, CSRF ok.
			add_comment_meta( $comment_id, 'comment_title', wp_kses_post( $_POST['comment_title'] ), true ); // WPCS: input var ok, CSRF ok.
		}
	}

	/**
	 * @param \WP_Comment $comment
	 */
	public function add_comment_title_html( $comment ) {
		$comment_title = get_comment_meta( $comment->comment_ID, 'comment_title', true );

		if ( empty( $comment_title ) ) {
			return;
		}
		?>
		<h6 class="woocommerce-review__title"><?php echo esc_html( $comment_title ); ?></h6>
		<?php
	}
}

Comments::instance()->initialize();
