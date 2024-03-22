<?php

namespace Minimog_Elementor;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

class Widget_Button_Add_To_Cart extends Widget_Button {

	private $product = null;

	public function get_name() {
		return 'tm-button-add-to-cart';
	}

	public function get_title() {
		return esc_html__( 'Button: Add To Cart', 'minimog' );
	}

	public function get_icon_part() {
		return 'eicon-product-add-to-cart';
	}

	public function get_keywords() {
		return [ 'button', 'add to cart' ];
	}

	public function register_controls() {
		parent::register_controls();

		$this->update_control( 'text', [
			'placeholder' => '',
			'default'     => '',
			'description' => esc_html__( 'Leave blank to use default text', 'minimog' ),
		] );

		$this->remove_control( 'link' );

		$this->start_injection( [
			'type' => 'control',
			'at'   => 'after',
			'of'   => 'style',
		] );

		$this->add_control( 'product_id', [
			'label'        => esc_html__( 'Choose Product', 'minimog' ),
			'type'         => Module_Query_Base::AUTOCOMPLETE_CONTROL_ID,
			'label_block'  => true,
			'multiple'     => false,
			'autocomplete' => [
				'object' => Module_Query_Base::QUERY_OBJECT_POST,
				'query'  => [
					'post_type' => 'product',
				],
			],
			'render_type'  => 'template',
		] );

		$this->add_control( 'show_product_price', [
			'label'        => esc_html__( 'Show Product Price', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => '1',
			'default'      => '1',
			'render_type'  => 'template',
		] );

		$this->add_control( 'show_product_price_separator', [
			'label'        => esc_html__( 'Price Separator', 'minimog' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
			'prefix_class' => 'minimog-button-add-to-cart-price-separator-',
		] );

		$this->end_injection();
	}

	public function before_render_button() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['product_id'] ) ) {
			return;
		}

		/**
		 * @var \WC_Product $product
		 */
		$product = wc_get_product( $settings['product_id'] );

		if ( empty( $product ) ) {
			return;
		}

		$this->product = $product;

		$button_class = implode(
			' ',
			array_filter(
				array(
					'minimog-button-add-to-cart',
					'product_type_' . $product->get_type(),
					$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
					$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
				)
			)
		);

		$this->add_render_attribute( 'button', [
			'class'             => $button_class,
			'rel'               => 'nofollow',
			'href'              => $product->add_to_cart_url(),
			'data-quantity'     => 1,
			'data-product_id'   => $product->get_id(),
			'data-product_sku'  => $product->get_sku(),
		] );
	}

	/**
	 * @return \WC_Product|null $this->product
	 */
	public function get_wc_product() {
		return $this->product;
	}

	public function get_button_text() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['text'] ) ) {
			return $settings['text'];
		}

		$product = $this->get_wc_product();

		if ( $product ) {
			return $product->add_to_cart_text();
		}

		return esc_html__( 'Add to cart', 'minimog' );
	}

	public function print_button_text_after() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['show_product_price'] ) ) {
			return;
		}

		$product = $this->get_wc_product();

		if ( $product && $price_html = $product->get_price_html() ) {
			echo '' . $price_html;
		}
	}

	protected function content_template() {
		// @formatter:off
		?>
		<#
		view.addRenderAttribute( 'text', 'class', 'button-text' );

		if( '' === settings.text ) {
			settings.text = 'Add to cart';
		}

		view.addInlineEditingAttributes( 'text', 'none' );

		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' );

		var buttonUrl = '';
		if( settings.link && settings.link.url ) {
			buttonUrl = settings.link.url;
		}
		#>
		<div class="tm-button-wrapper">
			<a id="{{ settings.button_css_id }}"
			   class="tm-button style-{{ settings.style }} tm-button-{{ settings.size }} elementor-animation-{{ settings.hover_animation }} icon-{{ settings.icon_align }} {{ settings.button_css_class }}"
			   href="{{ buttonUrl }}" role="button">

				<div class="button-content-wrapper">

					<# if ( iconHTML.rendered && settings.icon_align == 'left' ) { #>
					<div class="button-icon">
						{{{ iconHTML.value }}}
					</div>
					<# } #>

					<# if ( settings.text ) { #>
						<div {{{ view.getRenderAttributeString( 'text' ) }}}>
							{{{ settings.text }}}

						<# if ( settings.style == 'bottom-line-winding' ) { #>
							<span class="line-winding">
								<svg width="42" height="6" viewBox="0 0 42 6" fill="none"
								     xmlns="http://www.w3.org/2000/svg">
									<path fill-rule="evenodd" clip-rule="evenodd"
									      d="M0.29067 2.60873C1.30745 1.43136 2.72825 0.72982 4.24924 0.700808C5.77022 0.671796 7.21674 1.31864 8.27768 2.45638C8.97697 3.20628 9.88872 3.59378 10.8053 3.5763C11.7218 3.55882 12.6181 3.13683 13.2883 2.36081C14.3051 1.18344 15.7259 0.481897 17.2469 0.452885C18.7679 0.423873 20.2144 1.07072 21.2753 2.20846C21.9746 2.95836 22.8864 3.34586 23.8029 3.32838C24.7182 3.31092 25.6133 2.89009 26.2831 2.11613C26.2841 2.11505 26.285 2.11396 26.2859 2.11288C27.3027 0.935512 28.7235 0.233974 30.2445 0.204962C31.7655 0.17595 33.212 0.822796 34.2729 1.96053C34.9722 2.71044 35.884 3.09794 36.8005 3.08045C37.7171 3.06297 38.6134 2.64098 39.2836 1.86496C39.6445 1.44697 40.276 1.40075 40.694 1.76173C41.112 2.12271 41.1582 2.75418 40.7972 3.17217C39.7804 4.34954 38.3597 5.05108 36.8387 5.08009C35.3177 5.1091 33.8712 4.46226 32.8102 3.32452C32.1109 2.57462 31.1992 2.18712 30.2826 2.2046C29.3674 2.22206 28.4723 2.64289 27.8024 3.41684C27.8015 3.41793 27.8005 3.41901 27.7996 3.42009C26.7828 4.59746 25.362 5.299 23.841 5.32801C22.3201 5.35703 20.8735 4.71018 19.8126 3.57244C19.1133 2.82254 18.2016 2.43504 17.285 2.45252C16.3685 2.47 15.4722 2.89199 14.802 3.66802C13.7852 4.84539 12.3644 5.54693 10.8434 5.57594C9.32242 5.60495 7.8759 4.9581 6.81496 3.82037C6.11568 3.07046 5.20392 2.68296 4.28738 2.70044C3.37083 2.71793 2.47452 3.13992 1.80434 3.91594C1.44336 4.33393 0.811887 4.38015 0.393899 4.01917C-0.0240897 3.65819 -0.0703068 3.02672 0.29067 2.60873Z"
									      fill="#E8C8B3"/>
								</svg>
							</span>
						<# } #>
						</div>
					<# } #>

					<# if ( iconHTML.rendered && settings.icon_align == 'right' ) { #>
					<div class="button-icon">
						{{{ iconHTML.value }}}
					</div>
					<# } #>

				</div>

				<# if( settings.badge_text ) { #>
					<div class="button-badge">
						<div class="badge-text">{{{ settings.badge_text }}}</div>
					</div>
				<# } #>
			</a>
		</div>
		<?php
		// @formatter:off
	}
}
