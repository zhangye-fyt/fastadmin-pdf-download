<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Minimog_WP_Widget_Product_Highlight_Filter' ) ) {
	class Minimog_WP_Widget_Product_Highlight_Filter extends Minimog_WC_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'minimog-wp-widget-product-highlight-filter';
			$this->widget_cssclass    = 'minimog-wp-widget-product-highlight-filter minimog-wp-widget-filter';
			$this->widget_name        = sprintf( '%1$s %2$s', '[Minimog]', esc_html__( 'Product Highlight Filter', 'minimog' ) );
			$this->widget_description = esc_html__( 'Shows product status in a widget which lets you narrow down the list of products when viewing products.', 'minimog' );
			$this->settings           = array(
				'title'        => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Highlight', 'minimog' ),
					'label' => esc_html__( 'Title', 'minimog' ),
				),
				'display_type' => array(
					'type'    => 'select',
					'std'     => 'list',
					'label'   => esc_html__( 'List Layout', 'minimog' ),
					'options' => array(
						'list'   => esc_html__( 'List', 'minimog' ),
						'inline' => esc_html__( 'Inline', 'minimog' ),
					),
				),
				'list_style'   => array(
					'type'    => 'select',
					'std'     => 'normal',
					'label'   => esc_html__( 'List Style', 'minimog' ),
					'options' => array(
						'normal' => esc_html__( 'Normal List', 'minimog' ),
						'radio'  => esc_html__( 'Radio List', 'minimog' ),
					),
				),
				'enable_collapsed'  => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Collapsed ?', 'minimog' ),
				),
			);

			parent::__construct();
		}

		public function widget( $args, $instance ) {
			if ( ! is_shop() && ! is_product_taxonomy() ) {
				return;
			}

			if ( ! \Minimog\Woo\Product_Query::is_main_query_has_post() ) {
				return;
			}

			$filter_name = 'highlight_filter';
			$selected    = isset( $_GET[ $filter_name ] ) ? wc_clean( $_GET[ $filter_name ] ) : '';

			$options = Minimog_Woo::instance()->get_product_highlight_filter_options();

			$base_link = remove_query_arg( 'paged', $this->get_current_page_url() );

			$this->widget_start( $args, $instance );

			$display_type = $this->get_value( $instance, 'display_type' );
			$list_style   = $this->get_value( $instance, 'list_style' );

			$class = 'minimog-product-highlight-filter';
			$class .= ' show-display-' . $display_type;
			$class .= ' list-style-' . $list_style;
			$class .= ' single-choice';
			?>
			<ul class="<?php echo esc_attr( $class ); ?>">
				<?php foreach ( $options as $option_value => $option_label ) : ?>
					<?php
					if ( '' === $option_value ) {
						$link = remove_query_arg( $filter_name, $base_link );
					} else {
						$link = add_query_arg( $filter_name, $option_value, $base_link );
					}

					$item_class = 'filter-item';
					if ( $selected === $option_value ) {
						$item_class .= ' chosen';
					}
					?>
					<li class="<?php echo esc_attr( $item_class ); ?>">
						<a href="<?php echo esc_url( $link ); ?>"
						   class="filter-link"><?php echo esc_html( $option_label ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
			$this->widget_end( $args, $instance );
		}
	}
}
