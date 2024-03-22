<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Avatar_Upload' ) ) {
	class Avatar_Upload {

		protected static $instance = null;

		static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

			add_action( 'after_switch_theme', [ $this, 'allows_customer_upload_avatar' ], 10, 2 );

			if ( class_exists( 'Metronet_Profile_Picture' ) ) {
				if ( current_user_can( 'upload_files' ) ) {
					add_action( 'minimog/myaccount/after_avatar', [ $this, 'upload_icon' ], 5 );
					add_action( 'minimog/myaccount/before_info', [ $this, 'user_profile' ], 5 );
				}

				add_action( 'wp_ajax_minimog_upload_avatar', [ $this, 'ajax_upload_avatar' ] );
				add_action( 'wp_ajax_minimog_remove_avatar', [ $this, 'ajax_remove_avatar' ] );
			}
		}

		/**
		 * @param string    $old_name
		 * @param \WP_Theme $old_theme
		 */
		public function allows_customer_upload_avatar( $old_name, $old_theme ) {
			$subscriber = get_role( 'subscriber' );

			if ( $subscriber ) {
				$subscriber->add_cap( 'upload_files', true );
			}
		}

		public function frontend_scripts() {
			wp_dequeue_script( 'mpp_gutenberg_tabs' );

			$min = \Minimog_Enqueue::instance()->get_min_suffix();

			wp_register_script( 'minimog-avatar-upload', MINIMOG_THEME_URI . "/assets/js/woo/avatar-upload{$min}.js", [
				'minimog-script',
			], '1.0.0', true );

			if ( is_account_page() && is_user_logged_in() ) {
				wp_enqueue_script( 'minimog-avatar-upload' );

				$js_variables = [
					'update_avatar_nonce' => wp_create_nonce( 'minimog-update-user-avatar' ),
				];

				wp_localize_script( 'minimog-avatar-upload', '$minimogUpload', $js_variables );
			}
		}

		/**
		 * Gets a post id for the user - Creates a post if a post doesn't exist
		 *
		 * @param int $user_id User ID of   the user.
		 *
		 * @return int post_id
		 */
		private function get_user_post_id( $user_id = 0 ) {
			$user = get_user_by( 'id', $user_id );

			// Get/Create Profile Picture Post.
			$post_args = array(
				'post_type'   => 'mt_pp',
				'author'      => $user_id,
				'post_status' => 'publish',
			);
			$posts     = get_posts( $post_args );
			if ( ! $posts ) {
				$post_id = wp_insert_post(
					array(
						'post_author' => $user_id,
						'post_type'   => 'mt_pp',
						'post_status' => 'publish',
						'post_title'  => $user->data->display_name,
					)
				);
			} else {
				$post    = end( $posts );
				$post_id = $post->ID;
			}

			return $post_id;
		}

		public function user_profile() {
			$current_user = wp_get_current_user();
			$user_id      = $current_user->ID;
			$post_id      = $this->get_user_post_id( $user_id );
			?>
			<div class="minimog-user-profile">
				<form action="" method="post" class="minimog-user-profile__form" enctype="multipart/form-data">
					<input type="file" accept="image/*" name="user_avatar" class="tm_user_avatar hidden">
					<input type="hidden" name="tm_user_id" class="tm_user_id"
					       value="<?php echo esc_attr( $user_id ); ?>">
					<input type="hidden" name="tm_user_post_id" class="tm_user_post_id"
					       value="<?php echo esc_attr( $post_id ); ?>">

					<div class="minimog-user-profile__action">
						<a href="#" class="upload_avatar">
							<span class="action-icon"><i class="far fa-upload"></i></span>
							<span class="action-text"><?php esc_html_e( 'Upload', 'minimog' ) ?></span>
						</a>
						<a href="#" class="remove_avatar">
							<span class="action-icon"><i class="far fa-trash-alt"></i></span>
							<span class="action-text"><?php esc_html_e( 'Remove', 'minimog' ) ?></span>
						</a>
					</div>
				</form>
			</div>
			<?php
		}

		public function upload_icon() {
			?>
			<a href="#" class="btn-toggle-avatar-upload-menu"
			   aria-label="<?php esc_attr_e( 'Upload', 'minimog' ); ?>"><i class="far fa-camera"></i></a>
			<?php
		}

		public function ajax_remove_avatar() {
			if ( ! check_ajax_referer( 'minimog-update-user-avatar' ) ) {
				wp_die();
			}

			if ( ! current_user_can( 'upload_files' ) ) {
				wp_die();
			}

			$post_id = isset( $_POST['tm_user_post_id'] ) ? absint( $_POST['tm_user_post_id'] ) : 0;
			$user_id = isset( $_POST['tm_user_id'] ) ? absint( $_POST['tm_user_id'] ) : 0;

			if ( 0 === $post_id || 0 === $user_id ) {
				wp_die();
			}

			update_user_option( $user_id, 'metronet_image_id', 0 );
			delete_post_meta( $post_id, '_thumbnail_id' );

			$avatar_url = MINIMOG_THEME_IMAGE_URI . '/person.png';

			wp_send_json( [
				'success' => 1,
				'data'    => $avatar_url,
			] );
		}

		public function ajax_upload_avatar() {
			if ( ! check_ajax_referer( 'minimog-update-user-avatar' ) ) {
				wp_die();
			}

			if ( ! current_user_can( 'upload_files' ) ) {
				wp_die();
			}

			$post_id = isset( $_POST['tm_user_post_id'] ) ? absint( $_POST['tm_user_post_id'] ) : 0;
			$user_id = isset( $_POST['tm_user_id'] ) ? absint( $_POST['tm_user_id'] ) : 0;

			$media_id = media_handle_upload( 'user_avatar', $post_id );

			$response = array();

			if ( ! is_wp_error( $media_id ) ) {
				update_user_option( $user_id, 'metronet_post_id', $post_id );
				update_user_option( $user_id, 'metronet_image_id', $media_id );

				set_post_thumbnail( $post_id, $media_id );

				$avatar_url = \Minimog_Image::get_the_post_thumbnail_url( [
					'post_id' => $post_id,
					'size'    => '100x100',
				] );

				$response['success'] = 1;
				$response['data']    = $avatar_url;

			} else {
				$response['success'] = 0;
			}

			wp_send_json( $response );
		}
	}

	Avatar_Upload::instance()->initialize();
}
