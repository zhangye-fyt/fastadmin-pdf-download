<?php
/**
 * Category Caption Style 03 + 04
 * Info with small count
 */

defined( 'ABSPATH' ) || exit;
extract( $args );
?>
<div class="category-info">
	<h5 class="category-name">
		<a href="<?php echo esc_url( $link ); ?>">
			<?php echo esc_html( $category->name ); ?>
			<?php wc_get_template( 'cat-loop/item-count.php', [
				'category' => $category,
				'link'     => $link,
				'settings' => $settings,
			] ); ?>
		</a>
	</h5>
	<?php wc_get_template( 'cat-loop/min-price.php', [
		'category' => $category,
		'link'     => $link,
		'settings' => $settings,
	] ); ?>
</div>
