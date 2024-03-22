<?php

class Minimog_Walker_Nav_Menu extends Walker_Nav_Menu {

	private $mega_menu = false;

	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		$id_field = $this->db_fields['id'];
		if ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent          = '';
		$this->mega_menu = false;

		if ( $depth ) {
			$indent = str_repeat( "\t", $depth );
		}

		$classes = array();
		if ( ! empty( $item->classes ) ) {
			$classes = (array) $item->classes;
		}

		$classes[] = 'menu-item-' . $item->ID;

		$post_args = array(
			'post_type'   => 'nav_menu_item',
			'nopaging'    => true,
			'numberposts' => 1,
			'meta_key'    => '_menu_item_menu_item_parent',
			'meta_value'  => $item->ID,
		);

		if ( $item->menu_item_parent === '0' ) {
			$classes[] = 'level-1';
		}

		if ( ! empty( $item->icon_url ) ) {
			$classes[] = 'menu-item-has-icon';
		}

		$children = get_posts( $post_args );

		foreach ( $children as $child ) {
			$obj = get_post_meta( $child->ID, '_menu_item_object' );
			if ( $obj[0] === 'ic_mega_menu' ) {
				$classes[]       = apply_filters( 'insight_core_mega_menu_css_class', 'has-mega-menu', $item, $args, $depth );
				$this->mega_menu = true;
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= $indent . '<li' . $class_names . '>';

		$attrs           = array();
		$attrs['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$attrs['target'] = ! empty( $item->target ) ? $item->target : '';
		$attrs['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$attrs['href']   = ! empty( $item->url ) ? $item->url : '';
		$attrs           = apply_filters( 'nav_menu_link_attributes', $attrs, $item, $args, $depth );

		$attributes = '';
		foreach ( $attrs as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value      = 'href' === $attr ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = $args->before;
		$item_output .= '<a' . $attributes . '><div class="menu-item-wrap">';

		if ( ! empty( $item->icon ) ) {
			$item_output .= '<span class="menu-item-icon">';

			if ( 'svg' === $item->icon_type ) {
				$item_output .= $item->icon_svg;
			} else {
				$item_output .= '<img src="' . esc_url( $item->icon_url ) . '" alt="Menu icon">';
			}

			$item_output .= '</span>';
		}

		$item_output .= '<span class="menu-item-title">' . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after . '</span>';

		if ( $args->has_children ) {
			$item_output .= '<span class="toggle-sub-menu"> </span>';
		}

		$item_output .= '</div></a>';
		$item_output .= $args->after;

		if ( 'ic_mega_menu' === $item->object && class_exists( '\Elementor\Plugin' ) ) {
			$mega_menu_content_class = apply_filters( 'insight_core_mega_menu_content_css_class', 'mega-menu-content', $item, $args, $depth );

			$output .= '<div class="' . esc_attr( $mega_menu_content_class ) . '">' . \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $item->object_id ) . '</div>';
		} else {
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$class = 'sub-menu children';

		if ( $this->mega_menu ) {
			$class .= ' mega-menu';
		}

		$indent = str_repeat( "\t", $depth );
		$output .= $indent . '<ul class="' . $class . '">';
	}
}
