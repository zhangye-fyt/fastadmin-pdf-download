<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Light Gallery', 'minimog' ),
	'id'         => 'light_gallery',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'light_gallery_auto_play',
			'type'    => 'switch',
			'title'   => esc_html__( 'Auto Play', 'minimog' ),
			'default' => false,
		),
		array(
			'id'      => 'light_gallery_download',
			'type'    => 'switch',
			'title'   => esc_html__( 'Download', 'minimog' ),
			'default' => true,
		),
		array(
			'id'      => 'light_gallery_full_screen',
			'type'    => 'switch',
			'title'   => esc_html__( 'Full Screen Button', 'minimog' ),
			'default' => true,
		),
		array(
			'id'      => 'light_gallery_share',
			'type'    => 'switch',
			'title'   => esc_html__( 'Share Button', 'minimog' ),
			'default' => true,
		),
		array(
			'id'      => 'light_gallery_zoom',
			'type'    => 'switch',
			'title'   => esc_html__( 'Zoom Buttons', 'minimog' ),
			'default' => true,
		),
		array(
			'id'      => 'light_gallery_thumbnail',
			'type'    => 'switch',
			'title'   => esc_html__( 'Thumbnail Gallery', 'minimog' ),
			'default' => false,
		),
	),
) );
