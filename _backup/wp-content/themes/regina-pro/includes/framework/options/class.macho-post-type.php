<?php
/**
 * Macho_Post_Type
 *
 * @package Macho
 * @since 1.0.2
 * @version 1.0.1
 */

/**
 * We must add these to ensure they get flushed on any change in the $post_types array. Called statically so its only done once.
 */
add_action( 'init', array('Macho_Post_Type', 'flush_rewrite_rules'), 11 );

/**
 * register the post types statically so its does all registered in 1.
 */
add_action( 'init', array('Macho_Post_Type', 'register_post_types'), 10 );

/**
 * Update the notices to use the correct text, called statically in case the class is used on 100's of post types to prevent it being called over and over
 */
add_filter( 'post_updated_messages', array('Macho_Post_Type', 'post_update_messages') );

/**
 * Macho_Taxonomy simple class for creating post types
 */
class Macho_Post_Type extends Macho_Base{
    
    /**
     * @var string $version Class version.
     */
    public $version = '1.0.1';
    
    /**
     * @var array $post_types. Used to store all to be registered post types.
     */
    public static $post_types = array();

    /**
     * @var string $post_type. The post type name.
     */
    public $post_type = '';
    
    /**
     * @var array $args Class args to be run attached after <code>parse_args</code>.
     */
    public $args = array();
    
    /**
      * Function used across extended classes used in conjunction with <code>parse_args</code> to format supplied arrays and ensure all keys are supplied.
      *
      * @since 1.0.0
      *
      * @return array
      *
      */
    private function default_args(){
        //defaults taken from register_post_type() function. We set public to true instead of false as this is generally how it should be, can be changed on creation.
      return array(
            'labels'               => $this->parse_args(array(), $this->default_labels(), 'macho/post/type/labels'),
			'description'          => '',
			'public'               => true,
			'hierarchical'         => false,
			'exclude_from_search'  => null,
			'publicly_queryable'   => null,
			'show_ui'              => null,
			'show_in_menu'         => null,
			'show_in_nav_menus'    => null,
			'show_in_admin_bar'    => null,
			'menu_position'        => null,
			'menu_icon'            => null,
			'capability_type'      => 'post',
			'capabilities'         => array(),
			'map_meta_cap'         => null,
			'supports'             => array(),
			'register_meta_box_cb' => null,
			'taxonomies'           => array(),
			'has_archive'          => false,
			'rewrite'              => array('slug'=> $this->post_type),
			'query_var'            => true,
			'can_export'           => true,
			'delete_with_user'     => null,
			'_edit_link'           => 'post.php?post=%d',
			'messages' => array(
				0  => '', // Unused. Messages start at index 1.
				1  => __( '%s updated.', 'regina' ),
				2  => __( 'Custom field updated.', 'regina' ),
				3  => __( 'Custom field deleted.', 'regina' ),
				4  => __( '%s updated.', 'regina' ),
				5  => __( '%s restored to revision from %s', 'regina' ),
				6  => __( '%s published.', 'regina' ),
				7  => __( '%s saved.', 'regina' ),
				8  => __( '%s submitted.', 'regina' ),
				9  => __( '%s scheduled for: <strong>%s</strong>.', 'regina' ),
				10 => __( '%s draft updated.', 'regina' )
			),
			'disable_add_new' => false,
			'templates' => array(
				'single' => array(
					'override' => false,
					'path' => false
				),
				'archive' => array(
					'override' => false,
					'path' => false
				)
			),
        );
        
    }

    /**
      * Supplied some default labels with placeholders to be replaced during proccessing.
      *
      * @since 1.0.0
      *
      * @return array
      *
      */
    private function default_labels(){
    	$name = ucwords(str_replace('_', ' ', $this->post_type));
        return array(
            'name'               => sprintf(__('%ss', 'regina'), $name),
			'singular_name'      => $name,
			'menu_name'          => sprintf(__( '%ss', 'regina' ), $name),
			'name_admin_bar'     => sprintf(__( '%s', 'regina' ), $name),
			'add_new'            => __( 'Add New', 'regina' ),
			'add_new_item'       => sprintf(__( 'Add New %s', 'regina' ), $name),
			'new_item'           => sprintf(__( 'New %s', 'regina' ), $name),
			'edit_item'          => sprintf(__( 'Edit %s', 'regina' ), $name),
			'view_item'          => sprintf(__( 'View %s', 'regina' ), $name),
			'all_items'          => sprintf(__( 'All %ss', 'regina' ), $name),
			'search_items'       => sprintf(__( 'Search %ss', 'regina' ), $name),
			'parent_item_colon'  => sprintf(__( 'Parent %ss:', 'regina' ), $name),
			'not_found'          => sprintf(__( 'No %ss found.', 'regina' ), strtolower($name)),
			'not_found_in_trash' => sprintf(__( 'No %ss found in Trash.', 'regina' ), strtolower($name))
        );
        
    }

