<?php
/**
 * Macho_Font_Field
 *
 * @package Macho
 * @since 1.0.1
 * @version 1.0.1
 */

add_action('macho/options/field/font/render', array('Macho_Font_Field', 'render'), 1, 2);
add_action('macho/options/field/font/schema', array('Macho_Font_Field', 'schema'), 1, 2);
add_action('macho/options/field/font/enqueue', array('Macho_Font_Field', 'enqueue'));

/**
 * Macho_Font_Field simple select field.
 */
class Macho_Font_Field extends Macho_Field{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.1';

    /**
     * @var array $font_list proccessed font list.
     */
    public static $font_list = array();
    
    /**
     * Returns the default field data.
     *
     * @since 1.0.0
     *
     * @return array default field data
     */
    public static function field_data(){
        return array(
            'additional_fonts' => array(), 
            'units' => 'px',
            'google' => true,
            'value' => array(
                'font-family' => '',
                'font-size' => '',
                'line-height' => '',
                'color' => ''
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
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_localize_script('macho-options-js', 'macho_font', self::get_fonts_list());
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
        return array(
            'font-family' => '',
            'font-size' => '20',
            'line-height' => '24',
            'color' => ''
        );
    }

    public static function get_fonts_list(){

        if(!empty(self::$font_list)){
            return self::$font_list;
        }

        $self = new self;
        $list = array();

        $native_fonts = array(
            'Arial, sans-serif',
            'Helvetica, sans-serif',
            '\'Arial Black\', sans-serif',
            '\'Bookman Old Style\', serif',
            '\'Comic Sans MS\', cursive',
            'Courier, monospace',
            'Garamond, serif',
            'Georgia, serif',
            'Impact, sans-serif',
            '\'Lucida Console\', monospace',
            '\'Lucida Sans Unicode\', sans-serif',
            '\'MS Sans Serif\', sans-serif',
            '\'MS Serif\', sans-serif',
            '\'Palatino Linotype\', serif',
            'Tahoma, sans-serif',
            'Times New Roman, serif',
            '\'Trebuchet MS\', sans-serif',
            'Verdana, sans-serif'
        );
        foreach($native_fonts as $font){
            $_font = array(
                'name' => $font,
                'variants' => array(
                    '300',
                    '300italic',
                    'regular',
                    'italic',
                    '600',
                    '600italic',
                    '700',
                    '700italic',
                    '800',
                    '800italic'
                ),
                'optgroup' => 'native',
                'label' => __('System Fonts', 'regina')
            );
            $list[] = $_font;
        }


        $google_fonts = json_decode(file_get_contents(dirname(MT_FILE_URL) . '/framework/assets/vendor/google-fonts-source.json'), true);
        foreach($google_fonts['items'] as $font){
            $_font = array(
                'name' => $font['family'] . ', ' . $font['category'],
                'family' => $font['family'],
                'variants' => $font['variants'],
                'optgroup' => 'google',
                'label' => __('Google Fonts', 'regina')
            );
            $list[] = $_font;
        }
        self::$font_list = $list;
        return $list;
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

        $self = new self;
        
        $data = self::data_setup($data);

        $val = (is_array($data['value'])) ? $data['value'] : array();

        $data['value'] = self::parse_args($val, self::schema());


        $list = self::get_fonts_list();
        if(isset($data['value']['font-family']) && $data['value']['font-family'] != ''){
            foreach($list as $font){
                if($font['name'] == $data['value']['font-family']){
                    if($font['optgroup'] == 'google'){
                        echo '<link href="//fonts.googleapis.com/css?family='.str_replace(' ', '+', $font['family']).'" type="text/css" rel="stylesheet"/>';
                    }
                    break;
                }
            }
        }



        echo '<input type="hidden" value="'.$data['units'].'" class="units" />';
        echo '<table>';
            echo '<tr>';
                echo '<td width="40%" class="font-family">';
                    echo '<select id="'.$data['id'].'" name="'.$data['option_name'].'['.$data['name'].'][font-family]" data-google="'.$data['google'].'" class="font-family" placeholder="'.__('Font Family', 'regina').'"><option value="'.((isset($data['value']['font-family'])) ? $data['value']['font-family'] : '').'" select="selected"></option></select>';
                echo '</td>';
                //echo '<td width="20%">';
                    //echo '<input type="text" name="'.$data['option_name'].'['.$data['name'].'][color]" id="'.$data['id'].'-color" value="'.((isset($data['value']['color'])) ? $data['value']['color'] : '').'" class="color" />';
                //echo '</td>';
                echo '<td width="20%">';
                    echo '<input type="text" name="'.$data['option_name'].'['.$data['name'].'][font-size]" id="'.$data['id'].'-font-size" class="large-text font-size" value="'.((isset($data['value']['font-size'])) ? $data['value']['font-size'] : '').'" placeholder="'.__('Font Size', 'regina').' ('.$data['units'].')"/>';
                echo '</td>';
                echo '<td width="20%">';
                    echo '<input type="text" name="'.$data['option_name'].'['.$data['name'].'][line-height]" id="'.$data['id'].'-line-height" class="large-text line-height" value="'.((isset($data['value']['line-height'])) ? $data['value']['line-height'] : '').'" placeholder="'.__('Line Height', 'regina').' ('.$data['units'].')"/>';
                echo '</td>';
            echo '</tr>';
        echo '</table>';
        echo '<div class="font-preview" style="background: #f9f9f9;padding:20px 20px 20px 40px;font-family: '.$data['value']['font-family'].';line-height: '.$data['value']['line-height'].$data['units'].';font-size: '.$data['value']['font-size'].$data['units'].';color: '.$data['value']['color'].';"><div class="bg-switcher"><span class="dashicons dashicons-visibility"></span></div>1 2 3 4 5 6 7 8 9 0 A B C D E F G H I J K L M N O P Q R S T U V W X Y Z a b c d e f g h i j k l m n o p q r s t u v w x y z</div>';
    }
    
}