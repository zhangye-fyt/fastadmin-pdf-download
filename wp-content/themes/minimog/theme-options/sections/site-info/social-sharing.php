<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Share List', 'minimog' ),
	'id'         => 'section_sharing',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'      => 'social_sharing_item_enable',
			'type'    => 'sortable',
			'mode'    => 'toggle',
			'title'   => esc_html__( 'Sharing Links', 'minimog' ),
			'default' => Minimog_Redux::get_default_setting( 'social_sharing_item_enable' ),
			'options' => [
				'facebook'    => esc_attr__( 'Facebook', 'minimog' ),
				'twitter'     => esc_attr__( 'Twitter', 'minimog' ),
				'linkedin'    => esc_attr__( 'Linkedin', 'minimog' ),
				'tumblr'      => esc_attr__( 'Tumblr', 'minimog' ),
				'email'       => esc_attr__( 'Email', 'minimog' ),
				'pinterest'   => esc_attr__( 'Pinterest', 'minimog' ),
				'vk'          => esc_attr__( 'VK', 'minimog' ),
				'digg'        => esc_attr__( 'Digg', 'minimog' ),
				'reddit'      => esc_attr__( 'Reddit', 'minimog' ),
				'stumbleupon' => esc_attr__( 'StumbleUpon', 'minimog' ),
				'whatsapp'    => esc_attr__( 'WhatsApp', 'minimog' ),
				'xing'        => esc_attr__( 'Xing', 'minimog' ),
				'telegram'    => esc_attr__( 'Telegram', 'minimog' ),
				'skype'       => esc_attr__( 'Skype', 'minimog' ),
			],
		),
	),
) );
