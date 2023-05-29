<?php

/*
 * Enable shortcodes in widgets
 */
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );

if ( ! function_exists( 'mt_get_theme_option' ) ) {
	/**
	 * Get theme option value
	 *
	 * @param  string $theme_option ID of theme option
	 *
	 * @return string               Value of theme option
	 */

	function mt_get_theme_option( $theme_option = null, $array_value = null ) {

		$options = get_option( strtolower( MT_THEME_NAME ) . '_options' );

		if ( $theme_option !== null && $options[ $theme_option ] !== '' ) {

			if ( is_array( $options[ $theme_option ] ) && $array_value !== null ) {

				if ( isset( $options [ $theme_option ][ $array_value ] ) ) {

					switch ( $array_value ) {

						case 'background-image':
							return wp_get_attachment_url( $options[ $theme_option ][ $array_value ] );
							break;
						default:
							return $options[ $theme_option ][ $array_value ];
							break;
					}
				}

			} else {
				return $options[ $theme_option ];
			}
		} else if ( $theme_option == null ) {
			return $options;
		}

		return false;
	}
}


if ( ! function_exists( 'mt_disable_emojis' ) ) {
	/**
	 * Disable the emoji's
	 */
	function mt_disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'mt_disable_emojis_tinymce' );
	}
}

if ( ! function_exists( 'mt_disable_emojis_tinymce' ) ) {
	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 *
	 * @param    array $plugins
	 *
	 * @return   array  Difference betwen the two arrays
	 */
	function mt_disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
}

// check first to see if emoji is enabled / disabled
// if( mt_get_theme_option('emoji-enable')  == 0 ) {
// 	add_action( 'init', 'mt_disable_emojis' );
// }


if ( ! function_exists( 'mt_modify_post_mime_types' ) ) {
	/**
	 * Add more mime types to the media library
	 */
	function mt_modify_post_mime_types( $post_mime_types ) {

		// select the mime type, here: 'application/pdf'
		// then we define an array with the label values

		$post_mime_types['application/pdf']               = array(
			__( 'PDFs', 'regina' ),
			__( 'Manage PDFs', 'regina' ),
			_n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>', 'regina' ),
		);
		$post_mime_types['application/x-shockwave-flash'] = array(
			__( 'SWFs', 'regina' ),
			__( 'Manage SWFs', 'regina' ),
			_n_noop( 'SWF <span class="count">(%s)</span>', 'SWFs <span class="count">(%s)</span>', 'regina' ),
		);
		$post_mime_types['video/quicktime']               = array(
			__( 'MOVs', 'regina' ),
			__( 'Manage MOVs', 'regina' ),
			_n_noop( 'MOV <span class="count">(%s)</span>', 'MOVs <span class="count">(%s)</span>', 'regina' ),
		);
		$post_mime_types['video/x-flv']                   = array(
			__( 'FLVs', 'regina' ),
			__( 'Manage FLVs', 'regina' ),
			_n_noop( 'FLV <span class="count">(%s)</span>', 'FLVs <span class="count">(%s)</span>', 'regina' ),
		);


		// then we return the $post_mime_types variable
		return $post_mime_types;

	}

	// Add Filter Hook
	add_filter( 'post_mime_types', 'mt_modify_post_mime_types' );
}


