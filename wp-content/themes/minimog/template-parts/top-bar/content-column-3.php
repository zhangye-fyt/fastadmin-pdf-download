<?php
/**
 * The template for displaying columns of top bar.
 * one left column + one center column + one right column.
 *
 * This template can be overridden by copying it to minimog-child/template-parts/top-bar/content-column-2.php
 *
 * @author ThemeMove
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="col-md-3 top-bar-left">
	<div class="top-bar-column-wrap">
		<?php Minimog_Top_Bar::instance()->print_components( 'left' ); ?>
	</div>
</div>
<div class="col-md-6 top-bar-center">
	<div class="top-bar-column-wrap">
		<?php Minimog_Top_Bar::instance()->print_components( 'center' ); ?>
	</div>
</div>
<div class="col-md-3 top-bar-right">
	<div class="top-bar-column-wrap">
		<?php Minimog_Top_Bar::instance()->print_components( 'right' ); ?>
	</div>
</div>
