<?php
/**
 * Macho_Radio_Img_Field
 *
 * @package Macho
 * @since 1.0.2
 * @version 1.0.0
 */

add_action('macho/options/field/radio-img/render', array('Macho_Radio_Img_Field', 'render'), 1, 2);

/**
 * Macho_Radio_Img_Field simple radio image select field.
 */
class Macho_Radio_Img_Field extends Macho_Field{
    
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
            'inline' => true,
            'width' => '100px',
            'height' => 'auto'
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
        
        if($data['value'] == '' && isset($data['default']) && $data['default'] != ''){
            $data['value'] = $data['default'];
        }
        if($data['value'] == ''){
            $data['value'] = key($data['options']);
        }
        
        foreach($data['options'] as $key => $value){
            echo '<label'.(($data['value'] == $key) ? ' class="checked"' : '').'><input type="radio" name="'.$data['option_name'].'['.$data['name'].']" value="'.$key.'" '.checked($data['value'], $key, false).'><img src="'.$value.'" style="width:'.$data['width'].';height: '.$data['height'].';"/></label>';
            if($data['inline'] != true){
                echo '<br/>';
            }
        }
    }
    
}