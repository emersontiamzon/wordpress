<?php

//Add layout pieces
add_action( 'wp_head', 'antreas_section_layout', 9 );
function antreas_section_layout( $data ) {
	add_action( 'antreas_top', 'antreas_top_menu' );
	add_action( 'antreas_top', 'antreas_cart' );
	add_action( 'antreas_top', 'antreas_languages' );
	add_action( 'antreas_top', 'antreas_social_links' );
	add_action( 'antreas_header', 'antreas_logo' );
	add_action( 'antreas_header', 'antreas_menu_toggle' );
	add_action( 'antreas_header', 'antreas_menu' );
	add_action( 'antreas_title', 'antreas_page_title' );
	add_action( 'antreas_title', 'antreas_breadcrumb' );
	add_action( 'antreas_subfooter', 'antreas_subfooter' );
	add_action( 'antreas_footer', 'antreas_footer_menu' );
	add_action( 'antreas_footer', 'antreas_footer' );

	//Add homepage sections
	$order = apply_filters( 'antreas_homepage_order', antreas_get_option( 'home_order' ) );
	$array = explode( ',', $order );
	$count = 100;
	$hook  = 'antreas_before_main';
	foreach ( $array as $current_value ) {
		if ( trim( $current_value ) ) {
			if ( $current_value == 'content' ) {
				$hook  = 'antreas_after_main';
				$count = 100;
			} elseif ( function_exists( 'antreas_section_' . $current_value ) ) {	
				add_action( $hook, 'antreas_section_' . $current_value, $count );
			}
			$count += 100;
		}
	}
}


//Displays the homepage slider
if ( ! function_exists( 'antreas_section_slider' ) ) {
	function antreas_section_slider() {
		if ( defined( 'CPOTHEME_USE_SLIDES' ) && CPOTHEME_USE_SLIDES == true ) {
			if ( antreas_show_section( 'slider' ) ) {
				antreas_get_section( 'template-parts/homepage', 'slider' );
			}
		}
	}
}


//Displays the homepage features
if ( ! function_exists( 'antreas_section_features' ) ) {
	function antreas_section_features() {
		if ( defined( 'CPOTHEME_USE_FEATURES' ) && CPOTHEME_USE_FEATURES == true ) {
			if ( antreas_show_section( 'features' ) ) {
				get_template_part( 'template-parts/homepage', 'features' );
			}
		}
	}
}


//Displays the homepage featured posts and pages
if ( ! function_exists( 'antreas_section_featured' ) ) {
	function antreas_section_featured() {
		if ( defined( 'CPOTHEME_USE_PAGES' ) && CPOTHEME_USE_PAGES == true ) {
			if ( is_front_page() ) {
				get_template_part( 'template-parts/homepage', 'featured' );
			}
		}
	}
}


//Displays the homepage tagline
if ( ! function_exists( 'antreas_section_tagline' ) ) {
	function antreas_section_tagline() {
		if ( antreas_show_section( 'tagline' ) ) {
			if ( antreas_get_option( 'home_tagline' ) != '' || antreas_get_option( 'home_tagline_content' ) != '' ) {
				get_template_part( 'template-parts/homepage', 'tagline' );
			}
		}
	}
}


//Displays the homepage portfolio
if ( ! function_exists( 'antreas_section_portfolio' ) ) {
	function antreas_section_portfolio() {
		if ( defined( 'CPOTHEME_USE_PORTFOLIO' ) && CPOTHEME_USE_PORTFOLIO == true ) {
			if ( antreas_show_section( 'portfolio' ) ) {
				get_template_part( 'template-parts/homepage', 'portfolio' );
			}
		}
	}
}


//Displays the homepage products from WC or EDD
if ( ! function_exists( 'antreas_section_products' ) ) {
	function antreas_section_products() {
		if ( defined( 'CPOTHEME_USE_PRODUCTS' ) && CPOTHEME_USE_PRODUCTS == true ) {
			if ( antreas_show_section( 'products' ) && function_exists( 'is_shop' ) ) {
				antreas_get_section( 'template-parts/homepage', 'products' );
			}
		}
	}
}


//Displays the homepage services
if ( ! function_exists( 'antreas_section_services' ) ) {
	function antreas_section_services() {
		if ( defined( 'CPOTHEME_USE_SERVICES' ) && CPOTHEME_USE_SERVICES == true ) {
			if ( antreas_show_section( 'services' ) ) {
				get_template_part( 'template-parts/homepage', 'services' );
			}
		}
	}
}


