<?php

class Antreas_Theme {

	private $path;
	private $plugins;
	private $actions;

	function __construct() {

		$this->path = get_template_directory() . '/includes/';
		$this->load_dependencies();

		// Recommended Plugins
		$this->plugins = array(
			'kali-forms'                 => array( 'recommended' => true ),
			'modula-best-grid-gallery'   => array( 'recommended' => true ),
			'strong-testimonials'        => array( 'recommended' => false ),
			'uber-nocaptcha-recaptcha'   => array( 'recommended' => false ),
		);

		// Recommended Actions
		$this->actions = array(
			$actions[] = array(
				'id'          => ANTREAS_SLUG . '-req-ac-import-demo-content',
				'title'       => esc_html__( 'Import Demo Content', 'antreas' ),
				'description' => $this->import_action_description(),
				'help'        => $this->generate_action_html(),
				'check'       => Antreas_Notify_System::check_content_import(),
			),
			array(
				'id'          => ANTREAS_SLUG . '-req-ac-install-cpo-companion',
				'title'       => Antreas_Notify_System::create_plugin_requirement_title( __( 'Install: CPO Companion', 'antreas' ), __( 'Activate: CPO Companion', 'antreas' ), 'cpo-companion' ),
				'description' => __( 'It is highly recommended that you install the CPO Content Types plugin. It will help you manage all the special content types that this theme supports.', 'antreas' ),
				'check'       => Antreas_Notify_System::has_plugin( 'cpo-companion' ),
				'plugin_slug' => 'cpo-companion',
			),
			array(
				'id'          => ANTREAS_SLUG . '-req-ac-install-modula',
				'title'       => Antreas_Notify_System::create_plugin_requirement_title( __( 'Install: Modula', 'antreas' ), __( 'Activate: Modula', 'antreas' ), 'modula-best-grid-gallery' ),
				'description' => __( 'It is highly recommended that you install the Modula plugin.', 'antreas' ),
				'check'       => Antreas_Notify_System::has_plugin( 'modula-best-grid-gallery' ),
				'plugin_slug' => 'modula-best-grid-gallery',
			),
			array(
				'id'          => ANTREAS_SLUG . '-req-ac-install-kaliforms',
				'title'       => Antreas_Notify_System::create_plugin_requirement_title( __( 'Install: Kali Forms', 'antreas' ), __( 'Activate: Kali Forms', 'antreas' ), 'kali-forms' ),
				'description' => __( 'It is highly recommended that you install the Kali Forms plugin.', 'antreas' ),
				'check'       => Antreas_Notify_System::has_plugin( 'kali-forms' ),
				'plugin_slug' => 'kali-forms',
			),
		);

		$this->init_epsilon();
		$this->init_welcome_screen();

		add_action( 'customize_register', array( $this, 'customizer' ) );

		// filters for cpo companion.
		add_filter( 'cpo_companion_content', array( $this, 'content_path' ), 99 );
		add_filter( 'cpo_companion_widgets', array( $this, 'widgets_path' ), 99 );
		add_filter( 'cpo_companion_theme_settings', array( $this, 'theme_settings' ), 99 );

		add_filter( 'cpo_companion_theme_settings_name', array( $this, 'theme_settings_name' ), 99 );
		add_filter( 'cpo_companion_import_option', array( $this, 'import_option_name' ), 99 );
	}

	private function load_dependencies() {

		require_once $this->path . 'libraries/epsilon-framework/class-epsilon-framework.php';
		require_once $this->path . 'notify-system-checks.php';
		require_once $this->path . 'libraries/welcome-screen/inc/class-edd-theme-helper.php';
		require_once $this->path . 'libraries/welcome-screen/inc/class-epsilon-updater-class.php';
		require_once $this->path . 'libraries/welcome-screen/class-epsilon-welcome-screen.php';

	}

	private function init_epsilon() {

		$args = array(
			'controls' => array( 'toggle' ), // array of controls to load
			'sections' => array( 'recommended-actions' ), // array of sections to load
			'path'     => '/includes/libraries',
		);

		new Epsilon_Framework( $args );

	}

