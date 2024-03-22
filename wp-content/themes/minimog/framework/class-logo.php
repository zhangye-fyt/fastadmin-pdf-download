<?php
defined( 'ABSPATH' ) || exit;

class Minimog_Logo {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		/**
		 * @see Minimog_Redux::OPTION_NAME
		 */
		add_action( 'update_option_minimog_options', [ $this, 'regenerate_logo_dimensions' ], 20, 3 );
	}

	public function render( $args = array() ) {
		$defaults = [
			// Used when get specify skin. Eg: light, dark
			'skin' => '',
		];
		$args     = wp_parse_args( $args, $defaults );

		$has_both_skin    = false;
		$sticky_logo_skin = Minimog::setting( 'header_sticky_logo' );
		$header_logo_skin = Minimog_Global::instance()->get_header_skin();
		$display_logos    = [];

		if ( in_array( $args['skin'], [ 'dark', 'light' ] ) ) {
			$display_logos["{$args['skin']}"] = [];
		} else {
			if ( $sticky_logo_skin !== $header_logo_skin || is_page_template( 'templates/one-page-scroll.php' ) ) {
				$display_logos = array(
					'dark'  => [],
					'light' => [],
				);
			} else {
				$display_logos = 'dark' === $header_logo_skin ? array( 'dark' => [] ) : array( 'light' => [] );
			}
		}

		$lazy_load_enable = Minimog::setting( 'image_lazy_load_enable' ) && ! Minimog::elementor_is_edit_mode();
		$logo_width       = intval( Minimog::setting( 'logo_width' ) );
		$retina_width     = $logo_width * 2;
		$alt              = get_bloginfo( 'name', 'display' );

		if ( isset( $display_logos['dark'] ) ) {
			$logo_dark = Minimog::setting( 'logo_dark' );

			if ( ! empty( $logo_dark['id'] ) ) {
				$logo_dark_url = Minimog_Image::get_attachment_url_by_id( array(
					'id'   => $logo_dark['id'],
					'size' => "{$retina_width}x9999",
					'crop' => false,
				) );
			} elseif ( ! empty( $logo_dark['url'] ) ) {
				$logo_dark_url = $logo_dark['url'];
			}

			if ( ! empty( $logo_dark_url ) ) {
				$display_logos['dark'] = [
					'src'   => $logo_dark_url,
					'class' => 'logo dark-logo',
					'alt'   => $alt,
					'width' => $logo_width,
				];
			}
		}

		if ( isset( $display_logos['light'] ) ) {
			$logo_light = Minimog::setting( 'logo_light' );

			if ( ! empty( $logo_light['id'] ) ) {
				$logo_light_url = Minimog_Image::get_attachment_url_by_id( array(
					'id'   => $logo_light['id'],
					'size' => "{$retina_width}x9999",
					'crop' => false,
				) );
			} elseif ( ! empty( $logo_light['url'] ) ) {
				$logo_light_url = $logo_light['url'];
			}

			if ( ! empty( $logo_light_url ) ) {
				$display_logos['light'] = [
					'src'   => $logo_light_url,
					'class' => 'logo light-logo',
					'alt'   => $alt,
					'width' => $logo_width,
				];
			}
		}

		$logos_html = '';

		foreach ( $display_logos as $logo_skin => $display_logo ) {
			if ( empty( $display_logo['src'] ) ) {
				continue;
			}

			$transient_key   = "minimog_logo_dimensions_{$display_logo['src']}";
			$logo_dimensions = get_transient( $transient_key );

			if ( false !== $logo_dimensions ) {
				$attachment_width  = $logo_dimensions['width'];
				$attachment_height = $logo_dimensions['height'];
			} else {
				$logo_dimensions = $this->save_dimensions( $display_logo['src'] );

				if ( ! empty( $logo_dimensions ) ) {
					$attachment_width  = $logo_dimensions['width'];
					$attachment_height = $logo_dimensions['height'];
				} else {
					$attachment_width  = $display_logo['width'];
					$attachment_height = 42;
				}
			}

			$display_logo['height'] = $logo_width * ( $attachment_height / $attachment_width );

			$logo_class = $display_logo['class'];

			if ( $lazy_load_enable ) {
				$display_logo['class']    .= ' ll-image';
				$display_logo['data-src'] = $display_logo['src'];
				$display_logo['src']      = Minimog_Image::get_lazy_image_src( $display_logo['width'], $display_logo['height'] );
			}

			$logo_html = Minimog_Image::build_img_tag( $display_logo );

			if ( $lazy_load_enable ) {
				$logos_html .= Minimog_Image::build_lazy_img_tag( $logo_html, $attachment_width, $attachment_height, $logo_class );
			} else {
				$logos_html .= $logo_html;
			}
		}
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo '' . $logos_html; ?></a>
		<?php
	}

	public function save_dimensions( $logo_url ) {
		if ( empty( $logo_url ) ) {
			return false;
		}

		$transient_key = "minimog_logo_dimensions_{$logo_url}";
		$image_size    = Minimog_Image::get_image_size( $logo_url );

		if ( ! empty( $image_size ) ) {
			$dimensions = [
				'width'  => $image_size[0],
				'height' => $image_size[1],
			];

			set_transient( $transient_key, $dimensions, WEEK_IN_SECONDS );

			return $dimensions;
		}

		return false;
	}

	public function regenerate_logo_dimensions( $old_value, $value, $option ) {
		$settings   = [
			'logo_dark',
			'logo_light',
		];
		$logo_width = intval( Minimog::setting( 'logo_width' ) );

		foreach ( $settings as $setting ) {
			$logo     = Minimog::setting( $setting );
			$logo_url = '';

			if ( ! empty( $logo['id'] ) ) {
				$logo_url = Minimog_Image::get_attachment_url_by_id( array(
					'id'   => $logo['id'],
					'size' => "{$logo_width}x9999",
					'crop' => false,
				) );
			} elseif ( ! empty( $logo['url'] ) ) {
				$logo_url = $logo['url'];
			}

			$this->save_dimensions( $logo_url );
		}
	}

	/**
	 * Adds classes to the site branding.
	 *
	 * @param string $class
	 */
	public function output_wrap_class( $class = '' ) {
		$classes = array( 'branding' );

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters( 'minimog/branding/class', $classes, $class );

		echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
	}
}

Minimog_Logo::instance()->initialize();
