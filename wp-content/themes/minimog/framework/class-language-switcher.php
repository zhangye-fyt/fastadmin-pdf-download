<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Language_Switcher' ) ) {
	class Minimog_Language_Switcher {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			if ( defined( 'ICL_SITEPRESS_VERSION' ) ) { // By WPML.
				add_action( 'minimog/top_bar/language_switcher', [ $this, 'output_switcher_by_wpml' ] );
				add_action( 'minimog/header/language_switcher', [ $this, 'output_switcher_by_wpml' ] );
				add_action( 'minimog/mobile_menu/language_switcher', [ $this, 'output_switcher_by_wpml' ] );

				add_filter( 'minimog/top_bar/language_switcher/wrap_class', [ $this, 'add_css_class_by_wpml' ] );
				add_filter( 'minimog/header/language_switcher/wrap_class', [ $this, 'add_css_class_by_wpml' ] );
			} elseif ( class_exists( 'TRP_Translate_Press' ) ) { // By TranslatePress.
				add_action( 'minimog/top_bar/language_switcher', [ $this, 'output_switcher_by_translate_press' ] );
				add_action( 'minimog/header/language_switcher', [ $this, 'output_switcher_by_translate_press' ] );
				add_action( 'minimog/mobile_menu/language_switcher', [ $this, 'output_switcher_by_translate_press' ] );

				add_filter( 'minimog/top_bar/language_switcher/wrap_class', [ $this, 'add_css_class_by_translate_press' ] );
				add_filter( 'minimog/header/language_switcher/wrap_class', [ $this, 'add_css_class_by_translate_press' ] );
			} elseif ( function_exists( 'pll_the_languages' ) ) { // By Polylang.
				add_action( 'minimog/top_bar/language_switcher', [ $this, 'output_switcher_by_polylang' ] );
				add_action( 'minimog/header/language_switcher', [ $this, 'output_switcher_by_polylang' ] );
				add_action( 'minimog/mobile_menu/language_switcher', [ $this, 'output_switcher_by_polylang' ] );

				add_filter( 'minimog/top_bar/language_switcher/wrap_class', [ $this, 'add_css_class_by_polylang' ] );
				add_filter( 'minimog/header/language_switcher/wrap_class', [ $this, 'add_css_class_by_polylang' ] );
			}
		}

		public function output_switcher_by_wpml() {
			do_action( 'wpml_add_language_selector' );
		}

		public function output_switcher_by_polylang() {
			$pll_args = apply_filters( 'minimog/polylang/language_switcher/args', array(
				'dropdown'   => 0,
				'show_flags' => 1,
				'raw'        => 1,
			) );

			$languages    = pll_the_languages( $pll_args );
			$current_lang = false;

			if ( is_array( $languages ) && ! empty( $languages ) ) { // Custom.
				$items_html = '';
				foreach ( $languages as $code => $language ) {
					if ( ! empty( $language['current_lang'] ) ) {
						$current_lang = $language;
						continue;
					} else {
						$items_html .= $this->get_item_html_for_polylang( $language, $pll_args, '' );
					}
				}

				$current_lang['classes'][] = 'menu-item-has-children';
				?>
				<ul class="minimog-menu language-switcher-menu">
					<?php echo $this->get_item_html_for_polylang( $current_lang, $pll_args, $items_html ); ?>
				</ul>
				<?php
			}
		}

		public function get_item_html_for_polylang( $language, $setting, $content = '' ) {
			$flag = ! empty( $setting['show_flags'] ) ? $language['flag'] : '';

			$link_class = 'lang-item-link ';
			$link_class .= ! empty( $language['link_classes'] ) ? implode( ' ', $language['link_classes'] ) : '';

			$link = sprintf( '<a lang="%1$s" hreflang="%1$s" href="%2$s" class="%3$s">%4$s <span class="lang-item-name">%5$s</span></a>', $language['locale'], $language['url'], $link_class, $flag, $language['name'] );

			if ( ! empty( $language['current_lang'] ) && ! empty( $content ) ) {
				$content = '<ul class="sub-menu">' . $content . '</ul>';
			}

			$link = sprintf( '<li class="%1$s">%2$s %3$s</li>', esc_attr( implode( ' ', $language['classes'] ) ), $link, $content );

			return $link;
		}

		public function output_switcher_by_translate_press() {
			echo do_shortcode( '[language-switcher]' );
		}

		public function add_css_class_by_wpml( $classes ) {
			$classes[] = 'wpml';

			return $classes;
		}

		public function add_css_class_by_translate_press( $classes ) {
			$classes[] = 'translate-press';

			return $classes;
		}

		public function add_css_class_by_polylang( $classes ) {
			$classes[] = 'polylang';

			return $classes;
		}
	}

	Minimog_Language_Switcher::instance()->initialize();
}
