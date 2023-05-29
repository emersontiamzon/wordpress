<?php

class Regina_Customizer {
	public function __construct() {

		require_once 'customizer-active-callbacks.php';
		add_action( 'customize_register', array( $this, 'regine_customize_manager' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'regina_customizer_js_load' ) );
		add_action( 'customize_preview_init', array( $this, 'regina_customize_preview_style' ) );
		add_action( 'wp_ajax_add_section', array( $this, 'insert_section' ) );
		add_action( 'wp_ajax_order_section', array( $this, 'order_section' ) );
	}

	/**
	 * Customizer manager demo
	 * @param  WP_Customizer_Manager $wp_customize
	 * @return void
	 */
	public function regine_customize_manager( $wp_customize ) {

		require_once get_template_directory() . '/includes/custom-controls/class-regina-custom-panel.php';
		require_once get_template_directory() . '/includes/custom-controls/class-epsilon-control-tab.php';
		require_once get_template_directory() . '/includes/custom-controls/class-wp-customize-new-section.php';
		require_once get_template_directory() . '/includes/custom-controls/class-regina-section.php';
		require_once get_template_directory() . '/includes/custom-controls/class-wp-customize-new-section-control.php';
		require_once get_template_directory() . '/includes/custom-controls/class-regina-text-custom-control.php';
		require_once get_template_directory() . '/includes/custom-controls/class-regina-pro-install-plugin.php';

		$wp_customize->register_control_type( 'Epsilon_Control_Tab' );
		$wp_customize->register_control_type( 'WP_Customize_New_Section_Control' );

		$wp_customize->add_panel(
			new Regina_Custom_Panel(
				$wp_customize, 'regina_theme_options_panel', array(
					'title'    => esc_html__( 'Theme Options', 'regina' ),
					'priority' => 1,
				)
			)
		);

		$wp_customize->add_panel(
			'regina_frontpage_panel', array(
				'title'       => esc_html__( 'Front Page Sections', 'regina' ),
				'description' => esc_html__( 'Drag & drop to reorder front-page sections', 'regina' ),
				'priority'    => 1,
			)
		);

		$title_tagline = $wp_customize->get_section( 'title_tagline' );
		if ( $title_tagline ) {
			$title_tagline->panel    = 'regina_theme_options_panel';
			$title_tagline->priority = 10;
		}

		$static_page = $wp_customize->get_section( 'static_front_page' );
		if ( $static_page ) {
			$static_page->panel    = 'regina_theme_options_panel';
			$static_page->priority = 11;
		}

		$background_image = $wp_customize->get_section( 'background_image' );
		if ( $background_image ) {
			$background_image->panel    = 'regina_theme_options_panel';
			$background_image->priority = 10;
		}

		$this->regina_header( $wp_customize );
		$this->regina_homepage( $wp_customize );
		$this->regina_blog( $wp_customize );
		$this->regina_contact( $wp_customize );
		$this->regina_typography( $wp_customize );
		$this->regina_booking( $wp_customize );
		$this->regina_sidebars( $wp_customize );
		$this->regina_footer( $wp_customize );
		$this->regina_preloader( $wp_customize );
		$this->regina_advanced_options( $wp_customize );
		$this->add_section_cotnent( $wp_customize );
	}

