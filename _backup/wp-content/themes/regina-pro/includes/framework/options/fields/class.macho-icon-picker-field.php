<?php
/**
 * Macho_Icon_Picker_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/icon-picker/render', array('Macho_Icon_Picker_Field', 'render'), 1, 2);

/**
 * Macho_Text_Field simple text field.
 */
class Macho_Icon_Picker_Field extends Macho_Field{

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

        echo '<input type="text" name="'.$data['option_name'].'['.$data['name'].']" id="'.$data['id'].'" value="'.$data['value'].'" class="'.implode(' ', $data['classes']).'" placeholder="'.$data['placeholder'].'" />';

		if(!$data['value']) {
	       $data['value'] = ' | ';
		}

	    # Font Awesome Icons aren't separated by |, but by a space
        if( strpos( $data['value'] , '|' ) ) {
            $v = explode("|", $data['value']);
       } else {
	        $v = explode(" ", $data['value']);
        }



        echo '<div id="'.$data['id'].'" class="button icon-picker '. $v[0] . ' ' . $v[1]. '" type="button" data-target="#'.$data['id'].'" />';

    }

}