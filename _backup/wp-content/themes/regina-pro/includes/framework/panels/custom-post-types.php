<?php

$theme_options = get_option( strtolower(MT_THEME_NAME).'_options' );

/**
 * Service Custom Post Type
 */

$name = 'Service';   //name will be converted to lowercase with underscores for spaces and dashes

$args = array(
    //normal register_post_type() args, defaults used unless otherwise stated
    'labels'               => array(
        'name'               => sprintf(__('%ss', 'regina'), $name),
        'singular_name'      => $name,
        'menu_name'          => sprintf(__( '%ss', 'regina' ), $name),
        'name_admin_bar'     => sprintf(__( '%s', 'regina' ), $name),
        'add_new'            => __( 'Add New', 'regina' ),
        'add_new_item'       => sprintf(__( 'Add New %s', 'regina' ), $name),
        'new_item'           => sprintf(__( 'New %s', 'regina' ), $name),
        'edit_item'          => sprintf(__( 'Edit %s', 'regina' ), $name),
        'view_item'          => sprintf(__( '', 'regina' ), $name),
        'all_items'          => sprintf(__( 'All %ss', 'regina' ), $name),
        'search_items'       => sprintf(__( 'Search %ss', 'regina' ), $name),
        'parent_item_colon'  => sprintf(__( 'Parent %ss:', 'regina' ), $name),
        'not_found'          => sprintf(__( 'No %ss found.', 'regina' ), strtolower($name)),
        'not_found_in_trash' => sprintf(__( 'No %ss found in Trash.', 'regina' ), strtolower($name))
    ),
    'description'          => '',
    'public'               => false,
    'hierarchical'         => false,
    'exclude_from_search'  => true,
    'publicly_queryable'   => false,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'show_in_nav_menus'    => false,
    'show_in_admin_bar'    => true,
    'menu_position'        => null,
    'menu_icon'            => 'dashicons-awards',
    'capability_type'      => 'post',
    'capabilities'         => array(),
    'map_meta_cap'         => null,
    'supports'             => array('title', 'editor'),
    'register_meta_box_cb' => null,
    'taxonomies'           => array(),//default is empty array, we have assigned the tag taxonomy for demostration purposes. Any taxonomies added here must be builtin or registered before the init priority 10. If you want to assign Fluent_Taxonomies you need to declare this post type when registering the taxonomy.
    'has_archive'          => true,//default = false but we have set to true so you can see the archive template overwrite
    'rewrite'              => array('slug'=>'service'),
    'query_var'            => false,
    'can_export'           => true,
    'delete_with_user'     => null,
    '_edit_link'           => 'post.php?post=%d',
    //our custom messages array, defaults detailed below. this allows you to change the notices when posts are changed in some way without adding yet more filters
    'messages' => array(
        //these strings are passed through sprintf with the post type name, so use %s if you want that functionality
        0  => '', // Unused. Messages start at index 1.
        1  => __( '', 'regina' ),
        2  => __( 'Custom field updated.', 'regina' ),
        3  => __( 'Custom field deleted.', 'regina' ),
        4  => __( '', 'regina' ),
        5  => __( '%s restored to revision from %s', 'regina' ),
        6  => __( '%s published.', 'regina' ),
        7  => __( '%s saved.', 'regina' ),
        8  => __( '%s submitted.', 'regina' ),
        9  => __( '%s scheduled for: %s.', 'regina' ),
        10 => __( '%s draft updated.', 'regina' )
    ),
    //want to disable people adding new posts? this will remove the add new menu item and the add new button in the and list page, it will also redirect post-new.php?post_type=*** back to the list page.
    'disable_add_new' => false,
);

new Macho_Post_Type($name, $args);

/**
 * Member Custom Post Type
 */

$name = 'Member';   //name will be converted to lowercase with underscores for spaces and dashes
$member_rewrite = get_theme_mod('regina_cpt_rewrite', 'member');

