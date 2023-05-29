<?php
/**
 * Macho_Options_Page
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.2
 */

/**
 * Macho_Options_Page. Create and stores fields and sections.
 */
class Macho_Options_Page extends Macho_Options{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.2';
    
    /**
     * @var $page Macho_Page instance. If supplied the Options class should be used in page context and we use this to register the output we need on the page.
     */
    public $page = null;
    
    /**
     * __construct() parse arguments supplied, setup framework depending on the $context supplied.
     *
     * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
     * @uses Macho_Options::prepare_sections(); to prepare data.
     * @uses Macho_Options::provide();
     * @uses add_action();
     * @uses add_filter();
     *
     * @since 1.0.0
     *
     * @param array $args framework setup arguments. Used to change some base settings for the options including context.
     *
     * @param array $sections the sections an fields to be used.
     *
     * @param object $page if suppplied should be an instance of Macho_Page and used to render the meatboxes on none metabox pages.
     *
     * @return none
     */
    public function __construct( $args = array(), $sections = array() ){
        
        $this->args = $this->parse_args( $args, $this->default_args(), 'macho/options/page/args' );
        
        //prepare sections and fields before merging values
        $sections = $this->parse_args( $sections, array(), 'macho/options/page/'.$this->args['option_name'].'/sections' );
        
        //save user data about layouts used - this is global
        add_action('wp_ajax_options_save_layout', $this->provide('save_screen_settings'));
            
        $this->page = new Macho_Page( $this->args['page_args'] );
        
        $this->options = $this->get_option($this->args['option_name']);
    
        $this->prepare_sections($sections);
    
        add_action('macho/page/'.$this->page->args['slug'].'/load', $this->provide('load_page'));
        add_action('macho/page/'.$this->page->args['slug'].'/render', $this->provide('render_page'));

        if(isset($this->args['page_args']['network']) && $this->args['page_args']['network'] == true){
            add_filter( 'site_option_' . $this->args['option_name'], $this->provide( 'format_option' ) );
        }else{
            add_filter( 'option_' . $this->args['option_name'], $this->provide( 'format_option' ) );
        }
            
    }

    private function get_option($option_name, $default_value = false, $use_cache = true){
        if(isset($this->args['page_args']['network']) && $this->args['page_args']['network'] == true){
            return get_site_option($option_name, $default_value, $use_cache);
        }
        return get_option($option_name, $default_value);
    }

    private function update_option($option_name, $value){
        if(isset($this->args['page_args']['network']) && $this->args['page_args']['network'] == true){
            return update_site_option($option_name, $value);
        }
        return update_option($option_name, $value);
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
        
        return self::parse_args(array(
            'option_name'   => 'option_name',
            'sections'      => array(),
            'default_layout' => 'options-normal',
            'page_args' => array(),
            'restore' => true,
            'show_updated' => true,
            'messages' => array(
            	'save_button' => __('Save Options', 'regina'),
            	'saved' => __('Settings saved.', 'regina'),
            	'restore' => __('Settings reset to defaults.', 'regina'),
            	'save_box' => ''
            )
        ), parent::default_args());
        
    }
    
    /**
      * Loops through supplied data and prepares the $sections array.
      *
      * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
      * @uses Macho_Options::get_default_values(); to merge default values with the saved values if not set.
      * @uses Macho_Options::prepare_fields(); to prepare the nested fields contained in the supplied array.
      * @uses sanitize_key(); to sanitize the section ID.
      *
      * @since 1.0.0
      *
      * @param array $sections framework setup arguments. Used to change some base settings for the options including context.
      *
      * @param array $context the sections an fields to be used.
      *
      * @param object $id if suppplied should be an instance of Macho_Page and used to render the meatboxes on none metabox pages.
      *
      * @return none
      */
    protected function prepare_sections($sections, $id = null, $context = null){

        $this->options = $this->get_option($this->args['option_name']);
        if(!$this->options || $this->options == ''){
            $this->options = $this->get_default_values();
        }
        
        foreach($sections as $key => $section){
            $key = sanitize_key($key);
            $this->sections[$key] = $this->parse_args( $section, $this->section_args() );
            $this->sections[$key] = $this->prepare_tabs($this->sections[$key]);
            $fields = $this->sections[$key]['fields'];
            unset($this->sections[$key]['fields']);
            $this->sections[$key]['fields'] = $this->prepare_fields($fields, $this->options, $key);
        }
        
    }
    
