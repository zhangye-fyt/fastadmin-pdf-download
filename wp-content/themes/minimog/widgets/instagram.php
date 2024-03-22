<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Instagram' ) ) {
	class Minimog_WP_Widget_Instagram extends Minimog_Widget {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-instagram';
			$this->widget_cssclass    = 'minimog-wp-widget-instagram';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Instagram', 'minimog' ) );
			$this->widget_description = esc_html__( 'Display responsive Instagram feeds.', 'minimog' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'limit'        => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 40,
					'std'   => 6,
					'label' => esc_html__( 'Limit', 'minimog' ),
				),
				'columns'      => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 9,
					'std'   => 3,
					'label' => esc_html__( 'Columns', 'minimog' ),
				),
				'gutter'       => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 100,
					'std'   => 6,
					'label' => esc_html__( 'Gutter', 'minimog' ),
				),
				'image_shape'  => array(
					'type'    => 'select',
					'std'     => 'cropped',
					'label'   => esc_html__( 'Image Size', 'minimog' ),
					'options' => [
						'cropped'  => esc_html__( 'Square', 'minimog' ),
						'original' => esc_html__( 'Original', 'minimog' ),
					],
				),
				'hover_effect' => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Hover Effect', 'minimog' ),
					'options' => [
						''         => esc_html__( 'None', 'minimog' ),
						'zoom-in'  => esc_html__( 'Zoom In', 'minimog' ),
						'zoom-out' => esc_html__( 'Zoom Out', 'minimog' ),
						'move-up'  => esc_html__( 'Move Up', 'minimog' ),
					],
				),
				'show_button'  => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Button', 'minimog' ),
				),
				'button_text'  => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Button Text', 'minimog' ),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			$limit        = isset( $instance['limit'] ) ? $instance['limit'] : $this->settings['limit']['std'];
			$columns      = isset( $instance['columns'] ) ? $instance['columns'] : $this->settings['columns']['std'];
			$gutter       = isset( $instance['gutter'] ) ? $instance['gutter'] : $this->settings['gutter']['std'];
			$hover_effect = isset( $instance['hover_effect'] ) ? $instance['hover_effect'] : $this->settings['hover_effect']['std'];
			$image_shape  = isset( $instance['image_shape'] ) ? $instance['image_shape'] : $this->settings['image_shape']['std'];
			$show_button  = isset( $instance['show_button'] ) && $instance['show_button'] === 1 ? 'true' : 'false';
			$button_text  = isset( $instance['button_text'] ) ? $instance['button_text'] : $this->settings['button_text']['std'];
			$images       = Minimog_Instagram::instance()->get_images( $limit );
			$user         = Minimog_Instagram::instance()->get_user();

			$main_classes = [
				'minimog-instagram',
				'minimog-instagram-widget',
				'minimog-grid-wrapper',
				'minimog-instagram--' . $image_shape,
			];

			if ( $hover_effect ) {
				$main_classes[] = 'minimog-animation-' . $hover_effect;
			}

			$item_classes = [
				'minimog-instagram__item',
				'minimog-box',
				'grid-item',
			];

			$grid_options = [
				'type'    => 'grid',
				'columns' => $columns,
				'gutter'  => $gutter,
			];

			$this->widget_start( $args, $instance );

			if ( is_wp_error( $images ) ) {
				echo '' . $images->get_error_message();
			} elseif ( is_array( $images ) ) {
				$medias = array_slice( $images, 0, $limit );
				?>
				<div class="<?php echo esc_attr( implode( ' ', $main_classes ) ) ?>"
				     data-grid="<?php echo esc_attr( wp_json_encode( $grid_options ) ) ?>"
					<?php echo Minimog_Helper::grid_args_to_html_attr( $grid_options ); ?>>
					<div class="minimog-grid lazy-grid">
						<div class="grid-sizer"></div>
						<?php foreach ( $medias as $media ) : ?>
							<div class="<?php echo esc_attr( implode( ' ', $item_classes ) ) ?>">
								<?php echo Minimog_Instagram::instance()->get_image( $media ); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php
				if ( 'true' === $show_button ) {
					$button_args = [
						'link'          => [
							'url' => 'https://www.instagram.com/' . $user['username'],
						],
						'style'         => 'text',
						'text'          => ! empty( $button_text ) ? $button_text : '@' . esc_html( $user['username'] ),
						'wrapper_class' => 'minimog-instagram-widget-button',
						'icon'          => 'fab fa-instagram',
						'icon_align'    => 'left',
					];

					Minimog_Templates::render_button( $button_args );
				}
			}

			$this->widget_end( $args, $instance );
		}
	}
}
