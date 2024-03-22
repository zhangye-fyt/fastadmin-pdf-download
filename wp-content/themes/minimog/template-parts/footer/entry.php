<?php
$footer = Minimog_Global::instance()->get_footer();

if ( 'none' === $footer ) {
	return;
}
?>
<div id="page-footer-wrapper" class="page-footer-wrapper">
	<?php
	if ( function_exists( 'tm_addons_location_exits' ) && tm_addons_location_exits( 'tm_footer', true ) ) {
		minimog_load_template( 'footer/tm-elementor' );
	} elseif ( function_exists( 'elementor_location_exits' ) && elementor_location_exits( 'footer', true ) ) {
		minimog_load_template( 'footer/elementor' );
	} else {
		minimog_load_template( 'footer/simple' );
	}
	?>
</div>