$args = array(
    //normal register_post_type() args, defaults used unless otherwise stated
    'labels'               => array(
        'name'               => sprintf(__('%ss', 'regina'), $name),
        'singular_name'      => $name,
        'menu_name'          => sprintf(__( '%ss', 'regina' ), $name),
        'name_admin_bar'     => sprintf(__( '%s', 'regina' ), $name),
        'add_new'            => __( 'Add New', 'regina' ),
        'add_new_item'       => sprintf(__( 'Add New %s', 'regina' ), $name),
        'new_item'           => sprintf(__( 'New %s', 'regina' ), $name),
        'edit_item'          => sprintf(__( 'Edit %s', 'regina' ), $name),
        'view_item'          => sprintf(__( '', 'regina' ), $name),
        'all_items'          => sprintf(__( 'All %ss', 'regina' ), $name),
        'search_items'       => sprintf(__( 'Search %ss', 'regina' ), $name),
        'parent_item_colon'  => sprintf(__( 'Parent %ss:', 'regina' ), $name),
        'not_found'          => sprintf(__( 'No %ss found.', 'regina' ), strtolower($name)),
        'not_found_in_trash' => sprintf(__( 'No %ss found in Trash.', 'regina' ), strtolower($name))
    ),
    'description'          => '',
    'public'               => true,
    'hierarchical'         => false,
    'exclude_from_search'  => true,
    'publicly_queryable'   => true,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'show_in_nav_menus'    => false,
    'show_in_admin_bar'    => true,
    'menu_position'        => null,
    'menu_icon'            => 'dashicons-awards',
    'capability_type'      => 'post',
    'capabilities'         => array(),
    'map_meta_cap'         => null,
    'supports'             => array('title', 'editor'),
    'register_meta_box_cb' => null,
    'taxonomies'           => array(),//default is empty array, we have assigned the tag taxonomy for demostration purposes. Any taxonomies added here must be builtin or registered before the init priority 10. If you want to assign Fluent_Taxonomies you need to declare this post type when registering the taxonomy.
    'has_archive'          => true,//default = false but we have set to true so you can see the archive template overwrite
    'rewrite'              => array( 'slug' => $member_rewrite ),
    'query_var'            => false,
    'can_export'           => true,
    'delete_with_user'     => null,
    '_edit_link'           => 'post.php?post=%d',
    //our custom messages array, defaults detailed below. this allows you to change the notices when posts are changed in some way without adding yet more filters
    'messages' => array(
        //these strings are passed through sprintf with the post type name, so use %s if you want that functionality
        0  => '', // Unused. Messages start at index 1.
        1  => __( '', 'regina' ),
        2  => __( 'Custom field updated.', 'regina' ),
        3  => __( 'Custom field deleted.', 'regina' ),
        4  => __( '', 'regina' ),
        5  => __( '%s restored to revision from %s', 'regina' ),
        6  => __( '%s published.', 'regina' ),
        7  => __( '%s saved.', 'regina' ),
        8  => __( '%s submitted.', 'regina' ),
        9  => __( '%s scheduled for: %s.', 'regina' ),
        10 => __( '%s draft updated.', 'regina' )
    ),
    //want to disable people adding new posts? this will remove the add new menu item and the add new button in the and list page, it will also redirect post-new.php?post_type=*** back to the list page.
    'disable_add_new' => false,
);

new Macho_Post_Type($name, $args);

/**
 * Testimonial Custom Post Type
 */

$name = 'Testimonial';   //name will be converted to lowercase with underscores for spaces and dashes

