<?php
/**
 * Macho_Export_Field
 *
 * @package Macho
 * @since 1.0.1
 * @version 1.0.0
 */

add_action('macho/options/field/export/render', array('Macho_Export_Field', 'render'), 1, 2);
add_action('wp_ajax_macho_export', array('Macho_Export_Field', 'export'));

/**
 * Macho_Import_Field importer field.
 */
class Macho_Export_Field extends Macho_Field{
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.1
     *
     * @return array default field data
     */
    public static function field_data(){
        $self = new self;
        return array(
            'download_title' => __('Download Export File', 'regina'),
            'export_type' => 'page',
            'option_name' => '',
            'rel_id' => null
        );
    }

    /**
     * Exports the options instance
     *
     * @since 1.0.1
     *
     * @return none
     */
    public static function export(){
        if(!isset($_GET['action']) || $_GET['action'] != 'macho_export' || !isset($_GET['type']) || !isset($_GET['option']) || !isset($_GET['rel_id']) || !wp_verify_nonce($_GET['_wpnonce'], 'macho_nonce')){
            die(-1);
            return;
        }
        $options = array();
        switch($_GET['type']){
            case 'page':
                $options = get_option($_GET['option']);
            break;
            case 'meta':
                $options = get_post_meta($_GET['rel_id'], $_GET['option'], true);
            break;
            case 'user':
                $options = get_user_meta($_GET['rel_id'], $_GET['option'], true);
            break;
            case 'taxonomy':
                $options = get_option('taxonomy_'.$_GET['rel_id'].'_'.$_GET['option']);
            break;
        }
        if(is_array($options) && !empty($options)){
            header('Content-Type: application/octet-stream');
            header("Content-Description: File Transfer");
            header("Content-Transfer-Encoding: Binary"); 
            header("Content-disposition: attachment; filename=\"".date('Y-m-d_H:i:s_').$_GET['type'].'_'.$_GET['option'].".wpds\"");
            echo json_encode($options);
            exit();
        }
        wp_die(__('An unknown error occured!', 'regina'));
    }
    
    /**
     * Render the field HTML based on the data provided.
     *
     * @since 1.0.1
     *
     * @param array $data field data.
     *
     * @param object $object Macho_Options instance allowing you to alter anything if required.
     */
    public static function render($data = array(), $object){
        $self = new self;
        $data = self::data_setup($data);
        echo '<input type="hidden" name="'.$data['option_name'].'['.$data['name'].']" id="'.$data['id'].'" value="1" />';
        if(!isset($data['value']) || $data['value'] != '1'){
            echo '<strong>'.__('The export option will become available once you save this form.', 'regina').'</strong>';
        }else{
            echo '<a href="#" class="button buttom-small button-primary upload">' . $data['download_title'] . '</a>';
        }
    }
    
}