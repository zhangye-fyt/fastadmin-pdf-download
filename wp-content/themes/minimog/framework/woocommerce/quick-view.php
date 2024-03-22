<?php

namespace Minimog\Woo;

defined( 'ABSPATH' ) || exit;

class Quick_View {

	protected static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function initialize() {
		add_action( 'wp_ajax_product_quick_view', [ $this, 'get_quick_view_content' ] );
		add_action( 'wp_ajax_nopriv_product_quick_view', [ $this, 'get_quick_view_content' ] );

		add_action( 'minimog/quick_view/before', [ $this, 'show_hide_components' ] );
	}

	public function get_quick_view_content() {
		$productId = intval( $_REQUEST['pid'] );

		$post_object = get_post( $productId );

		setup_postdata( $GLOBALS['post'] =& $post_object );

		do_action( 'minimog/quick_view/before' );

		ob_start();
		wc_get_template_part( 'content', 'quick-view' );
		$template = ob_get_contents();
		ob_clean();

		do_action( 'minimog/quick_view/after' );

		$response['template'] = $template;

		wp_reset_postdata();

		echo json_encode( $response );

		wp_die();
	}

	public function show_hide_components() {
		add_filter( 'minimog/quick_view/is_showing', '__return_true' );
		add_filter( 'minimog/single_product/open_gallery', '__return_false' );

		if ( '1' !== \Minimog::setting( 'shop_quick_view_product_description' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		}

		if ( '1' !== \Minimog::setting( 'shop_quick_view_product_meta' ) ) {
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		}

		if ( '1' !== \Minimog::setting( 'shop_quick_view_product_badges' ) ) {
			remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
		}
	}

	public static function output_button( $args = array() ) {
		if ( ! self::can_show() ) {
			return;
		}

		global $product;

		$product_id = $product->get_id();

		$defaults = array(
			'show_tooltip'     => true,
			'tooltip_position' => 'top',
			'tooltip_skin'     => '',
			'style'            => '02',
		);
		$args     = wp_parse_args( $args, $defaults );

		$_wrapper_classes = "product-action quick-view-btn style-{$args['style']}";

		if ( $args['show_tooltip'] === true ) {
			$_wrapper_classes .= ' hint--bounce';
			$_wrapper_classes .= " hint--{$args['tooltip_position']}";

			if ( ! empty( $args['tooltip_skin'] ) ) {
				$_wrapper_classes .= " hint--{$args['tooltip_skin']}";
			}
		}
		?>
		<div class="<?php echo esc_attr( $_wrapper_classes ); ?>"
		     data-hint="<?php esc_attr_e( 'Quick view', 'minimog' ) ?>"
		     data-pid="<?php echo esc_attr( $product_id ); ?>">
			<a class="quick-view-icon" href="#"><?php esc_html_e( 'Quick view', 'minimog' ); ?></a>
		</div>
		<?php
	}

	public static function can_show() {
		return '1' === \Minimog::setting( 'shop_quick_view_enable' );
	}
}

Quick_View::instance()->initialize();