$args = array(
    //normal register_post_type() args, defaults used unless otherwise stated
    'labels'               => array(
        'name'               => sprintf(__('%ss', 'regina'), $name),
        'singular_name'      => $name,
        'menu_name'          => sprintf(__( '%ss', 'regina' ), $name),
        'name_admin_bar'     => sprintf(__( '%s', 'regina' ), $name),
        'add_new'            => __( 'Add New', 'regina' ),
        'add_new_item'       => sprintf(__( 'Add New %s', 'regina' ), $name),
        'new_item'           => sprintf(__( 'New %s', 'regina' ), $name),
        'edit_item'          => sprintf(__( 'Edit %s', 'regina' ), $name),
        'view_item'          => sprintf(__( '', 'regina' ), $name),
        'all_items'          => sprintf(__( 'All %ss', 'regina' ), $name),
        'search_items'       => sprintf(__( 'Search %ss', 'regina' ), $name),
        'parent_item_colon'  => sprintf(__( 'Parent %ss:', 'regina' ), $name),
        'not_found'          => sprintf(__( 'No %ss found.', 'regina' ), strtolower($name)),
        'not_found_in_trash' => sprintf(__( 'No %ss found in Trash.', 'regina' ), strtolower($name))
    ),
    'description'          => '',
    'public'               => false,
    'hierarchical'         => false,
    'exclude_from_search'  => true,
    'publicly_queryable'   => true,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'show_in_nav_menus'    => false,
    'show_in_admin_bar'    => true,
    'menu_position'        => null,
    'menu_icon'            => 'dashicons-awards',
    'capability_type'      => 'post',
    'capabilities'         => array(),
    'map_meta_cap'         => null,
    'supports'             => array('title', 'editor'),
    'register_meta_box_cb' => null,
    'taxonomies'           => array(),//default is empty array, we have assigned the tag taxonomy for demostration purposes. Any taxonomies added here must be builtin or registered before the init priority 10. If you want to assign Fluent_Taxonomies you need to declare this post type when registering the taxonomy.
    'has_archive'          => true,//default = false but we have set to true so you can see the archive template overwrite
    'rewrite'              => array('slug'=>'service'),
    'query_var'            => false,
    'can_export'           => true,
    'delete_with_user'     => null,
    '_edit_link'           => 'post.php?post=%d',
    //our custom messages array, defaults detailed below. this allows you to change the notices when posts are changed in some way without adding yet more filters
    'messages' => array(
        //these strings are passed through sprintf with the post type name, so use %s if you want that functionality
        0  => '', // Unused. Messages start at index 1.
        1  => __( '', 'regina' ),
        2  => __( 'Custom field updated.', 'regina' ),
        3  => __( 'Custom field deleted.', 'regina' ),
        4  => __( '', 'regina' ),
        5  => __( '%s restored to revision from %s', 'regina' ),
        6  => __( '%s published.', 'regina' ),
        7  => __( '%s saved.', 'regina' ),
        8  => __( '%s submitted.', 'regina' ),
        9  => __( '%s scheduled for: %s.', 'regina' ),
        10 => __( '%s draft updated.', 'regina' )
    ),
    //want to disable people adding new posts? this will remove the add new menu item and the add new button in the and list page, it will also redirect post-new.php?post_type=*** back to the list page.
    'disable_add_new' => false,
);

new Macho_Post_Type($name, $args);


 // Custom columns for Testimonial CPT
    add_filter("manage_edit-project_columns", "edit_project_columns" );
    add_action("manage_posts_custom_column", "custom_project_columns");

if( !function_exists( 'edit_project_columns' ) ) {

    function edit_project_columns($project_columns){

        $project_columns = array(
            "cb"                => "<input type ='checkbox' />",
            "title"             => "Title",
            "project_id"        => __('Project ID', 'regina'),
            "project_image"     => __("Image", "regina"),
            "date"              => "Date"
        );

        return $project_columns;
    }
}

if( !function_exists( 'custom_project_columns' ) ) {

    function custom_project_columns($project_column) {



        // access the global $post object
        global $post;

        // get post meta
        $project_options = get_post_meta($post->ID, strtolower(MT_THEME_NAME).'_options', true);

        switch ($project_column)
        {
            case "project_image":
                $image = wp_get_attachment_image_src( $project_options['project-main-image'], array(50,50) );
                echo '<img style="border-radius:4px;" width="'.$image[1].'" height="'.$image[2].'" src="'.$image[0].'">';
                break;
            case "project_id":
                echo $post->ID;
                break;
            
        }
    }
}


/**
 * Sections Custom Post Type
 */

$name = 'Section';   //name will be converted to lowercase with underscores for spaces and dashes

