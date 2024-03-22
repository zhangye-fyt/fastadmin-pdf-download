<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Post' ) ) {
	class Minimog_Post {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			minimog_require_file_once( MINIMOG_FRAMEWORK_DIR . '/blog/blog-query.php' );

			add_action( 'wp_ajax_post_infinite_load', [ $this, 'infinite_load' ] );
			add_action( 'wp_ajax_nopriv_post_infinite_load', [ $this, 'infinite_load' ] );

			add_filter( 'body_class', [ $this, 'body_class' ] );

			add_filter( 'post_class', [ $this, 'post_class' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );

			add_filter( 'minimog/page_sidebar/class', [ $this, 'set_sidebar_class' ] );
			add_filter( 'minimog/page_sidebar/single_width', [ $this, 'set_single_sidebar_width' ] );
			add_filter( 'minimog/page_sidebar/single_offset', [ $this, 'set_single_sidebar_offset' ] );
		}

		public function get_post_type() {
			return 'post';
		}

		/**
		 * Check if current page is category or tag pages
		 */
		function is_taxonomy() {
			return is_category() || is_tag();
		}

		public function is_archive() {
			return $this->is_taxonomy() || is_home() || is_author() || is_date() || is_post_type_archive( $this->get_post_type() );
		}

		public function is_single() {
			return is_singular( $this->get_post_type() );
		}

		public function set_single_sidebar_width( $width ) {
			if ( $this->is_archive() ) {
				$new_width = Minimog::setting( 'blog_archive_single_sidebar_width' );

				if ( isset( $new_width['width'] ) && '' !== $new_width['width'] ) {
					return $new_width['width'];
				}
			}

			return $width;
		}

		public function set_single_sidebar_offset( $offset ) {
			if ( $this->is_archive() ) {
				$new_offset = Minimog::setting( 'blog_archive_single_sidebar_offset' );

				if ( isset( $new_offset['width'] ) && '' !== $new_offset['width'] ) {
					/**
					 * Redux - Unit is included in dimensions type
					 * return $new_offset['width'] . 'px';
					 */
					return $new_offset['width'];
				}
			}

			return $offset;
		}

		public function set_sidebar_class( $class ) {
			if ( $this->is_archive() ) {
				$sidebar_style = Minimog::setting( 'blog_archive_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			if ( $this->is_single() ) {
				$sidebar_style = Minimog::setting( 'post_page_sidebar_style' );

				if ( ! empty( $sidebar_style ) ) {
					$class[] = 'style-' . $sidebar_style;
				}
			}

			return $class;
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 *
		 * @return array
		 */
		public function body_class( $classes ) {
			if ( $this->is_archive() ) {
				$classes[] = 'blog-archive';

				$blog_archive_style = Minimog::setting( 'blog_archive_style' );
				$classes[]          = "blog-archive-style-{$blog_archive_style}";
			}

			return $classes;
		}

		function post_class( $classes ) {
			if ( ! has_post_thumbnail() ) {
				$classes[] = 'post-no-thumbnail';
			}

			return $classes;
		}

		public function frontend_scripts() {
			$min = Minimog_Enqueue::instance()->get_min_suffix();

			wp_register_script( 'minimog-blog-archive', MINIMOG_THEME_URI . "/assets/js/blog-archive{$min}.js", [
				'jquery',
				'perfect-scrollbar',
			], MINIMOG_THEME_VERSION, true );

			if ( $this->is_archive() ) {
				wp_enqueue_script( 'minimog-grid-layout' );
				wp_enqueue_script( 'minimog-blog-archive' );
			}

			if ( is_singular( 'post' ) ) {
				wp_enqueue_script( 'lightgallery' );
				wp_enqueue_style( 'lightgallery' );
			}
		}

		public function get_blog_posts_per_page() {
			// Numbers per page.
			$numbers = Minimog::setting( 'blog_archive_posts_per_page' );

			$layout_preset = isset( $_GET['blog_archive_preset'] ) ? Minimog_Helper::data_clean( $_GET['blog_archive_preset'] ) : false;

			// Hard set post per page. because override preset settings run after init hook.
			if ( $layout_preset ) {
				switch ( $layout_preset ) {
					case '01':
						$numbers = 12;
						break;
					case '02':
						$numbers = 16;
						break;
					case '03':
						$numbers = 10;
						break;
					case '04': // List
						$numbers = 7;
						break;
				}
			}

			return $numbers;
		}

		public function get_blog_base_url() {
			global $wp_query;

			if ( is_home() ) {
				$link = home_url();
			} elseif ( is_category() ) {
				$term_slug = get_query_var( 'category' ); // This query var is empty when Permalink structure is custom.

				if ( empty( $term_slug ) ) {
					$term_slug = $wp_query->query_vars['category_name'] ?? '';
				}

				$link = get_term_link( $term_slug, 'category' );
			} elseif ( is_tag() ) {
				$term_slug = get_query_var( 'tag' );

				if ( empty( $term_slug ) ) {
					$term_slug = $wp_query->query_vars['tag'] ?? '';
				}

				// Then ajax pagination query var now is tag_id.
				if ( empty( $term_slug ) ) {
					$term_slug = $wp_query->query_vars['tag_id'] ?? '';
				}

				$link = get_term_link( $term_slug, 'post_tag' );
			} elseif ( is_year() ) {
				$link = get_year_link( get_query_var( 'year' ) );
			} elseif ( is_month() ) {
				$link = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
			} elseif ( is_day() ) {
				$link = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
			} else {
				$queried_object = get_queried_object();

				if ( ! empty( $queried_object->slug ) && ! empty( $queried_object->taxonomy ) ) {
					$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
				} else {
					$link = home_url();
				}
			}

			return $link;
		}

		public function get_blog_active_filters_url( $filters = array(), $link = '' ) {
			if ( empty( $link ) ) {
				$link = Minimog_Post::instance()->get_blog_base_url();
			}

			if ( empty( $filters ) ) {
				$filters = $_GET;
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( wp_specialchars_decode( get_search_query() ) ), $link );
			}

			if ( ! empty( $filters['filter_category'] ) ) {
				$link = add_query_arg( 'filter_category', wc_clean( wp_unslash( $filters['filter_category'] ) ), $link );
			}

			if ( ! empty( $filters['filter_tag'] ) ) {
				$link = add_query_arg( 'filter_tag', wc_clean( wp_unslash( $filters['filter_tag'] ) ), $link );
			}

			return $link;
		}

		function infinite_load() {
			$source     = isset( $_GET['source'] ) ? sanitize_text_field( $_GET['source'] ) : '';
			$query_vars = $_GET['query_vars'];

			if ( 'custom_query' === $source && isset( $query_vars['extra_tax_query'] ) ) {
				$query_vars = Minimog_Helper::build_extra_terms_query( $query_vars, $query_vars['extra_tax_query'] );
			}

			$minimog_query = new WP_Query( $query_vars );

			$settings = isset( $_GET['settings'] ) ? $_GET['settings'] : array();

			$response = array(
				'max_num_pages' => $minimog_query->max_num_pages,
				'found_posts'   => $minimog_query->found_posts,
				'count'         => $minimog_query->post_count,
			);

			$layout = $settings['layout'];

			if ( 'grid' === $settings['layout'] ) {
				$caption_style = 'yes' === $settings['show_caption'] ? $settings['caption_style'] : '01';
				$layout        = 'grid-' . $caption_style;
			}

			ob_start();

			if ( $minimog_query->have_posts() ) :
				set_query_var( 'minimog_query', $minimog_query );
				set_query_var( 'settings', $settings );

				while ( $minimog_query->have_posts() ) : $minimog_query->the_post();
					get_template_part( 'loop/widgets/blog/style', $layout );
				endwhile;

				wp_reset_postdata();
			endif;

			$template = ob_get_contents();
			ob_clean();

			$response['template'] = $template;

			echo json_encode( $response );

			wp_die();
		}

		function get_related_posts( $args ) {
			$defaults = array(
				'post_id'      => '',
				'number_posts' => 3,
			);
			$args     = wp_parse_args( $args, $defaults );
			if ( $args['number_posts'] <= 0 || $args['post_id'] === '' ) {
				return false;
			}

			$categories = get_the_category( $args['post_id'] );

			if ( ! $categories ) {
				return false;
			}

			foreach ( $categories as $category ) {
				if ( $category->parent === 0 ) {
					$term_ids[] = $category->term_id;
				} else {
					$term_ids[] = $category->parent;
					$term_ids[] = $category->term_id;
				}
			}

			// Remove duplicate values from the array.
			$unique_array = array_unique( $term_ids );

			$query_args = array(
				'post_type'      => $this->get_post_type(),
				'orderby'        => 'date',
				'order'          => 'DESC',
				'posts_per_page' => $args['number_posts'],
				'post__not_in'   => array( $args['post_id'] ),
				'no_found_rows'  => true,
				'tax_query'      => array(
					array(
						'taxonomy'         => 'category',
						'terms'            => $unique_array,
						'include_children' => false,
					),
				),
			);

			$query = new WP_Query( $query_args );

			wp_reset_postdata();

			return $query;
		}

		function get_the_post_meta( $name = '', $default = '' ) {
			$post_meta = get_post_meta( get_the_ID(), 'insight_post_options', true );

			if ( ! empty( $post_meta ) ) {
				$post_options = maybe_unserialize( $post_meta );

				if ( $post_options !== false && isset( $post_options[ $name ] ) ) {
					return $post_options[ $name ];
				}
			}

			return $default;
		}

		function get_the_post_format() {
			$format = '';
			if ( get_post_format() !== false ) {
				$format = get_post_format();
			}

			return $format;
		}

		function the_categories( $args = array() ) {
			if ( ! has_category() ) {
				return;
			}

			$defaults = array(
				'classes'    => 'post-categories',
				'separator'  => ', ',
				'show_links' => true,
				'single'     => true,
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<div class="<?php echo esc_attr( $args['classes'] ); ?>">
				<?php
				$categories = get_the_category();
				$loop_count = 0;
				foreach ( $categories as $category ) {
					if ( $loop_count > 0 ) {
						echo "{$args['separator']}";
					}

					if ( true === $args['show_links'] ) {
						printf( '<a href="%1$s"><span>%2$s</span></a>', esc_url( get_category_link( $category->term_id ) ), $category->name );
					} else {
						echo "<span>{$category->name}</span>";
					}

					$loop_count ++;

					if ( true === $args['single'] ) {
						break;
					}
				}
				?>
			</div>
			<?php
		}

		/**
		 * @param array $args
		 *
		 * Render first category template of the post.
		 */
		function the_category( $args = array() ) {
			if ( ! has_category() ) {
				return;
			}

			$defaults = array(
				'classes'    => 'post-categories',
				'show_links' => true,
			);
			$args     = wp_parse_args( $args, $defaults );
			?>
			<div class="<?php echo esc_attr( $args['classes'] ); ?>">
				<?php
				$categories = get_the_category();
				$category   = $categories[0];

				if ( $args['show_links'] ) {
					$link = get_term_link( $category );
					printf( '<a href="%1$s" rel="category tag"><span>%2$s</span></a>', $link, $category->name );
				} else {
					echo "<span>{$category->name}</span>";
				}
				?>
			</div>
			<?php
		}

		function nav_page_links() {
			?>
			<div class="blog-nav-links">
				<div class="nav-list">
					<div class="nav-item prev">
						<div class="inner">
							<?php
							if ( get_previous_post() ) {
								$nav_item_text = '<span class="nav-item--text prev-post">' . esc_html__( 'Previous', 'minimog' ) . '</span>';

								previous_post_link( '%link', $nav_item_text . '<h6>%title</h6>' );
							}
							?>
						</div>
					</div>
					<div class="nav-item next">
						<div class="inner">
							<?php
							if ( get_next_post() ) {
								$nav_item_text = '<span class="nav-item--text next-post">' . esc_html__( 'Next', 'minimog' ) . '</span>';

								next_post_link( '%link', $nav_item_text . '<h6>%title</h6>' );
							}
							?>
						</div>
					</div>
				</div>
			</div>

			<?php
		}

		function meta_author_template() {
			?>
			<div class="post-author">
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
					<span><?php esc_html_e( 'By', 'minimog' ); ?></span>
					<?php the_author(); ?>
				</a>
			</div>
			<?php
		}

		function meta_date_template() {
			?>
			<div class="post-date">
				<?php echo get_the_date(); ?>
			</div>
			<?php
		}

		function meta_view_count_template() {
			if ( function_exists( 'the_views' ) ) : ?>
				<div class="post-view">
					<?php the_views(); ?>
				</div>
			<?php
			endif;
		}

		function meta_comment_count_template() {
			?>
			<div class="post-comments">
				<?php
				$comment_count = get_comments_number();
				printf( _n( '%s comment', '%s comments', $comment_count, 'minimog' ), number_format_i18n( $comment_count ) );
				?>
			</div>
			<?php
		}

		function entry_date() {
			?>
			<div class="post-date">
				<span><?php esc_html_e( 'on', 'minimog' ); ?></span>
				<?php echo get_the_date(); ?>
			</div>
			<?php
		}

		function entry_meta_comment_count_template() {
			?>
			<div class="post-comments">
				<a href="#comments">
					<?php
					$comment_count = get_comments_number();
					printf( _n( '%s comment', '%s comments', $comment_count, 'minimog' ), number_format_i18n( $comment_count ) );
					?>
				</a>
			</div>
			<?php
		}

		function entry_categories() {
			if ( '1' !== Minimog::setting( 'single_post_categories_enable' ) || ! has_category() ) {
				return;
			}
			?>
			<div class="entry-post-categories">
				<?php the_category( ' ' ); ?>
			</div>
			<?php
		}

		function entry_tags() {
			if ( '1' !== Minimog::setting( 'single_post_tags_enable' ) || ! has_tag() ) {
				return;
			}
			?>
			<div class="entry-post-tags">
				<div class="tags-label heading-color"><?php echo esc_html__( 'Tags', 'minimog' ) . ': '; ?></div>
				<div class="tagcloud">
					<?php the_tags( '', ', ', '' ); ?>
				</div>
			</div>
			<?php
		}

		function entry_feature() {
			$post_feature_enable = Minimog_Helper::get_post_meta( 'post_entry_feature', 'default' );
			$post_feature_enable = 'default' === $post_feature_enable ? Minimog::setting( 'single_post_feature_enable' ) : $post_feature_enable;

			if ( '1' !== $post_feature_enable ) {
				return;
			}

			$post_format = $this->get_the_post_format();
			$image_size  = apply_filters( 'minimog/single_post/featured_image_size', '870x563' );

			// if ( 'none' === Minimog_Global::instance()->get_sidebar_status() ) {
			// 	$thumbnail_size = '1170x757';
			// }

			switch ( $post_format ) {
				case 'gallery':
					$this->entry_feature_gallery( $image_size );
					break;
				case 'audio':
					$this->entry_feature_audio();
					break;
				case 'video':
					$this->entry_feature_video( $image_size );
					break;
				case 'quote':
					$this->entry_feature_quote();
					break;
				case 'link':
					$this->entry_feature_link();
					break;
				default:
					$this->entry_feature_standard( $image_size );
					break;
			}
		}

		private function entry_feature_standard( $size ) {
			if ( ! has_post_thumbnail() ) {
				return;
			}
			?>
			<div class="entry-post-feature post-thumbnail">
				<?php Minimog_Image::the_post_thumbnail( [ 'size' => $size, ] ); ?>
			</div>
			<?php
		}

		private function entry_feature_gallery( $size ) {
			$gallery = $this->get_the_post_meta( 'post_gallery' );
			if ( empty( $gallery ) ) {
				return;
			}

			$slider_args = [
				'data-nav'            => '1',
				'data-loop'           => '1',
				'data-gutter-desktop' => '30',
			];
			?>
			<div
				class="entry-post-feature post-gallery tm-swiper tm-slider nav-style-02" <?php echo Minimog_Helper::slider_args_to_html_attr( $slider_args ); ?>>
				<div class="swiper-inner">
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<?php foreach ( $gallery as $image ) { ?>
								<div class="swiper-slide">
									<?php Minimog_Image::the_attachment_by_id( array(
										'id'   => $image['id'],
										'size' => $size,
									) ); ?>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		private function entry_feature_audio() {
			$audio = Minimog_Post::instance()->get_the_post_meta( 'post_audio' );
			if ( empty( $audio ) ) {
				return;
			}

			if ( strrpos( $audio, '.mp3' ) !== false ) {
				echo do_shortcode( '[audio mp3="' . $audio . '"][/audio]' );
			} else {
				?>
				<div class="entry-post-feature post-audio">
					<?php if ( wp_oembed_get( $audio ) ) { ?>
						<?php echo Minimog_Helper::w3c_iframe( wp_oembed_get( $audio ) ); ?>
					<?php } ?>
				</div>
				<?php
			}
		}

		private function entry_feature_video( $size ) {
			$video = $this->get_the_post_meta( 'post_video' );
			if ( empty( $video ) ) {
				return;
			}
			?>
			<div class="entry-post-feature post-video tm-popup-video type-poster minimog-animation-zoom-in">
				<a href="<?php echo esc_url( $video ); ?>" class="video-link minimog-box link-secret">
					<div class="video-poster">
						<div class="minimog-image">
							<?php if ( has_post_thumbnail() ) { ?>
								<?php Minimog_Image::the_post_thumbnail( [ 'size' => $size, ] ); ?>
							<?php } ?>
						</div>
						<div class="video-overlay"></div>

						<div class="video-button">
							<div class="video-play video-play-icon">
								<span class="icon"></span>
							</div>
						</div>
					</div>
				</a>
			</div>
			<?php
		}

		private function entry_feature_quote() {
			$text = $this->get_the_post_meta( 'post_quote_text' );
			if ( empty( $text ) ) {
				return;
			}
			$name     = $this->get_the_post_meta( 'post_quote_name' );
			$url      = $this->get_the_post_meta( 'post_quote_url' );
			$position = $this->get_the_post_meta( 'post_quote_position' );

			$quote_icon = '<span class="blockquote-icon svg-icon">
								<svg width="32" height="24" viewBox="0 0 32 24">
									<path d="M6.26087 12.5217C7.88406 12.5217 9.39131 11.942 10.5507 10.8986C9.15942 15.3043 6.14493 18.8986 1.50725 22.8406L2.08696 23.4203C8.57971 18.2029 12.5217 12.5217 12.5217 6.26087C12.5217 2.78261 9.73913 -1.94611e-06 6.26087 -2.25019e-06C2.78261 -2.55427e-06 1.8042e-06 2.78261 1.50012e-06 6.26087C1.19604e-06 9.73913 2.78261 12.5217 6.26087 12.5217ZM25.7391 12.5217C27.3623 12.5217 28.8696 11.942 29.913 10.8986C28.6377 15.3043 25.6232 18.8986 20.9855 22.8406L21.5652 23.4203C28.058 18.2029 32 12.5217 32 6.26087C32 2.78261 29.2174 -2.43263e-07 25.7391 -5.47343e-07C22.2609 -8.51422e-07 19.4783 2.78261 19.4783 6.26087C19.4783 9.73913 22.2609 12.5217 25.7391 12.5217Z"/>
								</svg>
							</span>';

			$quote_icon = apply_filters( 'minimog/post_quote/quote_icon', $quote_icon );

			?>
			<div class="entry-post-feature post-quote">
				<div class="post-quote-content tm-blockquote tm-blockquote--style-01">
					<blockquote>
						<?php if ( ! empty( $quote_icon ) ) {
							echo '<div class="tm-blockquote__icon">' . $quote_icon . '</div>';
						} ?>
						<p class="tm-blockquote__content"><?php echo esc_html( $text ); ?></p>

						<?php
						if ( ! empty( $name ) ) {
							$info = sprintf( '<cite class="tm-blockquote__author-name">%s</cite><span class="tm-blockquote__author-position">%s</span>', esc_html( $name ), ! empty( $position ) ? esc_html( $position ) : '' );

							if ( ! empty( $url ) ) {
								$info = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $url ), $info );
							}

							echo sprintf( '<div class="tm-blockquote__footer">%s</div>', $info );
						}
						?>
					</blockquote>
				</div>
			</div>
			<?php
		}

		private function entry_feature_link() {
			$link = $this->get_the_post_meta( 'post_link' );
			if ( empty( $link ) ) {
				return;
			}
			?>
			<div class="entry-post-feature post-link">
				<a href="<?php echo esc_url( $link ); ?>" target="_blank"><?php echo esc_html( $link ); ?></a>
			</div>
			<?php
		}

		function entry_share( $args = array() ) {
			if ( '1' !== Minimog::setting( 'single_post_share_enable' ) || ! class_exists( 'InsightCore' ) ) {
				return;
			}

			$social_sharing = Minimog::setting( 'social_sharing_item_enable' );
			if ( empty( $social_sharing ) ) {
				return;
			}
			?>
			<div class="entry-post-share">
				<div class="post-share style-01">
					<div class="share-label heading-color">
						<?php esc_html_e( 'Share:', 'minimog' ); ?>
					</div>
					<div class="share-media">
						<div class="share-list">
							<?php Minimog_Templates::get_sharing_list( $args ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		function loop_share( $args = array() ) {
			if ( ! class_exists( 'InsightCore' ) ) {
				return;
			}

			$social_sharing = Minimog::setting( 'social_sharing_item_enable' );
			if ( empty( $social_sharing ) ) {
				return;
			}
			?>
			<div class="post-share style-01">
				<div class="share-label">
					<?php esc_html_e( 'Share this post', 'minimog' ); ?>
				</div>
				<div class="share-media">
					<span class="share-icon far fa-share-alt"></span>

					<div class="share-list">
						<?php Minimog_Templates::get_sharing_list( $args ); ?>
					</div>
				</div>
			</div>
			<?php
		}

		function the_post_meta( $meta = array() ) {
			if ( empty( $meta ) ) {
				return;
			}
			?>
			<div class="entry-post-meta post-meta">
				<div class="entry-post-meta__inner inner">
					<?php if ( in_array( 'author', $meta, true ) ): ?>
						<?php $this->meta_author_template(); ?>
					<?php endif; ?>

					<?php if ( in_array( 'date', $meta, true ) ): ?>
						<?php $this->meta_date_template(); ?>
					<?php endif; ?>

					<?php if ( in_array( 'views', $meta, true ) ): ?>
						<?php $this->meta_view_count_template(); ?>
					<?php endif; ?>

					<?php if ( in_array( 'comments', $meta, true ) ): ?>
						<?php $this->meta_comment_count_template(); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php
		}
	}

	Minimog_Post::instance()->initialize();
}
