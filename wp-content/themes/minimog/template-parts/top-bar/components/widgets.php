<?php
/**
 * Widgets on top bar
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="top-bar-widgets">
	<?php Minimog_Sidebar::instance()->generated_sidebar( 'top_bar_widgets' ); ?>
</div>