    /**
     * Attached to the <code>get_option_{$option_name}</code> filter this function merges the value blueprint with the actual data ensuring all keys are set.
     *
     * Also uses wp_unslash to remove slashes from values.
     *
     * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
     * @uses Macho_Options::get_options_schema(); to merge supplied data with a default array index for the option.
     * @uses wp_unslash(); to remove escaped slashes.
     *
     * @since 1.0.0
     *
     * @param array $value data supplied from the database.
     *
     * @return array
     */
    public function format_option($value = false){
        
        if( !is_array( $value ) ){
            $value = array();
        }
        return wp_unslash($this->parse_args($value, $this->get_options_schema(), 'macho/options/page/'.$this->args['option_name'].'/get'));
        
    }
    
    /**
     * Fires events on the <code>load_page-{$page-hook}</code> hook point to register/enqueue styles/javascript.
     *
     * Save the page options. Register metaboxes and screen options.
     * It also adds filters to the metaboxes which help remove the padding applied by WordPress.
     *
     * @uses isset();
     * @uses check_admin_refferer();
     * @uses update_option();
     * @uses add_screen_option();
     * @uses get_option();
     * @uses add_action();
     * @uses do_action();
     * @uses add_filter();
     * @uses wp_enqueue_style();
     * @uses wp_enqueue_script();
     * @uses wp_localize_script();
     * @uses add_meta_box();
     * @uses time();
     * @uses Macho_Options::prepare_sections();
     * @uses Macho_Options::get_default_values();
     * @uses Macho_Options::remove_clones();
     * @uses Macho_Options::provide();
     *
     * @since 1.0.0
     *
     * @param object $page_object Macho_Page instance.
     *
     * @return none
     */
    public function load_page($page_object){
        
        if(isset($_POST['restore-defaults']) && $_POST['restore-defaults'] == true && $this->args['restore'] == true){
            check_admin_referer('save_'.$this->args['option_name'], $this->args['option_name'].'_nonce');
            $newoptions = $this->get_default_values();
            $this->update_option($this->args['option_name'], $newoptions);
            //save seperate fields if needed
            foreach($this->sections as $section){
                foreach($section['fields'] as $id => $field){
                    if($field['seperate'] === true){
                        $this->update_option($this->args['option_name'].'_'.$id, $newoptions[$id]);
                    }
                }
            }
            $option = get_option($this->args['option_name'] . '_meta' );
            $option['options-updated'] = time();
            $this->update_option($this->args['option_name'] . '_meta', $option);
            add_action('admin_notices', $this->provide('settings_default_updated'));
            add_action('network_admin_notices', $this->provide('settings_default_updated'));
        }elseif(isset($_POST[$this->args['option_name']])){
            check_admin_referer('save_'.$this->args['option_name'], $this->args['option_name'].'_nonce');
            $options = $_POST[$this->args['option_name']];
            $newoptions = apply_filters('macho/options/page/'.$this->args['option_name'].'/save', $this->remove_clones($_POST[$this->args['option_name']]));
            $this->update_option($this->args['option_name'], $newoptions);
            //save seperate fields if needed
            foreach($this->sections as $section){
                foreach($section['fields'] as $id => $field){
                    if($field['seperate'] === true){
                        $this->update_option($this->args['option_name'].'_'.$id, $newoptions[$id]);
                    }
                }
            }
            $option = $this->get_option($this->args['option_name'] . '_meta' );
            $option['options-updated'] = time();
            $this->update_option($this->args['option_name'] . '_meta', $option);
            add_action('admin_notices', $this->provide('settings_updated'));
            add_action('network_admin_notices', $this->provide('settings_updated'));
            do_action('macho/options/page/'.$this->args['option_name'].'/save', $newoptions);
        }
        
        //re run prepare sections to ensure the options are set
        $this->prepare_sections($this->sections);
        
        //add column choice and style choices
        add_screen_option('layout_columns', array('max' => 1, 'default' => 1) );
        add_filter('screen_settings', $this->provide('add_screen_settings'));
        
        $this->load_assets();
        $this->localize_script(array(
            'context' => 'page'
        ));

        /*
         * This code was used to display a side meta-box with the save button in it (hah)
         */
        //add_meta_box( 'submitdiv', __( 'Save Options', 'regina' ), $this->provide( 'save_box_content' ), get_current_screen(), 'normal', 'high' );

        foreach($this->sections as $key => $section) {
            add_meta_box($key, $section['title'], $this->provide('box_content'), get_current_screen(), $section['context'], $section['priority'], $key);
            add_filter('postbox_classes_' . get_current_screen()->id . '_' . $key, $this->provide('remove_box_padding'));
        }

        foreach($this->sections as $key => $section){
            foreach($section['fields'] as $k => $field){
                if(isset(static::$field_types[$field['type']])){
                    do_action('macho/options/field/'.$field['type'].'/enqueue', $field, static::$field_types[$field['type']]);
                }
            }
        }
        
    }
    
