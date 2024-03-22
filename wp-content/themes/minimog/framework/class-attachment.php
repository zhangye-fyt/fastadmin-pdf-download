<?php
defined( 'ABSPATH' ) || exit;

class Minimog_Attachment {
	protected static $instance = null;

	const CROPPED_KEY = '_wp_attachment_cropped';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'attachment_updated', [ $this, 'attachment_updated' ], 10, 3 );
	}

	public function attachment_updated( $post_ID, $post_after, $post_before ) {
		/**
		 * Update meta alt for attachment cropped data
		 */
		$attachment_alt = get_post_meta( $post_ID, '_wp_attachment_image_alt', true );

		$attachment_cropped_data = get_post_meta( $post_ID, self::CROPPED_KEY, true );

		if ( isset( $attachment_cropped_data['alt'] ) ) {
			$alt = ! empty( $attachment_alt ) ? $attachment_alt : $post_after->post_title;

			$attachment_cropped_data['alt'] = $alt;

			$this->update_cropped_info( $post_ID, $attachment_cropped_data );
		}
	}

	public function update_cropped_info( $attachment_id, $data ) {
		$attachment_id = (int) $attachment_id;

		$post = get_post( $attachment_id );

		if ( ! $post ) {
			return false;
		}

		if ( $data ) {
			return update_post_meta( $post->ID, self::CROPPED_KEY, $data );
		} else {
			return delete_post_meta( $post->ID, self::CROPPED_KEY );
		}
	}

	public static function get_cropped_info( $attachment_id = 0 ) {
		$attachment_id = (int) $attachment_id;

		if ( ! $attachment_id ) {
			$post = get_post();

			if ( ! $post ) {
				return false;
			}

			$attachment_id = $post->ID;
		}

		$data = get_post_meta( $attachment_id, self::CROPPED_KEY, true );

		if ( ! $data ) {
			return false;
		}

		return $data;
	}

	public function delete_all_cropped_info() {
		global $wpdb;

		$wpdb->query( $wpdb->prepare(
			"DELETE FROM $wpdb->postmeta WHERE `meta_key` LIKE %s",
			'%' . $wpdb->esc_like( self::CROPPED_KEY ) . '%'
		) );
	}
}

Minimog_Attachment::instance()->initialize();
