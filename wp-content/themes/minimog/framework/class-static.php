<?php
defined( 'ABSPATH' ) || exit;

class Minimog {
	const PRIMARY_FONT           = 'Jost';
	const SECONDARY_FONT         = 'Jost';
	const PRIMARY_COLOR          = '#DA3F3F';
	const SECONDARY_COLOR        = '#000';
	const HEADING_COLOR          = '#000';
	const TEXT_COLOR             = '#666';
	const TEXT_LIGHTEN_COLOR     = '#ababab';
	const TYPOGRAPHY_BODY_WEIGHT = '400';
	const COMMENT_AVATAR_SIZE    = 70;

	public static $mobile_detect = null;

	public static function mobile_detect() {
		if ( null === self::$mobile_detect ) {
			if ( class_exists( 'Mobile_Detect' ) ) {
				self::$mobile_detect = new Mobile_Detect();
			} else {
				self::$mobile_detect = false;
			}
		}

		return self::$mobile_detect;
	}

	public static function is_tablet() {
		if ( empty( self::mobile_detect() ) ) {
			return false;
		}

		return self::mobile_detect()->isTablet();
	}

	public static function is_mobile() {
		if ( empty( self::mobile_detect() ) ) {
			return false;
		}

		if ( self::is_tablet() ) {
			return false;
		}

		return self::mobile_detect()->isMobile();
	}

	public static function is_handheld() {
		return ( self::is_mobile() || self::is_tablet() );
	}

	public static function is_desktop() {
		return ! self::is_handheld();
	}

	public static function elementor_is_edit_mode() {
		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			return true;
		}

		return false;
	}

	/**
	 * Get theme setting
	 *
	 * @param string $option_name
	 * @param string $default
	 *
	 * @return mixed
	 */
	public static function setting( $option_name = '', $default = '' ) {
		global $minimog_options;

		if ( isset( $minimog_options[ $option_name ] ) ) {
			return $minimog_options[ $option_name ];
		}

		if ( '' !== $default ) {
			return $default;
		}

		return Minimog_Redux::instance()->get_default_setting( $option_name, $default );
	}

	/**
	 * Primary Menu
	 *
	 * @param array $args
	 */
	public static function menu_primary( $args = array() ) {
		$menu_class = 'menu__container sm sm-simple';

		if ( is_rtl() ) {
			$menu_class .= ' sm-rtl';
		}

		$smartmenu_settings = [
			'subMenusSubOffsetX' => -18,
			'subMenusSubOffsetY' => -17,
		];

		$defaults = array(
			'theme_location' => 'primary',
			'container'      => 'ul',
			'menu_class'     => $menu_class,
			'menu_id'        => 'menu-primary', // Change this id also need to change in global variable below.
			'extra_class'    => '',
			'items_wrap'     => '<ul id="%1$s" class="%2$s" data-sm-options="' . esc_attr( wp_json_encode( $smartmenu_settings ) ) . '">%3$s</ul>',
		);

		if ( $defaults['extra_class'] ) {
			$defaults['menu_class'] .= ' ' . $defaults['extra_class'];
		}

		$args = wp_parse_args( $args, $defaults );

		if ( has_nav_menu( 'primary' ) && class_exists( 'Minimog_Walker_Nav_Menu' ) ) {
			$args['walker'] = new Minimog_Walker_Nav_Menu;
		}

		$menu = Minimog_Helper::get_post_meta( 'menu_display', '' );

		if ( $menu !== '' ) {
			$args['menu'] = $menu;
		}

		/**
		 * Nav menu render need many works.
		 * Cache it & used for mobile version to get the best performance.
		 *
		 * @see Minimog::menu_mobile_primary()
		 */
		global $minimog_primary_menu;

		ob_start();

		wp_nav_menu( $args );

		$minimog_primary_menu = ob_get_clean();

		echo '' . $minimog_primary_menu;
	}

	/**
	 * Mobile Menu
	 *
	 * @param array $args
	 */
	public static function menu_mobile_primary( $args = array() ) {
		ob_start();
		if ( ! has_nav_menu( 'mobile-menu' ) ) {
			global $minimog_primary_menu;

			$pattern = '/<ul\s[^>]*id=\"menu-primary\"[^>]*>/';

			$mobile_menu = preg_replace( $pattern, '<ul id="mobile-menu-primary" class="menu__container">', $minimog_primary_menu );

			echo '' . $mobile_menu;
			unset( $GLOBALS['minimog_primary_menu'] );
		} else { // Use different menu.
			$menu_class = 'menu__container sm sm-simple';

			if ( is_rtl() ) {
				$menu_class .= ' sm-rtl';
			}

			$smartmenu_settings = [
				'subMenusSubOffsetX' => -18,
				'subMenusSubOffsetY' => -17,
			];

			$defaults = array(
				'theme_location' => 'mobile-menu',
				'container'      => 'ul',
				'menu_class'     => $menu_class,
				'menu_id'        => 'mobile-menu-primary',
				'extra_class'    => '',
				'items_wrap'     => '<ul id="%1$s" class="%2$s" data-sm-options="' . esc_attr( wp_json_encode( $smartmenu_settings ) ) . '">%3$s</ul>',
			);

			if ( $defaults['extra_class'] ) {
				$defaults['menu_class'] .= ' ' . $defaults['extra_class'];
			}

			$args = wp_parse_args( $args, $defaults );

			$args['walker'] = new Minimog_Walker_Nav_Menu;

			wp_nav_menu( $args );
		}
		$output = ob_get_clean();
		?>
		<div class="mobile-nav-tab mobile-nav-tab-main-menu"
		     id="tab-content-main-menu"
		     aria-labelledby="tab-title-main-menu"
		     role="tabpanel"
		     tabindex="0"
		     aria-expanded="true"
		>
			<?php echo '' . $output; ?>
		</div>
		<?php
	}

	/**
	 * @deprecated 1.4.5
	 */
	public static function branding_logo( $args = array() ) {
		Minimog_Logo::instance()->render( $args );
	}

	/**
	 * @deprecated 1.4.5
	 */
	public static function branding_class( $class = '' ) {
		Minimog_Logo::instance()->output_wrap_class( $class );
	}

	/**
	 * Adds custom attributes to the body tag.
	 */
	public static function body_attributes() {
		$attrs = apply_filters( 'minimog/body_attributes', array() );

		$attrs_string = '';
		if ( ! empty( $attrs ) ) {
			foreach ( $attrs as $attr => $value ) {
				$attrs_string .= " {$attr}=" . '"' . esc_attr( $value ) . '"';
			}
		}

		echo '' . $attrs_string;
	}

	/**
	 * Adds custom classes to the navigation.
	 */
	public static function navigation_class( $class = '' ) {
		$classes = array( 'navigation page-navigation' );

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters( 'minimog/navigation/class', $classes, $class );

		echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
	}
}
