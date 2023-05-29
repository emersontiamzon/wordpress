<?php

//Define customizer sections
if ( ! function_exists( 'antreas_metadata_panels' ) ) {
	function antreas_metadata_panels() {
		$data = array();

		$data['antreas_management'] = array(
			'title'       => __( 'General Theme Options', 'antreas' ),
			'description' => __( 'Options that help you manage your theme better.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'priority'    => 15,
		);

		$data['antreas_layout'] = array(
			'title'       => __( 'Layout', 'antreas' ),
			'description' => __( 'Here you can find settings that control the structure and positioning of specific elements within your website.', 'antreas' ),
			'priority'    => 25,
		);

		$data['antreas_content'] = array(
			'title'       => __( 'Content Areas', 'antreas' ),
			'description' => __( 'This theme includes a few areas where you can insert cutom content.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'priority'    => 26,
		);

		return apply_filters( 'antreas_customizer_panels', $data );
	}
}


//Define customizer sections
if ( ! function_exists( 'antreas_metadata_sections' ) ) {
	function antreas_metadata_sections() {
		$data = array();

		$data['antreas_layout_general'] = array(
			'title'       => __( 'Site Wide Structure', 'antreas' ),
			'description' => __( 'Settings that control the structure and positioning of design elements.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'panel'       => 'antreas_layout',
			'priority'    => 25,
		);

		$data['antreas_layout_home'] = array(
			'title'       => __( 'Homepage', 'antreas' ),
			'description' => __( 'Customize the appearance and behavior of the homepage elements.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'panel'       => 'antreas_layout',
			'priority'    => 50,
		);

		if ( defined( 'CPOTHEME_USE_SLIDES' ) && CPOTHEME_USE_SLIDES == true ) {
			$data['antreas_layout_slider'] = array(
				'title'       => __( 'Slider', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the slider.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_TAGLINE' ) && CPOTHEME_USE_TAGLINE == true ) {
			$data['antreas_layout_tagline'] = array(
				'title'       => __( 'Tagline', 'antreas' ),
				'description' => __( 'Customize the appearance and of the homepage tagline.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_FEATURES' ) && CPOTHEME_USE_FEATURES == true ) {
			$data['antreas_layout_features'] = array(
				'title'       => __( 'Features', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the feature blocks.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_PORTFOLIO' ) && CPOTHEME_USE_PORTFOLIO == true ) {
			$data['antreas_layout_portfolio'] = array(
				'title'       => __( 'Portfolio', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the portfolio.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_PRODUCTS' ) && CPOTHEME_USE_PRODUCTS == true ) {
			$data['antreas_layout_products'] = array(
				'title'       => __( 'Products', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the products.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_SERVICES' ) && CPOTHEME_USE_SERVICES == true ) {
			$data['antreas_layout_services'] = array(
				'title'       => __( 'Services', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the services.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_ABOUT' ) && CPOTHEME_USE_ABOUT == true ) {
			$data['antreas_layout_about'] = array(
				'title'       => __( 'About', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the about us section.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_TEAM' ) && CPOTHEME_USE_TEAM == true ) {
			$data['antreas_layout_team'] = array(
				'title'       => __( 'Team Members', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the team listing.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_TESTIMONIALS' ) && CPOTHEME_USE_TESTIMONIALS == true ) {
			$data['antreas_layout_testimonials'] = array(
				'title'       => __( 'Testimonials', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the testimonials.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_CLIENTS' ) && CPOTHEME_USE_CLIENTS == true ) {
			$data['antreas_layout_clients'] = array(
				'title'       => __( 'Clients', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the client listing.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_CONTACT' ) && CPOTHEME_USE_CONTACT == true ) {
			$data['antreas_layout_contact'] = array(
				'title'       => __( 'Contact', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the contact section.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		if ( defined( 'CPOTHEME_USE_SHORTCODE' ) && CPOTHEME_USE_SHORTCODE == true ) {
			$data['antreas_layout_shortcode'] = array(
				'title'       => __( 'Shortcode', 'antreas' ),
				'description' => __( 'Customize the appearance and behavior of the shortcode section.', 'antreas' ),
				'capability'  => 'edit_theme_options',
				'panel'       => 'antreas_layout',
				'priority'    => 50,
			);
		}

		$data['antreas_layout_posts'] = array(
			'title'       => __( 'Blog Posts', 'antreas' ),
			'description' => __( 'Modify the appearance and behavior of your blog posts by enabling specific elements.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'panel'       => 'antreas_layout',
			'priority'    => 50,
		);

		$data['antreas_typography'] = array(
			'title'       => __( 'Typography', 'antreas' ),
			'description' => __( 'Custom typefaces for the entire site.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'priority'    => 45,
		);

		$data['antreas_content_general'] = array(
			'title'       => __( 'Site Wide Content', 'antreas' ),
			'description' => __( 'Content areas located in common areas throughout the site. You can use HTML and shortcodes here.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'panel'       => 'antreas_content',
			'priority'    => 50,
		);

		$data['antreas_content_home'] = array(
			'title'       => __( 'Homepage', 'antreas' ),
			'description' => __( 'Add custom content to specific areas of the homepage. You can use HTML and shortcodes here.', 'antreas' ),
			'capability'  => 'edit_theme_options',
			'panel'       => 'antreas_content',
			'priority'    => 50,
		);

		return apply_filters( 'antreas_customizer_sections', $data );
	}
}


if ( ! function_exists( 'antreas_metadata_customizer' ) ) {
	function antreas_metadata_customizer( $std = null ) {
		$data = array();

		$data['logo_dimensions'] = array(
			'section'     => 'title_tagline',
			'type'        => 'dimensions',
			'partials'    => '.header .logo-img',
			'sanitize' => 'antreas_sanitize_logo_dimensions',
			'priority'    => 1,
		);

		$data['sidebar_position'] = array(
			'label'       => __( 'Default Sidebar Position', 'antreas' ),
			'description' => __( 'This option can be overridden in individual pages.', 'antreas' ),
			'section'     => 'antreas_layout_general',
			'type'        => 'select',
			'choices'     => antreas_metadata_sidebarposition_text(),
			'default'     => 'right',
		);

		$data['layout_subfooter_columns'] = array(
			'label'   => __( 'Number of Footer Columns', 'antreas' ),
			'section' => 'antreas_layout_general',
			'type'    => 'select',
			'choices' => antreas_metadata_sidebar_columns_text(),
			'default' => 3,
		);

		$data['sticky_header'] = array(
			'label'    => __( 'Sticky Header', 'antreas' ),
			'section'  => 'antreas_layout_general',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['layout_breadcrumbs'] = array(
			'label'    => __( 'Breadcrumbs', 'antreas' ),
			'section'  => 'antreas_layout_general',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['layout_languages'] = array(
			'label'    => __( 'Language Switcher', 'antreas' ),
			'section'  => 'antreas_layout_general',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['layout_cart'] = array(
			'label'    => __( 'Shopping Cart', 'antreas' ),
			'section'  => 'antreas_layout_general',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['general_credit'] = array(
			'label'    => __( 'Footer Credit Link', 'antreas' ),
			'section'  => 'antreas_layout_general',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
			'partials' => '.footer-content .cpo-credit-link',
		);

		$data['enable_go_to_top'] = array(
			'label'    => __( 'Footer Back To Top', 'antreas' ),
			'section'  => 'antreas_layout_general',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
			'partials' => '.footer #back-to-top',
		);

		$data['enable_animations'] = array(
			'label'    => __( 'Homepage Animations', 'antreas' ),
			'section'  => 'antreas_layout_general',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['footer_text'] = array(
			'label'        => __( 'Footer Text', 'antreas' ),
			'description'  => __( 'Add custom text that replaces the copyright line in the footer.', 'antreas' ),
			'section'      => 'antreas_layout_general',
			'multilingual' => true,
			'sanitize'     => 'esc_html',
			'type'         => 'textarea',
			'partials'     => '.footer-content .copyright',
		);

		$data['social_links'] = array(
			'label'        => __( 'Social Links', 'antreas' ),
			'description'  => __( 'Enter the URL of your preferred social profiles, one per line.', 'antreas' ),
			'section'      => 'antreas_layout_general',
			'multilingual' => true,
			'sanitize'     => 'esc_html',
			'type'         => 'textarea',
		);

		//Homepage
		$data['sidebar_position_home'] = array(
			'label'       => __( 'Sidebar Position in Homepage', 'antreas' ),
			'description' => __( 'If you set a static page to serve as the homepage, this option will be overridden by that page\'s settings.', 'antreas' ),
			'section'     => 'antreas_layout_home',
			'type'        => 'select',
			'choices'     => antreas_metadata_sidebarposition_text(),
			'default'     => 'none',
		);

		$data['home_order'] = array(
			'label'       => __( 'Content Ordering', 'antreas' ),
			'description' => __( 'Change the ordering of the various elements in the homepage.', 'antreas' ),
			'section'     => 'antreas_layout_home',
			'type'        => 'sortable',
			'choices'     => antreas_metadata_homepage_order(),
			'default'     => antreas_metadata_homepage_order_default(),
		);

		// Homepage Tagline
		if ( defined( 'CPOTHEME_USE_TAGLINE' ) && CPOTHEME_USE_TAGLINE == true ) {
			$data['home_tagline'] = array(
				'label'        => __( 'Tagline Title', 'antreas' ),
				'section'      => 'antreas_layout_tagline',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#tagline .tagline-title',
			);

			$data['home_tagline_content'] = array(
				'label'        => __( 'Tagline Content', 'antreas' ),
				'section'      => 'antreas_layout_tagline',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'wp_kses_post',
				'type'         => 'textarea',
				'partials'     => '#tagline .tagline-content',
			);

			$data['home_tagline_image'] = array(
				'label'    => __( 'Image', 'antreas' ),
				'section'  => 'antreas_layout_tagline',
				'sanitize' => 'esc_url',
				'type'     => 'image',
			);

			$data['home_tagline_url'] = array(
				'label'        => __( 'Tagline Button URL', 'antreas' ),
				'section'      => 'antreas_layout_tagline',
				'empty'        => true,
				'multilingual' => true,
				'default'      => '',
				'sanitize'     => 'esc_url',
				'type'         => 'text',
			);

			$data['home_tagline_link'] = array(
				'label'        => __( 'Tagline Button Label', 'antreas' ),
				'section'      => 'antreas_layout_tagline',
				'empty'        => true,
				'multilingual' => true,
				'default'      => __( 'Learn More', 'antreas' ),
				'sanitize'     => 'esc_attr',
				'type'         => 'text',
				'partials'     => '#tagline .tagline-link',
			);

			$data['tagline_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_tagline',
				'type'         => 'selectize',
				'choices'      => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['tagline_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_tagline',
				'type'         => 'selectize',
				'choices'      => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Homepage Slider
		if ( defined( 'CPOTHEME_USE_SLIDES' ) && CPOTHEME_USE_SLIDES == true ) {
			$data['slider_height'] = array(
				'label'    => __( 'Slider Height (px)', 'antreas' ),
				'section'  => 'antreas_layout_slider',
				'type'     => 'text',
				'sanitize' => 'absint',
				'default'  => '500',
			);

			$data['slider_speed'] = array(
				'label'    => __( 'Slider Transition Speed (ms)', 'antreas' ),
				'section'  => 'antreas_layout_slider',
				'type'     => 'text',
				'sanitize' => 'absint',
				'default'  => '400',
			);

			$data['slider_timeout'] = array(
				'label'    => __( 'Slider Timeout (ms)', 'antreas' ),
				'section'  => 'antreas_layout_slider',
				'type'     => 'text',
				'sanitize' => 'absint',
				'default'  => '8000',
			);

			$data['slider_effect'] = array(
				'label'   => __( 'Slider Transition Effect', 'antreas' ),
				'section' => 'antreas_layout_slider',
				'type'    => 'select',
				'choices' => antreas_metadata_slider_effect(),
				'default' => 'fade',
			);

			$data['slider_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_slider',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['slider_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_slider',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( '404_page' ),
			);

		}

		//Homepage Features
		if ( defined( 'CPOTHEME_USE_FEATURES' ) && CPOTHEME_USE_FEATURES == true ) {
			$data['home_features'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_features',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#features .section-title',
			);

			$data['home_features_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_features',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#features .section-subtitle',
			);

			$data['features_columns'] = array(
				'label'   => __( 'Features Columns', 'antreas' ),
				'section' => 'antreas_layout_features',
				'type'    => 'select',
				'choices' => antreas_metadata_columns(),
				'default' => 4,
			);

 			$data['features_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_features',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['features_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_features',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Portfolio layout
		if ( defined( 'CPOTHEME_USE_PORTFOLIO' ) && CPOTHEME_USE_PORTFOLIO == true ) {
			$data['home_portfolio'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_portfolio',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#portfolio .section-title',
			);

			$data['home_portfolio_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_portfolio',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#portfolio .section-subtitle',
			);

			$data['portfolio_columns'] = array(
				'label'   => __( 'Portfolio Columns', 'antreas' ),
				'section' => 'antreas_layout_portfolio',
				'type'    => 'select',
				'choices' => antreas_metadata_columns(),
				'default' => 5,
			);

			$data['portfolio_related'] = array(
				'label'       => __( 'Enable Related Portfolio Items', 'antreas' ),
				'description' => __( 'Shows portfolio items belonging to the same category at the end of each portfolio item.', 'antreas' ),
				'section'     => 'antreas_layout_portfolio',
				'type'        => 'checkbox',
				'sanitize'    => 'antreas_sanitize_bool',
				'default'     => true,
			);

			$data['portfolio_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_portfolio',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['portfolio_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_portfolio',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Products layout
		if ( defined( 'CPOTHEME_USE_PRODUCTS' ) && CPOTHEME_USE_PRODUCTS == true ) {
			$data['home_products'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_products',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#products .section-title',
			);

			$data['home_products_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_products',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#products .section-subtitle',
			);

			$data['products_columns'] = array(
				'label'   => __( 'Product Columns', 'antreas' ),
				'section' => 'antreas_layout_products',
				'type'    => 'select',
				'choices' => antreas_metadata_columns(),
				'default' => 4,
			);

 			$data['products_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_products',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['products_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_products',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Services layout
		if ( defined( 'CPOTHEME_USE_SERVICES' ) && CPOTHEME_USE_SERVICES == true ) {
			$data['home_services'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_services',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#services .section-title',
			);

			$data['home_services_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_services',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#services .section-subtitle',
			);

			$data['services_columns'] = array(
				'label'   => __( 'Services Columns', 'antreas' ),
				'section' => 'antreas_layout_services',
				'type'    => 'select',
				'choices' => antreas_metadata_columns(),
				'default' => 3,
			);

			$data['services_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_services',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['services_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_services',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//About section
		if ( defined( 'CPOTHEME_USE_ABOUT' ) && CPOTHEME_USE_ABOUT == true ) {
			$data['home_about'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_about',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#about .section-title',
			);

			$data['home_about_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_about',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#about .section-subtitle',
			);

			$data['about_pages'] = array(
				'label'        => __( 'About Pages', 'antreas' ),
				'description'  => __( 'Select the pages that will be displayed as columns', 'antreas' ),
				'section'      => 'antreas_layout_about',
				'type'         => 'selectize',
				'choices' => 'pages',
				'input_attrs' => array(
					'maxItems' => 4
				),
				'default'      => array(),
				'partials'     => '#about .about__content',
			);

			$data['about_show']   = array(
				'label'        => __( 'Show on', 'antreas' ),
				'description'  => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'      => 'antreas_layout_about',
				'type'         => 'selectize',
				'choices'      => 'all',
				'default'      => array( 'homepage' ),
			);

	 		$data['about_exclude'] = array(
				'label'        => __( 'Exclude from', 'antreas' ),
				'description'  => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'      => 'antreas_layout_about',
				'type'         => 'selectize',
				'choices'      => 'all',
				'default'      => array( '404_page' ),
			);
		}

		//Team layout
		if ( defined( 'CPOTHEME_USE_TEAM' ) && CPOTHEME_USE_TEAM == true ) {
			$data['home_team'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_team',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#team .section-title',
			);

			$data['home_team_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_team',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#team .section-subtitle',
			);

			$data['team_columns'] = array(
				'label'   => __( 'Team Columns', 'antreas' ),
				'section' => 'antreas_layout_team',
				'type'    => 'select',
				'choices' => antreas_metadata_columns(),
				'default' => 3,
			);

 			$data['team_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_team',
				'type'        => 'selectize',
				'choices'      => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['team_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_team',
				'type'        => 'selectize',
				'choices'      => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Testimonials
		if ( defined( 'CPOTHEME_USE_TESTIMONIALS' ) && CPOTHEME_USE_TESTIMONIALS == true ) {
			$data['home_testimonials'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_testimonials',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#testimonials .section-title',
			);

			$data['home_testimonials_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_testimonials',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#testimonials .section-subtitle',
			);

			$data['testimonials_columns'] = array(
				'label'   => __( 'Testimonials Columns', 'antreas' ),
				'section' => 'antreas_layout_testimonials',
				'type'    => 'select',
				'choices' => antreas_metadata_columns(),
				'default' => 3,
			);

			$data['testimonials_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_testimonials',
				'type'        => 'selectize',
				'choices'      => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['testimonials_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_testimonials',
				'type'        => 'selectize',
				'choices'      => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Clients
		if ( defined( 'CPOTHEME_USE_CLIENTS' ) && CPOTHEME_USE_CLIENTS == true ) {
			$data['home_clients'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_clients',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#clients .section-title',
			);

			$data['home_clients_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_clients',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#clients .section-subtitle',
			);

			$data['clients_columns'] = array(
				'label'   => __( 'Clients Columns', 'antreas' ),
				'section' => 'antreas_layout_clients',
				'type'    => 'select',
				'choices' => antreas_metadata_columns(),
				'default' => 5,
			);

	 		$data['clients_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_clients',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['clients_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_clients',
				'type'         => 'selectize',
				'choices'      => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Contact
		if ( defined( 'CPOTHEME_USE_CONTACT' ) && CPOTHEME_USE_CONTACT == true ) {
			$data['home_contact'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_contact',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#contact .section-title',
			);

			$data['home_contact_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_contact',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#contact .section-subtitle',
			);

			$data['home_contact_custom_control']   = array(
				'section'  => 'antreas_layout_contact',
				'type'     => 'contactform',
			);

			$data['home_contact_content'] = array(
				'label'        => __( 'Contact content', 'antreas' ),
				'description'  => __( 'Add content for this contact section.', 'antreas' ),
				'section'      => 'antreas_layout_contact',
				'multilingual' => true,
				'sanitize'     => 'wp_kses_post',
				'type'         => 'tinymce',
				'partials'     => '#contact .contact__content',
			);

			$data['contact_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_contact',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( 'homepage' ),
			);

			$data['contact_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_contact',
				'type'        => 'selectize',
				'choices'     => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Shortcode
		if ( defined( 'CPOTHEME_USE_SHORTCODE' ) && CPOTHEME_USE_SHORTCODE == true ) {
			$data['home_shortcode'] = array(
				'label'        => __( 'Section Title', 'antreas' ),
				'section'      => 'antreas_layout_shortcode',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#section .section-title',
			);

			$data['home_shortcode_subtitle'] = array(
				'label'        => __( 'Section Subtitle', 'antreas' ),
				'section'      => 'antreas_layout_shortcode',
				'empty'        => true,
				'multilingual' => true,
				'sanitize'     => 'esc_html',
				'type'         => 'text',
				'partials'     => '#section .section-subtitle',
			);

			$data['home_shortcode_content'] = array(
				'label'        => __( 'Section content', 'antreas' ),
				'description'  => __( 'Add content for this section. You can even use shortcodes.', 'antreas' ),
				'section'      => 'antreas_layout_shortcode',
				'multilingual' => true,
				'sanitize'     => 'wp_kses_post',
				'type'         => 'tinymce',
			);

			$data['shortcode_show']   = array(
				'label'       => __( 'Show on', 'antreas' ),
				'description' => __( 'show this section only on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_shortcode',
				'type'         => 'selectize',
				'choices'      => 'all',
				'default'     => array(),
			);

			$data['shortcode_exclude'] = array(
				'label'       => __( 'Exclude from', 'antreas' ),
				'description' => __( 'this section will not be displayed on these pages. You can even type in post ids (e.g. #123)', 'antreas' ),
				'section'     => 'antreas_layout_shortcode',
				'type'         => 'selectize',
				'choices'      => 'all',
				'default'     => array( '404_page' ),
			);
		}

		//Blog Posts
		$data['home_posts'] = array(
			'label'    => __( 'Enable Posts On Homepage', 'antreas' ),
			'section'  => 'antreas_layout_home',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['home_blog'] = array(
			'label'        => __( 'Section Title', 'antreas' ),
			'section'      => 'antreas_layout_posts',
			'empty'        => true,
			'multilingual' => true,
			'sanitize'     => 'esc_html',
			'type'         => 'text',
			'partials'     => '#main .section-title',
		);

		$data['home_blog_subtitle'] = array(
			'label'        => __( 'Section Subtitle', 'antreas' ),
			'section'      => 'antreas_layout_posts',
			'empty'        => true,
			'multilingual' => true,
			'sanitize'     => 'esc_html',
			'type'         => 'text',
			'partials'     => '#main .section-subtitle',
		);

		$data['blog_columns'] = array(
			'label'   => __( 'Posts Columns', 'antreas' ),
			'section' => 'antreas_layout_posts',
			'type'    => 'select',
			'choices' => antreas_metadata_columns(),
			'default' => 3,
		);

		$data['postpage_preview'] = array(
			'label'   => __( 'Content In Post Listings', 'antreas' ),
			'section' => 'antreas_layout_posts',
			'type'    => 'select',
			'choices' => antreas_metadata_post_preview(),
			'default' => 'excerpt',
		);

		$data['postpage_dates'] = array(
			'label'    => __( 'Enable Post Dates', 'antreas' ),
			'section'  => 'antreas_layout_posts',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['postpage_authors'] = array(
			'label'    => __( 'Enable Post Authors', 'antreas' ),
			'section'  => 'antreas_layout_posts',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['postpage_comments'] = array(
			'label'    => __( 'Enable Comment Count', 'antreas' ),
			'section'  => 'antreas_layout_posts',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['postpage_categories'] = array(
			'label'    => __( 'Enable Post Categories', 'antreas' ),
			'section'  => 'antreas_layout_posts',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		$data['postpage_tags'] = array(
			'label'    => __( 'Enable Post Tags', 'antreas' ),
			'section'  => 'antreas_layout_posts',
			'type'     => 'checkbox',
			'sanitize' => 'antreas_sanitize_bool',
			'default'  => true,
		);

		//Typography
		$data['type_size'] = array(
			'label'   => __( 'Font Size', 'antreas' ),
			'section' => 'antreas_typography',
			'type'    => 'select',
			'choices' => antreas_metadata_font_sizes(),
			'default' => '1.05',
		);

		$data['type_headings'] = array(
			'label'   => __( 'Headings & Titles', 'antreas' ),
			'section' => 'antreas_typography',
			'type'    => 'select',
			'choices' => antreas_metadata_fonts(),
			'default' => '',
		);

		$data['type_nav'] = array(
			'label'   => __( 'Main Navigation Menu', 'antreas' ),
			'section' => 'antreas_typography',
			'type'    => 'select',
			'choices' => antreas_metadata_fonts(),
			'default' => '',
		);

		$data['type_body'] = array(
			'label'   => __( 'Body Text', 'antreas' ),
			'section' => 'antreas_typography',
			'type'    => 'select',
			'choices' => antreas_metadata_fonts(),
			'default' => '',
		);

		$data['type_body_variants'] = array(
			'label'       => __( 'Load Font Variants', 'antreas' ),
			'description' => __( 'Loads additional font variations for the selected body typeface, if available. This will result in better-looking bold/light text.', 'antreas' ),
			'section'     => 'antreas_typography',
			'type'        => 'checkbox',
			'sanitize'    => 'antreas_sanitize_bool',
			'default'     => true,
		);

		//Colors
		$data['primary_color'] = array(
			'label'       => __( 'Primary Color', 'antreas' ),
			'description' => __( 'Used in buttons, headings, and other prominent elements.', 'antreas' ),
			'section'     => 'colors',
			'type'        => 'color',
			'sanitize'    => 'sanitize_hex_color',
			'default'     => '#22c0e3',
		);

		$data['secondary_color'] = array(
			'label'       => __( 'Secondary Color', 'antreas' ),
			'description' => __( 'Used in minor design elements and backgrounds.', 'antreas' ),
			'section'     => 'colors',
			'type'        => 'color',
			'sanitize'    => 'sanitize_hex_color',
			'default'     => '#424247',
		);

		$data['type_headings_color'] = array(
			'label'       => __( 'Headings & Titles', 'antreas' ),
			'description' => '',
			'section'     => 'colors',
			'type'        => 'color',
			'sanitize'    => 'sanitize_hex_color',
			'default'     => '#222222',
		);

		$data['type_widgets_color'] = array(
			'label'       => __( 'Widget Titles', 'antreas' ),
			'description' => '',
			'section'     => 'colors',
			'type'        => 'color',
			'sanitize'    => 'sanitize_hex_color',
			'default'     => '#222222',
		);

		$data['type_nav_color'] = array(
			'label'    => __( 'Main Menu', 'antreas' ),
			'section'  => 'colors',
			'type'     => 'color',
			'sanitize' => 'sanitize_hex_color',
			'default'  => '#676767',
		);

		$data['type_body_color'] = array(
			'label'    => __( 'Body Text', 'antreas' ),
			'section'  => 'colors',
			'type'     => 'color',
			'sanitize' => 'sanitize_hex_color',
			'default'  => '#919191',
		);

		$data['type_link_color'] = array(
			'label'    => __( 'Hyperlinks', 'antreas' ),
			'section'  => 'colors',
			'type'     => 'color',
			'sanitize' => 'sanitize_hex_color',
			'default'  => '#22c0e3',
		);

		$data['subfooter_bg_color'] = array(
			'label'    => __( 'Subfooter background color', 'antreas' ),
			'section'  => 'colors',
			'type'     => 'color',
			'sanitize' => 'sanitize_hex_color',
			'default'  => '#f3f3f3',
		);

		$data['footer_bg_color'] = array(
			'label'    => __( 'Footer background color', 'antreas' ),
			'section'  => 'colors',
			'type'     => 'color',
			'sanitize' => 'sanitize_hex_color',
			'default'  => '#333333',
		);

		return apply_filters( 'antreas_customizer_controls', $data );
	}
}
