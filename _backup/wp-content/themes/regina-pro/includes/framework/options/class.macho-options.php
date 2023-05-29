<?php
/**
 * Macho_Options
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.1
 */

//for validation: http://jqueryvalidation.org/documentation/



/**
 * Macho_Options main framework class. Create and stores fields and sections, displays in the admin based on $context supplied
 */
class Macho_Options extends Macho_Base{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.1';
    
    /**
     * @var array $field_types static definitions of field types. Can be registered, editted and removed.
     */
    public static $field_types = array();
    
    /**
     * @var array $sections conatins all the section and field data.
     */
    public $sections = array();
    
    /**
     * @var array $version store context specific data in this class property. Will negate the need to use get_***
     */
    public $options = array();
    
    /**
     * @var boolean $added_nonce_field used during metabox creation to decide when we should output the security nonce.
     */
    private $added_nonce_field = false;
    
    /**
     * Register a field type for use by the framework.
     *
     * Static interface used so field types can be regsitered at any point, and for any istance of the class.
     *
     * @since 1.0.0
     *
     * @param array $field the data to be supplied. Should be an array similar to: <pre><code>
     * array(
     * 'type' => '', //how to target the field type
     * 'class_name' => '', //name of the field class
     * 'path' => '', //where the class file is located (so we use lazy loading)
     * 'assets_path' => '' //where the field assets are stored
     * )
     * </code></pre>
     *
     * @return none
     */
    public static function register_field_type($field){
        static::$field_types[$field['type']] = $field;
    }
    
    /**
     * __construct() parse arguments supplied, setup framework options class.
     *
     * @since 1.0.0
     *
     * @param array $args framework setup arguments. Used to change some base settings for the options including context.
     *
     * @param array $sections the sections an fields to be used.
     *
     * @return none
     */
    public function __construct( $args = array(), $sections = array() ){
            
    }
    
    /**
      * Returns the default arguments for the $args property.
      *
      * This gets merged with user supplied array via <code>parse_args</code>.
      *
      * @since 1.0.0
      *
      * @return array
      */
    protected function default_args(){
        
        return array(
            'option_name'   => 'option_name',
            'context'       => 'page',
            'sections'      => array(),
            'default_layout' => 'options-normal',
            'rel_id' => ''
        );
        
    }
    
    /**
      * Returns the default arguments for the $sections property.
      *
      * This gets merged with user supplied array via <code>parse_args</code>.
      *
      * @since 1.0.0
      *
      * @return array
      */
    protected function section_args(){
        
        return array(
            'dash_icon' => '',
            'title' => '',
            'description' => '',
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(),
            'tabs' => array(),
            'caps' => array(),
            'roles' => array()
        );
        
    }
    
    /**
      * Returns the default arguments for the $field property (nested fields within the $sections property).
      *
      * This gets merged with user supplied array via <code>parse_args</code>.
      *
      * @since 1.0.0
      *
      * @return array
      */
    protected function field_args(){
        
        return array(
            'title' => '',
            'sub_title' => '',
            'description' => '',
            'type' => 'text',
            'required' => false,
            'required_message' => __('This option is required.', 'regina'),
            'default' => '',
            'value' => '',
            'caps' => array(),
            'roles' => array(),
            'conditions' => array(),
            'seperate' => false
        );
        
    }
    
    /**
      * Loops through supplied data and prepares the $sections array.
      *
      * This is different for each type of options so lets leave it blank
      *
      * @since 1.0.0
      *
      * @param array $sections framework setup arguments. Used to change some base settings for the options including context.
      *
      * @return none
      */
    protected function prepare_sections($sections, $id = null){
        
    }

    /**
      * Loops through supplied data and prepares the $section array.
      *
      * This is used to move nested tab fields into the scope of the $section and append the <code>tab_id</code>.
      * We do it this way so users can use the familiar nested field array syntax when creating the panel, we then just hook it up later.
      *
      * @since 1.0.6
      *
      * @param array $section framework setup arguments. Used to change some base settings for the options including context.
      *
      * @return $section
      */
    protected function prepare_tabs($section = array()){
        if(empty($section['tabs'])){
            return $section;
        }
        foreach($section['tabs'] as $id => $tab){
            foreach($tab['fields'] as $fieldid => $field){
                $field['tab_id'] = $id;
                $section['fields'][$fieldid] = $field;
            }
        }
        return $section;
    }
    
