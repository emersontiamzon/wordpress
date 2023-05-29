<?php

//prevent loading if class already loaded!
if(defined('MT_FILE_URL')){
    return;
}

/**
 * Define macho base path - needed for loading classes.
 */
define( 'MT_FILE_URL', dirname(__FILE__ ) );

//load base class
require MT_FILE_URL . '/options/class.macho-base.php';

//load page class
require MT_FILE_URL . '/options/class.macho-page.php';

//load options classes
require MT_FILE_URL . '/options/class.macho-options.php';
require MT_FILE_URL . '/options/class.macho-options-page.php';
require MT_FILE_URL . '/options/class.macho-options-meta.php';
require MT_FILE_URL . '/options/class.macho-options-user.php';
require MT_FILE_URL . '/options/class.macho-options-taxonomy.php';
require MT_FILE_URL . '/options/fields/class.macho-field.php';


//load post type class
require MT_FILE_URL . '/options/class.macho-post-type.php';

//load taxonomy class
require MT_FILE_URL . '/options/class.macho-taxonomy.php';

// load store class
require MT_FILE_URL . '/options/class.macho-store.php';


Macho_Base::$url = MT_THEME_URL .'/includes/framework/';

/**
 * Define path to macho jquery ui style. We will soon create our own, but for now the delta style is awesome!
 */
if(!defined('MACHO_JQUERY_UI_STYLE')){
    define('MACHO_JQUERY_UI_STYLE', Macho_Base::$url . '/assets/vendor/delta-ui/jquery-ui.css');
}

//register base field types - these can be overloaded by re-registering them later on.

//groups
Macho_Options::register_field_type(array(
    'type' => 'group',
    'class_name' => 'Macho_Group_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-group-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/group/'
));

//text inputs
Macho_Options::register_field_type(array(
    'type' => 'text',
    'class_name' => 'Macho_Text_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-text-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/text/'
));

Macho_Options::register_field_type(array(
    'type' => 'email',
    'class_name' => 'Macho_Email_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-email-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/email/'
));

Macho_Options::register_field_type(array(
    'type' => 'url',
    'class_name' => 'Macho_Url_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-url-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/url/'
));

Macho_Options::register_field_type(array(
    'type' => 'number',
    'class_name' => 'Macho_Number_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-number-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/number/'
));


Macho_Options::register_field_type(array(
    'type' => 'textarea',
    'class_name' => 'Macho_Textarea_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-textarea-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/textarea/'
));

//choice inputs
Macho_Options::register_field_type(array(
    'type' => 'radio',
    'class_name' => 'Macho_Radio_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-radio-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/radio/'
));

Macho_Options::register_field_type(array(
    'type' => 'checkbox',
    'class_name' => 'Macho_Checkbox_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-checkbox-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/checkbox/'
));

Macho_Options::register_field_type(array(
    'type' => 'select',
    'class_name' => 'Macho_Select_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-select-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/select/'
));

//special inputs
Macho_Options::register_field_type(array(
    'type' => 'editor',
    'class_name' => 'Macho_Editor_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-editor-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/editor/'
));

Macho_Options::register_field_type(array(
    'type' => 'color',
    'class_name' => 'Macho_Color_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-color-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/color/'
));

Macho_Options::register_field_type(array(
    'type' => 'date',
    'class_name' => 'Macho_Date_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-date-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/date/'
));

Macho_Options::register_field_type(array(
    'type' => 'media',
    'class_name' => 'Macho_Media_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-media-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/media/'
));

Macho_Options::register_field_type(array(
    'type' => 'gallery',
    'class_name' => 'Macho_Gallery_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-gallery-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/gallery/'
));

Macho_Options::register_field_type(array(
    'type' => 'switch',
    'class_name' => 'Macho_Switch_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-switch-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/switch/'
));

Macho_Options::register_field_type(array(
    'type' => 'radio-img',
    'class_name' => 'Macho_Radio_Img_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-radio-img-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/radio-img/'
));

Macho_Options::register_field_type(array(
    'type' => 'custom',
    'class_name' => 'Macho_Custom_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-custom-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/custom/'
));

Macho_Options::register_field_type(array(
    'type' => 'ace',
    'class_name' => 'Macho_Ace_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-ace-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/ace/'
));

Macho_Options::register_field_type(array(
    'type' => 'background',
    'class_name' => 'Macho_Background_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-background-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/background/'
));

Macho_Options::register_field_type(array(
    'type' => 'icon-picker',
    'class_name' => 'Macho_Icon_Picker_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-icon-picker-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/icon-picker/'
));

Macho_Options::register_field_type(array(
    'type' => 'font',
    'class_name' => 'Macho_Font_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-font-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/font/'
));

Macho_Options::register_field_type(array(
    'type' => 'info',
    'class_name' => 'Macho_Info_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-info-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/info/'
));

Macho_Options::register_field_type(array(
    'type' => 'heading',
    'class_name' => 'Macho_Heading_Field',
    'path' => MT_FILE_URL . '/options/fields/class.macho-heading-field.php',
    'assets_path' => Macho_Base::$url . 'assets/fields/heading/'
));