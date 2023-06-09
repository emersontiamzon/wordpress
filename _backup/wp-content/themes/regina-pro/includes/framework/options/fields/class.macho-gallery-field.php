<?php
/**
 * Macho_Gallery_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.0
 */

add_action('macho/options/field/gallery/render', array('Macho_Gallery_Field', 'render'), 1, 2);
add_action('macho/options/field/gallery/enqueue', array('Macho_Gallery_Field', 'enqueue'), 1, 2);

/**
 * Macho_Gallery_Field WordPress Media gallery select field.
 */
class Macho_Gallery_Field extends Macho_Field{
    
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
            'edit_title' => __('Add/Edit Gallery', 'regina'),
            'remove_title' => __('Delete Gallery', 'regina')
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
        
        echo '<div id="'.$data['id'].'-wrap">';
            $ids = array_filter(explode(',', $data['value']));
            $has_class = (!empty($ids)) ? ' options-gallery-images-has-children' : '';
            echo '<div class="options-gallery-images'.$has_class.'">';
                if(!empty($ids)){
                    foreach($ids as $id){
                        $img_url = wp_get_attachment_image_src($id, 'thumbnail', false);
                        echo '<div class="options-gallery-thumb" data-id="'.$id.'">';
                            echo '<img src="'.$img_url[0].'" />';
                        echo '</div>';
                    }
                }
            echo '<div class="clearfix"></div></div>';
            echo '<a href="#" class="button buttom-small button-primary options-gallery-add-edit">' . $data['edit_title'] . '</a>';
            echo ' <a href="#" class="button buttom-small options-gallery-remove">' . $data['remove_title'] . '</a>';
            echo '<input type="hidden" name="'.$data['option_name'].'['.$data['name'].']" id="'.$data['id'].'" value="'.$data['value'].'" class="options-gallery-ids" />';
        echo '</div>';
    }
    
}