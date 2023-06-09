<?php
/**
 * Macho_Page
 *
 * @package Macho
 * @since 1.0.0
 * @version 1.0.1
 */

/**
 * Macho_Page can be used on its own or supplied to the Macho_Options class as a dependancy for page rendering
 */
class Macho_Page extends Macho_Base{

    /**
     * @var string $version Class version.
     */
    public $version = '1.0.1';
    
    /**
     * @var string $page will be set as the unique page id when using add_**_page functions.
     */
    public $page = null;
 
    /**
     * __construct() parse arguments supplied, fire page creation action, and add callback to the render action if supplied.
     *
     * @uses Macho_Options::parse_args(); to merge supplied data with some sane defaults.
     * @uses Macho_Options::default_args(); to provide default data.
     * @uses Macho_Options::provide();
     * @uses sanitize_key();
     * @uses add_action();
     * @uses Add_filter
     *
     * @since 1.0.1
     *
     * @param array $args class setup arguments. Used to change some base settings for the class.
     *
     * @return none
     */
    public function __construct( $args = array() ){
        
        $this->args = $this->parse_args( $args, $this->default_args(), 'macho/page/args' );
        
        //lowercase dashes and underscores
        $this->args['slug'] = sanitize_key($this->args['slug']);
        
        //register the page
        $action = ($this->args['network'] == true) ? 'network_admin_menu' : 'admin_menu';
        add_action($action, $this->provide('_register_page'));
        
        //add render callback if needed
        if(false !== $this->args['callback']){
            add_action('macho/page/'.$this->args['slug'].'/render', $this->args['callback'], 1);
        }
    }
    
    /**
     * Returns an array of default arguments for the class.
     *
     * @since 1.0.0
     *
     * @return array
     */
    private function default_args(){
        
        return array(
            'slug' => 'options',
            'menu_title' => __( 'Options', 'regina' ),
            'page_title' => __( 'Options', 'regina' ),
            'parent' => '',
            'cap' => 'manage_options',
            'priority' => null,
            'menu_icon' => '',
            'page_icon' => 'icon-themes',
            'callback' => false,
            'network' => false
        );
        
    }
    
    /**
     * Registers the page in the WordPress Admin. Attach methods to the load and enqueue actions for registered page.
     *
     * @uses add_submenu_page();
     * @uses add_menu_page();
     * @uses Macho_Options::provide();
     * @uses add_action();
     *
     * @since 1.0.0
     *
     * @return none
     */
    public function _register_page(){
        
        if( $this->args['parent'] != '' ){
            $this->page = add_submenu_page(
                    $this->args['parent'],
                    $this->args['page_title'], 
                    $this->args['menu_title'], 
                    $this->args['cap'], 
                    $this->args['slug'], 
                    $this->provide('_page_html')
            );
        }else{
            $this->page = add_menu_page(
                    $this->args['page_title'], 
                    $this->args['menu_title'], 
                    $this->args['cap'], 
                    $this->args['slug'], 
                    $this->provide('_page_html'),
                    $this->args['menu_icon'],
                    $this->args['priority']
            );
        }
        add_action('admin_print_styles-'.$this->page, $this->provide('_enqueue'));
        add_action('load-'.$this->page, $this->provide('_load_page'));
        
    }
    
    /**
     * Allows the use of actions to render the page html based on the <code>$args['slug']</code> suppplied on creation.
     *
     * @uses do_action();
     *
     * @since 1.0.0
     */
    public function _page_html(){
        do_action('macho/page/'.$this->args['slug'].'/render', $this);
    }
    
    /**
     * Allows the use of actions to enqueue on the page based on the <code>$args['slug']</code> suppplied on creation.
     *
     * @uses do_action();
     *
     * @since 1.0.0
     */
    public function _enqueue(){
        do_action('macho/page/'.$this->args['slug'].'/enqueue', $this);
    }
    
    /**
     * Allows the use of actions on the page load action based on the <code>$args['slug']</code> suppplied on creation.
     *
     * @uses do_action();
     *
     * @since 1.0.0
     */
    public function _load_page(){
        do_action('macho/page/'.$this->args['slug'].'/load', $this);
    }
    
}