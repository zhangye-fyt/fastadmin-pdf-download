<?php
defined( 'ABSPATH' ) || exit;

class Minimog_Redux_Dynamic_Output {

	protected static $instance = null;
	const TRANSIENT_CSS_REDUX = 'minimog_css_redux';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_filter( 'minimog/options/transients_to_clear', [ $this, 'add_transient_clear' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'output_settings_style' ], 9999 );
	}

	private function get_field_outputs() {
		return [
			'body_background'                                                => [
				'type'   => 'background',
				'output' => [ 'background-color' => 'body' ],
			],
			'logo_padding'                                                   => [
				'type'   => 'spacing',
				'output' => [ '.page-header .branding__logo a' ],
			],
			'top_bar_style_01_text_typography'                               => [
				'type'   => 'typography',
				'output' => [ '.top-bar-01' ],
			],
			'top_bar_style_01_bg_color'                                      => [
				'type'   => 'color',
				'output' => [ 'background-color' => '.top-bar-01' ],
			],
			'top_bar_style_01_border'                                        => [
				'type'   => 'border',
				'output' => [ '.top-bar-01' ],
			],
			'top_bar_style_01_link_color'                                    => [
				'type'   => 'color',
				'output' => [ '--top-bar-link-color' => '.top-bar-01' ],
			],
			'top_bar_style_01_link_hover_color'                              => [
				'type'   => 'color',
				'output' => [ '--top-bar-link-hover-color' => '.top-bar-01' ],
			],
			'top_bar_style_01_tag_color'                                     => [
				'type'   => 'color',
				'output' => [ '--top-bar-tag-color' => '.top-bar-01' ],
			],
			'top_bar_style_01_tag_background'                                => [
				'type'   => 'color',
				'output' => [ '--top-bar-tag-background' => '.top-bar-01' ],
			],
			'header_sticky_background'                                       => [
				'type'      => 'background',
				'output'    => [
					'background-color' => '#page-header.header-pinned .page-header-inner',
				],
				'important' => true,
			],
			'header_style_navigation_typography'                             => [
				'type'   => 'typography',
				'output' => [
					'.page-header .menu--primary > ul > li > a',
					'.header-icon .text',
					'.header-categories-nav .nav-toggle-btn',
					'.mini-cart-total',
				],
			],
			'header_style_dark_background'                                   => [
				'type'   => 'background',
				'output' => [
					'background-color' => '.page-header.header-dark .page-header-inner',
				],
			],
			'header_style_dark_box_shadow'                                   => [
				'type'   => 'box_shadow',
				'output' => [ '.page-header.header-dark .page-header-inner' ],
			],
			'header_style_dark_text_color'                                   => [
				'type'   => 'color',
				'output' => [ '--header-text-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_link_color'                                   => [
				'type'   => 'color',
				'output' => [ '--header-link-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_link_hover_color'                             => [
				'type'   => 'color',
				'output' => [ '--header-link-hover-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_nav_link_color'                               => [
				'type'   => 'color',
				'output' => [ '--header-nav-link-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_nav_link_hover_color'                         => [
				'type'   => 'color',
				'output' => [ '--header-nav-link-hover-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_nav_line_color'                               => [
				'type'   => 'color',
				'output' => [ '--nav-item-hover-line-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_icon_color'                                   => [
				'type'   => 'color',
				'output' => [ '--header-icon-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_icon_hover_color'                             => [
				'type'   => 'color',
				'output' => [ '--header-icon-hover-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_icon_badge_text_color'                        => [
				'type'   => 'color',
				'output' => [ '--header-icon-badge-text-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_icon_badge_background_color'                  => [
				'type'   => 'color',
				'output' => [ '--header-icon-badge-background-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_tag_text_color'                               => [
				'type'   => 'color',
				'output' => [ '--header-tag-color' => '.page-header.header-dark' ],
			],
			'header_style_dark_tag_bg_color'                                 => [
				'type'   => 'color',
				'output' => [ '--header-tag-background' => '.page-header.header-dark' ],
			],
			'header_above_style_dark_background'                             => [
				'type'   => 'background',
				'output' => [ 'background-color' => '.page-header.header-dark .header-above' ],
			],
			'header_style_dark_form_text_color'                              => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-text' => '.page-header.header-dark' ],
			],
			'header_style_dark_form_background_color'                        => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-background' => '.page-header.header-dark' ],
			],
			'header_style_dark_form_border_color'                            => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-border' => '.page-header.header-dark' ],
			],
			'header_style_dark_form_submit_text_color'                       => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-submit-text' => '.page-header.header-dark' ],
			],
			'header_above_style_dark_border_color'                           => [
				'type'   => 'color',
				'output' => [ 'border-color' => '.page-header.header-dark .header-above' ],
			],
			'header_above_style_dark_item_separator_color'                   => [
				'type'   => 'color',
				'output' => [ '--header-item-separator-color' => '.page-header.header-dark .header-above' ],
			],
			'header_above_style_dark_text_color'                             => [
				'type'   => 'color',
				'output' => [ '--header-text-color' => '.page-header.header-dark .header-above' ],
			],
			'header_above_style_dark_link_color'                             => [
				'type'   => 'color',
				'output' => [ '--header-link-color' => '.page-header.header-dark .header-above' ],
			],
			'header_above_style_dark_link_hover_color'                       => [
				'type'   => 'color',
				'output' => [ '--header-link-hover-color' => '.page-header.header-dark .header-above' ],
			],
			'header_below_style_dark_border'                                 => [
				'type'   => 'border',
				'output' => [ '.header-below' ],
			],
			'header_below_style_dark_inner_border'                           => [
				'type'   => 'border',
				'output' => [ '.header-below-wrap' ],
			],
			'header_style_light_background'                                  => [
				'type'   => 'background',
				'output' => [ 'background-color' => '.page-header.header-light .page-header-inner' ],
			],
			'header_style_light_box_shadow'                                  => [
				'type'   => 'box_shadow',
				'output' => [ '.page-header.header-light .page-header-inner' ],
			],
			'header_style_light_text_color'                                  => [
				'type'   => 'color',
				'output' => [ '--header-text-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_link_color'                                  => [
				'type'   => 'color',
				'output' => [ '--header-link-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_link_hover_color'                            => [
				'type'   => 'color',
				'output' => [ '--header-link-hover-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_nav_link_color'                              => [
				'type'   => 'color',
				'output' => [ '--header-nav-link-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_nav_link_hover_color'                        => [
				'type'   => 'color',
				'output' => [ '--header-nav-link-hover-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_nav_line_color'                              => [
				'type'   => 'color',
				'output' => [ '--nav-item-hover-line-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_icon_color'                                  => [
				'type'   => 'color',
				'output' => [ '--header-icon-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_icon_hover_color'                            => [
				'type'   => 'color',
				'output' => [ '--header-icon-hover-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_icon_badge_text_color'                       => [
				'type'   => 'color',
				'output' => [ '--header-icon-badge-text-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_icon_badge_background_color'                 => [
				'type'   => 'color',
				'output' => [ '--header-icon-badge-background-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_tag_text_color'                              => [
				'type'   => 'color',
				'output' => [ '--header-tag-color' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_tag_bg_color'                                => [
				'type'   => 'color',
				'output' => [ '--header-tag-background' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_form_text_color'                             => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-text' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_form_background_color'                       => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-background' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_form_border_color'                           => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-border' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_style_light_form_submit_text_color'                      => [
				'type'   => 'color',
				'output' => [ '--minimog-color-form-submit-text' => '.page-header:not(.header-pinned).header-light' ],
			],
			'header_below_style_light_border'                                => [
				'type'   => 'border',
				'output' => [ '.page-header:not(.header-pinned).header-light .header-below' ],
			],
			'header_below_style_light_inner_border'                          => [
				'type'   => 'border',
				'output' => [ '.page-header:not(.header-pinned).header-light .header-below-wrap' ],
			],

			// Category toggler in dark version.
			'header_category_menu_toggle_color'                              => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-color' => '.header-categories-nav' ],
			],
			'header_category_menu_toggle_background_color'                   => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-background' => '.header-categories-nav' ],
			],
			'header_category_menu_toggle_hover_color'                        => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-hover-color' => '.header-categories-nav' ],
			],
			'header_category_menu_toggle_hover_background_color'             => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-hover-background' => '.header-categories-nav' ],
			],
			// Category toggler in light version.
			'header_style_light_category_menu_toggle_color'                  => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-color' => '.page-header.header-light .header-categories-nav' ],
			],
			'header_style_light_category_menu_toggle_background_color'       => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-background' => '.page-header.header-light .header-categories-nav' ],
			],
			'header_style_light_category_menu_toggle_hover_color'            => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-hover-color' => '.page-header.header-light .header-categories-nav' ],
			],
			'header_style_light_category_menu_toggle_hover_background_color' => [
				'type'   => 'color',
				'output' => [ '--nav-toggler-hover-background' => '.page-header.header-light .header-categories-nav' ],
			],
			// Category dropdown.
			'header_category_menu_background_color'                          => [
				'type'   => 'color',
				'output' => [ '--menu-background' => '.header-categories-nav' ],
			],
			'header_category_menu_border_color'                              => [
				'type'   => 'color',
				'output' => [ '--menu-border-color' => '.header-categories-nav' ],
			],
			'header_category_menu_link_color'                                => [
				'type'   => 'color',
				'output' => [ '--link-color' => '.header-categories-nav' ],
			],
			'header_category_menu_link_hover_color'                          => [
				'type'   => 'color',
				'output' => [ '--link-hover-color' => '.header-categories-nav' ],
			],
			'header_category_menu_link_hover_background'                     => [
				'type'   => 'color',
				'output' => [ '--link-hover-background' => '.header-categories-nav' ],
			],
			'header_category_menu_link_arrow_color'                          => [
				'type'   => 'color',
				'output' => [ '--link-arrow-color' => '.header-categories-nav' ],
			],
			'header_category_menu_link_hover_arrow_color'                    => [
				'type'   => 'color',
				'output' => [ '--link-hover-arrow-color' => '.header-categories-nav' ],
			],
			'header_sticky_text_color'                                       => [
				'type'   => 'color',
				'output' => [ '--header-text-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_link_color'                                       => [
				'type'   => 'color',
				'output' => [ '--header-link-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_link_hover_color'                                 => [
				'type'   => 'color',
				'output' => [ '--header-link-hover-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_nav_link_color'                                   => [
				'type'   => 'color',
				'output' => [ '--header-nav-link-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_nav_link_hover_color'                             => [
				'type'   => 'color',
				'output' => [ '--header-nav-link-hover-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_nav_line_color'                                   => [
				'type'   => 'color',
				'output' => [ '--nav-item-hover-line-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_icon_color'                                       => [
				'type'   => 'color',
				'output' => [ '--header-icon-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_icon_hover_color'                                 => [
				'type'   => 'color',
				'output' => [ '--header-icon-hover-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_icon_badge_text_color'                            => [
				'type'   => 'color',
				'output' => [ '--header-icon-badge-text-color' => '.page-header.header-pinned' ],
			],
			'header_sticky_icon_badge_background_color'                      => [
				'type'   => 'color',
				'output' => [ '--header-icon-badge-background-color' => '.page-header.header-pinned' ],
			],

			// Desktop Menu.
			'navigation_dropdown_bg_color'                                   => [
				'type'   => 'color',
				'output' => [ 'background-color' => '.sm-simple .children, .primary-menu-sub-visual' ],
			],
			'navigation_dropdown_box_shadow'                                 => [
				'type'   => 'box_shadow',
				'output' => [ '.desktop-menu .sm-simple .children, .primary-menu-sub-visual' ],
			],
			'navigation_dropdown_link_color'                                 => [
				'type'   => 'color',
				'output' => [ 'color' => '.sm-simple .children > li > a' ],
			],
			'navigation_dropdown_link_hover_color'                           => [
				'type'   => 'color',
				'output' => [
					'color' => '.sm-simple .children > li:hover > a,
					.sm-simple .children > li:hover > a:after,
					.sm-simple .children > li.current-menu-item > a,
					.sm-simple .children > li.current-menu-ancestor > a',
				],
			],
			'navigation_dropdown_link_hover_bg_color'                        => [
				'type'   => 'color',
				'output' => [
					'background-color' => '.sm-simple .children > li:hover > a,
					.sm-simple .children > li.current-menu-item > a,
					.sm-simple .children > li.current-menu-ancestor > a',
				],
			],
			// Mobile Menu.
			'mobile_menu_background'                                         => [
				'type'   => 'background',
				'output' => [ 'background-color' => '.page-mobile-main-menu > .inner' ],
			],
			'mobile_menu_overlay_color'                                      => [
				'type'   => 'color',
				'output' => [ 'background-color' => '.page-mobile-main-menu > .inner:before' ],
			],
			'mobile_menu_nav_level_1_padding'                                => [
				'type'   => 'spacing',
				'output' => [ '.page-mobile-main-menu .menu__container > li > a' ],
			],
			'title_bar_minimal_01_margin'                                    => [
				'type'   => 'spacing',
				'output' => [ '.page-title-bar-minimal-01' ],
			],
			'title_bar_minimal_01_breadcrumb_padding'                        => [
				'type'   => 'spacing',
				'output' => [ '.page-title-bar-minimal-01 .page-breadcrumb' ],
			],
			'title_bar_minimal_01_text_align'                                => [
				'type'   => 'select',
				'output' => [
					'--breadcrumb-align' => '.page-title-bar-minimal-01',
				],
			],
			'title_bar_minimal_01_breadcrumb_text_color'                     => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-text' => '.page-title-bar-minimal-01',
				],
			],
			'title_bar_minimal_01_breadcrumb_link_color'                     => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-link' => '.page-title-bar-minimal-01',
				],
			],
			'title_bar_minimal_01_breadcrumb_link_hover_color'               => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-link-hover' => '.page-title-bar-minimal-01',
				],
			],
			'title_bar_minimal_01_breadcrumb_separator_color'                => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-separator' => '.page-title-bar-minimal-01',
				],
			],
			// Title bar fill 01.
			'title_bar_fill_01_breadcrumb_text_align'                        => [
				'type'   => 'select',
				'output' => [
					'--breadcrumb-align' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_background'                                   => [
				'type'   => 'background',
				'output' => [
					'background-color' => '.page-title-bar-fill-01 .page-title-bar-bg',
				],
			],
			'title_bar_fill_01_background_overlay'                           => [
				'type'   => 'color',
				'output' => [
					'background' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_heading_color'                                => [
				'type'   => 'color',
				'output' => [
					'--title-bar-color-heading' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_text_color'                                   => [
				'type'   => 'color',
				'output' => [
					'--title-bar-color-text' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_breadcrumb_text_color'                        => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-text' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_breadcrumb_link_color'                        => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-link' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_breadcrumb_link_hover_color'                  => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-link-hover' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_breadcrumb_separator_color'                   => [
				'type'   => 'color',
				'output' => [
					'--breadcrumb-color-separator' => '.page-title-bar-fill-01',
				],
			],
			'title_bar_fill_01_category_name_color'                          => [
				'type'   => 'color',
				'output' => [
					'color' => '.page-title-bar-fill-01 .minimog-product-categories .category-name',
				],
			],
			'title_bar_fill_01_category_count_color'                         => [
				'type'   => 'color',
				'output' => [
					'color' => '.page-title-bar-fill-01 .minimog-product-categories .category-count',
				],
			],
			'title_bar_fill_01_category_price_color'                         => [
				'type'   => 'color',
				'output' => [
					'color' => '.page-title-bar-fill-01 .minimog-product-categories .category-min-price',
				],
			],
			'title_bar_fill_01_category_price_amount_color'                  => [
				'type'   => 'color',
				'output' => [
					'color' => '.page-title-bar-fill-01 .minimog-product-categories .category-min-price .amount',
				],
			],
			'error404_page_background_body'                                  => [
				'type'   => 'background',
				'output' => [
					'background-color' => '.error404',
				],
			],
			'pre_loader_background_color'                                    => [
				'type'   => 'color',
				'output' => [
					'background-color' => '.page-loading',
				],
			],
			'pre_loader_shape_color'                                         => [
				'type'   => 'color',
				'output' => [
					'--preloader-color' => '.page-loading',
				],
			],
			'pre_loader_image_width'                                         => [
				'type'   => 'dimensions',
				'output' => [
					'.minimog-pre-loader-gif-img',
				],
			],
			// Shop Price.
			'price_regular_color'                                            => [
				'type'      => 'color',
				'output'    => [ 'color' => '.price, .amount, .tr-price, .woosw-content-item--price' ],
				'important' => true,
			],
			'price_old_color'                                                => [
				'type'      => 'color',
				'output'    => [ 'color' => '.price del, del .amount, .tr-price del, .woosw-content-item--price del' ],
				'important' => true,
			],
			'price_sale_color'                                               => [
				'type'      => 'color',
				'output'    => [ 'color' => 'ins .amount, .product.sale ins, .product.sale ins .amount, .single-product .product.sale .entry-summary > .price ins .amount' ],
				'important' => true,
			],
			// Shop Badges.
			'shop_badge_sale_text_color'                                     => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-text' => '.woocommerce .product-badges .onsale',
				],
				'important' => true,
			],
			'shop_badge_sale_background_color'                               => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-bg' => '.woocommerce .product-badges .onsale',
				],
				'important' => true,
			],
			'shop_badge_new_text_color'                                      => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-text' => '.woocommerce .product-badges .new',
				],
				'important' => true,
			],
			'shop_badge_new_background_color'                                => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-bg' => '.woocommerce .product-badges .new',
				],
				'important' => true,
			],
			'shop_badge_hot_text_color'                                      => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-text' => '.woocommerce .product-badges .hot',
				],
				'important' => true,
			],
			'shop_badge_hot_background_color'                                => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-bg' => '.woocommerce .product-badges .hot',
				],
				'important' => true,
			],
			'shop_badge_best_selling_text_color'                             => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-text' => '.woocommerce .product-badges .best-seller',
				],
				'important' => true,
			],
			'shop_badge_best_selling_background_color'                       => [
				'type'      => 'color',
				'output'    => [
					'--p-badge-bg' => '.woocommerce .product-badges .best-seller',
				],
				'important' => true,
			],
		];
	}

	public function output_settings_style() {
		$transient_key = self::TRANSIENT_CSS_REDUX;
		$transient_key .= ! empty( $_GET['site_settings_preset'] ) ? '-' . $_GET['site_settings_preset'] : '';
		$transient_key .= ! empty( $_GET['settings_preset'] ) ? '-' . $_GET['settings_preset'] : '';

		// Skip cache in dev mode.
		$dynamic_css = ! Minimog_Helper::is_dev_mode() ? get_transient( $transient_key ) : false;

		if ( false === $dynamic_css ) {
			$dynamic_css               = '';
			$blacklist_font_attributes = [
				'font-options',
				'google',
				'subsets',
			];
			$field_outputs             = $this->get_field_outputs();
			foreach ( $field_outputs as $field_id => $field_setting ) {
				$field_value = Minimog::setting( $field_id );

				switch ( $field_setting['type'] ) {
					case 'color':
						if ( empty( $field_value ) ) {
							break;
						}

						$output = $field_setting['output'];
						$suffix = ! empty( $field_setting['important'] ) ? '!important' : '';

						foreach ( $output as $attribute => $selectors ) {
							if ( is_array( $field_value ) ) {
								if ( isset( $field_value[ $attribute ] ) ) {
									$dynamic_css .= "$selectors { $attribute: $field_value[$attribute] $suffix }";
								}
							} else {
								$dynamic_css .= "$selectors { $attribute: $field_value $suffix }";
							}
						}
						break;
					case 'background':
						if ( ! is_array( $field_value ) ) {
							break;
						}

						$suffix    = ! empty( $field_setting['important'] ) ? '!important' : '';
						$field_css = '';

						foreach ( $field_value as $attribute_name => $attribute_value ) {
							if ( '' === $attribute_value || 'media' === $attribute_name ) {
								continue;
							}

							if ( 'background-image' === $attribute_name ) {
								$field_css .= "{$attribute_name}: url( {$attribute_value} )$suffix;";
							} else {
								$field_css .= "{$attribute_name}: {$attribute_value}$suffix;";
							}
						}

						if ( '' === $field_css ) {
							break;
						}

						foreach ( $field_setting['output'] as $selectors ) {
							$dynamic_css .= "$selectors { $field_css }";
						}
						break;
					case 'typography':
						$field_css = '';

						if ( is_array( $field_value ) ) {
							foreach ( $field_value as $attribute => $attribute_value ) {
								if ( '' === $attribute_value || in_array( $attribute, $blacklist_font_attributes, true ) ) {
									continue;
								}

								$field_css .= "$attribute: $attribute_value;";
							}

							foreach ( $field_setting['output'] as $selectors ) {
								$dynamic_css .= "$selectors { $field_css }";
							}
						}

						break;
					case 'spacing':
					case 'dimensions':
						$field_css = '';

						if ( is_array( $field_value ) ) {
							$unit       = ! empty( $field_value['units'] ) ? $field_value['units'] : 'px';
							$attributes = $field_value;
							unset( $attributes['units'] );


							foreach ( $attributes as $attribute => $attribute_value ) {
								if ( '' === $attribute_value ) {
									continue;
								}

								$attribute_value = intval( $attribute_value );

								$field_css .= $attribute_value > 0 ? "$attribute: {$attribute_value}{$unit};" : "$attribute: {$attribute_value};";
							}

							if ( ! empty( $field_css ) ) {
								foreach ( $field_setting['output'] as $selectors ) {
									$dynamic_css .= "$selectors { $field_css }";
								}
							}
						}
						break;
					case 'border':
						if ( ! is_array( $field_value ) || empty( $field_value['border-style'] ) || empty( $field_value['border-color'] ) ) {
							break;
						}

						$field_css    = '';
						$border_style = $field_value['border-style'];
						$border_color = $field_value['border-color'];

						foreach ( $field_value as $attribute_name => $attribute_value ) {
							if ( '' === $attribute_value || 'border-style' === $attribute_name || 'border-color' === $attribute_name ) {
								continue;
							}

							$field_css .= "{$attribute_name}: {$attribute_value} $border_style $border_color;";
						}

						foreach ( $field_setting['output'] as $selectors ) {
							$dynamic_css .= "$selectors { $field_css }";
						}

						break;
					case 'box_shadow':
						$shadow_css = [];

						if ( is_array( $field_value ) ) {
							foreach ( $field_value as $shadow_type => $shadow_value ) {
								if ( empty( $shadow_value['checked'] ) ) {
									continue;
								}

								if ( 'inset-shadow' === $shadow_type ) {
									$shadow_css[] = "{$shadow_value['horizontal']}px {$shadow_value['vertical']}px {$shadow_value['blur']}px {$shadow_value['spread']}px {$shadow_value['color']} inset";
								} else {
									$shadow_css[] = "{$shadow_value['horizontal']}px {$shadow_value['vertical']}px {$shadow_value['blur']}px {$shadow_value['spread']}px {$shadow_value['color']}";
								}
							}
						}

						if ( ! empty( $shadow_css ) ) {
							$shadow_str = implode( ',', $shadow_css );
							foreach ( $field_setting['output'] as $selectors ) {
								$dynamic_css .= "$selectors { box-shadow: $shadow_str; }";
							}
						}
						break;
					case 'select':
						if ( '' !== $field_value ) {
							foreach ( $field_setting['output'] as $attribute => $selectors ) {
								$dynamic_css .= "$selectors { {$attribute}: $field_value; }";
							}
						}
						break;
				}
			}

			set_transient( $transient_key, $dynamic_css, 7 * DAY_IN_SECONDS );
		}

		if ( ! empty( $dynamic_css ) ) {
			wp_add_inline_style( 'minimog-style', html_entity_decode( $dynamic_css, ENT_QUOTES ) );
		}
	}

	public function add_transient_clear( $transients ) {
		global $wpdb;

		$sql   = "SELECT `option_name` FROM $wpdb->options WHERE `option_name` LIKE %s";
		$query = $wpdb->prepare( $sql, '%' . $wpdb->esc_like( '_transient_' . self::TRANSIENT_CSS_REDUX ) . '%' );

		$results = $wpdb->get_results( $query );
		if ( ! empty( $results ) ) {
			// We need remove _transient_ from option name.
			$prefix        = '_transient_';
			$prefix_length = strlen( $prefix );
			foreach ( $results as $record ) {
				if ( substr( $record->option_name, 0, $prefix_length ) == $prefix ) {
					$transient_name = substr( $record->option_name, $prefix_length );
					$transients[]   = $transient_name;
				}
			}
		}

		return $transients;
	}
}

Minimog_Redux_Dynamic_Output::instance()->initialize();
