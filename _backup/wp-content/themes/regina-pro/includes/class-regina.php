<?php

class Regina {

	private $plugins;
	private $actions;
	private $theme_slug = 'regina';

	function __construct() {

		$this->plugins = array(
			'kali-forms'           => array(
				'recommended' => true,
			),
			'modula-best-grid-gallery' => array(
				'recommended' => true,
			),
			'simple-author-box'        => array(
				'recommended' => true,
			),
			'strong-testimonials'        => array( 'recommended' => true ),
		);

		/*
		 * id - unique id; required
		 * title
		 * description
		 * check - check for plugins (if installed)
		 * plugin_slug - the plugin's slug (used for installing the plugin)
		 *
		 */
		$this->actions = array(
			array(
				'id'          => 'regina-check-kaliforms',
				'title'       => Regina_Notify_System::plugin_verifier( 'kali-forms', 'title', 'Kali Forms' ),
				'description' => Regina_Notify_System::plugin_verifier( 'kali-forms', 'description', 'Kali Forms' ),
				'check'       => Regina_Notify_System::has_plugin( 'kali-forms' ),
				'plugin_slug' => 'kali-forms',
			),
			array(
				'id'          => 'regina-check-modula-best-grid-gallery',
				'title'       => Regina_Notify_System::plugin_verifier( 'modula-best-grid-gallery', 'title', 'Modula' ),
				'description' => Regina_Notify_System::plugin_verifier( 'modula-best-grid-gallery', 'description', 'Modula' ),
				'check'       => Regina_Notify_System::has_plugin( 'modula-best-grid-gallery' ),
				'plugin_slug' => 'modula-best-grid-gallery',
			),
			array(
				'id'          => 'regina-pro-import-demo-content',
				'title'       => esc_html__( 'Import Demo Content', 'regina' ),
				'description' => esc_html__( 'This will make your website to look like our demo.', 'regina' ),
				'help'        => '<a class="button button-primary epsilon-import-content-button" href="#">' . __( 'Import Demo Content', 'regina' ) . '</a>',
				'check'       => Regina_Notify_System::check_for_content(),
			),
		);

		if ( is_customize_preview() ) {
			$url                      = 'themes.php?page=%1$s-welcome&tab=%2$s';
			$this->actions[1]['help'] = '<a class="button button-primary" id="" href="' . esc_url( admin_url( sprintf( $url, 'regina', 'recommended-actions' ) ) ) . '">' . __( 'Import Demo Content', 'regina' ) . '</a>';
		}

		$this->init_epsilon();
		$this->init_shortcodes();
		$this->init_welcome_screen();

		// Hooks
		add_action( 'admin_init', array( $this, 'regina_deactivate_unnecessary_plugins' ) );
		add_action( 'customize_register', array( $this, 'init_customizer' ) );

		// Tell polylang to translate our CPTs
		add_filter( 'pll_get_post_types', array( $this, 'add_cpt_to_polylang' ) );

		// Include our WPForms template
		require_once 'class-regina-form.php';

	}

	public function init_epsilon() {

		$args = array(
			'controls' => array(
				'toggle',
				'typography',
				'slider',
				'repeater',
				'section-repeater',
				'image',
				'text-editor',
				'icon-picker',
				'customizer-navigation',
				'color-scheme',
			),
			'sections' => array( 'recommended-actions' ),
			'backup'   => false,
			'path'     => '/includes/libraries',
		);

		new Epsilon_Framework( $args );
		$this->start_typography();
	}

	/**
	 * Instantiate the Epsilon Typography object
	 */
	public function start_typography() {

		$options = array(
			'regina_primary_font',
			'regina_secondary_font',
		);

		$handler = 'regina-style';
		Epsilon_Typography::get_instance( $options, $handler );
	}

	/**
	 * Initiate theme's shortcodes
	 */
	public function init_shortcodes() {

		// Check if MSM is activated
		require_once get_template_directory() . '/includes/framework/shortcodes/shortcodes-init.php';
		if ( ! Epsilon_Notify_System::check_plugin_is_active( 'macho-shortcode-manager' ) ) {
			require_once get_template_directory() . '/includes/libraries/macho-shortcode-manager/class-macho-shortcode-manager.php';
			new Macho_Shortcode_Manager();
		}

	}

	/**
	 * Initiate the welcome screen
	 */
	public function init_welcome_screen() {
		// Welcome screen.
		if ( is_admin() ) {

			require_once 'libraries/welcome-screen/class-epsilon-welcome-screen.php';

			Epsilon_Welcome_Screen::get_instance(
				$config = array(
					'theme-name'  => 'Regina',
					'theme-slug'  => 'regina',
					'actions'     => $this->actions,
					'plugins'     => $this->plugins,
					'edd'         => true,
					'download_id' => '4190',
				)
			);
		}// End if().
	}

	public function init_customizer( $wp_customize ) {

		$current_theme = wp_get_theme();
		$wp_customize->add_section(
			new Epsilon_Section_Recommended_Actions(
				$wp_customize, 'epsilon_recomended_section', array(
					'title'                        => esc_html__( 'Recomended Actions', 'regina' ),
					'social_text'                  => esc_html( $current_theme->get( 'Author' ) ) . esc_html__( ' is social :', 'regina' ),
					'plugin_text'                  => esc_html__( 'Recomended Plugins :', 'regina' ),
					'actions'                      => $this->actions,
					'plugins'                      => $this->plugins,
					'theme_specific_option'        => $this->theme_slug . '_actions_left',
					'theme_specific_plugin_option' => $this->theme_slug . '_show_required_plugins',
					'facebook'                     => 'https://www.facebook.com/machothemes',
					'twitter'                      => 'https://twitter.com/MachoThemez',
					'wp_review'                    => false,
					'priority'                     => 0,
				)
			)
		);

	}

	// Deactivate Kirki & Macho Shortcode Manager
	public function regina_deactivate_unnecessary_plugins() {

		$check_backwards_compatibility = get_option( 'regina_pro_backwards_compatibility' );
		if ( Epsilon_Notify_System::check_plugin_is_active( 'macho-shortcode-manager' ) ) {
			deactivate_plugins( 'macho-shortcode-manager/macho-shortcode-manager.php' );
		}

		if ( Epsilon_Notify_System::check_plugin_is_active( 'kirki' ) && ! isset( $check_backwards_compatibility['kirki'] ) ) {
			deactivate_plugins( 'kirki/kirki.php' );
			$check_backwards_compatibility['kirki'] = true;
			update_option( 'regina_pro_backwards_compatibility', $check_backwards_compatibility );
		}

	}

	public function add_cpt_to_polylang( $cpt ) {
		$cpt['service']     = 'service';
		$cpt['section']     = 'section';
		$cpt['testimonial'] = 'testimonial';

		return $cpt;
	}
}
