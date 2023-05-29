<?php

// Default slider option values, if user left them empty
$slider_options['slider-transition'] = isset( $slider_options['slider-transition'] ) ? $slider_options['slider-transition'] : 'horizontal';
$slider_options['slider-speed']      = isset( $slider_options['slider-speed'] ) ? $slider_options['slider-speed'] : '500';
$slider_options['slider-random']     = isset( $slider_options['slider-random'] ) ? $slider_options['slider-random'] : 'false';
$slider_options['slider-captions']   = isset( $slider_options['slider-captions'] ) ? $slider_options['slider-captions'] : 'false';
$slider_options['slider-controls']   = isset( $slider_options['slider-controls'] ) ? $slider_options['slider-controls'] : 'false';
$slider_options['slider-pager']      = isset( $slider_options['slider-pager'] ) ? $slider_options['slider-pager'] : 'false';
$slider_options['slider-auto']       = isset( $slider_options['slider-auto'] ) ? $slider_options['slider-auto'] : 'false';
$slider_options['slider-pause']      = isset( $slider_options['slider-pause'] ) ? $slider_options['slider-pause'] : 'false';

/* Hide settings */
$hide_class = '';
if ( 'false' != $atts['hide_xs'] ) {
	$hide_class .= ' hidden-xs';
}
if ( 'false' != $atts['hide_sm'] ) {
	$hide_class .= ' hidden-sm';
}
if ( 'false' != $atts['hide_md'] ) {
	$hide_class .= ' hidden-md';
}
if ( 'false' != $atts['hide_lg'] ) {
	$hide_class .= ' hidden-lg';
}

//start building the return string
$return_string = '<ul class="bxSlider macho' . $hide_class . '" id="bxSlider-' . sanitize_key( $id ) . '" data-slider-transition="' . esc_html( $slider_options['slider-transition'] ) . '" data-slider-id="' . sanitize_key( $id ) . '"
data-slider-speed="' . esc_html( $slider_options['slider-speed'] ) . '" data-slider-random="' . esc_html( $slider_options['slider-random'] ) . '"
data-slider-captions="' . esc_html( $slider_options['slider-captions'] ) . '" data-slider-controls="' . esc_html( $slider_options['slider-controls'] ) . '"
data-slider-pager="' . esc_html( $slider_options['slider-pager'] ) . '" data-slider-auto="' . esc_html( $slider_options['slider-auto'] ) . '" data-slider-single-item="0" data-slider-pause="' . esc_attr( $slider_options['slider-pause'] ) . '">';

foreach ( $slider_options['slider-fields'] as $key => $group_option ) {

	// start slide
	$return_string .= '<li>';

	// with caption
	if ( isset( $slider_options['slider-captions'] ) && isset( $group_option['caption'] ) ) {

		// start url
		if ( '' !== $group_option['url'] ) {
			$return_string .= '<a href="' . esc_url( $group_option['url'] ) . '">';
		}
		$return_string .= '<img src="' . esc_url( wp_get_attachment_url( $group_option['image'] ) ) . '" title="' . ( isset( $group_option['caption'] ) ? esc_html( $group_option['caption'] ) : '' ) . '" >';

		// end url
		if ( '' !== $group_option['url'] ) {
			$return_string .= '</a>';
		}
	} else { // no caption

		// start url
		if ( isset( $group_option['url'] ) ) {
			$return_string .= '<a href="' . ( isset( $group_option['url'] ) ? esc_url( $group_option['url'] ) : '' ) . '">';
		}

		$return_string .= '<img src="' . esc_url( wp_get_attachment_url( $group_option['image'] ) ) . '">';

		// end url
		if ( isset( $group_option['url'] ) ) {
			$return_string .= '</a>';
		}
	}
	// end slide
	$return_string .= '</li>';
}// End foreach().
$return_string .= '</ul>';

