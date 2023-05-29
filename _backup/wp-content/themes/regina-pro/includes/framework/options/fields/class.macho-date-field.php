<?php
/**
 * Macho_Date_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/date/render', array('Macho_Date_Field', 'render'), 1, 2);
add_action('macho/options/field/date/enqueue', array('Macho_Date_Field', 'enqueue'));

/**
 * Macho_Date_Field jQuery UI datepicker field.
 */
class Macho_Date_Field extends Macho_Field{
    
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
                'regular-text'
            ),
            'placeholder' => ''
        );
    }
    
    /**
     * Enqueue or register styles and scripts to be used when the field is rendered.
     *
     * @since 1.0.0
     *
     * @param array $data field data.
     *
     * @param array $field_data locations and other data for the field type.
     */
    public static function enqueue( $data = array(), $field_data = array() ){
        wp_enqueue_style('macho-ui-css');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
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
        
        echo '<div class="date-wrap"><input type="text" name="'.$data['option_name'].'['.$data['name'].']" id="'.$data['id'].'" value="'.$data['value'].'" class="'.implode(' ', $data['classes']).'" /> <div class="dashicons dashicons-calendar"></div></div>';   
    }
    
}