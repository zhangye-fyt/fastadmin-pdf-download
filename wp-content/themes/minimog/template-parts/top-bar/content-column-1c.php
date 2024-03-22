<?php
/**
 * The template for displaying columns of top bar.
 * one center column
 *
 * This template can be overridden by copying it to minimog-child/template-parts/top-bar/content-column-1c.php
 *
 * @author ThemeMove
 * @since  1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="col-md-12 top-bar-center">
	<div class="top-bar-column-wrap">
		<?php Minimog_Top_Bar::instance()->print_components( 'center' ); ?>
	</div>
</div>
