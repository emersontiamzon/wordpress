<?php
/**
 * Macho_Taxonomy
 *
 * @package Macho
 * @since 1.0.2
 * @version 1.0.0
 */

/**
 * We must add these to ensure they get flushed on any change in the $taxonomies array. Called statically so its only done once.
 */
add_action( 'admin_init', array('Macho_Taxonomy', 'flush_rewrite_rules') );

/**
 * Register the taxonomies statically so its does all registered in 1. Its done at priority 8 to play nicely with Macho_Post_Type
 */
add_action( 'init', array('Macho_Taxonomy', 'register_taxonomies'), 9 );

/**
 * Macho_Taxonomy simple class for creating taxonomies
 */
class Macho_Taxonomy extends Macho_Base{
    
    /**
     * @var string $version Class version.
     */
    public $version = '1.0.0';
    
    /**
     * @var array $taxonomies. Used to store all to be registered taxonomies.
     */
    public static $taxonomies = array();

    /**
     * @var string $taxonomy. The taxonomy name.
     */
    public static $taxonomy = '';
    
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
            'labels'               => $this->parse_args(array(), $this->default_labels(), 'macho/taxonomy/labels'),
            'description'           => '',
            'public'                => true,
            'hierarchical'          => false,
            'show_ui'               => true,//default is false, but it can be overridden
            'show_in_menu'          => null,
            'show_in_nav_menus'     => null,
            'show_tagcloud'         => null,
            'meta_box_cb'           => null,
            'capabilities'          => array(),
            'rewrite'               => true,
            'query_var'             => true, //this is not in the default to allow you to override it
            'update_count_callback' => '',
            'post_types' => array(),
      			'templates' => array(
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
    	$name = ucwords(str_replace('_', ' ', self::$taxonomy));
      return array(
        'name' => sprintf(__('%ss', 'regina'), $name),
        'singular_name' => $name,
        'search_items' => sprintf(__('Search %ss', 'regina'), $name),
        'popular_items' => sprintf(__('Popular %ss', 'regina'), $name),
        'all_items' => sprintf(__('All %ss', 'regina'), $name),
        'parent_item' => sprintf(__('Parent %ss', 'regina'), $name),
        'parent_item_colon' => sprintf(__('Parent %s:', 'regina'), $name),
        'edit_item' => sprintf(__('Edit %s', 'regina'), $name),
        'view_item' => sprintf(__('View %s', 'regina'), $name),
        'update_item' => sprintf(__('Update %s', 'regina'), $name),
        'add_new_item' => sprintf(__('Add New %s', 'regina'), $name),
        'new_item_name' => sprintf(__('Add New %s Name', 'regina'), $name),
        'separate_items_with_commas' => sprintf(__('Seperate %ss with commas', 'regina'), strtolower($name)),
        'add_or_remove_items' => sprintf(__('Add or remove %ss', 'regina'), strtolower($name)),
        'choose_from_most_used' => sprintf(__('Choose from the most used %ss', 'regina'), strtolower($name)),
        'not_found' => sprintf(__('No %ss found', 'regina'), strtolower($name)),
      );  
    }

    /**
      * Called on class instance creation, adds the new taxonomy to the static $taxonomies array for registering at the correct points.
      *
      * Replaces spaces and dashes with underscores in the taxonomy name but doesnt run sanitize_key() as register_taxonomy() does this already and we would just be adding proccessing time.
      * Trims post type name length to 20 chars at most.
      *
      * @uses Macho_Taxonomy::parse_args();
      * @uses apply_filters();
      *
      * @since 1.0.0
      *
      * @return none
      *
      */
    public function __construct($taxonomy = '', $args = array()){
    	self::$taxonomy = apply_filters('macho/taxonomy/name', strtolower(substr(str_replace(array(' ', '-'), '_', $taxonomy), 0, 32)));
    	//pass to internal args property
    	$this->args = $this->parse_args( $args, $this->default_args(), 'macho/taxonomy/args' );
    	//add it to the $post_types array
    	self::$taxonomies[self::$taxonomy] = $this->args;

    	//add template locate filter is we have paths
    	if($this->args['templates']['archive']['path'] != false){
    		add_filter('template_include', $this->provide('locate_template'));
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

      if(is_tax($this->taxonomy) && $this->args['templates']['archive']['path'] != false){
        $theme_files = array('taxonomy-'.$this->taxonomy.'.php');
        $exists_in_theme = locate_template($theme_files, false);
        if ( $exists_in_theme != '' && $this->args['templates']['archive']['override'] == false) {
          $template = $exists_in_theme;
        } else {
          $template = $this->args['templates']['archive']['path'];
        }
      }
      return $template;
  	}

    /**
      * Called during a rewrite flush, and on init to register the taxonomy in the static $taxonomies array.
      *
      * @uses register_post_type();
      *
      * @since 1.0.0
      *
      * @return none
      *
      */
    public static function register_taxonomies(){
    	foreach(self::$taxonomies as $taxonomy => $args){
    		register_taxonomy($taxonomy, $args['post_types'], $args);//set link to null, we will add it later
    	}
    }

    /**
      * Registers taxonomies from the static $taxonomies array and flushes the rewrite rules. This is done on admin init, and only if new taxonomies exist in the option.
      *
      * This is quite neat because it checks for new taxonomies against the saved option, but only flushes if 1 or more hasnt already been added. It then only updates the option if flush == true.
      * Its done on admin init as activation of plugins/themes can only be done via the admin so there is no need to do it on normal init.
      *
      * @uses Macho_Post_Type::register_taxonomies();
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
    	$registered = get_option('macho_registered_taxonomies', array());
    	$new_registered = array();
    	foreach(self::$taxonomies as $taxonomy => $args){
    		$new_registered[] = $taxonomy;
    		if(!in_array($taxonomy, $registered)){
    			$flush = true;
    		}
    	}
    	//if any post types have been removed, flush as well
    	foreach($registered as $taxonomy){
    		if(!in_array($taxonomy, self::$taxonomies)){
    			$flush = true;
    		}
    	}
    	if($flush == true){
    		self::register_taxonomies();
    		flush_rewrite_rules();
    		update_option('macho_registered_taxonomies', $new_registered);
    	}
    }
}