if ( ! function_exists( 'mt_ajax_posts' ) ) {
	/**
	 *
	 * Function that handles the return of posts for the Projects Archive CPT
	 *
	 */
	function mt_ajax_posts() {

		// The $_POST contains all the data sent via ajax
		if ( isset( $_POST ) ) {

			$nonce = $_POST['nonce'];

			// check to see if the submitted nonce matches with the
			// generated nonce we created earlier
			if ( ! wp_verify_nonce( $nonce, 'MTajax-post-nonce' ) ) {
				die ( 'Busted!' );
			}

			// get post limit
			$limit = get_option( 'posts_per_page' );

			// get counter
			$page = $_POST['page'];

			$args = array(
				'post_type'      => 'project',
				'post_status'    => 'publish',
				'posts_per_page' => $limit,
				'paged'          => $page,
			);

			$loop = new WP_Query( $args );

			// calculate total posts published in the Project CPT
			// include in this calculation the already published post IDs
			//
			// This is to avoid duplicate posts
			//
			//
			$content = '';
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) {
					$loop->the_post();
					$options = get_post_meta( get_the_ID(), strtolower( MT_THEME_NAME ) . '_options', true );
					if ( $options['project-main-image'] ) {
						$image = wp_get_attachment_image_src( $options['project-main-image'], 'portfolio-archive' );
					} // if

					$content .= '<div class="col-lg-4 col-md-4 col-xs-6">';
					$content .= '<div class="entry-content portfolio-picture">';
					$content .= '<a href="' . esc_url( get_the_permalink() ) . '">';
					$content .= '<span class="portfolio-picture-name"><em>' . esc_html( get_the_title() ) . '</em></span>';
					$content .= '<img src="' . esc_url( $image[0] ) . '">';
					$content .= '</a>';
					$content .= '</div>';
					$content .= '</div>';

				} // while
			} else {
				$content .= __( 'There are no more projects.', 'regina' );
			}

			if ( $loop->max_num_pages == $page ) {
				echo json_encode( array( 'content' => $content, 'hide' => true ) );
			} else {
				echo json_encode( array( 'content' => $content, 'hide' => false ) );
			}

			// Always die in functions echoing ajax content
			die();

		} // if($_POST)

	} // function definition

	add_action( 'wp_ajax_mt_ajax_posts', 'mt_ajax_posts' );
	add_action( 'wp_ajax_nopriv_mt_ajax_posts', 'mt_ajax_posts' );
}

if ( ! function_exists( 'mt_hex2rgba' ) ) {
	/*
	 * Function to convert hex color codes to rgba
	 */
	function mt_hex2rgba( $color, $opacity = false ) {

		$default = 'rgb(0,0,0)';

		//Return default if no color provided
		if ( empty( $color ) ) {
			return $default;
		}

		//Sanitize $color if "#" is provided
		if ( $color[0] == '#' ) {
			$color = substr( $color, 1 );
		}

		//Check if color has 6 or 3 characters and get values
		if ( strlen( $color ) == 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) == 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}

		//Convert hexadec to rgb
		$rgb = array_map( 'hexdec', $hex );

		//Check if opacity is set(rgba or rgb)
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
		} else {
			# If there's not opacity specified, make it 100%
			$output = 'rgba(' . implode( ",", $rgb ) . ',' . '1' . ')';
		}

		//Return rgb(a) color string
		return $output;
	}

}

/**
 *    Function to add Google Analytics to Header / Footer of the site
 */
if ( ! function_exists( 'mt_analytics_add' ) ) {
	/**
	 * Function used to output tracking codes before the closing </head> tag
	 */


	function mt_analytics_add() {

		if ( mt_get_theme_option( 'tracking-code' ) ) {
			echo '<!-- TRACKING CODE -->';
			echo mt_get_theme_option( 'tracking-code' );
			echo '<!-- {END} TRACKING CODE -->';
		}
	}

	//add_action( 'wp_head', 'mt_analytics_add' );
}

/**
 *    Function to add CSS Code to Header
 */
if ( ! function_exists( 'mt_css_code_add' ) ) {

	/**
	 * Function used to output CSS codes before the closing </head> tag
	 */

	function mt_css_code_add() {

		if ( mt_get_theme_option( 'css-code' ) ) {
			echo '<!-- CUSTOM CSS CODE -->';
			echo '<style>';
			echo mt_get_theme_option( 'css-code' );
			echo '</style>';
			echo '<!-- {END} CUSTOM CSS CODE -->';
		}
	}

	//add_action( 'wp_head', 'mt_css_code_add' );
}


if ( ! function_exists( 'mt_code_head_add' ) ) {
	/**
	 * Function used to output code before the closing </head> tag
	 */


	function mt_code_head_add() {


		if ( mt_get_theme_option( 'space-before-head' ) ) {
			echo '<!-- CODE BEFORE HEAD -->';
			echo mt_get_theme_option( 'space-before-head' );
			echo '<!-- {END} CODE BEFORE HEAD -->';
		}
	}

	//add_action( 'wp_head', 'mt_code_head_add' );
}

