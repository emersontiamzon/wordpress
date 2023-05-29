<?php

// Default carousel option values, if user left them empty
$slider_options['carousel-items']       = isset( $slider_options['carousel-items'] ) ? $slider_options['carousel-items'] : '1';
$slider_options['carousel-slide-speed'] = isset( $slider_options['carousel-slide-speed'] ) ? $slider_options['carousel-slide-speed'] : '200';
$slider_options['carousel-auto-play']   = isset( $slider_options['carousel-auto-play'] ) ? $slider_options['carousel-auto-play'] : '0';
$slider_options['carousel-navigation']  = isset( $slider_options['carousel-navigation'] ) ? $slider_options['carousel-navigation'] : '0';
$slider_options['carousel-pagination']  = isset( $slider_options['carousel-pagination'] ) ? $slider_options['carousel-pagination'] : '0';

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

// start building the return string
$return_string = '<div class="owlCarousel project-carousel' . $hide_class . '" id="owlCarousel-' . sanitize_key( $id ) . '" data-slider-id="' . sanitize_key( $id ) . '" data-slider-items="' . esc_html( $slider_options['carousel-items'] ) . '" data-slider-speed="' . esc_html( $slider_options['carousel-slide-speed'] ) . '"
data-slider-auto-play="' . esc_html( $slider_options['carousel-auto-play'] ) . '" data-slider-navigation="' . esc_html( $slider_options['carousel-navigation'] ) . '" data-slider-pagination="' . esc_html( $slider_options['carousel-pagination'] ) . '" data-slider-single-item="0">';

foreach ( $slider_options['carousel-fields'] as $key => $group_option ) {

	$return_string     .= '<div class="item">';
		$return_string .= '<img class="lazyOwl" data-src="' . esc_url( wp_get_attachment_url( $group_option['image'] ) ) . '">';
	$return_string     .= '</div><!--/item -->';
}

$return_string .= '</div><!--/owl-carousel-->';

