<?php
/**
 * Macho_Ace_Field
 *
 * @package macho
 * @since 1.0.5
 * @version 1.0.0
 */

add_action('macho/options/field/ace/render', array('Macho_Ace_Field', 'render'), 1, 2);
add_action('macho/options/field/ace/enqueue', array('Macho_Ace_Field', 'enqueue'));

/**
 * Macho_Ace_Field code editor field using ace.
 */
class Macho_Ace_Field extends Macho_Field{
    
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
            'rows' => 6,
            'ace' => array(
                'theme' => 'monokai',
                'mode' => 'css'
            )
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
        $self = new self;
        wp_enqueue_script('ace-editor', Macho_Base::$url . 'assets/vendor/ace/src-min-noconflict/ace.js', array('jquery'), $self->version, false);
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
        $ace_attributes = '';
        foreach($data['ace'] as $key => $value){
            $ace_attributes .= ' data-ace-'.$key.'="'.$value.'"';
        }
        
        echo '<textarea'.$ace_attributes.' name="'.$data['option_name'].'['.$data['name'].']" cols="'.$data['cols'].'" rows="'.$data['rows'].'" id="'.$data['id'].'" class="'.implode(' ', $data['classes']).'" placeholder="'.$data['placeholder'].'">'.$data['value'].'</textarea>';   
    }
    
}