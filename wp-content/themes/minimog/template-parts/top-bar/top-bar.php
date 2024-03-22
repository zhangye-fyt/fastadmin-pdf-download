<?php
$type   = Minimog_Global::instance()->get_top_bar_type();
$layout = Minimog::setting( "top_bar_style_{$type}_layout" );
?>
<div id="page-top-bar" <?php Minimog_Top_Bar::instance()->get_wrapper_class(); ?>>
	<div <?php Minimog_Top_Bar::instance()->get_container_class(); ?>>
		<div class="top-bar-wrap">
			<div class="top-bar-section">
				<div class="row">
					<?php minimog_load_template( 'top-bar/content-column', $layout ); ?>
				</div>
			</div>
		</div>
		<a href="#" id="top-bar-collapsible-toggle" class="top-bar-collapsible-toggle"
		   aria-label="<?php esc_attr_e( 'Toggle top bar', 'minimog' ); ?>"></a>
	</div>
</div>
