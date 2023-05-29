<?php

if ( ! function_exists( 'regina_services_shortcode' ) ) {

	function regina_services_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'number'    => 4,
			'link_text' => '',
			'link_url'  => '#',
		), $atts );

		$html = '<div id="services-block" class="text-center home content-block">';

		$args = array(
			'post_type'      => 'service',
			'post_status'    => 'publish',
			'posts_per_page' => $atts['number'],
		);

		$services = new WP_Query( $args );
		$i        = 1;

		while ( $services->have_posts() ) : $services->the_post();

			$icon           = mt_get_page_option( get_the_ID(), 'service-icon' );
			$icon_image_id  = mt_get_page_option( get_the_ID(), 'service-icon-image' );
			$icon_image     = wp_get_attachment_image_src( $icon_image_id, 'full' );
			$read_more_text = mt_get_page_option( get_the_ID(), 'service-read-more-text' );
			$read_more_url  = mt_get_page_option( get_the_ID(), 'service-read-more-link' );
			$html .= '<div class="col-lg-3 col-sm-6">';
			$html .= '<div class="service">';
			if ( $icon != '' ||  $icon_image != '' ):
				$html .= '<div class="icon">';
				if ( $read_more_url != '' ) {
					$html .= '<a href="' . $read_more_url . '" class="link">';
				}
				if ( $icon_image != '' ) {
					$html .= '<img src="' . $icon_image[0] . '">';
				} else {
					$html .= '<span class="' . str_replace( '|', ' ', $icon ) . '"></span>';
				}
				if ( $read_more_url != '' ) {
					$html .= '</a> ';
				}
				$html .= '</div> ';
			endif;

			$html .= '<h3>' . get_the_title() . '</h3>';
			$html .= '<p>' . get_the_content() . '</p>';
			$html .= '<br>';
			if ( $read_more_text != '' && $read_more_url != '' ):
				$html .= '<a href="' . $read_more_url . '" class="link small">' . $read_more_text . ' <span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
			endif;
			$html .= '</div><!--.service-->';
			$html .= '</div><!--.col-lg-3-->';

			if ( $i == 4 ) {
				$html .= '<div class="clear"></div>';
			}


			$i++;

		endwhile;

		if ( $atts['link_url'] != '' && $atts['link_text'] != '' ) {
			$html .= '<div class="col-xs-12 text-center">';
			$html .= '<a href="' . $atts['link_url'] . '" class="button dark outline">' . $atts['link_text'] . ' <span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
			$html .= '</div>';
		}
		$html .= '</div>';

		return $html;

	}

}

if ( ! function_exists( 'regina_members_shortcode' ) ) {

	function regina_members_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'number'    => 4,
			'link_text' => '',
			'link_url'  => '#',
		), $atts );

		$html = '<div id="team-block" class="text-center">';

		$args = array(
			'post_type'      => 'member',
			'post_status'    => 'publish',
			'posts_per_page' => $atts['number'],
		);

		$members = new WP_Query( $args );
		$i       = 1;
		while ( $members->have_posts() ) : $members->the_post();

			$member_image_id  = mt_get_page_option( get_the_ID(), 'member-image' );
			$member_image     = wp_get_attachment_image_src( $member_image_id, 'team-image-sizes' );
			$member_hospitals = mt_get_page_option( get_the_ID(), 'member-hospitals' );
			$image_meta       = get_post_meta( $member_image_id );

			$html .= '<div class="col-lg-3 col-sm-6">';
			$html .= '<div class="team-member">';
			if ( isset( $member_image[0] ) ):
				if ( array_key_exists( '_wp_attachment_image_alt', $image_meta ) ) {
					$html .= '<img data-original="' . $member_image[0] . '" alt="' . $image_meta['_wp_attachment_image_alt']['0'] . '" class="lazy">';
				} else {
					$html .= '<img data-original="' . $member_image[0] . '" class="lazy">';
				}
			endif;
			$html .= '<div class="inner">';
			$html .= '<h4 class="name">' . get_the_title() . '</h4>';
			$html .= '<p class="position"><small>' . $member_hospitals . '</small></p>';
			$html .= '</div>';
			$html .= '<div class="hover">';
			$html .= '<div class="description">';
			$html .= '<p>' . substr( get_the_excerpt(), 0, 200 ) . '...</p>';
			$html .= '</div>';
			$html .= '<div class="read-more">';
			$html .= '<a href="' . get_permalink() . '" class="button white outline">'.__( 'Read more', 'regina' ).' <span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
			$html .= '</div>';
			$html .= '</div><!--.hover-->';
			$html .= '</div><!--.team-member-->';
			$html .= '</div><!--.col-lg-3-->';

			if ( $i == 4 ) {
				$html .= '<div class="clear"></div>';
			}

			$i++;
		endwhile;

		if ( $atts['link_url'] != '' && $atts['link_text'] != '' ) {
			$html .= '<div class="col-xs-12 text-center">';
			$html .= '<a href="' . $atts['link_url'] . '" class="button dark outline">' . $atts['link_text'] . ' <span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
			$html .= '</div>';
		}
		$html .= '</div>';

		return $html;

	}

}

