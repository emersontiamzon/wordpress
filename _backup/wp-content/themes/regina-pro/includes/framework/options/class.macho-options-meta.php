<?php
/**
 * Macho_Options_Meta
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.2
 */

/**
 * Macho_Options_Meta. Create and stores fields and sections.
 */
class Macho_Options_Meta extends Macho_Options{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.2';
    
    /**
     * __construct() parse arguments supplied, setup framework options class.
     *
     * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
     * @uses Macho_Options::prepare_sections(); to prepare data.
     * @uses Macho_Options::provide();
     * @uses add_action();
     * @uses add_filter();
     *
     * @since 1.0.0
     *
     * @param array $args framework setup arguments. Used to change some base settings for the options including context.
     *
     * @param array $sections the sections an fields to be used.
     *
     * @param object $page if suppplied should be an instance of Macho_Page and used to render the meatboxes on none metabox pages.
     *
     * @return none
     */
    public function __construct( $args = array(), $sections = array() ){
        
        $this->args = $this->parse_args( $args, $this->default_args(), 'macho/options/meta/args' );
        
        //prepare sections and fields before merging values
        $sections = $this->parse_args( $sections, array(), 'macho/options/meta/'.$this->args['option_name'].'/sections' );
        
        //save user data about layouts used - this is global
        add_action('wp_ajax_options_save_layout', $this->provide('save_screen_settings'));
        
        $this->prepare_sections($sections);
        foreach($this->args['post_types'] as $post_type){
            add_action('add_meta_boxes_'.$post_type, $this->provide('load_meta'));
        }
        add_action('load-post-new.php', $this->provide('load_post_page'));
        add_action('load-post.php', $this->provide('load_post_page'));
        
        //save meta data
        add_action('save_post', $this->provide('save_meta'), 10, 2);
            
    }
    
    /**
      * Returns the default arguments for the $args property.
      *
      * This gets merged with user supplied array via <code>parse_args</code>.
      *
      * @since 1.0.0
      *
      * @return array
      */
    protected function default_args(){
        
        return array(
            'option_name'   => 'option_name',
            'sections'      => array(),
            'post_types'    => array(),
            'default_layout' => 'options-normal'
        );
        
    }
    
    /**
      * Loops through supplied data and prepares the $sections array.
      *
      * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
      * @uses Macho_Options::get_default_values(); to merge default values with the saved values if not set.
      * @uses Macho_Options::prepare_fields(); to prepare the nested fields contained in the supplied array.
      * @uses sanitize_key(); to sanitize the section ID.
      *
      * @since 1.0.0
      *
      * @param array $sections framework setup arguments. Used to change some base settings for the options including context.
      *
      * @param object $id if suppplied should be an instance of Macho_Page and used to render the meatboxes on none metabox pages.
      *
      * @return none
      */
    protected function prepare_sections($sections, $id = null, $context = null){
        
        $this->options = get_post_meta( $id, $this->args['option_name'], true );
        if(!$this->options || $this->options == ''){
            $this->options = $this->get_default_values();
        }
        
        foreach($sections as $key => $section){
            $key = sanitize_key($key);
            $this->sections[$key] = $this->parse_args( $section, $this->section_args() );
            $this->sections[$key] = $this->prepare_tabs($this->sections[$key]);
            $fields = $this->sections[$key]['fields'];
            unset($this->sections[$key]['fields']);
            $this->sections[$key]['fields'] = $this->prepare_fields($fields, $this->options, $key);
        }
        
    }
    
    /**
     * Hooks into the load post page so we can add a filter to the admn notices just on these pages, which in turn we use to add icons to the metabox titles.
     *
     * Its important this is done here and not before or we will see icons in the screen options menu.
     *
     * @uses add_action();
     * @uses Macho_Options::provide();
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function load_post_page(){
        //wierd hook place to reformat section titles, but hey it works
        add_action('admin_notices', $this->provide('setup_meta_box_titles'));
    }
    
    /**
     * The <code>load_meta</code> function is a replication of the <code>load_page</code> function, but tailored for adding meta boxes to post and cpt pages only.
     *
     * In the function we prepare the sections, enqueue styles and scripts, localize scripts. We then add metaboxes and add screen settings.
     *
     * @uses wp_enqueue_style();
     * @uses wp_enqueue_script();
     * @uses wp_localize_script();
     * @uses add_meta_box();
     * @uses get_current_screen();
     * @uses add_filter();
     * @uses do_action();
     * @uses Macho_Options::provide();
     * @uses Macho_Options::prepare_sections();
     *
     * @since 1.0.0
     *
     * @param object $post WP_Post object
     *
     * @return none
     */
    public function load_meta($post){
        
        //re run prepare sections to ensure the options are set
        $this->prepare_sections($this->sections, $post->ID);
        
        $this->load_assets();
        
        $this->localize_script(array(
            'context' => 'meta',
            'rel_id' => $post->ID
        ));
        
        foreach($this->sections as $key => $section){
            add_meta_box( $key, $section['title'], $this->provide( 'box_content' ), get_current_screen(), $section['context'], $section['priority'], $key );
            add_filter('postbox_classes_'.get_current_screen()->id.'_'.$key, $this->provide('remove_box_padding'));
        }

        foreach($this->sections as $key => $section){
            foreach($section['fields'] as $k => $field){
                if(isset(static::$field_types[$field['type']])){
                    do_action('macho/options/field/'.$field['type'].'/enqueue', $field, static::$field_types[$field['type']]);
                }
            }
        }
        add_filter('screen_settings', $this->provide('add_screen_settings'));
        
    }
    
    /**
     * The <code>save_meta</code> function is hooked on the save post action and will be supplied with the $post_id and $post object.
     *
     * We use this to save the data supplied by the meta boxes into post meta values.
     *
     * @uses wp_is_post_autosave();
     * @uses wp_is_post_revision();
     * @uses get_post_type_object();
     * @uses current_user_can();
     * @uses check_admin_referer();
     * @uses delete_post_meta();
     * @uses update_post_meta();
     * @uses Macho_Options::remove_clones();
     *
     * @since 1.0.0
     *
     * @param integar $post_id ID of the post
     *
     * @param object $post WP_Post object
     *
     * @return none
     */
    public function save_meta($post_id = '', $post){
        
        // Checks save status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
     
        // Exits script depending on save status
        if ( $is_autosave || $is_revision ) {
            return;
        }
        
        /* Get the post type object. */
        $post_type = get_post_type_object( $post->post_type );
    
        /* Check if the current user has permission to edit the post. */
        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
            return $post_id;
        
        //we have options
        if(isset($_POST[$this->args['option_name']])){
            check_admin_referer('save_'.$this->args['option_name'], $this->args['option_name'].'_nonce');
            delete_post_meta($post_id, $this->args['option_name']);
            $new_meta_value = apply_filters('macho/options/save', $this->remove_clones($_POST[$this->args['option_name']]));
            update_post_meta( $post_id, $this->args['option_name'], $new_meta_value );
            //save seperate fields if needed
            foreach($this->sections as $section){
                foreach($section['fields'] as $id => $field){
                    if($field['seperate'] === true && isset($new_meta_value[$id]) ){
                        delete_post_meta($post_id, $this->args['option_name'].'_'.$id);
                        update_post_meta( $post_id, $this->args['option_name'].'_'.$id, $new_meta_value[$id] );
                    }
                }
            }
        }
    }
    
}