<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Header' ) ) {

	class Minimog_Header {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {

		}

		/**
		 * @return array List header types include id & name.
		 */
		public function get_type() {
			return array(
				'01' => 'Style 01 - Center Logo', // Center Logo.
				'02' => 'Style 02 - Bottom Nav', // Bottom Nav - Right Search Form
				'03' => 'Style 03 - Center Nav', // Center Nav.
				'04' => 'Style 04 - Split Nav', // Split Nav.
				'05' => 'Style 05 - Bottom Nav ver.2', // Bottom Nav - Left Search Form
				'06' => 'Style 06 - Bottom Nav ver.3', // Bottom Nav - Center Search Form
				'07' => 'Style 07 - Left Nav & Logo', // Left Nav - Left Logo
				'08' => 'Style 08 - Bottom Nav ver.4', // Bottom Fill Nav - Center Search Form
				'09' => 'Style 09 - Bottom Nav ver.5', // Bottom Nav - Left Search Form In Nav
				'10' => 'Style 10 - Bottom Nav ver.6', // Bottom Nav - Center Logo + Center Search Form
			);
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_list( $default_option = false, $default_text = '' ) {
			$headers = array(
				'none' => esc_html__( 'Hide', 'minimog' ),
			);

			$headers += $this->get_type();

			if ( $default_option === true ) {
				if ( $default_text === '' ) {
					$default_text = esc_html__( 'Default', 'minimog' );
				}

				$headers = array( '' => $default_text ) + $headers;
			}

			return $headers;
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_overlay_list( $default_option = true, $default_text = '' ) {
			$overlays = array(
				'0' => esc_html__( 'No', 'minimog' ),
				'1' => esc_html__( 'Yes', 'minimog' ),
			);

			if ( $default_option === true ) {
				if ( '' === $default_text ) {
					$default_text = esc_html__( 'Use Global', 'minimog' );
				}

				$overlays = array( '' => $default_text ) + $overlays;
			}

			return $overlays;
		}

		/**
		 * @param bool   $default_option Show or hide default select option.
		 * @param string $default_text   Custom text for default option.
		 *
		 * @return array A list of options for select field.
		 */
		public function get_skin_list( $default_option = true, $default_text = '' ) {
			$skins = array(
				'dark'  => esc_html__( 'Dark', 'minimog' ),
				'light' => esc_html__( 'Light', 'minimog' ),
			);

			if ( $default_option === true ) {
				if ( '' === $default_text ) {
					$default_text = esc_html__( 'Use Global', 'minimog' );
				}

				$skins = array( '' => $default_text ) + $skins;
			}

			return $skins;
		}

		public function get_background_list() {
			return [
				''     => esc_html__( 'Default', 'minimog' ),
				'none' => esc_html__( 'Disabled', 'minimog' ),
			];
		}

		public function get_shadow_list() {
			return [
				''     => esc_html__( 'Default', 'minimog' ),
				'none' => esc_html__( 'Disabled', 'minimog' ),
			];
		}

		/**
		 * Get list of button style option for customizer.
		 *
		 * @return array
		 */
		public function get_button_style() {
			return array(
				'flat'   => esc_attr__( 'Flat', 'minimog' ),
				'border' => esc_attr__( 'Border', 'minimog' ),
			);
		}

		/**
		 * Add classes to the header.
		 *
		 * @var string $class Custom class.
		 */
		public function get_wrapper_class( $class = '' ) {
			$classes = array( 'page-header' );

			$header_type    = Minimog_Global::instance()->get_header_type();
			$header_overlay = Minimog_Global::instance()->get_header_overlay();
			$header_skin    = Minimog_Global::instance()->get_header_skin();

			$classes[] = "header-{$header_type}";

			if ( $header_overlay === '1' ) {
				$classes[] = 'header-layout-fixed';
			}

			$nav_hover_style = Minimog::setting( 'header_navigation_item_hover_style' );
			if ( ! empty( $nav_hover_style ) ) {
				$classes[] = 'nav-links-hover-style-' . $nav_hover_style;

				switch ( $nav_hover_style ) {
					case 'thin-line':
						$classes[] = 'nav-links-hover-style-line';
						break;
				}
			}

			$classes[] = "header-{$header_skin}";

			$_sticky_logo = Minimog::setting( 'header_sticky_logo' );
			$classes[]    = " header-sticky-$_sticky_logo-logo";

			$icon_style = Minimog::setting( 'header_icons_style' );
			$classes[]  = " header-$icon_style";

			$icon_badge_size = Minimog::setting( 'header_icons_badge_size' );
			$classes[]       = " header-icon-badge-$icon_badge_size";

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = apply_filters( 'minimog/header/class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function get_container_class( $class = '' ) {
			$classes = [];

			$type          = Minimog_Global::instance()->get_header_type();
			$content_width = Minimog::setting( "header_style_{$type}_content_width" );

			if ( is_singular() ) {
				$custom = Minimog_Helper::get_post_meta( 'header_content_width', '' );

				$content_width = ! empty( $custom ) ? $custom : $content_width;
			}

			$classes[] = Minimog_Site_Layout::instance()->get_container_class( $content_width );

			if ( ! empty( $class ) ) {
				if ( ! is_array( $class ) ) {
					$class = preg_split( '#\s+#', $class );
				}
				$classes = array_merge( $classes, $class );
			} else {
				// Ensure that we always coerce class to being an array.
				$class = array();
			}

			$classes = apply_filters( 'minimog/header/container_class', $classes, $class );

			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}

		public function is_active_above() {
			$header_type = Minimog_Global::instance()->get_header_type();
			$is_enable   = Minimog::setting( "header_style_{$header_type}_header_above_enable" );

			if ( is_singular() ) {
				$page_enable = Minimog_Helper::get_post_meta( 'header_above', '' );

				if ( '' !== $page_enable ) {
					$is_enable = $page_enable;
				}
			}

			return '1' === $is_enable ? true : false;
		}

		public function print_language_switcher() {
			$header_type = Minimog_Global::instance()->get_header_type();
			$enabled     = Minimog::setting( "header_style_{$header_type}_language_switcher_enable" );

			if ( '1' !== $enabled ) {
				return;
			}

			minimog_load_template( 'header/components/language-switcher' );
		}

		public function print_social_networks( $args = array() ) {
			$header_type   = Minimog_Global::instance()->get_header_type();
			$social_enable = Minimog::setting( "header_style_{$header_type}_social_networks_enable" );

			if ( '1' !== $social_enable ) {
				return;
			}

			$defaults = array(
				'style' => 'icons',
			);

			$args       = wp_parse_args( $args, $defaults );
			$el_classes = 'header-social-networks';

			if ( ! empty( $args['style'] ) ) {
				$el_classes .= " style-{$args['style']}";
			}
			?>
			<div class="<?php echo esc_attr( $el_classes ); ?>">
				<div class="inner">
					<?php
					$defaults = array(
						'tooltip_enable' => false,
					);

					if ( 'light' === Minimog_Global::instance()->get_header_skin() ) {
						$defaults['tooltip_skin'] = 'white';
					}

					$args = wp_parse_args( $args, $defaults );

					Minimog_Templates::social_icons( $args );
					?>
				</div>
			</div>
			<?php
		}

		public function print_widgets() {
			$header_type = Minimog_Global::instance()->get_header_type();

			$enabled = Minimog::setting( "header_style_{$header_type}_widgets_enable" );
			if ( '1' === $enabled ) {
				?>
				<div class="header-widgets">
					<?php Minimog_Sidebar::instance()->generated_sidebar( 'header_widgets' ); ?>
				</div>
				<?php
			}
		}

		public function print_search( $args = array() ) {
			$header_type = Minimog_Global::instance()->get_header_type();
			$search_type = Minimog::setting( "header_style_{$header_type}_search_enable" );

			if ( '0' === $search_type ) {
				return;
			}

			$icon_display = Minimog::setting( 'header_icons_display' );

			$defaults = [
				'show_text'         => 'text' === $icon_display,
				'extra_class'       => '',
				'template_position' => 'popup',
				'toggle_device'     => 'sm',
			];

			$args = wp_parse_args( $args, $defaults );

			switch ( $search_type ) {
				case 'inline':
					if ( 'form' === $args['template_position'] ) {
						$args['extra_class'] .= ' hide-' . $args['toggle_device'];

						$this->print_search_form( $args );
					} else {
						$args['extra_class'] .= ' show-' . $args['toggle_device'];

						$this->print_search_popup( $args );
					}
					break;
				case 'popup':
					if ( 'popup' === $args['template_position'] ) {
						$this->print_search_popup( $args );
					}
					break;
			}
		}

		public function print_search_form( $args = array() ) {
			$defaults = [
				'search_field_placeholder' => _x( 'Search products', 'placeholder', 'minimog' ),
				'extra_class'              => '',
			];

			$args = wp_parse_args( $args, $defaults );

			minimog_load_template( 'header/components/search-form', null, $args );
		}

		public function print_search_popup( $args = array() ) {
			minimog_load_template( 'header/components/search-popup', null, $args );
		}

		public function print_login_button( $args = array() ) {
			$header_type  = Minimog_Global::instance()->get_header_type();
			$login_enable = Minimog::setting( "header_style_{$header_type}_login_enable" );

			// Do nothing if user option disabled.
			if ( '1' !== $login_enable ) {
				return;
			}

			$icon_display = Minimog::setting( 'header_icons_display' );

			$defaults = [
				'display' => $icon_display,
				'style'   => 'icon',
			];

			$args = wp_parse_args( $args, $defaults );

			minimog_load_template( 'header/components/login-button', null, $args );
		}

		function print_mini_cart() {
			$header_type = Minimog_Global::instance()->get_header_type();
			$enabled     = Minimog::setting( "header_style_{$header_type}_cart_enable" );

			if ( Minimog_Woo::instance()->is_activated() && '1' === $enabled ) {
				minimog_load_template( 'header/components/mini-cart-button' );
			}
		}

		public function print_wishlist_button() {
			$header_type     = Minimog_Global::instance()->get_header_type();
			$wishlist_enable = Minimog::setting( "header_style_{$header_type}_wishlist_enable" );

			if ( '1' !== $wishlist_enable || ! class_exists( 'WPCleverWoosw' ) ) {
				return;
			}

			minimog_load_template( 'header/components/wishlist-button' );
		}

		public function print_button( $args = array() ) {
			$header_type = Minimog_Global::instance()->get_header_type();

			$button_enable = Minimog::setting( "header_style_{$header_type}_button_enable" );

			if ( '1' !== $button_enable ) {
				return;
			}

			$button_style       = Minimog::setting( 'header_button_style' );
			$button_text        = Minimog::setting( 'header_button_text' );
			$button_link        = Minimog::setting( 'header_button_link' );
			$button_link_target = Minimog::setting( 'header_button_link_target' );
			$button_link_rel    = Minimog::setting( 'header_button_link_rel' );
			$icon_class         = Minimog::setting( 'header_button_icon' );

			$defaults = array(
				'extra_class' => '',
				'style'       => '',
				'size'        => 'nm',
			);

			$args = wp_parse_args( $args, $defaults );

			if ( $button_link !== '' && $button_text !== '' ) {
				echo '<div class="header-buttons">';

				Minimog_Templates::render_button( [
					'wrapper'     => false,
					'text'        => $button_text,
					'link'        => [
						'url'         => $button_link,
						'is_external' => '1' === $button_link_target,
						'nofollow'    => $button_link_rel,
					],
					'icon'        => $icon_class,
					'style'       => $button_style,
					'size'        => $args['size'],
					'extra_class' => $args['extra_class'] . ' header-button',
				] );

				Minimog_Templates::render_button( [
					'wrapper'     => false,
					'text'        => $button_text,
					'link'        => [
						'url'         => $button_link,
						'is_external' => '1' === $button_link_target,
						'nofollow'    => $button_link_rel,
					],
					'icon'        => $icon_class,
					'style'       => $button_style,
					'size'        => 'sm',
					'extra_class' => $args['extra_class'] . ' header-sticky-button',
				] );

				echo '</div>';
			}
		}

		public function print_open_mobile_menu_button( $args = array() ) {
			$defaults = [
				'style'     => '01',
				'direction' => 'left',
				'animation' => Minimog::setting( 'mobile_menu_open_animation' ),
			];

			$args = wp_parse_args( $args, $defaults );

			minimog_load_template( 'header/components/mobile-menu-button', null, $args );
		}

		public function print_info_list() {
			$type      = Minimog_Global::instance()->get_header_type();
			$enable    = Minimog::setting( "header_style_{$type}_info_list_enable" );
			$info_list = Minimog_Helper::parse_redux_repeater_field_values( Minimog::setting( 'info_list' ) );

			if ( '1' === $enable && ! empty( $info_list ) ) {
				minimog_load_template( 'header/components/info-list', null, $args = [ 'info_list' => $info_list ] );
			}
		}

		public function print_info_list_secondary() {
			$type      = Minimog_Global::instance()->get_header_type();
			$enable    = Minimog::setting( "header_style_{$type}_info_list_secondary_enable" );
			$info_list = Minimog_Helper::parse_redux_repeater_field_values( Minimog::setting( 'header_info_list_secondary' ) );

			if ( '1' === $enable && ! empty( $info_list ) ) {
				minimog_load_template( 'header/components/info-list-secondary', null, $args = [ 'info_list' => $info_list ] );
			}
		}

		public function print_text() {
			$type   = Minimog_Global::instance()->get_header_type();
			$enable = Minimog::setting( "header_style_{$type}_text_enable" );
			$text   = Minimog::setting( 'header_text' );

			if ( '1' === $enable && ! empty( $text ) ) {
				minimog_load_template( 'header/components/text', null, $args = [ 'text' => $text ] );
			}
		}

		public function print_currency_switcher() {
			$type   = Minimog_Global::instance()->get_header_type();
			$enable = Minimog::setting( "header_style_{$type}_currency_switcher_enable" );

			if ( ! $enable ) {
				return;
			}

			echo apply_filters( 'minimog/header/components/currency_switcher/output', '' );
		}

		public function has_category_menu() {
			return '1' === Minimog::setting( 'header_category_menu_enable' ) && has_nav_menu( 'category-dropdown' );
		}

		public function print_category_dropdown( $args = array() ) {
			if ( ! $this->has_category_menu() ) {
				return;
			}

			$menu_class = 'product-category-dropdown menu__container sm sm-simple sm-vertical';

			if ( is_rtl() ) {
				$menu_class .= ' sm-rtl';
			}

			$smartmenu_settings = [
				'mainMenuSubOffsetX' => -15,
				'subMenusSubOffsetX' => -18,
				'subMenusSubOffsetY' => -17,
				'keepInViewport'     => false,
			];

			$defaults = array(
				'theme_location' => 'category-dropdown',
				'container'      => 'ul',
				'menu_class'     => $menu_class,
				'extra_class'    => '',
				'items_wrap'     => '<ul id="%1$s" class="%2$s" data-sm-options="' . esc_attr( wp_json_encode( $smartmenu_settings ) ) . '">%3$s</ul>',
			);

			$args = wp_parse_args( $args, $defaults );

			if ( ! empty( $args['extra_class'] ) ) {
				$args['menu_class'] .= ' ' . $args['extra_class'];
			}

			if ( class_exists( 'Minimog_Walker_Nav_Menu' ) ) {
				$args['walker'] = new Minimog_Walker_Nav_Menu;
			}

			$toggle_text   = __( 'Shop by Categories', 'minimog' );
			$wrapper_class = 'header-categories-nav';

			$is_sticky = Minimog::setting( 'header_category_menu_sticky_homepage' );

			if ( is_front_page() && '1' === $is_sticky ) {
				$wrapper_class .= ' categories-nav-fixed';
			}
			?>
			<div id="header-categories-nav" class="<?php echo esc_attr( $wrapper_class ); ?>">
				<div class="inner">
					<span class="nav-toggle-btn" id="nav-toggle-btn">
						<span class="nav-toggle-bars far fa-bars"></span>
						<?php echo esc_html( $toggle_text ); ?>
					</span>
					<nav class="category-menu">
						<?php wp_nav_menu( $args ); ?>
					</nav>
				</div>
			</div>
			<?php
		}

		public function print_category_menu( $args = array() ) {
			if ( ! $this->has_category_menu() ) {
				return;
			}

			$menu_class = 'product-category-dropdown menu__container';

			$defaults = array(
				'theme_location' => 'category-dropdown',
				'container'      => 'ul',
				'menu_class'     => $menu_class,
				'extra_class'    => '',
				'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			);

			$args = wp_parse_args( $args, $defaults );

			if ( ! empty( $args['extra_class'] ) ) {
				$args['menu_class'] .= ' ' . $args['extra_class'];
			}

			if ( class_exists( 'Minimog_Walker_Nav_Menu' ) ) {
				$args['walker'] = new Minimog_Walker_Nav_Menu;
			}
			?>
			<div class="mobile-nav-tab mobile-nav-tab-cat-menu"
			     id="tab-content-cat-menu"
			     aria-labelledby="tab-title-cat-menu"
			     role="tabpanel"
			     tabindex="0"
			     hidden
			     aria-expanded="false">
				<?php wp_nav_menu( $args ); ?>
			</div>
			<?php
		}
	}

	Minimog_Header::instance()->initialize();
}
