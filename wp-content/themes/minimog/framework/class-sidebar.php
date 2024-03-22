<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Sidebar' ) ) {
	class Minimog_Sidebar {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function initialize() {
			// Register widget areas.
			add_action( 'widgets_init', [ $this, 'register_sidebars' ] );

			add_filter( 'insight_core_dynamic_sidebar_args', [ $this, 'change_sidebar_args' ] );
		}

		/**
		 * Change sidebar args of dynamic sidebar.
		 *
		 * @param $args
		 *
		 * @return array
		 */
		public function change_sidebar_args( $args ) {
			$args['before_title'] = '<p class="widget-title heading">';
			$args['after_title']  = '</p>';

			return $args;
		}

		public function get_default_sidebar_args() {
			return [
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<p class="widget-title heading">',
				'after_title'   => '</p>',
			];
		}

		/**
		 * Register widget area.
		 *
		 * @access public
		 * @link   https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 */
		public function register_sidebars() {
			$defaults = $this->get_default_sidebar_args();

			register_sidebar( array_merge( $defaults, array(
				'id'          => 'blog_sidebar',
				'name'        => esc_html__( 'Blog Sidebar', 'minimog' ),
				'description' => esc_html__( 'Add widgets here.', 'minimog' ),
			) ) );

			register_sidebar( array_merge( $defaults, array(
				'id'          => 'blog_sidebar_2',
				'name'        => esc_html__( 'Blog Sidebar 2', 'minimog' ),
				'description' => esc_html__( 'Add widgets here.', 'minimog' ),
			) ) );

			register_sidebar( array_merge( $defaults, array(
				'id'          => 'page_sidebar',
				'name'        => esc_html__( 'Page Sidebar', 'minimog' ),
				'description' => esc_html__( 'Add widgets here.', 'minimog' ),
			) ) );

			register_sidebar( array_merge( $defaults, array(
				'id'          => 'top_bar_widgets',
				'name'        => esc_html__( 'Top Bar Widgets', 'minimog' ),
				'description' => esc_html__( 'Add widgets here.', 'minimog' ),
			) ) );

			do_action( 'minimog/register_sidebars', $defaults );
		}

		/**
		 * @param string $name name of sidebar to render.
		 *
		 * Check sidebar is active then render it.
		 */
		public function generated_sidebar( $name ) {
			if ( is_active_sidebar( $name ) ) {
				dynamic_sidebar( $name );
			}
		}

		public function render( $template_position = 'left' ) {
			$sidebar1         = Minimog_Global::instance()->get_sidebar_1();
			$sidebar2         = Minimog_Global::instance()->get_sidebar_2();
			$sidebar_position = Minimog_Global::instance()->get_sidebar_position();

			if ( 'none' !== $sidebar1 ) {
				$classes = [ 'page-sidebar', 'page-sidebar-' . $template_position ];
				$style   = '';

				if ( is_single() ) {
					$style = Minimog_Helper::get_post_meta( 'page_sidebar_style', '' );
				}

				if ( '' === $style ) {
					$classes = apply_filters( 'minimog/page_sidebar/class', $classes );
				} else {
					$classes[] = 'style-' . $style;
				}

				$widgets_collapsible = apply_filters( 'minimog/page_sidebar/widgets_collapsible', false );
				if ( $widgets_collapsible ) {
					$classes[] = 'sidebar-widgets-collapsible';
				}

				if ( $template_position === $sidebar_position ) {
					$sidebar1_classes   = $classes;
					$sidebar1_classes[] = 'sidebar-primary';

					$off_sidebar_on = apply_filters( 'minimog/page_sidebar/1/off_sidebar/enable', '0' );
					switch ( $off_sidebar_on ) {
						case '1': // All devices.
							$sidebar1_classes[] = 'sidebar-off';
							break;
						case 'mobile': // Only mobile.
							$sidebar1_classes[] = 'sidebar-off-mobile';
							break;
					}

					$this->get_sidebar_html( $sidebar1_classes, $sidebar1, true );
					?>
					<?php if ( '0' !== $off_sidebar_on ) : ?>
						<?php
						$primary_toggle_btn = 'btn-js-open-off-sidebar btn-open-off-sidebar-mobile btn-open-sidebar1';
						$primary_toggle_btn .= " position-{$sidebar_position}";

						$toggle_text = apply_filters( 'minimog/page_sidebar/1/off_sidebar/toggle_text', __( 'Open Sidebar', 'minimog' ) );
						?>
						<a href="#" class="<?php echo esc_attr( $primary_toggle_btn ); ?>"
						   data-sidebar-target="primary">
							<span class="button-text"><?php echo esc_html( $toggle_text ); ?></span>
						</a>
					<?php endif; ?>
					<?php
				}

				/**
				 * Only render sidebar 2 if sidebar 1 defined.
				 */
				if ( 'none' !== $sidebar2 && $template_position !== $sidebar_position ) {
					$sidebar2_classes   = $classes;
					$sidebar2_classes[] = 'sidebar-secondary';

					$off_sidebar_on = apply_filters( 'minimog/page_sidebar/2/off_sidebar/enable', '0' );

					switch ( $off_sidebar_on ) {
						case '1': // All devices.
							$sidebar2_classes[] = 'sidebar-off';
							break;
						case 'mobile': // Only mobile.
							$sidebar2_classes[] = 'sidebar-off-mobile';
							break;
					}

					$this->get_sidebar_html( $sidebar2_classes, $sidebar2 );
					?>
					<?php if ( '0' !== $off_sidebar_on ) : ?>
						<?php
						$secondary_toggle_btn = 'btn-js-open-off-sidebar btn-open-off-sidebar-mobile btn-open-sidebar2';
						$secondary_toggle_btn .= 'left' === $sidebar_position ? ' position-right' : ' position-left';

						$toggle_text = apply_filters( 'minimog/page_sidebar/2/off_sidebar/toggle_text', __( 'Open Sidebar', 'minimog' ) );
						?>
						<a href="#" class="<?php echo esc_attr( $secondary_toggle_btn ); ?>"
						   data-sidebar-target="secondary">
							<span class="button-text"><?php echo esc_html( $toggle_text ); ?></span>
						</a>
					<?php endif; ?>
					<?php
				}
			}
		}

		public function get_sidebar_html( $classes, $name, $first_sidebar = false ) {
			$classes = implode( ' ', $classes );
			?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<div class="page-sidebar-inner tm-sticky-column" data-sticky-group="content-sidebar"
				     itemscope="itemscope">
					<a href="#" class="btn-close-off-sidebar">
						<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path
								d="M10.6465 8.975L16.7012 15.0297C16.8639 15.1924 16.8639 15.3715 16.7012 15.5668L15.5781 16.6898C15.3828 16.8526 15.2038 16.8526 15.041 16.6898L14.0156 15.6156L8.98633 10.6352L2.93164 16.6898C2.76888 16.8526 2.58984 16.8526 2.39453 16.6898L1.27148 15.5668C1.10872 15.3715 1.10872 15.1924 1.27148 15.0297L7.32617 8.975L1.27148 2.92031C1.10872 2.75755 1.10872 2.57852 1.27148 2.3832L2.39453 1.26016C2.58984 1.0974 2.76888 1.0974 2.93164 1.26016L8.98633 7.31484L15.041 1.26016C15.2038 1.0974 15.3828 1.0974 15.5781 1.26016L16.7012 2.3832C16.8639 2.57852 16.8639 2.75755 16.7012 2.92031L15.627 3.9457L10.6465 8.975Z"
								fill="#000000"/>
						</svg>
					</a>
					<div class="page-sidebar-content-wrap">
						<div class="page-sidebar-content">
							<?php do_action( 'minimog/page_sidebar/before_content', $name, $first_sidebar ); ?>

							<?php dynamic_sidebar( $name ); ?>

							<?php do_action( 'minimog/page_sidebar/after_content', $name, $first_sidebar ); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

		public function get_supported_style_options( $args = array() ) {
			$defaults = [
				'default' => false,
			];

			$args = wp_parse_args( $args, $defaults );

			$options = [
				'01' => '01',
				'02' => '02',
			];

			if ( $args['default'] ) {
				$options = [ '' => esc_attr__( 'Default', 'minimog' ) ] + $options;
			}

			$options = apply_filters( 'minimog/page_sidebar/supported_styles', $options );

			return $options;
		}
	}

	Minimog_Sidebar::instance()->initialize();
}
