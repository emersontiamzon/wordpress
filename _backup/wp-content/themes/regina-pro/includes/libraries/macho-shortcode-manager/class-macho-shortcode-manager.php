<?php

if ( ! class_exists( 'Macho_Shortcode_Manager' ) ) {
	class Macho_Shortcode_Manager {

		/**
		 * Plugin version, used for cache-busting of style and script file references.
		 *
		 * @since   1.0.0
		 *
		 * @var     string
		 */
		const VERSION = '1.0.0';

		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		protected static $instance = null;

		/**
		 * Initialize the plugin by setting localization and loading public scripts
		 * and styles.
		 *
		 * @since     1.0.0
		 */
		public function __construct() {
			define( 'MSM_CSS_TINYMCE_URI', get_template_directory_uri() . '/includes/libraries/macho-shortcode-manager/assets/css/tinymce' );
			define( 'MSM_JS_TINYMCE_URI', get_template_directory_uri() . '/includes/libraries/macho-shortcode-manager/assets/js/tinymce' );
			define( 'MSM_IMG_TINYMCE_URI', get_template_directory_uri() . '/includes/libraries/macho-shortcode-manager/assets/images/tinymce' );
			define( 'MSM_TINYMCE_DIR', get_template_directory_uri() . 'tinymce' );

			define( 'MSM_CSS_URI', get_template_directory_uri() . '/includes/libraries/macho-shortcode-manager/assets/css' );
			define( 'MSM_JS_URI', get_template_directory_uri() . '/includes/libraries/macho-shortcode-manager/assets/js' );
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
			add_action( 'wp_ajax_macho_sm_popup', array( $this, 'popup' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'load_front_css' ) );
		}

		/**
		 * Registers TinyMCE rich editor buttons
		 *
		 * @return    void
		 */
		function init() {
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( 'mce_external_plugins', array( $this, 'add_rich_plugins' ) );
				add_filter( 'mce_buttons', array( $this, 'register_rich_buttons' ) );
			}

			require_once 'msm-functions.php';
			$this->init_shortcodes();

		}

		function load_front_css() {

			$google_maps_api_key = get_theme_mod( 'regina_map_api', '' );
			wp_enqueue_style( 'macho-fontawesome-css', MSM_CSS_URI . '/front/font-awesome.min.css', false, Macho_Shortcode_Manager::VERSION, 'all' );
			wp_enqueue_style( 'macho-style-css', MSM_CSS_URI . '/front/shortcodes.css', false, Macho_Shortcode_Manager::VERSION, 'all' );

			wp_enqueue_script( 'macho-jquery-lazyload', MSM_JS_URI . '/front/lazyLoad.js', array( 'jquery' ), Macho_Shortcode_Manager::VERSION, true );
			wp_enqueue_script( 'google-maps-api', '//maps.google.com/maps/api/js?sensor=false&key=' . esc_attr( $google_maps_api_key ), array(), Macho_Shortcode_Manager::VERSION, true );
			wp_enqueue_script( 'macho-pie-chart', MSM_JS_URI . '/front/easypiechart.min.js', array( 'jquery' ), Macho_Shortcode_Manager::VERSION, true );
			wp_enqueue_script( 'macho-msm-scripts', MSM_JS_URI . '/front/scripts.js', array( 'jquery' ), Macho_Shortcode_Manager::VERSION, true );

		}

		// --------------------------------------------------------------------------

		/**
		 * Find and include all shortcode classes within shortcodes folder
		 *
		 * @return void
		 */
		function init_shortcodes() {

			// include the shortcode definitions
			include dirname( __FILE__ ) . '/shortcodes.php';

			// Content Shortcodes
			add_shortcode( 'code', 'macho_code_function' );
			add_shortcode( 'button', 'macho_button_function' );
			add_shortcode( 'code', 'macho_code_function' );
			add_shortcode( 'row', 'macho_row_function' );
			add_shortcode( 'column', 'macho_columns_function' );
			add_shortcode( 'posts', 'macho_latest_blog_posts' );
			add_shortcode( 'spacer', 'macho_spacer' );
			add_shortcode( 'sep', 'macho_separator' );
			add_shortcode( 'pricing-tabel-container', 'macho_pricing_table_shortcode' );
			add_shortcode( 'pricing-table-item', 'macho_pricing_shortcode' );
			add_shortcode( 'heading', 'macho_heading_shortcode' );
			add_shortcode( 'cta', 'macho_callout_shortcode' );
			add_shortcode( 'pie', 'macho_pie_charts' );
			add_shortcode( 'member', 'macho_team_member' );
			add_shortcode( 'highlight', 'mt_highlight_shortcode' );
			add_shortcode( 'skillbar', 'mt_skillbar_shortcode' );
			add_shortcode( 'blockquote', 'macho_blockquote_function' );
			add_shortcode( 'rounded-image', 'macho_rounded_image' );
			add_shortcode( 'product-image', 'macho_pricing_product_image' );

			// Framework Specific Shortcodes
			add_shortcode( 'slider', 'macho_slider_function' );

			// Theme Specific Shortcodes
			add_shortcode( 'list', 'macho_checkmark_lists' );
			add_shortcode( 'list-item', 'macho_checkmark_list_item' );
			add_shortcode( 'contact-block', 'macho_contact_block' );
			add_shortcode( 'map', 'macho_google_maps' );
			add_shortcode( 'icon-list', 'macho_list_icon_wrapper' );
			add_shortcode( 'icon-list-item', 'macho_list_icon_item' );

		}

		/**
		 * Defins TinyMCE rich editor js plugin
		 *
		 * @return    void
		 */
		function add_rich_plugins( $plugin_array ) {
			if ( is_admin() ) {
				$plugin_array['macho_button'] = MSM_JS_URI . '/plugin.js';
			}

			return $plugin_array;
		}

		// --------------------------------------------------------------------------

		/**
		 * Adds TinyMCE rich editor buttons
		 *
		 * @return    void
		 */
		function register_rich_buttons( $buttons ) {
			array_push( $buttons, 'macho_button' );
			return $buttons;
		}

		/**
		 * Enqueue Scripts and Styles
		 *
		 * @return    void
		 */
		function admin_enqueue_scripts( $hook ) {

			if ( 'post-new.php' != $hook && 'post.php' != $hook ) {
				return;
			}

			// css
			wp_enqueue_style( 'macho-popup', MSM_CSS_TINYMCE_URI . '/popup.css', false, Macho_Shortcode_Manager::VERSION, 'all' );
			wp_enqueue_style( 'macho-jquery.chosen', MSM_CSS_TINYMCE_URI . '/chosen.css', false, Macho_Shortcode_Manager::VERSION, 'all' );
			wp_enqueue_style( 'macho-font-awesome', get_template_directory_uri() . '/includes/libraries/macho-shortcode-manager/assets/css/front/font-awesome.min.css', false, Macho_Shortcode_Manager::VERSION, 'all' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'macho-tooltip-css', MSM_CSS_TINYMCE_URI . '/tooltip.css', false, Macho_Shortcode_Manager::VERSION, 'all' );

			// js
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'macho-jquery-livequery', MSM_JS_TINYMCE_URI . '/jquery.livequery.js', false, Macho_Shortcode_Manager::VERSION, false );
			wp_enqueue_script( 'macho-jquery-appendo', MSM_JS_TINYMCE_URI . '/jquery.appendo.js', false, Macho_Shortcode_Manager::VERSION, false );
			wp_enqueue_script( 'macho-jquery.chosen', MSM_JS_TINYMCE_URI . '/chosen.jquery.min.js', false, Macho_Shortcode_Manager::VERSION, false );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'macho-tooltip', MSM_JS_URI . '/tooltip.min.js', false, Macho_Shortcode_Manager::VERSION, false );

			wp_enqueue_script( 'macho-popup', MSM_JS_TINYMCE_URI . '/popup.js', false, Macho_Shortcode_Manager::VERSION, true );
			wp_localize_script(
				'macho-popup', 'MachoShortcodesManager', array(
					'plugin_folder' => get_template_directory_uri() . '/includes/libraries/macho-shortcode-manager/assets/',
				)
			);

		}

		/**
		 * Popup function which will show shortcode options in thickbox.
		 *
		 * @return void
		 */
		function popup() {

			require_once( 'msm-popup.php' );
			die();

		}
	}
}// End if().
