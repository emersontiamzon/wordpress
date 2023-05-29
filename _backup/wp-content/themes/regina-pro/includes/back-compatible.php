<?php

$check_backwards_compatibility = get_option( 'regina_pro_backwards_compatibility' );

// New Slider Control
$slides = get_theme_mod( 'regina_homepage_slider' );
if ( is_array( $slides ) && ! empty( $slides ) && ! isset( $check_backwards_compatibility['customizer-slider'] ) ) {
	$new_slides = array();
	foreach ( $slides as $index => $slide ) {
		$new_slide = $slide;
		if ( isset( $slide['slider_image'] ) && absint( $slide['slider_image'] ) == $slide['slider_image'] ) {
			$new_image = wp_get_attachment_url( $slide['slider_image'] );
			if ( $new_image ) {
				$new_slide['slider_image'] = $new_image;
			}
		}

		array_push( $new_slides, $new_slide );

	}
	$check_backwards_compatibility['customizer-slider'] = 1;
	update_option( 'regina_pro_backwards_compatibility', $check_backwards_compatibility );
	set_theme_mod( 'regina_homepage_slider', $new_slides );
}

$regina_custom_css = get_theme_mod( 'regina_custom_css' );
if ( '' != $regina_custom_css ) {
	wp_update_custom_css_post( $regina_custom_css );
	remove_theme_mod( 'regina_custom_css' );
}

// Check for Custom Fonts
$primary_font   = get_theme_mod( 'regina_theme_primary_font' );
$secondary_font = get_theme_mod( 'regina_theme_secondary_font' );

if ( isset( $primary_font['font-family'] ) ) {
	$new_primary_font = array(
		'selectors'  => 'body,.icon-list li,h5,h6,.button,input[type="text"],textarea,#page-header .title,.ui-datepicker',
		'stylesheet' => 'regina-style',
		'json'       => array(
			'font-family' => $primary_font['font-family'],
		),
	);
	set_theme_mod( 'regina_primary_font', json_encode( $new_primary_font ) );
	remove_theme_mod( 'regina_theme_primary_font' );
}

if ( isset( $secondary_font['font-family'] ) ) {
	$new_secondary_font = array(
		'selectors'  => 'p small,h1, h2, h3, h4',
		'stylesheet' => 'regina-style',
		'json'       => array(
			'font-family' => $secondary_font['font-family'],
		),
	);
	set_theme_mod( 'regina_secondary_font', json_encode( $new_secondary_font ) );
	remove_theme_mod( 'regina_theme_secondary_font' );
}

$current_logo = get_theme_mod( 'regina_logo' );
if ( $current_logo ) {
	$logo_id = mt_get_attachment_id( $current_logo );
	if ( $logo_id ) {
		set_theme_mod( 'custom_logo', $logo_id );
		remove_theme_mod( 'regina_logo' );
	}
}

$footer_columns = get_theme_mod( 'regina_footer_columns' );
if ( '' != $footer_columns ) {

	$values = array(
		'12' => array(
			'columnsCount' => 1,
			'columns'      => array(
				array(
					'index' => 1,
					'span'  => 12,
				),
			),
		),
		'6'  => array(
			'columnsCount' => 2,
			'columns'      => array(
				array(
					'index' => 1,
					'span'  => 6,
				),
				array(
					'index' => 2,
					'span'  => 6,
				),
			),
		),
		'4'  => array(
			'columnsCount' => 3,
			'columns'      => array(
				array(
					'index' => 1,
					'span'  => 4,
				),
				array(
					'index' => 2,
					'span'  => 4,
				),
				array(
					'index' => 3,
					'span'  => 4,
				),
			),
		),
		'3'  => array(
			'columnsCount' => 4,
			'columns'      => array(
				array(
					'index' => 1,
					'span'  => 3,
				),
				array(
					'index' => 2,
					'span'  => 3,
				),
				array(
					'index' => 3,
					'span'  => 3,
				),
				array(
					'index' => 4,
					'span'  => 3,
				),
			),
		),
	);

	if ( isset( $values[ $footer_columns ] ) ) {
		$value = $values[ $footer_columns ];
	} else {
		$value = $values['3'];
	}

	if ( '0' == $footer_columns ) {
		set_theme_mod( 'regina_footer_widgets', 0 );
	}

	set_theme_mod( 'regina_footer_columns_v2', json_encode( $value ) );
	remove_theme_mod( 'regina_footer_columns' );

}
