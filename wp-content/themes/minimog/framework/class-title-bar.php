<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Title_Bar' ) ) {

	class Minimog_Title_Bar {

		protected static $instance = null;

		const TYPE_STANDARD_01 = 'standard-01';
		const TYPE_STANDARD_02 = 'standard-02';
		const TYPE_STANDARD_03 = 'standard-03';
		const TYPE_MINIMAL_01  = 'minimal-01';
		const TYPE_FILL_01     = 'fill-01';

		const DEFAULT_TYPE         = 'standard-01';
		const DEFAULT_MINIMAL_TYPE = 'minimal-01';

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			// Adds custom classes to the array of body classes.
			add_filter( 'body_class', [ $this, 'body_classes' ] );
		}

		public function body_classes( $classes ) {
			$title_bar = Minimog_Global::instance()->get_title_bar_type();
			$classes[] = "title-bar-{$title_bar}";

			/**
			 * Add class to hide entry title if this title bar has same post title also.
			 */
			if ( is_singular() && Minimog_Helper::strpos_array( $title_bar, [ 'standard-', 'fill-' ] ) !== false ) {
				$post_type    = get_post_type();
				$heading_text = '';

				switch ( $post_type ) {
					case 'post' :
						$heading_text = Minimog::setting( 'title_bar_single_blog_title' );
						break;
					case 'product' :
						$heading_text = Minimog::setting( 'product_single_title_bar_title' );
						break;
				}

				if ( '' === $heading_text ) {
					$classes[] = 'title-bar-has-post-title';
				}
			}

			return $classes;
		}

		public function get_list( $default_option = false, $default_text = '' ) {
			$options = array(
				'none'        => esc_html__( 'Hide', 'minimog' ),
				'standard-01' => sprintf( esc_html__( 'Standard %s', 'minimog' ), '01' ),
				'standard-02' => sprintf( esc_html__( 'Standard %s', 'minimog' ), '02' ),
				'standard-03' => sprintf( esc_html__( 'Standard %s', 'minimog' ), '03' ),
				'minimal-01'  => sprintf( esc_html__( 'Minimal %s', 'minimog' ), '01' ),
				'fill-01'     => sprintf( esc_html__( 'Fill %s', 'minimog' ), '01' ),
			);

			if ( $default_option === true ) {
				if ( $default_text === '' ) {
					$default_text = esc_html__( 'Default', 'minimog' );
				}

				$options = array( '' => $default_text ) + $options;
			}

			return $options;
		}

		public function the_wrapper_class() {
			$classes = array( 'page-title-bar' );

			$type = Minimog_Global::instance()->get_title_bar_type();

			$classes[] = "page-title-bar-{$type}";

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function the_container_class() {
			$container_size = Minimog_Helper::get_post_meta( 'page_title_bar_container_size', '' );

			if ( '' === $container_size ) {
				$container_size = Minimog::setting( 'title_bar_content_width' );
			}

			$container_class = Minimog_Site_Layout::instance()->get_container_class( $container_size );

			echo 'class="' . esc_attr( $container_class ) . '"';
		}

		public function render() {
			$type = Minimog_Global::instance()->get_title_bar_type();

			if ( 'none' === $type ) {
				return;
			}

			$template = explode( '-', $type );
			$slug     = $template[0];
			$name     = null;

			if ( isset( $template[1] ) ) {
				$name = $template[1];
			}

			minimog_load_template( 'title-bar/' . $slug, $name );
		}

		public function render_title() {
			$heading_text = '';
			$heading_size = 'h1';

			if ( Minimog_Woo::instance()->is_shop() ) {
				$heading_text = Minimog::setting( 'product_archive_title_bar_title' );
			} elseif ( Minimog_Woo::instance()->is_product_taxonomy() ) {
				$heading_text = single_cat_title( '', false );
			} elseif ( is_post_type_archive() ) {
				$heading_text = sprintf( esc_html__( 'Archives: %s', 'minimog' ), post_type_archive_title( '', false ) );
			} elseif ( is_home() ) {
				$heading_text = Minimog::setting( 'title_bar_home_title' ) . single_tag_title( '', false );
			} elseif ( is_tag() ) {
				$heading_text = Minimog::setting( 'title_bar_archive_tag_title' ) . single_tag_title( '', false );
			} elseif ( is_author() ) {
				$heading_text = Minimog::setting( 'title_bar_archive_author_title' ) . '<span class="vcard">' . get_the_author() . '</span>';
			} elseif ( is_year() ) {
				$heading_text = Minimog::setting( 'title_bar_archive_year_title' ) . get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'minimog' ) );
			} elseif ( is_month() ) {
				$heading_text = Minimog::setting( 'title_bar_archive_month_title' ) . get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'minimog' ) );
			} elseif ( is_day() ) {
				$heading_text = Minimog::setting( 'title_bar_archive_day_title' ) . get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'minimog' ) );
			} elseif ( is_search() ) {
				$heading_text = Minimog::setting( 'title_bar_search_title' ) . '"' . get_search_query() . '"';
			} elseif ( is_category() || is_tax() ) {
				$heading_text = Minimog::setting( 'title_bar_archive_category_title' ) . single_cat_title( '', false );
			} elseif ( is_singular() ) {
				$heading_text = Minimog_Helper::get_post_meta( 'page_title_bar_custom_heading', '' );

				if ( '' === $heading_text ) {
					$post_type = get_post_type();
					switch ( $post_type ) {
						case 'post' :
							$heading_text = Minimog::setting( 'title_bar_single_blog_title' );
							break;
						case 'product' :
							$heading_text = Minimog::setting( 'product_single_title_bar_title' );
							break;
					}
				}

				if ( '' === $heading_text ) {
					$heading_text = get_the_title();
					$heading_size = 'h2';
				}
			} else {
				$heading_text = get_the_title();
			}

			$heading_size = apply_filters( 'minimog/title_bar/heading_size', $heading_size );
			$heading_text = apply_filters( 'minimog/title_bar/heading_text', $heading_text );

			$heading_text = wp_kses( $heading_text, array(
				'span' => [
					'class' => [],
				],
				'mark' => [
					'class' => [],
				],
			) );
			?>
			<div class="page-title-bar-heading">
				<?php printf( '<%1$s class="heading"><span>%2$s</span></%1$s>', $heading_size, $heading_text ); ?>
			</div>
			<?php
		}
	}

	Minimog_Title_Bar::instance()->initialize();
}
