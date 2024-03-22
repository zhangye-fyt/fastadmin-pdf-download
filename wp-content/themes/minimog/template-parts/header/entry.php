<?php
$type = Minimog_Global::instance()->get_header_type();

if ( 'none' === $type ) {
	return;
}

if ( ! minimog_has_elementor_template( 'header' ) ) {
	minimog_load_template( 'header/header', $type );
}
