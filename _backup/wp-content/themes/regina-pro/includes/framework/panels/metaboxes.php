<?php
/**
 * Section Custom Post Type Metaboxes
 */

$args     = array();
$sections = array();

// Aside
$sections['section'] = array(
	'dash_icon'   => 'welcome-write-blog',
	'title'       => __( 'Section settings', 'regina' ),
	'description' => __( 'From here you will be able to control the front-page sections. Please make sure you\'ve read the documentation before proceeding', 'regina' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(

		'section-bg-image' => array(
			'type'    => 'background',
			'default' => array(
				'transparent' => 20,
			),
			'step'    => 1,
			'min'     => 20,
			'max'     => 100,
			'title'   => __( 'Select the section: background image, color & opacity.', 'regina' ),
			'default' => array(
				'color'       => '#FFF',
				'transparent' => '20',
			),
		),

		// 'section-parallax-enabled' => array(
		//     'type' => 'switch',
		//     'title' => __('Enable Parallax Effect ? ', 'regina'),
		//     'labels' => array(
		//         'on' => __('Enabled', 'regina'),
		//         'off' => __('Disabled', 'regina')
		//     ),
		//     'default' => '0',
		//     'description' => __('Default Value: Disabled. By enabling this, you will get a parallax effect on your background-image.', 'regina')
		// ),


		'section-heading-title' => array(
			'type'    => 'text',
			'title'   => __( 'Enter the section title', 'regina' ),
			'default' => __( 'Section Title', 'regina' ),
		),

		'section-sub-heading-title' => array(
			'type'    => 'text',
			'title'   => __( 'Enter the sub-section title', 'regina' ),
			'default' => __( 'Section Description', 'regina' ),
		),

		// 'section-bg-separator-type' => array(
		//      'type' => 'radio',
		//      'inline' => false,
		//      'title' => __('Select the separator type', 'regina'),
		//      'options' => array(
		//          'light-section-heading' => __('Dark Separator (works best on light backgrounds)', 'regina'),
		//          'colored-section-heading' => __('Light Separator (works best on dark backgrounds', 'regina')
		//      ),
		//      'default' => 'light-section-heading'
		//  ),
		// 'section-border-bottom-color' => array(
		//      'type' => 'color',
		//      'title' => __('Select the bottom border color. Default is: #FFF(white)', 'regina'),
		//      'default' => 'transparent'
		//  ),
		'section-style'             => array(
			'type'    => 'radio',
			'inline'  => false,
			'title'   => __( 'Select section style', 'regina' ),
			'options' => array(
				'with-padding'    => __( 'With Padding', 'regina' ),
				'without-padding' => __( 'Without Padding', 'regina' ),
			),
			'default' => 'without-padding',
		),
		'section-full-width'        => array(
			'type'     => 'switch',
			'title'    => __( 'Enable Full Width', 'regina' ),
			'default'  => '0',
			'seperate' => true,
		),
		'section-disable'           => array(
			'type'     => 'switch',
			'title'    => __( 'Display this section only if user is logged-in', 'regina' ),
			'default'  => '0',
			'seperate' => true,
		),

	),
);

// $sections['shortcodes'] = array(
//     'dash_icon' => 'welcome-write-blog',
//     'title' =>  __('After section shortcodes', 'regina'),
//     'description' => __('From here you will be able to enter shortcodes that will be displayed in full width after the section.','macho'),
//     'context' => 'normal',
//     'priority' => 'high',
//     'fields' => array(
//         'section-shortcode-container' => array(
//             'type' => 'textarea',
//             'title' => __('Full width shortcodes here.','macho'),
//             'sub_title' => __('Shortcodes entered here will be displayed in full width.', 'regina')
//         )
//     )
// );

//load normal options page
$args = array(
	'option_name' => strtolower( MT_THEME_NAME ) . '_options',
	'post_types'  => array( 'section' ),
);
new Macho_Options_Meta( $args, $sections );


/**
 * Service Custom Post Type Metaboxes
 */

$args     = array();
$sections = array();

$sections['general-service-settings'] = array(
	'dash_icon' => 'welcome-write-blog',
	'title'     => __( 'Service Settings', 'regina' ),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		'service-icon-image'     => array(
			'type'  => 'media',
			'title' => __( 'Service Icon Custom Image', 'regina' ),
		),
		'service-icon'           => array(
			'type'      => 'icon-picker',
			'title'     => 'Service Icon',
			'sub_title' => __( 'Dashicons & Font Awesome available.', 'regina' ),
		),
		'service-read-more-text' => array(
			'type'      => 'text',
			'title'     => 'Read more text',
			'sub_title' => __( 'Place read more text here', 'regina' ),
			'default'   => 'Read more',
		),
		'service-read-more-link' => array(
			'type'      => 'text',
			'title'     => 'Read more Link',
			'sub_title' => __( 'Place read more link here', 'regina' ),
			'default'   => '#',
		),
	),
);