if ( ! function_exists( 'mt_code_footer_add' ) ) {
	/**
	 * Function used to output code before the closing </body> tag
	 */

	function mt_code_footer_add() {

		if ( mt_get_theme_option( 'space-before-head' ) ) {
			echo '<!-- CODE BEFORE BODY -->';
			echo mt_get_theme_option( 'space-before-body' );
			echo '<!-- {END} CODE BEFORE BODY -->';
		}
	}

	//add_action( 'wp_footer', 'mt_code_footer_add' );
}

if ( ! function_exists( 'mt_get_image_id_by_link' ) ) {
	/**
	 * Function to get the ID of an image from the URL
	 */

	function mt_get_image_id_by_link( $link ) {
		global $wpdb;

		$link = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $link );

		return $wpdb->get_var( "SELECT ID FROM {$wpdb->posts} WHERE BINARY guid='$link'" );
	}
}


if ( ! function_exists( 'mt_pagination' ) ) {
	/**
	 * Number based pagination
	 *
	 * @param  string  $pages Maximum number of pages
	 * @param  integer $range
	 * @param  string  $current_query
	 *
	 * @return void
	 */
	function mt_pagination( $pages = '', $range = 2, $current_query = '' ) {


		$showitems = ( $range * 2 ) + 1;

		if ( $current_query == '' ) {
			global $paged;
			if ( empty( $paged ) ) {
				$paged = 1;
			}
		} else {
			$paged = $current_query->query_vars['paged'];
		}

		if ( $pages == '' ) {
			if ( $current_query == '' ) {
				global $wp_query;
				$pages = $wp_query->max_num_pages;
				if ( ! $pages ) {
					$pages = 1;
				}
			} else {
				$pages = $current_query->max_num_pages;
			}
		}

		if ( 1 != $pages ) {

			echo "<ul>";


			if ( $paged > 1 ) {
				echo "<li><a class='pagination-prev' href='" . get_pagenum_link( $paged - 1 ) . "'><span class=\"nc-icon-glyph arrows-1_bold-left\"></span></a></li>";
			}

			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					echo "<li><a href='" . get_pagenum_link( $i ) . "' class='inactive' >" . $i . "</a></li>";
				}
			}

			if ( $paged < $pages ) {
				echo "<li><a class='pagination-next' href='" . get_pagenum_link( $paged + 1 ) . "'><span class=\"nc-icon-glyph arrows-1_bold-right\"></span></a></li>";
			}

			echo "</ul>\n";
		}
	}
}

if ( ! function_exists( 'mt_content_nav' ) ) {
	/**
	 * Display navigation to next/previous pages when applicable
	 */
	function mt_content_nav( $nav_id ) {
		global $wp_query, $post;

		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next     = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';
		?>

		<nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
			<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'regina' ); ?></h1>

			<?php if ( is_single() ) : // navigation links for single posts ?>

				<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'regina' ) . '</span> %title' ); ?>
				<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'regina' ) . '</span>' ); ?>

			<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

				<?php if ( get_next_posts_link() ) : ?>
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'regina' ) ); ?></div>
				<?php endif; ?>

				<?php if ( get_previous_posts_link() ) : ?>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'regina' ) ); ?></div>
				<?php endif; ?>

			<?php endif; ?>
			<div class="clearfix"></div>
		</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->

		<?php
	}
}

if ( ! function_exists( 'mt_color_luminance' ) ) {
	/**
	 * Lightens/darkens a given colour (hex format), returning the altered colour in hex format.7
	 *
	 * @param str $hex Colour as hexadecimal (with or without hash);
	 *
	 * @percent float $percent Decimal ( 0.2 = lighten by 20%(), -0.4 = darken by 40%() )
	 * @return str Lightened/Darkend colour as hexadecimal (with hash);
	 */
	function mt_color_luminance( $hex, $percent ) {
		// validate hex string

		$hex     = preg_replace( '/[^0-9a-f]/i', '', $hex );
		$new_hex = '#';

		if ( strlen( $hex ) < 6 ) {
			$hex = $hex[0] + $hex[0] + $hex[1] + $hex[1] + $hex[2] + $hex[2];
		}

		// convert to decimal and change luminosity
		for ( $i = 0; $i < 3; $i++ ) {
			$dec = hexdec( substr( $hex, $i * 2, 2 ) );
			$dec = min( max( 0, $dec + $dec * $percent ), 255 );
			$new_hex .= str_pad( dechex( $dec ), 2, 0, STR_PAD_LEFT );
		}

		return $new_hex;
	}
}


