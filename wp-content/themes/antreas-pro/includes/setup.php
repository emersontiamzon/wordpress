<?php

//set settings defaults
add_filter( 'antreas_customizer_controls', 'antreas_customizer_controls' );
function antreas_customizer_controls( $data ) {

	//Layout
	$data['home_order']['default']        = 'slider,features,about,tagline,portfolio,services,products,testimonials,team,clients,contact,shortcode,content';
	$data['slider_height']['default']     = 650;
	$data['features_columns']['default']  = 4;
	$data['portfolio_columns']['default'] = 5;
	$data['services_columns']['default']  = 2;
	$data['team_columns']['default']      = 4;
	$data['clients_columns']['default']   = 5;

	//Typography
	$data['type_headings']['default'] = 'Hind';
	$data['type_nav']['default']      = 'Hind';
	$data['type_body']['default']     = 'Hind';

	//Colors
	$data['primary_color']['default']       = '#22c0e3';
	$data['secondary_color']['default']     = '#424247';
	$data['type_headings_color']['default'] = '#222222';
	$data['type_widgets_color']['default']  = '#222222';
	$data['type_nav_color']['default']      = '#676767';
	$data['type_body_color']['default']     = '#919191';
	$data['type_link_color']['default']     = '#22c0e3';

	return $data;
}


add_filter( 'antreas_background_args', 'antreas_background_args' );
function antreas_background_args( $data ) {
	$data = array(
		'default-color'      => 'eeeeee',
		'default-repeat'     => 'no-repeat',
		'default-position-x' => 'center',
		'default-attachment' => 'fixed',
		'default-size'       => 'cover',
	);

	return $data;
}


add_filter( 'antreas_portfolio_section_args', 'antreas_portfolio_section_args' );
function antreas_portfolio_section_args( $data ) {
	return array( 'spacing' => 'fit' );
}


add_filter( 'antreas_team_section_args', 'antreas_team_section_args' );
function antreas_team_section_args( $data ) {
	return array( 'spacing' => 'narrow' );
}


add_filter( 'antreas_clients_section_args', 'antreas_clients_section_args' );
function antreas_clients_section_args( $data ) {
	return array( 'spacing' => 'narrow' );
}


add_filter( 'antreas_services_section_args', 'antreas_services_section_args' );
function antreas_services_section_args( $data ) {
	return array( 'class' => 'secondary-color-bg dark' );
}