//load normal options page
$args = array(
	'option_name' => strtolower( MT_THEME_NAME ) . '_options',
	'post_types'  => array( 'service' ),
);
new Macho_Options_Meta( $args, $sections );

/**
 * Member Custom Post Type Metaboxes
 */

$args     = array();
$sections = array();

$sections['general-member-settings'] = array(
	'dash_icon' => 'welcome-write-blog',
	'title'     => __( 'Member Settings', 'regina' ),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		'member-image'            => array(
			'type'  => 'media',
			'title' => __( 'Member Image', 'regina' ),
		),
		'member-position'         => array(
			'type'      => 'text',
			'title'     => 'Doctor Position',
			'sub_title' => __( 'Place doctor position here', 'regina' ),
			'default'   => 'Senior Cardiologist',
		),
		'member-hospitals'        => array(
			'type'      => 'text',
			'title'     => 'Hospitals',
			'sub_title' => __( 'Hospitals where this doctor has worked', 'regina' ),
			'default'   => 'Cardiac Clinic, Primary Healthcare',
		),
		'member-hour-mf'          => array(
			'type'    => 'text',
			'title'   => 'Doctor working hours(Monday - Friday)',
			'default' => '8.00 - 17.00',
		),
		'member-hour-sat'         => array(
			'type'    => 'text',
			'title'   => 'Doctor working hours(Saturday)',
			'default' => '9.30 - 17.30',
		),
		'member-hour-sun'         => array(
			'type'    => 'text',
			'title'   => 'Doctor working hours(Sunday)',
			'default' => '9.30 - 15.00',
		),
		'member-social'           => array(
			'title'        => 'Social URL\'s',
			'type'         => 'group',
			'multiple'     => true,
			'layout'       => 'horizontal',
			'add_row_text' => __( 'Add new social platform', 'regina' ),
			'fields'       => array(
				'members-social-icon' => array(
					'type'      => 'icon-picker',
					'title'     => 'Social Icon',
					'sub_title' => __( 'Select social icon', 'regina' ),
				),
				'members-social-url'  => array(
					'type'      => 'text',
					'title'     => 'Social URL',
					'sub_title' => __( 'Place social url here', 'regina' ),
				),
			),
		),
		'book-appointment-toggle' => array(
			'type'    => 'switch',
			'labels'  => array(
				'on'  => __( 'Enabled', 'regina' ),
				'off' => __( 'Disabled', 'regina' ),
			),
			'default' => '0',
			'title'   => __( 'Enable Customisation of Book Appointment Button?', 'regina' ),
		),
		'book-appointment-label'  => array(
			'type'        => 'text',
			'description' => __( 'The text you\'d like to appear on the button', 'regina' ),
			'title'       => __( 'Book Appointment Button Label', 'regina' ),
			'conditions'  => array(
				array(
					array(
						'id'    => 'book-appointment-toggle',
						'value' => '1',
					),
				),
			),
		),
		'book-appointment-url'    => array(
			'type'        => 'text',
			'description' => __( 'Where to have this button link', 'regina' ),
			'title'       => __( 'Book Appointment Button URL', 'regina' ),
			'conditions'  => array(
				array(
					array(
						'id'    => 'book-appointment-toggle',
						'value' => '1',
					),
				),
			),
		),
	),
);

$sections['page-member-settings'] = array(
	'dash_icon' => 'welcome-write-blog',
	'title'     => __( 'Page Member Settings', 'regina' ),
	'context'   => 'normal',
	'priority'  => 'high',
	'fields'    => array(
		'member-page-header'       => array(
			'type'    => 'switch',
			'labels'  => array(
				'on'  => __( 'Enabled', 'regina' ),
				'off' => __( 'Disabled', 'regina' ),
			),
			'default' => '1',
			'title'   => __( 'Enable Member Page header ?', 'regina' ),
		),
		'member-page-header-image' => array(
			'type'  => 'media',
			'title' => __( 'Member Page Header Image', 'regina' ),
		),
		'member-page-title'        => array(
			'type'  => 'text',
			'title' => 'Member Page Title',
		),
		'member-page-subtitle'     => array(
			'type'  => 'text',
			'title' => 'Member Page Subtitle',
		),
		'member-page-breadcrumbs'  => array(
			'type'    => 'switch',
			'labels'  => array(
				'on'  => __( 'Enabled', 'regina' ),
				'off' => __( 'Disabled', 'regina' ),
			),
			'default' => '1',
			'title'   => __( 'Enable Breadcrumbs ?', 'regina' ),
		),
	),
);

