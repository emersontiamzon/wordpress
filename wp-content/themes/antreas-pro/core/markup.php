<?php


//Displays the blog title and descripion in home or frontpage
if ( ! function_exists( 'antreas_title' ) ) {
	//add_filter('wp_title', 'antreas_title');
	function antreas_title( $title ) {
		global $page, $paged;

		if ( is_feed() ) {
			return $title;
		}

		$separator   = ' | ';
		$description = get_bloginfo( 'description', 'display' );
		$name        = get_bloginfo( 'name' );

		//Homepage title
		if ( $description && ( is_home() || is_front_page() ) ) {
			$full_title = $title . $separator . $description;
		} else {
			$full_title = $title;
		}

		//Page numbers
		if ( $paged >= 2 || $page >= 2 ) {
			$full_title .= ' | ' . sprintf( __( 'Page %s', 'antreas' ), max( $paged, $page ) );
		}

		return $title;
	}
}


//Displays the current page's title. Used in the main banner area.
if ( ! function_exists( 'antreas_page_title' ) ) {
	function antreas_page_title() {
		global $post;
		if ( isset( $post->ID ) ) {
			$current_id = $post->ID;
		} else {
			$current_id = false;
		}
		$title_tag = function_exists( 'is_woocommerce' ) && is_woocommerce() && is_singular( 'product' ) ? 'span' : 'h1';

		echo '<' . $title_tag . ' class="pagetitle-title heading">';
		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			woocommerce_page_title();
		} elseif ( is_category() || is_tag() || is_tax() ) {
			echo single_tag_title( '', true );
		} elseif ( is_author() ) {
			the_author();
		} elseif ( is_date() ) {
			_e( 'Archive', 'antreas' );
		} elseif ( is_404() ) {
			echo __( 'Page Not Found', 'antreas' );
		} elseif ( is_search() ) {
			echo __( 'Search Results for', 'antreas' ) . ' "' . get_search_query() . '"';
		} elseif ( is_home() ) {
			echo get_the_title( get_option( 'page_for_posts' ) );
		} else {
			echo get_the_title( $current_id );
		}
		echo '</' . $title_tag . '>';
	}
}


