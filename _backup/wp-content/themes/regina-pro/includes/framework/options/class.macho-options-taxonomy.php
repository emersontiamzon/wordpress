<?php
/**
 * Macho_Options_Taxonomy
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.1
 */

/**
 * Macho_Options_Taxonomy. Create and stores fields and sections.
 */
class Macho_Options_Taxonomy extends Macho_Options{

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
     * @return none
     */
    public function __construct( $args = array(), $sections = array() ){
        
        $this->args = $this->parse_args( $args, $this->default_args(), 'macho/options/taxonomy/args' );
        
        //prepare sections and fields before merging values
        $sections = $this->parse_args( $sections, array(), 'macho/options/taxonomy/'.$this->args['option_name'].'/sections' );
        
        $this->prepare_sections($sections);
        foreach($this->args['taxonomies'] as $tax){
            add_action($tax.'_edit_form', $this->provide('render_taxonomy'), 1, 2);
            add_action($tax.'_add_form_fields', $this->provide('render_taxonomy'));
            add_action( 'edited_'.$tax, $this->provide('save_taxonomy'), 10, 2 );
            add_action( 'created_'.$tax, $this->provide('save_taxonomy'), 10, 2 );
        }
        add_action('delete_term_taxonomy', $this->provide('delete_taxonomy'));
        add_action('load-edit-tags.php', $this->provide('load_taxonomy'));
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
            'taxonomies' => array()
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
      * @param array $context the sections an fields to be used.
      *
      * @param object $id if suppplied should be an instance of Macho_Page and used to render the meatboxes on none metabox pages.
      *
      * @return none
      */
    protected function prepare_sections($sections, $id = null){
        
        if($id){
            $this->options = get_option('taxonomy_'.$id.'_'.$this->args['option_name'], array() );
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
     * The <code>load_taxonomy</code> function is a replication of the <code>load_page</code> function, but tailored for adding fields to taxonomy pages only.
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
    public function load_taxonomy(){
        
        $screen = get_current_screen();
		if ('edit-tags' != $screen->base || !in_array( $screen->taxonomy, $this->args['taxonomies'] )){
			return;
		}
        
        $this->prepare_sections( $this->sections );
        
        $this->load_assets();

        $rel_id = (isset($_REQUEST['tag_ID'])) ? $_REQUEST['tag_ID'] : false;
        
        $this->localize_script(array(
            'context' => 'taxonomy',
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
     * Save taxonomy data within options table.
     *
     * @uses check_admin_referer();
     * @uses Macho_Options::remove_clones();
     * @uses update_option();
     *
     * @since 1.0.0
     *
     * @param int Term ID
     *
     * @return none
     */
    public function save_taxonomy($term_id){
        //we have options
        if(isset($_POST[$this->args['option_name']])){
            if(strpos(current_filter(), 'created_') !== false){
                check_admin_referer('add-tag', '_wpnonce_add-tag');
            }else{
                check_admin_referer('update-tag_'.$term_id);
            }
            $value = apply_filters('macho/options/save', $this->remove_clones($_POST[$this->args['option_name']]));
            update_option('taxonomy_'.$term_id.'_'.$this->args['option_name'], $value );
            //save seperate fields if needed
            foreach($this->sections as $section){
                foreach($section['fields'] as $id => $field){
                    if($field['seperate'] === true){
                        update_option('taxonomy_'.$term_id.'_'.$this->args['option_name'].'_'.$id, $value[$id]);
                    }
                }
            }
        }
    }
    
    /**
     * Delete taxonomy data from within the options table when taxonomy deleted.
     *
     * @uses delete_option();
     *
     * @since 1.0.0
     *
     * @param int Term ID
     *
     * @return none
     */
    public function delete_taxonomy($term_id){
        delete_option('taxonomy_'.$term_id.'_'.$this->args['option_name']);
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
    public function render_taxonomy( $tag = '', $taxonomy = '' ){
        
        if(is_object($tag)){
            $this->prepare_sections( $this->sections, $tag->term_id );
        }else{
            $this->prepare_sections( $this->sections );
        }
        
        foreach($this->sections as $section_id => $section){
            $editclass = (isset($_GET['action']) && $_GET['action'] == 'edit') ? ' options-taxonomy-wrap-edit' : ' options-taxonomy-wrap-new';
            echo '<div class="options-taxonomy-wrap'.$editclass.'">';
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