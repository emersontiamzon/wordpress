<?php

//Print HTML for an icon
//TODO: Icon value should be in the format 'fontawesome-\f001'
if ( ! function_exists( 'antreas_icon' ) ) {
	function antreas_icon( $value, $wrapper = '', $echo = true ) {
		if ( $value === '0' || $value === 0 || $value === '' ) {
			return;
		}
		$icon_packs = antreas_metadata_icons();

		if( strpos( $value, '-') === false ) {

            $icon_data = antreas_check_fontawesome_compatibility( html_entity_decode($value) );

            if ( ! is_array( $icon_data ) ) {
                $font_library = '';
                if( isset( $icon_packs['fontawesomeregular']['icons'][ html_entity_decode($value) ] ) ){
                    $font_library = 'fontawesomeregular';
                }else if( isset($icon_packs['fontawesomebrands']['icons'][html_entity_decode($value)]) ){
                    $font_library = 'fontawesomebrands';
                }else if( isset($icon_packs['fontawesomesolid']['icons'][html_entity_decode($value)]) ){
                    $font_library = 'fontawesomesolid';
                }

                $icon_data = array( $font_library, $value );
            }
		}
		else{

            $icon_data = explode( '-', $value );

            if( $icon_data[0] == 'fontawesome' ){

                $icon_data = antreas_check_fontawesome_compatibility( html_entity_decode($value) );

                if ( ! is_array( $icon_data ) ) {
                    // Fix for older versions of FontAwesome and import problem
                    $old_icon = explode( '-', esc_html($icon_data) );
                    $new_icon = antreas_check_fontawesome_compatibility( html_entity_decode($old_icon[1]) );

                    if(!is_array($new_icon)) {

                        $font_library = '';
                        if ( isset( $icon_packs['fontawesomeregular']['icons'][ html_entity_decode( $old_icon[1] ) ] ) ) {
                            $font_library = 'fontawesomeregular';
                        } else if ( isset( $icon_packs['fontawesomebrands']['icons'][ html_entity_decode( $old_icon[1] ) ] ) ) {
                            $font_library = 'fontawesomebrands';
                        } else if ( isset( $icon_packs['fontawesomesolid']['icons'][ html_entity_decode( $old_icon[1] ) ] ) ) {
                            $font_library = 'fontawesomesolid';
                        }

                        $icon_data = array( $font_library, $old_icon[1] );

                    } else {
                        $icon_data = array( $new_icon[0], $new_icon[1] );
                    }
                }
            }
		}

        $icon_data[1] = html_entity_decode( $icon_data[1]);
        $font_library = $icon_data[0];
        $font_value   = $icon_data[1];

		$output = '';
		if ( $wrapper != '' ) {
			$output .= '<div class="' . $wrapper . '">';
		}
		$output .= antreas_get_icon( $font_library, html_entity_decode( $font_value ) );
		if ( $wrapper != '' ) {
			$output .= '</div>';
		}

		if ( $echo == false ) {
			return $output;
		} else {
			echo $output;
		}
    }
}


//Retrieve the correct library
function antreas_get_icon( $library, $value ) {
	$result = '';
	switch ( $library ) {
        case 'fontawesomesolid':
            $result = antreas_icon_library_fontawesome_solid( $value );
            break;
        case 'fontawesomebrands' :
            $result = antreas_icon_library_fontawesome_brands( $value );
            break;
        case 'fontawesomeregular' :
            $result = antreas_icon_library_fontawesome_regular( $value );
            break;
		case 'linearicons':
			$result = antreas_icon_library_linearicons( $value );
			break;
		case 'typicons':
			$result = antreas_icon_library_typicons( $value );
			break;
		default:
			$result = antreas_icon_library_fontawesome_solid( $value );
			break;
	}
	return $result;
}


//Icon library for fontawesomeweeqweqweqweqwe
function antreas_icon_library_fontawesome_regular( $value ) {
    return '<span style="font-family:\'Font Awesome 5 Regular\'">' . $value . '</span>';
}

function antreas_icon_library_fontawesome_brands( $value ) {
    return '<span style="font-family:\'Font Awesome 5 Brands\' ; font-weight: 900">' . $value . '</span>';
}

function antreas_icon_library_fontawesome_solid( $value ) {
    return '<span style="font-family:\'Font Awesome 5 Solid\'; font-weight: 900">' . $value . '</span>';
}

//Icon library for linearicons
function antreas_icon_library_linearicons( $value ) {
	wp_enqueue_style( 'antreas-linearicons' );
	return '<span style="font-family:\'linearicons\'">' . $value . '</span>';
}

//Icon library for typicons
function antreas_icon_library_typicons( $value ) {
	wp_enqueue_style( 'antreas-typicons' );
	return '<span style="font-family:\'typicons\'">' . $value . '</span>';
}