if ( ! function_exists( 'mt_calc_color_brightness' ) ) {
	/**
	 * Convert Calculate the brightness of a color
	 *
	 * @param  string $color Color (Hex) Code
	 *
	 * @return integer brightness level
	 */
	function mt_calc_color_brightness( $color ) {

		if ( strtolower( $color ) == 'black' || strtolower( $color ) == 'navy' || strtolower( $color ) == 'purple' || strtolower( $color ) == 'maroon' || strtolower( $color ) == 'indigo' || strtolower( $color ) == 'darkslategray' || strtolower( $color ) == 'darkslateblue' || strtolower( $color ) == 'darkolivegreen' || strtolower( $color ) == 'darkgreen' || strtolower( $color ) == 'darkblue' ) {
			$brightness_level = 0;
		} elseif ( strpos( $color, '#' ) === 0 ) {
			$color = mt_hex2rgb( $color );

			$brightness_level = sqrt( pow( $color[0], 2 ) * 0.299 + pow( $color[1], 2 ) * 0.587 + pow( $color[2], 2 ) * 0.114 );
		} else {
			$brightness_level = 150;
		}

		return $brightness_level;
	}
}


if ( ! function_exists( 'mt_rgb2hsl' ) ) {
	/**
	 * Convert RGB to HSL color model
	 *
	 * @param  string $hex Color Hex Code of RGB color
	 *
	 * @return array       HSL values
	 */
	function mt_rgb2hsl( $hex_color ) {

		$hex_color = str_replace( '#', '', $hex_color );

		if ( strlen( $hex_color ) < 3 ) {
			str_pad( $hex_color, 3 - strlen( $hex_color ), '0' );
		}

		$add    = strlen( $hex_color ) == 6 ? 2 : 1;
		$aa     = 0;
		$add_on = $add == 1 ? ( $aa = 16 - 1 ) + 1 : 1;

		$red   = round( ( hexdec( substr( $hex_color, 0, $add ) ) * $add_on + $aa ) / 255, 6 );
		$green = round( ( hexdec( substr( $hex_color, $add, $add ) ) * $add_on + $aa ) / 255, 6 );
		$blue  = round( ( hexdec( substr( $hex_color, ( $add + $add ), $add ) ) * $add_on + $aa ) / 255, 6 );

		$hsl_color = array( 'hue' => 0, 'sat' => 0, 'lum' => 0 );

		$minimum = min( $red, $green, $blue );
		$maximum = max( $red, $green, $blue );

		$chroma = $maximum - $minimum;

		$hsl_color['lum'] = ( $minimum + $maximum ) / 2;

		if ( $chroma == 0 ) {
			$hsl_color['lum'] = round( $hsl_color['lum'] * 100, 0 );

			return $hsl_color;
		}

		$range = $chroma * 6;

		$hsl_color['sat'] = $hsl_color['lum'] <= 0.5 ? $chroma / ( $hsl_color['lum'] * 2 ) : $chroma / ( 2 - ( $hsl_color['lum'] * 2 ) );

		if ( $red <= 0.004 || $green <= 0.004 || $blue <= 0.004 ) {
			$hsl_color['sat'] = 1;
		}

		if ( $maximum == $red ) {
			$hsl_color['hue'] = round( ( $blue > $green ? 1 - ( abs( $green - $blue ) / $range ) : ( $green - $blue ) / $range ) * 255, 0 );
		} else if ( $maximum == $green ) {
			$hsl_color['hue'] = round( ( $red > $blue ? abs( 1 - ( 4 / 3 ) + ( abs( $blue - $red ) / $range ) ) : ( 1 / 3 ) + ( $blue - $red ) / $range ) * 255, 0 );
		} else {
			$hsl_color['hue'] = round( ( $green < $red ? 1 - 2 / 3 + abs( $red - $green ) / $range : 2 / 3 + ( $red - $green ) / $range ) * 255, 0 );
		}

		$hsl_color['sat'] = round( $hsl_color['sat'] * 100, 0 );
		$hsl_color['lum'] = round( $hsl_color['lum'] * 100, 0 );

		return $hsl_color;
	}
}

