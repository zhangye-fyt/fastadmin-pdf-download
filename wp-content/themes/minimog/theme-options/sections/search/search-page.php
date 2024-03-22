<?php
Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'      => esc_html__( 'Search Page', 'minimog' ),
	'id'         => 'search_page',
	'subsection' => true,
	'fields'     => array(
		array(
			'id'          => 'search_page_filter',
			'type'        => 'select',
			'title'       => esc_html__( 'Search Results Filter', 'minimog' ),
			'description' => esc_html__( 'Controls the type of content that displays in search results.', 'minimog' ),
			'options'     => [
				'all'     => esc_html__( 'All Post Types and Pages', 'minimog' ),
				'page'    => esc_html__( 'Only Pages', 'minimog' ),
				'post'    => esc_html__( 'Only Blog Posts', 'minimog' ),
				'product' => esc_html__( 'Only Products', 'minimog' ),
			],
			'default'     => 'product',
		),
		array(
			'id'            => 'search_page_number_results',
			'title'         => esc_html__( 'Number of Search Results Per Page', 'minimog' ),
			'description'   => esc_html__( 'Controls the number of search results per page.', 'minimog' ),
			'type'          => 'slider',
			'default'       => 10,
			'min'           => 1,
			'max'           => 100,
			'step'          => 1,
			'display_value' => 'text',
		),
		array(
			'id'          => 'search_page_search_form_display',
			'title'       => esc_html__( 'Search Form Display', 'minimog' ),
			'description' => esc_html__( 'Controls the display of the search form on the search results page.', 'minimog' ),
			'type'        => 'select',
			'options'     => [
				'below'    => esc_html__( 'Below Result List', 'minimog' ),
				'above'    => esc_html__( 'Above Result List', 'minimog' ),
				'disabled' => esc_html__( 'Hide', 'minimog' ),
			],
			'default'     => 'disabled',
		),
		array(
			'id'          => 'search_page_no_results_text',
			'title'       => esc_html__( 'No Results Text', 'minimog' ),
			'description' => esc_html__( 'Enter the text that displays on search no results page.', 'minimog' ),
			'type'        => 'textarea',
			'default'     => esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'minimog' ),
		),
	),
) );
