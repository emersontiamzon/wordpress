<?php
/**
 * Macho_Search_Field
 *
 * @package Macho
 * @since 1.0.2
 * @version 1.0.0
 */

add_action('macho/options/field/search/render', array('Macho_Search_Field', 'render'), 1, 2);
add_action('macho/options/field/search/schema', array('Macho_Search_Field', 'schema'), 1, 2);
add_action('wp_ajax_macho_search_field_post_type', array('Macho_Search_Field', 'results'));

/**
 * Macho_Search_Field simple select field.
 */
class Macho_Search_Field extends Macho_Field{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.0';
    
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
            'source_type' => 'post_type',
            'post_types' => array('post'),
            'roles' => array(),//optionall limit by roles when source_type = user
            'value_key' => 'id',//must be id for post_type
            'value_label' => 'title',//must be title for post_type
            'multiple' => false,
            'placeholder' => __('Type to search', 'regina')
        );
    }

    private static function get_label($key, $field){
        switch($field['source_type']){
            case 'post_type':
                return get_the_title($key);
            break;
            case 'user':
                $user = get_user_by($field['value_key'], $key);
                if($user){
                    switch($field['value_label']){
                        case 'name':
                        case 'display_name':
                            return $user->get('display_name');
                        break;
                        case 'first_name':
                            return $user->first_name;
                        break;
                        case 'last_name':
                            return $user->last_name;
                        break;
                        case 'email':
                            return $user->get('user_email');
                        break;
                        case 'username':
                        case 'login':
                            return $user->get('user_login');
                        break;
                        default:
                            return $user->ID;
                    }
                }
            break;
        }
        return $key;
    }


    public static function results(){
        $filter = current_filter();


        echo json_encode(
            array(
                array(
                    'key' => '1',
                    'value' => 'a label'
                )
            )
        );
        die();

        echo json_encode(array('status' => 200) + $_POST + array('results' => array()));
        die();
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
        if($data['multiple'] == true){
            return array();
        }
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
        
        $multiple = ($data['multiple'] == true) ? '[]" multiple="multiple' : '';

        if($data['value'] == '' && isset($data['default']) && $data['default'] != ''){
            $data['value'] = $data['default'];
        }
        
        $opts = array();
        $opts['source_type'] = $data['source_type'];
        $opts['post_types'] = $data['post_types'];
        $opts['roles'] = $data['roles'];
        $opts['value_key'] = $data['value_key'];
        $opts['value_label'] = $data['value_label'];
        $opts['search_url'] = wp_nonce_url(admin_url('admin-ajax.php?action=macho_search_field_'.$data['source_type']), 'macho-field-search');



        $data_string = '';
        foreach($opts as $key => $opt){
            $val = (is_array($opt)) ? implode('|', $opt) : $opt;
            $data_string .= 'data-'.$key.'="'.$val.'"';
        }

        echo '<select name="'.$data['option_name'].'['.$data['name'].']'.$multiple.'" class="selectize-search" placeholder="'.$data['placeholder'].'" '.$data_string.'>';
            if($data['multiple'] == true && !empty($data['value'])){
                foreach($data['value'] as $val){
                    echo '<option value="'.$val.'" selected="selected">'.self::get_label($val, $data).'</option>';   
                }
            }elseif($data['value'] != ''){
                echo '<option value="'.$data['value'].'" selected="selected">'.self::get_label($data['value'], $data).'</option>';
            }
        echo '</select>';
    }
    
}