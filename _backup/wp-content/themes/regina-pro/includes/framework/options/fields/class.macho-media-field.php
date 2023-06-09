<?php
/**
 * Macho_Media_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/media/render', array('Macho_Media_Field', 'render'), 1, 2);
add_action('macho/options/field/media/enqueue', array('Macho_Media_Field', 'enqueue'), 1, 2);

/**
 * Macho_Media_Field WordPress Media uploader field.
 */
class Macho_Media_Field extends Macho_Field{
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        $self = new self;
        return array(
            'upload_title' => __('Select Media', 'regina'),
            'media_title' => __('Media Library', 'regina'),
            'media_select' => __('Select Media', 'regina')
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
        wp_enqueue_media();
        wp_localize_script( 'macho-options-js', 'macho_media', array(
            'file_icon' => $field_data['assets_path'].'images/unknown-file.jpg'
        ) );
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


        $img_url = wp_get_attachment_image_src($data['value'], 'thumbnail', false);

        //if no value setup the array
        if(!$data['value']){
            $img_url = array(
                0 => ''
            );
        }

        // default image placeholder
        if($data['value'] && empty($img_url)){
            $img_url = array(
                0 => $object::$field_types['media']['assets_path'].'images/unknown-file.jpg'
            );
        }
        
        $button_display = ($data['value']) ? ' style="display: none;"' : '';
        
        echo '<div id="'.$data['id'].'-wrap">';
            echo '<a href="#" class="button buttom-small upload"'.$button_display.' data-title="'.$data['media_title'].'" data-select="'.$data['media_select'].'">' . $data['upload_title'] . '</a>';
            echo '<input type="hidden" name="'.$data['option_name'].'['.$data['name'].']" id="'.$data['id'].'" value="'.$data['value'].'" class="options-media-id" />';
            $image_display = (!$data['value']) ? ' style="display: none;"' : '';
            echo '<div class="options-media-thumb"'.$image_display.'>';
                echo '<img src="'.$img_url[0].'" />';
                $edit_link = ( !empty($img_url) ) ? admin_url('post.php?action=edit&post='.$data['value']) : '#';
                echo '<a href="#" class="options-media-remove" title="' . __('Remove', 'regina') . '"><div class="dashicons dashicons-no-alt"></div></a>';
                echo '<a href="'.$edit_link.'" class="options-media-edit" title="' . __('Edit', 'regina') . '" target="_blank"><div class="dashicons dashicons-edit"></div></a>';
            echo '</div>';
        echo '</div>';
    }
    
}