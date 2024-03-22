<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Advanced', 'minimog' ),
	'id'         => 'advanced',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'          => 'scroll_top_enable',
			'type'        => 'switch',
			'title'       => esc_html__( 'Go To Top Button', 'minimog' ),
			'description' => esc_html__( 'Turn on to show go to top button.', 'minimog' ),
			'default'     => false,
		),
		array(
			'id'          => 'image_lazy_load_enable',
			'type'        => 'switch',
			'title'       => esc_html__( 'Image Lazy Load?', 'minimog' ),
			'description' => esc_html__( 'Turn on to make images load on scroll.', 'minimog' ),
			'default'     => true,
		),
		array(
			'id'          => 'retina_display_enable',
			'type'        => 'switch',
			'title'       => esc_html__( 'Retina Display?', 'minimog' ),
			'description' => esc_html__( 'Turn on to make images retina on high screen revolution.', 'minimog' ),
			'default'     => false,
		),
		array(
			'id'          => 'smooth_scroll_enable',
			'type'        => 'switch',
			'title'       => esc_html__( 'Smooth Scroll', 'minimog' ),
			'description' => esc_html__( 'Smooth scrolling experience for websites.', 'minimog' ),
			'default'     => false,
		),
	),
) );
