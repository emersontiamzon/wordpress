<?php

if ( ! function_exists( 'macho_slider_function' ) ) {

	function macho_slider_function( $atts ) {

		// no more PHP warnings
		$id            = '';
		$return_string = '';

		// get post ID from shortcode
		$atts = shortcode_atts(
			array(
				'id'      => '',
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		// get the slider settings from the post meta, using the post ID
		$slider_options = get_post_meta( $atts['id'], strtolower( MT_THEME_NAME ) . '_options', true );

		// let's make sure we actually have at least a slider created
		if ( $slider_options ) {

			if ( 'slider' == $slider_options['slider-type-select'] ) { // slider is selected (also, default value)
				get_template_part( 'includes/libraries/macho-shortcode-manager/slider/views/manual-slider' );

				if ( ! wp_script_is( 'bxSlider-min-js', 'enqueued' ) ) {
					wp_enqueue_script( 'bxSlider-min-js' );
				}
			} else { // carousel is selected

				$return_string = '';

				switch ( $slider_options['carousel-type'] ) {

					case 'manual':
						get_template_part( 'includes/libraries/macho-shortcode-manager/slider/views/manual-carousel' );
						break;

					case 'bigtestcarousel':
						get_template_part( 'includes/libraries/macho-shortcode-manager/slider/views/big-testimonials-carousel' );
						break;

					case 'projectscarousel':
						get_template_part( 'includes/libraries/macho-shortcode-manager/slider/views/projects-carousel' );
						break;

				}
				if ( ! wp_script_is( 'owlCarousel-min-js', 'enqueued' ) ) {
					wp_enqueue_script( 'owlCarousel-min-js' );
				}
			}
		}
		return $return_string;
	}
} // End if().


if ( ! function_exists( 'macho_button_function' ) ) {

	function macho_button_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'type'              => false,
				'size'              => false,
				'block'             => false,
				'dropdown'          => false,
				'link'              => '',
				'target'            => false,
				'disabled'          => false,
				'active'            => false,
				'xclass'            => false,
				'title'             => false,
				'data'              => false,
				'border-radius'     => false,
				'background-color'  => false,
				'text-color'        => false,
				'hover-color'       => false,
				'button_icon_left'  => '',
				'button_icon_right' => '',
				'hide_xs'           => 'false',
				'hide_sm'           => 'false',
				'hide_md'           => 'false',
				'hide_lg'           => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$icon_left_class  = strtolower( esc_attr( $atts['button_icon_left'] ) );
		$icon_right_class = strtolower( esc_attr( $atts['button_icon_right'] ) );

		$icon_left  = '';
		$icon_right = '';

		if ( $icon_left_class ) {
			$icon_left = '<i class="' . $icon_left_class . '"></i>';
		}
		if ( $icon_right_class ) {
			$icon_right = '<i class="' . $icon_right_class . '"></i>';
		}

		$class  = 'btn' . $hide_class;
		$class .= ( $atts['type'] ) ? ' btn-' . $atts['type'] : ' btn-default';
		$class .= ( $atts['size'] ) ? ' btn-' . $atts['size'] : '';
		$class .= ( 'true' == $atts['block'] ) ? ' btn-block' : '';
		$class .= ( 'true' == $atts['dropdown'] ) ? ' dropdown-toggle' : '';
		$class .= ( 'true' == $atts['disabled'] ) ? ' disabled' : '';
		$class .= ( 'true' == $atts['active'] ) ? ' active' : '';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<a href="%s" class="%s" %s%s%s style="border-radius:%s; background-color: %s; color: %s;">%s<span onMouseOver="this.style.color=\'%s\'" onMouseOut="this.style.color=\'%s\'" >%s </span>%s</a>',
			esc_url( $atts['link'] ),
			esc_attr( $class ),
			( $atts['target'] ) ? sprintf( ' target="%s"', esc_attr( $atts['target'] ) ) : '',
			( $atts['title'] ) ? sprintf( ' title="%s"', esc_attr( $atts['title'] ) ) : '',
			( $data_props ) ? ' ' . $data_props : '',
			esc_attr( $atts['border-radius'] ),
			esc_attr( $atts['background-color'] ),
			esc_attr( $atts['text-color'] ),
			$icon_left,
			esc_attr( $atts['hover-color'] ),
			esc_attr( $atts['text-color'] ),
			do_shortcode( $content ),
			$icon_right
		);

	}
} // End if().

if ( ! function_exists( 'macho_container_function' ) ) {

	function macho_container_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'fluid'   => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = ( 'true' == $atts['fluid'] ) ? 'container-fluid' : 'container';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';
		$class .= $hide_class;

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_alert_function' ) ) {

	function macho_alert_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'type'        => false,
				'dismissable' => false,
				'xclass'      => false,
				'data'        => false,
				'hide_xs'     => 'false',
				'hide_sm'     => 'false',
				'hide_md'     => 'false',
				'hide_lg'     => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'alert' . $hide_class;
		$class .= ( $atts['type'] ) ? ' alert-' . $atts['type'] : ' alert-success';
		$class .= ( 'true' == $atts['dismissable'] ) ? ' alert-dismissable' : '';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$dismissable = ( $atts['dismissable'] ) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s"%s>%s%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			$dismissable,
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_progress_function' ) ) {

	function macho_progress_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'striped'  => false,
				'animated' => false,
				'xclass'   => false,
				'data'     => false,
				'hide_xs'  => 'false',
				'hide_sm'  => 'false',
				'hide_md'  => 'false',
				'hide_lg'  => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'progress' . $hide_class;
		$class .= ( 'true' == $atts['striped'] ) ? ' progress-striped' : '';
		$class .= ( 'true' == $atts['animated'] ) ? ' active' : '';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_progress_bars_function' ) ) {

	function macho_progress_bars_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'type'    => false,
				'percent' => false,
				'label'   => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'progress-bar' . $hide_class;
		$class .= ( $atts['type'] ) ? ' progress-bar-' . $atts['type'] : '';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s" role="progressbar" %s%s>%s</div>',
			esc_attr( $class ),
			( $atts['percent'] ) ? ' aria-value="' . (int) $atts['percent'] . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . (int) $atts['percent'] . '%;"' : '',
			( $data_props ) ? ' ' . $data_props : '',
			( $atts['percent'] ) ? sprintf( '<span%s>%s</span>', ( ! $atts['label'] ) ? ' class="sr-only"' : '', (int) $atts['percent'] . '% Complete' ) : ''
		);
	}
} // End if().

