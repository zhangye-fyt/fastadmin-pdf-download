<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Blog_Query' ) ) {
	class Minimog_Blog_Query {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_action( 'wp_ajax_minimog_get_posts', [ $this, 'ajax_get_posts' ] );
			add_action( 'wp_ajax_nopriv_minimog_get_posts', [ $this, 'ajax_get_posts' ] );

			add_action( 'pre_get_posts', [ $this, 'update_main_query' ], 99 );
		}

		public function ajax_get_posts() {
			$base_url       = isset( $_GET['base_url'] ) ? sanitize_text_field( $_GET['base_url'] ) : '';
			$current        = isset( $_GET['blog-page'] ) ? intval( sanitize_text_field( $_GET['blog-page'] ) ) : 1;
			$per_page       = isset( $_GET['posts_per_page'] ) ? intval( sanitize_text_field( $_GET['posts_per_page'] ) ) : Minimog_Post::instance()->get_blog_posts_per_page();
			$template_part  = isset( $_GET['template_part'] ) ? sanitize_text_field( $_GET['template_part'] ) : '';
			$showingWidgets = isset( $_GET['widgets'] ) ? $_GET['widgets'] : array();

			$orig_request_uri = $_SERVER['REQUEST_URI'];

			// Overwrite the REQUEST_URI variable.
			$_SERVER['REQUEST_URI'] = $base_url;

			global $wp_query;
			$clone_wp_query = $wp_query;

			$query_args = [
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => $per_page,
				'paged'          => $current,
			];

			$tax_query = [];

			if ( ! empty( $_GET['is_tax'] ) ) {
				$current_tax_name = $_GET['is_current_tax'];
				$current_term_id  = $_GET['is_current_term_id'];

				if ( ! empty( $current_tax_name ) && ! empty( $current_term_id ) ) {
					$tax_query[] = [
						'taxonomy' => $current_tax_name,
						'field'    => 'term_id',
						'terms'    => [ $current_term_id ],
						'operator' => 'IN',
					];
				}
			}

			if ( ! empty( $tax_query ) ) {
				$query_args['tax_query'] = $tax_query;
			}

			if ( ! empty( $_GET['is_author'] ) ) {
				$author_id = intval( sanitize_text_field( $_GET['is_author_id'] ) );

				$query_args['author__in'] = [ $author_id ];
			}

			$date_query_vars = [
				'year',
				'monthnum',
				'w',
				'day',
				'hour',
				'minute',
				'second',
				'm',
			];

			foreach ( $date_query_vars as $var ) {
				if ( isset( $_GET[ $var ] ) && '' !== $_GET[ $var ] ) {
					$query_args[ $var ] = intval( $_GET[ $var ] );
				}
			}

			$query = new \WP_Query( $query_args );

			$wp_query = $query;

			$fragments = [];

			if ( ! empty( $showingWidgets ) ) {
				foreach ( $showingWidgets as $widget ) {
					$type     = $widget['name'];
					$instance = $widget['instance'];
					// Get only content html.
					$instance['widget_content_only'] = true;

					global $wp_widget_factory;

					// to avoid unwanted warnings let's check before using widget.
					if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
						ob_start();
						the_widget( $type, $instance );
						$widget_new_html = ob_get_clean();

						$fragments[ $widget['id'] . ' .widget-content-inner' ] = $widget_new_html;
					}
				}
			}

			if ( $query->have_posts() ) :
				ob_start();
				while ( $query->have_posts() ) : $query->the_post();
					$classes = array( 'grid-item', 'post-item' );
					?>
					<div <?php post_class( implode( ' ', $classes ) ); ?>>
						<?php minimog_load_template( 'blog/content-blog', $template_part ); ?>
					</div>
				<?php
				endwhile;
				wp_reset_postdata();

				$template = ob_get_clean();
				$template = preg_replace( '~>\s+<~', '><', $template );
				$template = trim( $template );
				$success  = true;
			else :
				$template = esc_html__( 'Sorry, we can not find any posts for this search.', 'minimog' );
				$success  = false;
			endif;

			ob_start();
			minimog_load_template( 'blog/loop/pagination' );
			$fragments['.minimog-grid-pagination'] = ob_get_clean();

			$response = [
				'success'   => $success,
				'template'  => $template,
				'fragments' => apply_filters( 'minimog/get_posts/fragments', $fragments ),
			];

			$wp_query = $clone_wp_query;

			// Restore the original REQUEST_URI - in case anything else would resort on it.
			$_SERVER['REQUEST_URI'] = $orig_request_uri;

			wp_send_json( $response );
		}

		/**
		 * @param \WP_Query $query
		 */
		public function update_main_query( $query ) {
			if ( $query->is_main_query() && ! is_admin() && Minimog_Post::instance()->is_archive() ) {
				if ( isset( $_GET['blog-page'] ) ) {
					$paged = intval( $_GET['blog-page'] );
					$query->set( 'paged', $paged );
				}

				$posts_per_page = Minimog_Post::instance()->get_blog_posts_per_page();

				// Change post per page.
				$query->set( 'posts_per_page', apply_filters( 'minimog/archive_blog/posts_per_page', $posts_per_page ) );
			}
		}

		public function get_query_vars() {
			global $wp_query;

			$is_post_tax = is_category() || is_tag();

			return [
				'posts_per_page' => Minimog_Post::instance()->get_blog_posts_per_page(),
				'year'           => $wp_query->query_vars['year'],
				'monthnum'       => $wp_query->query_vars['monthnum'],
				'w'              => $wp_query->query_vars['w'],
				'day'            => $wp_query->query_vars['day'],
				'hour'           => $wp_query->query_vars['hour'],
				'minute'         => $wp_query->query_vars['minute'],
				'second'         => $wp_query->query_vars['second'],
				'm'              => $wp_query->query_vars['m'],

				'is_author'          => is_author() ? 1 : 0,
				'is_author_id'       => is_author() ? get_queried_object()->ID : 0,
				'is_tax'             => $is_post_tax ? 1 : 0,
				'is_current_tax'     => $is_post_tax ? get_queried_object()->taxonomy : '',
				'is_current_term_id' => $is_post_tax ? absint( get_queried_object()->term_id ) : 0,
			];
		}
	}

	Minimog_Blog_Query::instance()->initialize();
}
