<?php
/**
 * Macho_Url_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/url/render', array('Macho_Url_Field', 'render'), 1, 2);

/**
 * Macho_Url_Field simple text field.
 */
class Macho_Url_Field extends Macho_Field{
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        return array(
            'classes' => array(
                'large-text'
            ),
            'placeholder' => ''
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
        
        echo '<input type="url" name="'.$data['option_name'].'['.$data['name'].']" id="'.$data['id'].'" value="'.$data['value'].'" class="'.implode(' ', $data['classes']).'" placeholder="'.$data['placeholder'].'" />';   
    }
    
}