	public function regina_customizer_js_load() {
		wp_enqueue_style( 'regina-customizer', get_template_directory_uri() . '/assets/css/customizer.css' );
		wp_enqueue_script( 'regina-scripts', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-controls', 'updates' ), '1.0', true );

		$customizer_urls = array(
			'postsURL'  => get_post_type_archive_link( 'post' ),
			'siteURL'   => site_url(),
			'searchURL' => site_url() . '?s=',
		);

		$last_post = get_posts(
			array(
				'posts_per_page' => 1,
			)
		);

		if ( ! empty( $last_post ) ) {
			$customizer_urls['postURL'] = get_permalink( $last_post[0]->ID );
		}

		$categories = get_categories(
			array(
				'number' => 1,
				'fields' => 'ids',
			)
		);

		if ( ! empty( $categories ) ) {
			$customizer_urls['categoryURL'] = get_category_link( $categories[0] );
		}

		$tags = get_tags(
			array(
				'number' => 1,
				'fields' => 'ids',
			)
		);

		if ( ! empty( $tags ) ) {
			$customizer_urls['tagURL'] = get_tag_link( $tags[0] );
		}

		$authors = new WP_User_Query(
			array(
				'has_published_posts' => array( 'post' ),
				'number'              => 1,
			)
		);

		if ( ! empty( $authors->results ) ) {
			$customizer_urls['authorURL'] = get_author_posts_url( $authors->results[0]->ID );
		}

		$post_type_object = get_post_type_object( 'section' );
		if ( $post_type_object->_edit_link ) {
			$link                                      = admin_url( $post_type_object->_edit_link . '&amp;action=edit' );
			$customizer_urls['editSectionDescription'] = esc_html__( 'In order to edit this section content and settings please go ', 'regina' ) . '<a href="' . $link . '" target="_blank">' . __( 'here', 'regina' ) . '</a>';
		}

		$customizer_urls['nonce'] = wp_create_nonce( 'regina-customizer-ajax' );

		wp_localize_script( 'regina-scripts', 'ReginaCustomizer', $customizer_urls );

	}

	public function regina_customize_preview_style() {
		wp_enqueue_style( 'regina-customizer-preview', get_template_directory_uri() . '/assets/css/customizer-preview.css' );

		wp_enqueue_script( 'regina-customizer-preview', get_template_directory_uri() . '/assets/js/customizer-previewer.js', array( 'customize-preview' ), '1.0', true );
	}

	public function regina_header( $wp_customize ) {

		// Header Panel
		$wp_customize->add_section(
			'regina_header_options', array(
				'title'    => esc_html__( 'Header Settings', 'regina' ),
				'panel'    => 'regina_theme_options_panel',
				'priority' => 1,
			)
		);

		// Header Options
		$custom_logo = $wp_customize->get_control( 'custom_logo' );
		if ( ! $custom_logo ) {
			$wp_customize->add_setting(
				'regina_logo', array(
					'sanitize_callback' => 'esc_html',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize, 'regina_logo', array(
						'label'   => esc_html__( 'Logo Image', 'regina' ),
						'section' => 'regina_header_options',
					)
				)
			);
		}

		$wp_customize->add_setting(
			'regina_header_type', array(
				'default'           => 'v1',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_header_type', array(
				'label'   => esc_html__( 'Choose Header Type', 'regina' ),
				'section' => 'regina_header_options',
				'type'    => 'radio',
				'choices' => array(
					'v1' => 'Header V1',
					'v2' => 'Header V2',
					'v3' => 'Header V3',
				),
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_header_type', array(
				'selector' => '#header',
			)
		);
		$wp_customize->add_setting(
			'regina_header_book_appointment', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_header_book_appointment', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable "Book Appointment" in nav.?', 'regina' ),
					'description' => esc_html__( 'Add a "Book Appointment" URL in your main navigation menu.', 'regina' ),
					'section'     => 'regina_header_options',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_book_appointment_text', array(
				'default'           => esc_html__( 'Book Appointment', 'regina' ),
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_book_appointment_text', array(
				'label'   => esc_html__( '"Book Appointment" text in nav.', 'regina' ),
				'section' => 'regina_header_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_book_appointment_text', array(
				'selector' => '#header .main-menu .appointment-link',
			)
		);

		// Top header
		$wp_customize->add_setting(
			'regina_top_header', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable Top Header ?', 'regina' ),
					'description' => esc_html__( 'Show/Hide the top header area', 'regina' ),
					'section'     => 'regina_header_options',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_email', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_email', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Email ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide the email in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_telephone', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_telephone', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Telephone Number ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide the telephone number in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_telephone', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_setting(
			'regina_header_search', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_header_search', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Search ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide the search box in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_facebook', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_facebook', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Facebook ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide Facebook icon in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_instagram', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_instagram', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Instagram ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide Instagram icon in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_twitter', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_twitter', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Twitter ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide Twitter icon in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_linkedin', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_linkedin', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header LinkedIN ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide LinkedIN icon in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_youtube', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_youtube', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Youtube ?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide Youtube icon in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);
		$wp_customize->add_setting(
			'regina_top_header_enable_yelp', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_yelp', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Yelp?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide Yelp icon link in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_top_header_enable_gplus', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_top_header_enable_gplus', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Top Header Google Plus?', 'regina' ),
					'description'     => esc_html__( 'Show/Hide Google Plus icon link in the top header area', 'regina' ),
					'section'         => 'regina_header_options',
					'active_callback' => 'regina_check_top_header',
				)
			)
		);

	}

	public function regina_homepage( $wp_customize ) {

		$wp_customize->add_section(
			'regina_homepage_options', array(
				'title'    => esc_html__( 'Hero Section', 'regina' ),
				'panel'    => 'regina_frontpage_panel',
				'priority' => 0,
			)
		);

		$wp_customize->add_setting(
			'regina_homepage_type', array(
				'default'           => 'slider',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'regina_homepage_type', array(
				'label'   => esc_html__( 'Select your background type', 'regina' ),
				'section' => 'regina_homepage_options',
				'type'    => 'radio',
				'choices' => array(
					'image'  => 'Static Image',
					'slider' => 'Slider as background',
				),
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_homepage_type', array(
				'selector' => '#home-slider',
			)
		);

		$wp_customize->add_setting(
			'regina_homepage_static_image', array(
				'sanitize_callback' => 'esc_url',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'regina_homepage_static_image', array(
					'label'           => esc_html__( 'Static Image', 'regina' ),
					'section'         => 'regina_homepage_options',
					'active_callback' => 'regina_check_hero_image',
				)
			)
		);

		$wp_customize->add_setting(
			new Epsilon_Setting_Repeater(
				$wp_customize, 'regina_homepage_slider', array(
					'transport' => 'postMessage',
					'default'   => array(
						array(
							'slider_image' => get_template_directory_uri() . '/assets/images/home/slide-1.jpg',
							'slider_url'   => '#',
						),
						array(
							'slider_image' => get_template_directory_uri() . '/assets/images/home/slide-1.jpg',
							'slider_url'   => '#',
						),
					),
				)
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Repeater(
				$wp_customize, 'regina_homepage_slider', array(
					'section'         => 'regina_homepage_options',
					'button_label'    => esc_html__( 'Add Slides', 'regina' ),
					'active_callback' => 'regina_check_hero_slider',
					'row_label'       => array(
						'type'  => 'text',
						'value' => esc_html__( 'Slide', 'epsilon-framework' ),
						'field' => false,
					),
					'fields'          => array(
						'slider_image' => array(
							'label'   => esc_html__( 'Select Slide Image', 'regina' ),
							'type'    => 'epsilon-image',
							'default' => get_template_directory_uri() . '/assets/images/home/slide-1.jpg',
						),
						'slider_url'   => array(
							'label'   => esc_html__( 'Slide URL', 'regina' ),
							'type'    => 'url',
							'default' => '#',
						),
					),
				)
			)
		);

		$wp_customize->add_setting(
			'regina_slider_autoplay', array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_slider_autoplay', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Enable Slider autoplay ?', 'regina' ),
					'description'     => esc_html__( 'Your slides will automatically rotate if you enable this option', 'regina' ),
					'section'         => 'regina_homepage_options',
					'active_callback' => 'regina_check_hero_slider',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_slider_spped', array(
				'default'           => 500,
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_slider_spped', array(
				'label'           => esc_html__( 'Slider Speed', 'regina' ),
				'description'     => __( 'Slide transition duration (in ms)', 'regina' ),
				'section'         => 'regina_homepage_options',
				'type'            => 'text',
				'active_callback' => 'regina_check_hero_slider_autoplay',
			)
		);

		$wp_customize->add_setting(
			'regina_slider_auto_pause', array(
				'default'           => 4000,
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_slider_auto_pause', array(
				'label'           => esc_html__( 'Slider Auto Pause', 'regina' ),
				'description'     => __( 'The amount of time (in ms) between each auto transition', 'regina' ),
				'section'         => 'regina_homepage_options',
				'type'            => 'text',
				'active_callback' => 'regina_check_hero_slider_autoplay',
			)
		);

		$wp_customize->add_setting(
			'regina_homepage_section_title', array(
				'default'           => esc_html__( 'We help people, like you.', 'regina' ),
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_homepage_section_title', array(
				'label'   => esc_html__( 'Homepage Section Title', 'regina' ),
				'section' => 'regina_homepage_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_homepage_section_title', array(
				'selector' => '#call-out h1',
			)
		);

		$wp_customize->add_setting(
			'regina_homepage_section_description', array(
				'sanitize_callback' => 'wp_kses_post',
				'default'           => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'regina' ),
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Text_Editor(
				$wp_customize, 'regina_homepage_section_description', array(
					'label'   => esc_html__( 'Homepage Section Description', 'regina' ),
					'section' => 'regina_homepage_options',
				)
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_homepage_section_description', array(
				'selector' => '#call-out > div',
			)
		);

		$wp_customize->add_setting(
			'regina_homepage_section_button_text', array(
				'default'           => esc_html__( 'Book Appointment', 'regina' ),
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_homepage_section_button_text', array(
				'label'   => esc_html__( 'Homepage Section Button Text', 'regina' ),
				'section' => 'regina_homepage_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_homepage_section_button_text', array(
				'selector' => '#call-out > .button',
			)
		);

		$wp_customize->add_setting(
			'regina_homepage_section_button_url', array(
				'default'           => '',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_homepage_section_button_url', array(
				'label'       => esc_html__( 'Homepage Section Button URL', 'regina' ),
				'description' => esc_html__( 'Leave it blank in order to show the appointment popup', 'regina' ),
				'section'     => 'regina_homepage_options',
				'type'        => 'text',
			)
		);

	}

	public function regina_blog( $wp_customize ) {

		$wp_customize->add_panel(
			new Regina_Custom_Panel(
				$wp_customize, 'regina_blog_panel', array(
					'priority'    => 15,
					'title'       => esc_html__( 'Blog settings', 'regina' ),
					'description' => esc_html__( 'Here you will control blog options', 'regina' ),
				)
			)
		);

		$wp_customize->add_section(
			'regina_blog_category_options', array(
				'title'    => esc_html__( 'Blog: Categories Options', 'regina' ),
				'panel'    => 'regina_blog_panel',
				'priority' => 1,
			)
		);

		$wp_customize->add_section(
			'regina_blog_tag_options', array(
				'title'    => esc_html__( 'Blog: Tags Options', 'regina' ),
				'panel'    => 'regina_blog_panel',
				'priority' => 2,
			)
		);

		$wp_customize->add_section(
			'regina_blog_author_options', array(
				'title'    => esc_html__( 'Blog: Author Options', 'regina' ),
				'panel'    => 'regina_blog_panel',
				'priority' => 2,
			)
		);

		$wp_customize->add_section(
			'regina_blog_archive_options', array(
				'title'    => esc_html__( 'Blog: Archives Options', 'regina' ),
				'panel'    => 'regina_blog_panel',
				'priority' => 2,
			)
		);

		$wp_customize->add_section(
			'regina_blog_search_options', array(
				'title'    => esc_html__( 'Blog: Search Options', 'regina' ),
				'panel'    => 'regina_blog_panel',
				'priority' => 2,
			)
		);

		$wp_customize->add_section(
			'regina_single_post_section', array(
				'title'    => esc_html__( 'Blog: Single Options', 'regina' ),
				'panel'    => 'regina_blog_panel',
				'priority' => 2,
			)
		);

		// Blog: Categories Options
		$wp_customize->add_setting(
			'regina_blog_category_header', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_category_header', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable header on categories page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the category page\'s title', 'regina' ),
					'section'     => 'regina_blog_category_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_category_header_image', array(
				'sanitize_callback' => 'esc_url',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'regina_blog_category_header_image', array(
					'label'           => esc_html__( 'Category Header Image', 'regina' ),
					'section'         => 'regina_blog_category_options',
					'active_callback' => 'regina_check_categories_header',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_category_breadcrumbs', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_category_breadcrumbs', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable breadcrumbs on categories page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the category page\'s breadcrumbs', 'regina' ),
					'section'     => 'regina_blog_category_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_category_sidebar', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_category_sidebar', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable sidebar on categories page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the category page\'s sidebar', 'regina' ),
					'section'     => 'regina_blog_category_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_category_sidebar_position', array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'regina_blog_category_sidebar_position', array(
				'label'           => esc_html__( 'Category Sidebar Position', 'regina' ),
				'section'         => 'regina_blog_category_options',
				'type'            => 'select',
				'choices'         => array(
					'left-sidebar'  => 'Left Sidebar',
					'right-sidebar' => 'Right Sidebar',
				),
				'active_callback' => 'regina_check_categories_sidebar',
			)
		);

		// Blog: Tags Options
		$wp_customize->add_setting(
			'regina_blog_tag_header', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_tag_header', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable header on tags page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the tags page\'s title', 'regina' ),
					'section'     => 'regina_blog_tag_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_tag_header_image', array(
				'sanitize_callback' => 'esc_url',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'regina_blog_tag_header_image', array(
					'label'           => esc_html__( 'Tag Header Image', 'regina' ),
					'section'         => 'regina_blog_tag_options',
					'active_callback' => 'regina_check_tags_header',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_tag_breadcrumbs', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_tag_breadcrumbs', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable breadcrumbs on tags page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the tags page\'s breadcrumbs', 'regina' ),
					'section'     => 'regina_blog_tag_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_tag_sidebar', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_tag_sidebar', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable sidebar on tags page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the tags page\'s sidebar', 'regina' ),
					'section'     => 'regina_blog_tag_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_tag_sidebar_position', array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'regina_blog_tag_sidebar_position', array(
				'label'           => esc_html__( 'Tag Sidebar Position', 'regina' ),
				'section'         => 'regina_blog_tag_options',
				'type'            => 'select',
				'choices'         => array(
					'left-sidebar'  => 'Left Sidebar',
					'right-sidebar' => 'Right Sidebar',
				),
				'active_callback' => 'regina_check_tags_sidebar',
			)
		);

		// Blog: Author Options

		$wp_customize->add_setting(
			'regina_blog_author_header', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_author_header', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable header on author page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the author page\'s title', 'regina' ),
					'section'     => 'regina_blog_author_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_author_header_image', array(
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'regina_blog_author_header_image', array(
					'label'       => esc_html__( 'Author Header Image', 'regina' ),
					'description' => esc_html__( 'Show/hide the breadcrumbs area in the author page', 'regina' ),
					'section'     => 'regina_blog_author_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_author_breadcrumbs', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_author_breadcrumbs', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable breadcrumbs on author page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the author page\'s breadcrumbs', 'regina' ),
					'section'     => 'regina_blog_author_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_author_sidebar', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_author_sidebar', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable sidebar on author page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the sidebar area in the author page', 'regina' ),
					'section'     => 'regina_blog_author_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_author_sidebar_position', array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_blog_author_sidebar_position', array(
				'label'   => esc_html__( 'Author Sidebar Position', 'regina' ),
				'section' => 'regina_blog_author_options',
				'type'    => 'select',
				'choices' => array(
					'left-sidebar'  => 'Left Sidebar',
					'right-sidebar' => 'Right Sidebar',
				),
			)
		);

		// Blog: Archives Options
		$wp_customize->add_setting(
			'regina_blog_archive_header', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_archive_header', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable header on archives ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the author page\'s sidebar', 'regina' ),
					'section'     => 'regina_blog_archive_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_archive_header_image', array(
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'regina_blog_archive_header_image', array(
					'label'   => esc_html__( 'Archive Header Image', 'regina' ),
					'section' => 'regina_blog_archive_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_archive_header_description', array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Text_Editor(
				$wp_customize, 'regina_blog_archive_header_description', array(
					'label'   => esc_html__( 'Archive header description', 'regina' ),
					'section' => 'regina_blog_archive_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_archive_breadcrumbs', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_archive_breadcrumbs', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable breadcrumbs on archives ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the archive page\'s breadcrumbs', 'regina' ),
					'section'     => 'regina_blog_archive_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_archive_sidebar', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_archive_sidebar', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable sidebar on archives ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the archive page\'s sidebar', 'regina' ),
					'section'     => 'regina_blog_archive_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_archive_sidebar_position', array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_blog_archive_sidebar_position', array(
				'label'   => esc_html__( 'Archive Sidebar Position', 'regina' ),
				'section' => 'regina_blog_archive_options',
				'type'    => 'select',
				'choices' => array(
					'left-sidebar'  => 'Left Sidebar',
					'right-sidebar' => 'Right Sidebar',
				),
			)
		);

		// Blog: Search Options
		$wp_customize->add_setting(
			'regina_blog_search_header', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_search_header', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable header on search page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the search page\'s title', 'regina' ),
					'section'     => 'regina_blog_search_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_search_header_image', array(
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'regina_blog_search_header_image', array(
					'label'   => esc_html__( 'Search Header Image', 'regina' ),
					'section' => 'regina_blog_search_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_search_header_description', array(
				'default'           => '',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Text_Editor(
				$wp_customize, 'regina_blog_search_header_description', array(
					'label'   => esc_html__( 'Search header description', 'regina' ),
					'section' => 'regina_blog_search_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_search_breadcrumbs', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_search_breadcrumbs', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable breadcrumbs on search page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the search page\'s breadcrumbs', 'regina' ),
					'section'     => 'regina_blog_search_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_search_sidebar', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_search_sidebar', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Enable sidebar on search page ?', 'regina' ),
					'description' => esc_html__( 'Show/hide the search page\'s sidebar', 'regina' ),
					'section'     => 'regina_blog_search_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_blog_search_sidebar_position', array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_blog_search_sidebar_position', array(
				'label'   => esc_html__( 'Search Sidebar Position', 'regina' ),
				'section' => 'regina_blog_search_options',
				'type'    => 'select',
				'choices' => array(
					'left-sidebar'  => 'Left Sidebar',
					'right-sidebar' => 'Right Sidebar',
				),
			)
		);

		// Single Post Settings

		/* Post Header */
		$wp_customize->add_setting(
			'regina_enable_post_header',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_post_header', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Show Page Header', 'regina' ),
					'description' => esc_html__( 'Here you can add a title, description and image background for each post', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);

		/* Post Featured Image */
		$wp_customize->add_setting(
			'regina_enable_post_featured_image',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_post_featured_image', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Show Featured Image', 'regina' ),
					'description' => esc_html__( 'Show/hide featured image from single post', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);

		/* Posted on on single blog posts */
		$wp_customize->add_setting(
			'regina_enable_post_posted_on_blog_posts',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_post_posted_on_blog_posts', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Posted on meta on single blog post', 'regina' ),
					'description' => esc_html__( 'This will disable the posted on zone as well as the author name', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'regina_enable_post_posted_on_blog_posts', array(
				'selector' => '.single-post .entry-meta',
			)
		);

		/* Post Navigation */
		$wp_customize->add_setting(
			'regina_enable_post_navigation',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_post_navigation', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Show Post Navigation', 'regina' ),
					'description' => esc_html__( 'Show/hide next,previous post navigation', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);

		/* Post Social Box */
		$wp_customize->add_setting(
			'regina_enable_post_social_box',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_post_social_box', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Show Post Social Box', 'regina' ),
					'description' => esc_html__( 'Show/hide the social box from single post', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);

		/* Post Tags on single blog posts */
		$wp_customize->add_setting(
			'regina_enable_post_tags_blog_posts',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_post_tags_blog_posts', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Tags meta on single blog post', 'regina' ),
					'description' => esc_html__( 'This will disable the tagged with zone.', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_enable_post_tags_blog_posts', array(
				'selector' => '.single-post ul.post-tags',
			)
		);

		/* Author Info Box on single blog posts */
		$wp_customize->add_setting(
			'regina_enable_author_box_blog_posts',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_author_box_blog_posts', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Author info box on single blog post', 'regina' ),
					'description' => esc_html__( 'Displayed right at the end of the post', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_enable_author_box_blog_posts', array(
				'selector' => '.single-post #post-author',
			)
		);

		/***********************************************/
		/************** Breadcrumb Settings  ***************/
		/***********************************************/
		$wp_customize->add_setting(
			'regina_breadcrumb_text', array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Regina_Text_Custom_Control(
				$wp_customize, 'regina_breadcrumb_text', array(
					'label'       => __( 'Breadcrumbs Settings', 'regina' ),
					'description' => __( 'Settings related to breadcrumbs on single post', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);

		/* Breadcrumbs on single blog posts */
		$wp_customize->add_setting(
			'regina_enable_post_breadcrumbs',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_post_breadcrumbs', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Breadcrumbs on single blog posts', 'regina' ),
					'description' => esc_html__( 'This will disable the breadcrumbs', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_enable_post_breadcrumbs', array(
				'selector' => '.single-post #breadcrumb',
			)
		);

		/* BreadCrumb Menu:  post category */
		$wp_customize->add_setting(
			'regina_blog_breadcrumb_menu_post_category', array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_blog_breadcrumb_menu_post_category', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Show post category ?', 'regina' ),
					'description'     => esc_html__( 'Show the post category in the breadcrumb ?', 'regina' ),
					'section'         => 'regina_single_post_section',
					'active_callback' => 'regina_check_breadcrumbs',
				)
			)
		);

		/***********************************************/
		/************** Related Blog Settings  ***************/
		/***********************************************/

		$wp_customize->add_setting(
			'regina_related_post_text', array(
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => '',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Regina_Text_Custom_Control(
				$wp_customize, 'regina_related_post_text', array(
					'label'       => __( 'Related Posts Settings', 'regina' ),
					'description' => __( 'Control various related posts settings from here. For a demo-like experience, we recommend you don\'t change these settings.', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);

		/*  related posts carousel */
		$wp_customize->add_setting(
			'regina_enable_related_blog_posts', array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_related_blog_posts', array(
					'type'        => 'epsilon-toggle',
					'label'       => esc_html__( 'Related posts carousel ?', 'regina' ),
					'description' => esc_html__( 'Displayed after the author box', 'regina' ),
					'section'     => 'regina_single_post_section',
				)
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_enable_related_blog_posts', array(
				'selector' => '.single-post #related-posts',
			)
		);

		/* Number of related posts to display at once  */
		$wp_customize->add_setting(
			'regina_howmany_blog_posts', array(
				'sanitize_callback' => 'absint',
				'default'           => 3,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Slider(
				$wp_customize, 'regina_howmany_blog_posts', array(
					'type'            => 'epsilon-slider',
					'label'           => esc_html__( 'How many blog posts to display in the carousel at once ?', 'regina' ),
					'description'     => esc_html__( 'No more than 4 posts at once;', 'regina' ),
					'choices'         => array(
						'min'  => 1,
						'max'  => 4,
						'step' => 1,
					),
					'section'         => 'regina_single_post_section',
					'default'         => 3,
					'active_callback' => 'regina_check_related_posts',
				)
			)
		);

		/*  related posts title */
		$wp_customize->add_setting(
			'regina_enable_related_title_blog_posts', array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_related_title_blog_posts', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Show posts title in the carousel?', 'regina' ),
					'section'         => 'regina_single_post_section',
					'active_callback' => 'regina_check_related_posts',
				)
			)
		);

		/*  related posts date */
		$wp_customize->add_setting(
			'regina_enable_related_date_blog_posts',
			array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_enable_related_date_blog_posts', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Show posts date  ?', 'regina' ),
					'section'         => 'regina_single_post_section',
					'active_callback' => 'regina_check_related_posts',
				)
			)
		);

		/* Auto play carousel */
		$wp_customize->add_setting(
			'regina_autoplay_blog_posts', array(
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_autoplay_blog_posts', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Autoplay related carousel ?', 'regina' ),
					'section'         => 'regina_single_post_section',
					'active_callback' => 'regina_check_related_posts',
				)
			)
		);

		/* Display pagination ?  */
		$wp_customize->add_setting(
			'regina_pagination_blog_posts', array(
				'sanitize_callback' => 'absint',
				'default'           => 0,
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_pagination_blog_posts', array(
					'type'            => 'epsilon-toggle',
					'label'           => esc_html__( 'Display pagination controls ?', 'regina' ),
					'description'     => esc_html__( 'Will be displayed as navigation bullets', 'regina' ),
					'section'         => 'regina_single_post_section',
					'active_callback' => 'regina_check_related_posts',
				)
			)
		);

	}

	public function regina_contact( $wp_customize ) {

		$wp_customize->add_section(
			'regina_contact_options', array(
				'title'    => esc_html__( 'Contact Info', 'regina' ),
				'panel'    => 'regina_theme_options_panel',
				'priority' => 50,
			)
		);

		$wp_customize->add_setting(
			'regina_contact_tab', array(
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Tab(
				$wp_customize, 'regina_contact_tab', array(
					'type'     => 'epsilon-tab',
					'section'  => 'regina_contact_options',
					'priority' => 1,
					'buttons'  => array(
						array(
							'name'   => __( 'Contact', 'regina' ),
							'fields' => array(
								'regina_top_telephone_number',
								'regina_top_fax_number',
								'regina_top_email',
								'regina_jobs_email',
								'regina_adress',
							),
							'active' => true,
						),
						array(
							'name'   => __( 'Social', 'regina' ),
							'fields' => array(
								'regina_top_facebook',
								'regina_top_instagram',
								'regina_top_twitter',
								'regina_top_linkedin',
								'regina_top_youtube',
								'regina_top_yelp',
							),
						),
					),
				)
			)
		);

		$wp_customize->add_setting(
			'regina_top_telephone_number', array(
				'default'           => '(650) 652-8500',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_telephone_number', array(
				'label'   => esc_html__( 'Telephone number', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_telephone_number', array(
				'selector' => '#sub-header .phone-number a',
			)
		);

		$wp_customize->add_setting(
			'regina_top_fax_number', array(
				'default'           => '(650) 652-8500',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_fax_number', array(
				'label'   => esc_html__( 'Fax number', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'regina_top_email', array(
				'default'           => 'contact@reginapro.com',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_email', array(
				'label'   => esc_html__( 'Contact Email', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_email', array(
				'selector' => '#sub-header .email a',
			)
		);

		$wp_customize->add_setting(
			'regina_jobs_email', array(
				'default'           => 'jobs@reginapro.com',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_jobs_email', array(
				'label'   => esc_html__( 'Jobs Email', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'regina_adress', array(
				'default'           => 'Medplus<br>33 Farlane Street<br>Keilor East<br>VIC 3033, New York<br>',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Text_Editor(
				$wp_customize, 'regina_adress', array(
					'label'   => esc_html__( 'Your Address', 'regina' ),
					'section' => 'regina_contact_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_top_facebook', array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_facebook', array(
				'label'   => esc_html__( 'Facebook URL', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_facebook', array(
				'selector' => '#sub-header .social-link-list .facebook a',
			)
		);

		$wp_customize->add_setting(
			'regina_top_instagram', array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_instagram', array(
				'label'   => esc_html__( 'Instagram URL', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_instagram', array(
				'selector' => '#sub-header .social-link-list .instagram a',
			)
		);

		$wp_customize->add_setting(
			'regina_top_twitter', array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_twitter', array(
				'label'   => esc_html__( 'Twitter URL', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_twitter', array(
				'selector' => '#sub-header .social-link-list .twitter a',
			)
		);

		$wp_customize->add_setting(
			'regina_top_linkedin', array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_linkedin', array(
				'label'   => esc_html__( 'LinkedIN URL', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_linkedin', array(
				'selector' => '#sub-header .social-link-list .linkedin a',
			)
		);

		$wp_customize->add_setting(
			'regina_top_youtube', array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_youtube', array(
				'label'   => esc_html__( 'Youtube URL', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_youtube', array(
				'selector' => '#sub-header .social-link-list .youtube a',
			)
		);

		$wp_customize->add_setting(
			'regina_top_yelp', array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_yelp', array(
				'label'   => esc_html__( 'Yelp URL', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_yelp', array(
				'selector' => '#sub-header .social-link-list .yelp a',
			)
		);

		$wp_customize->add_setting(
			'regina_top_gplus', array(
				'default'           => '#',
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_top_gplus', array(
				'label'   => esc_html__( 'Google Plus URL', 'regina' ),
				'section' => 'regina_contact_options',
				'type'    => 'text',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_top_gplus', array(
				'selector' => '#sub-header .social-link-list .gplus a',
			)
		);

		$wp_customize->add_setting(
			'regina_map_api', array(
				'default'           => '',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_map_api', array(
				'label'       => esc_html__( 'Google Maps API Key', 'regina' ),
				'description' => esc_html__( 'Follow this link to get your API key: ', 'regina' ) . '<a href="https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key" target="_blank">' . esc_html__( 'Google Maps API Key', 'regina' ) . '</a>',
				'section'     => 'regina_contact_options',
				'type'        => 'text',
			)
		);

		$wp_customize->add_setting(
			'regina_map_address', array(
				'default'           => 'New York',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_map_address', array(
				'label'       => esc_html__( 'Google Maps Address', 'regina' ),
				'description' => esc_html__( 'The value entered here will be used as default for the Google Maps Shortcode. Also, in the Google Maps Shortcode used on the Contact Page Template.', 'regina' ),
				'section'     => 'regina_contact_options',
				'type'        => 'text',
			)
		);

	}

	public function regina_typography( $wp_customize ) {

		$wp_customize->add_section(
			'regina_typo_color_options', array(
				'title'    => __( 'Typography & Colors', 'regina' ),
				'priority' => 30,
			)
		);
		$background_color = $wp_customize->get_control( 'background_color' );
		if ( $background_color ) {
			$background_color->section  = 'regina_typo_color_options';
			$background_color->priority = 1;
		}

		$wp_customize->add_setting(
			'regina_theme_color', array(
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#08cae8',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 'regina_theme_color', array(
					'label'   => esc_html__( 'Theme Primary Color:', 'regina' ),
					'section' => 'regina_typo_color_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_primary_font', array(
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Typography(
				$wp_customize, 'regina_primary_font', array(
					'section'       => 'regina_typo_color_options',
					'label'         => esc_html__( 'Theme Primary Font:', 'regina' ),
					'stylesheet'    => 'regina-style',
					'choices'       => array(
						'font-family',
					),
					'selectors'     => array(
						'body,.icon-list li,h5,h6,.button,input[type="text"],textarea,#page-header .title,.ui-datepicker',
					),
					'font_defaults' => array(
						'font-family' => 'Lato',
					),
				)
			)
		);

		$wp_customize->add_setting(
			'regina_secondary_font', array(
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Typography(
				$wp_customize, 'regina_secondary_font', array(
					'section'       => 'regina_typo_color_options',
					'label'         => esc_html__( 'Theme Secondary Font:', 'regina' ),
					'stylesheet'    => 'regina-style',
					'choices'       => array(
						'font-family',
					),
					'selectors'     => array(
						'p small,h1, h2, h3, h4',
					),
					'font_defaults' => array(
						'font-family' => 'Montserrat',
					),
				)
			)
		);

	}

	public function regina_booking( $wp_customize ) {

		$wp_customize->add_section(
			'regina_booking_form', array(
				'title'      => __( 'Booking Form', 'regina' ),
				'panel'      => 'regina_theme_options_panel',
				'priority'   => 70,
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'regina_booking_form_opions', array(
				'default'           => 'custom-form',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);

		// Add Controls to install WPForms & Contact Form 7
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		if ( ! is_plugin_active( 'kali-forms/kali-forms.php' ) ) {

			$wp_customize->add_setting(
				'regina_kaliforms', array(
					'transport' => 'postMessage',
				)
			);
			$wp_customize->add_control(
				new Regina_PRO_Install_Plugin(
					$wp_customize, 'regina_kaliforms', array(
						'section'         => 'regina_booking_form',
						'label'           => esc_html__( 'Install Kali Forms', 'regina' ),
						'description'     => __( 'In order to use <strong>Kali Forms</strong> you need to install & activate it', 'regina' ),
						'slug'            => 'kali-forms',
					)
				)
			);

		} else {

			$wp_customize->add_setting(
				'regina_kaliforms_form_id', array(
					'default'           => 0,
					'sanitize_callback' => 'absint',
					'transport'         => 'postMessage',
				)
			);
			$wp_customize->add_control(
				'regina_kaliforms_form_id', array(
					'label'           => esc_html__( 'Select Booking form', 'regina' ),
					'section'         => 'regina_booking_form',
					'type'            => 'select',
					'choices'         => $this->regina_get_kaliforms_forms(),
				)
			);

		}





	}

	public function regina_sidebars( $wp_customize ) {
		$wp_customize->add_section(
			'regina_sidebar_options', array(
				'title'      => esc_html__( 'Manage Sidebars', 'regina' ),
				'panel'      => 'regina_theme_options_panel',
				'priority'   => 80,
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			new Epsilon_Setting_Repeater(
				$wp_customize, 'regina_multi_sidebars', array(
					'transport' => 'postMessage',
				)
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Repeater(
				$wp_customize, 'regina_multi_sidebars', array(
					'section'   => 'regina_sidebar_options',
					'label'     => esc_html__( 'Sidebars', 'regina' ),
					'row_label' => array(
						'type'  => 'field',
						'value' => esc_html__( 'Sidebars', 'regina' ),
						'field' => 'sidebar_name',
					),
					'fields'    => array(
						'sidebar_name' => array(
							'type'    => 'text',
							'label'   => __( 'Sidebar Name', 'regina' ),
							'default' => '',
						),
					),
				)
			)
		);

	}

	public function regina_footer( $wp_customize ) {
		$wp_customize->add_section(
			'regina_footer_options', array(
				'title'      => __( 'Footer Settings', 'regina' ),
				'priority'   => 20,
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'regina_footer_widgets', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_footer_widgets', array(
					'type'    => 'epsilon-toggle',
					'label'   => __( 'Show Footer Widgets ?', 'regina' ),
					'section' => 'regina_footer_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_footer_columns_v2', array(
				'default'           => array(
					'columnsCount' => 4,
					'columns'      => array(
						array(
							'index' => 1,
							'span'  => 3,
						),
						array(
							'index' => 2,
							'span'  => 3,
						),
						array(
							'index' => 3,
							'span'  => 3,
						),
						array(
							'index' => 4,
							'span'  => 3,
						),
					),
				),
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Epsilon_Control_Layouts(
				$wp_customize, 'regina_footer_columns_v2', array(
					'type'     => 'epsilon-layouts',
					'section'  => 'regina_footer_options',
					'layouts'  => array(
						1 => get_template_directory_uri() . '/includes/libraries/epsilon-framework/assets/img/one-column.png',
						2 => get_template_directory_uri() . '/includes/libraries/epsilon-framework/assets/img/two-column.png',
						3 => get_template_directory_uri() . '/includes/libraries/epsilon-framework/assets/img/three-column.png',
						4 => get_template_directory_uri() . '/includes/libraries/epsilon-framework/assets/img/four-column.png',
					),
					'min_span' => 2,
					'label'    => esc_html__( 'Footer Columns', 'regina' ),
				)
			)
		);

		$wp_customize->add_setting(
			'regina_footer_copyright', array(
				'default'           => '&copy; ' . date( 'Y' ) . ' Regina All Rights Reserved.',
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Text_Editor(
				$wp_customize, 'regina_footer_copyright', array(
					'label'   => esc_html__( 'Footer Copyright.', 'regina' ),
					'section' => 'regina_footer_options',
				)
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'regina_footer_copyright', array(
				'selector' => '#sub-footer .site-copyright',
			)
		);

	}

	public function regina_preloader( $wp_customize ) {

		$wp_customize->add_section(
			'regina_preloader_options', array(
				'title'      => __( 'Preloader Options', 'regina' ),
				'panel'      => 'regina_theme_options_panel',
				'priority'   => 90,
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'regina_preloader', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_preloader', array(
					'type'    => 'epsilon-toggle',
					'label'   => __( 'Enable Prealoader ?', 'regina' ),
					'section' => 'regina_preloader_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_preloader_type', array(
				'default'           => 'center-atom',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_preloader_type', array(
				'type'    => 'select',
				'label'   => __( 'Choose Preloader Type', 'regina' ),
				'section' => 'regina_preloader_options',
				'choices' => array(
					'minimal'      => 'Minimal',
					'corner-top'   => 'Corner Top',
					'loading-bar'  => 'Loading Bar',
					'center-radar' => 'Center Radar',
					'center-atom'  => 'Center Atom',
					'custom'       => 'Custom',
				),
			)
		);

		$wp_customize->add_setting(
			'regina_minimal_text', array(
				'default'           => __( 'Your custom loading message here.', 'regina' ),
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_minimal_text', array(
				'label'   => __( 'Loading message', 'regina' ),
				'section' => 'regina_preloader_options',
				'type'    => 'text',
			)
		);

		$wp_customize->add_setting(
			'regina_custom_loader', array(
				'sanitize_callback' => 'esc_url',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize, 'regina_custom_loader', array(
					'label'   => __( 'Custom Loader', 'regina' ),
					'section' => 'regina_preloader_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_text_color', array(
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#08cae8',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 'regina_text_color', array(
					'label'   => __( 'Preloader text color:', 'regina' ),
					'section' => 'regina_preloader_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_graphic_color', array(
				'sanitize_callback' => 'sanitize_hex_color',
				'default'           => '#08cae8',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize, 'regina_graphic_color', array(
					'label'   => __( 'Preloader graphics color:', 'regina' ),
					'section' => 'regina_preloader_options',
				)
			)
		);

	}

	public function regina_advanced_options( $wp_customize ) {

		$wp_customize->add_section(
			'regina_advanced_options', array(
				'title'      => __( 'Advanced Options', 'regina' ),
				'panel'      => 'regina_theme_options_panel',
				'priority'   => 110,
				'capability' => 'edit_theme_options',
			)
		);

		$wp_customize->add_setting(
			'regina_cpt_rewrite', array(
				'default'           => 'member',
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_cpt_rewrite', array(
				'label'       => __( 'Rewrite CPT Members', 'regina' ),
				'description' => __( 'Change /member/ from Members URL', 'regina' ),
				'section'     => 'regina_advanced_options',
				'type'        => 'text',
			)
		);

		$wp_customize->add_setting(
			'regina_services_contact_enable', array(
				'sanitize_callback' => 'absint',
				'default'           => '1',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new Epsilon_Control_Toggle(
				$wp_customize, 'regina_services_contact_enable', array(
					'type'        => 'epsilon-toggle',
					'label'       => __( 'Contact Us on Services Page?', 'regina' ),
					'description' => __( 'Display contact us section on the Services Page', 'regina' ),
					'section'     => 'regina_advanced_options',
				)
			)
		);

		$wp_customize->add_setting(
			'regina_services_contact_title', array(
				'default'           => __( 'Speak with our doctors', 'regina' ),
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_services_contact_title', array(
				'label'       => __( 'Contact us title', 'regina' ),
				'description' => __( 'Heading for this section', 'regina' ),
				'section'     => 'regina_advanced_options',
				'type'        => 'text',
			)
		);

		$wp_customize->add_setting(
			'regina_services_contact_subtitle', array(
				'default'           => __( 'We offer various services lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.', 'regina' ),
				'sanitize_callback' => 'esc_html',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_services_contact_subtitle', array(
				'label'       => __( 'Contact us subtitle', 'regina' ),
				'description' => __( 'Subheading for this section', 'regina' ),
				'section'     => 'regina_advanced_options',
				'type'        => 'text',
			)
		);

		$wp_customize->add_setting(
			'regina_custom_js', array(
				'default'   => '',
				'transport' => 'postMessage',
			)
		);
		$wp_customize->add_control(
			'regina_custom_js', array(
				'label'   => esc_html__( 'Custom JS code', 'regina' ),
				'section' => 'regina_advanced_options',
				'type'    => 'textarea',
			)
		);

	}

	public function add_section_cotnent( $wp_customize ) {

		$wp_customize->add_section(
			new WP_Customize_New_Section(
				$wp_customize, 'regina_add_section', array(
					'title'    => __( 'New Section', 'regina' ),
					'panel'    => 'regina_frontpage_panel',
					'priority' => 90,
				)
			)
		);

		$args = array(
			'post_type'      => 'section',
			'post_status'    => 'publish',
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'posts_per_page' => - 1,
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => 'regina_options_section-disable',
					'value'   => '1',
					'compare' => '!=',
				),
				array(
					'key'     => 'regina_options_section-disable',
					'value'   => '1',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		$loop = new WP_Query( $args );

		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) {
				$loop->the_post();
				$menu_order = get_post_field( 'menu_order', get_the_ID() );
				if ( '0' == $menu_order || '' == $menu_order ) {
					$menu_order = 1;
				}
				$slug       = get_post_field( 'post_name', get_the_ID() );
				$section_id = 'regina_section_' . $slug;
				$wp_customize->add_section(
					new Regina_Section(
						$wp_customize, $section_id, array(
							'title'      => get_the_title(),
							'section_id' => get_the_ID(),
							'slug'       => $slug,
							'panel'      => 'regina_frontpage_panel',
							'priority'   => $menu_order,
						)
					)
				);
			}
			wp_reset_postdata();
		}

	}

	// Helper functions
	public function regina_get_cf7_forms() {
		$args = array(
			'post_status'    => 'publish',
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => -1,
		);

		$contact_forms = get_posts( $args );

		$forms = array(
			'0' => 'Select a contact form',
		);
		foreach ( $contact_forms as $key => $form ) {
			$forms[ $form->ID ] = $form->post_title;
		}
		return $forms;
	}

	public function regina_get_wpforms() {
		$args = array(
			'post_status'    => 'publish',
			'post_type'      => 'wpforms',
			'posts_per_page' => -1,
		);

		$contact_forms = get_posts( $args );

		$forms = array(
			'0' => 'Select a contact form',
		);
		foreach ( $contact_forms as $key => $form ) {
			$forms[ $form->ID ] = $form->post_title;
		}
		return $forms;
	}

	public function regina_get_kaliforms_forms() {
		$args = array(
			'post_status'    => 'publish',
			'post_type'      => 'kaliforms_forms',
			'posts_per_page' => -1,
		);

		$contact_forms = get_posts( $args );

		$forms = array(
			'0' => 'Select a contact form',
		);
		foreach ( $contact_forms as $key => $form ) {
			$forms[ $form->ID ] = $form->post_title;
		}
		return $forms;
	}

	public function insert_section() {

		if ( isset( $_POST['title'] ) && isset( $_POST['nonce'] ) ) {

			if ( wp_verify_nonce( $_POST['nonce'], 'regina-customizer-ajax' ) ) {

				$args = array(
					'post_type'   => 'section',
					'post_status' => 'publish',
					'post_title'  => sanitize_text_field( $_POST['title'] ),
				);

				$section_id = wp_insert_post( $args, $wp_error );

				if ( ! is_wp_error( $section_id ) ) {

					$slug     = get_post_field( 'post_name', $section_id );
					$response = json_encode(
						array(
							'slug' => $slug,
							'id'   => $section_id,
						)
					);

					wp_die( $response );

				}
			}
		}

		wp_die( '0' );
	}

	public function order_section() {

		if ( isset( $_POST['sections'] ) && isset( $_POST['nonce'] ) ) {

			if ( wp_verify_nonce( $_POST['nonce'], 'regina-customizer-ajax' ) ) {
				$menu_order = 1;
				if ( is_array( $_POST['sections'] ) ) {
					foreach ( $_POST['sections'] as $section_id ) {
						if ( 'section' == get_post_type( $section_id ) ) {
							wp_update_post(
								array(
									'ID'         => $section_id,
									'menu_order' => $menu_order,
								)
							);
							$menu_order++;
						}
					}
				}
			}
		}

		wp_die( 'success' );

	}

}

new Regina_Customizer();
