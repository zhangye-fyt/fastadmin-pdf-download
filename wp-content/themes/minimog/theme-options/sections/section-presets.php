<?php

Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'  => esc_html__( 'Settings Presets', 'minimog' ),
	'id'     => 'panel_settings_presets',
	'icon'   => 'eicon-wrench',
	'fields' => array(
		array(
			'id'       => 'settings_presets',
			'type'     => 'image_select',
			'presets'  => true,
			//'tiles'    => true,
			'title'    => esc_html__( 'Settings Presets', 'minimog' ),
			'subtitle' => esc_html__( 'This will overwrite all necessary settings to make your site like demo you chosen. Please export current settings to restore again.', 'minimog' ),
			'default'  => 0,
			'class'   => 'redux-row-block',
			'options'  => array(
				'megamog'             => array(
					'title'   => 'Megamog',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/megamog.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'megamog' ),
				),
				'supergear'           => array(
					'title'   => 'Supergear',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/supergear.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'supergear' ),
				),
				'megastore'           => array(
					'title'   => 'MegaStore',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/megastore.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'megastore' ),
				),
				'autopart'            => array(
					'title'   => 'Autopart',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/autopart.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'autopart' ),
				),
				'rtl'                 => array(
					'title'   => 'Home RTL',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/rtl.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'rtl' ),
				),
				'home-watch'          => array(
					'title'   => 'Home Watch',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-watch.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-watch' ),
				),
				'home-bra'            => array(
					'title'   => 'Home Bra',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-bra.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-bra' ),
				),
				'home-case-phone'     => array(
					'title'   => 'Home Case Phone',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-case-phone.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-case-phone' ),
				),
				'home-backpack'       => array(
					'title'   => 'Home Backpack',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-backpack.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-backpack' ),
				),
				'home-drink'          => array(
					'title'   => 'Home Drink',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-drink.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-drink' ),
				),
				'home-stationery'     => array(
					'title'   => 'Home Stationery',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-stationery.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-stationery' ),
				),
				'home-sneaker'        => array(
					'title'   => 'Home Sneaker',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-sneaker.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-sneaker' ),
				),
				'home-art'            => array(
					'title'   => 'Home Art',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-art.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-art' ),
				),
				'home-toy'            => array(
					'title'   => 'Home Toy',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-toy.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-toy' ),
				),
				'home-living'     => array(
					'title'   => 'Home Living',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-living.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-living' ),
				),
				'home-glasses'    => array(
					'title'   => 'Home Glasses',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-glasses.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-glasses' ),
				),
				'home-plants'     => array(
					'title'   => 'Home Plants',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-plants.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-plants' ),
				),
				'home-coffee'        => array(
					'title'   => 'Home Coffee',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-coffee.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-coffee' ),
				),
				'home-bedding'       => array(
					'title'   => 'Home Bedding',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-bedding.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-bedding' ),
				),
				'home-print'         => array(
					'title'   => 'Home Print',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-print.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-print' ),
				),
				'home-activewear'    => array(
					'title'   => 'Home Activewear',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-activewear.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-activewear' ),
				),
				'home-furniture'     => array(
					'title'   => 'Home Furniture',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-furniture.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-furniture' ),
				),
				'home-skateboard'    => array(
					'title'   => 'Home Skateboard',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-skateboard.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-skateboard' ),
				),
				'home-pizza'         => array(
					'title'   => 'Home Pizza',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-pizza.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-pizza' ),
				),
				'home-jewelry'       => array(
					'title'   => 'Home Jewelry',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-jewelry.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-jewelry' ),
				),
				'home-supplyment'    => array(
					'title'   => 'Home Supplyment',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-supplyment.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-supplyment' ),
				),
				'home-bag'           => array(
					'title'   => 'Home Bag',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-bag.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-bag' ),
				),
				'home-nail-polish'   => array(
					'title'   => 'Home Nail Polish',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-nail-polish.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-nail-polish' ),
				),
				'home-baby'          => array(
					'title'   => 'Home Baby',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-baby.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-baby' ),
				),
				'home-socks'         => array(
					'title'   => 'Home Socks',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-socks.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-socks' ),
				),
				'home-juice'         => array(
					'title'   => 'Home Juice',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-juice.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-juice' ),
				),
				'home-barber'        => array(
					'title'   => 'Home Barber',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-barber.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-barber' ),
				),
				'home-beauty'        => array(
					'title'   => 'Home Beauty',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-beauty.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-beauty' ),
				),
				'home-mirror'        => array(
					'title'   => 'Home Mirror',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-mirror.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-mirror' ),
				),
				'home-electronic'    => array(
					'title'   => 'Home Electronic',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-electronic.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-electronic' ),
				),
				'home-houseware'     => array(
					'title'   => 'Home Houseware',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-houseware.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-houseware' ),
				),
				'home-book'          => array(
					'title'   => 'Home Book',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-book.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-book' ),
				),
				'home-hat'           => array(
					'title'   => 'Home Hat',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-hat.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-hat' ),
				),
				'home-hand-santizer' => array(
					'title'   => 'Home Hand Sanitizer',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-hand-sanitizer.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-hand-santizer' ),
				),
				'home-bathroom'      => array(
					'title'   => 'Home Bathroom',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-bathroom.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-bathroom' ),
				),
				'home-skincare'      => array(
					'title'   => 'Home Skincare',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-skincare.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-skincare' ),
				),

				'home-candles'        => array(
					'title'   => 'Home Candles',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-candles.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-candles' ),
				),
				'home-organic'        => array(
					'title'   => 'Home Organic',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-organic.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-organic' ),
				),
				'home-pet'            => array(
					'title'   => 'Home Pet',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-pet.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-pet' ),
				),
				'home-pan'            => array(
					'title'   => 'Home Pan',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-pan.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-pan' ),
				),
				'home-paint'          => array(
					'title'   => 'Home paint',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-paint.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-paint' ),
				),
				'home-pod'            => array(
					'title'   => 'Home POD',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-pod.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-pod' ),
				),
				'home-gym-supplyment' => array(
					'title'   => 'Home Gym Supplyment',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-gym-supplyment.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-gym-supplyment' ),
				),
				'home-speaker'        => array(
					'title'   => 'Home Speaker',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-speaker.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-speaker' ),
				),
				'home-postcard'       => array(
					'title'   => 'Home Postcard',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-postcard.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-postcard' ),
				),
				'home-christmas'      => array(
					'title'   => 'Home Christmas',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-christmas.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-christmas' ),
				),
				'home-bfcm'           => array(
					'title'   => 'Home BFCM',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-bfcm.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-bfcm' ),
				),
				'home-surfboard'      => array(
					'title'   => 'Home Surfboard',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/surfboard.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-surfboard' ),
				),
				'home-bike'           => array(
					'title'   => 'Home Bike',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/bike.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-bike' ),
				),
				'home-ceramic'        => array(
					'title'   => 'Home Ceramic',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/ceramic.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-ceramic' ),
				),
				'home-camping'        => array(
					'title'   => 'Home Camping',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/camping.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-camping' ),
				),
				'home-cake'           => array(
					'title'   => 'Home Cake',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/cake.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-cake' ),
				),
				'home-soap'           => array(
					'title'   => 'Home Soap',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/soap.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-soap' ),
				),
				'home-floral'         => array(
					'title'   => 'Home Floral',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/floral.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-floral' ),
				),
				'home-smart-light'    => array(
					'title'   => 'Home Smart Light',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/smart-light.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-smart-light' ),
				),
				'home-puppies'        => array(
					'title'   => 'Home Pet Clothes',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/pet-clothes.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-puppies' ),
				),
				'home-keyboard'       => array(
					'title'   => 'Home Keyboard',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/keyboard.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-keyboard' ),
				),
				'home-halloween'      => array(
					'title'   => 'Home Halloween',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-halloween.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-halloween' ),
				),
				'home-bfcm-coachella' => array(
					'title'   => 'Home BFCM Coachella',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-bfcm-coachella.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-bfcm-coachella' ),
				),
				'home-stroller'       => array(
					'title'   => 'Home Stroller',
					'img'     => MINIMOG_THEME_IMAGE_URI . '/home-preview/home-stroller.jpg',
					'presets' => Minimog_Redux_Presets::get_settings( 'home-stroller' ),
				),
			),
		),
	),
) );
