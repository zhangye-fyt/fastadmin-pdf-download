<?php
/**
 * https://developers.facebook.com/docs/instagram-basic-display-api/guides/
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Instagram' ) ) {
	class Minimog_Instagram {
		private static $api_token;

		protected static $instance = null;

		const ACCESS_TOKEN_URL = 'https://minimog-wp.gitbook.io/minimog-ecommerce-wordpress-theme/theme-options/instagram';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_loaded', array( $this, 'set_access_token' ) );

			// Refresh token every 29 days
			add_action( 'minimog_monthly_tasks', array( $this, 'refresh_access_token' ) );
		}

		public function set_access_token() {
			$key = Minimog::setting( 'instagram_access_token' );

			self::$api_token = $key;
		}

		public function get_access_token() {
			return self::$api_token;
		}

		public function refresh_access_token() {
			$access_token = $this->get_access_token();

			if ( empty( $access_token ) ) {
				return new WP_Error( 'no_access_token', esc_html__( 'No access token', 'minimog' ) );
			}

			$data = wp_remote_get( 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $access_token );
			$data = wp_remote_retrieve_body( $data );
			$data = json_decode( $data, true );

			if ( isset( $data['error'] ) ) {
				return new WP_Error( 'access_token_refresh', $data['error']['message'] );
			}

			$new_access_token = $data['access_token'];

			$redux_options = get_option( 'minimog_options' );

			if ( ! empty( $redux_options ) && is_array( $redux_options ) ) {
				$redux_options['instagram_access_token'] = $new_access_token;
				update_option( 'minimog_options', $redux_options );
			} else {
				set_theme_mod( 'instagram_access_token', $new_access_token );
			}

			return $new_access_token;
		}

		/**
		 * Get user data
		 *
		 * @return bool|WP_Error|array
		 */
		public function get_user( $access_token = null ) {
			$access_token = ! empty( $access_token ) ? $access_token : $this->get_access_token();

			if ( empty( $access_token ) ) {
				return new WP_Error( 'no_access_token', esc_html__( 'No access token', 'minimog' ) );
			}

			/**
			 * Cache $user
			 */
			$transient_key = 'minimog_instagram_user_' . $access_token;

			$user = get_transient( $transient_key );

			if ( false === $user ) {
				$url  = add_query_arg( array(
					'fields'       => 'id,username',
					'access_token' => $access_token,
				), 'https://graph.instagram.com/me' );
				$data = wp_remote_get( $url );
				$data = wp_remote_retrieve_body( $data );

				if ( ! $data ) {
					return new WP_Error( 'no_user_data', esc_html__( 'No user data received', 'minimog' ) );
				}

				$user = json_decode( $data, true );

				if ( ! empty( $data ) ) {
					set_transient( $transient_key, $user, MONTH_IN_SECONDS );
				}
			}

			return $user;
		}

		/**
		 * Fetch photos from Instagram API
		 *
		 * @param  string $access_token
		 *
		 * @return array
		 */
		public function fetch_media( $access_token ) {
			$url = add_query_arg( array(
				'fields'       => 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp',
				'access_token' => $access_token,
			), 'https://graph.instagram.com/me/media' );

			$remote = wp_remote_retrieve_body( wp_remote_get( $url ) );
			$data   = json_decode( $remote, true );
			$images = array();

			if ( isset( $data['error'] ) ) {
				return new WP_Error( 'instagram_error', $data['error']['message'] );
			} else {
				if ( ! empty( $data['data'] ) ) {
					foreach ( $data['data'] as $media ) {
						$images[] = array(
							'id'      => $media['id'],
							'date'    => date( "U", strtotime( $media['timestamp'] ) ),
							// Convert ISO 8601 to unixtimestamp for sorting by date.
							'type'    => $media['media_type'],
							'caption' => isset( $media['caption'] ) ? $media['caption'] : $media['id'],
							'link'    => $media['permalink'],
							'images'  => array(
								'thumbnail' => ! empty( $media['thumbnail_url'] ) ? $media['thumbnail_url'] : $media['media_url'],
								'original'  => $media['media_url'],
							),
						);
					}
				}
			}

			return array(
				'images' => $images,
				'paging' => isset( $data['paging'] ) ? $data['paging'] : false,
			);
		}

		/**
		 * Get Instagram images
		 *
		 * @param int $limit
		 *
		 * @return array|WP_Error
		 */
		public function get_images( $limit = 12, $access_token = null ) {
			$access_token = ! empty( $access_token ) ? $access_token : $this->get_access_token();

			if ( empty( $access_token ) ) {
				return new WP_Error( 'instagram_no_access_token', esc_html__( 'No access token', 'minimog' ) );
			}

			$user = $this->get_user( $access_token );

			if ( ! $user || is_wp_error( $user ) ) {
				return $user;
			}

			if ( isset( $user['error'] ) ) {
				return new WP_Error( 'instagram_access_token_expired', $user['error']['message'] );
			}

			/**
			 * Cache $images
			 */
			$transient_key = 'minimog_instagram_photos_' . sanitize_title_with_dashes( $user['username'] . '__' . $limit );
			$images        = get_transient( $transient_key );

			if ( false === $images || empty( $images ) ) {
				$images = array();
				$next   = false;

				while ( count( $images ) < $limit ) {
					if ( ! $next ) {
						$fetched = $this->fetch_media( $access_token );
					} else {
						$fetched = $this->fetch_media( $next );
					}

					if ( is_wp_error( $fetched ) ) {
						break;
					}

					$images = array_merge( $images, $fetched['images'] );
					$next   = $fetched['paging'] ? $fetched['paging']['cursors']['after'] : false;

					if ( ! $next ) {
						break;
					}
				}

				if ( ! empty( $images ) ) {
					set_transient( $transient_key, $images, HOUR_IN_SECONDS * 2 );
				}
			}

			if ( ! empty( $images ) ) {
				return $images;
			} else {
				return new WP_Error( 'instagram_no_images', esc_html__( 'Instagram did not return any images.', 'minimog' ) );
			}
		}

		/**
		 * Get the output of an photo
		 *
		 * @param array $media Image Data
		 *
		 * @return string
		 */
		public function get_image( $media ) {
			if ( ! is_array( $media ) ) {
				return '';
			}

			$classes = [
				'minimog-image',
				'minimog-instagram-image',
			];

			$caption = is_array( $media['caption'] ) && isset( $media['caption']['text'] ) ? $media['caption']['text'] : $media['caption'];

			$image_attributes = [
				'src' => $media['images']['thumbnail'],
				'alt' => $caption,
			];

			$item_classes = [
				'instagram-item-link',
			];

			$lazy_load_enable = \Minimog::setting( 'image_lazy_load_enable' ) ? true : false;

			if ( $lazy_load_enable && ! \Minimog::elementor_is_edit_mode() ) {
				$image_attributes['class']    = 'll-image';
				$image_attributes['src']      = \Minimog_Image::get_lazy_image_src();
				$image_attributes['data-src'] = $media['images']['thumbnail'];
			}

			$image = \Minimog_Image::build_img_tag( $image_attributes );

			if ( $lazy_load_enable ) {
				$image = \Minimog_Image::build_lazy_img_tag( $image, 100, 100 );
			}

			return sprintf(
				'<div class="%s">
					<a href="%s" class="%s" target="_blank" rel="nofollow">
						<span class="icon"><i class="fab fa-instagram"></i></span>
						%s
					</a>
				</div>',
				esc_attr( implode( ' ', $classes ) ),
				esc_url( $media['link'] ),
				esc_attr( implode( ' ', $item_classes ) ),
				$image
			);
		}
	}

	Minimog_Instagram::instance()->initialize();
}