if ( ! function_exists( 'mt_get_page_option' ) ) {
	/**
	 * Get page option value
	 *
	 * @param  string  $page_option ID of page option
	 * @param  integer $post_id     Post/Page ID
	 *
	 * @return string               Value of page option
	 */
	function mt_get_page_option( $post_id, $page_option = null, $deep_value = null ) {

		#sanitization
		$post_id = absint( $post_id );

		$post_option = get_post_meta( $post_id, strtolower( MT_THEME_NAME ) . '_options', true );

		# Get the whole array
		if ( $post_id !== null && $page_option == null && $deep_value == null ) {
			return $post_option;

			# No Deep Value
		} else if ( $page_option !== null && $post_id !== null && $deep_value == null ) {


			if ( array_key_exists( $page_option, (array) $post_option ) ) {
				if ( ! empty( $post_option[ $page_option ] ) ) {
					return $post_option[ $page_option ];
				}
			} else {
				return 0;
			}

			# Array inside array
			# Get deep value
		} else if ( $page_option !== null && $post_id !== null && $deep_value !== null ) {

			if ( array_key_exists( $page_option, (array) $post_option ) && is_array( $post_option[ $page_option ] ) && ! empty( $post_option[ $page_option ][ $deep_value ] ) ) {

				switch ( $deep_value ) {

					case 'background-image':
						return wp_get_attachment_url( $post_option[ $page_option ][ $deep_value ] );
						break;

					default:
						return $post_option[ $page_option ][ $deep_value ];
						break;
				}
			} else {
				return false;
			}
		}

		return false;

	}
}

if ( ! function_exists( 'mt_get_page_options' ) ) {
	function mt_get_page_options( $post_id, $page_options = array() ) {

		if ( empty( $page_options ) ) {
			return $page_options;
		}

		$options = array();
		foreach ( $page_options as $option ) {
			if ( 0 !== mt_get_page_option( $post_id, $option ) ) {
				$options[ $option ] = mt_get_page_option( $post_id, $option );
			}
		}

		return $options;

	}
}


if ( ! function_exists( 'mt_display_sharingbox_social_links_array' ) ) {
	/**
	 * Set up the array for sharing box social networks.
	 *
	 * @since 3.5.0
	 * @return array  The social links array containing the social media and links to them.
	 */
	function mt_display_sharingbox_social_links_array() {

		/*
		 * Build the array
		 */
		$social_links_array = array();

		$social_link                    = 'http://www.facebook.com/sharer.php?m2w&s=100&p&#91;url&#93;=' . get_the_permalink() . '&p&#91;images&#93;&#91;0&#93;=' . wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ) . '&p&#91;title&#93;=' . rawurlencode( get_the_title() );
		$social_links_array['facebook'] = $social_link;

		$social_link                   = 'https://twitter.com/share?text=' . rawurlencode( get_the_title() ) . '&url=' . rawurlencode( get_the_permalink() );
		$social_links_array['twitter'] = $social_link;

		$social_link                    = 'http://linkedin.com/shareArticle?mini=true&amp;url=' . get_the_permalink() . '&amp;title=' . rawurlencode( get_the_title() );
		$social_links_array['linkedin'] = $social_link;

		$social_link                = 'mailto:?subject=' . get_the_title() . '&amp;body=' . get_the_permalink();
		$social_links_array['mail'] = $social_link;

		return $social_links_array;
	}
}

