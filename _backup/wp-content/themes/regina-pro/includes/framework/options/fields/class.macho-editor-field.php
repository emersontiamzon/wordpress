<?php
/**
 * Macho_Editor_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/editor/render', array('Macho_Editor_Field', 'render'), 1, 2);

/**
 * Macho_Editor_Field WordPress editor field.
 */
class Macho_Editor_Field extends Macho_Field{
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        return array(
            'args' => array(
                'textarea_rows' => 6
            )
        );
    }
    
    /**
     * Render the field HTML based on the data provided.
     *
     * @since 1.0.0
     *
     * @param array $data field data.
     *
     * @param object $object Macho_Options instance allowing you to alter anything if required.
     */
    public static function render($data = array(), $object){
        
        $data = self::data_setup($data);
        
        $data['args']['textarea_name'] = $data['option_name'].'['.$data['name'].']';
        wp_editor($data['value'], str_replace(array('-','_'), '', $data['id']), $data['args']);
        
    }
    
}