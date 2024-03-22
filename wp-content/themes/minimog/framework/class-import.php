<?php
defined( 'ABSPATH' ) || exit;

/**
 * Initial OneClick import for this theme
 */
if ( ! class_exists( 'Minimog_Import' ) ) {
	class Minimog_Import {

		protected static $instance = null;

		public static function instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function initialize() {
			add_filter( 'insight_core_import_demos', [ $this, 'import_demos' ] );
			add_filter( 'insight_core_import_generate_thumb', '__return_false' );
			add_filter( 'insight_core_import_delete_exist_posts', '__return_true' );

			add_action( 'insight_core_importer_dispatch_after', [ $this, 'delete_attachment_cropped_info' ] );
			add_action( 'insight_core_importer_dispatch_after', [ $this, 'update_links' ] );
			add_action( 'insight_core_importer_dispatch_after', [ $this, 'update_theme_options' ] );
		}

		public function import_demos() {
			$import_img_url = MINIMOG_THEME_URI . '/assets/import';

			return [
				'fashion1'       => [
					'screenshot'  => $import_img_url . '/fashion1/preview.jpg',
					'name'        => 'Fashion Store',
					'description' => 'Package includes: 
						<a href="https://minimog.thememove.com/" target="_blank">Fashion v1</a>, 
						<a href="https://minimog.thememove.com/home-v2/" target="_blank">Fashion v2</a>, 
						<a href="https://minimog.thememove.com/home-v3/" target="_blank">Fashion v3</a>, 
						<a href="https://minimog.thememove.com/home-v4/" target="_blank">Fashion v4</a>, 
						<a href="https://minimog.thememove.com/home-v5/" target="_blank">Fashion v5</a>, 
						<a href="https://minimog.thememove.com/home-v6/" target="_blank">Fashion v6</a>, 
						<a href="https://minimog.thememove.com/home-v7/" target="_blank">Fashion v7</a>, 
						<a href="https://minimog.thememove.com/home-v8/" target="_blank">Fashion v8</a>, 
						<a href="https://minimog.thememove.com/home-v9/" target="_blank">Fashion v9</a>, 
						<a href="https://minimog.thememove.com/home-v10/" target="_blank">Fashion v10</a>, 
						<a href="https://minimog.thememove.com/home-v11/" target="_blank">Fashion v11</a>,
						<a href="https://minimog.thememove.com/home-v12/" target="_blank">Fashion v12</a>,
						<a href="https://minimog.thememove.com/home-v13/" target="_blank">Fashion v13</a> 
						',
					'url'         => 'https://www.dropbox.com/s/6k38wnug2o3claa/minimog-insightcore-fashion1-1.0.zip?dl=1',
				],
				'fashion2'       => [
					'screenshot'  => $import_img_url . '/fashion2/preview.jpg',
					'name'        => 'Fashion Store 2',
					'description' => 'Package includes: 
						<a href="https://minimog.thememove.com/home-sock" target="_blank">Sock</a>, 
						<a href="https://minimog.thememove.com/home-pod" target="_blank">POD</a>, 
						<a href="https://minimog.thememove.com/home-bra" target="_blank">Bra</a>, 
						<a href="https://minimog.thememove.com/home-sneaker" target="_blank">Sneaker</a>, 
						<a href="https://minimog.thememove.com/home-activewear" target="_blank">Activewear</a>, 
						<a href="https://minimog.thememove.com/home-baby-store" target="_blank">Baby</a>, 
						<a href="https://minimog.thememove.com/home-toy" target="_blank">Toy</a>
						',
					'url'         => 'https://www.dropbox.com/s/rxdo1wx2gjwcj0i/minimog-insightcore-fashion2-1.0.zip?dl=1',
				],
				'accessories'    => [
					'screenshot'  => $import_img_url . '/accessories/preview.jpg',
					'name'        => 'Accessories Store',
					'description' => 'Package includes: 
						<a href="https://minimog.thememove.com/home-jewelry" target="_blank">Jewelry</a>, 
						<a href="https://minimog.thememove.com/home-bag" target="_blank">Bag</a>, 
						<a href="https://minimog.thememove.com/home-hat" target="_blank">Hat</a>, 
						<a href="https://minimog.thememove.com/home-glasses" target="_blank">Glasses</a>, 
						<a href="https://minimog.thememove.com/home-watch" target="_blank">Watch</a>, 
						<a href="https://minimog.thememove.com/home-case-phone" target="_blank">Phone Case</a>, 
						<a href="https://minimog.thememove.com/home-backpack" target="_blank">Backpack</a>
						',
					'url'         => 'https://www.dropbox.com/s/hinp7mejac89b1s/minimog-insightcore-accessories-1.0.zip?dl=1',
				],
				'food-drink'     => [
					'screenshot'  => $import_img_url . '/food-drink/preview.jpg',
					'name'        => 'Food & Drink',
					'description' => 'Package includes: 
						<a href="https://minimog.thememove.com/home-coffee" target="_blank">Coffee</a>, 
						<a href="https://minimog.thememove.com/home-juice" target="_blank">Juice</a>, 
						<a href="https://minimog.thememove.com/home-supplyment" target="_blank">Supplyment</a>, 
						<a href="https://minimog.thememove.com/home-pizza" target="_blank">Pizza</a>, 
						<a href="https://minimog.thememove.com/home-drink" target="_blank">Drink</a>, 
						<a href="https://minimog.thememove.com/home-organic" target="_blank">Organic</a>
						',
					'url'         => 'https://www.dropbox.com/s/cc72fbxmmthac9n/minimog-insightcore-food-drink-1.0.zip?dl=1',
				],
				'furniture-home' => [
					'screenshot'  => $import_img_url . '/furniture-home/preview.jpg',
					'name'        => 'Furniture & Home',
					'description' => 'Package includes:
						<a href="https://minimog.thememove.com/home-houseware" target="_blank">Houseware</a>, 
						<a href="https://minimog.thememove.com/home-furniture" target="_blank">Furniture</a>, 
						<a href="https://minimog.thememove.com/home-living" target="_blank">Living</a>, 
						<a href="https://minimog.thememove.com/home-bedding" target="_blank">Bedding</a>, 
						<a href="https://minimog.thememove.com/home-bathroom" target="_blank">Bathroom</a>, 
						<a href="https://minimog.thememove.com/home-mirror" target="_blank">Mirror</a>, 
						<a href="https://minimog.thememove.com/home-paint" target="_blank">Paint</a>, 
						<a href="https://minimog.thememove.com/home-pan" target="_blank">Pan</a> 
						',
					'url'         => 'https://www.dropbox.com/s/6nrmylwcdexwosi/minimog-insightcore-furniture-home-1.0.zip?dl=1',
				],
				'beauty'         => [
					'screenshot'  => $import_img_url . '/beauty/preview.jpg',
					'name'        => 'Beauty',
					'description' => 'Package includes:
					    <a href="https://minimog.thememove.com/home-nail-polish" target="_blank">Nail Polish</a>, 
						<a href="https://minimog.thememove.com/home-skincare" target="_blank">Skincare</a>, 
						<a href="https://minimog.thememove.com/home-beauty" target="_blank">Beauty</a>, 
						<a href="https://minimog.thememove.com/home-gym-supplyment" target="_blank">Gym</a>, 
						<a href="https://minimog.thememove.com/home-hand-santizer" target="_blank">Hand Santizer</a>, 
						<a href="https://minimog.thememove.com/home-barber" target="_blank">Barber</a> 
					 ',
					'url'         => 'https://www.dropbox.com/s/ll44smrhcewexwr/minimog-insightcore-beauty-1.0.zip?dl=1',
				],
				'others'         => [
					'screenshot'  => $import_img_url . '/others/preview.jpg',
					'name'        => 'Multi-goods Store',
					'description' => 'Package includes:
						<a href="https://minimog.thememove.com/home-book" target="_blank">Book</a>, 
						<a href="https://minimog.thememove.com/home-stationery" target="_blank">Stationery</a>, 
						<a href="https://minimog.thememove.com/home-plants" target="_blank">Plants</a>, 
						<a href="https://minimog.thememove.com/home-art" target="_blank">Art</a>, 
						<a href="https://minimog.thememove.com/home-skateboard" target="_blank">Skateboard</a>, 
						<a href="https://minimog.thememove.com/home-print" target="_blank">Print</a>, 
						<a href="https://minimog.thememove.com/home-candles" target="_blank">Candles</a>, 
						<a href="https://minimog.thememove.com/home-pet" target="_blank">Pet</a>, 
						<a href="https://minimog.thememove.com/home-electronic" target="_blank">Electronic</a>  
						',
					'url'         => 'https://www.dropbox.com/s/iu4wxti9x1rkkst/minimog-insightcore-others-1.0.zip?dl=1',
				],
				/*'main'       => [
					'screenshot'  => $import_img_url . '/main/preview.jpg',
					'name'        => 'Main Store',
					'description' => 'Include 56 homepages and all inner pages.',
					'preview_url' => 'https://minimog.thememove.com',
					'url'         => Minimog_Google_Manager::get_google_driver_url( '1RO1dtGLGgPtYewsJlJjMWOqlTjmK_lRw' ),
				],*/
				'supergear'      => [
					'screenshot'  => $import_img_url . '/supergear/preview.jpg',
					'name'        => 'Supergear Store',
					'description' => 'Package includes: <a href="https://minimog.thememove.com/supergear" target="_blank">Supergear</a>',
					'url'         => Minimog_Google_Manager::get_google_driver_url( '1Y583LWp2tmLBo3YnncX-v0H3GxJN21qx' ),
				],
				'megamog'        => [
					'screenshot'  => $import_img_url . '/megamog/preview.jpg',
					'name'        => 'Megamog Store',
					'description' => 'Package includes: <a href="https://minimog.thememove.com/megamog" target="_blank">Megamog</a>',
					'url'         => Minimog_Google_Manager::get_google_driver_url( '1sVfoKVEY9ILGO84Ons0QaRt33bsbcU2J' ),
				],
				'megastore'      => [
					'screenshot'  => $import_img_url . '/megastore/preview.jpg',
					'name'        => 'Mega Store',
					'description' => 'Package includes: <a href="https://minimog.thememove.com/megastore" target="_blank">Mega Store</a>',
					'url'         => Minimog_Google_Manager::get_google_driver_url( '1wB5Ho4PzKN__RkNA2h2TYTCGN4DQGviE' ),
				],
				'rtl'            => [
					'screenshot'  => $import_img_url . '/rtl/preview.jpg',
					'name'        => 'RTL Demo',
					'description' => 'Package includes: <a href="https://minimog.thememove.com/rtl" target="_blank">RTL</a>',
					'url'         => 'https://www.dropbox.com/s/dsnjixz5zyoirne/minimog-insightcore-rtl-2.2.0.zip?dl=1',
				],
				'autopart'       => [
					'screenshot'  => $import_img_url . '/autopart/preview.jpg',
					'name'        => 'Autopart Store',
					'description' => 'Package includes: <a href="https://minimog.thememove.com/autopart" target="_blank">Autopart</a>',
					'url'         => 'https://www.dropbox.com/s/gplytrlo2em9g9r/minimog-insightcore-autopart-2.0.zip?dl=1',
				],
				'next'           => [
					'screenshot'  => $import_img_url . '/next/preview.jpg',
					'name'        => 'Next Store',
					'description' => 'Package includes: 
						<a href="https://minimog.thememove.com/next" target="_blank">Speaker</a>, 
						<a href="https://minimog.thememove.com/next/home-bfcm" target="_blank">BFCM</a>, 
						<a href="https://minimog.thememove.com/next/home-bike" target="_blank">Bike</a>, 
						<a href="https://minimog.thememove.com/next/home-cake" target="_blank">Cake</a>, 
						<a href="https://minimog.thememove.com/next/home-camping" target="_blank">Camping</a>, 
						<a href="https://minimog.thememove.com/next/home-ceramic" target="_blank">Ceramic</a>, 
						<a href="https://minimog.thememove.com/next/home-christmas" target="_blank">Christmas</a>, 
						<a href="https://minimog.thememove.com/next/home-floral" target="_blank">Floral</a>, 
						<a href="https://minimog.thememove.com/next/home-keyboard" target="_blank">Keyboard</a>, 
						<a href="https://minimog.thememove.com/next/home-postcard" target="_blank">Postcard</a>, 
						<a href="https://minimog.thememove.com/next/home-puppies" target="_blank">Puppies</a>, 
						<a href="https://minimog.thememove.com/next/home-smart-light" target="_blank">Smart Light</a>, 
						<a href="https://minimog.thememove.com/next/home-soap" target="_blank">Soap</a>, 
						<a href="https://minimog.thememove.com/next/home-surfboard" target="_blank">Surfboard</a>
						',
					'url'         => 'https://www.dropbox.com/s/tivvoos80l36whl/minimog-insightcore-next.zip?dl=1',
				],
				'robust'         => [
					'screenshot'  => $import_img_url . '/robust/preview.jpg',
					'name'        => 'Robust Store',
					'description' => 'Package includes:
						<a href="https://minimog.thememove.com/robust" target="_blank">Halloween</a>,  
						<a href="https://minimog.thememove.com/robust/home-bfcm-coachella" target="_blank">BFCM Coachella</a>,  
						<a href="https://minimog.thememove.com/robust/home-stroller" target="_blank">Stroller</a> 
						',
					'url'         => 'https://www.dropbox.com/s/torjda1syeo4143/minimog-insightcore-robust-2.0.zip?dl=1',
				],
			];
		}

		/**
		 * Images package has no cropped images then
		 * need delete cropped data to crop attachment again.
		 */
		public function delete_attachment_cropped_info() {
			Minimog_Attachment::instance()->delete_all_cropped_info();
		}

		/**
		 * Fix links in Elementor after import
		 *
		 * @param $importer
		 */
		public function update_links( $importer ) {
			if ( ! isset( $importer->demo ) ) {
				return;
			}

			$demo_info = $this->get_demo_imported_url( $importer->demo );

			if ( empty( $demo_info ) ) {
				return;
			}

			// First replace WP upload dir.
			$old_upload_dir = $demo_info['upload_dir'];
			$wp_upload_dir  = wp_upload_dir();
			$new_upload_dir = $wp_upload_dir['baseurl'];

			$result = $this->replace_url( $old_upload_dir, $new_upload_dir );

			// Finally replace all other links.
			$from = $demo_info['site_url'];
			$to   = home_url();

			$result = $this->replace_url( $from, $to );
		}

		public function update_theme_options( $importer ) {
			$json_file   = MINIMOG_THEME_DIR . '/assets/import/' . $importer->demo . '/redux_options.json';
			$option_name = class_exists( 'Minimog_Redux' ) ? Minimog_Redux::OPTION_NAME : '';

			if ( ! empty( $json_file ) && file_exists( $json_file ) && ! empty( $option_name ) ) {
				global $wp_filesystem;

				minimog_require_file_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();

				$file_content = $wp_filesystem->get_contents( $json_file );
				$options      = json_decode( $file_content, true );

				if ( is_array( $options ) && ! empty( $options ) ) {
					// Change url from placeholder to current site.
					$home_url = home_url();
					foreach ( $options as $key => $option ) {
						if ( ! empty( $option['url'] ) && is_string( $option['url'] ) ) {
							$value = $option['url'];

							$option['url'] = str_replace( '%SITE_URL%', $home_url, $value );

							$options[ $key ] = $option;
						}
					}

					update_option( $option_name, $options );
				}
			}
		}

		public function get_demo_imported_url( $imported_demo ) {
			$demos = [
				'main'      => [
					'site_id' => 1,
				],
				'supergear' => [
					'site_id' => 2,
				],
				'megamog'   => [
					'site_id' => 3,
				],
				'megastore' => [
					'site_id' => 4,
				],
				'rtl'       => [
					'site_id' => 5,
				],
				'next'      => [
					'site_id' => 6,
				],
				'robust'    => [
					'site_id' => 7,
				],
				'autopart'  => [
					'site_id' => 8,
				],
			];

			$demos_from_import_site = [
				'fashion1'       => [
					'site_id' => 2,
				],
				'fashion2'       => [
					'site_id' => 3,
				],
				'accessories'    => [
					'site_id' => 4,
				],
				'food-drink'     => [
					'site_id' => 6,
				],
				'furniture-home' => [
					'site_id' => 10,
				],
				'beauty'         => [
					'site_id' => 11,
				],
				'others'         => [
					'site_id' => 12,
				],
			];

			foreach ( $demos as $demo_name => $demo_info ) {
				if ( $imported_demo === $demo_name ) {
					if ( 1 === $demo_info['site_id'] ) {
						return [
							'site_url'   => "https://minimog.thememove.com",
							'upload_dir' => "https://minimog.thememove.com/wp-content/uploads",
						];
					} else {
						return [
							'site_url'   => "https://minimog.thememove.com/{$demo_name}",
							'upload_dir' => "https://minimog.thememove.com/{$demo_name}/wp-content/uploads/sites/{$demo_info['site_id']}",
						];
					}
				}
			}

			foreach ( $demos_from_import_site as $demo_name => $demo_info ) {
				if ( $imported_demo === $demo_name ) {
					if ( 1 === $demo_info['site_id'] ) {
						return [
							'site_url'   => "https://minimog-import.thememove.com",
							'upload_dir' => "https://minimog-import.thememove.com/wp-content/uploads",
						];
					} else {
						return [
							'site_url'   => "https://minimog-import.thememove.com/{$demo_name}",
							'upload_dir' => "https://minimog-import.thememove.com/{$demo_name}/wp-content/uploads/sites/{$demo_info['site_id']}",
						];
					}
				}
			}

			return false;
		}

		public function replace_url( $from, $to ) {
			$is_valid_urls = ( filter_var( $from, FILTER_VALIDATE_URL ) && filter_var( $to, FILTER_VALIDATE_URL ) );
			if ( ! $is_valid_urls ) {
				return false;
			}
			global $wpdb;

			// @codingStandardsIgnoreStart cannot use `$wpdb->prepare` because it remove's the backslashes
			$rows_affected = $wpdb->query(
				"UPDATE {$wpdb->postmeta} " .
				"SET `meta_value` = REPLACE(`meta_value`, '" . str_replace( '/', '\\\/', $from ) . "', '" . str_replace( '/', '\\\/', $to ) . "') " .
				"WHERE `meta_key` = '_elementor_data' AND `meta_value` LIKE '[%' ;" ); // meta_value LIKE '[%' are json formatted
			// @codingStandardsIgnoreEnd

			return $rows_affected;
		}
	}

	Minimog_Import::instance()->initialize();
}
