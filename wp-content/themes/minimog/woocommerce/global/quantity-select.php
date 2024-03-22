<?php
/**
 * Product quantity select
 *
 * @package Minimog\WooCommerce\Templates
 * @since   1.0.0
 * @version 2.3.1
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'minimog' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'minimog' );

// In some cases we wish to display the quantity but not allow for it to be changed.
if ( $max_value && $min_value === $max_value ) {
	$is_readonly = true;
	$input_value = $min_value;
} else {
	$is_readonly = false;
}
?>
<div class="quantity-button-wrapper">
	<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>" aria-label="<?php echo esc_attr( $label ); ?>"><?php esc_html_e( 'Quantity', 'minimog' ); ?></label>
	<?php
	if ( $is_readonly ) {
		?>
		<div class="quantity">
			<input type="text"
			       readonly
			       id="<?php echo esc_attr( $input_id ); ?>"
			       class="qty"
			       name="<?php echo esc_attr( $input_name ); ?>"
			       value="<?php echo esc_attr( $min_value ); ?>"
			       title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'minimog' ); ?>"
			       size="4"
			       min="<?php echo esc_attr( $min_value ); ?>"
			       max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
			/>
		</div>
		<?php
	} else {
		global $product;
		$ranges  = explode( "\n", str_replace( "\r", "", $values ) );
		$options = [];

		if ( empty( $values ) ) {
			$options[] = 1;
		} else {
			foreach ( $ranges as $value ) {
				if ( is_numeric( $value ) ) {
					$options[] = intval( $value );
				} elseif ( strpos( $value, '-' ) !== false ) {
					$range = explode( '-', $value );

					if ( count( $range ) === 2 ) {
						$min = intval( $range[0] );
						$max = intval( $range[1] );

						$options = array_merge( $options, range( $min, $max ) );
					}
				}
			}

			$options = array_unique( $options );
			foreach ( $options as $key => $number ) {
				if ( $min_value > $number || ( '' !== $max_value && $max_value < $number ) ) {
					unset( $options[ $key ] );
				}
			}
		}

		/* translators: %s: Quantity. */
		$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'minimog' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'minimog' );
		?>
		<div class="quantity quantity-select">
			<select name="<?php echo esc_attr( $input_name ); ?>" id="<?php echo esc_attr( $input_id ); ?>" class="qty woosb-qty">
				<?php foreach ( $options as $option ): ?>
					<option value="<?php echo esc_attr( $option ) ?>" <?php selected( $input_value, $option ); ?>><?php echo esc_html( $option ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	<?php } ?>
</div>