//Displays the homepage services
if ( ! function_exists( 'antreas_section_about' ) ) {
	function antreas_section_about() {
		if ( defined( 'CPOTHEME_USE_ABOUT' ) && CPOTHEME_USE_ABOUT == true ) {
			if ( antreas_show_section( 'about' ) ) {
				get_template_part( 'template-parts/homepage', 'about' );
			}
		}
	}
}


//Displays the homepage team
if ( ! function_exists( 'antreas_section_team' ) ) {
	function antreas_section_team() {
		if ( defined( 'CPOTHEME_USE_TEAM' ) && CPOTHEME_USE_TEAM == true ) {
			if ( antreas_show_section( 'team' ) ) {
				get_template_part( 'template-parts/homepage', 'team' );
			}
		}
	}
}


//Displays the homepage testimonials
if ( ! function_exists( 'antreas_section_testimonials' ) ) {
	function antreas_section_testimonials() {
		if ( defined( 'CPOTHEME_USE_TESTIMONIALS' ) && CPOTHEME_USE_TESTIMONIALS == true ) {
			if ( antreas_show_section( 'testimonials' ) ) {
				get_template_part( 'template-parts/homepage', 'testimonials' );
			}
		}
	}
}


//Displays the homepage clients
if ( ! function_exists( 'antreas_section_clients' ) ) {
	function antreas_section_clients() {
		if ( defined( 'CPOTHEME_USE_CLIENTS' ) && CPOTHEME_USE_CLIENTS == true ) {
			if ( antreas_show_section( 'clients' ) ) {
				get_template_part( 'template-parts/homepage', 'clients' );
			}
		}
	}
}


//Displays the homepage contact
if ( ! function_exists( 'antreas_section_contact' ) ) {
	function antreas_section_contact() {
		if ( defined( 'CPOTHEME_USE_CONTACT' ) && CPOTHEME_USE_CONTACT == true ) {
			if ( antreas_show_section( 'contact' ) ) {
				get_template_part( 'template-parts/homepage', 'contact' );
			}
		}
	}
}


//Displays the shortcode section
if ( ! function_exists( 'antreas_section_shortcode' ) ) {
	function antreas_section_shortcode() {
		if ( defined( 'CPOTHEME_USE_SHORTCODE' ) && CPOTHEME_USE_SHORTCODE == true ) {
			if ( antreas_show_section( 'shortcode' ) ) {
				get_template_part( 'template-parts/homepage', 'shortcode' );
			}
		}
	}
}


//Get a section
if ( ! function_exists( 'antreas_get_section' ) ) {
	function antreas_get_section( $template, $part ) {
		$name = $template . '-' . $part . '.php';
		if ( locate_template( $name ) ) {
			get_template_part( $template, $part );
		} else {
			load_template( ANTREAS_CORE . '/templates/' . $name, false );
		}
	}
}


if ( ! function_exists( 'antreas_show_section' ) ) {
	function antreas_show_section( $section ) {
		$show_section = false;

		$show_pages = antreas_get_option( $section . '_show' );
		$exclude_pages = antreas_get_option( $section . '_exclude' );

		// if show_pages option is not available in the db, then show the section.
		if ( $show_pages === '' ) {
			return true;
		}

		if ( in_array( 'all', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_front_page() && in_array( 'homepage', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_singular( 'post' ) && in_array( 'all_posts', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_singular( 'page' ) && in_array( 'all_pages', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_singular( 'cpo_portfolio' ) && in_array( 'all_portfolio_items', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_singular( 'cpo_service' ) && in_array( 'all_services', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_404() && in_array( '404_page', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_archive() && in_array( 'archive', $show_pages ) ) {
			$show_section = true;
		}
		if ( is_search() && in_array( 'search', $show_pages ) ) {
			$show_section = true;
		}
		if ( in_array( get_the_ID(), $show_pages ) ) {
			$show_section = true;
		}

		// now exclude.
		if ( in_array( 'all', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_front_page() && in_array( 'homepage', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_singular( 'post' ) && in_array( 'all_posts', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_singular( 'page' ) && in_array( 'all_pages', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_singular( 'cpo_portfolio' ) && in_array( 'all_portfolio_items', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_singular( 'cpo_service' ) && in_array( 'all_services', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_404() && in_array( '404_page', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_archive() && in_array( 'archive', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( is_search() && in_array( 'search', $exclude_pages ) ) {
			$show_section = false;
		}
		if ( in_array( get_the_ID(), $exclude_pages ) ) {
			$show_section = false;
		}

		return $show_section;
	}
}


