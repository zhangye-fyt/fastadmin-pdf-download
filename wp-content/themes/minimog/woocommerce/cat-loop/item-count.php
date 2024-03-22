<?php
/**
 * Category item count
 */

defined( 'ABSPATH' ) || exit;
extract( $args );

if ( empty( $settings['show_count'] ) ) {
	return;
}
?>
<div class="category-count">
	<span class="cat-count-number"><?php echo intval( $category->count ); ?></span>

	<?php if ( ! empty( $settings['show_count_text'] ) ) : ?>
		<span class="cat-count-text">
			<?php echo esc_html( _n( 'item', 'items', $category->count, 'minimog' ) ); ?>
		</span>
	<?php endif; ?>
</div>
