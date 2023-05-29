<?php

// global
global $post;

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

// WP_Query arguments
$args = array(
	'post_type'      => 'project',
	'post_status'    => 'publish',
	'order'          => 'ASC',
	'orderby'        => 'menu_order',
	'posts_per_page' => -1,
);

// No more PHP warnings
$args['post__in'] = array();

// Get the saved testimonial post IDs
foreach ( $slider_options['project-carousel'] as $ky => $v ) {
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
		$project_options = get_post_meta( $post->ID, strtolower( MT_THEME_NAME ) . '_options', true );

		if ( $project_options ) {

			// start building the return string

			$return_string .= '<div class="work">';

			// get image URL from attachment ID
			$main_image = wp_get_attachment_image_src( $project_options['project-main-image'], 'project-slider-main-image' ); // (theme options array[value], image_size)
			$logo_image = wp_get_attachment_image_src( $project_options['project-client-logo'], 'project-slider-logo-image' );

			$return_string .= '<img class="lazyOwl" data-src="' . $main_image[0] . '">';

								$return_string .= '<div class="logo-background">';
			if ( $logo_image ) {
				$return_string .= '<img class="lazyOwl" data-src="' . esc_url( $logo_image[0] ) . '">';
			} else {
				$return_string .= esc_html( get_the_title() );
			}

								$return_string .= '</div>';

								$return_string .= '<div class="work-description">';
					$return_string             .= '<a class="work-project-link" href="' . esc_url( get_the_permalink() ) . '"><span class="work-description-icon fa fa-eye"><em>' . esc_html( mt_get_theme_option( 'project-shortcode-hover' ) ) . '</em></span></a>';
				$return_string                 .= '</div>';

			$return_string .= '</div><!--/.work-->';


		}
	}// End while().

	$return_string .= '</div><!--/owl-carousel-->';

} else {
	echo __( 'No posts found !', 'regina' );
}// End if().

// Restore original Post Data
wp_reset_postdata();
