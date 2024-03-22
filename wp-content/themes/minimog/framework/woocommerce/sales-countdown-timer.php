<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Sales_Countdown_Timer {
	/**
	 * @var \SALES_COUNTDOWN_TIMER_Data $settings
	 */

	protected $settings;
	protected $id;
	protected $index;
	protected $position;
	protected $pg_position;
	protected $product_id;
	protected $sticky_countdown;
	protected $atc_button;

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		if ( ! $this->is_activate() ) {
			return;
		}

		$this->settings = new \VI_SCT_SALES_COUNTDOWN_TIMER_Data();

		minimog_remove_filters_for_anonymous_class( 'wp', 'VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Single_Product_Countdown', 'init', 10 );
		add_action( 'wp', [ $this, 'init' ] );

		// Countdown timer position.
		add_action( 'woocommerce_before_template_part', [ $this, 'countdown_before_template' ] );
		add_action( 'woocommerce_after_template_part', [ $this, 'countdown_after_template' ] );
		add_action( 'woocommerce_after_add_to_cart_form', [ $this, 'countdown_cart_after' ] );
		add_action( 'minimog/single_product/product_images/after', [ $this, 'countdown_cart_after_product_images' ] );

		// Variations.
		minimog_remove_filters_for_anonymous_class( 'woocommerce_available_variation', 'VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Single_Product_Countdown', 'woocommerce_available_variation' );
		add_filter( 'woocommerce_available_variation', [ $this, 'woocommerce_available_variation' ], 20, 3 );

		/**
		 * Change span to div or remove redundant this selector to improvement performance.
		 * Temporary remove this wrapper because wrong price display via JS
		 */
		minimog_remove_filters_for_anonymous_class( 'woocommerce_get_price_html', 'VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Product_Countdown', 'sctv_woocommerce_get_price_html', PHP_INT_MAX );
		//add_filter( 'woocommerce_get_price_html', [ $this, 'update_price_html' ], PHP_INT_MAX, 2 );
	}

	public function is_activate() {
		return class_exists( 'VI_SCT_SALES_COUNTDOWN_TIMER_Data' );
	}

	public function init() {
		if ( is_admin() || ! is_product() ) {
			return;
		}

		global $post;
		$product_id = $post->ID;
		$product    = wc_get_product( $product_id );
		if ( $product ) {
			if ( ! $product->is_type( 'variable' ) ) {
				add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'countdown_cart_before' ) );
				if ( ! $product->is_in_stock() ) {
					return;
				}
				if ( empty( $product->get_date_on_sale_from( 'edit' ) ) || empty( $product->get_date_on_sale_to( 'edit' ) ) ) {
					return;
				}
				$this->init_countdown( $product_id );
			} else {
				add_action( 'woocommerce_before_single_variation', array( $this, 'countdown_cart_before' ) );
				if ( ! $product->is_in_stock() ) {
					return;
				}
				$variation_id      = get_post_meta( $product_id, '_woo_ctr_display_enable', true );
				$default_attribute = method_exists( $product, 'get_default_attributes' ) ? $product->get_default_attributes() : $product->get_variation_default_attributes();
				if ( ! $variation_id || count( $default_attribute ) ) {
					return;
				}

				$variation = wc_get_product( $variation_id );
				if ( ! $variation || ! $variation->is_in_stock() ) {
					return;
				}
				if ( ! $variation->get_date_on_sale_from( 'edit' ) && ! $variation->get_date_on_sale_to( 'edit' ) ) {
					return;
				}
				$this->init_countdown( $variation_id );
			}
		}
	}

	public function init_countdown( $product_id ) {
		$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return;
			}

			$this->product_id       = $product_id;
			$this->id               = $id;
			$this->index            = $index;
			$this->pg_position      = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position_in_single', $index );
			$this->position         = $this->settings->get_current_countdown( 'sale_countdown_single_product_position', $index );
			$this->sticky_countdown = $this->settings->get_current_countdown( 'sale_countdown_single_product_sticky', $index );
			$this->atc_button       = $this->settings->get_current_countdown( 'sale_countdown_add_to_cart_button', $index );
		}
	}

	public function countdown_cart_before() {
		if ( ! $this->id ) {
			return;
		}

		if ( $this->position == 'before_cart' ) {
			$this->simple_product_countdown_html();
		}
	}

	public function countdown_cart_after() {
		if ( ! $this->id ) {
			return;
		}

		if ( $this->position == 'after_cart' ) {
			$this->simple_product_countdown_html();
		}
	}

	public function countdown_cart_after_product_images() {
		if ( ! $this->id ) {
			return;
		}

		if ( $this->position == 'product_image' ) {
			$this->simple_product_countdown_html();
		}
	}

	public function countdown_before_template( $template_name ) {
		if ( ! $this->id ) {
			return;
		}

		switch ( $template_name ) {
			case 'single-product/sale-flash.php':
				if ( 'before_saleflash' === $this->position ) {
					$this->simple_product_countdown_html();
				}
				break;
			case 'single-product/price.php':
				if ( 'before_price' === $this->position ) {
					$this->simple_product_countdown_html();
				}
				break;
		}
	}

	public function countdown_after_template( $template_name ) {
		if ( ! $this->id ) {
			return;
		}

		switch ( $template_name ) {
			case 'single-product/sale-flash.php':
				if ( 'after_saleflash' === $this->position ) {
					$this->simple_product_countdown_html();
				}
				break;
			case 'single-product/price.php':
				if ( 'after_price' === $this->position ) {
					$this->simple_product_countdown_html();
				}
				break;
		}
	}

	/**
	 * @see \SALES_COUNTDOWN_TIMER_Frontend_Single_Product::woocommerce_available_variation()
	 *
	 * @param array                 $variation_data
	 * @param \WC_Product_Variable  $parent
	 * @param \WC_Product_Variation $variation
	 *
	 * @return mixed
	 */
	public function woocommerce_available_variation( $variation_data, $parent, $variation ) {
		$wc_ajax = $_REQUEST['wc-ajax'] ?? '';
		if ( in_array( $wc_ajax, [ 'viwcaio_show_variation' ] ) ) {
			return $variation_data;
		}

		if ( ! apply_filters( 'sctv_get_countdown_on_available_variation', true ) ) {
			return $variation_data;
		}

		if ( ! $variation->is_on_sale() ) {
			return $variation_data;
		}

		$variation_id = $variation->get_id();
		if ( '' === $variation->get_sale_price( 'edit' ) ) {
			return $variation_data;
		}

		if ( empty( $variation->get_date_on_sale_from( 'edit' ) ) || empty( $variation->get_date_on_sale_to( 'edit' ) ) ) {
			return $variation_data;
		}

		$id = get_post_meta( $variation_id, '_woo_ctr_select_countdown_timer', true );
		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );

			if ( $index === false ) {
				return $variation_data;
			}

			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return $variation_data;
			}

			/**
			 * 1 === Number + Text in box
			 * 2 === Number in box + Text out box
			 * 3 === Minimal Style
			 * 4 === Circle Countdown
			 */
			$display_supported = $this->settings->get_params( 'sale_countdown_display_type' );
			$display_type      = $display_supported[ $index ];

			$wrapper_class = 'woo-sctr-single-product-container woo-sctr-variation-product-container woo-sctr-single-product-shortcode-' . $id;
			switch ( $display_type ) {
				case '1':
					$wrapper_class .= ' minimog-sales-countdown-layout-box minimog-sales-countdown-layout-box-1';
					break;
				case '2':
					$wrapper_class .= ' minimog-sales-countdown-layout-box minimog-sales-countdown-layout-box-2';
					break;
				case '3':
					$wrapper_class .= ' minimog-sales-countdown-layout-inline';
					break;
				case '4':
					$wrapper_class .= ' minimog-sales-countdown-layout-circle';
					break;
			}

			$progress_bar_position = $this->settings->get_params( 'sale_countdown_progress_bar_position' )[ $index ];

			if ( in_array( $progress_bar_position, [ 'left_countdown', 'right_countdown' ] ) ) {
				$wrapper_class .= ' woo-sctr-single-product-inline-container';
			}

			ob_start();
			?>
			<div class="<?php echo esc_attr( $wrapper_class ); ?>">
				<?php
				echo '' . $this->get_countdown_timer_html( [
						'product_id'                 => $variation_id,
						'countdown_enable'           => '1',
						'countdown_id'               => $id,
						'progress_bar_enable'        => '1',
						'progress_bar_position'      => $progress_bar_position,
						'resize_archive_page_enable' => '',
						'sale_countdown_timer_id_t'  => current_time( 'timestamp' ),
						'is_single'                  => '1',
					] );
				?>
			</div>
			<?php
			$variation_desc = ob_get_clean();

			$variation_data['variation_description'] .= $variation_desc;
		}

		return $variation_data;
	}

	public function simple_product_countdown_html() {
		global $product;
		$index             = $this->index;
		$id                = $this->id;
		$display_supported = $this->settings->get_params( 'sale_countdown_display_type' );
		$display_type      = $display_supported[ $index ];

		$wrapper_class = 'woo-sctr-single-product-container woo-sctr-variation-product-container woo-sctr-single-product-shortcode-' . $id;
		switch ( $display_type ) {
			case '1':
				$wrapper_class .= ' minimog-sales-countdown-layout-box minimog-sales-countdown-layout-box-1';
				break;
			case '2':
				$wrapper_class .= ' minimog-sales-countdown-layout-box minimog-sales-countdown-layout-box-2';
				break;
			case '3':
				$wrapper_class .= ' minimog-sales-countdown-layout-inline';
				break;
			case '4':
				$wrapper_class .= ' minimog-sales-countdown-layout-circle';
				break;
		}

		$progress_bar_position = $this->settings->get_params( 'sale_countdown_progress_bar_position' )[ $index ];

		if ( in_array( $progress_bar_position, [ 'left_countdown', 'right_countdown' ] ) ) {
			$wrapper_class .= ' woo-sctr-single-product-inline-container';
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<?php
			echo '' . $this->get_countdown_timer_html( [
					'product_id'                 => $product->get_id(),
					'countdown_enable'           => '1',
					'countdown_id'               => $id,
					'progress_bar_enable'        => '1',
					'progress_bar_position'      => $progress_bar_position,
					'resize_archive_page_enable' => '',
					'sale_countdown_timer_id_t'  => current_time( 'timestamp' ),
					'is_single'                  => '1',
				] );
			?>
		</div>
		<?php
	}

	/**
	 * @see \SALES_COUNTDOWN_TIMER_Frontend_Single_Product::update_price()
	 */
	public function minimog_countdown_timer() {
		global $product;
		$product_id = $product->get_id();

		if ( 'variable' === $product->get_type() ) {
			return;
		}

		$id = get_post_meta( $product->get_id(), '_woo_ctr_select_countdown_timer', true );
		if ( '' === $id ) {
			return;
		}


		$index = array_search( $id, $this->settings->get_id() );

		if ( $index === false ) {
			return;
		}

		if ( ! $this->settings->get_active()[ $index ] ) {
			return;
		}

		$product_sale_from = $product->get_date_on_sale_from( 'edit' );
		$product_sale_to   = $product->get_date_on_sale_to( 'edit' );

		if ( empty( $product_sale_from ) || empty( $product_sale_to ) ) {
			return;
		}

		$offset    = get_option( 'gmt_offset' );
		$sale_from = $product_sale_from ? ( $product_sale_from->getTimestamp() + $offset * 3600 ) : 0;
		$sale_to   = $product_sale_to ? ( $product_sale_to->getTimestamp() + $offset * 3600 ) : 0;

		$sale_from_date = date( 'Y-m-d', $sale_from );
		$sale_to_date   = date( 'Y-m-d', $sale_to );
		$sale_from_time = $sale_from - strtotime( $sale_from_date );
		$sale_to_time   = $sale_to - strtotime( $sale_to_date );
		$sale_from_time = woo_ctr_time_revert( $sale_from_time );
		$sale_to_time   = woo_ctr_time_revert( $sale_to_time );
		// Calculate sold quantity during campaign.
		$data                       = get_post_meta( $product_id, '_woo_ctr_product_sold_quantity', true ) ? ( get_post_meta( $product_id, '_woo_ctr_product_sold_quantity', true ) ) : array();
		$order_status               = $this->settings->get_progress_bar_order_status()[ $index ] ? explode( ',', $this->settings->get_progress_bar_order_status()[ $index ] ) : array();
		$progress_bar_message       = $this->settings->get_progress_bar_message()[ $index ];
		$progress_bar_type          = $this->settings->get_progress_bar_type()[ $index ];
		$progress_bar_real_quantity = 0;
		if ( is_array( $data ) && count( $data ) && is_array( $order_status ) && count( $order_status ) ) {
			foreach ( $data as $key => $value ) {
				$order = get_post( $value['id'] );
				if ( $order && in_array( $order->post_status, $order_status ) ) {
					$progress_bar_real_quantity += $value['quantity'];
				}
			}
		}

		$this->id                = $id;
		$this->index             = $index;
		$this->position          = $this->settings->get_position()[ $index ];
		$this->sale_from_date    = $sale_from_date;
		$this->sale_from_time    = $sale_from_time;
		$this->sale_to_date      = $sale_to_date;
		$this->sale_to_time      = $sale_to_time;
		$this->progress_bar_html = $this->get_progress_bar_html( $product_id, $index, $progress_bar_real_quantity, $progress_bar_message, $progress_bar_type );

		$countdown_timer = do_shortcode( '[sales_countdown_timer enable_single_product="1" id="' . $this->id . '" sale_from_date="' . $this->sale_from_date . '" sale_from_time="' . $this->sale_from_time . '" sale_to_date="' . $this->sale_to_date . '" sale_to_time="' . $this->sale_to_time . '"]' );

		/**
		 * 1 === Number + Text in box
		 * 2 === Number in box + Text out box
		 * 3 === Minimal Style
		 * 4 === Circle Countdown
		 */
		$display_supported = $this->settings->get_display_type();
		$display_type      = $display_supported[ $index ];

		$wrapper_class = 'woo-sctr-single-product-container';
		switch ( $display_type ) {
			case '1':
				$wrapper_class .= ' minimog-sales-countdown-layout-box minimog-sales-countdown-layout-box-1';
				break;
			case '2':
				$wrapper_class .= ' minimog-sales-countdown-layout-box minimog-sales-countdown-layout-box-2';
				break;
			case '3':
				$wrapper_class .= ' minimog-sales-countdown-layout-inline';
				break;
			case '4':
				$wrapper_class .= ' minimog-sales-countdown-layout-circle';
				break;
		}
		?>
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
			<?php if ( 'above_countdown' === $this->settings->get_progress_bar_position()[ $this->index ] ) : ?>
				<?php echo '' . $this->progress_bar_html . $countdown_timer; ?>
			<?php else: ?>
				<?php echo '' . $countdown_timer . $this->progress_bar_html; ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * @see \VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Product_Countdown::register_shortcode()
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	public function get_countdown_timer_html( $args = array() ) {
		/**
		 * @var $product_id
		 * @var $countdown_enable
		 * @var $countdown_id
		 * @var $progress_bar_enable
		 * @var $progress_bar_position
		 * @var $resize_archive_page_enable
		 * @var $sale_countdown_timer_id_t
		 * @var $is_single
		 */
		$defaults = [
			'product_id'                 => 0,
			'countdown_enable'           => '1',
			'countdown_id'               => 'salescountdowntimer',
			'progress_bar_enable'        => '1',
			'progress_bar_position'      => 'above_countdown',
			'resize_archive_page_enable' => '',
			'sale_countdown_timer_id_t'  => '',
			'is_single'                  => '',
		];

		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		if ( ! $product_id ) {
			return false;
		}

		/**
		 * @var \WC_Product|\WC_Product_Variation $product
		 */
		$product = wc_get_product( $product_id );

		if ( empty( $product ) || ! $product instanceof \WC_Product ) {
			return false;
		}

		if ( ! $countdown_id ) {
			$countdown_id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		}
		if ( $countdown_id === '' ) {
			return false;
		}

		$index = array_search( $countdown_id, $this->settings->get_params( 'sale_countdown_id' ) );
		if ( $index === false ) {
			return false;
		}
		if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
			return false;
		}

		$offset    = get_option( 'gmt_offset' );
		$sale_from = ( $product->get_date_on_sale_from( 'edit' ) ) ? ( $product->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
		$sale_to   = ( $product->get_date_on_sale_to( 'edit' ) ) ? ( $product->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
		if ( get_post_meta( $product_id, '_woo_ctr_enable_loop_countdown', true ) ) {
			$countdown_time_reset = get_post_meta( $product_id, '_woo_ctr_loop_countdown_val', true ) ? : 0;
			if ( $countdown_time_reset ) {
				$countdown_time_reset = $this->get_loop_time_val( $countdown_time_reset, get_post_meta( $product_id, '_woo_ctr_loop_countdown_type', true ) );
			}
		}

		$shortcode_atts = array(
			'type'                       => 'product',
			'product_id'                 => $product_id,
			'id'                         => $countdown_id,
			'sale_from'                  => $sale_from,
			'sale_to'                    => $sale_to,
			'resize_archive_page_enable' => $resize_archive_page_enable,
			'sale_countdown_timer_id_t'  => $sale_countdown_timer_id_t,
			'countdown_time_reset'       => $countdown_time_reset ?? '',
		);
		$shortcode      = '[sales_countdown_timer ';
		foreach ( $shortcode_atts as $key => $value ) {
			$shortcode .= $key . '="' . $value . '" ';
		}
		$shortcode      .= ']';
		$countdown_html = do_shortcode( $shortcode );

		$progress_bar_html = '';
		$now               = current_time( 'timestamp' );
		if ( '1' === $progress_bar_enable && $now < $sale_to ) {
			$pg_upcoming_enable = $this->settings->get_current_countdown( 'sale_countdown_upcoming_progress_bar_enable', $index );
			if ( ! $pg_upcoming_enable && $sale_from > $now ) {
				$progress_bar_html = '';
			} else {
				$data                       = get_post_meta( $product_id,
					'_woo_ctr_product_sold_quantity',
					true ) ? ( get_post_meta( $product_id, '_woo_ctr_product_sold_quantity', true ) ) : array();
				$order_status               = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_order_status',
					$index ) ? explode( ',',
					$this->settings->get_current_countdown( 'sale_countdown_progress_bar_order_status',
						$index ) ) : array();
				$progress_bar_message       = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_message',
					$index );
				$progress_bar_real_quantity = 0;
				if ( is_array( $order_status ) && empty( $order_status ) ) {
					$order_status = array_keys( wc_get_order_statuses() );
				}
				if ( is_array( $data ) && count( $data ) && is_array( $order_status ) && count( $order_status ) ) {
					foreach ( $data as $key => $value ) {
						$order = get_post( $value['id'] );
						if ( $order && in_array( $order->post_status, $order_status ) ) {
							$progress_bar_real_quantity += $value['quantity'];
						}
					}
				}
				$progress_bar_html = $this->get_progress_bar_html(
					$product_id,
					$countdown_id,
					$index,
					$progress_bar_real_quantity,
					$progress_bar_message,
					$this->settings,
					$is_single
				);
			}
		}
		ob_start();
		if ( in_array( $progress_bar_position, array( 'above_countdown', 'left_countdown' ) ) ) {
			echo '' . $progress_bar_html . $countdown_html;
		} else {
			echo '' . $countdown_html . $progress_bar_html;
		}
		$html = ob_get_clean();

		return $html;
	}

	/**
	 * @see \VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Product_Countdown::get_progress_bar_html()
	 *
	 * @param      $product_id
	 * @param      $countdown_id
	 * @param      $index
	 * @param      $progress_bar_real_quantity
	 * @param      $progress_bar_message
	 * @param      $settings
	 * @param bool $is_single
	 *
	 * @return string
	 */
	public function get_progress_bar_html( $product_id, $countdown_id, $index, $progress_bar_real_quantity, $progress_bar_message, $settings, $is_single = false ) {
		$progress_bar                  = get_post_meta( $product_id, '_woo_ctr_enable_progress_bar', true );
		$progress_bar_goal             = (int) get_post_meta( $product_id, '_woo_ctr_progress_bar_goal', true );
		$progress_bar_initial          = (int) get_post_meta( $product_id, '_woo_ctr_progress_bar_initial', true );
		$progress_bar_type             = $settings->get_current_countdown( 'sale_countdown_progress_bar_type', $index );
		$progress_bar_message_position = $is_single ? $settings->get_current_countdown( 'sale_countdown_progress_bar_message_position_in_single', $index ) : $settings->get_current_countdown( 'sale_countdown_progress_bar_message_position', $index );
		$progress_bar_html             = '';

		if ( $progress_bar_real_quantity >= 0 && $progress_bar && $progress_bar_goal ) {
			$progress_bar_real_quantity += $progress_bar_initial;
			$quantity_sold              = $progress_bar_real_quantity;
			$quantity_left              = (int) ( $progress_bar_goal - $progress_bar_real_quantity );
			$percentage_sold            = (int) ( 100 * ( $progress_bar_real_quantity / $progress_bar_goal ) );
			$percentage_left            = 100 - $percentage_sold;
			if ( $progress_bar_real_quantity >= $progress_bar_goal ) {
				$progress_bar_real_quantity = $progress_bar_goal;
			}
			$progress_bar_fill = 100 * ( $progress_bar_real_quantity / $progress_bar_goal );
			if ( $progress_bar_type == 'decrease' ) {
				$progress_bar_fill = 100 - $progress_bar_fill;
			}
			if ( $progress_bar_fill < 0 ) {
				$progress_bar_fill = 0;
			} elseif ( $progress_bar_fill > 100 ) {
				$progress_bar_fill = 100;
			}

			/**
			 * Minimog Updated Here.
			 */
			$progress_bar_template = '<div class="progress-bar-message-item %2$s">%1$s</div>';

			$quantity_left_html = apply_filters( 'minimog/sales_countdown_timer/progress_bar/quantity_left_html', sprintf( $progress_bar_template,
				sprintf( __( 'Available: %s', 'minimog' ), '<span class="value">' . $quantity_left . '</span>' ),
				'quantity-left'
			) );

			$quantity_sold_html = apply_filters( 'minimog/sales_countdown_timer/progress_bar/quantity_sold_html', sprintf( $progress_bar_template,
				sprintf( __( 'Sold: %s', 'minimog' ), '<span class="value">' . $quantity_sold . '</span>' ),
				'quantity-sold'
			) );

			$percentage_left_html = apply_filters( 'minimog/sales_countdown_timer/progress_bar/percentage_left_html', sprintf( $progress_bar_template,
				sprintf( __( 'Available: %s', 'minimog' ), '<span class="value">' . $percentage_left . '</span>' ),
				'percentage-left'
			) );

			$percentage_sold_html = apply_filters( 'minimog/sales_countdown_timer/progress_bar/percentage_sold_html', sprintf( $progress_bar_template,
				sprintf( __( 'Sold: %s', 'minimog' ), '<span class="value">' . $percentage_sold . '</span>' ),
				'percentage-sold'
			) );

			$progress_bar_goal_html = apply_filters( 'minimog/sales_countdown_timer/progress_bar/goal_html', sprintf( $progress_bar_template,
				sprintf( __( 'Total: %s', 'minimog' ), '<span class="value">' . $progress_bar_goal . '</span>' ),
				'goal'
			) );

			/**
			 * Then update with custom html.
			 */
			$progress_bar_message = str_replace( '{quantity_left}', $quantity_left_html, $progress_bar_message );
			$progress_bar_message = str_replace( '{quantity_sold}', $quantity_sold_html, $progress_bar_message );
			$progress_bar_message = str_replace( '{percentage_sold}', $percentage_sold_html, $progress_bar_message );
			$progress_bar_message = str_replace( '{percentage_left}', $percentage_left_html, $progress_bar_message );
			$progress_bar_message = str_replace( '{goal}', $progress_bar_goal_html, $progress_bar_message );

			$progress_bar_class = array(
				'woo-sctr-progress-bar-wrap-container',
				'woo-sctr-progress-bar-wrap-container-shortcode-' . $countdown_id,
				'woo-sctr-progress-bar-position-' . $progress_bar_message_position,
			);
			if ( in_array( $progress_bar_message_position, [ 'left_progressbar', 'right_progressbar' ] ) ) {
				$progress_bar_class[] = 'woo-sctr-progress-bar-wrap-inline';
			}
			$progress_bar_class = trim( implode( ' ', $progress_bar_class ) );
			ob_start();
			?>
			<div class="<?php echo esc_attr( $progress_bar_class ); ?>">
				<?php if ( in_array( $progress_bar_message_position, array(
					'above_progressbar',
					'left_progressbar',
				) ) ) : ?>
					<div class="woo-sctr-progress-bar-message"><?php echo '' . $progress_bar_message; ?></div>
				<?php endif; ?>
				<div class="woo-sctr-progress-bar-wrap">
					<div class="woo-sctr-progress-bar-fill" data-width="<?php echo esc_attr( $progress_bar_fill ); ?>">
					</div>
					<?php if ( $progress_bar_message_position === 'in_progressbar' ) : ?>
						<div class="woo-sctr-progress-bar-message"><?php echo '' . $progress_bar_message; ?></div>
					<?php endif; ?>
				</div>
				<?php if ( in_array( $progress_bar_message_position, [
					'below_progressbar',
					'right_progressbar',
				] ) ) : ?>
					<div class="woo-sctr-progress-bar-message"><?php echo '' . $progress_bar_message; ?></div>
				<?php endif; ?>
			</div>
			<?php
			$progress_bar_html = ob_get_clean();
		}

		return $progress_bar_html;
	}

	private function get_loop_time_val( $loop, $type ) {
		switch ( $type ) {
			case 'day':
				$result = $loop * 86400;
				break;
			case 'hour':
				$result = $loop * 3600;
				break;
			default:
				$result = $loop * 60;
		}

		return $result;
	}

	public function update_price_html( $html, $product ) {
		$html = '<div class="vi-sctv-price">' . $html . '</div>';

		return $html;
	}
}

Sales_Countdown_Timer::instance()->initialize();
