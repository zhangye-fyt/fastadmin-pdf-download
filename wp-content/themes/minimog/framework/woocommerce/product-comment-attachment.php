<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

if ( class_exists( '\DCO_CA_Base' ) ) {
	class Product_Comment_Attachment extends \DCO_CA_Base {
		protected static $instance = null;

		const RECOMMEND_PLUGIN_VERSION = '2.2.0';

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
			if ( defined( 'DCO_CA_VERSION' ) && version_compare( DCO_CA_VERSION, self::RECOMMEND_PLUGIN_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_plugin_version' ] );
			}

			add_action( 'init', [ $this, 'init_hooks' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ], 11 );

			// Action on attachment field.
			add_filter( 'comment_form_submit_button', [ $this, 'add_attachment_button' ] );
			add_filter( 'dco_ca_form_element_label', [ $this, 'remove_text_comment_form_attachment' ] );
			add_filter( 'dco_ca_form_element_upload_size', [ $this, 'remove_text_comment_form_attachment' ] );
			add_filter( 'dco_ca_form_element_file_types', [ $this, 'remove_text_comment_form_attachment' ] );
			add_filter( 'dco_ca_form_element_autoembed_links', [ $this, 'remove_text_comment_form_attachment' ] );
			add_filter( 'dco_ca_attachment_field', [ $this, 'remove_attachment_field' ] );

			add_filter( 'dco_ca_get_attachment_preview_image', [ $this, 'update_attachment_image_html' ], 10, 3 );
		}

		public function is_activate() {
			return defined( 'DCO_CA_VERSION' );
		}

		public function admin_notice_minimum_plugin_version() {
			minimog_notice_required_plugin_version( 'DCO Comment Attachment', self::RECOMMEND_PLUGIN_VERSION );
		}

		public function frontend_scripts() {
			wp_dequeue_style( 'dco-comment-attachment' );
		}

		public function init_hooks() {
			parent::init_hooks();

			minimog_remove_filters_for_anonymous_class( 'comment_text', 'DCO_CA', 'display_attachment' );

			if ( $this->is_attachment_displayed() ) {
				add_filter( 'comment_text', [ $this, 'display_attachment' ] );
			}
		}

		/**
		 * @see   \DCO_CA::display_attachment Override Function
		 * Fix missing wrapper tag .dco-attachment-gallery when comment has only attachment.
		 *
		 * Displays an assigned attachments.
		 *
		 * @since 1.0.0
		 *
		 * @param string $comment_content Optional. Text of the comment.
		 *
		 * @return string Text of the comment with an assigned attachment.
		 */
		public function display_attachment( $comment_content = '' ) {
			if ( ! $this->has_attachment() ) {
				return $comment_content;
			}

			$attachment_id = (array) $this->get_attachment_id();
			if ( count( $attachment_id ) > 1 ) {
				$this->enable_gallery_image_size();
				$attachments_content = array();
				foreach ( $attachment_id as $attach_id ) {
					$type = $this->get_embed_type( $attach_id );
					$key  = "{$type}_{$attach_id}";

					$attachments_content[ $key ] = $this->get_attachment_preview( $attach_id );
				}

				if ( $this->get_option( 'combine_images' ) || is_admin() ) {
					// combine only images.
					$not_images = array();
					foreach ( $attachments_content as $key => $content ) {
						if ( strpos( $key, 'image' ) === false ) {
							$not_images[ $key ] = $content;
						}
					}
					$attachments_content = array_diff( $attachments_content, $not_images );
					$attachments_content = array_merge( $attachments_content, $not_images );
				}

				$attachment_content = implode( '', $attachments_content );
				$this->disable_gallery_image_size();
			} else {
				$attachment_content = $this->get_attachment_preview( current( $attachment_id ) );
			}

			$attachment_content = '<div class="dco-attachment-gallery">' . $attachment_content . '</div>';

			return $comment_content . $attachment_content;
		}

		/**
		 * Keep button only on comment form attachment.
		 *
		 * @param $output
		 *
		 * @return string
		 */
		public function remove_text_comment_form_attachment( $output ) {
			$output = '';

			return $output;
		}

		/**
		 * Remove Attachment field on single post
		 *
		 * @param $output
		 *
		 * @return string
		 */
		public function remove_attachment_field( $output ) {
			if ( 'product' != get_post_type() ) {
				return '';
			}

			return $output;
		}

		/**
		 * Add a attachment button next to submit button
		 * This button will trigger real button.
		 *
		 * @param $submit_button
		 *
		 * @return string
		 */
		public function add_attachment_button( $submit_button ) {
			if ( ! is_product() ) {
				return $submit_button;
			}

			$button = '<button type="button" class="comment-form__attachment-button attachment-button">';
			$button .= '<div class="button-icon svg-icon"><i class="far fa-camera"></i></div>';
			$button .= '<div class="button-text"><span>' . esc_html__( 'Add Photos', 'minimog' ) . '</span></div>';
			$button .= '</button>';

			return $button . $submit_button;
		}

		/**
		 * @see    \DCO_CA_Base::get_attachment_preview()
		 *
		 * @param string $attachment_content
		 * @param int    $attachment_id
		 * @param string $thumbnail_size
		 *
		 * @return string $attachment_content New HTML
		 */
		public function update_attachment_image_html( $attachment_content, $attachment_id, $thumbnail_size ) {
			if ( is_admin() ) {
				return $attachment_content;
			}

			// Override for frontend only.
			$thumbnail_size = '9999x120';

			$img = \Minimog_Image::get_attachment_by_id( [
				'id'   => $attachment_id,
				'size' => $thumbnail_size,
			] );

			$has_link = $this->get_option( 'link_thumbnail' );

			if ( '1' === $has_link ) {
				$full_img_url = wp_get_attachment_image_url( $attachment_id, 'full' );
				$img          = '<a href="' . esc_url( $full_img_url ) . '">' . $img . '</a>';
			}

			$attachment_content = '<p class="dco-attachment dco-image-attachment">' . $img . '</p>';

			return $attachment_content;
		}

		/**
		 * @see   \DCO_CA::is_attachment_displayed() Clone Function
		 * Checks that attachment displayed or not.
		 *
		 * @since 1.2.0
		 *
		 * @return bool True if the attachment display is enabled or false otherwise.
		 */
		public function is_attachment_displayed() {
			/**
			 * Filters whether to disable the attachment display.
			 *
			 * Prevents the attachment from being displayed in the comments list.
			 *
			 * @since 1.2.0
			 *
			 * @param bool $bool Whether to disable the attachment display.
			 *                   Returning true to the filter will disable the attachment display.
			 *                   Default false.
			 */
			return ! apply_filters( 'dco_ca_disable_display_attachment', false );
		}

		/**
		 * @see   \DCO_CA::enable_gallery_image_size() Clone Function
		 * Sets the image size for the gallery.
		 *
		 * @since 2.0.0
		 */
		public function enable_gallery_image_size() {
			add_filter( 'dco_ca_admin_thumbnail_size', array( $this, 'get_gallery_image_size' ) );
			add_filter( 'dco_ca_get_option_thumbnail_size', array( $this, 'get_gallery_image_size' ) );
		}

		/**
		 * @see   \DCO_CA::disable_gallery_image_size() Clone Function
		 * Restores the image size for the single image.
		 *
		 * @since 2.0.0
		 */
		public function disable_gallery_image_size() {
			remove_filter( 'dco_ca_admin_thumbnail_size', array( $this, 'get_gallery_image_size' ) );
			remove_filter( 'dco_ca_get_option_thumbnail_size', array( $this, 'get_gallery_image_size' ) );
		}

		/**
		 * @see   \DCO_CA::get_gallery_image_size() Clone Function
		 * Sets the image size for the gallery (callback function).
		 *
		 * @since 2.0.0
		 *
		 * @param string $size The thumbnail size of the attachment image.
		 *
		 * @return string The overridden thumbnail size, if it's necessary.
		 */
		public function get_gallery_image_size( $size ) {
			if ( 'dco_ca_admin_thumbnail_size' === current_filter() ) {
				/**
				 * Filters the attachment image size in the gallery for the admin panel.
				 *
				 * @since 2.0.0
				 *
				 * @param string $size The thumbnail size of the attachment image.
				 */
				return apply_filters( 'dco_ca_admin_gallery_size', 'thumbnail' );
			}

			if ( 'dco_ca_get_option_thumbnail_size' === current_filter() && $this->get_option( 'combine_images' ) ) {
				return $this->get_option( 'gallery_size' );
			}

			return $size;
		}
	}

	Product_Comment_Attachment::instance()->initialize();
}