    /**
      * Called on class instance creation, adds the new post type to the static $post_types array for registering at the correct points.
      *
      * Replaces spaces and dashes with underscores in the post type name but doesnt run sanitize_key() as register_post_type() does this already and we would just be adding proccessing time.
      * Trims post type name length to 20 chars at most.
      *
      * @uses Macho_Post_Type::parse_args();
      * @uses apply_filters();
      *
      * @since 1.0.0
      *
      * @return none
      *
      */
    public function __construct($post_type = '', $args = array()){
    	$this->post_type = apply_filters('macho/post/type/name', strtolower(substr(str_replace(array(' ', '-'), '_', $post_type), 0, 20)));
    	//pass to internal args property
    	$this->args = $this->parse_args( $args, $this->default_args(), 'macho/post/type/args' );
    	//add it to the $post_types array
    	self::$post_types[$this->post_type] = $this->args;
    	//remove add new feature if needed
    	if($this->args['disable_add_new'] == true){
    		add_action('admin_menu', $this->provide('remove_add_new'));
    		add_action("load-post-new.php", $this->provide('block_add_new'));
    	}
    	//add template locate filter is we have paths
    	if($this->args['templates']['archive']['path'] != false || $this->args['templates']['single']['path'] != false){
    		add_filter('template_include', $this->provide('locate_template'));
    	}
    }

    /**
      * Removes the sub menu item for adding new posts to the post type.
      *
      * Only called if set, and removes the add new button in the header as well.
      *
      * @uses remove_submenu_page();
      *
      * @since 1.0.0
      *
      * @return none
      *
      */
    public function remove_add_new(){
    	remove_submenu_page( 'edit.php?post_type='.$this->post_type, 'post-new.php?post_type='.$this->post_type );
    	if (isset($_GET['post_type']) && $_GET['post_type'] == $this->post_type) {
	        echo '<style type="text/css">
	        .add-new-h2 { display:none; }
	        </style>';
	    }
    }

    /**
      * Blocks access of adding new posts to the post type.
      *
      * Only called if set, does a wp_redirect to the overview page.
      *
      * @uses wp_redirect();
      *
      * @since 1.0.0
      *
      * @return none
      *
      */
    public function block_add_new(){
    	if(isset($_GET['post_type']) && $_GET["post_type"] == $this->post_type){ 
        	wp_redirect("edit.php?post_type=".$this->post_type);
        }
    }

	/**
      * Returns the set template path if its either not found in the theme, or we want to override it.
      *
      * @uses is_post_type_archive();
      * @uses locate_template();
      * @uses get_post_type();
      *
      * @since 1.0.0
      *
      * @return string $template. The template path.
      *
      */
    public function locate_template($template){

		if ( is_post_type_archive($this->post_type) && $this->args['templates']['archive']['path'] != false) {
			$theme_files = array('archive-'.$this->post_type.'.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' && $this->args['templates']['archive']['override'] == false) {
				$template = $exists_in_theme;
			} else {
				$template = $this->args['templates']['archive']['path'];
			}
			return $template;
		}
		if(get_post_type() == $this->post_type && $this->args['templates']['single']['path'] != false && is_single()){
			$theme_files = array('single-'.$this->post_type.'.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' && $this->args['templates']['single']['override'] == false) {
				$template = $exists_in_theme;
			} else {
				$template = $this->args['templates']['single']['path'];
			}
			return $template;
		}

	    return $template;
	}

    /**
      * Called during a rewrite flush, and on init to register the post types in the static $post_types array.
      *
      * @uses register_post_type();
      *
      * @since 1.0.0
      *
      * @return none
      *
      */
    public static function register_post_types(){
    	foreach(self::$post_types as $post_type => $args){
    		//remove labels array if provided as simple type
    		if(isset($args['label']) && $args['label'] != ''){
    			unset($args['labels']);
    		}
    		register_post_type($post_type, $args);
    		foreach($args['taxonomies'] as $taxonomy){
	          register_taxonomy_for_object_type( $taxonomy, $post_type );
	        }
    	}
    }

