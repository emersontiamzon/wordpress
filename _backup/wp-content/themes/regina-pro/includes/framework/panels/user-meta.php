<?php


$sections = array();

// Aside
$sections['social'] = array(
    'dash_icon' => 'welcome-write-blog',
    'title' =>  __('[MT] User Social Media', 'regina'),
    'context' => 'core',
    'priority' => 'high',
    'caps' => array(),
    'fields' => array(
        'user_avatar' => array(
            'type' => 'media',
            'title' => __('User Image', 'regina'),
        ),
        'user-facebook-url' => array(
            'type' => 'text',
            'classes' => array('regular-text'),
            'title' => __('Enter user FB url', 'regina'),
            'sub_title' => __('Read the documentation to set it up exactly like in the demo.', 'regina'),
        ),
        'user-twitter-url' => array(
            'type' => 'text',
            'classes' => array('regular-text'),
            'title' => __('Enter user Twitter @username below', 'regina'),
            'sub_title' => __('Read the documentation to set it up exactly like in the demo.', 'regina'),
        ),
        'user-linkedin-url' => array(
            'type' => 'text',
            'classes' => array('regular-text'),
            'title' => __('Enter user LinkedIN @username below', 'regina'),
            'sub_title' => __('Read the documentation to set it up exactly like in the demo.', 'regina'),
        ),
    )
);



//load normal options page
$args = array(
    'option_name' => strtolower(MT_THEME_NAME).'_options',
    'page_args' => array(
        'cap' => 'manage_options',//the capability of users who can access this page
        'priority' => null,//the menu item priority
        'callback' => false//the page render callback
    ),
    'restore' => true,//false to disable the restore option
    'show_updated' => true,//false to disable the last updated time
);
$panel = new Macho_Options_User( $args, $sections );
