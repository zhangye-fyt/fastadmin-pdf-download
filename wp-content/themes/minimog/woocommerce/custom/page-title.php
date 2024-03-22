<?php
/**
 * Page title
 */

defined( 'ABSPATH' ) || exit;

$heading_text = '';

if ( Minimog_Woo::instance()->is_shop() ) {
	$heading_text = Minimog::setting( 'product_archive_title_bar_title' );
} elseif ( Minimog_Woo::instance()->is_product_taxonomy() ) {
	$heading_text = single_cat_title( '', false );
} else {
	$heading_text = get_the_title();
}

?>
<h1 class="page-title">
	<?php echo '' . $heading_text; ?>
</h1>
