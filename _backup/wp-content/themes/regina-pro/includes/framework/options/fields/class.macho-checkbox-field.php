<?php
/**
 * Macho_Checkbox_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/checkbox/render', array('Macho_Checkbox_Field', 'render'), 1, 2);
add_action('macho/options/field/checkbox/schema', array('Macho_Checkbox_Field', 'schema'), 1, 2);

/**
 * Macho_Checkbox_Field simple checkbox field.
 */
class Macho_Checkbox_Field extends Macho_Field{
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        return array(
            'options' => array(),
            'inline' => true
        );
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
     */
    public static function schema($value = '', $data = array()){
        $data = self::data_setup($data);
        if(count($data['options']) > 1){
            $out = array();
            foreach($data['options'] as $key => $value){
                $out[$key] = false;   
            }
            return $out;
        }
        return false;
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
        
        if(count($data['options']) > 1){
            foreach($data['options'] as $key => $value){
                $checked = (isset($data['value'][$key]) && $data['value'][$key] == true) ? ' checked="checked"' : '';
                echo '<label><input type="checkbox" name="'.$data['option_name'].'['.$data['name'].']['.$key.']" value="1" '.$checked.'> <span>'.$value.'</span></label>';
                if($data['inline'] != true){
                    echo '<br/>';
                }
            }
        }else{
            foreach($data['options'] as $key => $value){
                $checked = (isset($data['value']) && $data['value'] == true) ? ' checked="checked"' : '';
                echo '<label><input type="checkbox" name="'.$data['option_name'].'['.$data['name'].']" value="1" '.$checked.'> <span>'.$value.'</span></label>';
            }
        }
    }
    
}