	private function init_welcome_screen() {

		Epsilon_Welcome_Screen::get_instance(
			$config = array(
				'theme-name'  => ANTREAS_NAME,
				'theme-slug'  => ANTREAS_SLUG,
				'actions'     => $this->actions,
				'plugins'     => $this->plugins,
				'edd'         => true,
				'download_id' => '51651',
			)
		);

	}

	public function customizer( $wp_customize ) {

		$wp_customize->add_section(
			new Epsilon_Section_Recommended_Actions(
				$wp_customize,
				'antreas_recomended_section',
				array(
					'title'                        => esc_html__( 'Recomended Actions', 'antreas' ),
					'social_text'                  => esc_html__( 'We are social', 'antreas' ),
					'plugin_text'                  => esc_html__( 'Recomended Plugins', 'antreas' ),
					'actions'                      => $this->actions,
					'plugins'                      => $this->plugins,
					'theme_specific_option'        => ANTREAS_PREFIX . '_show_required_actions',
					'theme_specific_plugin_option' => ANTREAS_PREFIX . '_show_recommended_plugins',
					'facebook'                     => 'https://www.facebook.com/cpothemes',
					'twitter'                      => 'https://twitter.com/cpothemes',
					'wp_review'                    => false,
					'priority'                     => 0,
				)
			)
		);

	}

	private function import_action_description() {

		$imported     = array();
		$not_imported = array();

		if ( 1 == get_option( 'antreas_content_imported' ) ) {
			$imported[] = 'content';
		} else {
			$not_imported[] = 'content';
		}

		if ( 1 == get_option( 'antreas_widgets_imported' ) ) {
			$imported[] = 'widgets';
		} else {
			$not_imported[] = 'widgets';
		}

		if ( 1 == get_option( 'antreas_pro_settings_imported' ) ) {
			$imported[] = 'settings';
		} else {
			$not_imported[] = 'settings';
		}

		$description = '';
		if ( $imported ) {
			$description .= sprintf( __( 'Looks like you already imported demo %s.<br/>', 'antreas' ), implode( ', ', $imported ) );
		}
		$description .= sprintf( __( 'Clicking the button below will add %s to your WordPress installation. Click advanced to customize the import process. This procces might take up to 2 min. Please don\'t close the window.', 'antreas' ), implode( ', ', $not_imported ) );

		return $description;
	}

