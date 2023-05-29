<?php
/**
 * Macho_Info_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/heading/render', array('Macho_Heading_Field', 'render'), 1, 2);

/**
 * Macho_Heading_Field simple text field.
 */
class Macho_Heading_Field extends Macho_Field {
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        return array(
            'icon' => '',
            'show_title' => true
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
        $icon = ($data['icon'] != '') ? '<div class="dashicons dashicons-'.$data['icon'].'"></div> ' : '';
        echo '<div class="heading-info">';
            if($data['show_title'] === true){
                echo '<h3>'.$icon.$data['title'].'</h3>';
            }
        echo '</div>';
        
    }
    
}