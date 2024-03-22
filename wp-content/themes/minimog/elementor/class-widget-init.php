<?php

namespace Minimog_Elementor;

defined( 'ABSPATH' ) || exit;

class Widget_Init {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function initialize() {
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_widget_categories' ] );

		// Registered Widgets.
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		//add_action( 'elementor/widgets/register', [ $this, 'remove_unwanted_widgets' ], 15 );

		add_action( 'elementor/dynamic_tags/register', [ $this, 'register_tags' ] );

		add_action( 'elementor/element/after_add_attributes', [ $this, 'remove_old_entrance_animation' ] );

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'after_register_scripts' ] );

		// Modify original widgets settings.
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/modify-base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/section.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/column.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/common.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/accordion.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/animated-headline.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/counter.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/image.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/form.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/heading.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/icon-box.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/progress.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/countdown.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/original/wpforms.php' );
	}

	/**
	 * @param \Elementor\Element_Base $element
	 */
	public function remove_old_entrance_animation( $element ) {
		$setting = $element->get_settings_for_display();
		if ( ! empty( $setting['_animation'] ) && 'tmFadeInUp' === $setting['_animation'] ) {
			$element->remove_render_attribute( '_wrapper', 'class', 'elementor-invisible' );
		}
	}

	/**
	 * Register scripts for widgets.
	 */
	public function after_register_scripts() {
		$min = \Minimog_Enqueue::instance()->get_min_suffix();

		// Fix Wordpress old version not registered this script.
		if ( ! wp_script_is( 'imagesloaded', 'registered' ) ) {
			wp_register_script( 'imagesloaded', MINIMOG_THEME_URI . '/assets/libs/imagesloaded/imagesloaded.min.js', array( 'jquery' ), null, true );
		}

		wp_register_script( 'circle-progress', MINIMOG_THEME_URI . '/assets/libs/circle-progress/circle-progress.min.js', array( 'jquery' ), null, true );
		wp_register_script( 'minimog-widget-circle-progress', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-circle-progress.js', array(
			'jquery',
			'circle-progress',
		), null, true );

		\Minimog_Enqueue::instance()->register_swiper();
		\Minimog_Enqueue::instance()->register_grid_layout();

		wp_register_script( 'minimog-group-widget-carousel', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/group-widget-carousel.js', array(
			'minimog-swiper-wrapper',
		), null, true );

		wp_register_script( 'minimog-grid-query', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/grid-query.js', array( 'jquery' ), null, true );

		wp_register_script( 'minimog-widget-grid-post', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-grid-post.js', array( 'minimog-grid-layout' ), null, true );
		wp_register_script( 'minimog-group-widget-grid', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/group-widget-grid.js', array( 'minimog-grid-layout' ), null, true );

		wp_register_script( 'minimog-widget-google-map', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-google-map.js', array( 'jquery' ), null, true );

		wp_register_script( 'vivus', MINIMOG_ELEMENTOR_URI . '/assets/libs/vivus/vivus.js', array( 'jquery' ), null, true );
		wp_register_script( 'minimog-widget-icon-box', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-icon-box.js', array(
			'jquery',
			'vivus',
		), null, true );

		wp_register_script( 'minimog-widget-flip-box', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-flip-box.js', array(
			'jquery',
			'imagesloaded',
		), null, true );

		wp_register_script( 'minimog-widget-tabs', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-tabs.js', array(
			'jquery',
			'minimog-tab-panel',
		), null, true );

		wp_register_script( 'minimog-widget-shoppable-image', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-shoppable-image.js', array(
			'jquery',
		), null, true );

		wp_register_script( 'count-to', MINIMOG_ELEMENTOR_URI . "/assets/libs/countTo/jquery.countTo{$min}.js", array( 'jquery' ), null, true );
		wp_register_script( 'minimog-widget-counter', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-counter.js', array(
			'jquery',
			'count-to',
		), null, true );

		wp_register_script( 'minimog-widget-gallery-justified-content', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-gallery-justified-content.js', array(
			'justifiedGallery',
		), null, true );

		wp_register_script( 'countdown', MINIMOG_ELEMENTOR_URI . '/assets/libs/jquery.countdown/js/jquery.countdown.min.js', array( 'jquery' ), MINIMOG_THEME_VERSION, true );
		wp_register_script( 'minimog-product-carousel-countdown', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-product-carousel-countdown.js', array(
			'minimog-swiper-wrapper',
			'countdown',
		), null, true );

		wp_register_script( 'minimog-widget-product-bundle', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-product-bundle.js', array(
			'jquery',
		), null, true );

		wp_register_script( 'minimog-widget-countdown', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-countdown.js', array(
			'jquery',
			'countdown',
		), null, true );

		wp_register_script( 'minimog-widget-product-filter', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-product-filter.js', array(
			'jquery',
		), null, true );

		wp_register_script( 'minimog-featured-product', MINIMOG_ELEMENTOR_URI . '/assets/js/widgets/widget-featured-product.js', array(
			'minimog-swiper-wrapper',
		), null, true );
	}

	/**
	 * @param \Elementor\Elements_Manager $elements_manager
	 *
	 * Add category.
	 */
	function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category( 'minimog', [
			'title' => esc_html__( 'By Minimog', 'minimog' ),
			'icon'  => 'eicon-settings',
		] );

		$elements_manager->add_category( 'minimog_wc_product', [
			'title' => esc_html__( 'Product (Minimog)', 'minimog' ),
			'icon'  => 'eicon-woo-settings',
		] );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @param \Elementor\Widgets_Manager $widget_manager
	 *
	 * @since  1.0.0
	 *
	 * @access public
	 * @throws \Exception
	 */
	public function init_widgets( $widget_manager ) {
		// Include Widget files.
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/form/form-base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/posts/posts-base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/carousel-base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/posts-carousel-base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/terms-carousel-base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/static-carousel.php' );

		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/modern-image.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/accordion.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/blockquote.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/button.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/button-scroll.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/button-popup-video.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/google-map.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/heading.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/icon.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/icon-box.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/animated-icon-box.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/image-box.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/image-layers.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/image-gallery.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/gallery-justified-content.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/banner.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/attribute-list.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/countdown.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/list.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/marquee-list.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/team-member.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/social-networks.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/popup-video.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/separator.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/tabs.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/counter.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/icon-box-list.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/client-box.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/rating-box.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/typed-headline.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/instagram.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/simple-link.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/simple-list.php' );

		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/grid/grid-base.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/grid/static-grid.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/grid/client-logo.php' );

		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/posts/blog.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/posts/blog-carousel.php' );


		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/grid/testimonial-grid.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/testimonial-carousel.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/testimonial-slideshow.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/instagram-carousel.php' );


		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/image-carousel.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/modern-slider.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/slider-slideshow.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/parallax-sliders.php' );

		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/image-box-carousel.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/testimonial.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/carousel-nav-buttons.php' );

		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/theme/site-logo.php' );

		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/language-switcher.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/currency-switcher.php' );

		// Register Widgets.
		$widget_manager->register( new Widget_Modern_Image() );
		$widget_manager->register( new Widget_Accordion() );
		$widget_manager->register( new Widget_Blockquote() );
		$widget_manager->register( new Widget_Button() );
		$widget_manager->register( new Widget_Button_Scroll() );
		$widget_manager->register( new Widget_Button_Popup_Video() );
		$widget_manager->register( new Widget_Client_Logo() );
		$widget_manager->register( new Widget_Google_Map() );
		$widget_manager->register( new Widget_Heading() );
		$widget_manager->register( new Widget_Icon() );
		$widget_manager->register( new Widget_Icon_Box() );
		$widget_manager->register( new Widget_Animated_Icon_Box() );
		$widget_manager->register( new Widget_Image_Box() );
		$widget_manager->register( new Widget_Image_Layers() );
		$widget_manager->register( new Widget_Image_Gallery() );
		$widget_manager->register( new Widget_Image_Carousel() );
		$widget_manager->register( new Widget_Gallery_Justified_Content() );
		$widget_manager->register( new Widget_Banner() );
		$widget_manager->register( new Widget_Modern_Slider() );
		$widget_manager->register( new Widget_Blog() );
		$widget_manager->register( new Widget_Blog_Carousel() );
		$widget_manager->register( new Widget_Attribute_List() );
		$widget_manager->register( new Widget_List() );
		$widget_manager->register( new Widget_Marquee_List() );
		$widget_manager->register( new Widget_Countdown() );
		$widget_manager->register( new Widget_Team_Member() );
		$widget_manager->register( new Widget_Testimonial_Carousel() );
		$widget_manager->register( new Widget_Testimonial_Grid() );
		$widget_manager->register( new Widget_Testimonial_Slideshow() );
		$widget_manager->register( new Widget_Social_Networks() );
		$widget_manager->register( new Widget_Popup_Video() );
		$widget_manager->register( new Widget_Separator() );
		$widget_manager->register( new Widget_Tabs() );
		$widget_manager->register( new Widget_Counter() );
		$widget_manager->register( new Widget_Icon_Box_List() );
		$widget_manager->register( new Widget_Client_Box() );
		$widget_manager->register( new Widget_Rating_Box() );
		$widget_manager->register( new Widget_Testimonial() );
		$widget_manager->register( new Widget_Image_Box_Carousel() );
		$widget_manager->register( new Widget_Slider_Slideshow() );
		$widget_manager->register( new Widget_Parallax_Sliders() );
		$widget_manager->register( new Widget_Carousel_Nav_Buttons() );
		$widget_manager->register( new Widget_Typed_Headline() );
		$widget_manager->register( new Widget_Instagram() );
		$widget_manager->register( new Widget_Instagram_Carousel() );
		$widget_manager->register( new Widget_Simple_Link() );
		$widget_manager->register( new Widget_Simple_List() );
		$widget_manager->register( new Widget_Site_Logo() );
		$widget_manager->register( new Widget_Language_Switcher() );
		$widget_manager->register( new Widget_Currency_Switcher() );

		/**
		 * Include & Register Dependency Widgets.
		 */

		if ( \Minimog_Woo::instance()->is_activated() ) {
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/button-add-to-cart.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-carousel.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-list-carousel.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-grid-tabs.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-carousel-tabs.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/feature-product-carousel.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-carousel-countdown.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/feature-product.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-banner.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/products-slideshow.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-categories-carousel.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-categories-grid.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-categories-metro.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-categories-list.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-category.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-filter.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-title.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-images.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-price.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-description.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-add-to-cart.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-live-visitors.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-shipping.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-meta.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-data-tabs.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-related.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-upsell.php' );
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/single-product-recent-viewed.php' );

			$widget_manager->register( new Widget_Button_Add_To_Cart() );
			$widget_manager->register( new Widget_Product() );
			$widget_manager->register( new Widget_Product_Carousel() );
			$widget_manager->register( new Widget_Product_List_Carousel() );
			$widget_manager->register( new Widget_Product_Tabs() );
			$widget_manager->register( new Widget_Carousel_Product_Tabs() );
			$widget_manager->register( new Widget_Feature_Product_Carousel() );
			$widget_manager->register( new Widget_Product_Carousel_Countdown() );
			$widget_manager->register( new Widget_Feature_Product() );
			$widget_manager->register( new Widget_Product_Banner() );
			$widget_manager->register( new Widget_Products_Slideshow() );
			$widget_manager->register( new Widget_Product_Category_Carousel() );
			$widget_manager->register( new Widget_Product_Categories_Grid() );
			$widget_manager->register( new Widget_Product_Categories_Metro() );
			$widget_manager->register( new Widget_Product_Categories_List() );
			$widget_manager->register( new Widget_Product_Category() );
			$widget_manager->register( new Widget_Product_Filter() );
			$widget_manager->register( new Widget_Single_Product_Title() );
			$widget_manager->register( new Widget_Single_Product_Images() );
			$widget_manager->register( new Widget_Single_Product_Price() );
			$widget_manager->register( new Widget_Single_Product_Description() );
			$widget_manager->register( new Widget_Single_Product_Add_To_Cart() );
			$widget_manager->register( new Widget_Single_Product_Live_Visitors() );
			$widget_manager->register( new Widget_Single_Product_Shipping() );
			$widget_manager->register( new Widget_Single_Product_Meta() );
			$widget_manager->register( new Widget_Single_Product_Data_Tabs() );
			$widget_manager->register( new Widget_Single_Product_Related() );
			$widget_manager->register( new Widget_Single_Product_Upsell() );
			$widget_manager->register( new Widget_Single_Product_Recent_Viewed() );

			if ( class_exists( 'Insight_Product_Brands' ) ) {
				minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-brands.php' );
				minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-brands-carousel.php' );
				$widget_manager->register( new Widget_Product_Brands() );
				$widget_manager->register( new Widget_Product_Brands_Carousel() );
			}

			// Product Bundle
			if ( class_exists( 'WPCleverWoosb' ) ) {
				minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/product-bundle.php' );
				$widget_manager->register( new Widget_Product_Bundle() );
			}

			if ( class_exists( '\MABEL_SILITE\Shoppable_Images' ) ) {
				minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/shoppable-image.php' );
				$widget_manager->register( new Widget_Shoppable_Image() );
			}
		}

		if ( function_exists( 'mc4wp_get_forms' ) ) {
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/form/mailchimp-form.php' );

			$widget_manager->register( new Widget_Mailchimp_Form() );
		}

		if ( defined( 'WPCF7_VERSION' ) ) {
			minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/form/contact-form-7.php' );

			$widget_manager->register( new Widget_Contact_Form_7() );
		}

		/**
		 * Inactive - Need to check and delete in future
		 */
		//minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/posts/blog-category.php' );
		//minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/modern-carousel.php' );
		//minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/carousel/team-member-carousel.php' );
		//minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/full-page.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/table.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/shapes.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/flip-box.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/gradation.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/pricing-table.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/twitter.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/timeline.php' );
		//minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/circle-progress-chart.php' );

		//$widget_manager->register( new Widget_Blog_Category() );
		//$widget_manager->register( new Widget_Modern_Carousel() );
		//$widget_manager->register( new Widget_Team_Member_Carousel() );
		//$widget_manager->register( new Widget_Full_Page() );
		$widget_manager->register( new Widget_Table() );
		$widget_manager->register( new Widget_Shapes() );
		$widget_manager->register( new Widget_Flip_Box() );
		$widget_manager->register( new Widget_Gradation() );
		$widget_manager->register( new Widget_Timeline() );
		$widget_manager->register( new Widget_Pricing_Table() );
		$widget_manager->register( new Widget_Twitter() );
		//$widget_manager->register( new Widget_Circle_Progress_Chart() );
	}

	/**
	 * @param \Elementor\Widgets_Manager $widgets_manager
	 *
	 * Remove unwanted widgets
	 */
	function remove_unwanted_widgets( $widgets_manager ) {
		$elementor_widget_blacklist = array(
			'theme-site-logo',
		);

		foreach ( $elementor_widget_blacklist as $widget_name ) {
			$widgets_manager->unregister( $widget_name );
		}
	}

	/**
	 * @param \Elementor\Core\DynamicTags\Manager $tags_manager
	 */
	public function register_tags( $tags_manager ) {
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/tags/traits/tag-product-id.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/tags/base-tag.php' );
		minimog_require_file_once( MINIMOG_ELEMENTOR_DIR . '/widgets/woocommerce/tags/product-title.php' );

		$tags = [
			'Product_Title',
		];

		$tags_manager->register_group( 'woocommerce', [
			'title' => esc_html__( 'WooCommerce', 'minimog' ),
		] );

		foreach ( $tags as $tag ) {
			$tag = 'Minimog_Elementor\\Modules\\Woocommerce\\tags\\' . $tag;

			$tags_manager->register( new $tag() );
		}
	}
}

Widget_Init::instance()->initialize();
