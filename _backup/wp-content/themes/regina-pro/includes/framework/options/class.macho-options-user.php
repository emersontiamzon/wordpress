<?php
/**
 * Macho_Options_User
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.1
 */

/**
 * Macho_Options_User. Create and stores fields and sections.
 */
class Macho_Options_User extends Macho_Options{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.1';
    
    /**
     * __construct() parse arguments supplied, setup framework depending on the $context supplied.
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
        
        $this->args = $this->parse_args( $args, $this->default_args(), 'macho/options/user/args' );
        
        //prepare sections and fields before merging values
        $sections = $this->parse_args( $sections, array(), 'macho/options/user/'.$this->args['option_name'].'/sections' );
        
        $this->prepare_sections($sections, 'user');
        add_action('show_user_profile', $this->provide('render_user'));
        add_action('edit_user_profile', $this->provide('render_user'));
        add_action('admin_enqueue_scripts', $this->provide('load_user'));
        add_action('admin_enqueue_scripts', $this->provide('load_user'));
        add_action('edit_user_profile_update', $this->provide('save_user'));
        add_action('personal_options_update', $this->provide('save_user'));
            
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
    protected function prepare_sections($sections, $id = null){
        
        if($id){
            $this->options = get_user_meta( $id, $this->args['option_name'], true );
        }
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
     * The <code>load_user</code> function is a replication of the <code>load_page</code> function, but tailored for adding fields to profile and user pages only.
     *
     * In the function we prepare the sections, enqueue styles and scripts, localize scripts. We then add fields.
     *
     * @uses wp_enqueue_style();
     * @uses wp_enqueue_script();
     * @uses wp_localize_script();
     * @uses do_action();
     *
     * @since 1.0.0
     *
     * @param object $post WP_Post object
     *
     * @return none
     */
    public function load_user(){
        
        $screen = get_current_screen();
        if($screen->id != 'profile' && $screen->id != 'user-edit'){return;}
        
        $this->load_assets();

        //get user id
        $rel_id = '';
        if($screen->id == 'profile'){
        	$current_user = wp_get_current_user();
        	$rel_id = $current_user->ID;
        }else{
        	$rel_id = $_REQUEST['user_id'];
        }
        
        $this->localize_script(array(
            'context' => 'user',
            'rel_id' => $rel_id
        ));
        
        foreach($this->sections as $key => $section){
            foreach($section['fields'] as $k => $field){
                if(isset(static::$field_types[$field['type']])){
                    do_action('macho/options/field/'.$field['type'].'/enqueue', $field, static::$field_types[$field['type']]);
                }
            }
        }
        
    }
    
    /**
     * Save the submitted user meta details.
     *
     * In the function we prepare the sections, save submitted data and wait.....
     *
     * @uses delete_user_meta();
     * @uses update_user_meta();
     * @uses check_admin_refferer();
     * @uses wp_die();
     * @uses add_action();
     * @uses Macho::prepare_sections();
     *
     * @since 1.0.0
     *
     * @param integar $user_id saved user id
     *
     * @return none
     */
    public function save_user($user_id){
        
        //we have options
        if(isset($_POST[$this->args['option_name']])){
                check_admin_referer('update-user_' . $user_id);
    
            if ( !current_user_can('edit_user', $user_id) )
                wp_die(__('You do not have permission to edit this user.', 'regina'));
            
            delete_user_meta($user_id, $this->args['option_name']);
            $new_meta_value = apply_filters('macho/options/save', $this->remove_clones($_POST[$this->args['option_name']]));
            update_user_meta( $user_id, $this->args['option_name'], $new_meta_value );
            //save seperate fields if needed
            foreach($this->sections as $section){
                foreach($section['fields'] as $id => $field){
                    if($field['seperate'] === true){
                    	delete_user_meta($user_id, $this->args['option_name'].'_'.$id);
                    	update_user_meta( $user_id, $this->args['option_name'].'_'.$id, $new_meta_value[$id] );
                    }
                }
            }
            add_action('admin_notices', $this->provide('settings_updated'));
        }
        
        //re run prepare sections to ensure the options are set
        $this->prepare_sections($this->sections, 'user', $user_id);
        
    }
    
    /**
     * Display a settings updated notice on the <code>admin_notices</code> action for user saves.
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function settings_updated(){
        echo '<div class="updated"><p><strong>'.__('User Settings Saved.', 'regina').'</strong></p></div>';
    }
    
    /**
     * Render table and fields for user profiles
     *
     * @since 1.0.0
     *
     * @param object|null $user WP_User object
     *
     * @return none
     */
    public function render_user( $user = null ){
        if(!$user){
            $user = wp_get_current_user();
        }
        $this->prepare_sections( $this->sections, $user->ID);
        
        foreach($this->sections as $section_id => $section){
            
            echo '<div class="options-user-wrap">';
                echo '<h3>'.$this->sections[$section_id]['title'].'</h3>';
                if($this->sections[$section_id]['description'] != ''){
                    echo $this->sections[$section_id]['description'];
                }
                echo '<img class="options-spinner" src="images/spinner-2x.gif"/>';
                echo '<table class="form-table options-table-responsive options-table">';
                    foreach($this->sections[$section_id]['fields'] as $key => $field){
                        echo '<tr id="field-'.$key.'">';
                            echo '<th scope="row">';
                                echo '<label>' . $field['title'];
                                if($field['required']){
                                    echo '<span class="options-required">*</span>';
                                }
                                echo '</label>';
                                if($field['sub_title'] != ''){
                                    echo '<p class="description">'.$field['sub_title'].'</p>';
                                }
                            echo '</th>';
                            echo '<td class="field field-type-'.$field['type'].'">';
                                if(!isset(static::$field_types[$field['type']])){
                                    echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>';
                                }else{
                                    do_action('macho/options/field/'.$field['type'].'/render', $field, $this);
                                }
                                if($field['description'] != ''){
                                    echo '<p class="description">'.$field['description'].'</p>';
                                }
                            echo '</td>';
                        echo '</tr>';
                    }
                echo '</table>';
            echo '</div>';
        }
    }
}