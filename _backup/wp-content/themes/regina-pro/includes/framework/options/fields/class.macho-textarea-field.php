<?php
/**
 * Macho_Textarea_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/textarea/render', array('Macho_Textarea_Field', 'render'), 1, 2);

/**
 * Macho_Textarea_Field simple textarea field.
 */
class Macho_Textarea_Field extends Macho_Field{
    
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
            'placeholder' => '',
            'cols' => 60,
            'rows' => 6
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
        
        echo '<textarea name="'.$data['option_name'].'['.$data['name'].']" cols="'.$data['cols'].'" rows="'.$data['rows'].'" id="'.$data['id'].'" class="'.implode(' ', $data['classes']).'" placeholder="'.$data['placeholder'].'">'.$data['value'].'</textarea>';   
    }
    
}