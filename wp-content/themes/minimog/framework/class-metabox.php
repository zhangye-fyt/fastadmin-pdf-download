<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_Metabox' ) ) {
	class Minimog_Metabox {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'insight_core_meta_boxes', [ $this, 'register_meta_boxes' ] );
		}

		/**
		 * Register Metabox
		 *
		 * @param $meta_boxes
		 *
		 * @return array
		 */
		public function register_meta_boxes( $meta_boxes ) {
			$page_registered_sidebars = Minimog_Helper::get_registered_sidebars( true );

			$general_options = array(
				array(
					'title'  => 'Layout',
					'fields' => array(
						array(
							'id'    => 'site_class',
							'type'  => 'text',
							'title' => 'Body Class',
							'desc'  => 'Add a class name to body then refer to it in custom CSS.',
						),
						array(
							'id'        => 'settings_preset',
							'type'      => 'select',
							'title'     => 'Settings Preset',
							'default'   => '',
							'options'   => [
								''                    => 'None',
								'home-fashion-v9'     => 'Home Fashion v9',
								'home-watch'          => 'Home Watch',
								'home-bra'            => 'Home Bra',
								'home-case-phone'     => 'Home Case Phone',
								'home-backpack'       => 'Home Backpack',
								'home-drink'          => 'Home Drink',
								'home-stationery'     => 'Home Stationery',
								'home-sneaker'        => 'Home Sneaker',
								'home-art'            => 'Home Art',
								'home-toy'            => 'Home Toy',
								'home-living'         => 'Home Living',
								'home-glasses'        => 'Home Glasses',
								'home-plants'         => 'Home Plants',
								'home-activewear'     => 'Home Activewear',
								'home-coffee'         => 'Home Coffee',
								'home-bedding'        => 'Home Bedding',
								'home-print'          => 'Home Print',
								'home-furniture'      => 'Home Furniture',
								'home-skateboard'     => 'Home Skateboard',
								'home-pizza'          => 'Home Pizza',
								'home-jewelry'        => 'Home Jewelry',
								'home-supplyment'     => 'Home Supplyment',
								'home-bag'            => 'Home Bag',
								'home-nail-polish'    => 'Home Nail',
								'home-baby'           => 'Home Baby',
								'home-socks'          => 'Home Socks',
								'home-juice'          => 'Home Juice',
								'home-barber'         => 'Home Barber',
								'home-beauty'         => 'Home Beauty',
								'home-mirror'         => 'Home Mirror',
								'home-electronic'     => 'Home Electronic',
								'home-houseware'      => 'Home Houseware',
								'home-book'           => 'Home Book',
								'home-hat'            => 'Home Hat',
								'home-hand-santizer'  => 'Home Hand Santizer',
								'home-bathroom'       => 'Home Bathroom',
								'home-skincare'       => 'Home Skincare',
								'home-candles'        => 'Home Candles',
								'home-organic'        => 'Home Organic',
								'home-paint'          => 'Home Paint',
								'home-pan'            => 'Home Pan',
								'home-pet'            => 'Home Pet',
								'home-pod'            => 'Home POD',
								'home-gym-supplyment' => 'Home Gym Supplyment',
								'home-speaker'        => 'Home Speaker',
								'home-postcard'       => 'Home Postcard',
								'home-christmas'      => 'Home Christmas',
								'home-bfcm'           => 'Home BFCM',
								'home-surfboard'      => 'Home Surfboard',
								'home-bike'           => 'Home Bike',
								'home-ceramic'        => 'Home Ceramic',
								'home-camping'        => 'Home Camping',
								'home-cake'           => 'Home Cake',
								'home-soap'           => 'Home Soap',
								'home-floral'         => 'Home Floral',
								'home-smart-light'    => 'Home Smart Light',
								'home-puppies'        => 'Home Puppies',
								'home-keyboard'       => 'Home Keyboard',
								'home-halloween'      => 'Home Halloween',
								'home-bfcm-coachella' => 'Home BFCM Coachella',
								'home-stroller'       => 'Home Stroller',
							],
							'wrap_class' => Minimog_Helper::is_demo_site() || Minimog_Helper::is_dev_mode() ? '' : 'hidden display-none',
						),
					),
				),
				array(
					'title'  => 'Site Background',
					'fields' => array(
						array(
							'id'    => 'site_background_color',
							'type'  => 'color',
							'title' => 'Background Color',
							'desc'  => 'Controls the background color of the outer background area in boxed mode of this page.',
						),
						array(
							'id'    => 'site_background_image',
							'type'  => 'media',
							'title' => 'Background Image',
							'desc'  => 'Controls the background image of the outer background area in boxed mode of this page.',
						),
						array(
							'id'      => 'site_background_repeat',
							'type'    => 'select',
							'title'   => 'Background Repeat',
							'desc'    => 'Controls the background repeat of the outer background area in boxed mode of this page.',
							'options' => array(
								'no-repeat' => 'No repeat',
								'repeat'    => 'Repeat',
								'repeat-x'  => 'Repeat X',
								'repeat-y'  => 'Repeat Y',
							),
						),
						array(
							'id'      => 'site_background_attachment',
							'type'    => 'select',
							'title'   => 'Background Attachment',
							'desc'    => 'Controls the background attachment of the outer background area in boxed mode of this page.',
							'options' => array(
								''       => 'Default',
								'fixed'  => 'Fixed',
								'scroll' => 'Scroll',
							),
						),
						array(
							'id'    => 'site_background_position',
							'type'  => 'text',
							'title' => 'Background Position',
							'desc'  => 'Controls the background position of the outer background area in boxed mode of this page.',
						),
						array(
							'id'    => 'site_background_size',
							'type'  => 'text',
							'title' => 'Background Size',
							'desc'  => 'Controls the background size of the outer background area in boxed mode of this page.',
						),
					),
				),
				array(
					'title'  => 'Top Bar',
					'fields' => array(
						array(
							'id'      => 'top_bar_type',
							'type'    => 'select',
							'title'   => 'Type',
							'desc'    => 'Select top bar type that displays on this page.',
							'default' => '',
							'options' => Minimog_Top_Bar::instance()->get_list( true ),
						),
						array(
							'id'      => 'top_bar_content_width',
							'type'    => 'select',
							'title'   => 'Content Width',
							'default' => '',
							'options' => Minimog_Site_Layout::instance()->get_container_wide_list( true ),
						),
						array(
							'id'    => 'top_bar_background_color',
							'type'  => 'color',
							'title' => 'Background',
						),
						array(
							'id'    => 'top_bar_text_color',
							'type'  => 'color',
							'title' => 'Text Color',
						),
						array(
							'id'    => 'top_bar_link_color',
							'type'  => 'color',
							'title' => 'Link Color',
						),
						array(
							'id'    => 'top_bar_link_hover_color',
							'type'  => 'color',
							'title' => 'Link Hover Color',
						),
						array(
							'id'    => 'top_bar_button_text_color',
							'type'  => 'color',
							'title' => 'Button Text Color',
						),
						array(
							'id'    => 'top_bar_button_background_color',
							'type'  => 'color',
							'title' => 'Button Background Color',
						),
						array(
							'id'    => 'top_bar_button_border_color',
							'type'  => 'color',
							'title' => 'Button Border Color',
						),
						array(
							'id'    => 'top_bar_button_hover_text_color',
							'type'  => 'color',
							'title' => 'Button Hover Text Color',
						),
						array(
							'id'    => 'top_bar_button_hover_background_color',
							'type'  => 'color',
							'title' => 'Button Hover Background Color',
						),
						array(
							'id'    => 'top_bar_button_hover_border_color',
							'type'  => 'color',
							'title' => 'Button Hover Border Color',
						),
						array(
							'id'      => 'top_bar_text',
							'type'    => 'textarea',
							'title'   => 'Custom Text',
							'desc'    => 'Custom top bar text. Leave blank to use default.',
							'default' => '',
						),
						array(
							'id'      => 'top_bar_text_style',
							'type'    => 'select',
							'title'   => 'Text Style',
							'default' => '',
							'options' => [
								''   => 'Default',
								'01' => sprintf( 'Style %s', '01' ),
							],
						),
					),
				),
				array(
					'title'  => 'Header',
					'fields' => array(
						array(
							'id'      => 'header_type',
							'type'    => 'select',
							'title'   => 'Header Type',
							'desc'    => 'Select header type that displays on this page.',
							'default' => '',
							'options' => Minimog_Header::instance()->get_list( true ),
						),
						array(
							'id'      => 'header_content_width',
							'type'    => 'select',
							'title'   => 'Content Width',
							'default' => '',
							'options' => Minimog_Site_Layout::instance()->get_container_wide_list( true ),
						),
						array(
							'id'      => 'header_above',
							'type'    => 'switch',
							'title'   => 'Header Above',
							'default' => '',
							'options' => array(
								''  => 'Default',
								'0' => 'Hide',
								'1' => 'Show',
							),
						),
						array(
							'id'      => 'header_overlay',
							'type'    => 'switch',
							'title'   => 'Header Overlay',
							'default' => '',
							'options' => array(
								''  => 'Default',
								'0' => 'No',
								'1' => 'Yes',
							),
						),
						array(
							'id'      => 'header_skin',
							'type'    => 'switch',
							'title'   => 'Header Skin',
							'default' => '',
							'options' => array(
								''      => 'Default',
								'dark'  => 'Dark',
								'light' => 'Light',
							),
						),
						array(
							'id'      => 'header_shadow',
							'type'    => 'switch',
							'title'   => 'Header Shadow',
							'default' => '',
							'options' => array(
								''     => 'Default',
								'none' => 'None',
							),
						),
						array(
							'id'      => 'header_background',
							'type'    => 'switch',
							'title'   => 'Header Background',
							'default' => '',
							'options' => array(
								''     => 'Default',
								'none' => 'None',
							),
						),
						array(
							'id'      => 'menu_display',
							'type'    => 'select',
							'title'   => 'Primary menu',
							'desc'    => 'Select which menu displays on this page.',
							'default' => '',
							'options' => Minimog_Nav_Menu::get_all_menus(),
						),
						array(
							'id'      => 'menu_one_page',
							'type'    => 'switch',
							'title'   => 'One Page Menu',
							'default' => '0',
							'options' => array(
								'0' => 'Disable',
								'1' => 'Enable',
							),
						),
					),
				),
				array(
					'title'  => 'Page Title Bar',
					'fields' => array(
						array(
							'id'      => 'page_title_bar_layout',
							'type'    => 'select',
							'title'   => 'Layout',
							'default' => '',
							'options' => Minimog_Title_Bar::instance()->get_list( true ),
						),
						array(
							'id'      => 'page_title_bar_container_size',
							'type'    => 'select',
							'title'   => 'Container Size',
							'default' => '',
							'options' => Minimog_Site_Layout::instance()->get_container_wide_list( true ),
						),
						array(
							'id'    => 'page_title_bar_bottom_spacing',
							'type'  => 'text',
							'title' => 'Spacing',
							'desc'  => 'Controls the bottom spacing of title bar of this page. Enter value including any valid CSS unit. For e.g: 50px. Leave blank to use global setting.',
						),
						array(
							'id'      => 'page_title_bar_background_color',
							'type'    => 'color',
							'title'   => 'Background Color',
							'default' => '',
						),
						array(
							'id'      => 'page_title_bar_background',
							'type'    => 'media',
							'title'   => 'Background Image',
							'default' => '',
						),
						array(
							'id'      => 'page_title_bar_background_overlay',
							'type'    => 'color',
							'title'   => 'Background Overlay',
							'default' => '',
						),
						array(
							'id'    => 'page_title_bar_custom_heading',
							'type'  => 'text',
							'title' => 'Custom Heading Text',
							'desc'  => 'Insert custom heading for the page title bar. Leave blank to use default.',
						),
					),
				),
				array(
					'title'  => 'Sidebars',
					'fields' => array(
						array(
							'id'      => 'page_sidebar_1',
							'type'    => 'select',
							'title'   => 'Sidebar 1',
							'desc'    => 'Select sidebar 1 that will display on this page.',
							'default' => 'default',
							'options' => $page_registered_sidebars,
						),
						array(
							'id'      => 'page_sidebar_2',
							'type'    => 'select',
							'title'   => 'Sidebar 2',
							'desc'    => 'Select sidebar 2 that will display on this page.',
							'default' => 'default',
							'options' => $page_registered_sidebars,
						),
						array(
							'id'      => 'page_sidebar_position',
							'type'    => 'switch',
							'title'   => 'Sidebar Position',
							'desc'    => 'Select position of Sidebar 1 for this page. If sidebar 2 is selected, it will display on the opposite side.',
							'default' => 'default',
							'options' => Minimog_Helper::get_list_sidebar_positions( true ),
						),
						array(
							'id'      => 'page_single_sidebar_width',
							'type'    => 'text',
							'title'   => 'Single Sidebar Width',
							'desc'    => 'Controls the width of the sidebar when only one sidebar is present. Input value as % unit. For e.g: 33.33333. Leave blank to use global setting.',
							'default' => '',
						),
						array(
							'id'      => 'page_single_sidebar_offset',
							'type'    => 'text',
							'title'   => 'Single Sidebar Offset',
							'desc'    => 'Controls the offset of the sidebar when only one sidebar is present. Enter value including any valid CSS unit. For e.g: 70px. Leave blank to use global setting.',
							'default' => '',
						),
						array(
							'id'      => 'page_sidebar_style',
							'type'    => 'select',
							'title'   => 'Sidebar Style',
							'default' => '',
							'options' => Minimog_Sidebar::instance()->get_supported_style_options( [ 'default' => true ] ),
						),
					),
				),
				array(
					'title'  => 'Sliders',
					'fields' => array(
						array(
							'id'      => 'revolution_slider',
							'type'    => 'select',
							'title'   => 'Revolution Slider',
							'desc'    => 'Select the unique name of the slider.',
							'options' => Minimog_Helper::get_list_revslider(),
						),
						array(
							'id'      => 'slider_position',
							'type'    => 'select',
							'title'   => 'Slider Position',
							'default' => 'below',
							'options' => array(
								'above' => 'Above Header',
								'below' => 'Below Header',
							),
						),
					),
				),
				array(
					'title'  => 'Footer',
					'fields' => array(
						array(
							'id'      => 'footer_enable',
							'type'    => 'select',
							'title'   => 'Footer Enable',
							'default' => '',
							'options' => array(
								''     => 'Yes',
								'none' => 'No',
							),
						),
					),
				),
			);

			// Page.
			$meta_boxes[] = array(
				'id'         => 'insight_page_options',
				'title'      => 'Page Options',
				'post_types' => array( 'page' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'fields'     => array(
					array(
						'type'  => 'tabpanel',
						'items' => $general_options,
					),
				),
			);

			// Post.
			$meta_boxes[] = array(
				'id'         => 'insight_post_options',
				'title'      => 'Page Options',
				'post_types' => array( 'post' ),
				'context'    => 'normal',
				'priority'   => 'high',
				'fields'     => array(
					array(
						'type'  => 'tabpanel',
						'items' => array_merge( array(
							array(
								'title'  => 'Post',
								'fields' => array(
									array(
										'id'      => 'post_entry_feature',
										'type'    => 'switch',
										'title'   => 'Featured Image',
										'default' => 'default',
										'options' => [
											'default' => 'Default',
											'1'       => 'Show',
											'0'       => 'Hide',
										],
									),
									array(
										'id'    => 'post_gallery',
										'type'  => 'gallery',
										'title' => 'Gallery Format',
									),
									array(
										'id'    => 'post_video',
										'type'  => 'text',
										'title' => 'Video URL',
										'desc'  => 'Input the url of video vimeo or youtube. For e.g: https://www.youtube.com/watch?v=9No-FiEInLA',
									),
									array(
										'id'    => 'post_audio',
										'type'  => 'textarea',
										'title' => 'Audio Format',
									),
									array(
										'id'    => 'post_quote_text',
										'type'  => 'text',
										'title' => 'Quote Format - Source Text',
									),
									array(
										'id'    => 'post_quote_name',
										'type'  => 'text',
										'title' => 'Quote Format - Source Name',
									),
									array(
										'id'    => 'post_quote_position',
										'type'  => 'text',
										'title' => 'Quote Format - Source Position',
									),
									array(
										'id'    => 'post_quote_url',
										'type'  => 'text',
										'title' => 'Quote Format - Source Url',
									),
									array(
										'id'    => 'post_link',
										'type'  => 'text',
										'title' => 'Link Format',
									),
								),
							),
						), $general_options ),
					),
				),
			);

			return apply_filters( 'minimog/meta_box/page_options', $meta_boxes, $general_options );
		}

	}

	Minimog_Metabox::instance()->initialize();
}
