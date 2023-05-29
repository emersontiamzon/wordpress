<?php

if ( ! function_exists( 'regina_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function regina_setup() {

		/*
		* Using this feature you can set the maximum allowed width for any content in the theme, like oEmbeds and images added to posts.
		* @see http://codex.wordpress.org/Content_Width
		*/
		if ( ! isset( $content_width ) ) {
			$content_width = 1140;
		}

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on machoframe, use a find and replace
		 * to change 'qck' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'regina', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => 'Header Menu',
				'footer'  => 'Footer Menu',
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Add custom logo
		add_theme_support(
			'custom-logo', array(
				'height'     => 100,
				'width'      => 400,
				'flex-width' => true,
			)
		);

		// Custom Background
		$custom_background_args = array(
			'default-color'      => '#fff',
			'default-image'      => false,
			'default-repeat'     => false,
			'default-position-x' => false,
			'default-attachment' => false,
		);
		add_theme_support( 'custom-background', $custom_background_args );

		// image sizes
		add_image_size( 'slider-image-sizes', 1682, 560, true );
		add_image_size( 'team-image-sizes', 263, 9999 );

		//selective refresh
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Include backwards compatibility
		require_once get_template_directory() . '/includes/back-compatible.php';

		// Load theme core framework
		include_once dirname( __FILE__ ) . '/includes/framework/framework-init.php';

	}

	add_action( 'after_setup_theme', 'regina_setup' );
} // End if().


