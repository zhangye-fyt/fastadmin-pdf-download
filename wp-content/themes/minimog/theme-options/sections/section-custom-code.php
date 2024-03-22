<?php

Redux::set_section( Minimog_Redux::OPTION_NAME, array(
	'title'  => esc_html__( 'Custom Code', 'minimog' ),
	'id'     => 'panel_custom_code',
	'icon'   => 'eicon-custom-css',
	'fields' => array(
		array(
			'id'       => 'custom_js_enable',
			'type'     => 'switch',
			'title'    => esc_html__( 'Custom Javascript?', 'minimog' ),
			'subtitle' => esc_html__( 'Turn on to enable custom Javascript', 'minimog' ),
			'default'  => false,
		),
		array(
			'title'    => 'Javascript Code',
			'subtitle' => 'Paste your Javascript code here.',
			'id'       => 'custom_js',
			'type'     => 'ace_editor',
			'mode'     => 'javascript',
			'options'  => array( 'minLines' => 20 ),
			'required' => array( 'custom_js_enable', '=', true ),
		),
		array(
			'id'       => 'custom_css_enable',
			'type'     => 'switch',
			'title'    => esc_html__( 'Custom CSS?', 'minimog' ),
			'subtitle' => esc_html__( 'Turn on to enable custom CSS', 'minimog' ),
			'default'  => false,
		),
		array(
			'title'    => 'CSS Code',
			'subtitle' => '<p>Add your own CSS code here to customize the appearance and layout of your site. <a href="https://www.w3schools.com/css/default.asp" target="_blank">Learn more about CSS</a></p><p>To make your CSS code run on mobile devices only then wrap your css code in media query. <a href="https://www.w3schools.com/css/css3_mediaqueries.asp" target="_blank">Learn more Media Query</a> For eg:</p>
			<p><code>@media screen and (max-width: 767px) {<br/>
				  &nbsp;&nbsp;&nbsp;&nbsp;.sidebar {<br/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;display: none;<br/>
				  &nbsp;&nbsp;&nbsp;&nbsp;}<br/>
				}</code></p>',
			'id'       => 'custom_css',
			'type'     => 'ace_editor',
			'mode'     => 'css',
			'theme'    => 'monokai',
			'options'  => array( 'minLines' => 20 ),
			'required' => array( 'custom_css_enable', '=', true ),
		),
	),
) );