if ( ! function_exists( 'regina_testimonials_shortcode' ) ) {

	function regina_testimonials_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'number' => 4,
		), $atts );

		$html = '';

		$args = array(
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => $atts['number'],
		);

		$testimonials = new WP_Query( $args );
		if ( $testimonials->have_posts() ) {
			$html .= '<div class="testimonials">';
			$html .= '<span class="icon nc-icon-glyph ui-2_chat-round-content"></span>';
			$html .= '<div id="testimonials-slider">';
			$html .= '<ul class="bxslider">';

			while ( $testimonials->have_posts() ) : $testimonials->the_post();
				$testimonial_image_id         = mt_get_page_option( get_the_ID(), 'testimonial-person-image' );
				$testimonial_meta             = get_post_meta( $testimonial_image_id );
				$testimonial_image            = wp_get_attachment_image_src( $testimonial_image_id, 'full' );
				$testimonial_member_name      = mt_get_page_option( get_the_ID(), 'testimonial-person-name' );
				$testimonial_member_postition = mt_get_page_option( get_the_ID(), 'testimonial-person-position' );

				$html .= '<li>';
				$html .= '<p class="quote">' . get_the_content() . '</p>';
				if ( isset( $testimonial_image[0] ) ) {
					if ( array_key_exists( '_wp_attachment_image_alt', $testimonial_meta ) ) {
						$html .= '<img src="' . $testimonial_image[0] . '"  alt="' . $testimonial_meta['_wp_attachment_image_alt']['0'] . ' width="90">';
					} else {
						$html .= '<img src="' . $testimonial_image[0] . '" width="90">';
					}
				}
				$html .= '<h5 class="name">' . $testimonial_member_name . '</h5>';
				$html .= '<p class="position">' . $testimonial_member_postition . '</p>';
				$html .= '</li>';

			endwhile;
			$html .= '</ul>';
			$html .= '</div><!--#testimonials-slider-->';
			$html .= '</div><!--.testimonials-->';
			$html .= '<div class="clear"></div>';
		}

		return $html;

	}

}

if ( ! function_exists( 'regina_gallery_wrapper_shortcode' ) ) {

	function regina_gallery_wrapper_shortcode( $atts, $content ) {

		$html = '<ul class="images">' . do_shortcode( $content ) . '</ul>';

		return $html;

	}

}

if ( ! function_exists( 'regina_gallery_shortcode' ) ) {

	function regina_gallery_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'image' => '',
		), $atts );


		$html = '';
		$iamge_id = $atts['image'];

		// explode by sep into an array
		$iamge_id = explode( ",", $iamge_id );

		if ( is_array( $iamge_id ) ) {

			// remove whitespaces
			$iamge_id = array_filter( array_map( 'trim', $iamge_id ) );


			foreach ( $iamge_id as $k => $image_id ) {

				$image_meta = get_post_meta( $image_id );
				$image      = wp_get_attachment_image_src( $image_id, 'full' );

				if ( isset( $image[0] ) ) {
					if ( array_key_exists( '_wp_attachment_image_alt', $image_meta ) ) {
						$html .= '<li><img data-original="' . $image[0] . '" alt="' . $image_meta['_wp_attachment_image_alt']['0'] . '" class="lazy"></li>';
					} else {
						$html .= '<li><img data-original="' . $image[0] . '" class="lazy"></li>';
					}
				}
			}
		} else {

			$image_meta = get_post_meta( $iamge_id );
			$image      = wp_get_attachment_image_src( $iamge_id, 'full' );

			if ( isset( $image[0] ) ) {
				if ( array_key_exists( '_wp_attachment_image_alt', $image_meta ) ) {
					$html .= '<li><img data-original="' . $image[0] . '" alt="' . $image_meta['_wp_attachment_image_alt']['0'] . '" class="lazy"></li>';
				} else {
					$html .= '<li><img data-original="' . $image[0] . '" class="lazy"></li>';
				}
			}

		}


		return $html;

	}
}