new Macho_Post_Type($name, array(
    //normal register_post_type() args, defaults used unless otherwise stated
    'labels'               => array(
        'name'               => sprintf(__('%ss', 'regina'), $name),
        'singular_name'      => $name,
        'menu_name'          => sprintf(__( '%ss', 'regina' ), $name),
        'name_admin_bar'     => sprintf(__( '%s', 'regina' ), $name),
        'add_new'            => __( 'Add New', 'regina' ),
        'add_new_item'       => sprintf(__( 'Add New %s', 'regina' ), $name),
        'new_item'           => sprintf(__( 'New %s', 'regina' ), $name),
        'edit_item'          => sprintf(__( 'Edit %s', 'regina' ), $name),
        'view_item'          => sprintf(__( '', 'regina' ), $name),
        'all_items'          => sprintf(__( 'All %ss', 'regina' ), $name),
        'search_items'       => sprintf(__( 'Search %ss', 'regina' ), $name),
        'parent_item_colon'  => sprintf(__( 'Parent %ss:', 'regina' ), $name),
        'not_found'          => sprintf(__( 'No %ss found.', 'regina' ), strtolower($name)),
        'not_found_in_trash' => sprintf(__( 'No %ss found in Trash.', 'regina' ), strtolower($name))
    ),
    'description'          => '',
    'public'               => false,
    'hierarchical'         => false,
    'exclude_from_search'  => true,
    'publicly_queryable'   => false,
    'show_ui'              => true,
    'show_in_menu'         => true,
    'show_in_nav_menus'    => true,
    'show_in_admin_bar'    => true,
    'menu_position'        => null,
    'menu_icon'            => 'dashicons-editor-justify',
    'capability_type'      => 'post',
    'capabilities'         => array(),
    'map_meta_cap'         => null,
    'supports'             => array('title', 'thumbnail', 'editor', 'page-attributes'),
    'register_meta_box_cb' => null,
    'taxonomies'           => array(),//default is empty array, we have assigned the tag taxonomy for demostration purposes. Any taxonomies added here must be builtin or registered before the init priority 10. If you want to assign Fluent_Taxonomies you need to declare this post type when registering the taxonomy.
    'has_archive'          => true,//default = false but we have set to true so you can see the archive template overwrite
    'rewrite'              => true,
    'query_var'            => false,
    'can_export'           => true,
    'delete_with_user'     => null,
    '_edit_link'           => 'post.php?post=%d',
    //our custom messages array, defaults detailed below. this allows you to change the notices when posts are changed in some way without adding yet more filters
    'messages' => array(
        //these strings are passed through sprintf with the post type name, so use %s if you want that functionality
        0  => '', // Unused. Messages start at index 1.
        1  => __( '', 'regina' ),
        2  => __( 'Custom field updated.', 'regina' ),
        3  => __( 'Custom field deleted.', 'regina' ),
        4  => __( '', 'regina' ),
        5  => __( '%s restored to revision from %s', 'regina' ),
        6  => __( '%s published.', 'regina' ),
        7  => __( '%s saved.', 'regina' ),
        8  => __( '%s submitted.', 'regina' ),
        9  => __( '%s scheduled for: %s.', 'regina' ),
        10 => __( '%s draft updated.', 'regina' )
    ),
    //want to disable people adding new posts? this will remove the add new menu item and the add new button in the and list page, it will also redirect post-new.php?post_type=*** back to the list page.
    'disable_add_new' => false,
));


/**
 * Remove View from CPTs that are not public
 */

add_filter( 'post_row_actions', 'remove_row_actions', 10, 1 );

function remove_row_actions( $actions )
{
    if( get_post_type() === 'section' || get_post_type() === 'testimonial')
        unset( $actions['view'] );
        unset( $actions['inline hide-if-no-js'] );
    return $actions;
}

function hide_view_preview_cpt() {

    if(get_post_type() === 'section' || get_post_type() === 'testimonial') {
        echo '<style>';
            echo '#message p a {display: none;}';
        echo '</style>';
    }
}

add_action('admin_head', 'hide_view_preview_cpt');