	private function generate_action_html() {

		$import_actions = array();
		if ( 1 != get_option( 'antreas_content_imported' ) ) {
			$import_actions['import_content'] = esc_html__( 'Import Content', 'antreas' );
		}
		if ( 1 != get_option( 'antreas_widgets_imported' ) ) {
			$import_actions['import_widgets'] = esc_html__( 'Import Widgets', 'antreas' );
		}
		if ( 1 != get_option( 'antreas_pro_settings_imported' ) ) {
			$import_actions['import_options'] = esc_html__( 'Import Settings', 'antreas' );
		}

		$import_plugins = array(
			'cpo-companion'            => esc_html__( 'CPO Companion', 'antreas' ),
			'modula-best-grid-gallery' => esc_html__( 'Modula Gallery', 'antreas' ),
			'kali-forms'               => esc_html__( 'Kali Forms', 'antreas' ),
		);

		$plugins_html = '';

		if ( is_customize_preview() ) {
			$url  = 'themes.php?page=%1$s-welcome&tab=%2$s';
			$html = '<a class="button button-primary" id="" href="' . esc_url( admin_url( sprintf( $url, ANTREAS_SLUG, 'recommended-actions' ) ) ) . '">' . __( 'Import Demo Content', 'antreas' ) . '</a>';
		} else {
			$html  = '<p><a class="button button-primary cpo-import-button epsilon-ajax-button" data-action="import_demo" id="add_default_sections" href="#">' . __( 'Import Demo Content', 'antreas' ) . '</a>';
			$html .= '<a class="button epsilon-hidden-content-toggler" href="#welcome-hidden-content">' . __( 'Advanced', 'antreas' ) . '</a></p>';
			$html .= '<div class="import-content-container" id="welcome-hidden-content">';

			foreach ( $import_plugins as $id => $label ) {
				if ( ! Antreas_Notify_System::has_plugin( $id ) ) {
					$plugins_html .= $this->generate_checkbox( $id, $label, 'plugins', true );
				}
			}

			if ( '' != $plugins_html ) {
				$html .= '<div class="plugins-container">';
				$html .= '<h4>' . __( 'Plugins', 'antreas' ) . '</h4>';
				$html .= '<div class="checkbox-group">';
				$html .= $plugins_html;
				$html .= '</div>';
				$html .= '</div>';
			}

			$html .= '<div class="demo-content-container">';
			$html .= '<h4>' . __( 'Demo Content', 'antreas' ) . '</h4>';
			$html .= '<div class="checkbox-group">';
			foreach ( $import_actions as $id => $label ) {
				$html .= $this->generate_checkbox( $id, $label );
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;

	}

	private function generate_checkbox( $id, $label, $name = 'options', $block = false ) {
		$string = '<label><input checked type="checkbox" name="%1$s" class="demo-checkboxes"' . ( $block ? ' disabled ' : ' ' ) . 'value="%2$s">%3$s</label>';
		return sprintf( $string, $name, $id, $label );
	}

	// Path to demo content
	public function content_path() {
		return get_template_directory() . '/demo/content.xml';
	}

	public function widgets_path() {
		return get_template_directory() . '/demo/widgets.wie';
	}

	public function import_option_name() {
		return 'antreas_pro_all_imported';
	}

	public function theme_settings_name() {
		return 'antreas_settings';
	}

	public function theme_settings() {

		$defaults = array(
			'logo_dimensions'            => array( 'width' => 90, 'height' => 43 ),
			'sidebar_position'           => 'right',
			'layout_subfooter_columns'   => 3,
			'sticky_header'              => true,
			'layout_breadcrumbs'         => true,
			'layout_languages'           => true,
			'layout_cart'                => true,
			'general_credit'             => true,
			'enable_go_to_top'           => true,
			'enable_animations'          => true,
			'sidebar_position_home'      => 'none',
			'home_order'                 => 'slider,features,about,tagline,portfolio,services,products,testimonials,team,clients,contact,shortcode,content',
			'home_tagline'               => __( 'Antreas is a theme with great potential', 'antreas' ),
			'home_tagline_content'       => __( 'this tagline can be easily added anywhere on your site', 'antreas' ),
			'home_tagline_url'           => '',
			'home_tagline_link'          => __( 'Learn More', 'antreas' ),
			'tagline_show'               => array( 'homepage' ),
			'tagline_exclude'            => array( '404_page' ),
			'slider_height'              => '650',
			'slider_speed'               => '400',
			'slider_timeout'             => '8000',
			'slider_effect'              => 'fade',
			'slider_show'                => array( 'homepage' ),
			'slider_exclude'             => array( '404_page' ),
			'home_features'              => __( 'Why choose us', 'antreas' ),
			'home_features_subtitle'     => __( 'show & tell', 'antreas' ),
			'features_columns'           => 4,
			'features_show'              => array( 'homepage' ),
			'features_exclude'           => array( '404_page' ),
			'home_portfolio'             => __( 'See our Online Portfolio', 'antreas' ),
			'home_portfolio_subtitle'    => __( 'our work', 'antreas' ),
			'portfolio_columns'          => 5,
			'portfolio_related'          => true,
			'portfolio_show'             => array( 'homepage' ),
			'portfolio_exclude'          => array( '404_page' ),
			'home_products'              => __( 'Our Featured Products', 'antreas' ),
			'home_products_subtitle'     => __( 'our shop', 'antreas' ),
			'products_columns'           => 4,
			'products_show'              => array( 'homepage' ),
			'products_exclude'           => array( '404_page' ),
			'home_services'              => __( 'What we can do for you', 'antreas' ),
			'home_services_subtitle'     => __( 'our services', 'antreas' ),
			'services_columns'           => 2,
			'services_show'              => array( 'homepage' ),
			'services_exclude'           => array( '404_page' ),
			'home_about'                 => __( 'About us', 'antreas' ),
			'home_about_subtitle'        => __( 'who we are', 'antreas' ),
			'about_pages'                => array(),
			'about_show'                 => array( 'homepage' ),
			'about_exclude'              => array( '404_page' ),
			'home_team'                  => __( 'Meet our team', 'antreas' ),
			'home_team_subtitle'         => __( 'we are dedicated', 'antreas' ),
			'team_columns'               => 4,
			'team_show'                  => array( 'homepage' ),
			'team_exclude'               => array( '404_page' ),
			'home_testimonials'          => __( 'What people say about us', 'antreas' ),
			'home_testimonials_subtitle' => __( 'our testimonials', 'antreas' ),
			'testimonials_columns'       => 3,
			'testimonials_show'          => array( 'homepage' ),
			'testimonials_exclude'       => array( '404_page' ),
			'home_clients'               => __( 'Some of our best clients', 'antreas' ),
			'home_clients_subtitle'      => __( 'section subtitle', 'antreas' ),
			'clients_columns'            => 5,
			'clients_show'               => array( 'homepage' ),
			'clients_exclude'            => array( '404_page' ),
			'home_contact'               => __( 'Contact us', 'antreas' ),
			'home_contact_subtitle'      => __( 'get in touch', 'antreas' ),
			'home_contact_content'       => '<h5>We don\'t byte</h5><p>Proin quis nisl et mauris dictum fringilla. Aliquam erat volutpat. Integer consequat sagittis tellus a dapibus. Donec porta libero vel sapien rutrum, vitae efficitur risus vestibulum. Maecenas arcu ante, suscipit in fermentum sit amet, pretium eu turpis. Pellentesque iaculis cursus ipsum eget rutrum. Nullam blandit laoreet dui, sed dictum nisl consequat a. Quisque sagittis libero lorem, posuere commodo sem molestie sed. Donec ullamcorper feugiat finibus.</p><h5>Contact Info</h5><p><i class="fa fa-map-marker"></i>  <strong>Adress:</strong> 75 Ninth Avenue New York, NY 10011</p><p><i class="fa fa-phone"></i>  <strong>Telephone:</strong> +1 212-565-0000</p><p><i class="fa fa-envelope"></i>  <strong>Email:</strong> <a href="mailto:contact@gmail.com">contact@gmail.com</a></p>',
			'contact_show'               => array( 'homepage' ),
			'contact_exclude'            => array( '404_page' ),
			'home_shortcode'             => __( 'Other Information', 'antreas' ),
			'home_shortcode_subtitle'    => '',
			'shortcode_show'             => array(),
			'shortcode_exclude'          => array( '404_page' ),
			'home_posts'                 => true,
			'home_blog'                  => __( 'Recent blog posts', 'antreas' ),
			'home_blog_subtitle'         => __( 'our blog', 'antreas' ),
			'blog_columns'               => 3,
			'postpage_preview'           => 'excerpt',
			'postpage_dates'             => true,
			'postpage_authors'           => true,
			'postpage_comments'          => true,
			'postpage_categories'        => true,
			'postpage_tags'              => true,
			'type_size'                  => '1.05',
			'type_headings'              => 'Hind',
			'type_nav'                   => 'Hind',
			'type_body'                  => 'Hind',
			'type_body_variants'         => true,
			'primary_color'              => '#22c0e3',
			'secondary_color'            => '#424247',
			'type_headings_color'        => '#222222',
			'type_widgets_color'         => '#222222',
			'type_nav_color'             => '#676767',
			'type_body_color'            => '#919191',
			'type_link_color'            => '#22c0e3',
			'subfooter_bg_color'         => '#f3f3f3',
			'footer_bg_color'            => '#333333',
			'social_links'               => 'http://www.facebook.com http://www.twitter.com http://www.linkedin.com http://www.instagram.com',
		);

		return $defaults;
	}

}

new Antreas_Theme();