if ( ! function_exists( 'regina_new_gallery_shortcode' ) ) {

	function regina_new_gallery_shortcode( $atts ) {

		$atts = shortcode_atts( array(
			'image' => '',
		), $atts );

		$html = '';
		$iamge_id = $atts['image'];

		// explode by sep into an array
		$iamge_id = explode( ",", $iamge_id );

		if ( is_array( $iamge_id ) ) {

			// remove whitespaces
			$iamge_id = array_filter( array_map( 'trim', $iamge_id ) );

			foreach ( $iamge_id as $k => $image_id ) {

				$image_meta = get_post_meta( $image_id );
				$image      = wp_get_attachment_image_src( $image_id, 'full' );

				if ( isset( $image[0] ) ) {
					if ( array_key_exists( '_wp_attachment_image_alt', $image_meta ) ) {
						$html .= '<li><img data-original="' . $image[0] . '" alt="' . $image_meta['_wp_attachment_image_alt']['0'] . '" class="lazy"></li>';
					} else {
						$html .= '<li><img data-original="' . $image[0] . '" class="lazy"></li>';
					}
				}
			}
		} else {

			$image_meta = get_post_meta( $iamge_id );
			$image      = wp_get_attachment_image_src( $iamge_id, 'full' );

			if ( isset( $image[0] ) ) {
				if ( array_key_exists( '_wp_attachment_image_alt', $image_meta ) ) {
					$html .= '<li><img data-original="' . $image[0] . '" alt="' . $image_meta['_wp_attachment_image_alt']['0'] . '" class="lazy"></li>';
				} else {
					$html .= '<li><img data-original="' . $image[0] . '" class="lazy"></li>';
				}
			}

		}

		return $html;

	}
}

if ( ! function_exists( 'regina_appointment_button_shortcode' ) ) {

	function regina_appointment_button_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'button_text'  => '',
			'button_style' => '',
			'button_align' => '',
		), $atts );
		$html = '';

		$class = 'button';

		if ( $atts['button_style'] == 'outline' ) {
			$class .= ' outline';
		}
		if ( $atts['button_align'] != 'center' ) {
			$class .= ' pull-' . $atts['button_align'];
		}

		if ( $atts['button_align'] == 'center' ) {
			$html .= '<div class="col-md-12 text-center"><a href="#mt-popup-modal" class="' . $class . '">' . $atts['button_text'] . ' <span class="nc-icon-glyph arrows-1_bold-right"></span></a><div class="clear"></div></div>';
		} else {
			$html .= '<a href="#mt-popup-modal" class="' . $class . '">' . $atts['button_text'] . ' <span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
		}

		return $html;

	}
}

if ( ! function_exists( 'regina_accordion_wrapper_shortcode' ) ) {

	function regina_accordion_wrapper_shortcode( $atts, $content ) {

		$html = '<div class="accordion clearfix"><ul>' . do_shortcode( $content ) . '</ul></div>';

		return $html;

	}
}

if ( ! function_exists( 'regina_accordion_shortcode' ) ) {

	function regina_accordion_shortcode( $atts, $content ) {

		$atts = shortcode_atts( array(
			'accordion_title' => '',
		), $atts );

		$html = '';
		$html .= '<li>';
		$html .= '<a href="#">' . $atts['accordion_title'] . ' <span></span></a>';
		$html .= '<div class="inner">' . do_shortcode( $content ) . '</div>';
		$html .= '</li>';

		return $html;

	}
}

