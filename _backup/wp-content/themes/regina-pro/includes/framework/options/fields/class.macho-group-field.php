<?php
/**
 * Macho_Group_Field
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.1
 */

add_action('macho/options/field/group/render', array('Macho_Group_Field', 'render'), 1, 2);
add_filter('macho/options/field/group/schema', array('Macho_Group_Field', 'schema'), 1, 2);
add_filter('macho/options/field/group/default', array('Macho_Group_Field', 'default_value'), 1, 2);
add_action('macho/options/field/group/enqueue', array('Macho_Group_Field', 'enqueue'));

/**
 * Macho_Group_Field repeatable, nestable groups of fields.
 */
class Macho_Group_Field extends Macho_Field{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.1';
    
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
            'multiple' => false,
            'fields' => array(),
            'layout' => 'horizontal',
            'show_headers' => true,
            'add_row_text' => __('Add Row', 'regina')
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
        foreach($data['fields'] as $k => $field){
            if(isset(Macho_Options::$field_types[$field['type']])){
                do_action('macho/options/field/'.$field['type'].'/enqueue', $field, Macho_Options::$field_types[$field['type']]);
            }
        }
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
        $output = array();
        foreach($data['fields'] as $field_key => $field){
            $output[$field_key] = apply_filters('macho/options/field/'.$field['type'].'/schema', '', $field);
        }
        return $output;
    }
    
    /**
     * Allows the field class to reformat any supplied default value from the original supplied to the object instance.
     *
     * @since 1.0.0
     *
     * @uses Macho_Base to generate guids for array values
     *
     * @param string $default current default
     *
     * @param array $data field data
     */
    public static function default_value($default = array(), $data = array()){
        
        $data = self::data_setup($data);
        
        //if multiple
        if($data['multiple'] == true){
            //format default to include guid keys
            if(is_array($default) && !empty($default)){
                $output = array();
                //create an instance to use the guid function
                $base = new Macho_Base;
                foreach($default as $value){
                    $output[$base->guid()] = $value;   
                }
                return $output;
            }
            //return empty group
            return array();
        }
        
        $output = array();
        foreach($data['fields'] as $field_key => $field){
            $output[$field_key] = apply_filters('macho/options/field/'.$field['type'].'/default', $field['default'], $field);
        }
        return $output;
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

        if(!is_array($data['value'])){
            $data['value'] = array();
        }
        
        $data['value'] = array_filter($data['value']);
        
        //$data['layout'] = 'horizontal'; // forced until we add it
        
        $data['default_width'] = 100 / count($data['fields']);
        
        if($data['layout'] == 'horizontal'){
            echo '<table class="options-table-responsive options-group-table" id="options-group-table-'.$data['id'].'">';
                if($data['show_headers'] === true){
                    echo '<thead><tr class="options-group-field-headers">';
                        if($data['multiple'] == true){
                            echo '<th class="options-group-actions">&nbsp;</th>';
                        }
                        foreach($data['fields'] as $key => $field){
                            $width = (isset($field['width'])) ? $field['width'] : $data['default_width'];
                            echo '<th data-width="'.$width.'">';
                                echo '<label>' . $field['title'];
                                if($field['required']){
                                    echo '<span class="options-required">*</span>';
                                }
                                echo '</label>';
                                if($field['sub_title'] != ''){
                                    echo '<p class="description">'.$field['sub_title'].'</p>';
                                }
                            echo '</th>';
                        }
                        if($data['multiple'] == true){
                            echo '<th class="options-group-actions">&nbsp;</th>';
                        }
                    echo '</tr></thead>';
                }
                echo '<tbody>';
                        if($data['multiple'] == true){
                            if(!empty($data['value'])){
                                foreach($data['value'] as $value_key => $value){
                                    $value = array_filter($value);
                                    if(empty($value)){
                                        continue;
                                    }
                                    echo '<tr id="field-'.$data['id'].'-'.$value_key.'">';
                                        echo '<td class="options-group-actions options-group-move"><span class="dashicons-menu dashicons">&nbsp;</span></td>';
                                        foreach($data['fields'] as $key => $field){
                                            $key = $data['id'] . '-' . $value_key . '-' . $key;
                                            $field['id'] = $key;
                                            $index = explode('][', $field['name']);
                                            $field['value'] = ( isset( $value[end($index)] ) ) ? $value[end($index)] : '';
                                            $field['name'] = $data['name'] . ']['.$value_key.'][' . $field['name'];
                                            
                                            echo '<td id="field-'.$key.'" class="field-type-'.$field['type'].'">';
                                                if(!isset(Macho_Options::$field_types[$field['type']])){
                                                    echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>';
                                                }else{
                                                    do_action('macho/options/field/'.$field['type'].'/render', $field, $object);
                                                }
                                                if($field['description'] != ''){
                                                    echo '<p class="description">'.$field['description'].'</p>';
                                                }
                                            echo '</td>';
                                        }
                                        echo '<td class="options-group-actions"><a href="#" class="options-group-remove dashicons-no-alt dashicons" title="Remove">&nbsp;</a></td>';
                                    echo '</tr>';
                                }
                            }
                        }else{
                            echo '<tr>';
                            foreach($data['fields'] as $key => $field){
                                $key = $data['id'] . '-' . $key;
                                $field['id'] = $key;
                                $field['value'] = (isset($data['value'][$field['name']])) ? $data['value'][$field['name']] : '';
                                $field['name'] = $data['name'] . '][' . $field['name'];
                                
                                echo '<td id="field-'.$key.'" class="field-type-'.$field['type'].'">';
                                    if(!isset(Macho_Options::$field_types[$field['type']])){
                                        echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>';
                                    }else{
                                        do_action('macho/options/field/'.$field['type'].'/render', $field, $object);
                                    }
                                    if($field['description'] != ''){
                                        echo '<p class="description">'.$field['description'].'</p>';
                                    }
                                echo '</td>';
                            }
                            echo '</tr>';
                        }
                echo '</tbody>';
                if($data['multiple'] == true){
                    $count = count($data['fields']) + 2;
                    echo '<tfoot><tr><td colspan="'.$count.'" class="options-group-add"><a href="#" data-id="'.$data['id'].'" data-layout="horizontal" class="button button-small">'.$data['add_row_text'].'</a></td></tr></tfoot>';
                    
                    //template
                    echo '<tfoot id="'.$data['id'].'-template" class="options-group-template">';
                    echo '<tr id="field-'.$data['id'].'-##'.$data['id'].'-clone##">'."\n";
                        echo '<td class="options-group-actions options-group-move"><span class="dashicons-menu dashicons">&nbsp;</span></td>'."\n";
                        foreach($data['fields'] as $key => $field){
                            $key = $data['id'] . '-##'.$data['id'].'-clone##-' . $key;
                            $field['id'] = $key;
                            $field['value'] = '';
                            
                            //fix for nested multiples
                            if(strpos($field['name'], '[##'.$data['id'].'-clone##]') === false){
                                $field['name'] = $data['name'] . '][##'.$data['id'].'-clone##][' . $field['name'];
                            }else{
                                $field['name'] = $data['name'] . '][' . $field['name'];   
                            }
                            
                            echo '<td id="field-'.$key.'" class="field-type-'.$field['type'].'">'."\n";
                                if(!isset(Macho_Options::$field_types[$field['type']])){
                                    echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>'."\n";
                                }else{
                                    do_action('macho/options/field/'.$field['type'].'/render', $field, $object);
                                }
                                if($field['description'] != ''){
                                    echo '<p class="description">'.$field['description'].'</p>'."\n";
                                }
                            echo '</td>'."\n";
                        }
                        echo '<td class="options-group-actions '.$field['id'].'"><a href="#" class="options-group-remove dashicons-no-alt dashicons" title="Remove">&nbsp;</a></td>'."\n";
                    echo '</tr>'."\n";
                echo '</tfoot>';
                    
                    
                }
            echo '</table>';
        }elseif($data['layout'] == 'vertical'){
            echo '<table class="options-table-responsive options-group-table" id="options-group-table-'.$data['id'].'">';
                echo '<tbody>';
                        if($data['multiple'] == true){
                            if(!empty($data['value'])){
                                foreach($data['value'] as $value_key => $value){
                                    //$value = array_filter($value);
                                    if(empty($value)){
                                        continue;
                                    }
                                    echo '<tr id="field-'.$data['id'].'-'.$value_key.'">';
                                        echo '<td class="options-group-actions options-group-move"><span class="dashicons-menu dashicons">&nbsp;</span></td>';
                                        echo '<td class="options-group-vertical"><table class="options-group-vertical-table">';
                                            foreach($data['fields'] as $key => $field){
                                                $key = $data['id'] . '-' . $value_key . '-' . $key;
                                                $field['id'] = $key;
                                                $index = explode('][', $field['name']);
                                                $field['value'] = ( isset( $value[end($index)] ) ) ? $value[end($index)] : '';
                                                $field['name'] = $data['name'] . ']['.$value_key.'][' . $field['name'];
                                                
                                                echo '<tr>';
                                                    echo '<td class="label">';
                                                        echo '<label>' . $field['title'];
                                                        if($field['required']){
                                                            echo '<span class="options-required">*</span>';
                                                        }
                                                        echo '</label>';
                                                        if($field['sub_title'] != ''){
                                                            echo '<p class="description">'.$field['sub_title'].'</p>';
                                                        }
                                                    echo '</td>';
                                                    echo '<td id="field-'.$key.'" class="field-type-'.$field['type'].'">';
                                                        if(!isset(Macho_Options::$field_types[$field['type']])){
                                                            echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>';
                                                        }else{
                                                            do_action('macho/options/field/'.$field['type'].'/render', $field, $object);
                                                        }
                                                        if($field['description'] != ''){
                                                            echo '<p class="description">'.$field['description'].'</p>';
                                                        }
                                                    echo '</td>';
                                                echo '</tr>';
                                            }
                                        echo '</table></td>';
                                        echo '<td class="options-group-actions"><a href="#" class="options-group-remove dashicons-no-alt dashicons" title="Remove">&nbsp;</a></td>';
                                    echo '</tr>';
                                }
                            }
                        }else{

                            echo '<tr>';
                                echo '<td class="options-group-vertical"><table class="options-group-vertical-table">';
                                    foreach($data['fields'] as $key => $field){
                                        $key = $data['id'] . '-' . $key;
                                        $field['id'] = $key;
                                        $field['value'] = (isset($data['value'][$field['name']])) ? $data['value'][$field['name']] : '';
                                        $field['name'] = $data['name'] . '][' . $field['name'];
                                        
                                        echo '<tr>';
                                            echo '<td class="label">';
                                                echo '<label>' . $field['title'];
                                                if($field['required']){
                                                    echo '<span class="options-required">*</span>';
                                                }
                                                echo '</label>';
                                                if($field['sub_title'] != ''){
                                                    echo '<p class="description">'.$field['sub_title'].'</p>';
                                                }
                                            echo '</td>';
                                            echo '<td id="field-'.$key.'" class="field-type-'.$field['type'].'">';
                                                if(!isset(Macho_Options::$field_types[$field['type']])){
                                                    echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>';
                                                }else{
                                                    do_action('macho/options/field/'.$field['type'].'/render', $field, $object);
                                                }
                                                if($field['description'] != ''){
                                                    echo '<p class="description">'.$field['description'].'</p>';
                                                }
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                echo '</table></td>';
                            echo '</tr>';
                        }
                echo '</tbody>';
                if($data['multiple'] == true){
                    $count = count($data['fields']) + 2;
                    echo '<tfoot><tr><td colspan="'.$count.'" class="options-group-add"><a href="#" data-id="'.$data['id'].'" data-layout="vertical" class="button button-small">'.$data['add_row_text'].'</a></td></tr></tfoot>';
                    
                    //template
                    echo '<tfoot id="'.$data['id'].'-template" class="options-group-template">';
                    echo '<tr id="field-'.$data['id'].'-##'.$data['id'].'-clone##">'."\n";
                        echo '<td class="options-group-actions options-group-move"><span class="dashicons-menu dashicons">&nbsp;</span></td>'."\n";
                        echo '<td class="options-group-vertical"><table class="options-group-vertical-table">';
                            foreach($data['fields'] as $key => $field){
                                $key = $data['id'] . '-##'.$data['id'].'-clone##-' . $key;
                                $field['id'] = $key;
                                $field['value'] = '';
                                //fix for nested multiples
                                if(strpos($field['name'], '[##'.$data['id'].'-clone##]') === false){
                                    $field['name'] = $data['name'] . '][##'.$data['id'].'-clone##][' . $field['name'];
                                }else{
                                    $field['name'] = $data['name'] . '][' . $field['name'];   
                                }
                                
                                echo '<tr>';
                                    echo '<td class="label">';
                                        echo '<label>' . $field['title'];
                                        if($field['required']){
                                            echo '<span class="options-required">*</span>';
                                        }
                                        echo '</label>';
                                        if($field['sub_title'] != ''){
                                            echo '<p class="description">'.$field['sub_title'].'</p>';
                                        }
                                    echo '</td>';
                                    echo '<td id="field-'.$key.'" class="field-type-'.$field['type'].'">';
                                        if(!isset(Macho_Options::$field_types[$field['type']])){
                                            echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>';
                                        }else{
                                            do_action('macho/options/field/'.$field['type'].'/render', $field, $object);
                                        }
                                        if($field['description'] != ''){
                                            echo '<p class="description">'.$field['description'].'</p>';
                                        }
                                    echo '</td>';
                                echo '</tr>';
                            }
                        echo '</table></td>';
                        echo '<td class="options-group-actions '.$field['id'].'"><a href="#" class="options-group-remove dashicons-no-alt dashicons" title="Remove">&nbsp;</a></td>'."\n";
                    echo '</tr>'."\n";
                echo '</tfoot>';
                    
                    
                }
            echo '</table>';
        }
           
    }
    
}