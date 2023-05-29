<?php

// global
global $post;

// Default carousel option values, if user left them empty
$slider_options['carousel-items']       = isset( $slider_options['carousel-items'] ) ? $slider_options['carousel-items'] : 1;
$slider_options['carousel-slide-speed'] = isset( $slider_options['carousel-slide-speed'] ) ? $slider_options['carousel-slide-speed'] : 200;
$slider_options['carousel-auto-play']   = isset( $slider_options['carousel-auto-play'] ) ? $slider_options['carousel-auto-play'] : false;
$slider_options['carousel-navigation']  = isset( $slider_options['carousel-navigation'] ) ? $slider_options['carousel-navigation'] : false;
$slider_options['carousel-pagination']  = isset( $slider_options['carousel-pagination'] ) ? $slider_options['carousel-pagination'] : false;

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

$return_string = '<div class="owlCarousel testimonial-carousel' . $hide_class . '" id="owlCarousel-' . sanitize_key( $id ) . '" data-slider-id="' . sanitize_key( $id ) . '" data-slider-items="' . esc_html( $slider_options['carousel-items'] ) . '" data-slider-speed="' . esc_html( $slider_options['carousel-slide-speed'] ) . '"
data-slider-auto-play="' . $slider_options['carousel-auto-play'] . '" data-slider-pagination="' . $slider_options['carousel-pagination'] . '" data-slider-navigation="' . $slider_options['carousel-navigation'] . '" data-slider-single-item="0">';

foreach ( $slider_options['testimonials-carousel'] as $key => $testimonial_options ) {

				$return_string .= '<div>';
				$return_string .= '<div class="media">';

				$return_string .= '<div class="media-top align-center">';

					// get image URL from attachment ID
					$att_image = wp_get_attachment_image_src( $testimonial_options['image'], array( 92, 92 ) );

	if ( $att_image ) {

		# If we have a testimonial URL, link the image and output the new return mark-up
		if ( array_key_exists( 'url', $testimonial_options ) && ! empty( $testimonial_options['url'] ) ) {
			$return_string .= '<a href="' . esc_url( $testimonial_options['url'] ) . '"><img class="testimonials-picture lazyOwl" data-src="' . esc_url( $att_image[0] ) . '"></a>';
		} else {
			# No URL :(
			$return_string .= '<img class="testimonials-picture lazyOwl" data-src="' . esc_url( $att_image[0] ) . '">';
		}
	}

					$return_string .= '</div><!--/.media-left.media-middle-->';

					$return_string .= '<div class="media-body">';

						$return_string .= '<p class="align-center">';
	if ( isset( $testimonial_options['text'] ) && ! empty( $testimonial_options['text'] ) ) {

		$return_string .= esc_html( $testimonial_options['text'] );

	} else {
		$return_string .= __( 'Please enter testimonial text', 'regina' );
	}
						$return_string .= '</p>';
						$return_string .= '<div class="media-heading align-center">';
	if ( isset( $testimonial_options['person'] ) && ! empty( $testimonial_options['person'] ) || isset( $testimonial_options['company'] ) && ! empty( $testimonial_options['company'] ) ) {

		if ( array_key_exists( 'url', $testimonial_options ) && ! empty( $testimonial_options['url'] ) ) {
				$return_string .= '<a href="' . esc_url( $testimonial_options['url'] ) . '"><span class="mt-person-name">' . esc_html( $testimonial_options['person'] ) . '</span></a>';
				$return_string .= ' - ';
				$return_string .= '<span class="mt-company-name">' . esc_html( $testimonial_options['company'] ) . '</span>';
		} else {
			# No URL :(
			$return_string .= '<span class="mt-person-name">' . esc_html( $testimonial_options['person'] ) . '</span>';
			$return_string .= ' - ';
			$return_string .= '<span class="mt-company-name">' . esc_html( $testimonial_options['company'] ) . '</span>';
		}
	} else {
		$return_string .= __( 'Please enter testimonial person name.', 'regina' );
	}

				$return_string .= '</div><!--/.media-heading-->';
				$return_string .= '</div><!--/.media-body-->';
				$return_string .= '</div><!--/.media-->';
				$return_string .= '</div>';
}// End foreach().



	$return_string .= '</div><!--/owl-carousel-->';

// Restore original Post Data
wp_reset_postdata();