//Displays the current page's title. Used in the main banner area.
if ( ! function_exists( 'antreas_header_image' ) ) {
	function antreas_header_image() {
		$page_title = antreas_layout_title();
		if ( $page_title != 'minimal' && $page_title != 'none' ) {
			$url = apply_filters( 'antreas_header_image', get_header_image() );
			if ( $url != '' ) {
				return $url;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}


//Displays a Revolution Slider assigned to the current page.
add_action( 'antreas_before_main', 'antreas_header_slider', 5 );
if ( ! function_exists( 'antreas_header_slider' ) ) {
	function antreas_header_slider() {
		if ( function_exists( 'putRevSlider' ) ) {
			$current_id = antreas_current_id();
			if ( is_tax() || is_category() || is_tag() ) {
				$page_slider = antreas_tax_meta( $current_id, 'page_slider' );
			} else {
				$page_slider = get_post_meta( $current_id, 'page_slider', true );
			}

			if ( $page_slider != '0' && $page_slider != '' ) {
				echo '<div id="revslider" class="revslider">';
				putRevSlider( $page_slider );
				echo '</div>';
			}
		}
	}
}


//Display custom favicon
if ( ! function_exists( 'antreas_favicon' ) ) {
	add_action( 'wp_head', 'antreas_favicon' );
	function antreas_favicon() {
		$favicon_url = antreas_get_option( 'general_favicon' );
		if ( $favicon_url != '' ) {
			echo '<link type="image/x-icon" href="' . esc_url( $favicon_url ) . '" rel="icon" />';
		}
	}
}


//Add theme-specific body classes
add_filter( 'body_class', 'antreas_body_class' );
function antreas_body_class( $body_classes = '' ) {
	$current_id = antreas_current_id();
	$classes    = '';

	//Layout Style
	/*$layout_style = antreas_get_option('layout_style');
	$classes .= ' wrapper-'.esc_attr($layout_style);*/

	//Sidebar Layout
	$classes .= ' sidebar-' . antreas_get_sidebar_position();

	//Full Width Pages
	if ( is_404() || is_search() ) {
		$page_full = 0;
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$page_full = get_post_meta( get_option( 'woocommerce_shop_page_id' ), 'page_full', true );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$page_full = antreas_tax_meta( $current_id, 'page_full' );
	} else {
		$page_full = get_post_meta( $current_id, 'page_full', true );
	}

	if ( $page_full == '1' ) {
		$classes .= ' content-full';
	}

	//Header type
	$header = antreas_layout_header();
	if ( $header != '' || $header != 'normal' ) {
		$classes .= ' header-' . $header;
	}

	//Title type
	$title = antreas_layout_title();
	if ( $title != '' || $title != 'normal' ) {
		$classes .= ' title-' . $title;
	}

	// Title area type.
	$title_area = antreas_layout_title_area();
	if ( $title_area !== '' || $title_area !== 'normal' ) {
		$classes .= ' titlearea-' . $title_area;
	}

	//Footer type
	$footer = antreas_layout_footer();
	if ( $footer != '' || $footer != 'normal' ) {
		$classes .= ' footer-' . $footer;
	}

	// Sticky on/off
	$sticky = antreas_get_option( 'sticky_header' );
	if ( $sticky == 1 ) {
		$classes .= ' cpo-sticky-header';
	}

	if ( has_post_thumbnail() ) {
		$classes .= ' has-post-thumbnail';
	}

	if ( is_customize_preview() ) {
		$classes .= ' customizer-preview';
	}

	if ( antreas_get_option( 'enable_animations' ) === true ) {
		$classes .= ' enable-animations';
	}

	$body_classes[] = esc_attr( $classes );

	return $body_classes;
}


//Display viewport tag
if ( ! function_exists( 'antreas_viewport' ) ) {
	add_action( 'wp_head', 'antreas_viewport' );
	function antreas_viewport() {
		echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>' . "\n";
	}
}


//Print pingback metatag
if ( ! function_exists( 'antreas_pingback' ) ) {
	add_action( 'wp_head', 'antreas_pingback' );
	function antreas_pingback() {
		if ( get_option( 'default_ping_status' ) == 'open' ) {
			echo '<link rel="pingback" href="' . get_bloginfo( 'pingback_url' ) . '"/>' . "\n";
		}
	}
}


//Print charset metatag
if ( ! function_exists( 'antreas_charset' ) ) {
	add_action( 'wp_head', 'antreas_charset' );
	function antreas_charset() {
		echo '<meta charset="' . get_bloginfo( 'charset' ) . '"/>' . "\n";
	}
}


// Display shortcut edit links for logged in users.
if ( ! function_exists( 'antreas_edit' ) ) {
	function antreas_edit( $id = 0, $context = 'display' ) {

		$post = get_post( $id );
		if ( ! $post ) {
			return;
		}

		if ( 'revision' === $post->post_type ) {
			$action = '';
		} elseif ( 'display' == $context ) {
			$action = '&amp;action=edit';
		} else {
			$action = '&action=edit';
		}

		$post_type_object = get_post_type_object( $post->post_type );
		if ( ! $post_type_object ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		if ( $post_type_object->_edit_link ) {
			$link = admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) );
		} else {
			$link = '';
		}

		if ( $link ) {
			echo '<a target="_blank" title="' . esc_attr__( 'Edit', 'antreas' ) . '" class="post-edit-link" href="' . esc_url( $link ) . '">' . esc_html__( 'Edit', 'antreas' ) . '</a>';
		}
	}
}


//Display the site's logo
if ( ! function_exists( 'antreas_logo' ) ) {
	function antreas_logo() {

		// get custom logo.
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		// get custom logo dimensions.
		$logo_dimensions = antreas_get_option( 'logo_dimensions' );
		if ( ! $logo_dimensions ) {
			$logo_dimensions = array(
				'width'  => '',
				'height' => '',
			);
		}

		// We have a custom logo
		if ( $custom_logo_id ) {

			$custom_logo_img = sprintf( '<img class="logo-img" itemprop="logo" src="%1$s" width="%2$s" height="%3$s" alt="%4$s"/>', wp_get_attachment_url( $custom_logo_id ), $logo_dimensions['width'], $logo_dimensions['height'], get_bloginfo( 'name', 'display' ) );
			$output          = sprintf( '<a href="%1$s" class="logo-link" rel="home" itemprop="url">%2$s</a>', esc_url( home_url( '/' ) ), $custom_logo_img );

		} elseif ( is_front_page() ) {
			$output = sprintf( '<a href="%1$s" class="logo-link"><h1 class="site-title">%2$s</h1></a>', esc_url( home_url( '/' ) ), esc_html( get_bloginfo( 'name' ) ) );
		} else {
			$output = sprintf( '<a href="%1$s" class="logo-link"><span class="site-title">%2$s</span></a>', esc_url( home_url( '/' ) ), esc_html( get_bloginfo( 'name' ) ) );
		}

		echo $output;
	}
}


//Display language switcher
if ( ! function_exists( 'antreas_languages' ) ) {
	function antreas_languages( $display = false ) {
		if ( $display || antreas_get_option( 'layout_languages' ) == 1 && function_exists( 'icl_get_languages' ) ) :
			$output = '<div id="languages" class="languages">';
			$langs  = icl_get_languages( 'skip_missing=0&orderby=code' );

			//Print current active language
			foreach ( $langs as $current_lang ) :
				if ( $current_lang['language_code'] == ICL_LANGUAGE_CODE ) :
					$output .= '<div class="language-active" id="language-active-' . esc_attr( $current_lang['language_code'] ) . '">';
					$output .= '<img src="' . esc_url( $current_lang['country_flag_url'] ) . '" alt="' . esc_attr( $current_lang['language_code'] ) . '"> ';
					$output .= $current_lang['native_name'];
					$output .= '</div>';
				endif;
			endforeach;

			//Print full lagnguage list
			$output .= '<div class="language-list">';
			foreach ( $langs as $current_lang ) :
				$output .= '<a class="language-item" href="' . esc_url( $current_lang['url'] ) . '" id="language-switch-' . esc_attr( $current_lang['language_code'] ) . '">';
				$output .= '<img src="' . esc_url( $current_lang['country_flag_url'] ) . '" alt="' . esc_attr( $current_lang['language_code'] ) . '"> ';
				$output .= $current_lang['native_name'];
				$output .= '</a>';
			endforeach;
			$output .= '</div>';

			$output .= '</div>';
			echo $output;
		endif;
	}
}


//Display woocommerce cart
if ( ! function_exists( 'antreas_cart' ) ) {
	function antreas_cart( $display = false ) {
		if ( ( $display || antreas_get_option( 'layout_cart' ) == 1 ) && function_exists( 'is_woocommerce' ) ) {

			wp_enqueue_style( 'antreas-fontawesome' );

			global $woocommerce;
			$output = '<div id="shopping-cart" class="shopping-cart">';

			//Cart Summary
			$output .= '<div class="cart-title">';
			$output .= $woocommerce->cart->get_cart_total();
			$output .= '</div>';

			//Cart dropdown
			$output .= '<div class="cart_list cart-list">';
			$output .= '<div class="woocommerce widget_shopping_cart">';
			$output .= '<div class="widget_shopping_cart_content"></div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			echo $output;
		}
	}
}


//Display social links
if ( ! function_exists( 'antreas_social_links' ) ) {
	function antreas_social_links() {
		echo '<div id="social" class="social">';
		$links  = antreas_get_option( 'social_links' );
		$output = false;

		//old format uses arrays; new format uses lines
		if ( is_array( $links ) ) {
			foreach ( $links as $current_link ) {
				if ( $current_link['url'] != '' ) {
					echo '<a class="social-profile" href="' . esc_url( $current_link['url'] ) . '" title="' . esc_attr( $current_link['name'] ) . '" target="_blank">';
					if ( $current_link['icon'] != '' ) {
						echo '<span class="social-icon">' . antreas_icon( $current_link['icon'] ) . '</span>';
					}
					if ( $current_link['name'] != '' ) {
						echo '<span class="social-title">' . $current_link['name'] . '</span>';
					}
					echo '</a>';
					$output = true;
				}
			}
		} else {
			$link_list       = explode( '<br />', nl2br( $links ) );
			$social_networks = antreas_metadata_social_networks();
			foreach ( $link_list as $current_link ) {
				$link_url = trim( $current_link );
				if ( $link_url != '' ) {
					foreach ( $social_networks as $current_network_id => $current_network_data ) {
						if ( strpos( $link_url, $current_network_id ) !== false ) {
							echo '<a class="social-profile social-profile-' . strtolower( esc_attr( $current_network_data['name'] ) ) . '" href="' . esc_url( $link_url ) . '" title="' . esc_attr( $current_network_data['name'] ) . '" target="_blank">';
							echo '<span class="social-icon">' . antreas_icon( $current_network_data['icon'] ) . '</span>';
							echo '<span class="social-title">' . $current_network_data['name'] . '</span>';
							echo '</a>';
							$output = true;
						}
					}
				}
			}
		}

		if ( $output ) {
			wp_enqueue_style( 'antreas-fontawesome' );
		}
		echo '</div>';
	}
}


//Display social links - old and deprecated
if ( ! function_exists( 'antreas_social' ) ) {
	function antreas_social() {
		$output  = '<div id="social" class="social social-old">';
		$output .= antreas_social_item( antreas_get_option( 'social_facebook' ), 'Facebook', 'facebook' );
		$output .= antreas_social_item( antreas_get_option( 'social_twitter' ), 'Twitter', 'twitter' );
		$output .= antreas_social_item( antreas_get_option( 'social_gplus' ), 'Google Plus', 'google-plus' );
		$output .= antreas_social_item( antreas_get_option( 'social_youtube' ), 'YouTube', 'youtube' );
		$output .= antreas_social_item( antreas_get_option( 'social_linkedin' ), 'LinkedIn', 'linkedin' );
		$output .= antreas_social_item( antreas_get_option( 'social_pinterest' ), 'Pinterest', 'pinterest' );
		$output .= antreas_social_item( antreas_get_option( 'social_foursquare' ), 'Foursquare', 'foursquare' );
		$output .= antreas_social_item( antreas_get_option( 'social_tumblr' ), 'Tumblr', 'tumblr' );
		$output .= antreas_social_item( antreas_get_option( 'social_flickr' ), 'Flickr', 'flickr' );
		$output .= antreas_social_item( antreas_get_option( 'social_instagram' ), 'Instagram', 'instagram' );
		$output .= antreas_social_item( antreas_get_option( 'social_dribbble' ), 'Dribbble', 'dribbble' );
		$output .= antreas_social_item( antreas_get_option( 'social_skype' ), 'Skype', 'skype' );
		$output .= '</div>';
		echo $output;
	}
}

if ( ! function_exists( 'antreas_social_item' ) ) {
	function antreas_social_item( $url, $title = '', $name = '' ) {
		if ( $url != '' ) :
			$output = '<a class="social-profile social-profile-' . esc_attr( $name ) . '" href="' . esc_url( $url ) . '" title="' . esc_attr( $title ) . '" target="_blank">';
			if ( $name != '' ) {
				$output .= '<span class="social-icon"></span>';
			}
			if ( $title != '' ) {
				$output .= '<span class="social-title">' . $title . '</span>';
			}
			$output .= '</a>';

			return $output;
		endif;
	}
}


//Prints speed, timeout and effect values for the homepage slider
if ( ! function_exists( 'antreas_slider_data' ) ) {
	function antreas_slider_data( $navigation = true, $pagination = true ) {
		$output  = '';
		$output .= ' data-cycle-pause-on-hover="false"';
		$output .= ' data-cycle-slides=".slide"';

		if ( $navigation ) {
			$output .= ' data-cycle-prev=".slider-prev"';
			$output .= ' data-cycle-next=".slider-next"';
		}

		if ( $pagination ) {
			$output .= ' data-cycle-pager=".slider-pages"';
		}

		$slider_timeout = (int) antreas_get_option( 'slider_timeout' );
		if ( $slider_timeout == '' ) {
			$slider_timeout = '8000';
		}
		$output .= ' data-cycle-timeout="' . esc_attr( $slider_timeout ) . '"';

		$slider_speed = (int) antreas_get_option( 'slider_speed' );
		if ( $slider_speed == '' ) {
			$slider_speed = '2000';
		}
		$output .= ' data-cycle-speed="' . esc_attr( $slider_speed ) . '"';

		$slider_effect = antreas_get_option( 'slider_effect' );
		if ( $slider_effect == '' ) {
			$slider_effect = 'fade';
		}
		$output .= ' data-cycle-fx="' . esc_attr( $slider_effect ) . '"';

		echo $output;
	}
}


//Print an option content
if ( ! function_exists( 'antreas_block' ) ) {
	function antreas_block( $option, $wrapper = '', $subwrapper = '' ) {
		$content = antreas_get_option( $option );
		if ( '' != trim( $content ) ) {
			if ( '' != $wrapper ) {
				$ids = explode( ' ', $wrapper );
				if ( is_array( $ids ) ) {
					$ids = $ids[0];
				}
				echo '<div id="' . esc_attr( $ids ) . '" class="' . esc_attr( $wrapper ) . '">';
			}
			if ( '' != $subwrapper ) {
				echo '<div class="' . esc_attr( $subwrapper ) . '">';
			}
			echo do_shortcode( antreas_get_option( wp_kses_post( $option ) ) );
			if ( '' != $subwrapper ) {
				echo '</div>';
			}
			if ( '' != $wrapper ) {
				echo '</div>';
			}
		}
	}
}


//Print an option content
if ( ! function_exists( 'antreas_section_heading' ) ) {
	function antreas_section_heading( $slug, $class = null ) {
		$slug    = esc_attr( $slug );
		$heading = antreas_get_option( 'home_' . $slug );
		if ( $heading ) {
			echo '<div class="' . esc_attr( ! empty( $class ) ? $class : '' ) . ' section-heading ' . $slug . '-heading">';
				antreas_block( 'home_' . $slug . '_subtitle', $slug . '-subtitle section-subtitle' );
				echo '<div class="section-title ' . $slug . '-title heading">' . $heading . '</div>';
			echo '</div>';
		}
	}
}


//Print 404 message
if ( ! function_exists( 'antreas_404' ) ) {
	function antreas_404() {
		echo apply_filters( 'antreas_404', __( 'The requested page could not be found. It could have been deleted or changed location. Try searching for it using the search function.', 'antreas' ) );
	}
}


//Print subfooter sidebars
if ( ! function_exists( 'antreas_subfooter' ) ) {
	function antreas_subfooter( $class = '' ) {
		$footer_columns = antreas_get_option( 'layout_subfooter_columns' );
		if ( $footer_columns == '' ) {
			$footer_columns = 3;
		}
		echo '<div class="row">';
		for ( $count = 1; $count <= $footer_columns; $count ++ ) {
			if ( is_active_sidebar( 'footer-widgets-' . $count ) ) {
				echo '<div class="column col' . esc_attr( $footer_columns . ' ' . $class ) . '">';
				echo '<div class="subfooter-column">';
				dynamic_sidebar( 'footer-widgets-' . $count );
				echo '</div>';
				echo '</div>';
			}
		}
		echo '</div>';
		echo '<div class="clear"></div>';
	}
}


//Print footer copyright line
if ( ! function_exists( 'antreas_footer' ) ) {
	function antreas_footer() {
		echo '<div class="footer-content">';
		if ( antreas_get_option( 'footer_text' ) != '' ) {
			echo '<span class="copyright">' . do_shortcode( stripslashes( html_entity_decode( antreas_get_option( 'footer_text' ) ) ) ) . '</span>';
		} else {
			echo '<span class="copyright">&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) . '. </span>';
		}
		if ( antreas_get_option( 'general_credit' ) == 1 ) {
			echo '<span class="cpo-credit-link"> ';
			printf( __( 'Theme designed by <a href="%s">Macho Themes</a>.', 'antreas' ), 'https://www.machothemes.com/' );
			echo '</span>';
		}
		echo '</div>';
	}
}

//Print submenu navigation
if ( ! function_exists( 'antreas_submenu' ) ) {
	function antreas_submenu() {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		if ( empty( $ancestors[0] ) || $ancestors[0] == 0 ) {
			$ancestors[0] = get_the_ID();
		}
		echo '<ul id="submenu" class="menu-sub">';
		wp_list_pages( apply_filters( 'antreas_submenu_query', 'title_li=&child_of=' . $ancestors[0] ) );
		echo '</ul>';
	}
}


//Print submenu navigation
if ( ! function_exists( 'antreas_sitemap' ) ) {
	function antreas_sitemap() {
		//Print page list
		echo '<div class="column col2">';
		echo '<h3>' . __( 'Pages', 'antreas' ) . '</h3>';
		echo '<ul>' . wp_list_pages( 'sort_column=menu_order&title_li=&echo=0' ) . '</ul>';
		echo '</div>';

		//Print post categories and tag cloud
		echo '<div class="column col2 col-last">';
		echo '<h3>' . __( 'Post Categories', 'antreas' ) . '</h3>';
		echo '<ul>' . wp_list_categories( 'title_li=&show_count=1&echo=0' ) . '</ul>';
		echo '<h3>' . __( 'Post Tags', 'antreas' ) . '</h3>';
		echo '<ul>' . wp_tag_cloud( 'echo=0' ) . '</ul>';
		echo '</div>';

		echo '<div class="clear"></div>';
	}
}


//Enqueue custom font stylesheets from Google Fonts
if ( ! function_exists( 'antreas_fonts' ) ) {
	function antreas_fonts( $font_name, $load_variants = false ) {
		$font_variants = $load_variants != false ? ':100,300,400,700' : '';
		if ( is_array( $font_name ) ) {
			foreach ( $font_name as $current_font ) {
				if ( ! in_array( $current_font, array( 'Arial', 'Georgia', 'Times+New+Roman', 'Verdana' ) ) ) {
					$font_id = 'antreas-font-' . strtolower( str_replace( '+', '-', $current_font ) );
					wp_enqueue_style( $font_id, '//fonts.googleapis.com/css?family=' . str_replace( '%2B', '+', rawurlencode( $current_font . $font_variants ) ) );
				}
			}
		} else {
			if ( ! in_array( $font_name, array( 'Arial', 'Georgia', 'Times+New+Roman', 'Verdana' ) ) ) {
				$font_id = 'antreas-font-' . strtolower( str_replace( '+', '-', $font_name ) );
				wp_enqueue_style( $font_id, '//fonts.googleapis.com/css?family=' . str_replace( '%2B', '+', rawurlencode( $font_name . $font_variants ) ) );
			}
		}
	}
}


//Creates a grid of columns for display templated content, running the WordPress loop
if ( ! function_exists( 'antreas_grid' ) ) {
	function antreas_grid( $posts, $element, $template, $columns = 3, $args = null ) {
		if ( $posts == null ) {
			antreas_grid_default( $element, $template, $columns, $args );
		} else {
			global $post;
			antreas_grid_custom( $posts, $element, $template, $columns, $args );
		}
	}
}


//Runs the grid using the default loop
if ( ! function_exists( 'antreas_grid_default' ) ) {
	function antreas_grid_default( $element, $template, $columns = 3, $args = null ) {
		$class = isset( $args['class'] ) ? $args['class'] : '';
		if ( $columns == '' ) {
			$columns = 3;
		}

		echo '<div class="row">';
		$count = 0;
		while ( have_posts() ) {
			the_post();
			if ( $count % $columns == 0 && $count > 0 ) {
				echo '</div>';
				do_action( 'antreas_grid_' . $template );
				echo '<div class="row">';
			}
			$count ++;
			echo '<div class="column ' . esc_attr( $class ) . ' col' . esc_attr( $columns ) . '">';
			get_template_part( 'template-parts/' . $element, $template );
			echo '</div>';
		}
		echo '</div>';
	}
}


//Runs the grid using a custom loop
if ( ! function_exists( 'antreas_grid_custom' ) ) {
	function antreas_grid_custom( $posts, $element, $template, $columns = 3, $args = null ) {
		global $post;
		$class = isset( $args['class'] ) ? $args['class'] : '';
		if ( $columns == '' ) {
			$columns = 3;
		}

		echo '<div class="row">';
		$count = 0;
		foreach ( $posts as $post ) {
			setup_postdata( $post );
			if ( $count % $columns == 0 && $count > 0 ) {
				echo '</div>';
				do_action( 'antreas_grid_' . $template );
				echo '<div class="row">';
			}
			$count ++;
			echo '<div class="column ' . esc_attr( $class ) . ' col' . esc_attr( $columns ) . '">';
			get_template_part( 'template-parts/' . $element, $template );
			echo '</div>';
		}
		echo '</div>';
	}
}


//Adds custom analytics code in the footer
if ( ! function_exists( 'antreas_layout_analytics' ) ) {
	//add_action('wp_footer','antreas_layout_analytics');
	function antreas_layout_analytics() {
		$output = stripslashes( html_entity_decode( antreas_get_option( 'general_analytics' ), ENT_QUOTES ) );
		//$output = stripslashes($output);
		echo $output;
	}
}


//Adds custom css code in the footer
if ( ! function_exists( 'antreas_layout_css' ) ) {
	add_action( 'wp_head', 'antreas_layout_css', 25 );
	function antreas_layout_css() {
		$output = antreas_get_option( 'general_css' );
		if ( $output != '' ) {
			$output = '<style type="text/css">' . wp_strip_all_tags( $output ) . '</style>';
			echo $output;
		}
	}
}


//Retrieve sidebar position (DEPRECATED)
if ( ! function_exists( 'antreas_sidebar_position' ) ) {
	function antreas_sidebar_position() {
		$sidebar_position = antreas_get_option( 'sidebar_position' );
		if ( $sidebar_position == 'left' ) {
			echo 'content-right';
		} elseif ( $sidebar_position == 'none' ) {
			echo 'content-wide';
		}
	}
}


// Generates breadcrumb navigation
if ( ! function_exists( 'antreas_breadcrumb' ) ) {
	function antreas_breadcrumb( $display = false ) {
		$page_title = antreas_layout_title();
		if ( $page_title != 'minimal' && $page_title != 'none' ) {
			if ( ! is_home() && ! is_front_page() && ( $display || antreas_get_option( 'layout_breadcrumbs' ) ) ) {
				//Use WooCommerce navigation if it's a shop page
				if ( function_exists( 'is_woocommerce' ) && function_exists( 'woocommerce_breadcrumb' ) && is_woocommerce() ) {
					woocommerce_breadcrumb();

					return;
				}

				$result = '';
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					$result = yoast_breadcrumb( '', '', false );
				}

				if ( $result == '' ) {
					global $post;
					if ( is_object( $post ) ) {
						$pid = $post->ID;
					} else {
						$pid = '';
					}
					$result = '';

					if ( $pid != '' ) {
						$result = "<span class='breadcrumb-separator'></span>";
						//Add post hierarchy
						if ( is_singular() ) :
							$post_data = get_post( $pid );
							$result   .= "<span class='breadcrumb-title'>" . apply_filters( 'the_title', $post_data->post_title ) . "</span>\n";
							//Add post hierarchy
							while ( $post_data->post_parent ) :
								$post_data = get_post( $post_data->post_parent );
								$result    = "<span class='breadcrumb-separator'></span><a class='breadcrumb-link' href='" . get_permalink( $post_data->ID ) . "'>" . apply_filters( 'the_title', $post_data->post_title ) . "</a>\n" . $result;
							endwhile;

						elseif ( is_tax() ) :
							$result .= single_tag_title( '', false );

						elseif ( is_author() ) :
							$author  = get_userdata( get_query_var( 'author' ) );
							$result .= $author->display_name;

							//Prefix with a taxonomy if possible
						elseif ( is_category() ) :
							$post_data = get_the_category( $pid );
							if ( isset( $post_data[0] ) ) :
								$data = get_category_parents( $post_data[0]->cat_ID, true, ' &raquo; ' );
								if ( ! is_object( $data ) ) :
									$result .= substr( $data, 0, - 8 );
								endif;
							endif;

						elseif ( is_search() ) :
							$result .= __( 'Search Results', 'antreas' );
						else :
							if ( isset( $post->ID ) ) {
								$current_id = $post->ID;
							} else {
								$current_id = false;
							}
							if ( $current_id ) {
								$result .= get_the_title( $current_id );
							}
						endif;
					} elseif ( is_404() ) {
						$result  = "<span class='breadcrumb-separator'></span>";
						$result .= __( 'Page Not Found', 'antreas' );
					}
					$result = '<a class="breadcrumb-link" href="' . home_url() . '">' . __( 'Home', 'antreas' ) . '</a>' . $result;
				}

				$output = '<div id="breadcrumb" class="breadcrumb">' . $result . '</div>';
				echo $output;
			}
		}
	}
}


//Displays the search form on search pages
add_action( 'antreas_before_content', 'antreas_search_form' );
if ( ! function_exists( 'antreas_search_form' ) ) {
	function antreas_search_form() {
		if ( is_search() ) {
			$search_query = '';
			if ( isset( $_GET['s'] ) ) {
				$search_query = esc_attr( $_GET['s'] );
			}

			echo '<div class="search-form">';
			echo '<form role="search" method="get" id="search-form" class="search-form" action="' . home_url( '/' ) . '">';
			echo '<input type="text" value="' . $search_query . '" name="s" id="s" />';
			echo '<input type="submit" id="search-submit" value="' . __( 'Search', 'antreas' ) . '" />';
			echo '</form>';
			echo '</div>';

			if ( ! have_posts() ) {
				echo '<p class="search-submit">' . __( 'No results were found. Please try searching with different terms.', 'antreas' ) . '</p>';
			}
		}
	}
}


//Displays the post image on listings and blog posts
if ( ! function_exists( 'antreas_postpage_image' ) ) {
	function antreas_postpage_image( $size = 'portfolio' ) {
		if ( has_post_thumbnail() ) {
			if ( ! is_singular( 'post' ) ) {
				echo '<a href="' . get_permalink( get_the_ID() ) . '" title="' . sprintf( esc_attr__( 'Go to %s', 'antreas' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark">';
				the_post_thumbnail( $size );
				echo '</a>';
			} else {
				the_post_thumbnail();
			}
		}
	}
}


//Displays the post title on listings
if ( ! function_exists( 'antreas_postpage_title' ) ) {
	function antreas_postpage_title() {
		if ( ! is_singular( 'post' ) ) {
			echo '<h2 class="post-title">';
			echo '<a href="' . get_permalink( get_the_ID() ) . '" title="' . sprintf( esc_attr__( 'Go to %s', 'antreas' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark">';
			the_title();
			echo '</a>';
			echo '</h2>';
		}
	}
}


//Displays the post content
if ( ! function_exists( 'antreas_postpage_content' ) ) {
	function antreas_postpage_content() {
		$preview = antreas_get_option( 'postpage_preview' );

		if ( $preview === true || $preview == 'full' || is_singular( 'post' ) ) {
			do_action( 'antreas_before_post_content' );
			the_content();
			antreas_post_pagination();
			do_action( 'antreas_after_post_content' );
		} elseif ( $preview != 'none' ) {
			the_excerpt();
		}
	}
}

//Displays the post date
if ( ! function_exists( 'antreas_postpage_date' ) ) {
	function antreas_postpage_date( $display = false, $date_format = '', $format_text = '' ) {
		if ( $display || antreas_get_option( 'postpage_dates' ) === true ) {
			if ( $date_format != '' ) {
				$date_string = get_the_date( $date_format );
			} else {
				$date_format = get_option( 'date_format' );
				$date_string = get_the_date( $date_format );
			}
			if ( $format_text != '' ) {
				$date_string = sprintf( $format_text, $date_string );
			}
			echo '<div class="post-date">' . $date_string . '</div>';
		}
	}
}

//Displays the author link
if ( ! function_exists( 'antreas_postpage_author' ) ) {
	function antreas_postpage_author( $display = false, $format_text = '' ) {
		if ( $display || antreas_get_option( 'postpage_authors' ) === true ) {
			$author_alt = sprintf( esc_attr__( 'View all posts by %s', 'antreas' ), get_the_author() );
			$author     = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>', get_author_posts_url( get_the_author_meta( 'ID' ) ), $author_alt, get_the_author() );
			if ( $format_text != '' ) {
				$author = sprintf( $format_text, $author );
			}
			echo '<div class="post-author">' . $author . '</div>';
		}
	}
}

//Displays the category list for the current post
if ( ! function_exists( 'antreas_postpage_categories' ) ) {
	function antreas_postpage_categories( $display = false, $format_text = '' ) {
		if ( $display || antreas_get_option( 'postpage_categories' ) === true ) {
			$category_list = get_the_category_list( ', ' );
			if ( $format_text != '' ) {
				$category_list = sprintf( $format_text, $category_list );
			}
			echo '<div class="post-category">' . $category_list . '</div>';
		}
	}
}

//Displays the number of comments for the post
if ( ! function_exists( 'antreas_postpage_comments' ) ) {
	function antreas_postpage_comments( $display_always = false, $format_text = '' ) {
		if ( $display_always || antreas_get_option( 'postpage_comments' ) === true ) {
			$comments_num = get_comments_number();

			//Format comment texts
			if ( $format_text != '' ) {
				$text = $format_text;
			} else {
				if ( $comments_num == 0 ) {
					$text = __( 'No Comments', 'antreas' );
				} elseif ( $comments_num == 1 ) {
					$text = __( 'One Comment', 'antreas' );
				} else {
					$text = __( '%1$s Comments', 'antreas' );
				}
			}

			$comments = sprintf( $text, number_format_i18n( $comments_num ) );
			echo '<div class="post-comments">' . sprintf( '<a href="%1$s">%2$s</a>', get_permalink( get_the_ID() ) . '#comments', $comments ) . '</div>';
		}
	}
}

//Displays the post tags
if ( ! function_exists( 'antreas_postpage_tags' ) ) {
	function antreas_postpage_tags( $display = false, $before = '', $separator = ', ', $after = '' ) {
		if ( $display || antreas_get_option( 'postpage_tags' ) === true ) {
			echo '<div class="post-tags">';
			the_tags( $before, $separator, $after );
			echo '</div>';
		}
	}
}


//Display Read More link for post excerpts
if ( ! function_exists( 'antreas_postpage_readmore' ) ) {
	function antreas_postpage_readmore( $classes = '' ) {
		if ( ! is_singular( 'post' ) ) {
			echo '<a class="post-readmore ' . esc_attr( $classes ) . '" href="' . get_permalink( get_the_ID() ) . '">';
			echo apply_filters( 'antreas_readmore', __( 'Read More', 'antreas' ) );
			echo '</a>';
		}
	}
}


//Displays the author box
if ( ! function_exists( 'antreas_author' ) ) {
	function antreas_author() {
		if ( antreas_get_option( 'postpage_authors' ) === true && get_the_author_meta( 'description' ) ) {
			if ( function_exists( 'ts_fab_add_author_box' ) ) {
				echo ts_fab_add_author_box( '' );
			} else {
				echo '<div id="author-info" class="author-info">';
				echo '<div class="author-content">';
				echo '<div class="author-image">' . get_avatar( get_the_author_meta( 'user_email' ), 100 ) . '</div>';
				echo '<div class="author-body">';
				echo '<h4 class="author-name">';
				echo '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '">' . get_the_author() . '</a>';
				echo '</h4>';
				echo '<div class="author-description">';
				the_author_meta( 'description' );
				echo '</div>';
				//Social links
				echo '<div class="author-social">';
				$user_meta = get_the_author_meta( 'user_url' );
				if ( $user_meta != '' ) {
					echo '<a target="_blank" rel="nofollow" class="author-web" href="' . esc_attr( $user_meta ) . '">' . __( 'Website', 'antreas' ) . '</a>';
				}
				$user_meta = get_the_author_meta( 'facebook' );
				if ( $user_meta != '' ) {
					echo '<a target="_blank" rel="nofollow" class="author-facebook" href="' . esc_attr( $user_meta ) . '">' . __( 'Facebook', 'antreas' ) . '</a>';
				}
				$user_meta = get_the_author_meta( 'twitter' );
				if ( $user_meta != '' ) {
					echo '<a target="_blank" rel="nofollow" class="author-twitter" href="//twitter.com/' . esc_attr( $user_meta ) . '">' . __( 'Twitter', 'antreas' ) . '</a>';
				}
				$user_meta = get_the_author_meta( 'googleplus' );
				if ( $user_meta != '' ) {
					echo '<a target="_blank" rel="nofollow" class="author-googleplus" href="' . esc_attr( $user_meta ) . '">' . __( 'Google+', 'antreas' ) . '</a>';
				}
				$user_meta = get_the_author_meta( 'linkedin' );
				if ( $user_meta != '' ) {
					echo '<a target="_blank" rel="nofollow" class="author-linkedin" href="' . esc_attr( $user_meta ) . '">' . __( 'LinkedIn', 'antreas' ) . '</a>';
				}
				do_action( 'antreas_author_links' );
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		}
	}

	remove_filter( 'the_content', 'ts_fab_add_author_box', 15 );
}


//Displays the author box
if ( ! function_exists( 'antreas_team_links' ) ) {
	function antreas_team_links( $args = null ) {
		$links = get_post_meta( get_the_ID(), 'team_links', true );
		if ( is_array( $links ) ) {
			wp_enqueue_style( 'antreas-fontawesome' );
			echo '<div class="team-member-links">';
			foreach ( $links as $link ) {
				if ( isset( $link['url'] ) && $link['url'] != '' ) {
					echo '<a class="team-member-link" rel="nofollow" href="' . $link['url'] . '" title="' . $link['name'] . '" target="_blank">';
					antreas_icon( $link['icon'] );
					echo '</a>';
				}
			}
			echo '<div class="clear"></div>';
			echo '</div>';
		}

	}
}


//Displays visual media of a particular post
if ( ! function_exists( 'antreas_get_media' ) ) {
	function antreas_get_media( $url ) {
		if ( $url != '' ) {
			$media = wp_oembed_get( $url );
			if ( $media !== false ) {
				echo '<div class="video">' . $media . '</div>';
			} else {
				echo '<img src="' . esc_url( $url ) . '">';
			}
		}
	}
}

//Displays visual media of a particular post
if ( ! function_exists( 'antreas_post_media' ) ) {
	function antreas_post_media( $post_id, $media_type, $video = '', $options = null ) {

		//Backwards compatibility - If meta not exists, use attached images
		if ( metadata_exists( 'post', $post_id, 'page_gallery' ) ) {
			$value = get_post_meta( $post_id, 'page_gallery', true );
		} else {
			$args   = array(
				'post_type'      => 'attachment',
				'post_status'    => 'inherit',
				'post_parent'    => $post_id,
				'exclude'        => get_post_thumbnail_id( $post_id ),
				'post_mime_type' => 'image',
				'posts_per_page' => - 1,
				'order'          => 'ASC',
				'orderby'        => 'menu_order',
			);
			$images = get_posts( $args );
			$value  = '';
			$first  = true;
			foreach ( $images as $current_image ) {
				if ( ! $first ) {
					$value .= ',';
				}
				$value .= $current_image->ID;
				$first  = false;
			}
		}

		switch ( $media_type ) {
			case 'none':
				break;
			case 'image':
				the_post_thumbnail( 'full', array( 'class' => 'single-image' ) );
				break;
			case 'slideshow':
				antreas_post_slideshow( $value );
				break;
			case 'gallery':
				antreas_post_gallery( $value, 3 );
				break;
			case 'video':
				antreas_post_video( $video );
				break;
			default:
				the_post_thumbnail( 'full', array( 'class' => 'single-image' ) );
				break;
		}
	}
}


//Displays a slideshow of the given query
if ( ! function_exists( 'antreas_post_slideshow' ) ) {
	function antreas_post_slideshow( $images, $options = null ) {
		$attachments = array_filter( explode( ',', $images ) );
		$image_size  = isset( $options['size'] ) ? $options['size'] : 'full';
		$thumb_count = 0;
		if ( $attachments ) { ?>
			<div class="slideshow">
				<div class="slideshow-slides cycle-slideshow" data-cycle-slides=".slideshow-slide"
					 data-cycle-prev=".slideshow-prev" data-cycle-next=".slideshow-next" data-cycle-timeout="5000"
					 data-cycle-speed="700" data-cycle-fx="fade" data-cycle-auto-height="container">
					<?php wp_enqueue_script( 'antreas_cycle' ); ?>
					<?php foreach ( $attachments as $attachment_id ) : ?>
						<?php if ( trim( $attachment_id ) != '' ) : ?>
							<?php $thumb_count ++; ?>
							<div class="slideshow-slide"
								<?php
								if ( $thumb_count != 1 ) {
									echo 'style="display:none;"';
								}
								?>
							>
								<?php $image_url = wp_get_attachment_image_src( $attachment_id, $image_size ); ?>
								<img src="<?php echo esc_url( $image_url[0] ); ?>"/>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="slideshow-prev"></div>
				<div class="slideshow-next"></div>
			</div>
			<?php
		}
	}
}


//Displays a gallery of the given query
if ( ! function_exists( 'antreas_post_gallery' ) ) {
	function antreas_post_gallery( $images, $columns = 3, $size = 'portfolio', $options = null ) {
		$attachments   = array_filter( explode( ',', $images ) );
		$column_style  = isset( $options['style'] ) ? $options['style'] : 'column-narrow';
		$feature_count = 0;
		if ( $attachments ) {
			wp_enqueue_style( 'antreas-magnific' );
			wp_enqueue_script( 'antreas-magnific' );
			?>
			<div class="image-gallery">
				<div class="row">
					<?php foreach ( $attachments as $attachment_id ) : ?>
						<?php if ( trim( $attachment_id ) != '' ) : ?>
							<?php
							if ( $feature_count % $columns == 0 && $feature_count > 0 ) {
								echo '</div><div class="row">';
							}
							?>
							<?php $feature_count ++; ?>
							<div class="column <?php echo $column_style; ?> col
															<?php
															echo esc_attr( $columns );
															if ( $feature_count % $columns == 0 ) {
																echo ' col-last';
															}
															?>
							">
								<div class="image-gallery-item">
									<?php $source = wp_get_attachment_image_src( $attachment_id, $size ); ?>
									<?php $original_source = wp_get_attachment_image_src( $attachment_id, 'full' ); ?>
									<a href="<?php echo esc_url( $original_source[0] ); ?>" rel="gallery[portfolio]">
										<img src="<?php echo esc_url( $source[0] ); ?>"/>
									</a>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
	}
}

//Displays a video of the given query
if ( ! function_exists( 'antreas_post_video' ) ) {
	function antreas_post_video( $video_url, $image_url = '' ) {
		if ( $video_url != '' ) {
			?>
			<div class="video">
				<?php echo wp_oembed_get( $video_url ); ?>
			</div>
			<?php
		}
	}
}


//Paginates a single post's content by using a numbered list
if ( ! function_exists( 'antreas_pagination' ) ) {
	function antreas_pagination() {
		$query        = $GLOBALS['wp_query'];
		$current_page = max( 1, absint( $query->get( 'paged' ) ) );
		$total_pages  = max( 1, absint( $query->max_num_pages ) );
		if ( $total_pages == 1 ) {
			return;
		}

		$pages_to_show         = 8;
		$larger_page_to_show   = 10;
		$larger_page_multiple  = 2;
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start       = floor( $pages_to_show_minus_1 / 2 );
		$half_page_end         = ceil( $pages_to_show_minus_1 / 2 );
		$start_page            = $current_page - $half_page_start;

		$end_page = $current_page + $half_page_end;

		if ( ( $end_page - $start_page ) != $pages_to_show_minus_1 ) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}

		if ( $end_page > $total_pages ) {
			$start_page = $total_pages - $pages_to_show_minus_1;
			$end_page   = $total_pages;
		}

		if ( $start_page < 1 ) {
			$start_page = 1;
		}

		$out = '';

		//First Page Link
		if ( 1 == $current_page ) {
			$out .= '<span class="first_page">' . __( 'First', 'antreas' ) . '</span>';
		} else {
			$out .= '<a class="pagination-page page first_page" href="' . esc_url( get_pagenum_link( 1 ) ) . '">' . __( 'First', 'antreas' ) . '</a>';
		}

		//Show each page
		foreach ( range( $start_page, $end_page ) as $i ) {
			if ( $i == $current_page ) {
				$out .= "<span>$i</span>";
			} else {
				$out .= '<a class="pagination-page page" href="' . esc_url( get_pagenum_link( $i ) ) . '">' . $i . '</a>';
			}
		}

		//Last Page Link
		if ( $total_pages == $current_page ) {
			$out .= '<span class="last_page">' . __( 'Last', 'antreas' ) . '</span>';
		} else {
			$out .= '<a class="pagination-page page last_page" href="' . esc_url( get_pagenum_link( $total_pages ) ) . '">' . __( 'Last', 'antreas' ) . '</a>';
		}

		$out = '<div id="pagination" class="pagination">' . $out . '</div>';

		echo $out;
	}
}


//Paginates a list of posts, such as the blog or portfolio
if ( ! function_exists( 'antreas_numbered_pagination' ) ) {
	function antreas_numbered_pagination( $query = '' ) {
		global $wp_query;
		if ( $query != '' ) {
			$total_pages = $query->max_num_pages;
		} else {
			$total_pages = $wp_query->max_num_pages;
		}
		if ( $total_pages > 1 ) {
			echo '<div class="pagination">';
			if ( ! $current_page = get_query_var( 'paged' ) ) {
				$current_page = 1;
			}
			echo paginate_links(
				array(
					'base'      => str_replace( 999999, '%#%', esc_url( get_pagenum_link( 999999 ) ) ),
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => $total_pages,
					'mid_size'  => 4,
					'type'      => 'list',
					'prev_next' => false,
				)
			);
			echo '</div>';
		}
	}
}


//Paginates a single post by using a numbered list
if ( ! function_exists( 'antreas_post_pagination' ) ) {
	function antreas_post_pagination() {
		wp_link_pages(
			array(
				'before'    => '<div class="postpagination">',
				'after'     => '</div>',
				'pagelink'  => '<span>%</span>',
				'separator' => '',
			)
		);
	}
}


//Prints the main navigation menu
if ( ! function_exists( 'antreas_menu' ) ) {
	function antreas_menu( $options = null ) {
		$page_header = antreas_layout_header();
		if ( $page_header != 'minimal' && $page_header != 'none' ) {
			if ( has_nav_menu( 'main_menu' ) ) {
				if ( isset( $options['toggle'] ) && $options['toggle'] == true ) {
					antreas_menu_toggle();
				}
				wp_nav_menu(
					array(
						'menu_id'        => 'menu-main',
						'menu_class'     => 'menu-main',
						'theme_location' => 'main_menu',
						'depth'          => '4',
						'container'      => false,
					)
				);
			}
		}
	}
}


//Prints the mobile navigation menu
if ( ! function_exists( 'antreas_mobile_menu' ) ) {
	add_action( 'antreas_header', 'antreas_mobile_menu', 20 );
	function antreas_mobile_menu( $options = null ) {
		$page_header = antreas_layout_header();
		if ( $page_header != 'minimal' && $page_header != 'none' ) {
			//Use mobile menu if set, or fall back to the main menu
			if ( has_nav_menu( 'mobile_menu' ) ) {
				echo '<button id="menu-mobile-close" class="menu-mobile-close menu-mobile-toggle"></button>';
				wp_nav_menu(
					array(
						'menu_id'        => 'menu-mobile',
						'menu_class'     => 'menu-mobile',
						'theme_location' => 'mobile_menu',
						'depth'          => '4',
						'container'      => false,
					)
				);
			} elseif ( has_nav_menu( 'main_menu' ) ) {
				echo '<button id="menu-mobile-close" class="menu-mobile-close menu-mobile-toggle"></button>';
				wp_nav_menu(
					array(
						'menu_id'        => 'menu-mobile',
						'menu_class'     => 'menu-mobile',
						'theme_location' => 'main_menu',
						'depth'          => '4',
						'container'      => false,
					)
				);
			}
		}
	}
}


//Prints the main navigation menu
if ( ! function_exists( 'antreas_menu_toggle' ) ) {
	function antreas_menu_toggle() {
		$page_header = antreas_layout_header();
		if ( $page_header != 'minimal' && $page_header != 'none' ) {
			if ( has_nav_menu( 'main_menu' ) ) {
				echo '<button id="menu-mobile-open" class=" menu-mobile-open menu-mobile-toggle"></button>';
			}
		}
	}
}


//Prints the footer navigation menu
if ( ! function_exists( 'antreas_top_menu' ) ) {
	function antreas_top_menu() {
		if ( has_nav_menu( 'top_menu' ) ) {
			echo '<div id="topmenu" class="topmenu">';
			wp_nav_menu(
				array(
					'menu_class'     => 'menu-top',
					'theme_location' => 'top_menu',
					'depth'          => 0,
					'fallback_cb'    => null,
					'walker'         => new Antreas_Menu_Walker(),
				)
			);
			echo '</div>';
		}
	}
}


//Prints the footer navigation menu
if ( ! function_exists( 'antreas_footer_menu' ) ) {
	function antreas_footer_menu() {
		$page_footer = antreas_layout_footer();
		if ( $page_footer != 'minimal' && $page_footer != 'none' ) {
			if ( has_nav_menu( 'footer_menu' ) ) {
				echo '<div id="footermenu" class="footermenu">';
				wp_nav_menu(
					array(
						'menu_class'     => 'menu-footer',
						'theme_location' => 'footer_menu',
						'depth'          => 1,
						'fallback_cb'    => false,
						'walker'         => new Antreas_Menu_Walker(),
					)
				);
				echo '</div>';
			}
		}
	}
}


//Prints a custom navigation menu based around a single taxonomy
if ( ! function_exists( 'antreas_secondary_menu' ) ) {
	function antreas_secondary_menu( $taxonomy = 'cpo_portfolio_category', $class ) {
		if ( taxonomy_exists( $taxonomy ) ) {
			$feature_posts = get_terms( $taxonomy, 'order=ASC&orderby=name' );
			if ( sizeof( $feature_posts ) > 0 ) {
				$current_id = antreas_current_id();
				echo '<div id="menu-secondary ' . $class . '" class="menu-secondary ' . $class . '">';
				foreach ( $feature_posts as $current_item ) {
					$active_item = '';
					if ( $current_item->term_id == $current_id ) {
						$active_item = ' menu-item-active';
					}
					echo '<a href="' . get_term_link( $current_item, 'cpo_portfolio_category' ) . '" class="menu-item' . $active_item . '">';
					echo '<div class="menu-title">' . $current_item->name . '</div>';
					echo '</a>';
				}
				echo '</div>';
			}
		}
	}
}


//TODO: Print a default navigation menu when there is none, using the theme markup
if ( ! function_exists( 'antreas_default_menu' ) ) {
	function antreas_default_menu() {
		$args  = array( 'sort_column' => 'menu_order, post_title' );
		$pages = get_pages( $args );

		if ( $pages ) {
			$count   = 0;
			$output  = '';
			$output .= '<ul class="menu-main">';
			foreach ( $pages as $current_page ) {
				$count ++;
				if ( $current_page->post_parent == 0 && $count < 17 ) {
					$output .= '<li class="menu-item">';
					$output .= '<a href="' . get_permalink( $current_page->ID ) . '">';
					$output .= '<span class="menu-link">';
					$output .= '<span class="menu-title">' . $current_page->post_title . '</span>';
					$output .= '</span>';
					$output .= '</a>';
					$output .= '</li>';
				}
			}
			$output .= '</ul>';
		}
		echo $output;
	}
}


//Print comment protected message
if ( ! function_exists( 'antreas_comments_protected' ) ) {
	function antreas_comments_protected() {
		if ( post_password_required() ) {
			echo '<p class="comments-protected">';
			_e( 'This page is protected. Please type the password to be able to read its contents.', 'antreas' );
			echo '</p>';

			return true;
		}

		return false;
	}
}


//Print comment list title
if ( ! function_exists( 'antreas_comments_title' ) ) {
	function antreas_comments_title() {
		echo '<h3 id="comments-title" class="comments-title">';
		if ( get_comments_number() == 1 ) {
			_e( 'One comment', 'antreas' );
		} else {
			printf( __( '%s comments', 'antreas' ), number_format_i18n( get_comments_number() ) );
		}
		echo '</h3>';
	}
}


//Print comment markup
if ( ! function_exists( 'antreas_comment' ) ) {
	function antreas_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		//Normal Comments
		switch ( $comment->comment_type ) :
			case '':
				?>
				<li <?php comment_class( 'comment' ); ?> id="comment-<?php comment_ID(); ?>">

				<div class="comment-body">
					<div class="comment-avatar">
						<?php echo get_avatar( $comment, 100 ); ?>
					</div>
					<div class="comment-title">
						<div class="comment-options">
							<?php edit_comment_link( __( 'Edit', 'antreas' ) ); ?>
							<?php
							comment_reply_link(
								array_merge(
									$args,
									array(
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
									)
								)
							);
							?>
						</div>
						<div class="comment-author">
							<?php echo get_comment_author_link(); ?>
						</div>
						<div class="comment-date">
							<a rel="nofollow" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<?php printf( __( '%1$s at %2$s', 'antreas' ), get_comment_date(), get_comment_time() ); ?>
							</a>
						</div>
					</div>

					<div class="comment-content">
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<span class="comment-approval"><?php _e( 'Your comment is awaiting approval.', 'antreas' ); ?></span>
						<?php endif; ?>

						<?php comment_text(); ?>
					</div>
				</div>
				<?php
				break;

			//Pingbacks & Trackbacks
			case 'pingback':
			case 'trackback':
				?>
				<li class="pingback">
				<?php comment_author_link(); ?>
				<?php edit_comment_link( __( 'Edit', 'antreas' ), ' (', ')' ); ?>
				<?php
				break;
		endswitch;
	}
}

//Print comment list pagination
if ( ! function_exists( 'antreas_comments_pagination' ) ) {
	function antreas_comments_pagination() {
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
			echo '<div class="comments-navigation">';
			echo '<div class="comments-previous">';
			previous_comments_link( __( 'Older', 'antreas' ) );
			echo '</div>';
			echo '<div class="comments-next">';
			next_comments_link( __( 'Newer', 'antreas' ) );
			echo '</div>';
			echo '</div>';
		}
	}
}


//Print Tagline title
if ( ! function_exists( 'antreas_tagline_title' ) ) {
	function antreas_tagline_title() {
		$tagline = antreas_get_option( 'home_tagline' );
		if ( $tagline != '' ) {
			echo '<div class="tagline-title">';
			echo $tagline;
			echo '</div>';

		}
	}
}


//Print Tagline content
if ( ! function_exists( 'antreas_tagline_content' ) ) {
	function antreas_tagline_content() {
		$tagline = antreas_get_option( 'home_tagline_content' );
		if ( $tagline != '' ) {
			echo '<div class="tagline-content">';
			echo $tagline;
			echo '</div>';

		}
	}
}


//Print Tagline image
if ( ! function_exists( 'antreas_tagline_image' ) ) {
	function antreas_tagline_image() {
		$tagline = antreas_get_option( 'home_tagline_image' );
		if ( $tagline != '' ) {
			echo '<img class="tagline-image" src="' . $tagline . '"/>';
		}
	}
}


//Print Tagline image
if ( ! function_exists( 'antreas_tagline_link' ) ) {
	function antreas_tagline_link( $class = '' ) {
		$url  = antreas_get_option( 'home_tagline_url' );
		$link = antreas_get_option( 'home_tagline_link' );
		if ( $url != '' && $link != '' ) {
			echo '<a class="tagline-link ' . $class . '" href="' . esc_url( $url ) . '">';
			echo $link;
			echo '</a>';
		}
	}
}