    /**
     * Loops through supplied data and prepares the $fields array.
     *
     * Function may also call itself on nested fields (groups). Returns a multilevel fields array.
     *
     * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
     * @uses Macho_Options::prepare_fields(); to prepare the nested fields contained in the supplied array.
     * @uses sanitize_key(); to sanitize the field ID.
     * @uses class_exists();
     * @uses unset();
     *
     * @since 1.0.0
     *
     * @param array $fields
     *
     * @param array $options
     *
     * @param string $key
     *
     * @return array
     */
    protected function prepare_fields($fields = array(), $options = array(), $key = ''){
        
        $out = array();
        if ( ! is_array( $options ) ) {
            $options = array();
        }
        foreach( $fields as $field_key => $field ){
            $options[$field_key] = isset($options[$field_key]) ? $options[$field_key] : '';
            $field_key = sanitize_key($field_key);
            $field['option_name'] = $this->args['option_name'];
            $field['id'] = $field_key;
            $field['name'] = $field_key;
            $field['value'] = (isset($options[$field_key])) ? $options[$field_key] : '';
            $out[$field_key] = $this->parse_args( $field, $this->field_args() );
            $type = $out[$field_key]['type'];
            if(isset(static::$field_types[$type]) && !class_exists(static::$field_types[$type]['class_name']) ){
                require_once static::$field_types[$type]['path'];
            }
            if(isset($out[$field_key]['fields'])){
                $_fields = $out[$field_key]['fields'];
                unset($out[$field_key]['fields']);
                $out[$field_key]['fields'] = $this->prepare_fields($_fields, $options[$field_key], $field['name']);
            }
        }
        return $out;
        
    }
    
    /**
     * Internal function used to supply the default array keys and types for the sections and fields supplied.
     *
     * This is used within the <code>format_option()</code> function to ensure all array keys are set when retrieving the values via <code>get_option</code>.
     *
     * @uses apply_filters();
     *
     * @since 1.0.0
     *
     * @return array
     */
    protected function get_options_schema(){
        
        $output = array();
        foreach($this->sections as $key => $section){
            foreach($section['fields'] as $field_key => $field){
                $output[$field_key] = apply_filters('macho/options/field/'.$field['type'].'/schema', '', $field);
            }
        }
        return $output;
        
    }
    
    /**
     * Attached to the <code>get_option_{$option_name}</code> filter this function merges the value blueprint with the actual data ensuring all keys are set.
     *
     * Also uses wp_unslash to remove slashes from values.
     *
     * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
     * @uses Macho_Options::get_options_schema(); to merge supplied data with a default array index for the option.
     * @uses wp_unslash(); to remove escaped slashes.
     * @uses apply_filters();
     *
     * @since 1.0.0
     *
     * @return array
     */
    protected function get_default_values(){
        
        $output = array();
        foreach($this->sections as $key => $section){
            foreach($section['fields'] as $field_key => $field){
                $output[$field_key] = apply_filters('macho/options/field/'.$field['type'].'/default', $field['default'], $field);
            }
        }
        return $output;
        
    }
    
    /**
     * Saving the options usually means the <code>$_POST</code> array conatins the group clones in the array keys.
     *
     * These usually look like <code>##field-id-clone##</code>. this function simply looks for the existance of two hashes at the start of the supplied keys and removes them from the returning array if present.
     *
     * @uses is_array(); during foreach.
     * @uses array_filter(); to remove empty indexes.
     * @uses Macho_Options::remove_clones(); to remove nested clones.
     *
     * @since 1.0.0
     *
     * @param array $value data supplied from the save action.
     *
     * @return array $out the sanitized and trimmed array data.
     */
    protected function remove_clones($value){
        
        if(!is_array($value)){return $value;}
        
        $out = array();
        foreach($value as $k => $v){
            if(is_array($v)){
                $v = array_filter($v);
                if(empty($v)){continue;}
            }
            if($k[0] != '#' && $k[1] != '#'){
                $out[$k] = $this->remove_clones($v);
            }
        }
        return $out;
        
    }
    