if ( ! function_exists( 'mt_output_social_sharing_box' ) ) {

	function mt_output_social_sharing_box() {

		$social_background_color = mt_get_theme_option( 'social-share-background-color' );
		$social_icon_color       = mt_get_theme_option( 'social-share-icon-color' );


		if ( $social_background_color ) {
			echo '<div class="mt-social-sharing-box" style="background-color:' . $social_background_color . ';">';
		} else {
			echo '<div class="mt-social-sharing-box">';
		}

		if ( ! $social_icon_color ) {
			$social_icon_color = '#777';
		}

		echo '<div class="col-lg-6">';
		echo '<h4 style="color:' . $social_icon_color . '">' . __( 'Share this article - choose your platform:', 'regina' ) . '</h4>';
		echo '</div>';

		echo '<div class="col-lg-6 text-right">';
		/*
		 * Start the HTML output
		 */
		foreach ( mt_display_sharingbox_social_links_array() as $key => $value ) {

			switch ( $key ) {
				case 'facebook':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Facebook"><i class="fa fa-facebook" style="color:' . $social_icon_color . '" ></i></a>';
					break;
				case 'twitter':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Twitter"><i class="fa fa-twitter" style="color:' . $social_icon_color . '"></i></a>';
					break;
				case 'reddit':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Reddit"><i class="fa fa-reddit" style="color:' . $social_icon_color . '"></i></a>';
					break;
				case 'linkedin':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="LinkedIN"><i class="fa fa-linkedin" style="color:' . $social_icon_color . '"></i></a>';
					break;
				case 'googleplus':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Google+"><i class="fa fa-google-plus" style="color:' . $social_icon_color . '"></i></a>';
					break;
				case 'tumblr':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Tumblr"><i class="fa fa-tumblr" style="color:' . $social_icon_color . '"></i></a>';
					break;
				case 'pinterest':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Pinterest"><i class="fa fa-pinterest" style="color:' . $social_icon_color . '"></i></a>';
					break;
				case 'vk':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Vkontakte"><i class="fa fa-vk" style="color:' . $social_icon_color . '"></i></a>';
					break;
				case 'mail':
					echo '<a target="_blank" rel="nofollow" href="' . $value . '" data-toggle="tooltip" data-placement="top" title="Mail"><i class="fa fa-envelope" style="color:' . $social_icon_color . '"></i></a>';
					break;
			}

		}

		echo '</div><!--/.col-lg-6-->';
		echo '</div><!--/.mt-social-sharing-box-->';

	}
}


if ( ! function_exists( 'mt_the_excerpt_max_charlength' ) ) {
	/**
	 * Function to change the excerpt character length
	 */
	function mt_the_excerpt_max_charlength( $charlength ) {

		$excerpt = get_the_excerpt();
		$charlength++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut   = -( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				return mb_substr( $subex, 0, $excut );
			} else {
				return $subex;
			}

		} else {
			return $excerpt;
		}
	}
}

if ( ! function_exists( 'mt_the_excerpt_read_more_link' ) ) {
	/**
	 * Function to change the excerpt red more text
	 */
	function mt_the_excerpt_read_more_link() {
		$urlText = mt_get_theme_option( 'blog-excerpt-read-more-text' ) != '' ? esc_html( mt_get_theme_option( 'blog-excerpt-read-more-text' ) ) : __( 'Read more', 'regina' );

		return ' <a href="' . get_the_permalink() . '">' . $urlText . '</a>';

	}
}

