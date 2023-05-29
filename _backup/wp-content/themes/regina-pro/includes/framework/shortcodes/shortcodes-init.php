<?php

if ( ! function_exists( 'register_shortcodes' ) ) {

	function register_shortcodes() {

		// include the shortcode definitions
		include dirname( __FILE__ ) . '/shortcodes.php';

		// get theme version
		$theme = wp_get_theme();

		//Regina Shortcodes
		add_shortcode( 'services', 'regina_services_shortcode' );
		add_shortcode( 'members', 'regina_members_shortcode' );
		add_shortcode( 'testimonials', 'regina_testimonials_shortcode' );
		add_shortcode( 'gallery-wrapper', 'regina_gallery_wrapper_shortcode' );

		// this shortcode overwrites the default [gallery] shortcode. In V1.4 we've removed it so that it doesn't overwrite the default WP behaviour for it
		if ( $theme->get( 'Version' ) == '1.3' && $theme->get( 'Name' ) == 'Regina' ) {
			add_shortcode( 'gallery', 'regina_gallery_shortcode' );
		}

		add_shortcode( 'mt-gallery', 'regina_new_gallery_shortcode' );
		add_shortcode( 'appointment-button', 'regina_appointment_button_shortcode' );
		add_shortcode( 'accordion-wrapper', 'regina_accordion_wrapper_shortcode' );
		add_shortcode( 'accordion', 'regina_accordion_shortcode' );
		add_shortcode( 'blog', 'regina_blog_shortcode' );
		add_shortcode( 'container', 'regina_container_shortcode' );
		add_shortcode( 'regina-button', 'regina_button_shortcode' );
		add_shortcode( 'awards-wrapper', 'regina_awards_wrapper_shortcode' );
		add_shortcode( 'award', 'regina_awards_shortcode' );
		add_shortcode( 'section-with-background', 'regina_section_shortcode' );

	}

	add_filter( 'macho_add_shortcodes_to_sm', 'regina_add_custom_shortcodes', 10, 1 );

	function regina_add_custom_shortcodes( $shortcodes ) {
		unset( $shortcodes['member'] );
		unset( $shortcodes['cta'] );
		unset( $shortcodes['slider'] );
		unset( $shortcodes['rounded-image'] );
		unset( $shortcodes['button'] );

		//Services Shortcode
		$shortcodes['services'] = array(
			'title'  => __( 'Services', 'regina' ),
			'params' => array(
				'number'    => array(
					'std'     => '',
					'type'    => 'select',
					'label'   => __( 'Size', 'regina' ),
					'options' => array(
						'4'  => '4',
						'8'  => '8',
						'12' => '12',
						'16' => '16',
					),
				),
				'link_text' => array(
					'std'   => 'Our Services',
					'type'  => 'text',
					'label' => __( 'Link Text', 'regina' ),
				),
				'link_url'  => array(
					'std'   => '#',
					'type'  => 'text',
					'label' => __( 'Link URL', 'regina' ),
				),
			),
		);

		$shortcodes['members'] = array(
			'title'  => __( 'Members', 'regina' ),
			'params' => array(
				'number'    => array(
					'std'     => '',
					'type'    => 'select',
					'label'   => __( 'Size', 'regina' ),
					'options' => array(
						'4'  => '4',
						'8'  => '8',
						'12' => '12',
						'16' => '16',
					),
				),
				'link_text' => array(
					'std'   => __( 'Meet the full team', 'regina' ),
					'type'  => 'text',
					'label' => __( 'Link Text', 'regina' ),
				),
				'link_url'  => array(
					'std'   => '#',
					'type'  => 'text',
					'label' => __( 'Link URL', 'regina' ),
				),
			),
		);

		$shortcodes['testimonials'] = array(
			'title'  => __( 'Testimonials', 'regina' ),
			'params' => array(
				'number' => array(
					'std'     => '',
					'type'    => 'select',
					'label'   => __( 'Size', 'regina' ),
					'options' => array(
						'4'  => '4',
						'8'  => '8',
						'12' => '12',
						'16' => '16',
					),
				),
			),
		);

		$shortcodes['gallery-wrapper'] = array(
			'title'           => __( 'Gallery', 'regina' ),
			'params'          => array(),
			'with_parent_tag' => true,
			'child_shortcode' => array(
				'tag'          => 'mt-gallery',
				'clone_button' => __( 'Add New Image', 'regina' ),
				'params'       => array(
					'image' => array(
						'type'  => 'uploader',
						'label' => __( 'Upload Image', 'regina' ),
					),
				),
			),
		);

		$shortcodes['appointment-button'] = array(
			'title'  => __( 'Appointment Button', 'regina' ),
			'params' => array(
				'button_text'  => array(
					'std'   => 'Book appointment',
					'type'  => 'text',
					'label' => __( 'Button Text', 'regina' ),
				),
				'button_style' => array(
					'std'     => 'solid',
					'type'    => 'select',
					'label'   => __( 'Button Style', 'regina' ),
					'options' => array(
						'solid'   => __( 'Solid', 'regina' ),
						'outline' => __( 'Outline', 'regina' ),
					),
				),
				'button_align' => array(
					'std'     => 'center',
					'type'    => 'select',
					'label'   => __( 'Button Align', 'regina' ),
					'options' => array(
						'left'   => __( 'Left', 'regina' ),
						'center' => __( 'Center', 'regina' ),
						'right'  => __( 'Right', 'regina' ),
					),
				),
			),
		);

		$shortcodes['accordion-wrapper'] = array(
			'title'           => __( 'Accordion', 'regina' ),
			'params'          => array(),
			'with_parent_tag' => true,
			'child_shortcode' => array(
				'tag'          => 'accordion',
				'clone_button' => __( 'Add Accordion Item', 'regina' ),
				'params'       => array(
					'accordion_title' => array(
						'type'  => 'text',
						'label' => __( 'Accordion Title', 'regina' ),
					),
				),
				'content'      => array(
					'std'   => '',
					'label' => __( 'Accordion Text', 'regina' ),
				),

			),
		);

		$shortcodes['blog'] = array(
			'title'  => __( 'Blog Posts', 'regina' ),
			'params' => array(
				'number' => array(
					'std'     => '4',
					'type'    => 'select',
					'label'   => __( 'Number of blog posts', 'regina' ),
					'options' => array(
						'4'  => '4',
						'8'  => '8',
						'12' => '12',
						'16' => '16',
					),
				),
			),
		);

		$shortcodes['container'] = array(
			'title'   => __( 'Container', 'regina' ),
			'params'  => array(),
			'content' => array(
				'std'   => '',
				'label' => __( 'Container Content', 'regina' ),
			),
		);

		$shortcodes['regina-button'] = array(
			'title'  => __( 'Button', 'regina' ),
			'params' => array(
				'button_text'  => array(
					'std'   => 'Book appointment',
					'type'  => 'text',
					'label' => __( 'Button Text', 'regina' ),
				),
				'button_url'   => array(
					'std'   => '#',
					'type'  => 'text',
					'label' => __( 'Button URL', 'regina' ),
				),
				'button_style' => array(
					'std'     => 'solid',
					'type'    => 'select',
					'label'   => __( 'Button Style', 'regina' ),
					'options' => array(
						'solid'   => __( 'Solid', 'regina' ),
						'outline' => __( 'Outline', 'regina' ),
					),
				),
				'button_align' => array(
					'std'     => 'center',
					'type'    => 'select',
					'label'   => __( 'Button Align', 'regina' ),
					'options' => array(
						'left'   => __( 'Left', 'regina' ),
						'center' => __( 'Center', 'regina' ),
						'right'  => __( 'Right', 'regina' ),
					),
				),
			),
		);

		$shortcodes['awards-wrapper'] = array(
			'title'           => __( 'Awards', 'regina' ),
			'params'          => array(),
			'with_parent_tag' => true,
			'child_shortcode' => array(
				'tag'          => 'award',
				'clone_button' => __( 'Add New Award', 'regina' ),
				'params'       => array(
					'award_image' => array(
						'type'  => 'uploader',
						'label' => __( 'Award Logo', 'regina' ),
					),
					'award_text'  => array(
						'std'   => '',
						'type'  => 'text',
						'label' => __( 'Award Name', 'regina' ),
					),
				),
			),
		);

		$shortcodes['section-with-background'] = array(
			'title'  => __( 'Section', 'regina' ),
			'params' => array(
				'background' => array(
					'type'  => 'colorpicker',
					'label' => __( 'Background Color', 'regina' ),
				),
			),
		);

		return $shortcodes;
	}

	add_action( 'init', 'register_shortcodes' );
}


