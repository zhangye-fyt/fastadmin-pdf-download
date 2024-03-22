<?php
$sidebar_positions   = Minimog_Helper::get_list_sidebar_positions();
$registered_sidebars = Minimog_Redux::instance()->get_registered_widgets_options();

Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Single Post', 'minimog' ),
	'id'         => 'single_blog',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'     => 'section_start_single_blog_header',
			'type'   => 'tm_heading',
			'title'  => esc_html__( 'Header Settings', 'minimog' ),
			'indent' => true,
		),
		array(
			'id'          => 'blog_single_header_type',
			'type'        => 'select',
			'title'       => esc_html__( 'Header Style', 'minimog' ),
			'placeholder' => esc_html__( 'Use Global Setting', 'minimog' ),
			'options'     => Minimog_Header::instance()->get_list( true ),
		),
		array(
			'id'          => 'blog_single_header_overlay',
			'type'        => 'select',
			'title'       => esc_html__( 'Header Overlay', 'minimog' ),
			'placeholder' => esc_html__( 'Use Global Setting', 'minimog' ),
			'options'     => Minimog_Header::instance()->get_overlay_list(),
		),
		array(
			'id'          => 'blog_single_header_skin',
			'type'        => 'select',
			'title'       => esc_html__( 'Header Skin', 'minimog' ),
			'placeholder' => esc_html__( 'Use Global Setting', 'minimog' ),
			'options'     => Minimog_Header::instance()->get_skin_list(),
		),
		array(
			'id'     => 'section_start_blog_single_title_bar',
			'type'   => 'tm_heading',
			'title'  => esc_html__( 'Title Bar Settings', 'minimog' ),
			'indent' => true,
		),
		array(
			'id'          => 'blog_single_title_bar_layout',
			'type'        => 'select',
			'title'       => esc_html__( 'Title Bar Style', 'minimog' ),
			'placeholder' => esc_html__( 'Use Global Setting', 'minimog' ),
			'options'     => Minimog_Title_Bar::instance()->get_list( true ),
			'default'     => Minimog_Redux::get_default_setting( 'blog_single_title_bar_layout' ),
		),
		array(
			'id'     => 'section_start_blog_single_sidebar',
			'type'   => 'tm_heading',
			'title'  => esc_html__( 'Sidebar Settings', 'minimog' ),
			'indent' => true,
		),
		array(
			'id'      => 'post_page_sidebar_1',
			'type'    => 'select',
			'title'   => esc_html__( 'Sidebar 1', 'minimog' ),
			'options' => $registered_sidebars,
			'default' => Minimog_Redux::get_default_setting( 'post_page_sidebar_1' ),
		),
		array(
			'id'      => 'post_page_sidebar_2',
			'type'    => 'select',
			'title'   => esc_html__( 'Sidebar 2', 'minimog' ),
			'options' => $registered_sidebars,
			'default' => Minimog_Redux::get_default_setting( 'post_page_sidebar_2' ),
		),
		array(
			'id'      => 'post_page_sidebar_position',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Sidebar Position', 'minimog' ),
			'options' => $sidebar_positions,
			'default' => Minimog_Redux::get_default_setting( 'post_page_sidebar_position' ),
		),
		array(
			'id'      => 'post_page_sidebar_style',
			'type'    => 'select',
			'title'   => esc_html__( 'Sidebar Style', 'minimog' ),
			'options' => Minimog_Sidebar::instance()->get_supported_style_options(),
			'default' => Minimog_Redux::get_default_setting( 'post_page_sidebar_style' ),
		),
		array(
			'id'     => 'section_start_blog_single_layout',
			'type'   => 'tm_heading',
			'title'  => esc_html__( 'Other Settings', 'minimog' ),
			'indent' => true,
		),
		array(
			'id'      => 'single_post_related_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Related Post', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_related_enable' ),
		),
		array(
			'id'            => 'single_post_related_number',
			'title'         => esc_html__( 'Number of related posts item', 'minimog' ),
			'type'          => 'slider',
			'default'       => Minimog_Redux::get_default_setting( 'single_post_related_number' ),
			'min'           => 1,
			'max'           => 30,
			'step'          => 1,
			'display_value' => 'text',
			'required'      => array(
				[ 'single_post_related_enable', '=', '1' ],
			),
		),
		array(
			'id'      => 'single_post_feature_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Featured Image', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_feature_enable' ),
		),
		array(
			'id'      => 'single_post_title_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Post Title', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_title_enable' ),
		),
		array(
			'id'      => 'single_post_categories_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Categories', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_categories_enable' ),
		),
		array(
			'id'      => 'single_post_tags_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Tags', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_tags_enable' ),
		),
		array(
			'id'      => 'single_post_date_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Post Meta Date', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_date_enable' ),
		),
		array(
			'id'      => 'single_post_author_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Post Meta Author', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_author_enable' ),
		),
		array(
			'id'      => 'single_post_share_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Sharing', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_share_enable' ),
		),
		array(
			'id'      => 'single_post_author_box_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Author Info Box', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_author_box_enable' ),
		),
		array(
			'id'      => 'single_post_pagination_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Previous/Next Pagination', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_pagination_enable' ),
		),
		array(
			'id'      => 'single_post_comment_enable',
			'type'    => 'button_set',
			'title'   => esc_html__( 'Comments List/Form', 'minimog' ),
			'options' => array(
				'0' => esc_html__( 'Hide', 'minimog' ),
				'1' => esc_html__( 'Show', 'minimog' ),
			),
			'default' => Minimog_Redux::get_default_setting( 'single_post_comment_enable' ),
		),
	),
) );