if ( ! function_exists( 'regina_blog_shortcode' ) ) {

	function regina_blog_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'number' => 4,
		), $atts );

		$html = '';

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $atts['number'],
		);

		$posts = new WP_Query( $args );
		if ( $posts->have_posts() ) {
			$html .= '<div id="blog" class="single">';
			$html .= '<div id="related-posts">';

			while ( $posts->have_posts() ) : $posts->the_post();
				$testimonial_image_id         = mt_get_page_option( get_the_ID(), 'testimonial-person-image' );
				$testimonial_image            = wp_get_attachment_image_src( $testimonial_image_id, 'full' );
				$testimonial_member_name      = mt_get_page_option( get_the_ID(), 'testimonial-person-name' );
				$testimonial_member_postition = mt_get_page_option( get_the_ID(), 'testimonial-person-position' );

				$html .= '<div class="col-sm-3">';
				$html .= '<div class="post">';
				$html .= '<a href="' . get_permalink() . '">';
				if ( has_post_thumbnail() ) {
					$url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
					$html .= '<img data-original="' . $url . '" alt="90 medical procedures" class="lazy">';
				}
				$html .= '<div class="inner">';
				$html .= '<h6 class="date">' . get_the_date( 'F d, Y', '', '' ) . '</h6>';
				$html .= '<p class="title">' . get_the_title() . '</p>';
				$html .= '</div>';
				$html .= '</a>';
				$html .= '</div><!--.post-->';
				$html .= '</div><!--.col-sm-4-->';

			endwhile;

			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;

	}

}
if ( ! function_exists( 'regina_container_shortcode' ) ) {

	function regina_container_shortcode( $atts, $content ) {

		$html = '<div class="container">' . do_shortcode( $content ) . '</div>';

		return $html;

	}
}

if ( ! function_exists( 'regina_button_shortcode' ) ) {

	function regina_button_shortcode( $atts ) {
		$atts = shortcode_atts( array(
			'button_text'  => '',
			'button_style' => '',
			'button_align' => '',
			'button_url'   => '',
		), $atts );
		$html = '';

		$class = 'button';

		if ( $atts['button_style'] == 'outline' ) {
			$class .= ' outline';
		}
		if ( $atts['button_align'] != 'center' ) {
			$class .= ' pull-' . $atts['button_align'];
		}

		if ( $atts['button_align'] == 'center' ) {
			$html .= '<div class="col-md-12 text-center"><a href="' . $atts['button_url'] . '" class="' . $class . '">' . $atts['button_text'] . ' <span class="nc-icon-glyph arrows-1_bold-right"></span></a><div class="clear"></div></div>';
		} else {
			$html .= '<a href="' . $atts['button_url'] . '" class="' . $class . '">' . $atts['button_text'] . ' <span class="nc-icon-glyph arrows-1_bold-right"></span></a>';
		}

		return $html;

	}
}

if ( ! function_exists( 'regina_awards_wrapper_shortcode' ) ) {

	function regina_awards_wrapper_shortcode( $atts, $content ) {

		$html = '<div id="awards">' . do_shortcode( $content ) . '</div>';

		return $html;

	}
}

if ( ! function_exists( 'regina_awards_shortcode' ) ) {

	function regina_awards_shortcode( $atts, $content ) {

		$atts = shortcode_atts( array(
			'award_image' => '',
			'award_text'  => '',
		), $atts );

		$iamge_id = $atts['award_image'];


		$html = '';
		$html .= '<div class="col-md-3 col-md-offset-0 col-sm-5 col-xs-8 col-xs-offset-2">';
		$html .= '<div class="award">';
		$html .= '<div class="inner">';
		if ( $iamge_id != '' ) {
			$image = wp_get_attachment_image_src( $iamge_id, 'full' );
			if ( isset( $image[0] ) ) {
				$html .= '<img src="' . $image[0] . '" alt="' . $atts['award_text'] . '" width="200">';
			}
		}
		$html .= '</div>';
		$html .= '<p class="name">' . $atts['award_text'] . '</p>';
		$html .= '</div><!--.award-->';
		$html .= '</div>';

		return $html;

	}
}

if ( ! function_exists( 'regina_section_shortcode' ) ) {

	function regina_section_shortcode( $atts, $content ) {

		$atts = shortcode_atts( array(
			'background' => '',
		), $atts );

		$style = '';

		if ( $atts['background'] != '' ) {
			$style = 'style="background:' . $atts['background'] . '"';
		}

		$html = '<div class="section-with-background" ' . $style . '>' . do_shortcode( $content ) . '<div class="clear"></div></div>';

		return $html;

	}
}