function regina_scripts_and_styles() {

	// Preloader
	$preloader = get_theme_mod( 'regina_preloader', true );
	$type      = get_theme_mod( 'regina_preloader_type', 'center-atom' );
	$gmap_api  = get_theme_mod( 'regina_map_api' );

	//Inlcude Fonts
	$primary_font   = get_theme_mod(
		'regina_theme_primary_font', array(
			'font-family' => 'Lato',
		)
	);
	$secondary_font = get_theme_mod(
		'regina_theme_secondary_font', array(
			'font-family' => 'Montserrat',
		)
	);
	$theme_color    = get_theme_mod( 'regina_theme_color', '#08cae8' );

	$google_url = 'https://fonts.googleapis.com/css?family=' . $primary_font['font-family'] . ':400,700%7C' . $secondary_font['font-family'] . ':400,700';
	wp_enqueue_style( 'regina-fonts', $google_url );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'regina-bootstrap-style', get_template_directory_uri() . '/assets/css/bootstrap/bootstrap.min.css' );
	wp_enqueue_style( 'regina-mobile-style', get_template_directory_uri() . '/assets/css/mobile.min.css' );
	wp_enqueue_style( 'regina-main-style', get_template_directory_uri() . '/assets/css/styles.min.css' );
	wp_enqueue_style( 'regina-modal-css', get_template_directory_uri() . '/assets/css/modal.min.css' );

	if ( $preloader && ! is_customize_preview() ) {
		if ( 'corner-top' == $type ) {
			wp_enqueue_style( 'pace-corner-top-css', get_template_directory_uri() . '/assets/css/pace/pace-corner-top.css' );
		} elseif ( 'loading-bar' == $type ) {
			wp_enqueue_style( 'pace-loading-bar-css', get_template_directory_uri() . '/assets/css/pace/pace-loading-bar.css' );
		} elseif ( 'center-radar' == $type ) {
			wp_enqueue_style( 'pace-center-radar-css', get_template_directory_uri() . '/assets/css/pace/pace-center-radar.css' );
		} elseif ( 'center-atom' == $type ) {
			wp_enqueue_style( 'pace-center-atom-css', get_template_directory_uri() . '/assets/css/pace/pace-center-atom.css' );
		}
	}

	if ( is_single() ) {
		wp_enqueue_style( 'regina-owl-carousel', get_template_directory_uri() . '/assets/css/owl-carousel/owl-carousel.css', array(), '', 'all' );
		wp_enqueue_style( 'regina-owl-theme', get_template_directory_uri() . '/assets/css/owl-carousel/owl-theme.css', array(), '', 'all' );
	}

	wp_enqueue_style( 'regina-style', get_stylesheet_uri() );

	//Custom inline css
	$custom_css = '';
	if ( '#08cae8' != $theme_color ) {
		$custom_css .= '#sidebar .widget_recent_entries ul li:before, #sidebar .widget_recent_comments ul li:before, #sidebar .widget_recent_comments ul li, #sidebar .widget_categories ul li:before, .icon-list li,.icon-list.grey li:before,p a,.button.outline,.button.white,.button.white.outline:hover,.button.white.outline:focus,.google-map .content .company,#sub-header .social-link-list li a:hover,#sub-header .social-link-list li a:focus,#navigation ul li a:hover,#navigation ul li a:focus,#breadcrumb ul li a:hover,#breadcrumb ul li a:focus,#footer a a:hover,#footer a a:focus,#footer .link-list a:hover,#footer .link-list a:focus,#footer .social-link-list li a:hover,#footer .social-link-list li a:focus,#sub-footer .link-list li a:hover,#sub-footer .link-list li a:focus,#services-block .service .icon,#blog .post .title a:hover,#blog .post .title a:focus,#blog .post #post-navigation a:hover,#blog .post #post-navigation a:focus,#blog .post #share-post .social li.email a,#blog .post #post-author .content .social li a:hover,#blog .post #post-author .content .social li a:focus,#blog #related-posts .post a .inner .date,#sidebar .recent-posts li a:hover,#sidebar .recent-posts li a:focus,#sidebar .categories li a:hover,#sidebar .categories li a:focus,#sidebar .comments li a:hover,#sidebar .comments li a:focus,#sidebar .recent-posts li:before,#sidebar .categories li:before,#sidebar .comments li:before,#sidebar .comments li a,#comments-list ul.comments li.comment .content .meta,.contact-social li a:hover,.contact-social li a:focus,.medic .medic-meta .inner .social li a:hover,.medic .medic-meta .inner .social li a:focus,.medic .medic-description .position,#sidebar .other-services li a:hover,#sidebar .other-services li a:focus, .icon-list.grey li:before, a, p a { color: ' . esc_attr( $theme_color ) . ' }';
		$custom_css .= '.ui-datepicker-header, #wp-calendar caption, #appointment-form .input input[type="text"], #appointment-form .input textarea, .button, .button.outline:hover,.button.outline:focus,.button.dark.outline:hover,.button.dark.outline:focus,hr,.google-map .marker .icon,#navigation .nav-search-box .search-btn,#call-out,#services-block .service:hover .icon,#team-block .team-member .hover,#home-testimonials,#blog .post .post-tags li a:hover,#blog .post .post-tags li a:focus,#blog #related-posts .post a:hover .inner,#blog #related-posts .post a:focus .inner,#sidebar .search.widget .search-btn,#sidebar .tags li a:hover,#sidebar .tags li a:focus,#related-team .team-member .hover,#sidebar .related-doctors .doctor a:hover,#sidebar .related-doctors .doctor a:focus,.accordion ul li a,.ui-datepicker-header,#team-block .team-member .hover .read-more { background: ' . $theme_color . '; }';
		$custom_css .= '#services-block .service:hover .icon a {color: #FFF; }';
		// booking form custom CSS
		$custom_css .= '.nav-menu-search button.icon, .widget_search input[type="submit"], .modaloverlay .mt-modal, #appointment-form .input input[type="text"], #appointment-form .input textarea, #sidebar .widget_tag_cloud a:hover, #sidebar .widget_tag_cloud a:focus  {background-color: ' . esc_attr( $theme_color ) . '!important;}';
		$custom_css .= '.button.outline,input[type="text"]:focus,textarea:focus,#navigation ul li a:hover,#navigation ul li a:focus,#services-block .service .icon { border-color: ' . esc_attr( $theme_color ) . '; }';
		$custom_css .= '::-moz-selection { background: ' . esc_attr( $theme_color ) . '; }';
		$custom_css .= '::selection { background: ' . esc_attr( $theme_color ) . '; }';
		$custom_css .= '.macho_widget_contact p:hover span, #footer .macho_widget_contact p:hover a, .macho_widget_contact p:hover a, .modaloverlay .mt-close, a.link, .view-full-post-btn:after, #sub-header p:hover span, #sub-header p a:hover,#sub-header p a:focus, #sub-header p:hover a, #sidebar .widget_categories li a:hover,#sidebar .widget_categories li a:focus, #sidebar .widget_recent_comments li a:hover, #sidebar .widget_recent_comments li a:focus, #sidebar .widget_recent_entries li a:hover, #sidebar .widget_recent_entries li a:focus, #footer a:hover, #footer a:focus, #appointment-form input[type=submit]:hover, #appointment-form input[type=submit]:focus {color: ' . esc_attr( $theme_color ) . '}';
		$custom_css .= 'nav ul li:hover, nav ul li:focus-within , .nav-menu-search input#search {border-color: ' . esc_attr( $theme_color ) . ' !important }';
	}

	wp_add_inline_style( 'regina-style', $custom_css );

	if ( is_singular() ) {
		if ( comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_enqueue_script( 'regina-owl-carousel-min', get_template_directory_uri() . '/assets/js/plugins/owl-carousel/owl-carousel.min.js', array( 'jquery' ), '', true );
	}
	wp_enqueue_script( 'regina-bxslider-script', get_template_directory_uri() . '/assets/js/plugins/bxslider/bxslider.min.js', array( 'jquery' ), '1.0.0', true );
	if ( $gmap_api ) {
		wp_enqueue_script( 'regina-gmaps-script', get_template_directory_uri() . '/assets/js/plugins/gmap/gmaps.min.js', array( 'jquery' ), '1.0.0', true );
	}
	wp_enqueue_script( 'regina-lazyloads-script', get_template_directory_uri() . '/assets/js/plugins/lazyload/lazyload.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'regina-waypoints-script', get_template_directory_uri() . '/assets/js/plugins/waypoints/waypoints.min.js', array( 'jquery' ), '1.0.0', true );
	if ( $preloader && ! is_customize_preview() ) {
		wp_enqueue_script( 'regina-pace-script', get_template_directory_uri() . '/assets/js/pace/pace.min.js', array( 'jquery' ), '1.0.0', true );
	}
	wp_enqueue_script( 'regina-preloader-script', get_template_directory_uri() . '/assets/js/preloader.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'regina-custom-script', get_template_directory_uri() . '/assets/js/custom.min.js', array( 'jquery' ), '1.0.0', true );

	$urls = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);

	if ( get_theme_mod( 'regina_slider_autoplay', 0 ) ) {
		$urls['autoplay'] = true;
	} else {
		$urls['autoplay'] = false;
	}
	$urls['speed'] = get_theme_mod( 'regina_slider_spped', 500 );
	$urls['pause'] = get_theme_mod( 'regina_slider_auto_pause', 4000 );

	wp_localize_script( 'regina-custom-script', 'regina', $urls );

}

add_action( 'wp_enqueue_scripts', 'regina_scripts_and_styles' );

// Include customizer
require_once 'includes/class-regina-customizer.php';

function regina_custom_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback':
		case 'trackback':
			?>
			<li <?php comment_class(); ?> id="comment<?php comment_ID(); ?>">
			<div class="back-link">< ?php comment_author_link(); ?></div>
			<?php
			break;
		default:
			?>

			<li <?php comment_class( 'comment' ); ?>>
				<div class="row">
					<div class="col-xs-2">
						<?php echo get_avatar( $comment, 100 ); ?>
					</div><!--.col-xs-1-->

					<div class="col-xs-10">
						<div class="content">
							<p class="name"><?php comment_author(); ?></p>
							<p class="meta"><?php comment_date(); ?> at <?php comment_time(); ?></p>
							<div class="body"><?php comment_text(); ?></div>
<?php
comment_reply_link(
	array_merge(
		$args, array(
			'reply_text' => __( 'Reply', 'regina' ),
			'after'      => '',
			'depth'      => $depth,
			'max_depth'  => $args['max_depth'],
		)
	)
);
?>
						</div>
					</div>
				</div><!--.row-->
			</li><!--.comment-->
			<?php
			// End the default styling of comment
			break;
	endswitch;
}