//load normal options page
$args = array(
	'option_name' => strtolower( MT_THEME_NAME ) . '_options',
	'post_types'  => array( 'member' ),
);
new Macho_Options_Meta( $args, $sections );

/**
 * Testimonial Custom Post Type Metaboxes
 */

$args     = array();
$sections = array();

$sections['general-testimonial-settings'] = array(
	'dash_icon'   => 'welcome-write-blog',
	'title'       => __( 'Testimonial Settings', 'regina' ),
	'description' => __( 'From here you will be able to control the testimonials. Please make sure you\'ve read the documentation before proceeding', 'regina' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		'testimonial-person-name'     => array(
			'type'  => 'text',
			'title' => 'Testimonial Person Name',
		),
		'testimonial-person-position' => array(
			'type'    => 'text',
			'title'   => 'Testimonial Person Position',
			'default' => 'Manager',
		),
		'testimonial-person-image'    => array(
			'type'  => 'media',
			'title' => __( 'Testimonial Person Image', 'regina' ),
		),
	),
);

//load normal options page
$args = array(
	'option_name' => strtolower( MT_THEME_NAME ) . '_options',
	'post_types'  => array( 'testimonial' ),
);
new Macho_Options_Meta( $args, $sections );

/**
 * Posts Metaboxes
 */

$args     = array();
$sections = array();

