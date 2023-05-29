<?php

//Create meta fields for pages and taxonomies alike
function antreas_metadata_layout_options() {

	$data = array();

	$data['layout_sidebar'] = array(
		'name'   => 'layout_sidebar',
		'label'  => __( 'Sidebar Position', 'antreas' ),
		'desc'   => __( 'Determines the location of the sidebar by default.', 'antreas' ),
		'type'   => 'imagelist',
		'option' => antreas_metadata_sidebarposition_optional(),
		'std'    => 'default',
	);

	if ( defined( 'REVSLIDER_TEXTDOMAIN' ) || function_exists( 'putRevSlider' ) ) {
		$data['page_slider'] = array(
			'name'   => 'page_slider',
			'std'    => '',
			'label'  => __( 'Page Slider', 'antreas' ),
			'desc'   => sprintf( __( 'Sets a slider for this page. Requires the %s plugin.', 'antreas' ), '<a target="_blank" href="https://codecanyon.net/item/slider-revolution-responsive-wordpress-plugin">Revolution Slider</a>' ),
			'type'   => 'select',
			'option' => antreas_metadata_revsliders(),
			'std'    => '0',
		);
	}

	$data['page_header'] = array(
		'name'   => 'page_header',
		'std'    => '',
		'label'  => __( 'Page Header', 'antreas' ),
		'desc'   => __( 'Specifies the format of the header for this page.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_page_header(),
		'std'    => 'normal',
	);

	$data['page_title'] = array(
		'name'   => 'page_title',
		'label'  => __( 'Page Title', 'antreas' ),
		'desc'   => __( 'Specifies the format of the title heading for this page.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_page_title(),
		'std'    => 'normal',
	);

	$data['page_title_area'] = array(
		'name'   => 'page_title_area',
		'label'  => __( 'Page Title Area', 'antreas' ),
		'desc'   => __( 'Specifies the format of the title area for this page.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_page_title_area(),
		'std'    => 'normal',
	);

	$data['title_area_overlay_color'] = array(
		'name'    => 'title_area_overlay_color',
		'label'   => __( 'Title Overlay Color', 'antreas' ),
		'desc'    => __( 'specify the overlay color for this page title', 'antreas' ),
		'type'    => 'color',
		'std'     => '#000000',
	);

	$data['title_area_overlay_opacity'] = array(
		'name'    => 'title_area_overlay_opacity',
		'label'   => __( 'Title Overlay Transparency', 'antreas' ),
		'desc'    => __( 'specify the overlay transparency for this page title', 'antreas' ),
		'type'    => 'range',
		'attrs'   => array( 'min' => 0, 'max' => 1, 'step' => 0.1 ),
		'std'     => 0.4,
	);

	$data['page_footer'] = array(
		'name'   => 'page_footer',
		'std'    => '',
		'label'  => __( 'Page Footer', 'antreas' ),
		'desc'   => __( 'Specifies the format of the footer for this page.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_page_footer(),
		'std'    => 'normal',
	);

	$data['page_full'] = array(
		'name'  => 'page_full',
		'std'   => '',
		'label' => __( 'Full Width Page', 'antreas' ),
		'desc'  => __( 'Allows the page content to fill the entire width of the screen. Useful for creating full width rows with backgrounds.', 'antreas' ),
		'type'  => 'yesno',
	);

	return apply_filters( 'antreas_metadata_layout', $data );
}


//Create slide meta fields
function antreas_metadata_slide_options() {

	$data = array();

	$data['slide_image'] = array(
		'name'  => 'slide_image',
		'std'   => '',
		'label' => __( 'Slide Image', 'antreas' ),
		'desc'  => __( 'Add a complementary image to the slide.', 'antreas' ),
		'type'  => 'upload',
	);

	$data['slide_position'] = array(
		'name'   => 'slide_position',
		'std'    => '',
		'label'  => __( 'Caption Position', 'antreas' ),
		'desc'   => __( 'Determines where the caption of the slide is positioned.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_slide_position(),
	);

	$data['slide_color'] = array(
		'name'   => 'slide_color',
		'std'    => '',
		'label'  => __( 'Color Scheme', 'antreas' ),
		'desc'   => __( 'Determines the color scheme used in the caption.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_color_scheme(),
	);

	$data['slide_title'] = array(
		'name'    => 'slide_title',
		'std'     => '1',
		'label'   => __( 'Hide Slide title', 'antreas' ),
		'desc'    => __( 'Removes the title of this slide.', 'antreas' ),
		'type'    => 'yesno',
		'default' => true,
	);

	$data['slide_title_font_size'] = array(
		'name'    => 'slide_title_font_size',
		'label'   => __( 'Slide title font size', 'antreas' ),
		'desc'    => __( 'specify the slide title font size in pixels', 'antreas' ),
		'type'    => 'range',
		'attrs'   => array( 'min' => 5, 'max' => 100, 'step' => 1 ),
		'std'     => 40,
	);

	$data['slide_content_font_size'] = array(
		'name'    => 'slide_content_font_size',
		'label'   => __( 'Slide content font size', 'antreas' ),
		'desc'    => __( 'specify the slide content font size in pixels', 'antreas' ),
		'type'    => 'range',
		'attrs'   => array( 'min' => 5, 'max' => 100, 'step' => 1 ),
		'std'     => 20,
	);

	$data['slide_button_text_1'] = array(
		'name'  => 'slide_button_text_1',
		'std'   => '',
		'label' => __( 'First Button Text', 'antreas' ),
		'desc'  => __( 'Sets the text of the first button of this slide.', 'antreas' ),
		'type'  => 'text',
	);

 	$data['slide_button_url_1'] = array(
		'name'  => 'slide_button_url_1',
		'std'   => '',
		'label' => __( 'First Button URL', 'antreas' ),
		'desc'  => __( 'Specify a URL for the first button of this slide. Requires a valid destination URL.', 'antreas' ),
		'type'  => 'text',
	);

	$data['slide_button_text_2'] = array(
		'name'  => 'slide_button_text_2',
		'std'   => '',
		'label' => __( 'Second Button Text', 'antreas' ),
		'desc'  => __( 'Sets the text of the second button of this slide.', 'antreas' ),
		'type'  => 'text',
	);

 	$data['slide_button_url_2'] = array(
		'name'  => 'slide_button_url_2',
		'std'   => '',
		'label' => __( 'Second Button URL', 'antreas' ),
		'desc'  => __( 'Specify a URL for the second button of this slide. Requires a valid destination URL.', 'antreas' ),
		'type'  => 'text',
	);

	$data['slide_overlay_color'] = array(
		'name'    => 'slide_overlay_color',
		'label'   => __( 'Overlay Color', 'antreas' ),
		'desc'    => __( 'specify the overlay color for this slide', 'antreas' ),
		'type'    => 'color',
		'std'     => '#000000',
	);

	$data['slide_overlay_opacity'] = array(
		'name'    => 'slide_overlay_opacity',
		'label'   => __( 'Overlay Transparency', 'antreas' ),
		'desc'    => __( 'specify the overlay transparency for this slide', 'antreas' ),
		'type'    => 'range',
		'attrs'   => array( 'min' => 0, 'max' => 1, 'step' => 0.1 ),
		'std'     => 0,
	);

	return apply_filters( 'antreas_metadata_slide', $data );
}


//Create feature meta fields
function antreas_metadata_feature_options() {

	$data = array();

	$data['feature_icon'] = array(
		'name'  => 'feature_icon',
		'std'   => '',
		'label' => __( 'Feature Icon', 'antreas' ),
		'desc'  => __( 'Sets an icon to be used as the featured element.', 'antreas' ),
		'type'  => 'iconlist',
	);

	$data['feature_url'] = array(
		'name'  => 'feature_url',
		'std'   => '',
		'label' => __( 'Target URL', 'antreas' ),
		'desc'  => __( 'Sets a destination URL for this feature.', 'antreas' ),
		'type'  => 'text',
	);

	return apply_filters( 'antreas_metadata_feature', $data );
}


//Create portfolio meta fields
function antreas_metadata_portfolio_options() {

	$data = array();

	$data['portfolio_featured'] = array(
		'name'  => 'portfolio_featured',
		'std'   => '',
		'label' => __( 'Featured Item', 'antreas' ),
		'desc'  => __( 'Specifies whether this item appears in the homepage.', 'antreas' ),
		'type'  => 'yesno',
	);

	$data['portfolio_layout'] = array(
		'name'   => 'portfolio_layout',
		'std'    => '',
		'label'  => __( 'Media Layout', 'antreas' ),
		'desc'   => __( 'Specifies how the images attached to this item should be displayed. The featured image will be excluded from the list of elements.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_media(),
	);

	$data['portfolio_custom_url'] = array(
		'name'  => 'portfolio_custom_url',
		'std'   => '',
		'label' => __( 'Custom URL', 'antreas' ),
		'desc'  => __( 'Sets a custom URL for this portfolio project.', 'antreas' ),
		'type'  => 'text',
	);

	return apply_filters( 'antreas_metadata_portfolio', $data );
}


//Create product meta fields
function antreas_metadata_product_options() {

	$data = array();

	$data['product_featured'] = array(
		'name'  => 'product_featured',
		'std'   => '',
		'label' => __( 'Featured Item', 'antreas' ),
		'desc'  => __( 'Specifies whether this item appears in the homepage.', 'antreas' ),
		'type'  => 'yesno',
	);

	$data['product_layout'] = array(
		'name'   => 'product_layout',
		'std'    => '',
		'label'  => __( 'Media Layout', 'antreas' ),
		'desc'   => __( 'Specifies how the images attached to this item should be displayed. The featured image will be excluded from the list of elements.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_media(),
	);

	return apply_filters( 'antreas_metadata_product', $data );
}


//Create service meta fields
function antreas_metadata_service_options() {

	$data = array();

	$data['service_featured'] = array(
		'name'  => 'service_featured',
		'std'   => '',
		'label' => __( 'Featured Item', 'antreas' ),
		'desc'  => __( 'Specifies whether this item appears in the homepage.', 'antreas' ),
		'type'  => 'yesno',
	);

	$data['service_icon_type'] = array(
		'name'   => 'service_icon_type',
		'std'    => '',
		'label'  => __( 'Service Icon Type', 'antreas' ),
		'desc'   => __( 'select to use icon or image as the service preview.', 'antreas' ),
		'type'   => 'select',
		'option' => array(
			'fontawesome'     => __( 'Font Awesome', 'antreas' ),
			'image'   => __( 'Image', 'antreas' ),
		),
	);

	$data['service_icon'] = array(
		'name'  => 'service_icon',
		'std'   => '',
		'label' => __( 'Service Icon', 'antreas' ),
		'desc'  => __( 'Sets an icon to be used as the service preview.', 'antreas' ),
		'type'  => 'iconlist',
	);

	$data['service_image'] = array(
		'name'  => 'service_image',
		'std'   => '',
		'label' => __( 'Service Image', 'antreas' ),
		'desc'  => __( 'Sets an image to be used as the service preview.', 'antreas' ),
		'type'  => 'upload',
	);

	$data['service_layout'] = array(
		'name'   => 'service_layout',
		'std'    => '',
		'label'  => __( 'Media Layout', 'antreas' ),
		'desc'   => __( 'Specifies how the images attached to this item should be displayed. The featured image will be excluded from the list of elements.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_media(),
	);

	$data['service_url'] = array(
		'name'  => 'service_url',
		'std'   => '',
		'label' => __( 'Target URL', 'antreas' ),
		'desc'  => __( 'Sets a destination URL for this service.', 'antreas' ),
		'type'  => 'text',
	);

	return apply_filters( 'antreas_metadata_service', $data );
}


//Create client meta fields
function antreas_metadata_client_options() {

	$data = array();

	$data['client_url'] = array(
		'name'  => 'client_url',
		'std'   => '',
		'label' => __( 'Destination URL', 'antreas' ),
		'desc'  => __( 'Links the client to a specific URL.', 'antreas' ),
		'type'  => 'text',
	);

	return apply_filters( 'antreas_metadata_client', $data );
}


//Create team meta fields
function antreas_metadata_team_options() {

	$data = array();

	$data['team_featured'] = array(
		'name'  => 'team_featured',
		'std'   => '',
		'label' => __( 'Featured Member', 'antreas' ),
		'desc'  => __( 'Specifies whether this member appears in the homepage.', 'antreas' ),
		'type'  => 'yesno',
	);

	$data['team_description'] = array(
		'name'  => 'team_description',
		'std'   => '',
		'label' => __( 'Member Description', 'antreas' ),
		'desc'  => __( 'Specifies a small description for this team member.', 'antreas' ),
		'type'  => 'text',
	);

	$data['team_links'] = array(
		'name'   => 'team_links',
		'std'    => '',
		'label'  => __( 'Social Profiles', 'antreas' ),
		'desc'   => __( 'Enter the URL of the social profiles for this team member.', 'antreas' ),
		'type'   => 'collection',
		'option' => antreas_metadata_social_profiles(),
	);

	return apply_filters( 'antreas_metadata_team', $data );
}


//Create testimonial meta fields
function antreas_metadata_testimonial_options() {

	$data = array();

	$data['testimonial_description'] = array(
		'name'  => 'testimonial_description',
		'std'   => '',
		'label' => __( 'Testimonial Description', 'antreas' ),
		'desc'  => __( 'Specifies a small description for this testimonial.', 'antreas' ),
		'type'  => 'text',
	);

	return apply_filters( 'antreas_metadata_testimonial', $data );
}


//Create page meta fields
function antreas_metadata_page_options() {

	$data = array();

	$data['page_featured'] = array(
		'name'   => 'page_featured',
		'std'    => '',
		'label'  => __( 'Show In Homepage', 'antreas' ),
		'desc'   => __( 'Specifies whether this item is featured in the homepage.', 'antreas' ),
		'type'   => 'select',
		'option' => antreas_metadata_featured_page(),
	);

	return apply_filters( 'antreas_metadata_page', $data );
}
