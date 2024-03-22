<?php
defined( 'ABSPATH' ) || exit;

class Minimog_Redux_Font_Loader {

	protected static $instance = null;

	public static $standard_fonts = array(
		'Arial, Helvetica, sans-serif'                             => 'Arial, Helvetica, sans-serif',
		'\'Arial Black\', Gadget, sans-serif'                      => '\'Arial Black\', Gadget, sans-serif',
		'\'Bookman Old Style\', serif'                             => '\'Bookman Old Style\', serif',
		'\'Comic Sans MS\', cursive'                               => '\'Comic Sans MS\', cursive',
		'Courier, monospace'                                       => 'Courier, monospace',
		'Garamond, serif'                                          => 'Garamond, serif',
		'Georgia, serif'                                           => 'Georgia, serif',
		'Impact, Charcoal, sans-serif'                             => 'Impact, Charcoal, sans-serif',
		'\'Lucida Console\', Monaco, monospace'                    => '\'Lucida Console\', Monaco, monospace',
		'\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif'   => '\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif',
		'\'MS Sans Serif\', Geneva, sans-serif'                    => '\'MS Sans Serif\', Geneva, sans-serif',
		'\'MS Serif\', \'New York\', sans-serif'                   => '\'MS Serif\', \'New York\', sans-serif',
		'\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif' => '\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif',
		'Tahoma,Geneva, sans-serif'                                => 'Tahoma, Geneva, sans-serif',
		'\'Times New Roman\', Times,serif'                         => '\'Times New Roman\', Times, serif',
		'\'Trebuchet MS\', Helvetica, sans-serif'                  => '\'Trebuchet MS\', Helvetica, sans-serif',
		'Verdana, Geneva, sans-serif'                              => 'Verdana, Geneva, sans-serif',
	);

	public static $custom_fonts = array(
		'Butler'              => 'Butler, sans-serif',
		'ClashGrotesk'        => 'ClashGrotesk, sans-serif',
		'ClashDisplay'        => 'ClashDisplay, sans-serif',
		'Gilroy'              => 'Gilroy, sans-serif',
		'Gordita'             => 'Gordita, sans-serif',
		'Futura'              => 'Futura, sans-serif',
		'NewYork'             => 'NewYork, sans-serif',
		'WorthbitesRough'     => 'WorthbitesRough, sans-serif',
		'Jellee'              => 'Jellee, sans-serif',
		'Sentient'            => 'Sentient, sans-serif',
		'GeneralSans'         => 'GeneralSans, sans-serif',
		'Satoshi'             => 'Satoshi, sans-serif',
		'GeezaPro'            => 'GeezaPro, sans-serif',
		'BespokeSerif'        => 'BespokeSerif, sans-serif',
		'Open Sans Condensed' => 'Open Sans Condensed, sans-serif',
	);

