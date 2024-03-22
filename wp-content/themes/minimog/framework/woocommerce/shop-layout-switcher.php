<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Layout_Switcher {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'woocommerce_before_shop_loop', [ $this, 'add_switcher_button' ], 50 );
	}

	public function add_switcher_button() {
		if ( '1' !== \Minimog::setting( 'shop_archive_layout_switcher' ) ) {
			return;
		}

		// Disabled in shortcodes.
		if ( wc_get_loop_prop( 'is_shortcode' ) && wc_get_loop_prop( 'is_paginated' ) ) {
			return;
		}

		$item_base_class = 'switcher-item hint--bounce hint--top';

		$layouts = [
			'grid-one'   => [
				'name'    => __( 'List', 'minimog' ),
				'columns' => 1,
			],
			'grid-two'   => [
				'name'    => sprintf( _n( '%s column', '%s columns', 2, 'minimog' ), 2 ),
				'columns' => 2,
			],
			'grid-three' => [
				'name'    => sprintf( _n( '%s column', '%s columns', 3, 'minimog' ), 3 ),
				'columns' => 3,
			],
			'grid-four'  => [
				'name'    => sprintf( _n( '%s column', '%s columns', 4, 'minimog' ), 4 ),
				'columns' => 4,
			],
			'grid-five'  => [
				'name'    => sprintf( _n( '%s column', '%s columns', 5, 'minimog' ), 5 ),
				'columns' => 5,
			],
		];
		?>
		<div id="archive-layout-switcher" class="archive-layout-switcher">
			<div class="inner">
				<?php foreach ( $layouts as $layout_key => $layout ) : ?>
					<?php
					$item_class = $layout_key . ' ' . $item_base_class;
					?>
					<a href="#"
					   class="<?php echo esc_attr( $item_class ); ?>"
					   aria-label="<?php echo esc_attr( $layout['name'] ); ?>"
					   data-layout="<?php echo esc_attr( $layout_key ); ?>"
					   data-columns="<?php echo esc_attr( $layout['columns'] ); ?>"
					>
						<?php echo \Minimog_SVG_Manager::instance()->get( $layout_key ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

Layout_Switcher::instance()->initialize();
