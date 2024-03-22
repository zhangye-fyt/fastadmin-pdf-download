<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     Redux Framework
 * @subpackage  Repeater
 * @author      Dovy Paukstys (dovy)
 * @author      Kevin Provance (kprovance)
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ReduxFramework_tm_heading' ) ) {

	/**
	 * Main ReduxFramework_css_layout class
	 *
	 * @since       1.0.0
	 */
	class ReduxFramework_tm_heading {

		public $parent;
		public $field;
		public $value;
		public $extension_dir   = '';
		public $extension_url   = '';

		/**
		 * Class Constructor. Defines the args for the extensions class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       array        $field  Field sections.
		 * @param       array|string $value  Values.
		 * @param       array        $parent Parent object.
		 *
		 * @return      void
		 */
		public function __construct( $field, $value, $parent ) {

			// Set required variables
			$this->parent = $parent;
			$this->field  = $field;
			$this->value  = $value;

			// Set extension dir & url
			if ( empty( $this->extension_dir ) ) {
				$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
				$this->extension_url = MINIMOG_REDUX_EXTENSION_URI . '/tm_heading/tm_heading/';
				// $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
			}

			$defaults = array(
				'indent'   => true,
				'style'    => '',
				'class'    => '',
				'title'    => '',
				'subtitle' => '',
				'collapse' => 'hide', // Accept value: '', 'show', 'hide'
			);
			
			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {
			$guid = uniqid();

			if ( true === $this->field['indent'] ) {
				$this->field['class'] .= ' redux-section-indent-start';
			}

			$add_class = '';
			if ( isset( $this->field['indent'] ) && true === $this->field['indent'] ) {
				$add_class = ' form-table-section-indented';
			} elseif ( ! isset( $this->field['indent'] ) || ( isset( $this->field['indent'] ) && false !== $this->field['indent'] ) ) {
				$add_class = ' hide';
			}

			$table_style = '';

			echo '<input type="hidden" id="' . esc_attr( $this->field['id'] ) . '-marker"></td></tr></table>';

			if ( isset( $this->field['indent'] ) && true === $this->field['indent'] ) {
				echo '<div class="indent-section-container">';
			}

			// Open collapse div
			if ( ! empty( $this->field['collapse'] ) ) {
				$class = 'redux-section-collapse-wrapper';

				if ( 'show' == $this->field['collapse'] ) {
					$class .= ' active';
					
				} elseif( 'hide' == $this->field['collapse'] ) {
					$table_style = 'display: none;';
				}

				echo '<div id="redux-section-collapse-wrapper-' . esc_attr( $this->field['id'] ) . '" class="' . esc_attr( $class ) . '" data-id="' . esc_attr( $this->field['id'] ) . '">';
			}

			echo '<div id="section-' . esc_attr( $this->field['id'] ) . '" class="redux-section-field redux-field ' . esc_attr( $this->field['style'] ) . ' ' . esc_attr( $this->field['class'] ) . ' ">';

			if ( ! empty( $this->field['title'] ) ) {
				echo '<h3>' . wp_kses_post( $this->field['title'] ) . '</h3>';
			}

			if ( ! empty( $this->field['subtitle'] ) ) {
				echo '<div class="redux-section-desc">' . wp_kses_post( $this->field['subtitle'] ) . '</div>';
			}

			echo '</div>';

			// Close collapse div
			if ( ! empty( $this->field['collapse'] ) ) {
				// Collapse icon
				echo '<div class="redux-section-collapse__icon"></div>';

				echo '</div>';
			}

			if ( isset( $this->field['indent'] ) && true === $this->field['indent'] ) {
				echo '</div>';
			}

			echo '<table id="section-table-' . esc_attr( $this->field['id'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '" class="form-table form-table-section no-border' . esc_attr( $add_class ) . '" style="' . esc_attr( $table_style ) . '"><tbody><tr><th></th><td id="' . esc_attr( $guid ) . '">';

			?>
			<script type="text/javascript">
				jQuery( document ).ready(
					function() {
						jQuery( '#<?php echo esc_attr( $this->field['id'] ); ?>-marker' ).parents( 'tr:first' )
						.css( {display: 'none'} )
						.prev( 'tr' )
						.css( 'border-bottom', 'none' );

						var group = jQuery( '#<?php echo esc_attr( $this->field['id'] ); ?>-marker' ).parents( '.redux-group-tab:first' );
						if ( !group.hasClass( 'sectionsChecked' ) ) {
							group.addClass( 'sectionsChecked' );
							var test = group.find( '.redux-section-indent-start h3' );
							jQuery.each(
								test, function( key, value ) {
									jQuery( value ).css( 'margin-top', '20px' )
								}
							);
							if ( group.find( 'h3:first' ).css( 'margin-top' ) === "20px" ) {
								group.find( 'h3:first' ).css( 'margin-top', '0' );
							}
						}
					}
				);
			</script>
			<?php
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() {

			$extension = ReduxFramework_extension_tm_heading::getInstance();

			// Set up min files for dev_mode = false.
			$min = '';// Redux_Functions::isMin();

			wp_enqueue_script( 'redux-field-tm_heading-js', apply_filters( "Redux/tm_heading/{$this->parent->args['opt_name']}/enqueue/redux-field-tm_heading-js", $this->extension_url . 'field_tm_heading' . $min . '.js' ), array(
				'jquery',
				'wp-color-picker', 
				'redux-js',
			), time(), true );


			wp_enqueue_style( 'redux-field-tm_heading-css', apply_filters( "Redux/tm_heading/{$this->parent->args['opt_name']}/enqueue/redux-field-tm_heading-css", $this->extension_url . 'field_tm_heading.css' ), array(), time(), 'all' );
		}

		/**
		 * Functions to pass data from the PHP to the JS at render time.
		 *
		 * @return array Params to be saved as a javascript object accessable to the UI.
		 * @since  Redux_Framework 3.1.5
		 */
		public function localize( $field, $value = array() ) {
			
		}
	}
}