    /**
     * Load assets and add the screen options for any page. Maybe use in extended classes.
     *
     * @uses add_filter();
     * @uses wp_enqueue_style();
     * @uses wp_enqueue_script();
     * @uses wp_localize_script();
     *
     * @since 1.0.0
     *
     * @return none
     */
    protected function load_assets(){
        
        //enqueue scripts
        wp_enqueue_style( 'macho-options-css', Macho_Base::$url . 'assets/css/options.css', array(), $this->version );
        wp_enqueue_style( 'icon-picker-css', Macho_Base::$url . 'assets/css/icon-picker.css', array(), $this->version );
        wp_enqueue_style( 'nucleo-css', get_template_directory_uri() . '/assets/css/admin/nucleo.css', array(), $this->version );
        wp_register_style( 'macho-ui-css', Macho_Base::$url . 'assets/vendor/delta-ui/jquery-ui.css', array(), $this->version );
        wp_enqueue_style( 'font-awesome', Macho_Base::$url .'assets/css/font-awesome.css', array(), $this->version );

        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-accordion' );

        wp_enqueue_script( 'macho-options-js', Macho_Base::$url . 'assets/js/options-compiled.js', array('jquery', 'postbox', 'jquery-ui-core', 'jquery-ui-slider', 'icon-picker-js'), $this->version, true );
        wp_enqueue_script( 'icon-picker-js', MAcho_Base::$url . 'assets/js/icon-picker.js', array('jquery', 'postbox'), $this->version, true );

    }
    
    /**
     * Returns an array of required fields and there data.
     *
     * @uses isset();
     *
     * @since 1.0.0
     *
     * @return array
     */
    protected function get_required_js(){
        $out = array();
        foreach($this->sections as $key => $section){
            foreach($section['fields'] as $k => $field){
                if(isset($field['required']) && $field['required'] === true){
                    $out[$field['id']] = array(
                        'type' => $field['type'],
                        'msg' => $field['required_message']
                    );
                }
            }
        }
        return $out;
    }

    /**
     * Returns an array of conditional fields and their data.
     *
     * @uses Macho_Options::get_field_type();
     *
     * @since 1.0.1
     *
     * @return array
     */
    protected function get_conditionals(){
        $out = array();
        foreach($this->sections as $key => $section){
            foreach($section['fields'] as $k => $field){
                if(isset($field['conditions']) && !empty($field['conditions'])){
                	$conds = $field['conditions'];
                	foreach($conds as $k => $cond){
                		foreach($cond as $k2 => $v){
                			if(!isset($v['value'])){
                				$conds[$k][$k2]['value'] = '';
                				$v['value'] = '';
                			}
                			if(!isset($v['id'])){
                				$conds[$k][$k2]['id'] = '';
                				$v['id'] = '';
                			}
                			if(!isset($v['field_type'])){
                				$conds[$k][$k2]['field_type'] = $this->get_field_type($v['id']);
                			}
                			if(!isset($v['type'])){
                				$conds[$k][$k2]['type'] = '==';
                			}
                			if(is_array($v['value'])){
                				$conds[$k][$k2]['value'] = implode('|', $v['value']);
                			}
                		}
                	}
                    $out[$field['id']] = $conds;
                }
            }
        }
        return $out;
    }