add_action( 'wp_ajax_send_appointment_email', 'regina_send_appointment_email' );
add_action( 'wp_ajax_nopriv_send_appointment_email', 'regina_send_appointment_email' );

function regina_set_html_content_type() {
	return 'text/html';
}

function regina_send_appointment_email() {

	$html = '';

	if ( isset( $_POST['name'] ) && '' != $_POST['name'] ) {
		$html .= __( 'Patient Name: ', 'regina' ) . $_POST['name'] . '<br>';
	} else {
		echo 'error';
		die();
	}

	if ( isset( $_POST['email'] ) && '' != $_POST['email'] ) {
		$html .= __( 'Patient Email: ', 'regina' ) . $_POST['email'] . '<br>';
	} else {
		echo 'error';
		die();
	}

	if ( isset( $_POST['phone'] ) && '' != $_POST['phone'] ) {
		$html .= __( 'Patient Phone: ', 'regina' ) . $_POST['phone'] . '<br>';
	} else {
		echo 'error';
		die();
	}

	if ( isset( $_POST['date'] ) && '' != $_POST['date'] ) {
		$html .= __( 'Appointment Date: ', 'regina' ) . $_POST['date'] . '<br>';
	} else {
		echo 'error';
		die();
	}

	if ( isset( $_POST['message'] ) && '' != $_POST['message'] ) {
		$html .= __( 'Patient Message: ', 'regina' ) . $_POST['message'] . '<br>';
	} else {
		echo 'error';
		die();
	}

	$email = '';

	$contact_email = get_theme_mod( 'regina_top_email', '' );

	if ( '' != $contact_email ) {
		$email = $contact_email;
	} else {
		$email = get_option( 'admin_email' );
	}

	add_filter( 'wp_mail_content_type', 'regina_set_html_content_type' );

	wp_mail( $email, __( 'New Appointment', 'regina' ), $html );

	remove_filter( 'wp_mail_content_type', 'regina_set_html_content_type' );

	echo 'succes';
	die();

}

