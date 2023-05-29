<?php
/**
 * Macho_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */


/**
 * Macho_Field can be extended to create field classes and contains all basic methods required.
 */
class Macho_Field extends Macho_Base{
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        return array();
    }
    
    /**
     * Parse passed data with default data.
     *
     * @since 1.0.0
     *
     * @param array $data supplied field data.
     *
     * @return array parsed field data
     */
    public static function data_setup( $data = array() ){
        return self::parse_args( $data, static::field_data() );
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
        
          
    }
    
    /**
     * Notify the Macho_Options class of the schema needed for this field type within the values array.
     *
     * Generally this will be an empty string just to register the key in the array, but custom fields and things like multi selects will need this to be an array.
     * Groups use this to define the nested fields as well.
     *
     * @since 1.0.0
     *
     * @param string $value the current value that will be used.
     *
     * @param array $data field data as supplied by the section or group.
     *
     * @return mixed schema value type
     */
    public static function schema($value = '', $data = array() ){
        return '';
    }
    
    /**
     * Allows the field class to reformat any supplied default value from the original supplied to the object instance.
     *
     * @since 1.0.0
     *
     * @param string $default current default
     *
     * @param array $data field data
     *
     * @return mixed default value
     */
    public static function default_value($default = '', $data = array()){
        return $default;
    }
    
}