    /**
     * Returns the field type for any given field.
     *
     *
     * @since 1.0.1
     *
     * @return string
     */
    private function get_field_type($key){
    	foreach($this->sections as $section){
    		foreach($section['fields'] as $id => $field){
    			if($id == $key){
    				return $field['type'];
    				break;
    			}
    		}
    	}
    }



    
    /**
     * Hooked into the load post page this then runs the preparation on meta box titles to add the desired icon font if set.
     *
     * @uses Macho_Options::prepare_meta_box_titles();
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function setup_meta_box_titles(){
        $this->prepare_meta_box_titles();
        $this->prepare_meta_box_titles('advanced');
        $this->prepare_meta_box_titles('side');
    }

    /**
     * Localize the main options script
     *
     * @uses Macho_Base::parse_args();
     *
     * @since 1.0.1
     *
     * @return none
     */
    public function localize_script($data = array()){
        $data = $this->parse_args($data, array(
            'L10n' => array(
                'warning_defaults' => __('Are you sure?', 'regina'),
            ),
            'required' => $this->get_required_js(),
            'conditionals' => $this->get_conditionals(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'option_name' => $this->args['option_name'],
            'nonce' => wp_create_nonce('macho_nonce'),
            'rel_id' => ''
        ));
        wp_localize_script( 'macho-options-js', 'macho', $data );
    }
    
    /**
     * This method adds the HTML required to show the layout options within the screen settings tab.
     *
     * @uses wp_get_current_user();
     * @uses get_user_option();
     *
     * @since 1.0.0
     *
     * @param string $html
     *
     * @return string $html
     */
    public function add_screen_settings($html = ''){
        $user = wp_get_current_user();
        $val = get_user_option("optionslayout", $user->ID);
        if(!$val){
            $val = $this->args['default_layout'];
        }
        return $html .= '<h5>' . __('Options Layout', 'regina').'</h5>
		      <div class="columns-prefs">'.__('Style:', 'regina').'
                <label class="columns-prefs-options-layout">
					<input type="radio" class="options-layout-input" name="options_layout" value="options-normal" '. checked( 'options-normal', $val, false ).' /> '.__('Standard', 'regina').'
                </label>
                <label class="columns-prefs-options-layout">
					<input type="radio" class="options-layout-input" name="options_layout" value="options-block" '. checked( 'options-block', $val, false ).' /> '.__('Block', 'regina').'
                </label>
            </div>';   
    }
    
    /**
     * Save ajax requests sent via the <code>wp_ajax...</code> action. This saves the layout as either normal or block for the current user.
     *
     * @uses wp_die();
     * @uses check_ajax_referer();
     * @uses update_user_option();
     * @uses sanitize_html_class();
     * @uses wp_get_current_user();
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function save_screen_settings() {
        check_ajax_referer( 'screen-options-nonce', 'screenoptionnonce' );
        if(!isset( $_POST['options_layout'] )){
            wp_die(-1);
        }
    
        if ( ! $user = wp_get_current_user() )
            wp_die( -1 );

        update_user_option($user->ID, "optionslayout", sanitize_html_class($_POST['options_layout']), true);
    
        wp_die( 1 );
    }
    
    /**
     * Meta box filter which allows you to add additional classes to the meta box.
     *
     * the framework uses this to apply 0px padding and margins on the <code>.inside</code> class for better layouts.
     *
     * @since 1.0.0
     *
     * @param array $classes
     *
     * @return array $classes
     */
    public function remove_box_padding($classes){
        $classes[] = 'options-postbox';
        return $classes;
    }
    
    /**
     * The prepare meta box titles function is used to run through all the sections, check if a dashicon is to be prepended to the title, and if it is alter the global <code>$wp_meta_boxes</code> titles accordingly.
     *
     * @uses get_current_screen();
     * @uses isset();
     *
     * @since 1.0.0
     *
     * @param string $context which meta boxes we are going to alter.
     *
     * @param object $post WP_Post object
     *
     * @return none
     */
    protected function prepare_meta_box_titles($context = 'normal'){
        global $wp_meta_boxes;
        $screen = get_current_screen();
        $page = $screen->id;
    
        $i = 0;
        do {
    
            if ( !isset($wp_meta_boxes) || !isset($wp_meta_boxes[$page]) || !isset($wp_meta_boxes[$page][$context]) )
                break;
    
            foreach ( array('high', 'sorted', 'core', 'default', 'low') as $priority ) {
                if ( isset($wp_meta_boxes[$page][$context][$priority]) ) {
                    foreach ( (array) $wp_meta_boxes[$page][$context][$priority] as $key => $box ) {
                        if ( false == $box || ! $box['title'] )
                            continue;
                        $i++;
                        if(isset($this->sections[$box['id']]['dash_icon']) && $this->sections[$box['id']]['dash_icon'] != ''){
                            $wp_meta_boxes[$page][$context][$priority][$key]['title'] = '<div class="dashicons dashicons-'.$this->sections[$box['id']]['dash_icon'].'"></div> ' . $box['title'];
                            continue;
                        }
                    }
                }
            }
        } while(0);
        
    }

    
    /**
     * Display meta boxes for all registered sections.
     *
     * @uses wp_nonce_field();
     * @uses isset();
     * @uses do_action();
     *
     * @since 1.0.0
     *
     * @param object|null $post WP_Post object
     *
     * @param array $data additional data
     *
     * @return none
     */
    public function box_content($post = null, $data = array()){
        
        if($this->added_nonce_field !== true){
            $this->added_nonce_field = true;
            wp_nonce_field('save_'.$this->args['option_name'], $this->args['option_name'].'_nonce');
        }
        
        
        $section_id = $data['args'];

        if(empty($this->sections[$section_id]['tabs'])){
            echo '<img class="options-spinner" src="'.admin_url('images/spinner-2x.gif').'"/>';
            echo '<table class="options-table-responsive options-table">';

            echo '<tr>';

                if($this->sections[$section_id]['description'] != ''){
                    echo '<td colspan="2">'.$this->sections[$section_id]['description'].'</td>';
                }



            echo '</tr>';


                foreach($this->sections[$section_id]['fields'] as $key => $field){
                    echo '<tr id="field-'.$key.'">';
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
                        echo '<td class="field field-type-'.$field['type'].'">';
                            if(!isset(static::$field_types[$field['type']])){
                                echo '<p><strong>'.__('Field type not registered!','regina').'</strong></p>';
                            }else{
                                do_action('macho/options/field/'.$field['type'].'/render', $field, $this);
                            }
                            if($field['description'] != ''){
                                echo '<p class="description">'.$field['description'].'</p>';
                            }
                        echo '</td>';
                    echo '</tr>';
                }
            echo '</table>';
        }else {
            echo '<div class="options-section-description">' . $this->sections[$section_id]['description'] . '</div>';


            echo '<div class="options-tabs-tabs">';

            foreach ($this->sections[$section_id]['tabs'] as $id => $tab) {
                echo '<a href="#" data-tab="' . $section_id . '-' . $id . '" title="' . $tab['title'] . '"><span class="'.$tab['icon'].'"></span>' . $tab['title'] . '</a>';
            }
            echo '</div>';
            echo '<img class="options-spinner" src="' . admin_url('images/spinner-2x.gif') . '"/>';
            echo '<div class="options-tabs-tables">';
            foreach ($this->sections[$section_id]['tabs'] as $id => $tab) {


                echo '<div id="' . $section_id . '-' . $id . '" class="options-tab">';
                echo '<table class="options-table-responsive options-table">';

               echo '<tr>';
                if ($tab['description'] != '') {
                    //echo '<tr>';
                        echo '<td colspan="2">' . $tab['description'] . '</td>';
                    //echo '</tr>';
                }


                // save button
                /*
                echo '<div id="publishing-action">';
                echo get_submit_button( apply_filters('macho/options/page/'.$this->args['option_name'].'/save/button', $this->args['messages']['save_button']), 'primary large', 'macho[save]', false);
                echo '</div>';
                */

                echo '</tr>';


                foreach ($this->sections[$section_id]['fields'] as $key => $field) {
                    if (!isset($field['tab_id']) || $field['tab_id'] != $id) {
                        continue;
                    }

                    echo '<tr id="field-' . $key . '">';
                    echo '<td class="label">';
                    echo '<label>' . $field['title'];
                    if ($field['required']) {
                        echo '<span class="options-required">*</span>';
                    }
                    echo '</label>';
                    if ($field['sub_title'] != '') {
                        echo '<p class="description">' . $field['sub_title'] . '</p>';
                    }
                    echo '</td>';
                    echo '<td class="field field-type-' . $field['type'] . '">';
                    if (!isset(static::$field_types[$field['type']])) {
                        echo '<p><strong>' . __('Field type not registered!', 'regina') . '</strong></p>';
                    } else {
                        do_action('macho/options/field/' . $field['type'] . '/render', $field, $this);
                    }
                    if ($field['description'] != '') {
                        echo '<p class="description">' . $field['description'] . '</p>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</table>';
                echo '</div>';
            }
            echo '</div>';
        }
    }
}