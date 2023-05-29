<?php

/*-----------------------------------------------------------------------------------*/
/*	Shortcode Selection Config
/*-----------------------------------------------------------------------------------*/

$msm_shortcodes_config['shortcode-generator'] = array(
	'no_preview' => true,
	'params'     => array(),
	'shortcode'  => '',
	'title'      => 'Choose',
);

/* Layout */

$msm_shortcodes_config['row']    = array(
	'title'           => __( 'Column', 'regina' ),
	'params'          => array(),
	'with_parent_tag' => true,
	'child_shortcode' => array(
		'tag'          => 'column',
		'clone_button' => __( 'Add Column', 'regina' ),
		'params'       => array(
			'md'      => array(
				'type'    => 'select',
				'label'   => __( 'Column', 'regina' ),
				'options' => array(
					'6'  => __( 'One Half', 'regina' ),
					'4'  => __( 'One Third', 'regina' ),
					'8'  => __( 'Two Thirds', 'regina' ),
					'3'  => __( 'One Fourth', 'regina' ),
					'9'  => __( 'Three Fourth', 'regina' ),
					'2'  => __( 'One Sixth', 'regina' ),
					'10' => __( 'Five Sixth', 'regina' ),
					'12' => __( 'Full Width ( Six Sixth )', 'regina' ),
				),
				'desc'    => __( 'Please select the width of column', 'regina' ),
			),
			'hide_xs' => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on extra small devices', 'regina' ),
				'desc'  => __( 'Phones (<768px)', 'regina' ),
			),
			'hide_sm' => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on small devices', 'regina' ),
				'desc'  => __( 'Tablets (≥768px)', 'regina' ),
			),
			'hide_md' => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on medium devices', 'regina' ),
				'desc'  => __( 'Desktops (≥992px)', 'regina' ),
			),
			'hide_lg' => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on large devices', 'regina' ),
				'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
			),
		),
		'content'      => array(
			'std'   => '',
			'label' => __( 'Column Content', 'regina' ),
			'desc'  => __( 'Insert the column content', 'regina' ),
		),
	),
);
$msm_shortcodes_config['spacer'] = array(
	'title'  => __( 'Spacer', 'regina' ),
	'params' => array(
		'xclass'  => array(
			'std'   => '',
			'type'  => 'text',
			'label' => __( 'Extra CSS classes, separated by spaces.', 'regina' ),
		),
		'height'  => array(
			'std'   => '30px',
			'type'  => 'text',
			'label' => __( 'Height In Pixels', 'regina' ),
		),
		'hide_xs' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
);
$msm_shortcodes_config['sep']    = array(
	'title'  => __( 'Divider', 'regina' ),
	'params' => array(
		'style'         => array(
			'std'     => '',
			'type'    => 'select',
			'label'   => __( 'Size', 'regina' ),
			'options' => array(
				'solid'  => __( 'Solid', 'regina' ),
				'dashed' => __( 'Dashed', 'regina' ),
				'double' => __( 'Double', 'regina' ),
			),
		),
		'margin_top'    => array(
			'std'   => '20px',
			'type'  => 'text',
			'label' => __( 'Top Margin In Pixels', 'regina' ),
		),
		'margin_bottom' => array(
			'std'   => '20px',
			'type'  => 'text',
			'label' => __( 'Bottom Margin In Pixels', 'regina' ),
		),
		'color'         => array(
			'type'  => 'colorpicker',
			'label' => __( 'Divider Color', 'regina' ),
		),
		'hide_xs'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
);

/* Elements */

$msm_shortcodes_config['button'] = array(
	'title'   => __( 'Button', 'regina' ),
	'params'  => array(
		'link'              => array(
			'std'   => esc_url( 'http://www.machothemes.com/themes/pixova/' ),
			'type'  => 'text',
			'label' => __( 'Button Link', 'regina' ),
		),
		'background-color'  => array(
			'type'  => 'colorpicker',
			'label' => __( 'Button Background Color', 'regina' ),
		),
		'text-color'        => array(
			'type'  => 'colorpicker',
			'label' => __( 'Button Text Color', 'regina' ),
		),
		'size'              => array(
			'type'    => 'select',
			'label'   => __( 'Button Size', 'regina' ),
			'options' => array(
				'xs' => 'Default',
				'sm' => 'Small',
				'md' => 'Medium',
				'lg' => 'Large',
			),
		),
		'target'            => array(
			'type'    => 'select',
			'label'   => __( 'Button Target', 'regina' ),
			'desc'    => __( '_self = open in same window <br />_blank = open in new window.', 'regina' ),
			'options' => array(
				'_self'  => '_self',
				'_blank' => '_blank',
			),
		),
		'border-radius'     => array(
			'std'   => '3px',
			'type'  => 'text',
			'label' => __( 'Button Border Radius', 'regina' ),
		),
		'rel'               => array(
			'type'    => 'select',
			'label'   => __( 'Button Rel attribute', 'regina' ),
			'options' => array(
				''         => __( 'None', 'regina' ),
				'nofollow' => __( 'Nofollow', 'regina' ),
			),
		),
		'button_icon_left'  => array(
			'std'     => '',
			'type'    => 'iconpicker',
			'options' => get_fontawesome_icons(),
			'label'   => __( 'Button Left Icon', 'regina' ),
		),
		'button_icon_right' => array(
			'std'     => '',
			'type'    => 'iconpicker',
			'options' => get_fontawesome_icons(),
			'label'   => __( 'Button: Right Icon ', 'regina' ),
		),
		'hide_xs'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
	'content' => array(
		'std'   => __( 'Download', 'regina' ),
		'label' => __( 'Button Text', 'regina' ),
		'desc'  => __( 'Text shown on button', 'regina' ),
	),
);

$msm_shortcodes_config['heading'] = array(
	'title'   => __( 'Heading', 'regina' ),
	'params'  => array(
		'font_size'     => array(
			'std'   => '14px',
			'type'  => 'text',
			'label' => __( 'Font Size (in pixels)', 'regina' ),
		),
		'color'         => array(
			'type'  => 'colorpicker',
			'label' => __( 'Heading Hex Color', 'regina' ),
		),
		'margin_top'    => array(
			'std'   => '30px',
			'type'  => 'text',
			'label' => __( 'Top Margin', 'regina' ),
		),
		'margin_bottom' => array(
			'std'   => '30px',
			'type'  => 'text',
			'label' => __( 'Bottom Margin', 'regina' ),
		),
		'type'          => array(
			'type'    => 'select',
			'label'   => __( 'Type', 'regina' ),
			'options' => array(
				'h1'   => 'h1',
				'h2'   => 'h2',
				'h3'   => 'h3',
				'h4'   => 'h4',
				'h5'   => 'h5',
				'h6'   => 'h6',
				'span' => 'span',
				'div'  => 'div',
			),
		),
		'style'         => array(
			'type'    => 'select',
			'label'   => __( 'Style', 'regina' ),
			'options' => array(
				''            => 'Solid Bottom Border', // default value
				'double-line' => __( 'Double Line', 'regina' ),
				'dashed-line' => __( 'Dashed Line', 'regina' ),
				'dotted-line' => __( 'Dotted Line', 'regina' ),
			),
		),
		'text_align'    => array(
			'type'    => 'select',
			'label'   => __( 'Text Align', 'regina' ),
			'options' => array(
				'left'   => __( 'Left', 'regina' ),
				'center' => __( 'Center', 'regina' ),
				'right'  => __( 'Right', 'regina' ),
			),
		),
		'icon_left'     => array(
			'std'     => '',
			'type'    => 'iconpicker',
			'options' => get_fontawesome_icons(),
			'label'   => __( 'Left Icon', 'regina' ),
		),
		'icon_right'    => array(
			'std'     => '',
			'type'    => 'iconpicker',
			'options' => get_fontawesome_icons(),
			'label'   => __( 'Right Icon', 'regina' ),
		),
		'hide_xs'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
	'content' => array(
		'std'   => __( 'This is a heading', 'regina' ),
		'label' => __( 'Title', 'regina' ),
	),
);

$msm_shortcodes_config['highlight'] = array(
	'title'   => __( 'Highlights', 'regina' ),
	'params'  => array(
		'color'   => array(
			'std'     => '',
			'type'    => 'select',
			'label'   => __( 'Color', 'regina' ),
			'options' => array(
				'blue'   => __( 'Blue', 'regina' ),
				'green'  => __( 'Green', 'regina' ),
				'yellow' => __( 'Yellow', 'regina' ),
				'red'    => __( 'Red', 'regina' ),
				'gray'   => __( 'Gray', 'regina' ),
			),
		),
		'hide_xs' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
	'content' => array(
		'std'   => __( 'hey check me out', 'regina' ),
		'label' => __( 'Highlighted Text', 'regina' ),
	),

);

$msm_shortcodes_config['rounded-image'] = array(
	'title'  => __( 'Rounded Image', 'regina' ),
	'params' => array(
		'margin_top'    => array(
			'std'   => '30px',
			'type'  => 'text',
			'label' => __( 'Top Margin', 'regina' ),
		),
		'margin_right'  => array(
			'std'   => '30px',
			'type'  => 'text',
			'label' => __( 'Right Margin', 'regina' ),
		),
		'margin_bottom' => array(
			'std'   => '30px',
			'type'  => 'text',
			'label' => __( 'Bottom Margin', 'regina' ),
		),
		'margin_left'   => array(
			'std'   => '30px',
			'type'  => 'text',
			'label' => __( 'Left Margin', 'regina' ),
		),
		'align'         => array(
			'type'    => 'select',
			'label'   => __( 'Text Align', 'regina' ),
			'options' => array(
				'left'   => 'Left',
				'center' => 'Center',
				'right'  => 'Right',
			),
		),
		'image_id'      => array(
			'type'  => 'uploader',
			'label' => __( 'Upload Image', 'regina' ),
		),
		'hide_xs'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'       => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
);

$msm_shortcodes_config['map'] = array(
	'title'  => __( 'Google Map', 'regina' ),
	'params' => array(
		'disablecontrols'   => array(
			'std'   => 'false',
			'type'  => 'checkbox',
			'label' => __( 'Disable map controls ? (zoom in/out)', 'regina' ),
		),
		'enablescrollwheel' => array(
			'std'   => 'false',
			'type'  => 'checkbox',
			'label' => __( 'Enable scroll wheel ?', 'regina' ),
		),

		'address'           => array(
			'std'      => 'New York',
			'type'     => 'text',
			'label'    => __( 'Location', 'regina' ),
			'required' => true,
		),
		'width'             => array(
			'std'   => '100%',
			'type'  => 'text',
			'label' => __( 'Width', 'regina' ),
		),
		'height'            => array(
			'std'   => '300px',
			'type'  => 'text',
			'label' => __( 'Height', 'regina' ),
		),
		'zoom'              => array(
			'std'   => '15',
			'type'  => 'text',
			'label' => __( 'Zoom', 'regina' ),
		),
		'hide_xs'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'           => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
);

$msm_shortcodes_config['skillbar'] = array(
	'title'  => __( 'Skillbar', 'regina' ),
	'params' => array(
		'title'        => array(
			'std'   => 'Web Design',
			'type'  => 'text',
			'label' => __( 'Skill', 'regina' ),
		),
		'percentage'   => array(
			'std'   => '85',
			'type'  => 'text',
			'label' => __( 'Percentage', 'regina' ),
		),
		'color'        => array(
			'type'  => 'colorpicker',
			'label' => __( 'Button Text Color', 'regina' ),
		),
		'show_percent' => array(
			'type'    => 'select',
			'label'   => __( 'Show Percent', 'regina' ),
			'options' => array(
				'true'  => 'True',
				'false' => 'False',
			),
		),
		'hide_xs'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
);

$msm_shortcodes_config['cta'] = array(
	'title'   => __( 'Callout', 'regina' ),
	'params'  => array(
		'button_text'             => array(
			'std'   => __( 'Download', 'regina' ),
			'type'  => 'text',
			'label' => __( 'Button Text', 'regina' ),
		),
		'button_url'              => array(
			'std'   => esc_url( 'http://www.machothemes.com/themes/pixova/' ),
			'type'  => 'text',
			'label' => __( 'Button Link', 'regina' ),
		),
		'button_target'           => array(
			'type'    => 'select',
			'label'   => __( 'Button Target', 'regina' ),
			'desc'    => __( 'Do you want to open the button in a new window or same window ?', 'regina' ),
			'details' => __( '_self means it will open in the same window while _blank means a new window', 'regina' ),
			'options' => array(
				'_self'  => '_self',
				'_blank' => '_blank',
			),
		),
		'button_rel'              => array(
			'type'    => 'select',
			'label'   => __( 'Button Rel attribute', 'regina' ),
			'options' => array(
				''         => __( 'None', 'regina' ),
				'nofollow' => __( 'Nofollow', 'regina' ),
			),
		),
		'button_icon_left'        => array(
			'std'     => '',
			'type'    => 'iconpicker',
			'options' => get_fontawesome_icons(),
			'label'   => __( 'Button Left Icon (FontAwesome)', 'regina' ),
		),
		'button_icon_right'       => array(
			'std'     => '',
			'type'    => 'iconpicker',
			'options' => get_fontawesome_icons(),
			'label'   => __( 'Button: Right Icon (FontAwesome)', 'regina' ),
		),
		'background-color'        => array(
			'type'  => 'colorpicker',
			'std'   => '#2ecc71',
			'label' => __( 'Callout Background Color', 'regina' ),
		),
		'button-background-color' => array(
			'type'  => 'colorpicker',
			'std'   => '#ffffff',
			'label' => __( 'Callout Button Background Color', 'regina' ),
		),
		'button-text-color'       => array(
			'type'  => 'colorpicker',
			'std'   => '#000000',
			'label' => __( 'Callout Button Text Color', 'regina' ),
		),
		'hide_xs'                 => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'                 => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'                 => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'                 => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
	'content' => array(
		'std'   => __( 'This is our main CTA text.', 'regina' ),
		'label' => __( 'Content', 'regina' ),
		'desc'  => __( 'Insert CTA text', 'regina' ),
	),
);

$msm_shortcodes_config['pricing-tabel-container'] = array(
	'title'           => __( 'Pricing Table', 'regina' ),
	'params'          => array(),
	'with_parent_tag' => true,
	'child_shortcode' => array(
		'tag'          => 'pricing-table-item',
		'clone_button' => __( 'Add Pricing Table', 'regina' ),
		'params'       => array(
			'size'              => array(
				'type'    => 'select',
				'label'   => __( 'Size', 'regina' ),
				'options' => array(
					'6'  => '1/2',
					'4'  => '1/3',
					'8'  => '2/3',
					'3'  => '1/4',
					'9'  => '3/4',
					'2'  => '1/6',
					'10' => '5/6',
					'12' => 'Full width',
				),
			),
			'image_id'          => array(
				'type'  => 'uploader',
				'label' => __( 'Upload Image', 'regina' ),
				'desc'  => 'Uploade image for Pricing Table Content',
			),
			'position'          => array(
				'type'    => 'select',
				'label'   => __( 'Position', 'regina' ),
				'options' => array(
					'first'  => __( 'First', 'regina' ),
					'middle' => __( 'Middle', 'regina' ),
					'last'   => __( 'Last', 'regina' ),
				),
			),
			'featured'          => array(
				'type'    => 'select',
				'label'   => __( 'Featured?', 'regina' ),
				'options' => array(
					'no'  => __( 'No', 'regina' ),
					'yes' => __( 'Yes', 'regina' ),
				),
			),
			'plan'              => array(
				'std'   => 'Basic',
				'type'  => 'text',
				'label' => __( 'Plan', 'regina' ),
			),
			'cost'              => array(
				'std'   => '$20',
				'type'  => 'text',
				'label' => __( 'Cost', 'regina' ),
			),
			'per'               => array(
				'std'   => 'per month',
				'type'  => 'text',
				'label' => __( 'Per (optional)', 'regina' ),
			),
			'button_text'       => array(
				'std'   => 'Download',
				'type'  => 'text',
				'label' => __( 'Button Text', 'regina' ),
			),
			'button_url'        => array(
				'std'   => esc_url( 'http://www.machothemes.com/themes/pixova/' ),
				'type'  => 'text',
				'label' => __( 'Button Link', 'regina' ),
			),
			'button_target'     => array(
				'type'    => 'select',
				'label'   => __( 'Button Target', 'regina' ),
				'details' => __( '_self = open in same window while _blank = open in new window.', 'regina' ),
				'options' => array(
					'_self'  => '_self',
					'_blank' => '_blank',
				),
			),
			'button_rel'        => array(
				'type'    => 'select',
				'label'   => __( 'Button Rel attribute', 'regina' ),
				'options' => array(
					''         => __( 'None', 'regina' ),
					'nofollow' => __( 'Nofollow', 'regina' ),
				),
			),
			'button_icon_left'  => array(
				'std'     => '',
				'type'    => 'iconpicker',
				'options' => get_fontawesome_icons(),
				'label'   => __( 'Button Left Icon ', 'regina' ),
			),
			'button_icon_right' => array(
				'std'     => '',
				'type'    => 'iconpicker',
				'options' => get_fontawesome_icons(),
				'label'   => __( 'Button: Right Icon ', 'regina' ),
			),
			'hide_xs'           => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on extra small devices', 'regina' ),
				'desc'  => __( 'Phones (<768px)', 'regina' ),
			),
			'hide_sm'           => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on small devices', 'regina' ),
				'desc'  => __( 'Tablets (≥768px)', 'regina' ),
			),
			'hide_md'           => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on medium devices', 'regina' ),
				'desc'  => __( 'Desktops (≥992px)', 'regina' ),
			),
			'hide_lg'           => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on large devices', 'regina' ),
				'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
			),
		),
		'content'      => array(
			'std'   => '<ul><li class="checked">30GB Storage</li><li class="checked">512MB Ram</li><li class="checked">10 databases</li><li class="unchecked">1,000 Emails</li><li>25GB Bandwidth</li></ul>',
			'label' => __( 'Features (ul list is best)', 'regina' ),
		),
	),
);

$msm_shortcodes_config['pie'] = array(
	'title'   => __( 'Chart', 'regina' ),
	'params'  => array(
		'percent'     => array(
			'std'   => '85',
			'type'  => 'text',
			'label' => __( 'Percentage', 'regina' ),
		),
		'track_color' => array(
			'type'  => 'colorpicker',
			'label' => __( 'Track Color', 'regina' ),
		),
		'bar_color'   => array(
			'type'  => 'colorpicker',
			'label' => __( 'Bar color', 'regina' ),
		),
		'icon'        => array(
			'std'     => '',
			'type'    => 'iconpicker',
			'options' => get_fontawesome_icons(),
			'label'   => __( 'Chart Icon (FontAwesome)', 'regina' ),
		),
		'hide_xs'     => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'     => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'     => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'     => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
	'content' => array(
		'std'   => '',
		'label' => __( 'Text to be displayed inside the chart wheel', 'regina' ),
	),
);

$msm_shortcodes_config['slider'] = array(
	'title'  => __( 'Slider', 'regina' ),
	'params' => array(
		'id'      => array(
			'std'     => '',
			'type'    => 'select',
			'options' => get_sliders(),
			'label'   => __( 'Select Slider', 'regina' ),
		),
		'hide_xs' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg' => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
);

$msm_shortcodes_config['member'] = array(
	'title'  => __( 'Team', 'regina' ),
	'params' => array(
		'name'         => array(
			'std'   => __( 'John Doe', 'regina' ),
			'type'  => 'text',
			'label' => __( 'Enter team member name', 'regina' ),
		),
		'description'  => array(
			'std'   => __( 'Quisque consectetur sem id nisi lacinia, eu sodales dui rhoncus. Pellentesque habitant morbi tristique senectus et netur.', 'regina' ),
			'type'  => 'textarea',
			'label' => __( 'Team member description', 'regina' ),
		),
		'image_id'     => array(
			'type'  => 'uploader',
			'label' => __( 'Upload Image', 'regina' ),
		),
		'facebook_url' => array(
			'std'   => esc_url( 'https://www.facebook.com/machothemes/' ),
			'type'  => 'text',
			'label' => __( 'Facebook URL', 'regina' ),
		),
		'linkedin_url' => array(
			'std'   => esc_url( 'https://www.linkedin.com/machothemes/' ),
			'type'  => 'text',
			'label' => __( 'LinkedIN URL', 'regina' ),
		),
		'dribbble_url' => array(
			'std'   => esc_url( 'https://www.dribbble.com/machothemes/' ),
			'type'  => 'text',
			'label' => __( 'Dribbble URL', 'regina' ),
		),
		'twitter_url'  => array(
			'std'   => esc_url( 'https://www.twitter.com/machothemes/' ),
			'type'  => 'text',
			'label' => __( 'Twitter URL', 'regina' ),
		),
		'hide_xs'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on extra small devices', 'regina' ),
			'desc'  => __( 'Phones (<768px)', 'regina' ),
		),
		'hide_sm'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on small devices', 'regina' ),
			'desc'  => __( 'Tablets (≥768px)', 'regina' ),
		),
		'hide_md'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on medium devices', 'regina' ),
			'desc'  => __( 'Desktops (≥992px)', 'regina' ),
		),
		'hide_lg'      => array(
			'type'  => 'checkbox',
			'label' => __( 'Hide on large devices', 'regina' ),
			'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
		),
	),
);

$msm_shortcodes_config['icon-list'] = array(
	'title'           => __( 'List With Icon', 'regina' ),
	'params'          => array(),
	'with_parent_tag' => true,
	'child_shortcode' => array(
		'tag'          => 'icon-list-item',
		'clone_button' => __( 'Add List Item', 'regina' ),
		'params'       => array(
			'icon_class' => array(
				'std'     => '',
				'type'    => 'iconpicker',
				'options' => get_fontawesome_icons(),
				'label'   => __( 'List Item Icon', 'regina' ),
			),
			'hide_xs'    => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on extra small devices', 'regina' ),
				'desc'  => __( 'Phones (<768px)', 'regina' ),
			),
			'hide_sm'    => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on small devices', 'regina' ),
				'desc'  => __( 'Tablets (≥768px)', 'regina' ),
			),
			'hide_md'    => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on medium devices', 'regina' ),
				'desc'  => __( 'Desktops (≥992px)', 'regina' ),
			),
			'hide_lg'    => array(
				'type'  => 'checkbox',
				'label' => __( 'Hide on large devices', 'regina' ),
				'desc'  => __( 'Desktops (≥1200px)', 'regina' ),
			),
		),
		'content'      => array(
			'std'   => '',
			'label' => __( 'List Item Text', 'regina' ),
		),
	),
);

$msm_shortcodes_config = apply_filters( 'macho_add_shortcodes_to_sm', $msm_shortcodes_config );
