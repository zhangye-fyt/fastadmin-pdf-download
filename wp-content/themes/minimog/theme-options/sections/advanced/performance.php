<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Performance', 'minimog' ),
	'id'         => 'performance',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'          => 'disable_emoji',
			'type'        => 'switch',
			'title'       => esc_html__( 'Disable Emojis', 'minimog' ),
			'description' => esc_html__( 'Remove Wordpress Emojis functionality.', 'minimog' ),
			'default'     => true,
		),
		array(
			'id'          => 'disable_embeds',
			'type'        => 'switch',
			'title'       => esc_html__( 'Disable Embeds', 'minimog' ),
			'description' => esc_html__( 'Remove Wordpress Embeds functionality.', 'minimog' ),
			'default'     => true,
		),
	),
) );
