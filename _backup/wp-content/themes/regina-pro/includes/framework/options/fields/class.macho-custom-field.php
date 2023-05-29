<?php
/**
 * Macho_Custom_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/custom/render', array('Macho_Custom_Field', 'render'), 1, 2);
add_action('macho/options/field/custom/enqueue', array('Macho_Custom_Field', 'enqueue'), 1, 2);
add_action('macho/options/field/custom/schema', array('Macho_Custom_Field', 'schema'), 1, 2);
add_action('macho/options/field/custom/default', array('Macho_Custom_Field', 'default_value'), 1, 2);

/**
 * Macho_Custom_Field field.
 */
class Macho_Custom_Field extends Macho_Field{
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        return array(
            'callbacks' => array(
                'render' => 'Macho_Field::render', 
                'enqueue' => 'Macho_Field::enqueue', 
                'schema' => 'Macho_Field::schema', 
                'default' => 'Macho_Field::default_value'
            )
        );
    }
    
    /**
     * Enqueue or register styles and scripts to be used when the field is rendered.
     *
     * @uses call_user_func(); to send data to callback.
     *
     * @since 1.0.0
     *
     * @param array $data field data.
     *
     * @param array $field_data locations and other data for the field type.
     */
    public static function enqueue( $data = array(), $field_data = array() ){
        
        $data = self::data_setup($data);
        $passed_data = $data;
        unset($passed_data['callbacks']);
        call_user_func($data['callbacks']['enqueue'], $passed_data, $field_data);
        
    }
    
    /**
     * Render the field HTML based on the data provided.
     *
     * @uses call_user_func(); to send data to callback.
     *
     * @since 1.0.0
     *
     * @param array $data field data.
     *
     * @param object $object Macho_Options instance allowing you to alter anything if required.
     */
    public static function render($data = array(), $object){
        
        $data = self::data_setup($data);
        $passed_data = $data;
        unset($passed_data['callbacks']);
        call_user_func($data['callbacks']['render'], $passed_data, $object);
          
    }
    
    /**
     * Notify the Macho_Options class of the schema needed for this field type within the values array.
     *
     * Generally this will be an empty string just to register the key in the array, but custom fields and things like multi selects will need this to be an array.
     * Groups use this to define the nested fields as well.
     *
     * @uses call_user_func(); to send data to callback.
     *
     * @since 1.0.0
     *
     * @param string $value the current value that will be used.
     *
     * @param array $data field data as supplied by the section or group.
     *
     * @return mixed schema value for field
     */
    public static function schema($value = '', $data = array() ){
        
        $data = self::data_setup($data);
        $passed_data = $data;
        unset($passed_data['callbacks']);
        return call_user_func($data['callbacks']['schema'], $value, $passed_data);
        
    }
    
    /**
     * Allows the field class to reformat any supplied default value from the original supplied to the object instance.
     *
     * @uses call_user_func(); to send data to callback.
     *
     * @since 1.0.0
     *
     * @param string $default current default
     *
     * @param array $data field data
     *
     * @return mixed default value for field
     */
    public static function default_value($default = '', $data = array()){
        
        $data = self::data_setup($data);
        $passed_data = $data;
        unset($passed_data['callbacks']);
        return call_user_func($data['callbacks']['default'], $default, $passed_data);
        
    }
    
}