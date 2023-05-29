<?php
/**
 * Macho_Number_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/number/render', array('Macho_Number_Field', 'render'), 1, 2);

/**
 * Macho_Number_Field simple number field.
 */
class Macho_Number_Field extends Macho_Field{
    
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
                'small-text'
            ), 
            'step' => 1, 
            'min' => 0, 
            'max' => 999999999999
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
        
        echo '<input type="number" name="'.$data['option_name'].'['.$data['name'].']" id="'.$data['id'].'" value="'.$data['value'].'" class="'.implode(' ', $data['classes']).'" step="'.$data['step'].'" min="'.$data['min'].'" max="'.$data['max'].'" autocomplete="false" />'; 
        
    }
    
}