// Backwards compatibility for Page Templates
add_filter( 'page_template', 'regina_fix_page_templates' );
function regina_fix_page_templates( $t ) {

	$old_templates = array( 'page-services.php', 'page-full-width.php', 'page-contact.php', 'page-members.php' );
	$new_templates = array(
		'page-services.php'   => 'page-templates/template-services.php',
		'page-full-width.php' => 'page-templates/template-full-width.php',
		'page-contact.php'    => 'page-templates/template-contact.php',
		'page-members.php'    => 'page-templates/template-members.php',
	);

	$page_id  = get_queried_object_id();
	$template = get_post_meta( $page_id, '_wp_page_template', true );

	if ( $template && 'default' != $template && in_array( $template, $old_templates ) ) {
		if ( file_exists( trailingslashit( STYLESHEETPATH ) . $new_templates[ $template ] ) ) {
			$t = trailingslashit( STYLESHEETPATH ) . $new_templates[ $template ];
		} elseif ( file_exists( trailingslashit( TEMPLATEPATH ) . $new_templates[ $template ] ) ) {
			$t = trailingslashit( TEMPLATEPATH ) . $new_templates[ $template ];
		}
		update_post_meta( $page_id, '_wp_page_template', $new_templates[ $template ] );
	}
	return $t;
}

function regina_pro_logo() {

	if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
		the_custom_logo();
	} else {

		echo '<a href="' . esc_attr( site_url() ) . '" class="logo-text">';
		bloginfo( 'name' );
		echo '</a>';
	}

}

require_once 'includes/class-regina-walker-nav.php';
require_once 'includes/libraries/epsilon-framework/class-epsilon-autoloader.php';
require_once 'includes/libraries/class-regina-notify-system.php';
require_once 'includes/class-regina.php';
new Regina();
