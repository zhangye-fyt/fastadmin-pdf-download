<?php
defined( 'ABSPATH' ) || exit;

/**
 * Abstract Widget Class
 *
 * @version  1.0
 * @extends  WP_Widget
 */
if ( ! class_exists( 'Minimog_Widget' ) ) {
	abstract class Minimog_Widget extends WP_Widget {

		/**
		 * CSS class.
		 *
		 * @var string
		 */
		public $widget_cssclass;

		/**
		 * Widget description.
		 *
		 * @var string
		 */
		public $widget_description;

		/**
		 * Widget ID.
		 *
		 * @var string
		 */
		public $widget_id;

		/**
		 * Widget name.
		 *
		 * @var string
		 */
		public $widget_name;

		/**
		 * Settings.
		 *
		 * @var array
		 */
		public $settings;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'                   => $this->widget_cssclass,
				'description'                 => $this->widget_description,
				'customize_selective_refresh' => true,
			);

			parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );
		}

		/**
		 * Output the html at the start of a widget.
		 *
		 * @param $args
		 *          [widget_wrapper_only]: Custom var used render wrapper tag only. Make it working properly on Ajax Filtering.
		 * @param $instance
		 */
		public function widget_start( $args, $instance ) {
			$custom_class         = '';
			$widget_content_style = '';

			if ( ! empty( $instance['enable_scrollable'] ) ) {
				$custom_class = ' widget-scrollable';
			}

			if ( ! empty( $instance['enable_collapsed'] ) ) {
				$custom_class         = ' collapsed';
				$widget_content_style .= 'display: none;';
			}

			if ( ! empty( $custom_class ) ) {
				$args['before_widget'] = preg_replace( '/class="/', 'class="' . $custom_class . ' ', $args['before_widget'], 1 );
			}

			if ( empty( $instance['widget_content_only'] ) ) {
				echo '' . $args['before_widget'];

				if ( empty( $args['widget_wrapper_only'] ) ) {
					echo '<input type="hidden" class="widget-instance" data-name="' . get_class( $this ) . '" data-instance="' . esc_attr( wp_json_encode( $instance ) ) . '"/>';

					if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) {
						echo '' . $args['before_title'] . $title . $args['after_title'];
					}

					printf( '<div class="widget-content" %1$s>', ! empty( $widget_content_style ) ? 'style="' . $widget_content_style . '"' : '' );
				}

			}

			if ( empty( $args['widget_wrapper_only'] ) ) {
				echo '<div class="widget-content-inner">';
			}
		}

		/**
		 * Output the html at the end of a widget.
		 *
		 * @param $args
		 * @param $instance
		 */
		public function widget_end( $args, $instance ) {
			if ( empty( $args['widget_wrapper_only'] ) ) {
				echo '</div>';  // .widget-content-inner.
			}

			if ( empty( $instance['widget_content_only'] ) ) {
				if ( empty( $args['widget_wrapper_only'] ) ) {
					echo '</div>'; // .widget-content
				}

				echo '' . $args['after_widget'];
			}
		}

		/**
		 * Updates a particular instance of a widget.
		 *
		 * @see    WP_Widget->update
		 *
		 * @param  array $new_instance
		 * @param  array $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = $old_instance;

			if ( empty( $this->settings ) ) {
				return $instance;
			}

			// Loop settings and get values to save.
			foreach ( $this->settings as $key => $setting ) {
				if ( ! isset( $setting['type'] ) ) {
					continue;
				}

				// Format the value based on settings type.
				switch ( $setting['type'] ) {
					case 'number' :
						$instance[ $key ] = absint( $new_instance[ $key ] );

						if ( isset( $setting['min'] ) && '' !== $setting['min'] ) {
							$instance[ $key ] = max( $instance[ $key ], $setting['min'] );
						}

						if ( isset( $setting['max'] ) && '' !== $setting['max'] ) {
							$instance[ $key ] = min( $instance[ $key ], $setting['max'] );
						}
						break;
					case 'textarea' :
						$instance[ $key ] = wp_kses( trim( wp_unslash( $new_instance[ $key ] ) ), wp_kses_allowed_html( 'post' ) );
						break;
					case 'checkbox' :
						$instance[ $key ] = empty( $new_instance[ $key ] ) ? 0 : 1;
						break;
					default:
						$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
						break;
				}

				/**
				 * Sanitize the value of a setting.
				 */
				$instance[ $key ] = apply_filters( 'insight_widget_settings_sanitize_option', $instance[ $key ], $new_instance, $key, $setting );
			}

			return $instance;
		}

		/**
		 * Outputs the settings update form.
		 *
		 * @see   WP_Widget->form
		 *
		 * @param array $instance
		 *
		 * @return null
		 */
		public function form( $instance ) {
			$this->set_form_settings();

			if ( empty( $this->settings ) ) {
				return;
			}
			?>
			<div class="minimog-widget-form-content">
				<?php
				foreach ( $this->settings as $key => $setting ) {
					$control_class           = [ "minimog-widget-control widget-control--$key" ];
					$value                   = isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];
					$condition               = isset( $setting['condition'] ) ? $setting['condition'] : '';
					$wrap_class              = [
						'minimog-wp-widget-control-wrap',
					];
					$wrap_attrs              = [];

					if ( ! empty( $condition ) ) {
						$wrap_attrs['data-condition'] = $setting['condition'];

						$wrap_class[] = 'control-has-condition';
					}

					if ( ! empty( $setting['class'] ) ) {
						$control_class[] = $setting['class'];
					}

					if ( $this->has_controls_depend( $key ) ) {
						$control_class[] = 'widget-control--has-depend';
					}

					ob_start();
					switch ( $setting['type'] ) {
						case 'text' :
							$control_class[] = 'widefat';
							?>
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" class="widget-control--label"><?php echo esc_html( $setting['label'] ); ?></label>
							<input class="<?php echo esc_attr( implode( ' ', $control_class ) ); ?>"
							       id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text"
							       value="<?php echo esc_attr( $value ); ?>"/>
							<?php $this->print_field_desc( $setting ); ?>
							<?php
							break;

						case 'number' :
							$control_class[] = 'widefat';
							?>
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" class="widget-control--label"><?php echo esc_html( $setting['label'] ); ?></label>
							<input class="<?php echo esc_attr( implode( ' ', $control_class ) ); ?>"
							       id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number"
							       step="<?php echo esc_attr( $setting['step'] ); ?>"
							       min="<?php echo esc_attr( $setting['min'] ); ?>"
							       max="<?php echo esc_attr( $setting['max'] ); ?>"
							       value="<?php echo esc_attr( $value ); ?>"/>
							<?php $this->print_field_desc( $setting ); ?>
							<?php
							break;

						case 'select' :
							$control_class[] = 'widefat';
							?>
							<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" class="widget-control--label"><?php echo esc_html( $setting['label'] ); ?></label>
							<select class="<?php echo esc_attr( implode( ' ', $control_class ) ); ?>"
							        id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							        name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>">
								<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
									<option value="<?php echo esc_attr( $option_key ); ?>" <?php selected( $option_key, $value ); ?>><?php echo esc_html( $option_value ); ?></option>
								<?php endforeach; ?>
							</select>
							<?php $this->print_field_desc( $setting ); ?>
							<?php
							break;

						case 'textarea' :
							$control_class[] = 'widefat';
							?>
							<label
								for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" class="widget-control--label"><?php echo esc_html( $setting['label'] ); ?></label>
							<textarea class="<?php echo esc_attr( implode( ' ', $control_class ) ); ?>"
							          id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
							          name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
							          cols="20"
							          rows="3"><?php echo esc_textarea( $value ); ?></textarea>
							<?php $this->print_field_desc( $setting ); ?>
							<?php
							break;

						case 'checkbox' :
							$control_class[] = 'checkbox';
							?>
							<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>">
								<input type="checkbox"
								       class="<?php echo esc_attr( implode( ' ', $control_class ) ); ?>"
								       id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"
								       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
								       value="1" <?php checked( $value, 1 ); ?> />
								<?php echo esc_html( $setting['label'] ); ?>
							</label>
							<?php $this->print_field_desc( $setting ); ?>
							<?php
							break;

						case 'image':
							$wrap_class[] = 'kungfu-attach-wrap';
							$control_class[] = 'kungfu-media';
							?>
							<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" class="widget-control--label"><?php echo esc_html( $setting['label'] ); ?></label>
							<div class="kungfu-media-image">
								<?php if ( $value ) : ?>
									<?php Minimog_Image::the_attachment_by_id( [
										'id'   => $value,
										'size' => '150x150',
									] ); ?>
								<?php endif; ?>
							</div>
							<input type="hidden" class="<?php echo esc_attr( implode( ' ', $control_class ) ); ?>"
							       name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"
							       value="<?php echo esc_attr( $value ); ?>"/>
							<a class="kungfu-media-open kungfu-button success">
								<i class="fa fa-upload"></i><?php esc_html_e( 'Upload', 'minimog' ); ?>
							</a>
							<a class="kungfu-media-remove kungfu-button danger"
								<?php if ( empty( $value ) ) : ?>
									style="display:none"
								<?php endif; ?>
							><i class="fa fa-trash-o"></i><?php esc_html_e( 'Remove', 'minimog' ); ?></a>
							<?php
							break;

						// Default: run an action.
						default :
							do_action( 'minimog_widget_field_' . $setting['type'], $key, $value, $setting, $instance );
							break;
					}
					$control_html = ob_get_clean();

					$wrap_attrs['class'] = $wrap_class;

					printf( '<div %1$s>%2$s</div>',
						Minimog_Helper::convert_array_html_attributes_to_string( $wrap_attrs ),
						$control_html
					);
				}
				?>
			</div>
			<?php
		}

		/**
		 * Check whether if has any controls depend on given control.
		 *
		 * @param $given_control_id
		 *
		 * @return bool
		 */
		public function has_controls_depend( $given_control_id ) {
			foreach ( $this->settings as $control_id => $control_settings ) {
				if ( empty( $control_settings['condition'] ) ) {
					continue;
				}

				foreach ( $control_settings['condition'] as $depend_control_id => $depend_control_condition ) {
					if ( $depend_control_id === $given_control_id ) {
						return true;
					}
				}
			}

			return false;
		}

		public function print_field_desc( $setting ) {
			if ( empty( $setting['desc'] ) ) {
				return;
			}
			?>
			<small><?php echo $setting['desc']; ?></small>
			<?php
		}

		/**
		 * Placeholder function for children update settings before form render.
		 * For heave query should do in this function to improvement performance.
		 * For eg: Terms or Posts select options.
		 */
		public function set_form_settings() {
		}

		public function get_value( $instance, $setting_name ) {
			if ( isset( $instance["{$setting_name}"] ) ) {
				return $instance["{$setting_name}"];
			} elseif ( isset( $this->settings["{$setting_name}"]['std'] ) ) {
				return $this->settings["{$setting_name}"]['std'];
			}

			return '';
		}
	}
}