if ( ! function_exists( 'macho_code_function' ) ) {

	function macho_code_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'inline'     => false,
				'scrollable' => false,
				'xclass'     => false,
				'data'       => false,
				'hide_xs'    => 'false',
				'hide_sm'    => 'false',
				'hide_md'    => 'false',
				'hide_lg'    => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = $hide_class;
		$class .= ( 'true' == $atts['scrollable'] ) ? ' pre-scrollable' : '';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<%1$s class="%2$s"%3$s>%4$s</%1$s>',
			( $atts['inline'] ) ? 'code' : 'pre',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_row_function' ) ) {

	function macho_row_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'row' . $hide_class;
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_columns_function' ) ) {

	function macho_columns_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'lg'        => false,
				'md'        => false,
				'sm'        => false,
				'xs'        => false,
				'offset_lg' => false,
				'offset_md' => false,
				'offset_sm' => false,
				'offset_xs' => false,
				'pull_lg'   => false,
				'pull_md'   => false,
				'pull_sm'   => false,
				'pull_xs'   => false,
				'push_lg'   => false,
				'push_md'   => false,
				'push_sm'   => false,
				'push_xs'   => false,
				'xclass'    => false,
				'data'      => false,
				'hide_xs'   => 'false',
				'hide_sm'   => 'false',
				'hide_md'   => 'false',
				'hide_lg'   => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = $hide_class;
		$class .= ( $atts['lg'] ) ? ' col-lg-' . $atts['lg'] : '';
		$class .= ( $atts['md'] ) ? ' col-md-' . $atts['md'] : '';
		$class .= ( $atts['sm'] ) ? ' col-sm-' . $atts['sm'] : '';
		$class .= ( $atts['xs'] ) ? ' col-xs-' . $atts['xs'] : '';
		$class .= ( $atts['offset_lg'] || '0' === $atts['offset_lg'] ) ? ' col-lg-offset-' . $atts['offset_lg'] : '';
		$class .= ( $atts['offset_md'] || '0' === $atts['offset_md'] ) ? ' col-md-offset-' . $atts['offset_md'] : '';
		$class .= ( $atts['offset_sm'] || '0' === $atts['offset_sm'] ) ? ' col-sm-offset-' . $atts['offset_sm'] : '';
		$class .= ( $atts['offset_xs'] || '0' === $atts['offset_xs'] ) ? ' col-xs-offset-' . $atts['offset_xs'] : '';
		$class .= ( $atts['pull_lg'] || '0' === $atts['pull_lg'] ) ? ' col-lg-pull-' . $atts['pull_lg'] : '';
		$class .= ( $atts['pull_md'] || '0' === $atts['pull_md'] ) ? ' col-md-pull-' . $atts['pull_md'] : '';
		$class .= ( $atts['pull_sm'] || '0' === $atts['pull_sm'] ) ? ' col-sm-pull-' . $atts['pull_sm'] : '';
		$class .= ( $atts['pull_xs'] || '0' === $atts['pull_xs'] ) ? ' col-xs-pull-' . $atts['pull_xs'] : '';
		$class .= ( $atts['push_lg'] || '0' === $atts['push_lg'] ) ? ' col-lg-push-' . $atts['push_lg'] : '';
		$class .= ( $atts['push_md'] || '0' === $atts['push_md'] ) ? ' col-md-push-' . $atts['push_md'] : '';
		$class .= ( $atts['push_sm'] || '0' === $atts['push_sm'] ) ? ' col-sm-push-' . $atts['push_sm'] : '';
		$class .= ( $atts['push_xs'] || '0' === $atts['push_xs'] ) ? ' col-xs-push-' . $atts['push_xs'] : '';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().




if ( ! function_exists( 'macho_label_function' ) ) {

	function macho_label_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'type'    => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'label' . $hide_class;
		$class .= ( $atts['type'] ) ? ' label-' . $atts['type'] : ' label-default';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<span class="%s"%s>%s</span>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().


if ( ! function_exists( 'macho_well_function' ) ) {

	function macho_well_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'size'    => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'well' . $hide_class;
		$class .= ( $atts['size'] ) ? ' well-' . $atts['size'] : '';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_panel_function' ) ) {

	function macho_panel_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'title'   => false,
				'heading' => false,
				'type'    => false,
				'footer'  => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'panel' . $hide_class;
		$class .= ( $atts['type'] ) ? ' panel-' . $atts['type'] : ' panel-default';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		if ( ! $atts['heading'] && $atts['title'] ) {
			$atts['heading'] = $atts['title'];
			$atts['title']   = true;
		}

		$data_props = parse_data_attributes( $atts['data'] );

		$footer = ( $atts['footer'] ) ? '<div class="panel-footer">' . $atts['footer'] . '</div>' : '';

		if ( $atts['heading'] ) {
			$heading = sprintf(
				'<div class="panel-heading">%s%s%s</div>',
				( $atts['title'] ) ? '<h3 class="panel-title">' : '',
				esc_html( $atts['heading'] ),
				( $atts['title'] ) ? '</h3>' : ''
			);
		} else {
			$heading = '';
		}

		return sprintf(
			'<div class="%s"%s>%s<div class="panel-body">%s</div>%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			$heading,
			do_shortcode( $content ),
			( $footer ) ? ' ' . $footer : ''
		);
	}
} // End if().

if ( ! function_exists( 'macho_tabs_function' ) ) {

	function macho_tabs_function( $atts, $content = null ) {

		if ( isset( $GLOBALS['tabs_count'] ) ) {
			$GLOBALS['tabs_count']++;
		} else {
			$GLOBALS['tabs_count'] = 0;
		}

		$GLOBALS['tabs_default_count'] = 0;

		$atts = shortcode_atts(
			array(
				'type'    => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$ul_class  = 'nav' . $hide_class;
		$ul_class .= ( $atts['type'] ) ? ' nav-' . $atts['type'] : ' nav-tabs';
		$ul_class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$div_class = 'tab-content';

		$id = 'custom-tabs-' . $GLOBALS['tabs_count'];

		$data_props = parse_data_attributes( $atts['data'] );

		$atts_map = macho_attribute_map( $content );

		// Extract the tab titles for use in the tab widget.
		if ( $atts_map ) {
			$tabs                           = array();
			$GLOBALS['tabs_default_active'] = true;
			foreach ( $atts_map as $check ) {
				if ( ! empty( $check['tab']['active'] ) ) {
					$GLOBALS['tabs_default_active'] = false;
				}
			}
			$i = 0;
			foreach ( $atts_map as $tab ) {
				$tabs[] = sprintf(
					'<li%s><a href="#%s" data-toggle="tab">%s</a></li>',
					( ! empty( $tab['tab']['active'] ) || ( $GLOBALS['tabs_default_active'] && 0 == $i ) ) ? ' class="active"' : '',
					'custom-tab-' . $GLOBALS['tabs_count'] . '-' . md5( $tab['tab']['title'] ),
					$tab['tab']['title']
				);
				$i++;
			}
		}
		return sprintf(
			'<ul class="%s" id="%s"%s>%s</ul><div class="%s">%s</div>',
			esc_attr( $ul_class ),
			esc_attr( $id ),
			( $data_props ) ? ' ' . $data_props : '',
			( $tabs ) ? implode( $tabs ) : '',
			esc_attr( $div_class ),
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_tab_function' ) ) {

	function macho_tab_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'title'   => false,
				'active'  => false,
				'fade'    => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		if ( $GLOBALS['tabs_default_active'] && 0 == $GLOBALS['tabs_default_count'] ) {
			$atts['active'] = true;
		}
		$GLOBALS['tabs_default_count']++;

		$class  = 'tab-pane' . $hide_class;
		$class .= ( 'true' == $atts['fade'] ) ? ' fade' : '';
		$class .= ( 'true' == $atts['active'] ) ? ' active' : '';
		$class .= ( 'true' == $atts['active'] && 'true' == $atts['fade'] ) ? ' in' : '';
		$id     = 'custom-tab-' . $GLOBALS['tabs_count'] . '-' . md5( $atts['title'] );

		$data_props = parse_data_attributes( $atts['data'] );
		return sprintf(
			'<div id="%s" class="%s"%s>%s</div>',
			esc_attr( $id ),
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);

	}
} // End if().

if ( ! function_exists( 'macho_collapsibles_function' ) ) {

	function macho_collapsibles_function( $atts, $content = null ) {

		if ( isset( $GLOBALS['collapsibles_count'] ) ) {
			$GLOBALS['collapsibles_count']++;
		} else {
			$GLOBALS['collapsibles_count'] = 0;
		}
		$atts = shortcode_atts(
			array(
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'panel-group' . $hide_class;
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$id = 'custom-collapse-' . $GLOBALS['collapsibles_count'];

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s" id="%s"%s>%s</div>',
			esc_attr( $class ),
			esc_attr( $id ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_collapse_function' ) ) {

	function macho_collapse_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'title'   => false,
				'type'    => false,
				'active'  => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$panel_class  = 'panel' . $hide_class;
		$panel_class .= ( $atts['type'] ) ? ' panel-' . $atts['type'] : ' panel-default';
		$panel_class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$collapse_class  = 'panel-collapse';
		$collapse_class .= ( 'true' == $atts['active'] ) ? ' in' : ' collapse';

		$a_class  = '';
		$a_class .= ( 'true' == $atts['active'] ) ? '' : 'collapsed';

		$parent           = 'custom-collapse-' . $GLOBALS['collapsibles_count'];
		$current_collapse = $parent . '-' . md5( $atts['title'] );

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%1$s"%2$s>
        <div class="panel-heading">
          <h4 class="panel-title">
            <a class="%3$s" data-toggle="collapse" data-parent="#%4$s" href="#%5$s">%6$s</a>
          </h4>
        </div>
        <div id="%5$s" class="%7$s">
          <div class="panel-body">%8$s</div>
        </div>
      </div>',
			esc_attr( $panel_class ),
			( $data_props ) ? ' ' . $data_props : '',
			$a_class,
			$parent,
			$current_collapse,
			$atts['title'],
			esc_attr( $collapse_class ),
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_tooltip_function' ) ) {

	function macho_tooltip_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'title'     => '',
				'placement' => 'top',
				'animation' => 'true',
				'html'      => 'false',
				'data'      => '',
				'hide_xs'   => 'false',
				'hide_sm'   => 'false',
				'hide_md'   => 'false',
				'hide_lg'   => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class         = 'bs-tooltip' . $hide_class;
		$atts['data'] .= ( $atts['animation'] ) ? check_for_data( $atts['data'] ) . 'animation,' . $atts['animation'] : '';
		$atts['data'] .= ( $atts['placement'] ) ? check_for_data( $atts['data'] ) . 'placement,' . $atts['placement'] : '';
		$atts['data'] .= ( $atts['html'] ) ? check_for_data( $atts['data'] ) . 'html,' . $atts['html'] : '';

		$return  = '';
		$tag     = 'span';
		$content = do_shortcode( $content );
		$return .= get_dom_element( $tag, $content, $class, $atts['title'], $atts['data'] );
		return $return;

	}
} // End if().

if ( ! function_exists( 'macho_popover_function' ) ) {

	function macho_popover_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'title'     => false,
				'text'      => '',
				'placement' => 'top',
				'animation' => 'true',
				'html'      => 'false',
				'data'      => '',
				'hide_xs'   => 'false',
				'hide_sm'   => 'false',
				'hide_md'   => 'false',
				'hide_lg'   => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class = 'bs-popover' . $hide_class;

		$atts['data'] .= check_for_data( $atts['data'] ) . 'toggle,popover';
		$atts['data'] .= check_for_data( $atts['data'] ) . 'content,' . $atts['text'];
		$atts['data'] .= ( $atts['animation'] ) ? check_for_data( $atts['data'] ) . 'animation,' . $atts['animation'] : '';
		$atts['data'] .= ( $atts['placement'] ) ? check_for_data( $atts['data'] ) . 'placement,' . $atts['placement'] : '';
		$atts['data'] .= ( $atts['html'] ) ? check_for_data( $atts['data'] ) . 'html,' . $atts['html'] : '';

		$return  = '';
		$tag     = 'span';
		$content = do_shortcode( $content );
		$return .= get_dom_element( $tag, $content, $class, $atts['title'], $atts['data'] );
		return $return;
	}
} // End if().

if ( ! function_exists( 'macho_emphasis_function' ) ) {

	function macho_emphasis_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'type'    => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = $hide_class;
		$class .= ( $atts['type'] ) ? 'text-' . $atts['type'] : 'text-muted';
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<span class="%s"%s>%s</span>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_media_function' ) ) {

	function macho_media_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class      = 'media' . $hide_class;
		$class     .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';
		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s"%s>%s</div>',
			esc_attr( $class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().

if ( ! function_exists( 'macho_media_object_function' ) ) {
	function macho_media_object_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'pull'    => false,
				'media'   => 'left',
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$class  = 'media-object img-responsive' . $hide_class;
		$class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$media_class = '';
		$media_class = ( $atts['media'] ) ? 'media-' . $atts['media'] : '';
		$media_class = ( $atts['pull'] ) ? 'pull-' . $atts['pull'] : $media_class;

		$return  = '';
		$tag     = array( 'figure', 'div', 'img', 'i', 'span' );
		$content = do_shortcode( preg_replace( '/(<br>)+$/', '', $content ) );
		$return .= scrape_dom_element( $tag, $content, $class, '', $atts['data'] );
		$return  = '<span class="' . $media_class . '">' . $return . '</span>';
		return $return;
	}
} // End if().

if ( ! function_exists( 'macho_media_body_function' ) ) {

	function macho_media_body_function( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'title'   => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$div_class  = 'media-body' . $hide_class;
		$div_class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$h4_class  = 'media-heading';
		$h4_class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );
		return sprintf(
			'<div class="%s" %s><h4 class="%s">%s</h4>%s</div>',
			esc_attr( $div_class ),
			( $data_props ) ? ' ' . $data_props : '',
			esc_attr( $h4_class ),
			esc_html( $atts['title'] ),
			do_shortcode( $content )
		);
	}
} // End if().


if ( ! function_exists( 'macho_checkmark_lists' ) ) {

	function macho_checkmark_lists( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$div_class  = 'mt-features' . $hide_class;
		$div_class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';
		$data_props = parse_data_attributes( $atts['data'] );
		return sprintf(
			'<ul class="%s" %s>%s</ul>',
			esc_attr( $div_class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);
	}
} // End if().


if ( ! function_exists( 'macho_checkmark_list_item' ) ) {

	function macho_checkmark_list_item( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'icon'    => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$div_class  = $atts['icon'] . $hide_class;
		$div_class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';
		$data_props = parse_data_attributes( $atts['data'] );
		return sprintf(
			'<li><span class="%s" %s></span>%s</li>',
			esc_attr( $div_class ),
			( $data_props ) ? ' ' . $data_props : '',
			do_shortcode( $content )
		);

	}
} // End if().

if ( ! function_exists( 'macho_contact_block' ) ) {

	function macho_contact_block( $atts ) {

		$atts = shortcode_atts(
			array(
				'address' => true,
				'phone'   => true,
				'fax'     => true,
				'email'   => true,
				'color'   => '#FFF',
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		// get stored theme options
		$options = get_option( strtolower( MT_THEME_NAME ) . '_options' );

		if ( false !== $atts['address'] ) {
			$street = isset( $options['contact-street'] ) ? $options['contact-street'] : '';
		} else {
			$street = '';
		}

		if ( false !== $atts['phone'] ) {
			$phone = isset( $options['contact-phone'] ) ? $options['contact-phone'] : '';
		} else {
			$phone = '';
		}

		if ( false !== $atts['fax'] ) {
			$fax = isset( $options['contact-fax'] ) ? $options['contact-fax'] : '';
		} else {
			$fax = '';
		}

		if ( false !== $atts['email'] ) {
			$email = isset( $options['contact-email'] ) ? $options['contact-email'] : '';
		} else {
			$email = '';
		}

		$return_string = '<div class="mt-contact-info' . $hide_class . '" style="color:' . $atts['color'] . ';">';
		if ( false !== $atts['address'] ) {

			$return_string .= '<h3>' . esc_html( mt_get_theme_option( 'address-contact-string' ) ) . '</h3>';

			$return_string .= '<p class="contact-info-details address">' . wp_kses_post(
				$street, array(
					'a'  => array(
						'href'  => array(),
						'class' => array(),
					),
					'u'  => array(),
					'i'  => array(
						'class' => array(),
					),
					'b'  => array(),
					'p'  => array(),
					'br' => array(),
				)
			) . '</p>';

		}

		if ( false !== $atts['phone'] || false !== $atts['fax'] || false !== $atts['email'] ) {

			$return_string .= '<h3>' . esc_html( mt_get_theme_option( 'customer-support-string' ) ) . '</h3>';

			$return_string .= '<p class="contact-info-details">';

			if ( '' !== $phone ) {
				$return_string .= 'Phone: ' . esc_html( $phone ) . '<br />';
			}

			if ( '' !== $fax ) {
				$return_string .= 'Fax: ' . esc_html( $fax ) . '<br />';
			}

			if ( '' !== $email ) {
				$return_string .= 'Email: ' . antispambot( esc_html( $email ) ) . '<br />';
			}

			$return_string .= '</p>';

		}

		$return_string .= '</div>';

		return $return_string;

	}
} // End if().

if ( ! function_exists( 'macho_google_maps' ) ) {

	function macho_google_maps( $atts ) {

		$atts = shortcode_atts(
			array(
				'address'           => false,
				'width'             => '100%',
				'height'            => '400px',
				'enablescrollwheel' => 'true',
				'zoom'              => 15,
				'disablecontrols'   => 'false',
				'hide_xs'           => 'false',
				'hide_sm'           => 'false',
				'hide_md'           => 'false',
				'hide_lg'           => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$address = $atts['address'];

		if ( empty( $address ) ) {
			$address = get_theme_mod( 'regina_map_address', 'New York' );
		}

		if ( $address && wp_script_is( 'google-maps-api', 'registered' ) ) :

			wp_print_scripts( 'google-maps-api' );

			$coordinates = macho_map_get_coordinates( $address );

			if ( ! is_array( $coordinates ) ) {
				return;
			}

			$map_id = uniqid( 'pw_map_' ); // generate a unique ID for this map

			ob_start(); ?>
			<div class="macho_map_canvas<?php echo $hide_class; ?>" id="<?php echo esc_attr( $map_id ); ?>" style="height: <?php echo esc_attr( $atts['height'] ); ?>; width: <?php echo esc_attr( $atts['width'] ); ?>"></div>
			<script type="text/javascript">
				var map_<?php echo $map_id; ?>;

				function macho_run_map_<?php echo $map_id; ?>(){
					var location = new google.maps.LatLng("<?php echo $coordinates['lat']; ?>", "<?php echo $coordinates['lng']; ?>");
					var map_options = {
						zoom: <?php echo $atts['zoom']; ?>,
						center: location,
						// This is where you would paste any styles.
						styles: [{"featureType":"all","elementType":"geometry.fill","stylers":[{"lightness":"82"},{"saturation":"-100"}]},{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#879299"}]}],
						scrollwheel: <?php echo 'true' === strtolower( $atts['enablescrollwheel'] ) ? '1' : '0'; ?>,
						disableDefaultUI: <?php echo 'true' === strtolower( $atts['disablecontrols'] ) ? '1' : '0'; ?>,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					}
					map_<?php echo $map_id; ?> = new google.maps.Map(document.getElementById("<?php echo $map_id; ?>"), map_options);
					var marker = new google.maps.Marker({
						position: location,
						map: map_<?php echo $map_id; ?>
					});
				}
				macho_run_map_<?php echo $map_id; ?>();
			</script>
			<?php
			return ob_get_clean();
		else :
			return __( 'This Google Map cannot be loaded because the maps API does not appear to be loaded', 'regina' );
		endif;

	}
} // End if().

if ( ! function_exists( 'macho_map_get_coordinates' ) ) {
	/**
	 * Retrieve coordinates for an address
	 *
	 * Coordinates are cached using transients and a hash of the address
	 *
	 * @access      private
	 * @since       1.0
	 * @return      void
	 */
	function macho_map_get_coordinates( $address, $force_refresh = false ) {

		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		if ( $force_refresh || false === $coordinates ) {

			$args     = array(
				'address' => urlencode( $address ),
				'sensor'  => 'false',
			);
			$url      = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );
			if ( is_wp_error( $response ) ) {
				return;
			}

			$data = wp_remote_retrieve_body( $response );
			if ( is_wp_error( $data ) ) {
				return;
			}

			if ( 200 == $response['response']['code'] ) {

				$data = json_decode( $data );

				if ( 'OK' === $data->status ) {

					$coordinates            = $data->results[0]->geometry->location;
					$cache_value['lat']     = $coordinates->lat;
					$cache_value['lng']     = $coordinates->lng;
					$cache_value['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $cache_value, 3600 * 24 * 30 * 3 );
					$data = $cache_value;

				} elseif ( 'ZERO_RESULTS' === $data->status ) {
					return __( 'No location found for the entered address.', 'regina' );
				} elseif ( 'INVALID_REQUEST' === $data->status ) {
					return __( 'Invalid request. Did you enter an address?', 'regina' );
				} else {
					return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'regina' );
				}
			} else {
				return __( 'Unable to contact Google API service.', 'regina' );
			}
		} else {
			// return cached results
			$data = $coordinates;
		} // End if().

		return $data;
	}
} // End if().


if ( ! function_exists( 'macho_spacer' ) ) {

	function macho_spacer( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'height'  => false,
				'xclass'  => false,
				'data'    => false,
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$div_class  = 'mt-spacer' . $hide_class;
		$div_class .= ( $atts['xclass'] ) ? ' ' . $atts['xclass'] : '';

		$data_props = parse_data_attributes( $atts['data'] );

		return sprintf(
			'<div class="%s" %s style="height: %s;"></div>',
			esc_attr( $div_class ),
			( $data_props ) ? ' ' . $data_props : '',
			esc_attr( $atts['height'] ),
			do_shortcode( $content )
		);

	}
} // End if().

if ( ! function_exists( 'macho_separator' ) ) {
	function macho_separator( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'style'         => false,
				'margin-top'    => false,
				'margin_bottom' => false,
				'color'         => false,
				'hide_xs'       => 'false',
				'hide_sm'       => 'false',
				'hide_md'       => 'false',
				'hide_lg'       => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$div_class             = 'mt-separator' . $hide_class;
		$atts['style']         = $atts['style'] ? $atts['style'] : 'solid';
		$atts['margin_top']    = $atts['margin_top'] ? $atts['margin_top'] : '0px';
		$atts['margin_bottom'] = $atts['margin_bottom'] ? $atts['margin_bottom'] : '0px';
		$atts['color']         = $atts['color'] ? $atts['color'] : '#ddd';

		return sprintf(
			'<div class="%s %s" style="margin-top: %s; margin-bottom: %s;border-color: %s"></div>',
			esc_attr( $div_class ),
			esc_attr( $atts['style'] ),
			esc_attr( $atts['margin_top'] ),
			esc_attr( $atts['margin_bottom'] ),
			esc_attr( $atts['color'] )
		);
	}
} // End if().


/*
 * Pricing Table
 *
 */
if ( ! function_exists( 'macho_pricing_table_shortcode' ) ) {
	function macho_pricing_table_shortcode( $atts, $content = null ) {

		$atts           = shortcode_atts(
			array(
				'visibility' => 'all',
				'size'       => ' ',
				'class'      => ' ',
			), $atts
		);
		$atts['size']   = 'col-md-' . $atts['size'];
		$atts['class'] .= ' ' . $atts['size'];

		return '<div class="mt-pricing-table ' . esc_attr( $atts['class'] ) . ' mt-' . esc_attr( $atts['visibility'] ) . ' ">' . do_shortcode( $content ) . '</div>';
	}
}

if ( ! function_exists( 'macho_pricing_product_image' ) ) {
	function macho_pricing_product_image( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'id'      => '',
				'hide_xs' => 'false',
				'hide_sm' => 'false',
				'hide_md' => 'false',
				'hide_lg' => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		if ( null !== $atts['id'] ) {
			$image_url = esc_url( wp_get_attachment_url( absint( $atts['id'] ) ) );
			return '<img class="mt-product-image' . $hide_class . '" src="' . $image_url . '" alt="pricing table product image">';
		}

	}
} // End if().

if ( ! function_exists( 'macho_pricing_shortcode' ) ) {
	function macho_pricing_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'size'                 => '12',
				'position'             => ' ',
				'featured'             => 'no',
				'plan'                 => '', // plan name
				'cost'                 => '', // plan price
				'per'                  => '', // per year / per month, etc
				'button_url'           => '#', // url for purchase button
				'button_text'          => __( 'Purchase', 'regina' ), // text for purchase button
				'button_target'        => 'self', // button target -> self / blank
				'button_rel'           => 'nofollow', // button rel -> seo purposes
				'button_border_radius' => '0',
				'button_icon_left'     => '',
				'button_icon_right'    => '',
				'class'                => '',
				'image_id'             => false,
				'hide_xs'              => 'false',
				'hide_sm'              => 'false',
				'hide_md'              => 'false',
				'hide_lg'              => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		//set variables
		$featured_pricing    = ( 'yes' == $atts['featured'] ) ? 'featured' : null;
		$border_radius_style = ( $atts['button_border_radius'] ) ? 'style="border-radius:' . esc_attr( $atts['button_border_radius'] ) . '"' : null;
		$icon_left           = strtolower( esc_attr( $atts['button_icon_left'] ) );
		$icon_right          = strtolower( esc_attr( $atts['button_icon_right'] ) );

		//start content
		$pricing_content  = '';
		$pricing_content .= '<div class="mt-pricing ' . $featured_pricing . esc_attr( $atts['class'] ) . $hide_class . ' col-md-' . esc_attr( $atts['size'] ) . '">';
		$pricing_content .= '<div class="mt-pricing-header">';

		# If there's a plan name set ...
		if ( null !== $atts['plan'] ) {
			$pricing_content .= '<h5>' . esc_attr( $atts['plan'] ) . '</h5>';
		}

		# If there's no 'per' option specified, don't display a default.
		if ( null !== $atts['per'] && ! empty( $atts['per'] ) ) {
			$pricing_content .= '<div class="mt-pricing-cost">' . esc_attr( $atts['cost'] ) . '<span class="mt-pricing-per"> / ' . esc_attr( $atts['per'] ) . '</span></div>';
		} else {
			$pricing_content .= '<div class="mt-pricing-cost">' . esc_attr( $atts['cost'] ) . '</div>';
		}

		$pricing_content .= '</div><!--/.mt-pricing-header-->';
		$pricing_content .= '<div class="mt-pricing-content">';
		$pricing_content .= wp_kses(
			$content, array(
				'ul'  => array(),
				'img' => array(
					'class' => array(),
					'alt'   => array(),
					'title' => array(),
				),
				'li'  => array(
					'class' => array(),
				),
			)
		);

		if ( $atts['image_id'] ) {
			$url              = wp_get_attachment_url( $atts['image_id'] );
			$pricing_content .= '<img src="' . $url . '" class="img-responsive">';
		}
		$pricing_content .= '</div>';

		# Check to see if we have a button URL
		if ( null !== $atts['button_url'] ) {

			$pricing_content .= '<div class="mt-pricing-button"><a href="' . esc_url( $atts['button_url'] ) . '" class="mt-button" target="_' . esc_attr( $atts['button_target'] ) . '" rel="' . esc_attr( $atts['button_rel'] ) . '" ' . esc_attr( $border_radius_style ) . '><span class="mt-button-inner" >';

			# Left Icon ?
			if ( '' !== $icon_left && 'none' !== $icon_left ) {
				$pricing_content .= '<i class="mt-pricing-button-icon-left fa fa-' . $icon_left . '"></i>';
			}

			# If we have text for the button ...
			if ( null !== $atts['button_text'] ) {
				$pricing_content .= esc_html( $atts['button_text'] );
			}

			# Right Icon ?
			if ( '' !== $icon_right && 'none' !== $icon_right ) {
				$pricing_content .= '<i class="mt-pricing-button-icon-right fa fa-' . $icon_right . '"></i>';
			}

			$pricing_content .= '</span></a></div>';
		} else {

			$pricing_content .= '<div class="mt-pricing-button"' . esc_attr( $border_radius_style ) . '><span class="mt-button-inner">';

			# Left Icon ?
			if ( '' !== $icon_left && 'none' !== $icon_left ) {
				$pricing_content .= '<i class="mt-pricing-button-icon-left fa fa-' . $icon_left . '"></i>';
			}

			# If we have text for the button ...
			if ( null !== $atts['button_text'] ) {
				$pricing_content .= esc_html( $atts['button_text'] );
			}

			# Right Icon ?
			if ( '' !== $icon_right && 'none' !== $icon_right ) {
				$pricing_content .= '<i class="mt-pricing-button-icon-right fa fa-' . $icon_right . '"></i>';
			}
			$pricing_content .= '</span></div>';
		} // End if().

		$pricing_content .= '</div>';
		return do_shortcode( $pricing_content );
	}
} // End if().


/*
 * Headings
 *
 */
if ( ! function_exists( 'macho_heading_shortcode' ) ) {
	function macho_heading_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'type'          => 'h2',
				'style'         => 'double-line',
				'margin_top'    => '',
				'margin_bottom' => '',
				'text_align'    => '',
				'font_size'     => '',
				'color'         => '',
				'class'         => '',
				'icon_left'     => '',
				'icon_right'    => '',
				'hide_xs'       => 'false',
				'hide_sm'       => 'false',
				'hide_md'       => 'false',
				'hide_lg'       => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$style_attr = '';
		if ( $atts['font_size'] ) {
			$style_attr .= 'font-size: ' . esc_attr( $atts['font_size'] ) . ';';
		}
		if ( $atts['color'] ) {
			$style_attr .= 'color: ' . esc_attr( $atts['color'] ) . ';';
		}
		if ( $atts['margin_bottom'] ) {
			$style_attr .= 'margin-bottom: ' . esc_attr( $atts['margin_bottom'] ) . 'px;';
		}
		if ( $atts['margin_top'] ) {
			$style_attr .= 'margin-top: ' . esc_attr( $atts['margin_top'] ) . 'px;';
		}

		if ( $atts['text_align'] ) {
			$text_align = 'text-' . $atts['text_align'];
		} else {
			$text_align = 'text-left';
		}

		$return_string = '<' . esc_attr( $atts['type'] ) . ' class="mt-heading' . $hide_class . ' mt-heading-' . esc_attr( $atts['style'] ) . ' ' . $text_align . ' ' . esc_attr( $atts['class'] ) . '" style="' . $style_attr . '"><span>';
		if ( '' !== $atts['icon_left'] && 'none' !== $atts['icon_left'] ) {
			$return_string .= '<i class="mt-heading-icon-left fa fa-' . esc_attr( $atts['icon_left'] ) . '"></i>';
		}
		$return_string .= $content;

		if ( '' !== $atts['icon_right'] && 'none' !== $atts['icon_right'] ) {
			$return_string .= '<i class="mt-heading-icon-right fa fa-' . esc_attr( $atts['icon_right'] ) . '"></i>';
		}
		$return_string .= '</span></' . esc_attr( $atts['type'] ) . '>';

		return $return_string;
	}
} // End if().

/*
 * Ribbon CTA
 *
 */
if ( ! function_exists( 'macho_callout_shortcode' ) ) {
	function macho_callout_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'background-color'        => '',
				'caption'                 => '',
				'button_text'             => '',
				'button_url'              => 'http://www.machothemes.com',
				'button_rel'              => 'nofollow',
				'button_target'           => 'blank',
				'button_size'             => '',
				'button-background-color' => '',
				'button-text-color'       => '',
				'class'                   => '',
				'button_icon_left'        => '',
				'button_icon_right'       => '',
				'visibility'              => 'all',
				'hide_xs'                 => 'false',
				'hide_sm'                 => 'false',
				'hide_md'                 => 'false',
				'hide_lg'                 => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}
		$style = '';
		if ( '' != $atts['button-background-color'] ) {
			$style .= 'background-color:' . esc_attr( $atts['button-background-color'] ) . ';';
		}
		if ( '' != $atts['button-text-color'] ) {
			$style .= 'color:' . esc_attr( $atts['button-text-color'] ) . ';';
		}
		if ( '' != $style ) {
			$style = 'style="' . $style . '"';
		}
		$icon_left        = strtolower( esc_attr( $atts['button_icon_left'] ) );
		$icon_right       = strtolower( esc_attr( $atts['button_icon_right'] ) );
		$background_color = ( '' !== $atts['background-color'] ) ? $atts['background-color'] : 'transparent';

		$return_string  = '<div class="mt-callout-wrapper' . $hide_class . '" style="background-color:' . esc_attr( $background_color ) . '">';
		$return_string .= '<div class="mt-callout mt-' . esc_attr( $atts['visibility'] ) . '">';

		if ( '' !== $atts['button_text'] ) {

			$return_string .= '<div class="mt-callout-caption pull-left">';
			$return_string .= '<p>' . do_shortcode(
				wp_kses_post(
					$content, array(
						'u' => array(),
						'i' => array(
							'class' => array(),
						),
						'b' => array(),
						'p' => array(
							'class' => array(),
						),
					)
				)
			) . '</p>';
			$return_string .= '</div>';
			$return_string .= '<div class="mt-callout-button pull-right text-right">';
			$return_string .= '<a href="' . esc_url( $atts['button_url'] ) . '" title="' . esc_attr( $atts['button_text'] ) . '" target="_' . esc_attr( $atts['button_target'] ) . '" class="btn btn-ghost-white' . esc_attr( $atts['class'] ) . '" ' . $style . '><span class="mt-button-inner">';
			if ( '' !== $icon_left && 'none' !== $icon_left ) {
				$return_string .= '<i class="mt-callout-icon-left fa fa-' . $icon_left . '"></i>';
			}

			$return_string .= esc_html( $atts['button_text'] );
			if ( '' !== $icon_right && 'none' !== $icon_right ) {
				$return_string .= '<i class="mt-callout-icon-right fa fa-' . $icon_right . '"></i>';
			}

			$return_string .= '</span></a>';
			$return_string .= '</div>';
		}

		$return_string .= '</div><!--/.mt-callout.mt-all-->';
		$return_string .= '</div><!--/.mt-callout-wrapper-->';
		return $return_string;
	}
} // End if().


// Pie Charts
if ( ! function_exists( 'macho_pie_charts' ) ) {

	function macho_pie_charts( $atts, $content = null ) {

		$return_string = '';
		$atts          = shortcode_atts(
			array(
				'percent'     => '100',
				'track_color' => '#EEE',
				'bar_color'   => '#DDD',
				'line_width'  => '10',
				'icon'        => '',
				'hide_xs'     => 'false',
				'hide_sm'     => 'false',
				'hide_md'     => 'false',
				'hide_lg'     => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$return_string = '<div class="mt-chart' . $hide_class . '" data-trackColor="' . esc_attr( $atts['track_color'] ) . '" data-barColor="' . esc_attr( $atts['bar_color'] ) . '" data-lineWidth="' . esc_attr( $atts['line_width'] ) . '" data-percent="' . esc_attr( $atts['percent'] ) . '">';

		if ( null !== $content && '' == $atts['icon'] ) {

			$return_string .= '<div class="mt-pie-chart-custom-text">';
			$return_string .= esc_html( $content );
			$return_string .= '</div>';

		} elseif ( '' !== $atts['icon'] ) {

			$return_string .= '<div class="mt-pie-chart-custom-icon">';
			$return_string .= '<i class="' . $atts['icon'] . '"></i>';
			$return_string .= '</div>';
		}

		$return_string .= '</div><!--/.mt-chart-->';
		return $return_string;

	}
} // End if().

// Team Members
if ( ! function_exists( 'macho_team_member' ) ) {
	function macho_team_member( $atts ) {

		$atts = shortcode_atts(
			array(
				'name'         => '',
				'description'  => '',
				'image_id'     => '',
				'linkedin_url' => '',
				'facebook_url' => '',
				'twitter_url'  => '',
				'dribbble_url' => '',
				'hide_xs'      => 'false',
				'hide_sm'      => 'false',
				'hide_md'      => 'false',
				'hide_lg'      => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		if ( '' !== $atts['image_id'] ) {
			$image_url = wp_get_attachment_image_src( $atts['image_id'], 'team-member' );
			// we only need the image URL
			$image_url = esc_url( $image_url[0] );
		} else {
			$image_url = '';
		}

		$return_string  = '<div class="mt-team' . $hide_class . '">';
		$return_string .= '<img class="mt-team-img lazy" data-original="' . esc_url( $image_url ) . '">';
		$return_string .= '<div class="mt-team-member-name">';
		$return_string .= $atts['name'];
		$return_string .= '<div class="mt-team-member-description">';
		$return_string .= $atts['description'];
		$return_string .= '</div>';

		if ( '' !== $atts['linkedin_url'] || '' !== $atts['facebook_url'] || '' !== $atts['twitter_url'] || '' !== $atts['dribbble_url'] ) {

			$return_string .= '<div class="mt-team-description">';

			$return_string .= '<div class="mt-team-member-icons">';

			if ( '' !== $atts['linkedin_url'] ) {
				$return_string .= '<div class="mt-team-member-icon">';
				$return_string .= '<a rel="nofollow" href="' . esc_url( $atts['linkedin_url'] ) . '"><i class="fa fa-linkedin"></i></a>';
				$return_string .= '</div>';
			}

			if ( '' !== $atts['facebook_url'] ) {
				$return_string .= '<div class="mt-team-member-icon">';
				$return_string .= '<a rel="nofollow" href="' . esc_attr( $atts['facebook_url'] ) . '"><i class="fa fa-facebook-official"></i></a>';
				$return_string .= '</div>';
			}

			if ( '' !== $atts['twitter_url'] ) {
				$return_string .= '<div class="mt-team-member-icon">';
				$return_string .= '<a rel="nofollow" href="' . esc_attr( $atts['twitter_url'] ) . '"><i class="fa fa-twitter"></i></a>';
				$return_string .= '</div>';
			}

			if ( '' !== $atts['dribbble_url'] ) {
				$return_string .= '<div class="mt-team-member-icon">';
				$return_string .= '<a rel="nofollow" href="' . esc_attr( $atts['dribbble_url'] ) . '"><i class="fa fa-dribbble"></i></a>';
				$return_string .= '</div>';
			}

			$return_string .= '</div>!<--/.mt-team-member-icons-->';
			$return_string .= '</div><!--/.mt-team-description-->';
		}

		$return_string .= '</div>';

		$return_string .= '</div>';

		return $return_string;

	}
} // End if().


if ( ! function_exists( 'mt_highlight_shortcode' ) ) {
	function mt_highlight_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'color'      => 'yellow',
				'class'      => '',
				'visibility' => 'all',
				'hide_xs'    => 'false',
				'hide_sm'    => 'false',
				'hide_md'    => 'false',
				'hide_lg'    => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		return '<span class="mt-highlight' . $hide_class . ' mt-highlight-' . esc_attr( $atts['color'] ) . ' ' . esc_attr( $atts['class'] ) . ' mt-' . esc_attr( $atts['visibility'] ) . '">' . do_shortcode( $content ) . '</span>';

	}
} // End if().

if ( ! function_exists( 'mt_skillbar_shortcode' ) ) {
	function mt_skillbar_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'        => '',
				'percentage'   => '100',
				'color'        => '#6adcfa',
				'class'        => '',
				'show_percent' => 'true',
				'visibility'   => 'all',
				'hide_xs'      => 'false',
				'hide_sm'      => 'false',
				'hide_md'      => 'false',
				'hide_lg'      => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		// Display the accordion    ';
		$output = '<div class="mt-skillbar' . $hide_class . $atts['class'] . ' mt-' . $atts['visibility'] . '" data-percent="' . intval( esc_attr( $atts['percentage'] ) ) . '%">';
		if ( '' !== $atts['title'] ) {
			$output .= '<div class="mt-skillbar-title" style="background: ' . esc_attr( $atts['color'] ) . ';"><span>' . esc_html( $atts['title'] ) . '</span></div>';
		}
		$output .= '<div class="mt-skillbar-bar" style="background: ' . esc_attr( $atts['color'] ) . ';"></div>';
		if ( 'true' == $atts['show_percent'] ) {
			$output .= '<div class="mt-skill-bar-percent">' . esc_attr( $atts['percentage'] ) . '%</div>';
		}
		$output .= '</div>';

		return $output;
	}
} // End if().

if ( ! function_exists( 'macho_code_function' ) ) {
	function macho_code_function( $content = null ) {
		$output = '<code>' . $content . '</code>';
		return $output;
	}
}

if ( ! function_exists( 'macho_blockquote_function' ) ) {
	function macho_blockquote_function( $atts, $content = null ) {
		$output = '<blockquote><p>' . $content . '</p></blockquote>';
		return $output;
	}
}

if ( ! function_exists( 'macho_rounded_image' ) ) {
	function macho_rounded_image( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'margin_top'    => '0',
				'margin_bottom' => '0',
				'margin_left'   => '0',
				'margin_right'  => '0',
				'border_radius' => '50%',
				'align'         => 'center',
				'image_id'      => '',
				'hide_xs'       => 'false',
				'hide_sm'       => 'false',
				'hide_md'       => 'false',
				'hide_lg'       => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$margins = esc_attr( $atts['margin_top'] ) . ' ' . esc_attr( $atts['margin_right'] ) . ' ' . esc_attr( $atts['margin_bottom'] ) . ' ' . esc_attr( $atts['margin_left'] );

		$output    = '<div class="mt-rounded-image' . $hide_class . '">';
		$image_url = wp_get_attachment_image_src( $atts['image_id'] );

		$output .= '<img class="lazy align' . esc_attr( $atts['align'] ) . '" style="margin:' . $margins . ';border-radius:' . esc_attr( $atts['border_radius'] ) . '" data-original="' . esc_url( $image_url[0] ) . '">';
		$output .= '</div>';

		return $output;

	}
} // End if().

if ( ! function_exists( 'macho_list_icon_wrapper' ) ) {
	function macho_list_icon_wrapper( $atts, $content = null ) {
		$output  = '<ul class="list-with-icons">';
		$output .= do_shortcode( $content );
		$output .= '</ul>';
		return $output;
	}
}

if ( ! function_exists( 'macho_list_icon_item' ) ) {
	function macho_list_icon_item( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'icon_class' => false,
				'hide_xs'    => 'false',
				'hide_sm'    => 'false',
				'hide_md'    => 'false',
				'hide_lg'    => 'false',
			), $atts
		);

		/* Hide settings */
		$hide_class = '';
		if ( 'false' != $atts['hide_xs'] ) {
			$hide_class .= ' hidden-xs';
		}
		if ( 'false' != $atts['hide_sm'] ) {
			$hide_class .= ' hidden-sm';
		}
		if ( 'false' != $atts['hide_md'] ) {
			$hide_class .= ' hidden-md';
		}
		if ( 'false' != $atts['hide_lg'] ) {
			$hide_class .= ' hidden-lg';
		}

		$output = '<li class="' . $hide_class . '">';
		if ( $atts['icon_class'] ) {
			$output .= '<i class="' . esc_attr( $atts['icon_class'] ) . '"></i>';
		}
		$output .= do_shortcode( $content );
		$output .= '</li>';

		return $output;
	}
} // End if().



/*--------------------------------------------------------------------------------------
	*
	* Parse data-attributes for shortcodes
	*
	* @author   Macho Themes
	* @since    1.0
	*
*-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'parse_data_attributes' ) ) {
	function parse_data_attributes( $data ) {

		$data_props = '';

		if ( $data ) {
			$data = explode( '|', $data );

			foreach ( $data as $d ) {
				$d           = explode( ',', $d );
				$data_props .= sprintf( 'data-%s="%s" ', esc_html( $d[0] ), esc_attr( trim( $d[1] ) ) );
			}
		} else {
			$data_props = false;
		}
		return $data_props;
	}
}
/*--------------------------------------------------------------------------------------
  *
  * get DOMDocument element and apply shortcode parameters to it. Create the element if it doesn't exist
  *
  * @author     Macho Themes
  * @since      1.0
  *
*-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'get_dom_element' ) ) {
	function get_dom_element( $tag, $content, $class, $title = '', $data = null ) {

		//clean up content
		$content        = trim( trim( $content ), chr( 0xC2 ) . chr( 0xA0 ) );
		$previous_value = libxml_use_internal_errors( true );

		$dom = new DOMDocument;
		$dom->loadXML( $content );

		libxml_clear_errors();
		libxml_use_internal_errors( $previous_value );

		if ( ! $dom->documentElement ) {
			$element = $dom->createElement( $tag, $content );
			$dom->appendChild( $element );
		}

		$dom->documentElement->setAttribute( 'class', $dom->documentElement->getAttribute( 'class' ) . ' ' . esc_attr( $class ) );
		if ( $title ) {
			$dom->documentElement->setAttribute( 'title', $title );
		}
		if ( $data ) {
			$data = explode( '|', $data );
			foreach ( $data as $d ) :
				$d = explode( ',', $d );
				$dom->documentElement->setAttribute( 'data-' . $d[0], trim( $d[1] ) );
			endforeach;
		}
		return $dom->saveXML( $dom->documentElement );
	}
} // End if().

/*--------------------------------------------------------------------------------------
	*
	* Scrape the shortcode's contents for a particular DOMDocument tag or tags, pull them out, apply attributes, and return just the tags.
	*
	*-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'scrape_dom_element' ) ) {
	function scrape_dom_element( $tag, $content, $class, $title = '', $data = null ) {

		$previous_value = libxml_use_internal_errors( true );

		$dom = new DOMDocument;
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

		libxml_clear_errors();
		libxml_use_internal_errors( $previous_value );
		foreach ( $tag as $find ) {
			$tags = $dom->getElementsByTagName( $find );
			foreach ( $tags as $find_tag ) {
				$return_stringdom = new DOMDocument;
				$new_root         = $return_stringdom->importNode( $find_tag, true );
				$return_stringdom->appendChild( $new_root );

				if ( is_object( $return_stringdom->documentElement ) ) {
					$return_stringdom->documentElement->setAttribute( 'class', $return_stringdom->documentElement->getAttribute( 'class' ) . ' ' . esc_attr( $class ) );
					if ( $title ) {
						$return_stringdom->documentElement->setAttribute( 'title', $title );
					}
					if ( $data ) {
						$data = explode( '|', $data );
						foreach ( $data as $d ) :
							$d = explode( ',', $d );
							$return_stringdom->documentElement->setAttribute( 'data-' . $d[0], trim( $d[1] ) );
						endforeach;
					}
				}
				return $return_stringdom->saveHTML( $return_stringdom->documentElement );

			}
		}
	}
} // End if().

/*--------------------------------------------------------------------------------------
  *
  * We need to be able to figure out the attributes of a wrapped shortcode
  *
  * @author     Macho Themes
  * @since      1.0
  *
*-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'macho_attribute_map' ) ) {
	function macho_attribute_map( $str, $att = null ) {
		$res    = array();
		$return = array();
		$reg    = get_shortcode_regex();
		preg_match_all( '~' . $reg . '~', $str, $matches );
		foreach ( $matches[2] as $key => $name ) {
			$parsed = shortcode_parse_atts( $matches[3][ $key ] );
			$parsed = is_array( $parsed ) ? $parsed : array();

			$res[ $name ] = $parsed;
			$return[]     = $res;
		}
		return $return;
	}
}
/*--------------------------------------------------------------------------------------
   *
   * Add dividers to data attributes content if needed
   * @author    Macho Themes
   * @since     1.0
   *
*-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'check_for_data' ) ) {
	function check_for_data( $data ) {
		if ( $data ) {
			return '|';
		}
	}
}
/*--------------------------------------------------------------------------------------
   *
   * If the user puts a return between the shortcode and its contents, sometimes we want to strip the resulting P tags out
   * @author    Macho Themes
   * @since     1.0
   *
*-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'strip_paragraph' ) ) {
	function strip_paragraph( $content ) {
		$content = str_ireplace( '<p>', '', $content );
		$content = str_ireplace( '</p>', '', $content );
		return $content;
	}
}
/*--------------------------------------------------------------------------------------
   *
   * Intelligently remove extra P and BR tags around shortcodes that WordPress likes to add
   * @author    Macho Themes
   * @since     1.0
   *
*-------------------------------------------------------------------------------------*/
if ( ! function_exists( 'macho_fix_shortcodes' ) ) {
	function macho_fix_shortcodes( $content ) {
		$array = array(
			'<p>['    => '[',
			']</p>'   => ']',
			']<br />' => ']',
			']<br>'   => ']',
		);

		$content = strtr( $content, $array );
		return $content;
	}
}
add_filter( 'the_content', 'macho_fix_shortcodes' );
