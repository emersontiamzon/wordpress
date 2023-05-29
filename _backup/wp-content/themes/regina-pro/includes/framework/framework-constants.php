<?php

   $theme_version_from_stylesheet = wp_get_theme();

   if( !defined('MT_THEME_URL') ) {
        define('MT_THEME_URL', get_template_directory_uri() );
    }

    if( !defined('MT_THEME_VERSION') ) { 
      define('MT_THEME_VERSION', $theme_version_from_stylesheet->get('Version'));
    }


    if( !defined('MT_THEME_NAME') ) {
      define('MT_THEME_NAME', str_replace(' ', '-', $theme_version_from_stylesheet->get('Name')) );
    }

/*
    if ( !defined('MT_FRAMEWORK_VERSION') ){
        define('MT_FRAMEWORK_VERSION', $theme_version_from_stylesheet->get('Version') );
    }
*/