// Aside
$sections['general-posts'] = array(
	'title'       => __( 'Header Post Settings', 'regina' ),
	'description' => __( 'From here you will be able to control the post settings.', 'regina' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'tabs'        => array(
		'Header' => array(
			'title'       => __( 'Header settings', 'regina' ),
			'icon'        => 'dashicons dashicons-desktop',
			'description' => __( 'Header - single post settings', 'regina' ),
			'fields'      => array(
				'post-header'       => array(
					'type'    => 'switch',
					'labels'  => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'default' => get_theme_mod( 'regina_enable_post_header', 1 ),
					'title'   => __( 'Enable Post header ?', 'regina' ),
				),
				'post-header-image' => array(
					'type'  => 'media',
					'title' => __( 'Post Header Image', 'regina' ),
				),
				'post-title'        => array(
					'type'  => 'text',
					'title' => 'Post Title',
				),
				'post-subtitle'     => array(
					'type'  => 'text',
					'title' => 'Post Subtitle',
				),
				'post-breadcrumbs'  => array(
					'type'    => 'switch',
					'labels'  => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'default' => get_theme_mod( 'regina_enable_post_breadcrumbs', 1 ),
					'title'   => __( 'Enable Breadcrumbs ?', 'regina' ),
				),
			),
		),
		'Post'   => array(
			'title'       => __( 'Post settings', 'regina' ),
			'icon'        => 'dashicons dashicons-admin-post',
			'description' => __( 'Single post settings', 'regina' ),
			'fields'      => array(
				'single-post-show-post-featured-image' => array(
					'type'    => 'switch',
					'labels'  => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'title'   => __( 'Show single post featured image ?', 'regina' ),
					'default' => get_theme_mod( 'regina_enable_post_featured_image', 1 ),
				),
				'single-post-show-post-meta'           => array(
					'type'      => 'switch',
					'labels'    => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'title'     => __( 'Show single meta ?', 'regina' ),
					'sub_title' => __( 'By turning this off, the meta box below the featured image will not be displayed any more', 'regina' ),
					'default'   => get_theme_mod( 'regina_enable_post_posted_on_blog_posts', 1 ),
				),
				'single-post-show-post-prev-next'      => array(
					'type'      => 'switch',
					'labels'    => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'title'     => __( 'Show single post prev / next navigation ?', 'regina' ),
					'sub_title' => __( 'Navigation between posts will be disabled', 'regina' ),
					'default'   => get_theme_mod( 'regina_enable_post_navigation', 1 ),
				),
				'single-post-show-post-social-box'     => array(
					'type'    => 'switch',
					'labels'  => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'title'   => __( 'Show single post social box ?', 'regina' ),
					'default' => get_theme_mod( 'regina_enable_post_social_box', 1 ),
				),
				'single-post-show-post-author-box'     => array(
					'type'    => 'switch',
					'labels'  => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'title'   => __( 'Show single post author box ?', 'regina' ),
					'default' => get_theme_mod( 'regina_enable_author_box_blog_posts', 1 ),
				),

				'single-post-show-post-related-box' => array(
					'type'    => 'switch',
					'labels'  => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'title'   => __( 'Show single post related posts box ?', 'regina' ),
					'default' => get_theme_mod( 'regina_enable_related_blog_posts', 1 ),
				),

			),
		),

		'Sidebar' => array(
			'title'       => __( 'Sidebar settings', 'regina' ),
			'icon'        => 'dashicons dashicons-slides',
			'description' => __( 'Sidebar - single post settings', 'regina' ),
			'fields'      => array(
				'single-post-show-sidebar'     => array(
					'type'      => 'switch',
					'labels'    => array(
						'on'  => __( 'Enabled', 'regina' ),
						'off' => __( 'Disabled', 'regina' ),
					),
					'title'     => __( 'Show sidebar ?', 'regina' ),
					'sub_title' => __( 'By turning off the sidebar, your single post will have a full-width layout', 'regina' ),
				),
				'single-post-sidebar-position' => array(
					'type'    => 'radio',
					'inline'  => false,
					'options' => array(
						'left-sidebar'  => __( 'Left Sidebar', 'regina' ),
						'right-sidebar' => __( 'Right Sidebar', 'regina' ),
					),
					'title'   => __( 'Sidebar position', 'regina' ),
				),
				'single-post-sidebar'          => array(
					'type'    => 'select',
					'inline'  => false,
					'options' => mt_get_sidebars(),
					'title'   => __( 'Select Sidebar', 'regina' ),
					'default' => 'sidebar-blog',
				),
			),
		),
	),
);

// $sections['aside-general-posts'] = array(
//     'title' =>  __('Featured Video', 'regina'),
//     'dash_icon' => 'video-alt2',
//     'context' => 'side',
//     'priority' => 'high',
//     'fields' => array(
//         'single-post-featured-video' => array(
//             'type' => 'textarea',
//             'title' => __('Enter video link', 'regina'),
//             'sub_title' => __('By taking advantage of the oEmbed features found in WordPress, you can simply paste your youtube video link here.', 'regina'),
//         )
//     )
// );

//load normal options page
$args = array(
	'option_name' => strtolower( MT_THEME_NAME ) . '_options',
	'post_types'  => array( 'post' ),
);

new Macho_Options_Meta( $args, $sections );


/**
 * Page Metaboxes
 */


$args     = array();
$sections = array();

// Aside
$sections['general-page'] = array(
	'title'       => __( 'Page Settings', 'regina' ),
	'description' => __( 'From here you will be able to control the page settings.', 'regina' ),
	'context'     => 'normal',
	'priority'    => 'high',
	'fields'      => array(
		'page-header'           => array(
			'type'    => 'switch',
			'labels'  => array(
				'on'  => __( 'Enabled', 'regina' ),
				'off' => __( 'Disabled', 'regina' ),
			),
			'default' => '1',
			'title'   => __( 'Enable Page header ?', 'regina' ),
		),
		'page-header-image'     => array(
			'type'  => 'media',
			'title' => __( 'Page Header Image', 'regina' ),
		),
		'page-title'            => array(
			'type'  => 'text',
			'title' => 'Page Title',
		),
		'page-subtitle'         => array(
			'type'  => 'text',
			'title' => 'Page Subtitle',
		),
		'page-breadcrumbs'      => array(
			'type'    => 'switch',
			'labels'  => array(
				'on'  => __( 'Enabled', 'regina' ),
				'off' => __( 'Disabled', 'regina' ),
			),
			'default' => '1',
			'title'   => __( 'Enable Breadcrumbs ?', 'regina' ),
		),
		'page-sidebar'          => array(
			'type'    => 'switch',
			'labels'  => array(
				'on'  => __( 'Enabled', 'regina' ),
				'off' => __( 'Disabled', 'regina' ),
			),
			'default' => '0',
			'title'   => __( 'Enable Sidebar ?', 'regina' ),
		),
		'page-sidebar-position' => array(
			'type'    => 'radio',
			'inline'  => false,
			'title'   => __( 'Select sidebar position', 'regina' ),
			'options' => array(
				'left-sidebar'  => __( 'Left Sidebar', 'regina' ),
				'right-sidebar' => __( 'Right Sidebar', 'regina' ),
			),
			'default' => 'right-sidebar',
		),
		'page-sidebar-id'       => array(
			'type'    => 'select',
			'inline'  => false,
			'options' => mt_get_sidebars(),
			'title'   => __( 'Select Sidebar', 'regina' ),
			'default' => 'sidebar-page',
		),
	),
);

//load normal options page
$args = array(
	'option_name' => strtolower( MT_THEME_NAME ) . '_options',
	'post_types'  => array( 'page' ),
);

new Macho_Options_Meta( $args, $sections );