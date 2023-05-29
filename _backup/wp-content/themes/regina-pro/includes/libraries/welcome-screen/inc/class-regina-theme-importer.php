<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Don't duplicate me!
if ( ! class_exists( 'Regina_Theme_Importer' ) ) {

	class Regina_Theme_Importer {


		/**
		 * Holds a copy of the object for easy reference.
		 *
		 * @since 0.0.2
		 *
		 * @var object
		 */
		public $theme_options_file;

		/**
		 * Holds a copy of the object for easy reference.
		 *
		 * @since 0.0.2
		 *
		 * @var object
		 */
		public $widgets;

		/**
		 * Holds a copy of the object for easy reference.
		 *
		 * @since 0.0.2
		 *
		 * @var object
		 */
		public $content_demo;

		/**
		 * Flag imported to prevent duplicates
		 *
		 * @since 0.0.3
		 *
		 * @var array
		 */
		public $flag_as_imported = array(
			'content' => false,
			'menus'   => false,
			'widgets' => false,
		);

		/**
		 * imported sections to prevent duplicates
		 *
		 * @since 0.0.3
		 *
		 * @var array
		 */
		public $imported_demos = array();

		/**
		 * Flag imported to prevent duplicates
		 *
		 * @since 0.0.3
		 *
		 * @var bool
		 */
		public $add_admin_menu = true;

		/**
		 * Holds a copy of the object for easy reference.
		 *
		 * @since 0.0.2
		 *
		 * @var object
		 */
		private static $instance;

		/**
		 * Constructor. Hooks all interactions to initialize the class.
		 *
		 * @since 0.0.2
		 */
		public function __construct() {

						self::$instance = $this;

			// Folder path
			$this->demo_files_path = get_template_directory() . '/demo-content/';

			// Theme Options File Name
			$this->theme_options_file_name = 'theme_options.dat';

			// Widgets File Name
			$this->widgets_file_name = 'widgets.json';

			// Content File Name
			$this->content_demo_file_name = 'content.xml';

			// Apply filets
			$this->demo_files_path = apply_filters( 'mt_theme_importer_demo_files_path', $this->demo_files_path );

			$this->theme_options_file = apply_filters( 'mt_theme_importer_theme_options_file', $this->demo_files_path . $this->theme_options_file_name );

			$this->widgets = apply_filters( 'mt_theme_importer_widgets_file', $this->demo_files_path . $this->widgets_file_name );

			$this->content_demo = apply_filters( 'mt_theme_importer_content_demo_file', $this->demo_files_path . $this->content_demo_file_name );

			add_filter( 'add_post_metadata', array( $this, 'check_previous_meta' ), 10, 5 );

		}


		/*
		 * Avoids adding duplicate meta causing arrays in arrays from WP_importer
		 *
		 *
		 * @since 0.0.2
		 *
		 */
		public function check_previous_meta( $continue, $post_id, $meta_key, $meta_value, $unique ) {

			$old_value = get_metadata( 'post', $post_id, $meta_key );

			if ( count( $old_value ) == 1 ) {

				if ( $old_value[0] === $meta_value ) {

					return false;

				} elseif ( $old_value[0] !== $meta_value ) {

					update_post_meta( $post_id, $meta_key, $meta_value );
					return false;

				}
			}

		}

		/**
		 * Add Panel Page
		 *
		 * @since 0.0.2
		 */
		public function after_wp_importer() {
			update_option( 'regina_pro_imported_demo', $this->flag_as_imported );
		}


		/**
		 * Process all imports
		 *
		 * @params $content
		 * @params $options
		 * @params $options
		 * @params $widgets
		 *
		 * @since 0.0.3
		 *
		 * @return null
		 */
		public function process_imports( $content = true, $options = true, $menus = true, $widgets = true, $reading_options = false ) {

			if ( $content && ! empty( $this->content_demo ) && is_file( $this->content_demo ) ) {
				$this->set_demo_data( $this->content_demo );
			}

			if ( $menus ) {
				$this->set_demo_menus();
			}

			if ( $reading_options ) {
				$this->set_reading_options();
			}

			if ( $widgets && ! empty( $this->widgets ) && is_file( $this->widgets ) ) {
				$this->process_widget_import_file( $this->widgets );
			}

			$this->after_wp_importer();

		}

		/**
		 * add_widget_to_sidebar Import sidebars
		 * @param  string $sidebar_slug    Sidebar slug to add widget
		 * @param  string $widget_slug     Widget slug
		 * @param  string $count_mod       position in sidebar
		 * @param  array  $widget_settings widget settings
		 *
		 * @since 0.0.2
		 *
		 * @return null
		 */
		public function add_widget_to_sidebar( $sidebar_slug, $widget_slug, $count_mod, $widget_settings = array() ) {

			$sidebars_widgets = get_option( 'sidebars_widgets' );

			if ( ! isset( $sidebars_widgets[ $sidebar_slug ] ) ) {
				$sidebars_widgets[ $sidebar_slug ] = array(
					'_multiwidget' => 1,
				);
			}

			$new_widgets = get_option( 'widget_' . $widget_slug );

			if ( ! is_array( $new_widgets ) ) {
				$new_widgets = array();
			}

			$count                               = count( $new_widgets ) + 1 + $count_mod;
			$sidebars_widgets[ $sidebar_slug ][] = $widget_slug . '-' . $count;

			$new_widgets[ $count ] = $widget_settings;

			update_option( 'sidebars_widgets', $sidebars_widgets );
			update_option( 'widget_' . $widget_slug, $new_widgets );

		}

		public function set_demo_data( $file ) {

			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true );
			}

			require_once ABSPATH . 'wp-admin/includes/import.php';

			$importer_error = false;

			if ( ! class_exists( 'WP_Importer' ) ) {

				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

				if ( file_exists( $class_wp_importer ) ) {

					require_once( $class_wp_importer );

				} else {

					$importer_error = true;

				}
			}

			if ( ! class_exists( 'WP_Import' ) ) {

				$class_wp_import = dirname( __FILE__ ) . '/class-wp-import.php';

				if ( file_exists( $class_wp_import ) ) {
					require_once( $class_wp_import );
				} else {
					$importer_error = true;
				}
			}

			if ( $importer_error ) {

				die( 'Error on import' );

			} else {

				if ( ! is_file( $file ) ) {

					echo "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the WordPress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ";

				} else {

					$wp_import                    = new WP_Import();
					$wp_import->fetch_attachments = true;
					$wp_import->import( $file );
					$this->flag_as_imported['content'] = true;

				}
			}

		}

		public function set_demo_menus() {

			// create the nav menus and insert items into them (all sections will be defaultly linked in the menu)
			$menuname       = 'Regina Primary Menu';
			$mtmenulocation = 'primary';

			// Does the menu exist already?
			$menu_exists = wp_get_nav_menu_object( $menuname );

			// If it doesn't exist, let's create it.
			if ( $menu_exists ) {
				if ( ! has_nav_menu( $mtmenulocation ) ) {
					$locations                    = get_theme_mod( 'nav_menu_locations' );
					$locations[ $mtmenulocation ] = $menu_exists->term_id;
					set_theme_mod( 'nav_menu_locations', $locations );
				}
			}

			// create the nav menus and insert items into them (all sections will be defaultly linked in the menu)
			$menuname       = 'Regina Footer Menu';
			$mtmenulocation = 'footer';

			// Does the menu exist already?
			$menu_exists = wp_get_nav_menu_object( $menuname );

			// If it doesn't exist, let's create it.
			if ( $menu_exists ) {
				if ( ! has_nav_menu( $mtmenulocation ) ) {
					$locations                    = get_theme_mod( 'nav_menu_locations' );
					$locations[ $mtmenulocation ] = $menu_exists->term_id;
					set_theme_mod( 'nav_menu_locations', $locations );
				}
			}

			$this->flag_as_imported['menus'] = true;
		}

		public function set_reading_options() {
			// delete default posts
			$post = get_page_by_path( 'hello-world', OBJECT, 'post' );
			if ( $post ) {
				wp_delete_post( $post->ID, true );
			}

			// delete default page
			$page = get_page_by_path( 'sample-page', OBJECT, 'page' );
			if ( $page ) {
				wp_delete_post( $page->ID, true );
			}

			// new pages to be created
			$new_pages = array(
				'Front Page',
				'Blog',
			);

			foreach ( $new_pages as $new_page_title ) {

				//don't change the code bellow, unless you know what you're doing

				$page_check = get_page_by_title( $new_page_title );

				$new_page = array(
					'post_type'   => 'page',
					'post_title'  => $new_page_title,
					'post_status' => 'publish',
					'post_author' => 1,
				);

				if ( ! isset( $page_check->ID ) ) {
					$new_page_id = wp_insert_post( $new_page );
				}
			}

			// Start creating the menus & reading options
			$homepage  = get_page_by_title( 'Front Page' );
			$blog_page = get_page_by_title( 'Blog' );

			// update reading options to newly created pages
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $homepage->ID );
			update_option( 'page_for_posts', $blog_page->ID );

			// update permalink structure
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );

			$this->flag_as_imported['reading_options'] = true;

		}

		/**
		 * Available widgets
		 *
		 * Gather site's widgets into array with ID base, name, etc.
		 * Used by export and import functions.
		 *
		 * @since 0.0.2
		 *
		 * @global array $wp_registered_widget_updates
		 * @return array Widget information
		 */
		function available_widgets() {

			global $wp_registered_widget_controls;

			$widget_controls = $wp_registered_widget_controls;

			$available_widgets = array();

			foreach ( $widget_controls as $widget ) {

				if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) { // no dupes
					$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
					$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];

				}
			}

			return $available_widgets;

		}


		/**
		 * Process import file
		 *
		 * This parses a file and triggers importation of its widgets.
		 *
		 * @since 0.0.2
		 *
		 * @param string $file Path to .wie file uploaded
		 * @global string $widget_import_results
		 */
		function process_widget_import_file( $file ) {

			// File exists?
			if ( ! file_exists( $file ) ) {
				wp_die(
					__( 'Widget Import file could not be found. Please try again.', 'regina' ), '', array(
						'back_link' => true,
					)
				);
			}

			// Get file contents and decode
			$response = wp_remote_get(
				get_template_directory_uri() . '/demo-content/' . $this->widgets_file_name, array(
					'timeout' => 15,
				)
			);

			if ( ! is_wp_error( $response ) && isset( $response['response']['code'] ) && 200 === $response['response']['code'] ) {
				$data = wp_remote_retrieve_body( $response );
			} else {
				$data = '';
			}

			$data = json_decode( $data );

			// Import the widget data
			// Make results available for display on import/export page
			$this->widget_import_results = $this->import_widgets( $data );

		}


		/**
		 * Import widget JSON data
		 *
		 * @since 0.0.2
		 * @global array $wp_registered_sidebars
		 * @param object $data JSON widget data from .json file
		 * @return array Results array
		 */
		public function import_widgets( $data ) {

			global $wp_registered_sidebars;

			// Have valid data?
			// If no data or could not decode
			if ( empty( $data ) || ! is_object( $data ) ) {
				return;
			}

			// Hook before import
			$data = apply_filters( 'mt_theme_import_widget_data', $data );

			// Get all available widgets site supports
			$available_widgets = $this->available_widgets();

			// Get all existing widget instances
			$widget_instances = array();
			foreach ( $available_widgets as $widget_data ) {
				$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
			}

			// Begin results
			$results = array();

			// Loop import data's sidebars
			foreach ( $data as $sidebar_id => $widgets ) {

				// Skip inactive widgets
				// (should not be in export file)
				if ( 'wp_inactive_widgets' == $sidebar_id ) {
					continue;
				}

				// Check if sidebar is available on this site
				// Otherwise add widgets to inactive, and say so
				if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
					$sidebar_available    = true;
					$use_sidebar_id       = $sidebar_id;
					$sidebar_message_type = 'success';
					$sidebar_message      = '';
				} else {
					$sidebar_available    = false;
					$use_sidebar_id       = 'wp_inactive_widgets'; // add to inactive if sidebar does not exist in theme
					$sidebar_message_type = 'error';
					$sidebar_message      = __( 'Sidebar does not exist in theme (using Inactive)', 'regina' );
				}

				// Result for sidebar
				$results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID
				$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
				$results[ $sidebar_id ]['message']      = $sidebar_message;
				$results[ $sidebar_id ]['widgets']      = array();

				// Loop widgets
				foreach ( $widgets as $widget_instance_id => $widget ) {

					$fail = false;

					// Get id_base (remove -# from end) and instance ID number
					$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
					$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

					// Does site support this widget?
					if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
						$fail                = true;
						$widget_message_type = 'error';
						$widget_message      = __( 'Site does not support widget', 'regina' ); // explain why widget not imported
					}

					// Filter to modify settings before import
					// Do before identical check because changes may make it identical to end result (such as URL replacements)
					$widget = apply_filters( 'mt_theme_import_widget_settings', $widget );

					// Does widget with identical settings already exist in same sidebar?
					if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

						// Get existing widgets in this sidebar
						$sidebars_widgets = get_option( 'sidebars_widgets' );
						$sidebar_widgets  = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); // check Inactive if that's where will go

						// Loop widgets with ID base
						$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
						foreach ( $single_widget_instances as $check_id => $check_widget ) {

							// Is widget in same sidebar and has identical settings?
							if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {

								$fail                = true;
								$widget_message_type = 'warning';
								$widget_message      = __( 'Widget already exists', 'regina' ); // explain why widget not imported

								break;

							}
						}
					}

					// No failure
					if ( ! $fail ) {

						// Add widget instance
						$single_widget_instances   = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time
						$single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array(
							'_multiwidget' => 1,
						); // start fresh if have to
						$single_widget_instances[] = (array) $widget; // add it

						// Get the key it was given
						end( $single_widget_instances );
						$new_instance_id_number = key( $single_widget_instances );

						// If key is 0, make it 1
						// When 0, an issue can occur where adding a widget causes data from other widget to load, and the widget doesn't stick (reload wipes it)
						if ( '0' === strval( $new_instance_id_number ) ) {
							$new_instance_id_number                             = 1;
							$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
							unset( $single_widget_instances[0] );
						}

						// Move _multiwidget to end of array for uniformity
						if ( isset( $single_widget_instances['_multiwidget'] ) ) {
							$multiwidget = $single_widget_instances['_multiwidget'];
							unset( $single_widget_instances['_multiwidget'] );
							$single_widget_instances['_multiwidget'] = $multiwidget;
						}

						// Update option with new widget
						update_option( 'widget_' . $id_base, $single_widget_instances );

						// Assign widget instance to sidebar
						$sidebars_widgets                      = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time
						$new_instance_id                       = $id_base . '-' . $new_instance_id_number; // use ID number from new widget instance
						$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id; // add new instance to sidebar
						update_option( 'sidebars_widgets', $sidebars_widgets ); // save the amended data

						// Success message
						if ( $sidebar_available ) {
							$widget_message_type = 'success';
							$widget_message      = __( 'Imported', 'regina' );
						} else {
							$widget_message_type = 'warning';
							$widget_message      = __( 'Imported to Inactive', 'regina' );
						}
					}// End if().

					// Result for widget instance
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name']         = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; // widget name or ID if name not available (not supported by site)
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = $widget->title ? $widget->title : __( 'No Title', 'regina' ); // show "No Title" if widget instance is untitled
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
					$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;

				}// End foreach().
			}// End foreach().

			$this->flag_as_imported['widgets'] = true;

			// Return results
			return $results;

		}



	} //class

} // End if().


