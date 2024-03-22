<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.7.0
 */

defined( 'ABSPATH' ) || exit;

$show_result_count = Minimog::setting( 'shop_archive_result_count' );
?>
<div class="shop-actions-toolbar shop-actions-toolbar-left col">
	<div class="inner">
		<?php do_action( 'minimog/shop_archive/actions_toolbar_left/before' ); ?>

		<?php if ( '1' === $show_result_count ): ?>
			<div class="woocommerce-result-count archive-result-count">
				<?php echo Minimog_Woo::instance()->get_result_count_text( $current, $per_page, $total ); ?>
			</div>
		<?php endif; ?>

		<?php do_action( 'minimog/shop_archive/actions_toolbar_left/after' ); ?>
	</div>
</div>
