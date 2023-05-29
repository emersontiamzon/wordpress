<?php

// global
global $post;

// Default carousel option values, if user left them empty
$slider_options['carousel-items']       = isset( $slider_options['carousel-items'] ) ? $slider_options['carousel-items'] : '1';
$slider_options['carousel-slide-speed'] = isset( $slider_options['carousel-slide-speed'] ) ? $slider_options['carousel-slide-speed'] : '200';
$slider_options['carousel-auto-play']   = isset( $slider_options['carousel-auto-play'] ) ? $slider_options['carousel-auto-play'] : 'false';
$slider_options['carousel-navigation']  = isset( $slider_options['carousel-navigation'] ) ? $slider_options['carousel-navigation'] : 'false';
$slider_options['carousel-pagination']  = isset( $slider_options['carousel-pagination'] ) ? $slider_options['carousel-pagination'] : 'false';

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

// WP_Query arguments
$args = array(
	'post_type'   => 'testimonial',
	'post_status' => 'publish',
	'order'       => 'ASC',
	'orderby'     => 'menu_order',
);

// No more PHP warnings
$args['post__in'] = array();

// Get the saved testimonial post IDs
foreach ( $slider_options['testimonials-carousel'] as $ky => $v ) {
	array_push( $args['post__in'], $v ); // push them to our array
}


// The Query
$query = new WP_Query( $args );


// The Loop
if ( $query->have_posts() ) {


	$return_string = '<div class="owlCarousel project-carousel' . $hide_class . '" id="owlCarousel-' . sanitize_key( $id ) . '" data-slider-id="' . sanitize_key( $id ) . '" data-slider-items="' . esc_html( $slider_options['carousel-items'] ) . '" data-slider-speed="' . esc_html( $slider_options['carousel-slide-speed'] ) . '"
data-slider-auto-play="' . $slider_options['carousel-auto-play'] . '" data-slider-navigation="' . $slider_options['carousel-navigation'] . '" data-slider-pagination="' . $slider_options['carousel-pagination'] . '" data-slider-single-item="0">';

	while ( $query->have_posts() ) {

		$query->the_post();

		// get saved post meta
		$testimonial_options = get_post_meta( $post->ID, strtolower( MT_THEME_NAME ) . '_options', true );

		if ( $testimonial_options ) {

			// start building the return string

			$return_string .= '<div class="app-testimonial">';
			$return_string .= '<div class="app-testimonial-heading">';

			// get image URL from attachment ID
			$att_image = wp_get_attachment_image_src( $testimonial_options['testimonial-person-image'], 'small-testimonial-image' );

			$return_string .= '<img class="app-testimonial-image" src="' . esc_url( $att_image[0] ) . '">';

			if ( isset( $testimonial_options['testimonial-person-name'] ) && ! empty( $testimonial_options['testimonial-person-name'] ) ) {
				$return_string .= '<h4>' . esc_html( $testimonial_options['testimonial-person-name'] ) . '</h4>';
			} else {
				$return_string .= __( 'Please enter testimonial person name.', 'regina' );
			}

			$return_string .= '<ul class="app-testimonial-rating">';

			// star ratings (1-5)
			switch ( $testimonial_options['testimonial-person-rating'] ) {

				case '1':
					$return_string .= '<li><span class="fa fa-star"></span></li>'; // 1 full
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>'; // 4 empty
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>';
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>';
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>';
					break;

				case '2':
					$return_string .= '<li><span class="fa fa-star"></span></li>'; // 2 full
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>'; // 3 empty
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>';
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>';
					break;

				case '3':
					$return_string .= '<li><span class="fa fa-star"></span></li>'; // 3 full
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>'; // 2 empty
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>';
					break;

				case '4':
					$return_string .= '<li><span class="fa fa-star"></span></li>'; // 4 full
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li class="star-empty"><span class="fa fa-star"></span></li>'; // 1 empty
					break;

				case '5':
					$return_string .= '<li><span class="fa fa-star"></span></li>'; // 5 full
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					$return_string .= '<li><span class="fa fa-star"></span></li>';
					break;
			}// End switch().

			$return_string .= '</ul>';

			$return_string .= '</div><!--/.app-testimonial-heading-->';

			if ( isset( $testimonial_options['testimonial-person-text'] ) && ! empty( $testimonial_options['testimonial-person-text'] ) ) {
				$return_string .= '<p class="app-testimonial-text">';
				$return_string .= '" ' . esc_html( $testimonial_options['testimonial-person-text'] ) . ' "';
				$return_string .= '</p>';
			} else {
				$return_string .= __( 'Please enter testimonial text', 'regina' );
			}

			$return_string .= '</div><!--/app-testimonial-->';
		}// End if().
	}// End while().

	$return_string .= '</div><!--/owl-carousel-->';

} else {
	echo __( 'No posts found !', 'regina' );
}// End if().

// Restore original Post Data
wp_reset_postdata();