if ( ! function_exists( 'mt_check_plugin_active_status' ) ) {
	/**
	 * Simple utility function to return true / false if a plugin is active or not
	 */
	function mt_check_plugin_active_status( $plugin_slug ) {

		/*
		* Require plugin.php to use is_plugin_active() below
		*/
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		// check if plugin is activated
		if ( is_plugin_active( esc_html( $plugin_slug ) ) ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'mt_get_current_user_field' ) ) {
	function mt_get_current_user_field( $field_name = null ) {

		global $current_user;
		get_currentuserinfo();

		if ( $field_name !== null ) {
			return $current_user->{$field_name}; // returns only the field passed as param
		} else {
			return (array) $current_user; // returns the whole array
		}

		return false;

	}
}


if ( ! function_exists( 'mt_get_related_posts' ) ) {
	/**
	 * Get related posts by category
	 *
	 * @param  integer $post_id      current post id
	 * @param  integer $number_posts number of posts to fetch
	 *
	 * @return object                  object with posts info
	 */
	function mt_get_related_posts( $post_id, $number_posts = -1 ) {

		$related_postsuery = new WP_Query();
		$args              = '';

		if ( $number_posts == 0 ) {
			return $related_postsuery;
		}

		$args = wp_parse_args( $args, array(
			'category__in'        => wp_get_post_categories( $post_id ),
			'ignore_sticky_posts' => 0,
			'posts_per_page'      => $number_posts,
			'post__not_in'        => array( $post_id ),
			'meta_key'            => '_thumbnail_id',
		) );


		$related_postsuery = new WP_Query( $args );
		wp_reset_postdata();
		wp_reset_query();

		return $related_postsuery;
	}
}

if ( ! function_exists( 'mt_render_related_posts' ) ) {
	/**
	 * Render related posts carousel
	 *
	 * @return string                    HTML markup to display related posts
	 **/
	function mt_render_related_posts() {

		$return_string = '';

		$return_string = '<div class="mt-related-posts">';

		// Check if related posts should be shown
		$related_posts = mt_get_related_posts( get_the_ID(), mt_get_theme_option( 'blog-related-posts-max-pull' ) );

		// Number of posts to show / view
		$limit = ( ( mt_get_theme_option( 'blog-related-posts-number' ) !== 0 ) ? mt_get_theme_option( 'blog-related-posts-number' ) : '4' );

		// Auto play
		$auto_play = ( ( mt_get_theme_option( 'blog-related-auto-play' ) == 1 ) ? true : false );

		// Pagination
		$pagination = ( ( mt_get_theme_option( 'blog-related-pagination' ) == 1 ) ? true : false );


		$return_string .= '<div class="row">';

		/*
		 * Heading
		 */
		$return_string .= '<div class="col-sm-11 col-xs-12">';
		$return_string .= '<h3>' . __( 'Related posts: ', 'regina' ) . '</h3>';
		$return_string .= '</div>';

		/*
		 * Arrows
		 */
		$return_string .= '<div class="col-sm-1 hidden-xs">';
		$return_string .= '<ul class="mt-carousel-arrows clearfix">';
		$return_string .= '<li class="pull-right"><a href="#" class="mt-owl-next fa fa-angle-right"></a></li>';
		$return_string .= '<li class="pull-left"><a href="#" class="mt-owl-prev fa fa-angle-left"></a></li>';

		$return_string .= '</ul>';
		$return_string .= '</div>';
		$return_string .= '</div><!--/.row-->';

		$return_string .= sprintf( '<div class="owlCarousel" data-slider-single-item="0" data-slider-id="%s" id="owlCarousel-%s" data-slider-items="%s" data-slider-speed="400" data-slider-auto-play="%s" data-slider-navigation="false" data-slider-pagination="%s">', get_the_ID(), get_the_ID(), $limit, $auto_play, $pagination );

		// Loop through related posts
		while ( $related_posts->have_posts() ): $related_posts->the_post();

			$return_string .= '<div class="mt-blogpost">';
			$return_string .= '<div class="item">';
			$return_string .= '<div class="col-sm-12">';
			$return_string .= '<div class="thumbnail">';

			if ( mt_get_theme_option( 'blog-enable-featured-image' ) == 1 ) {
				if ( has_post_thumbnail( $related_posts->post->ID ) ) {
					$return_string .= '<a href="' . esc_url( get_the_permalink() ) . '">' . get_the_post_thumbnail( $related_posts->post->ID, 'homepage-blog-posts' ) . '</a>';
				}
			}

			$return_string .= '<div class="caption align-center">';

			if ( mt_get_theme_option( 'blog-enable-post-meta-date' ) == 1 ) {
				$return_string .= '<div class="mt-date">' . get_the_date( get_option( 'date-format' ), $related_posts->post->ID ) . '</div>';
			}

			if ( mt_get_theme_option( 'blog-enable-post-title' ) == 1 ) {
				$return_string .= '<a class="mt-blogpost-title" href="' . esc_url( get_the_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
			}

			$return_string .= '</div><!--/.caption-->';


			$return_string .= '</div><!--/.thumbnail-->';
			$return_string .= '</div> <!--/.col-sm-6.col-md-4-->';
			$return_string .= '</div><!--/.item-->';
			$return_string .= '</div> <!--/.blogPost-->';

		endwhile;

		$return_string .= '</div>'; // owl Carousel
		$return_string .= '</div><!--/.mt-related-posts-->';

		wp_reset_query();
		wp_reset_postdata();

		return $return_string;

	}
}

if ( ! function_exists( 'mt_get_number_of_comments' ) ) {
	/**
	 * Simple function used to return the number of comments a post has.
	 */
	function mt_get_number_of_comments( $post_id ) {

		$num_comments = get_comments_number( $post_id ); // get_comments_number returns only a numeric value

		if ( comments_open() ) {
			if ( $num_comments == 0 ) {
				$comments = __( 'No Comments', 'regina' );
			} elseif ( $num_comments > 1 ) {
				$comments = $num_comments . __( ' Comments', 'regina' );
			} else {
				$comments = __( '1 Comment', 'regina' );
			}
			$write_comments = '<a href="' . get_comments_link() . '">' . $comments . '</a>';
		} else {
			$write_comments = __( 'Comments are off for this post.', 'regina' );
		}

		return $write_comments;

	}
}
if ( ! function_exists( 'mt_fix_responsive_videos' ) ) {
	/*
	/* Add responsive container to embeds
	*/
	function mt_fix_responsive_videos( $html ) {
		return '<div class="mt-video-container">' . $html . '</div>';
	}

	add_filter( 'embed_oembed_html', 'mt_fix_responsive_videos', 10, 3 );
	add_filter( 'video_embed_html', 'mt_fix_responsive_videos' ); // Jetpack
}

if ( ! function_exists( 'mt_breadcrumbs' ) ) {
	/**
	 * Render the breadcrumbs with help of class-breadcrumbs.php
	 *
	 * @return void
	 */
	function mt_breadcrumbs() {
		$breadcrumbs = new Macho_Breadcrumbs();
		$breadcrumbs->get_breadcrumbs();
	}
}


function mt_nice_debug( $var, $type = 'print_r' ) {

	switch ( $type ) {
		case 'print_r':

			echo "<pre>";
			print_r( $var );
			echo "<pre>";

			break;
		case 'var_dump':

			echo "<pre>";
			var_dump( $var );
			echo "<pre>";

			break;
	}
}


function generateRandomString( $length = 10 ) {
	$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen( $characters );
	$randomString     = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$randomString .= $characters[ rand( 0, $charactersLength - 1 ) ];
	}

	return $randomString;
}

function mt_get_sidebars() {

	$sidebars       = array(
		'sidebar-page' => 'Page Sidebar',
		'sidebar-blog' => 'Blog Sidebar',
	);
	$theme_sidebars = get_theme_mod( 'regina_multi_sidebars', array() );

	if ( ! empty( $theme_sidebars ) ) {
		foreach ( $theme_sidebars as $theme_sidebar ) {
			$sidebarID              = 'sidebar-' . str_replace( ' ', '-', strtolower( $theme_sidebar['sidebar_name'] ) );
			$sidebars[ $sidebarID ] = $theme_sidebar['sidebar_name'];
		}
	}

	return $sidebars;

}

/**
 * Get an attachment ID given a URL.
 *
 * @param string $url
 *
 * @return int Attachment ID on success, 0 on failure
 */
function mt_get_attachment_id( $url ) {
	$attachment_id = 0;
	$dir           = wp_upload_dir();
	if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
		$file       = basename( $url );
		$query_args = array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'fields'      => 'ids',
			'meta_query'  => array(
				array(
					'value'   => $file,
					'compare' => 'LIKE',
					'key'     => '_wp_attachment_metadata',
				),
			),
		);
		$query      = new WP_Query( $query_args );
		if ( $query->have_posts() ) {
			foreach ( $query->posts as $post_id ) {
				$meta                = wp_get_attachment_metadata( $post_id );
				$original_file       = basename( $meta['file'] );
				$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
				if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
					$attachment_id = $post_id;
					break;
				}
			}
		}
	}

	return $attachment_id;
}

/**
 * Custom Read More on excerpts
 */
if ( ! function_exists( 'mt_excerpt_more' ) ) {
	function mt_excerpt_more( $more ) {
		global $post;

		return '<div class="view-full-post"><a href="' . get_permalink( $post->ID ) . '" class="view-full-post-btn">' . __( 'View Full Post', 'regina' ) . '</a></div>';
	}

	add_filter( 'excerpt_more', 'mt_excerpt_more' );
}

/**
 * Remove &nbsp; from TinyMCE when switching from Text -> Visual Editor Mode
 */
if ( ! function_exists( 'mt_allow_nbsp_in_tinymce' ) ) {
	function mt_allow_nbsp_in_tinymce( $mceInit ) {
		$mceInit['entities']        = '';
		$mceInit['entity_encoding'] = 'named';

		return $mceInit;
	}

	add_filter( 'tiny_mce_before_init', 'mt_allow_nbsp_in_tinymce' );
}