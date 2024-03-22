<?php
defined( 'ABSPATH' ) || exit;

class Minimog_Google_Manager {

	protected static $instance = null;

	const GOOGLE_DRIVER_API = 'AIzaSyBQsxIg32Eg17Ic0tmRvv1tBZYrT9exCwk';

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	function initialize() {

	}

	public static function get_google_driver_url( $file_id ) {
		return "https://www.googleapis.com/drive/v3/files/{$file_id}?alt=media&key=" . self::GOOGLE_DRIVER_API;
	}
}

Minimog_Google_Manager::instance()->initialize();
