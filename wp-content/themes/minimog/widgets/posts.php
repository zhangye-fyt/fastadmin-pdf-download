<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Posts' ) ) {
	class Minimog_WP_Widget_Posts extends Minimog_Widget {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-posts';
			$this->widget_cssclass    = 'minimog-wp-widget-posts';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Posts', 'minimog' ) );
			$this->widget_description = esc_html__( 'Get list blog post.', 'minimog' );
			$this->settings           = array(
				'title'           => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'cat'             => array(
					'type'    => 'select',
					'std'     => 'recent_posts',
					'label'   => esc_html__( 'Category', 'minimog' ),
					'options' => [],
				),
				'show_thumbnail'  => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Thumbnail', 'minimog' ),
				),
				'show_categories' => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Category', 'minimog' ),
				),
				'num'             => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 40,
					'std'   => 5,
					'label' => esc_html__( 'Number Posts', 'minimog' ),
				),
			);

			parent::__construct();
		}

		public function set_form_settings() {
			$cat_options = array(
				'recent_posts' => esc_html__( 'Recent Posts', 'minimog' ),
				'sticky_posts' => esc_html__( 'Sticky Posts', 'minimog' ),
			);
			$categories  = get_categories( 'hide_empty=0' );
			if ( $categories ) {
				foreach ( $categories as $category ) {
					$cat_options[ $category->term_id ] = esc_html__( 'Category: ', 'minimog' ) . $category->name;
				}
			}

			$this->settings['cat']['options'] = $cat_options;
		}

		public function widget( $args, $instance ) {
			$cat             = isset( $instance['cat'] ) ? $instance['cat'] : $this->settings['cat']['std'];
			$num             = isset( $instance['num'] ) ? $instance['num'] : $this->settings['num']['std'];
			$show_thumbnail  = isset( $instance['show_thumbnail'] ) && $instance['show_thumbnail'] === 1 ? 'true' : 'false';
			$show_categories = isset( $instance['show_categories'] ) && $instance['show_categories'] === 1 ? 'true' : 'false';

			$this->widget_start( $args, $instance );

			if ( $cat === 'recent_posts' ) {
				$query_args = array(
					'post_type'           => 'post',
					'ignore_sticky_posts' => 1,
					'posts_per_page'      => $num,
					'orderby'             => 'date',
					'order'               => 'DESC',
					'no_found_rows'       => true,
				);
			} elseif ( $cat === 'sticky_posts' ) {
				$sticky     = get_option( 'sticky_posts' );
				$query_args = array(
					'post_type'      => 'post',
					'post__in'       => $sticky,
					'posts_per_page' => $num,
					'no_found_rows'  => true,
				);
			} else {
				$query_args = array(
					'post_type'           => 'post',
					'cat'                 => $cat,
					'ignore_sticky_posts' => 1,
					'posts_per_page'      => $num,
					'no_found_rows'       => true,
				);
			}

			$minimog_query = new WP_Query( $query_args );
			if ( $minimog_query->have_posts() ) {
				$count = $minimog_query->post_count;
				$i     = 0;
				?>
				<div class="post-list minimog-animation-zoom-in">
					<?php
					while ( $minimog_query->have_posts() ) {
						$minimog_query->the_post();
						$i++;
						$classes = array( 'post-item minimog-box' );
						if ( $i === 1 ) {
							$classes[] = 'first-post';
						} elseif ( $i === $count ) {
							$classes[] = 'last-post';
						}
						?>
						<div <?php post_class( implode( ' ', $classes ) ); ?> >
							<?php if ( $show_thumbnail === 'true' ) : ?>
								<div class="post-widget-thumbnail minimog-image">
									<a href="<?php the_permalink(); ?>">
										<?php if ( has_post_thumbnail() ) { ?>
											<?php Minimog_Image::the_post_thumbnail( array( 'size' => '70x46' ) ); ?>
											<?php
										} else {
											Minimog_Templates::image_placeholder( 70, 46 );
										}
										?>
									</a>
								</div>
							<?php endif; ?>
							<div class="post-widget-info">
								<?php if ( $show_categories === 'true' ) {
									Minimog_Post::instance()->the_category( array(
										'classes'   => 'post-widget-categories',
									) );
								} ?>

								<h5 class="post-widget-title title-has-link post-title-2-rows">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h5>
							</div>
						</div>
						<?php
					} ?>
				</div>
				<?php
			}
			wp_reset_postdata();

			$this->widget_end( $args, $instance );
		}
	}
}