    /**
     * Display a settings updated notice on the <code>admin_notices</code> action.
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function settings_updated(){
        echo '<div class="updated"><p><strong>'.apply_filters('macho/options/page/'.$this->args['option_name'].'/saved/message', $this->args['messages']['saved']).'</strong></p></div>';
        do_action('macho/options/page/'.$this->args['option_name'].'/saved/messages', $this);
    }
    
    /**
     * Display a settings updated message when restire defaults is clicked.
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function settings_default_updated(){
        echo '<div class="updated info"><p><strong>'.apply_filters('macho/options/page/'.$this->args['option_name'].'/restore/message', $this->args['messages']['restore']).'</strong></p></div>';
        do_action('macho/options/page/'.$this->args['option_name'].'/restore/messages', $this);
    }
    
    /**
     * Renders the options page if $context is page. Hooked onto the Macho_Page render action.
     *
     * @uses get_admin_page_title();
     * @uses wp_nonce_field();
     * @uses get_current_screen();
     * @uses do_meta_boxes();
     * @uses Macho_Options::prepare_meta_box_titles();
     *
     * @since 1.0.0
     *
     * @param object $page_object Macho_Page instance
     *
     * @return none
     */
    public function render_page($page_object){
        
        //current workaround to prevent icons in screen options
        $this->setup_meta_box_titles();
        
        echo '<div class="wrap">';
            echo '<h1>'.get_admin_page_title().'</h1>';
            echo '<form method="post" action="" id="options-form">';
                /* Used to save closed metaboxes and their order */
				wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
				wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
            
                echo '<input type="hidden" name="save-action" value="save-options-'.$this->args['option_name'].'" />';


                /**
                 * we should re-use this code when we'll add advertising on the right side of the framework options
                 */
                //$columns = ( 1 == get_current_screen()->get_columns() ) ? '1' : '2';
            echo '<div class="macho-wrapper">';
                echo '<div class="branding-box">';

                    echo '<div class="branding-logo">';
                        echo '<img src="'.esc_url( get_template_directory_uri() ).'/framework/panels/admin/assets/images/logo.png">';
                    echo '</div>';
                    echo '<ul class="running-on">';
                        echo '<li>'.MT_THEME_NAME . ' : <strong>' . MT_THEME_VERSION . '</strong></li>';
                        // echo '<li>'. 'Muscle Core: <strong>' . MT_FRAMEWORK_VERSION . '</strong></li>';
                    echo '</ul>';
                echo '</div>';


                echo '<div class="latest-news">';
                    echo '<h2>'.__('Thank you for choosing '.MT_THEME_NAME, 'regina').'</h2>';
                    echo '<p>'.__('Registering your purchase will allow you to get support and automatic theme updates. We know you will love it. If you run into any trouble, please do not hesitate to fill out a support ticket. Our dedicated staff is ready to take on all support requests.', 'regina').'</p>';
                    echo '<hr />';
                    echo '<ul class="support-links-theme-page">';
                        echo '<li><i class="dashicons dashicons-welcome-view-site"></i><a target="_blank" href="'.esc_url( 'http://docs.machothemes.com' ).'">Theme Documentation</a></li>';
                        echo '<li><i class="dashicons dashicons-format-chat"></i><a target="_blank" href="http://www.machothemes.com/contact/">Get Support</a></li>';
                    echo '</ul>';
                echo '</div>';
        echo '<div class="clear"></div>';
            echo '</div><!--/.macho-wrapper-->';

        echo '<div id="publishing-action"><span class="spinner"></span>'.get_submit_button( apply_filters('macho/options/page/'.$this->args['option_name'].'/save/button', $this->args['messages']['save_button']), 'primary large', 'macho[save]', false).'</div>';
        echo '<br />';

                    echo '<div id="poststuff">';
                    //echo '<div id="post-body" class="metabox-holder columns-'.$columns.'">';
                    echo '<div id="post-body" class="metabox-holder">';
                        echo '<div id="postbox-container-1" class="postbox-container">';
                            do_meta_boxes( get_current_screen()->id, 'side', null );
                        echo '</div>';
                        echo '<div id="postbox-container-2" class="postbox-container">';
                            do_meta_boxes( get_current_screen()->id, 'normal', null );
                            do_meta_boxes( get_current_screen()->id, 'advanced', null );
                        echo '</div>';
                    echo '</div>';

                    echo '<div id="publishing-action"><span class="spinner"></span>'.get_submit_button( apply_filters('macho/options/page/'.$this->args['option_name'].'/save/button', $this->args['messages']['save_button']), 'primary large', 'macho[save]', false).'</div>';
                    echo '<div id="delete-action"><a class="submitdelete deletion options-restore-default" href="#">' . __( 'Restore ALL Theme Defaults', 'regina' ) . '</a></div>';
                echo '</div>';
            echo '</form>';
        echo '</div>';
    }
    