    /**
      * Registers post types from the static $post_types array and flushes the rewrite rules. This is done on admin init, and only if new post types exist in the option.
      *
      * This is quite neat because it checks for new post types against the saved option, but only flushes if 1 or more hasnt already been added. It then only updates the option if flush == true.
      * Its done on admin init as activation of plugins/themes can only be done via the admin so there is no need to do it on normal init.
      *
      * @uses Macho_Post_Type::register_post_types();
      * @uses flush_rewrite_rules();
      * @uses get_option();
      *
      * @since 1.0.0
      *
      * @return none
      *
      */
    public static function flush_rewrite_rules(){
    	$flush = false;
    	$registered = get_option('macho_registered_post_types', array());
      $registeredKey = array_keys($registered);
    	$new_registered = array();
    	foreach(self::$post_types as $post_type => $args){
    		$new_registered[] = $post_type;
    		if( ! in_array( $post_type, $registeredKey ) ){
    			$flush = true;
    		}elseif ( $registered[$post_type]['rewrite']['slug'] != $args['rewrite']['slug'] ) {
          $flush = true;
        }
      }
    	//if any post types have been removed, flush as well
    	foreach($registeredKey as $post_type){
    		if(!in_array($post_type, array_keys(self::$post_types))){
    			$flush = true;
    		}
    	}
    	if($flush == true){
    		self::register_post_types();
    		flush_rewrite_rules();
    		update_option('macho_registered_post_types', self::$post_types);
    	}
    }

    /**
      * Returns any custom update messages for the post type notices.
      *
      * WordPress should really allow you to do this in the labels arg of registering the post type, but hey its easy enough to work round.
      * This function exits if it isnt a framework created post type, or provides the messages from the default array, or user supplied if it is.
      *
      * @uses get_post();
      * @uses get_post_type();
      * @uses get_post_type_object();
      * @uses wp_post_revision_title();
      * @uses esc_url();
      * @uses add_query_arg();
      *
      * @since 1.0.0
      *
      * @return array $messages
      *
      */
    public static function post_update_messages($messages = array()){

    	$post             = get_post();
  		$post_type        = get_post_type( $post );
  		$post_type_object = get_post_type_object( $post_type );

      if(!isset(self::$post_types[$post_type])){
        return $messages;
      }
  		$_messages = self::$post_types[$post_type]['messages'];

  		//create self to get text domain
  		$self = new Macho_Base;

  		$messages[$post_type] = array(
  			0  => '', // Unused. Messages start at index 1.
  			1  => sprintf($_messages[1], $post_type_object->labels->singular_name),
  			2  => $_messages[2],
  			3  => $_messages[3],
  			4  => sprintf($_messages[4], $post_type_object->labels->singular_name),
  			/* translators: %s: date and time of the revision */
  			5  => isset( $_GET['revision'] ) ? sprintf( $messages[5], $post_type_object->labels->singular_name, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
  			6  => sprintf($_messages[6], $post_type_object->labels->singular_name),
  			7  => sprintf($_messages[7], $post_type_object->labels->singular_name),
  			8  => sprintf($_messages[8], $post_type_object->labels->singular_name),
  			9  => sprintf(
  				$_messages[9],
  				$post_type_object->labels->singular_name,
  				date_i18n( __( 'M j, Y @ G:i', 'regina' ), strtotime( $post->post_date ) )
  			),
  			10 => sprintf($_messages[10], $post_type_object->labels->singular_name)
  		);

  		if ( $post_type_object->publicly_queryable ) {
  			$permalink = get_permalink( $post->ID );

  			$view_link = sprintf( ' <a href="%s">%s %s</a>', esc_url( $permalink ), __( 'View', 'regina' ), strtolower($post_type_object->labels->singular_name) );
  			$messages[ $post_type ][1] .= $view_link;
  			$messages[ $post_type ][6] .= $view_link;
  			$messages[ $post_type ][9] .= $view_link;

  			$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
  			$preview_link = sprintf( ' <a target="_blank" href="%s">%s %s</a>', esc_url( $preview_permalink ), __( 'Preview', 'regina' ), strtolower($post_type_object->labels->singular_name) );
  			$messages[ $post_type ][8]  .= $preview_link;
  			$messages[ $post_type ][10] .= $preview_link;
  		}

    	return $messages;

    }
}