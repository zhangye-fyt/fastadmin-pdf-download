<?php
/**
 * Social icons on top bar
 *
 * @package Minimog
 * @since   1.0.0
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="top-bar-social-network">
	<div class="inner">
		<?php Minimog_Templates::social_icons( array(
			'display'        => 'icon',
			'tooltip_enable' => false,
		) ); ?>
	</div>
</div>