    /**
     * Display the Save Options meta box on options pages.
     *
     * @uses get_option();
     * @uses date();
     * @uses get_submit_button();
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function save_box_content(){
        
        $option = $this->get_option($this->args['option_name'] . '_meta');
            
        $updated = ( isset($option['options-updated']) ) ? date(get_option('date_format') . ' ' . get_option('time_format'), $option['options-updated']) : __('Never', 'regina');
        
        echo '<div class="submitbox" id="submitpost">'.
                (($this->args['show_updated'] == true) ? '<div id="minor-publishing">
                <div id="misc-publishing-actions">
                    <div class="misc-pub-section curtime misc-pub-curtime"><span id="timestamp">
                        Last Updated: <b>' . $updated . '</b></span>
                    </div>
                </div>
                <div class="clear"></div>
            </div>' : '').

                (($this->args['messages']['save_box'] != '') ? '<div class="misc-pub-section macho-options-save-box-message">'.$this->args['messages']['save_box'].'</div>' : '')


                
            .'<div id="major-publishing-actions">'.
                (($this->args['restore'] == true) ? '<div id="delete-action"><a class="submitdelete deletion options-restore-default" href="#">' . __( 'Restore ALL Theme Defaults', 'regina' ) . '</a></div>' : '')
            
                .'<div id="publishing-action"><span class="spinner"></span>'.get_submit_button( apply_filters('macho/options/page/'.$this->args['option_name'].'/save/button', $this->args['messages']['save_button']), 'primary large', 'macho[save]', false).'</div>
                <div class="clear"></div>
            </div>
        </div>';
    }
}