	protected static $typography_fields = array(
		'typography_body'                    => [
			'all-styles'  => true,
			'all-subsets' => true,
		],
		'typography_heading'                 => [],
		'typography_heading2'                => [],
		'button_typography'                  => [
			'inherit' => 'button_typography_inherit',
		],
		'form_typography'                    => [
			'inherit' => 'form_typography_inherit',
		],
		'top_bar_style_01_text_typography'   => [],
		'header_style_navigation_typography' => [],
	);

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'redux/minimog_options/field/typography/custom_fonts', [ $this, 'add_custom_fonts_for_redux' ] );

		// Load google fonts.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_google_fonts' ] );

		// Load custom fonts.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_custom_fonts' ] );
	}

	public function enqueue_google_fonts() {
		$main_typography = '';
		$fonts           = [];
		$custom_fonts    = $this->get_custom_fonts();

		foreach ( self::$typography_fields as $field_id => $field_settings ) {
			$value = Minimog::setting( $field_id );

			if ( empty( $value['font-family'] ) ) {
				continue;
			}

			$current_font = $value['font-family'];

			// Do nothing if is not google font.
			if ( isset( $custom_fonts[ $current_font ] ) ||
			     isset( self::$standard_fonts[ $current_font ] )
			) {
				continue;
			}

			if ( 'typography_body' === $field_id ) {
				$main_typography = $value['font-family'];
			}

			// Skip if same body font.
			if ( 'typography_body' !== $field_id ) {
				if ( ( $main_typography === $current_font || 'inherit' === $current_font || '' === $current_font ) ) {
					continue;
				}

				if ( ! empty( $field_settings['inherit'] ) && '1' === Minimog::setting( $field_settings['inherit'] ) ) {
					continue;
				}
			}

			if ( ! isset( $fonts[ $current_font ] ) ) {
				$fonts[ $current_font ] = [
					'font-weight' => [],
					'subset'      => [],
				];
			}

			if ( ! empty( $field_settings['all-styles'] ) ) {
				$fonts[ $current_font ]['font-weight'] = [ 100, 200, 300, 400, 500, 600, 700, 800, 900 ];
			}

			if ( ! empty( $field_settings['all-subsets'] ) ) {
				$fonts[ $current_font ]['subset'] = [ 'cyrillic', 'latin', 'latin-ext' ];
			}


			if ( ! empty( $value['font-weight'] ) ) {
				$fonts[ $current_font ]['font-weight'] = array_unique( array_merge( $fonts[ $current_font ]['font-weight'], [ $value['font-weight'] ] ) );
			}

			if ( ! empty( $value['subset'] ) ) {
				$fonts[ $current_font ]['subset'] = array_unique( array_merge( $fonts[ $current_font ]['subset'], [ $value['subset'] ] ) );
			}
		}

		if ( ! empty( $fonts ) ) {
			foreach ( $fonts as $font_family => $font_settings ) {
				/**
				 * @see https://developers.google.com/fonts/docs/css2
				 */
				$url = '//fonts.googleapis.com/css2?family=' . str_replace( ' ', '+', $font_family );

				if ( ! empty( $font_settings['font-weight'] ) ) {
					$url .= ':ital,wght@';

					$normal_styles = [];
					$italic_styles = [];

					// Need sort right order to make google load properly.
					asort( $font_settings['font-weight'] );

					foreach ( $font_settings['font-weight'] as $font_weight ) {
						$normal_styles[] = "0,{$font_weight}";
						$italic_styles[] = "1,{$font_weight}";
					}

					$url .= implode( ';', array_merge( $normal_styles, $italic_styles ) );
				}

				$url .= '&display=swap';

				$handle = 'google-font-' . strtolower( str_replace( ' ', '-', $font_family ) );

				wp_enqueue_style( $handle, $url, null, null );
				add_filter( 'wp_resource_hints', [ $this, 'google_fonts_preconnect' ], 10, 2 );
			}
		}
	}

	/**
	 * Add Google Fonts preconnect link.
	 *
	 * @param array  $urls              HTML to be added.
	 * @param string $relationship_type Handle name.
	 *
	 * @return array
	 */
	public function google_fonts_preconnect( $urls, $relationship_type ) {
		if ( 'preconnect' !== $relationship_type ) {
			return $urls;
		}
		$urls[] = array(
			'rel'  => 'preconnect',
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);

		return $urls;
	}

	public function get_custom_fonts() {
		$additional_fonts = apply_filters( 'minimog/fonts/additional_fonts', array() );

		return array_merge( self::$custom_fonts, $additional_fonts );
	}

	public function add_custom_fonts_for_redux( $fonts ) {
		$custom_fonts = $this->get_custom_fonts();

		$custom_fonts = array(
			'Custom Fonts' => $custom_fonts,
		);

		return array_merge( $fonts, $custom_fonts );
	}

	public function enqueue_custom_fonts() {
		$font_families = [];

		foreach ( self::$typography_fields as $field_id => $field_settings ) {
			$font = Minimog::setting( $field_id );

			if ( is_array( $font ) && ! empty( $font['font-family'] ) ) {
				$font_families[] = $font['font-family'];
			}
		}

		$font_families = array_unique( $font_families );

		foreach ( $font_families as $font_family ) {
			if ( strpos( $font_family, 'Butler' ) !== false ) {
				wp_enqueue_style( 'font-butler', MINIMOG_THEME_URI . '/assets/fonts/butler/font-butler.min.css', null, null );
			} elseif ( strpos( $font_family, 'ClashGrotesk' ) !== false ) {
				wp_enqueue_style( 'font-clash-grotesk', MINIMOG_THEME_URI . '/assets/fonts/clash-grotesk/font-clash-grotesk.min.css', null, null );
			} elseif ( strpos( $font_family, 'ClashDisplay' ) !== false ) {
				wp_enqueue_style( 'font-clash-display', MINIMOG_THEME_URI . '/assets/fonts/clash-display/font-clash-display.min.css', null, null );
			} elseif ( strpos( $font_family, 'Gilroy' ) !== false ) {
				wp_enqueue_style( 'font-gilroy', MINIMOG_THEME_URI . '/assets/fonts/gilroy/font-gilroy.min.css', null, null );
			} elseif ( strpos( $font_family, 'Gordita' ) !== false ) {
				wp_enqueue_style( 'font-gordita', MINIMOG_THEME_URI . '/assets/fonts/gordita/font-gordita.min.css', null, null );
			} elseif ( strpos( $font_family, 'Futura' ) !== false ) {
				wp_enqueue_style( 'font-futura', MINIMOG_THEME_URI . '/assets/fonts/futura/font-futura.min.css', null, null );
			} elseif ( strpos( $font_family, 'NewYork' ) !== false ) {
				wp_enqueue_style( 'font-new-york', MINIMOG_THEME_URI . '/assets/fonts/new-york/font-new-york.min.css', null, null );
			} elseif ( strpos( $font_family, 'WorthbitesRough' ) !== false ) {
				wp_enqueue_style( 'font-worthbites-rough', MINIMOG_THEME_URI . '/assets/fonts/worthbites-rough/font-worthbites-rough.min.css', null, null );
			} elseif ( strpos( $font_family, 'Jellee' ) !== false ) {
				wp_enqueue_style( 'font-jellee', MINIMOG_THEME_URI . '/assets/fonts/jellee/font-jellee.min.css', null, null );
			} elseif ( strpos( $font_family, 'GeneralSans' ) !== false ) {
				wp_enqueue_style( 'font-general-sans', MINIMOG_THEME_URI . '/assets/fonts/general-sans/font-general-sans.min.css', null, null );
			} elseif ( strpos( $font_family, 'Sentient' ) !== false ) {
				wp_enqueue_style( 'font-sentient', MINIMOG_THEME_URI . '/assets/fonts/sentient/font-sentient.min.css', null, null );
			} elseif ( strpos( $font_family, 'Satoshi' ) !== false ) {
				wp_enqueue_style( 'font-satoshi', MINIMOG_THEME_URI . '/assets/fonts/satoshi/font-satoshi.min.css', null, null );
			} elseif ( strpos( $font_family, 'GeezaPro' ) !== false ) {
				wp_enqueue_style( 'font-geeza-pro', MINIMOG_THEME_URI . '/assets/fonts/geeza-pro/font-geeza-pro.min.css', null, null );
			} elseif ( strpos( $font_family, 'BespokeSerif' ) !== false ) {
				wp_enqueue_style( 'font-bespoke-serif', MINIMOG_THEME_URI . '/assets/fonts/bespoke-serif/font-bespoke-serif.min.css', null, null );
			} elseif ( strpos( $font_family, 'Open Sans Condensed' ) !== false ) {
				wp_enqueue_style( 'font-open-sans-condensed', MINIMOG_THEME_URI . '/assets/fonts/open-sans-condensed/font-open-sans-condensed.min.css', null, null );
			} else {
				do_action( 'minimog/custom_fonts/enqueue', $font_family ); // hook to custom do enqueue fonts.
			}
		}
	}
}

Minimog_Redux_Font_Loader::instance()->initialize();
