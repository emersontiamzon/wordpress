<?php
// Generate custom CSS.
function antreas_generate_custom_css() {
	$primary_color         = antreas_get_option( 'primary_color' );
	$secondary_color       = antreas_get_option( 'secondary_color' );
	$type_size             = antreas_get_option( 'type_size' );
	$type_headings         = apply_filters( 'antreas_font_headings', antreas_get_option( 'type_headings' ) );
	$type_nav              = apply_filters( 'antreas_font_menu', antreas_get_option( 'type_nav' ) );
	$type_body             = apply_filters( 'antreas_font_body', antreas_get_option( 'type_body' ) );
	$color_headings        = apply_filters( 'antreas_color_headings', antreas_get_option( 'type_headings_color' ) );
	$color_widgets         = apply_filters( 'antreas_color_widgets', antreas_get_option( 'type_widgets_color' ) );
	$color_nav             = apply_filters( 'antreas_color_menu', antreas_get_option( 'type_nav_color' ) );
	$color_body            = apply_filters( 'antreas_color_body', antreas_get_option( 'type_body_color' ) );
	$color_links           = apply_filters( 'antreas_color_links', antreas_get_option( 'type_link_color' ) );
	$subfooter_bg_color    = antreas_get_option( 'subfooter_bg_color' );
	$footer_bg_color       = antreas_get_option( 'footer_bg_color' );
	$slider_height         = antreas_get_option( 'slider_height' );

	ob_start();
	?>

	body {
		<?php if ( $type_size != '' ) : ?>
			font-size: <?php echo esc_attr( $type_size ); ?>rem;
		<?php endif; ?>

		<?php if ( $type_body != '' ) : ?>
			font-family: '<?php echo esc_attr( antreas_metadata_fonts_name( $type_body ) ); ?>';
			font-weight: <?php echo esc_attr( antreas_metadata_fonts_weight( $type_body ) ); ?>;
		<?php endif; ?>

		<?php if ( $color_body != '' ) : ?>
			color: <?php echo esc_attr( $color_body ); ?>;
		<?php endif; ?>
	}

	<?php if ( $type_body != '' ) : ?>
		.button, .button:link, .button:visited, input[type=submit] {
			font-family: '<?php echo esc_attr( antreas_metadata_fonts_name( $type_body ) ); ?>';
			font-weight: <?php echo esc_attr( antreas_metadata_fonts_weight( $type_body ) ); ?>;
		}
	<?php endif; ?>

	h1, h2, h3, h4, h5, h6, .heading, .dark .heading, .header .site-title {
		<?php if ( $type_headings != '' ) : ?>
			font-family: '<?php echo esc_attr( antreas_metadata_fonts_name( $type_headings ) ); ?>';
			font-weight: <?php echo esc_attr( antreas_metadata_fonts_weight( $type_headings ) ); ?>;
		<?php endif; ?>

		<?php if ( $color_headings != '' ) : ?>
			color: <?php echo esc_attr( $color_headings ); ?>;
		<?php endif; ?>
	}

	.widget-title {
		<?php if ( $color_widgets != '' ) : ?>
			color: <?php echo esc_attr( $color_widgets ); ?>;
		<?php endif; ?>
	}

	.menu-main li a {
		<?php if ( $type_nav != '' ) : ?>
			font-family:'<?php echo esc_attr( antreas_metadata_fonts_name( $type_nav ) ); ?>';
			font-weight:<?php echo esc_attr( antreas_metadata_fonts_weight( $type_nav ) ); ?>;
		<?php endif; ?>
		<?php if ( $color_nav != '' ) : ?>
			color: <?php echo esc_attr( $color_nav ); ?>;
		<?php endif; ?>
	}

	.menu-mobile li a {
		<?php if ( $type_nav != '' ) : ?>
			font-family:'<?php echo esc_attr( antreas_metadata_fonts_name( $type_nav ) ); ?>';
			font-weight:<?php echo esc_attr( antreas_metadata_fonts_weight( $type_nav ) ); ?>;
		<?php endif; ?>
		<?php if ( $color_body != '' ) : ?>
			color: <?php echo esc_attr( $color_body ); ?>;
		<?php endif; ?>
	}

	<?php if ( $color_links != '' ) : ?>
		a:link, a:visited, a:hover, a:focus {
			color:<?php echo esc_attr( $color_links ); ?>;
		}
	<?php endif; ?>

	<?php if ( $slider_height != '' ) : ?>
		.slider-slides { height:<?php echo esc_attr( $slider_height ); ?>px; }
	<?php endif; ?>

	.subfooter { background-color: <?php echo esc_attr( $subfooter_bg_color ); ?>; }
	.footer { background-color: <?php echo esc_attr( $footer_bg_color ); ?>; }

	<?php if ( $primary_color != '' ) : ?>
		.primary-color { color:<?php echo esc_attr( $primary_color ); ?>; }
		.primary-color-bg { background-color:<?php echo esc_attr( $primary_color ); ?>; }
		.primary-color-border { border-color:<?php echo esc_attr( $primary_color ); ?>; }
		.menu-item.menu-highlight > a { background-color:<?php echo esc_attr( $primary_color ); ?>; }
		.widget_nav_menu a .menu-icon { color:<?php echo esc_attr( $primary_color ); ?>; }
		::selection  { color:#fff; background-color:<?php echo esc_attr( $primary_color ); ?>; }
		::-moz-selection { color:#fff; background-color:<?php echo esc_attr( $primary_color ); ?>; }
		.has-primary-color { color:<?php echo esc_attr( $primary_color ); ?>; }
		.has-primary-background-color { background-color:<?php echo esc_attr( $primary_color ); ?>; }
		.slider-prev:focus:after, .slider-next:focus:after { color: <?php echo esc_attr( $primary_color ); ?>; }
		html body .button,
		html body .button:link,
		html body .button:visited,
		html body input[type=submit],
		.woocommerce #respond input#submit.alt,
		.woocommerce a.button.alt,
		.woocommerce button.button.alt,
		.woocommerce input.button.alt {
			background-color: <?php echo esc_attr( $primary_color ); ?>;
		}

		html body .button:hover,
		html body .button:focus,
		html body input[type=submit]:hover,
		html body input[type=submit]:focus,
		.woocommerce a.button:hover,
		.woocommerce a.button:focus {
			color: #fff;
			background: #121212;
		}

		.menu-top li a:hover,
		.menu-top li a:focus,
		.menu-main li a:hover,
		.menu-main li a:focus,
		.menu-main .current-menu-item > a {
			color: <?php echo esc_attr( $primary_color ); ?>;
		}

		.menu-portfolio .current-cat a,
		.pagination .current { background-color: <?php echo esc_attr( $primary_color ); ?>; }

		.features a.feature-image { color: <?php echo esc_attr( $primary_color ); ?>; }
	<?php endif; ?>

	<?php if ( $secondary_color != '' ) : ?>
		.secondary-color { color:<?php echo esc_attr( $secondary_color ); ?>; }
		.secondary-color-bg { background-color:<?php echo esc_attr( $secondary_color ); ?>; }
		.secondary-color-border { border-color:<?php echo esc_attr( $secondary_color ); ?>; }
		.has-secondary-color { color:<?php echo esc_attr( $secondary_color ); ?>; }
		.has-secondary-background-color { background-color:<?php echo esc_attr( $secondary_color ); ?>; }
	<?php endif; ?>

	<?php
	return preg_replace( '/\s+/', ' ', ob_get_clean() );
}


//Enqueue Google fonts
add_action( 'wp_head', 'antreas_styling_fonts', 20 );
function antreas_styling_fonts() {
	antreas_fonts( apply_filters( 'antreas_font_headings', antreas_get_option( 'type_headings' ) ) );
	antreas_fonts( apply_filters( 'antreas_font_menu', antreas_get_option( 'type_nav' ) ) );
	antreas_fonts( apply_filters( 'antreas_font_body', antreas_get_option( 'type_body' ) ), antreas_get_option( 'type_body_variants' ) );
}


// Registers all widget areas
add_action( 'widgets_init', 'antreas_init_sidebar' );
function antreas_init_sidebar() {

	register_sidebar(
		array(
			'name'          => __( 'Default Widgets', 'antreas' ),
			'id'            => 'primary-widgets',
			'description'   => __( 'Sidebar shown in all standard pages by default.', 'antreas' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title heading">',
			'after_title'   => '</div>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Secondary Widgets', 'antreas' ),
			'id'            => 'secondary-widgets',
			'description'   => __( 'Shown in pages with more than one sidebar.', 'antreas' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<div class="widget-title heading">',
			'after_title'   => '</div>',
		)
	);

	$footer_columns = apply_filters( 'antreas_subfooter_columns', antreas_get_option( 'layout_subfooter_columns' ) );
	if ( $footer_columns == '' ) {
		$footer_columns = 3;
	}
	for ( $count = 1; $count <= $footer_columns; $count++ ) {
		register_sidebar(
			array(
				'id'            => 'footer-widgets-' . $count,
				'name'          => __( 'Footer Widgets', 'antreas' ) . ' ' . $count,
				'description'   => __( 'Shown in the footer area.', 'antreas' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="widget-title heading">',
				'after_title'   => '</div>',
			)
		);
	}
}


//Registers all menu areas
add_action( 'widgets_init', 'antreas_init_menu' );
function antreas_init_menu() {
	register_nav_menus( array( 'top_menu' => __( 'Top Menu', 'antreas' ) ) );
	register_nav_menus( array( 'main_menu' => __( 'Main Menu', 'antreas' ) ) );
	register_nav_menus( array( 'footer_menu' => __( 'Footer Menu', 'antreas' ) ) );
	register_nav_menus( array( 'mobile_menu' => __( 'Mobile Menu', 'antreas' ) ) );
}
