<?php
/**
 * Macho_Options_Media
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */


//http://www.wpbeginner.com/wp-tutorials/how-to-add-additional-fields-to-the-wordpress-media-uploader/
add_filter('attachment_fields_to_edit', function($form_fields, $post){
        
            $form_fields['custom'] = array(
                'label' => 'testing',
                'input' => 'html',
                'html' => 'my html markup'
            );
            return $form_fields;
        
        }, 1, 2);


/**
 * Macho_Options_Media. Create and stores fields and sections.
 */
class Macho_Options_Media extends Macho_Options{
    
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
        
        $this->args = $this->parse_args( $args, $this->default_args(), 'macho/options/media/args' );
        
        //prepare sections and fields before merging values
        $sections = $this->parse_args( $sections, array(), 'macho/options/media/'.$this->args['option_name'].'/sections' );
        
        $this->prepare_sections($sections);
        
        add_filter('attachment_fields_to_edit', function($form_fields, $post){
        
            $form_fields['custom'] = array(
                'label' => 'testing',
                'input' => 'html',
                'html' => 'my html markup'
            );
            return $form_fields;
        
        });
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
            $this->options = get_post_meta($id, $this->args['option_name'], true );
        }
        if(!$this->options || $this->options == ''){
            $this->options = $this->get_default_values();
        }
        
        foreach($sections as $key => $section){
            $key = sanitize_key($key);
            $this->sections[$key] = $this->parse_args( $section, $this->section_args() );
            $fields = $this->sections[$key]['fields'];
            unset($this->sections[$key]['fields']);
            $this->sections[$key]['fields'] = $this->prepare_fields($fields, $this->options, $key);
        }
        
    }
}