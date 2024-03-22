<?php
/**
 * Category Caption Style 08
 * Info split
 */

defined( 'ABSPATH' ) || exit;
extract( $args );
?>
<h5 class="category-name">
	<a href="<?php echo esc_url( $link ); ?>">
		<?php echo esc_html( $category->name ); ?>
	</a>
</h5>
<?php wc_get_template( 'cat-loop/item-count.php', [
	'category' => $category,
	'link'     => $link,
	'settings' => $settings,
] ); ?>
<?php wc_get_template( 'cat-loop/min-price.php', [
	'category' => $category,
	'link'     => $link,
	'settings' => $settings,
] ); ?>
