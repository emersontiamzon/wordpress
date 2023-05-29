<?php
class Macho_Shortcodes {

	var $conf;
	var $popup;
	var $params;
	var $shortcode;
	var $cparams;
	var $cshortcode;
	var $popup_title;
	var $no_preview;
	var $has_child;
	var $output;
	var $errors;

	// --------------------------------------------------------------------------

	function __construct( $popup ) {
		if ( file_exists( dirname( __FILE__ ) . '/config.php' ) ) {
			$this->conf  = dirname( __FILE__ ) . '/config.php';
			$this->popup = $popup;

			$this->formate_shortcode();
		} else {
			$this->append_error( 'Config file does not exist' );
		}
	}

	// --------------------------------------------------------------------------

	function formate_shortcode() {
		global $msm_shortcodes_config;

		// get config file
		require_once( $this->conf );

		unset( $msm_shortcodes_config['shortcode-generator']['params']['select_shortcode'] );
		if ( isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode'] ) ) {
			$this->has_child = true;
		}

		if ( isset( $msm_shortcodes_config ) && is_array( $msm_shortcodes_config ) ) {
			// get shortcode config stuff
			$this->params      = $msm_shortcodes_config[ $this->popup ]['params'];
			$this->popup_title = $msm_shortcodes_config[ $this->popup ]['title'] . ' Shortcode';

			// adds stuff for js use
			$shortcode_sintax = '[' . $this->popup . '%s]%s[/' . $this->popup . ']';

			if ( isset( $msm_shortcodes_config[ $this->popup ]['no_preview'] ) && $msm_shortcodes_config[ $this->popup ]['no_preview'] ) {
				//$this->append_output( "\n" . '<div id="_macho_preview" class="hidden">false</div>' );
				$this->no_preview = true;
			}

			$shortcode_params  = '';
			$shortcode_content = '';

			if ( isset( $msm_shortcodes_config[ $this->popup ]['content'] ) ) {
				$shortcode_content = '{{content}}';
				$pkey              = 'macho_content';
				$this->content     = array();

				if ( ! isset( $msm_shortcodes_config[ $this->popup ]['content']['label'] ) ) {
					$this->content['label'] = '';
				} else {
					$this->content['label'] = $msm_shortcodes_config[ $this->popup ]['content']['label'];
				}
				if ( ! isset( $msm_shortcodes_config[ $this->popup ]['content']['desc'] ) ) {
					$this->content['desc'] = '';
				} else {
					$this->content['desc'] = $msm_shortcodes_config[ $this->popup ]['content']['desc'];
				}
				if ( ! isset( $msm_shortcodes_config[ $this->popup ]['content']['std'] ) ) {
					$this->content['std'] = '';
				} else {
					$this->content['std'] = $msm_shortcodes_config[ $this->popup ]['content']['std'];
				}
				if ( isset( $msm_shortcodes_config[ $this->popup ]['content']['details'] ) ) {
					$this->content['details'] = $msm_shortcodes_config[ $this->popup ]['content']['details'];
				}

				$row_start  = '<tbody>' . "\n";
				$row_start .= '<tr class="form-row" class="' . $pkey . '">' . "\n";
				$row_start .= '<td class="label">';
				$row_start .= '<span class="macho-form-label-title">' . $this->content['label'] . '</span>' . "\n";

				if ( isset( $this->content['details'] ) ) {
					$row_start .= '<i class="fa fa-question-circle tooltip-icon" data-toggle="tooltip" data-placement="right" title="' . $this->content['details'] . '"></i>';
				}

				$row_start .= '<span class="macho-form-desc">' . $this->content['desc'] . '</span>' . "\n";
				$row_start .= '</td>' . "\n";
				$row_start .= '<td class="field">' . "\n";

				$row_end  = '</td>' . "\n";
				$row_end .= '</tr>' . "\n";
				$row_end .= '</tbody>' . "\n";

				// prepare
				$output = $row_start;

				//$output .= $editor_contents;
				$output .= '<textarea rows="10" cols="30" name="' . $pkey . '" id="' . $pkey . '" class="macho-form-textarea macho-input">' . $this->content['std'] . '</textarea>' . "\n";
				$output .= $row_end;

				// append
				$this->append_output( $output );

			}// End if().

			// filters and excutes params
			foreach ( $this->params as $pkey => $param ) {
				$shortcode_params .= ' ' . $pkey . '="{{' . $pkey . '}}"';
				// prefix the fields names and ids with macho_
				$pkey = 'macho_' . $pkey;

				if ( ! isset( $param['std'] ) ) {
					$param['std'] = '';
				}

				if ( ! isset( $param['desc'] ) ) {
					$param['desc'] = '';
				}

				if ( ! isset( $param['required'] ) ) {
					$param['required'] = false;
				}

				// popup form row start
				$row_start = '<tbody>' . "\n";
				if ( $param['required'] ) {
					$row_start .= '<tr class="form-row required-field" class="' . $pkey . '">' . "\n";
				} else {
					$row_start .= '<tr class="form-row" class="' . $pkey . '">' . "\n";
				}
				if ( 'info' != $param['type'] ) {
					$row_start .= '<td class="label">';
					$row_start .= '<span class="macho-form-label-title">' . $param['label'] . '</span>' . "\n";

					if ( isset( $param['details'] ) ) {
						$row_start .= '<i class="fa fa-question-circle tooltip-icon" data-toggle="tooltip" data-placement="right" title="' . $param['details'] . '"></i>';
					}

					$row_start .= '<span class="macho-form-desc">' . $param['desc'] . '</span>' . "\n";
					$row_start .= '</td>' . "\n";
				}
				$row_start .= '<td class="field">' . "\n";

				// popup form row end
				$row_end  = '</td>' . "\n";
				$row_end .= '</tr>' . "\n";
				$row_end .= '</tbody>' . "\n";

				switch ( $param['type'] ) {
					case 'text':
						// prepare
						$output  = $row_start;
						$output .= '<input type="text" class="macho-form-text macho-input" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'textarea':
						// prepare
						$output = $row_start;

						// Turn on the output buffer
						ob_start();

						// Echo the editor to the buffer
						wp_editor(
							$param['std'], $pkey, array(
								'editor_class'  => 'macho_tinymce',
								'media_buttons' => true,
							)
						);

						// Store the contents of the buffer in a variable
						$editor_contents = ob_get_clean();

						//$output .= $editor_contents;
						$output .= '<textarea rows="10" cols="30" name="' . $pkey . '" id="' . $pkey . '" class="macho-form-textarea macho-input">' . $param['std'] . '</textarea>' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'select':
						// prepare
						$output  = $row_start;
						$output .= '<div class="macho-form-select-field">';
						$output .= '<div class="macho-shortcodes-arrow">&#xf107;</div>';
						$output .= '<select name="' . $pkey . '" id="' . $pkey . '" class="macho-form-select macho-input">' . "\n";
						$output .= '</div>';

						if ( is_array( $param['options'] ) ) {
							foreach ( $param['options'] as $value => $option ) {
								$selected = ( isset( $param['std'] ) && $param['std'] == $value ) ? 'selected="selected"' : '';
								$output  .= '<option value="' . $value . '"' . $selected . '>' . $option . '</option>' . "\n";
							}
						}

						$output .= '</select>' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'multiple_select':
						// prepare
						$output  = $row_start;
						$output .= '<select name="' . $pkey . '" id="' . $pkey . '" multiple="multiple" class="macho-form-multiple-select macho-input">' . "\n";

						if ( $param['options'] && is_array( $param['options'] ) ) {
							foreach ( $param['options'] as $value => $option ) {
								$output .= '<option value="' . $value . '">' . $option . '</option>' . "\n";
							}
						}

						$output .= '</select>' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'checkbox':
						$checkbox_text = '';
						if ( isset( $param['checkbox_text'] ) ) {
							$checkbox_text = $param['checkbox_text'];
						}

						// prepare
						$output  = $row_start;
						$output .= '<label for="' . $pkey . '" class="macho-form-checkbox">' . "\n";
						$output .= '<input type="checkbox" class="macho-input" name="' . $pkey . '" id="' . $pkey . '" ' . ( 'true' == $param['std'] ? 'checked' : '' ) . ' value="true" />' . "\n";
						$output .= ' ' . $checkbox_text . '</label>' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'uploader':
						// prepare
						$output  = $row_start;
						$output .= '<div class="macho-upload-container">';
						$output .= '<img src="" alt="Image" class="uploaded-image" />';
						$output .= '<input type="hidden" class="macho-form-text macho-form-upload macho-input" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />' . "\n";
						$output .= '<a href="' . $pkey . '" class="macho-upload-button" data-upid="1">Upload</a>';
						$output .= '</div>';
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'gallery':
						if ( ! isset( $cpkey ) ) {
							$cpkey = '';
						}

						// prepare
						$output  = $row_start;
						$output .= '<a href="' . $cpkey . '" class="macho-gallery-button macho-shortcodes-button">Attach Images to Gallery</a>';
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'iconpicker':
						// prepare
						$output = $row_start;

						$output .= '<div class="iconpicker">';
						foreach ( $param['options'] as $value => $option ) {
							$output .= '<i class="' . $value . '" data-name="' . $value . '"></i>';
						}
						$output .= '</div>';

						if ( ! isset( $param['std'] ) ) {
							$param['std'] = '';
						}

						$output .= '<input type="hidden" class="macho-form-text macho-input" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'colorpicker':
						if ( ! isset( $param['std'] ) ) {
							$param['std'] = '';
						}

						// prepare
						$output  = $row_start;
						$output .= '<input type="text" class="macho-form-text macho-input wp-color-picker-field" name="' . $pkey . '" id="' . $pkey . '" value="' . $param['std'] . '" />' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'info':
						// prepare
						$output  = $row_start;
						$output .= '<p>' . $param['std'] . "</p>\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;

					case 'size':
						// prepare
						$output  = $row_start;
						$output .= '<div class="macho-form-group">' . "\n";
						$output .= '<label>Width</label>' . "\n";
						$output .= '<input type="text" class="macho-form-text macho-input" name="' . $pkey . '_width" id="' . $pkey . '_width" value="' . $param['std'] . '" />' . "\n";
						$output .= '</div>' . "\n";
						$output .= '<div class="macho-form-group last">' . "\n";
						$output .= '<label>Height</label>' . "\n";
						$output .= '<input type="text" class="macho-form-text macho-input" name="' . $pkey . '_height" id="' . $pkey . '_height" value="' . $param['std'] . '" />' . "\n";
						$output .= '</div>' . "\n";
						$output .= $row_end;

						// append
						$this->append_output( $output );

						break;
				}// End switch().
			}// End foreach().
			if ( '' != $shortcode_params ) {
				if ( '' == $shortcode_content && isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode'] ) ) {
					$shortcode_content = '{{child_shortcode}}';
				}
				$shortcode_sintax = sprintf( $shortcode_sintax, $shortcode_params, $shortcode_content );
			} elseif ( '' != $shortcode_content ) {
				$shortcode_sintax = sprintf( $shortcode_sintax, $shortcode_params, $shortcode_content );
			} elseif ( isset( $msm_shortcodes_config[ $this->popup ]['with_parent_tag'] ) ) {
				$shortcode_sintax = sprintf( $shortcode_sintax, $shortcode_params, '{{child_shortcode}}' );
				;
			} else {
				$shortcode_sintax = '{{child_shortcode}}';
			}

			$this->append_output( "\n" . '<div id="_macho_shortcode" class="hidden">' . $shortcode_sintax . '</div>' );
			$this->append_output( "\n" . '<div id="_macho_popup" class="hidden">' . $this->popup . '</div>' );

			// checks if has a child shortcode
			if ( isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode'] ) ) {
				// set child shortcode
				$this->cparams = $msm_shortcodes_config[ $this->popup ]['child_shortcode']['params'];
				//$this->cshortcode = $msm_shortcodes_config[$this->popup]['child_shortcode']['shortcode'];

				$cshortcode_params  = '';
				$cshortcode_content = '';

				// popup parent form row start
				$prow_start  = '<tbody>' . "\n";
				$prow_start .= '<tr class="form-row has-child">' . "\n";
				$prow_start .= '<td>' . "\n";
				$prow_start .= '<div class="child-clone-rows">' . "\n";

				// for js use

				// start the default row
				$prow_start .= '<div class="child-clone-row">' . "\n";
				$prow_start .= '<ul class="child-clone-row-form">' . "\n";

				// add $prow_start to output
				$this->append_output( $prow_start );

				// Check if child shortcode have content

				if ( isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content'] ) ) {
					$cshortcode_content = '{{content}}';
					$cpkey              = 'macho_content';
					$this->ccontent     = array();

					if ( ! isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['label'] ) ) {
						$this->ccontent['label'] = '';
					} else {
						$this->ccontent['label'] = $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['label'];
					}
					if ( ! isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['desc'] ) ) {
						$this->ccontent['desc'] = '';
					} else {
						$this->ccontent['desc'] = $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['desc'];
					}
					if ( ! isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['std'] ) ) {
						$this->ccontent['std'] = '';
					} else {
						$this->ccontent['std'] = $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['std'];
					}

					if ( isset( $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['details'] ) ) {
						$this->ccontent['details'] = $msm_shortcodes_config[ $this->popup ]['child_shortcode']['content']['details'];
					}

					$crow_start  = '<li class="child-clone-row-form-row clearfix">' . "\n";
					$crow_start .= '<div class="child-clone-row-label-desc">' . "\n";
					$crow_start .= '<div class="child-clone-row-label">' . "\n";
					$crow_start .= '<label>' . $this->ccontent['label'] . '</label>' . "\n";
					if ( isset( $this->ccontent['details'] ) ) {
						$crow_start .= '<i class="fa fa-question-circle tooltip-icon" data-toggle="tooltip" data-placement="right" title="' . $this->ccontent['details'] . '"></i>';
					}
					$crow_start .= '</div>' . "\n";
					$crow_start .= '<span class="child-clone-row-desc">' . $this->ccontent['desc'] . '</span>' . "\n";
					$crow_start .= '</div>' . "\n";
					$crow_start .= '<div class="child-clone-row-field">' . "\n";

					$crow_end  = '</div>' . "\n";
					$crow_end .= '</li>' . "\n";

					$coutput  = $crow_start;
					$coutput .= '<textarea rows="10" cols="30" name="' . $cpkey . '" id="' . $cpkey . '" class="macho-form-textarea macho-cinput">' . $this->ccontent['std'] . '</textarea>' . "\n";
					$coutput .= $crow_end;

					// append
					$this->append_output( $coutput );

				}// End if().

				foreach ( $this->cparams as $cpkey => $cparam ) {

					$cshortcode_params .= ' ' . $cpkey . '="{{' . $cpkey . '}}"';
					// prefix the fields names and ids with macho_
					$cpkey = 'macho_' . $cpkey;

					if ( ! isset( $cparam['std'] ) ) {
						$cparam['std'] = '';
					}

					if ( ! isset( $cparam['desc'] ) ) {
						$cparam['desc'] = '';
					}

					if ( ! isset( $cparam['required'] ) ) {
						$cparam['required'] = false;
					}

					// popup form row start
					if ( $cparam['required'] ) {
						$crow_start = '<li class="child-clone-row-form-row clearfix required-field">' . "\n";
					} else {
						$crow_start = '<li class="child-clone-row-form-row clearfix">' . "\n";
					}
					$crow_start .= '<div class="child-clone-row-label-desc">' . "\n";
					$crow_start .= '<div class="child-clone-row-label">' . "\n";
					$crow_start .= '<label>' . $cparam['label'] . '</label>' . "\n";

					if ( isset( $cparam['details'] ) ) {
						$crow_start .= '<i class="fa fa-question-circle tooltip-icon" data-toggle="tooltip" data-placement="right" title="' . $cparam['details'] . '"></i>';
					}

					$crow_start .= '</div>' . "\n";
					$crow_start .= '<span class="child-clone-row-desc">' . $cparam['desc'] . '</span>' . "\n";
					$crow_start .= '</div>' . "\n";
					$crow_start .= '<div class="child-clone-row-field">' . "\n";

					// popup form row end
					$crow_end  = '</div>' . "\n";
					$crow_end .= '</li>' . "\n";

					switch ( $cparam['type'] ) {
						case 'text':
							// prepare
							$coutput  = $crow_start;
							$coutput .= '<input type="text" class="macho-form-text macho-cinput" name="' . $cpkey . '" id="' . $cpkey . '" value="' . $cparam['std'] . '" />' . "\n";
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;

						case 'textarea':
							// prepare
							$coutput  = $crow_start;
							$coutput .= '<textarea rows="10" cols="30" name="' . $cpkey . '" id="' . $cpkey . '" class="macho-form-textarea macho-cinput">' . $cparam['std'] . '</textarea>' . "\n";
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;

						case 'select':
							// prepare
							$coutput  = $crow_start;
							$coutput .= '<div class="macho-form-select-field">';
							$coutput .= '<div class="macho-shortcodes-arrow">&#xf107;</div>';
							$coutput .= '<select name="' . $cpkey . '" id="' . $cpkey . '" class="macho-form-select macho-cinput">' . "\n";
							$coutput .= '</div>';

							foreach ( $cparam['options'] as $value => $option ) {
								$coutput .= '<option value="' . $value . '">' . $option . '</option>' . "\n";
							}

							$coutput .= '</select>' . "\n";
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;

						case 'checkbox':
							$ccheckbox_text = '';
							if ( isset( $cparam['checkbox_text'] ) ) {
								$ccheckbox_text = $cparam['checkbox_text'];
							}

							// prepare
							$coutput  = $crow_start;
							$coutput .= '<label for="' . $cpkey . '" class="macho-form-checkbox">' . "\n";
							$coutput .= '<input type="checkbox" class="macho-cinput" name="' . $cpkey . '" id="' . $cpkey . '" ' . ( $cparam['std'] ? 'checked' : '' ) . ' value="true" />' . "\n";
							$coutput .= ' ' . $ccheckbox_text . '</label>' . "\n";
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;

						case 'uploader':
							if ( ! isset( $cparam['std'] ) ) {
								$cparam['std'] = '';
							}

							// prepare
							$coutput  = $crow_start;
							$coutput .= '<div class="macho-upload-container">';
							$coutput .= '<img src="" alt="Image" class="uploaded-image" />';
							$coutput .= '<input type="hidden" class="macho-form-text macho-form-upload macho-cinput" name="' . $cpkey . '" id="' . $cpkey . '" value="' . $cparam['std'] . '" />' . "\n";
							$coutput .= '<a href="' . $cpkey . '" class="macho-upload-button" data-upid="1">Upload</a>';
							$coutput .= '</div>';
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;

						case 'colorpicker':
							// prepare
							$coutput  = $crow_start;
							$coutput .= '<input type="text" class="macho-form-text macho-cinput wp-color-picker-field" name="' . $cpkey . '" id="' . $cpkey . '" value="' . $cparam['std'] . '" />' . "\n";
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;

						case 'iconpicker':
							// prepare
							$coutput = $crow_start;

							$coutput .= '<div class="iconpicker">';
							foreach ( $cparam['options'] as $value => $option ) {
								$coutput .= '<i class="' . $value . '" data-name="' . $value . '"></i>';
							}
							$coutput .= '</div>';

							$coutput .= '<input type="hidden" class="macho-form-text macho-cinput" name="' . $cpkey . '" id="' . $cpkey . '" value="' . $cparam['std'] . '" />' . "\n";
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;

						case 'size':
							// prepare
							$coutput  = $crow_start;
							$coutput .= '<div class="macho-form-group">' . "\n";
							$coutput .= '<label>Width</label>' . "\n";
							$coutput .= '<input type="text" class="macho-form-text macho-cinput" name="' . $cpkey . '_width" id="' . $cpkey . '_width" value="' . $cparam['std'] . '" />' . "\n";
							$coutput .= '</div>' . "\n";
							$coutput .= '<div class="macho-form-group last">' . "\n";
							$coutput .= '<label>Height</label>' . "\n";
							$coutput .= '<input type="text" class="macho-form-text macho-cinput" name="' . $cpkey . '_height" id="' . $cpkey . '_height" value="' . $cparam['std'] . '" />' . "\n";
							$coutput .= '</div>' . "\n";
							$coutput .= $crow_end;

							// append
							$this->append_output( $coutput );

							break;
					}// End switch().
				}// End foreach().

				$cshortcode_sintax = sprintf( '[' . $msm_shortcodes_config[ $this->popup ]['child_shortcode']['tag'] . '%s]%s[/' . $msm_shortcodes_config[ $this->popup ]['child_shortcode']['tag'] . ']', $cshortcode_params, $cshortcode_content );

				// popup parent form row end
				$prow_end  = '</ul>' . "\n";       // end .child-clone-row-form
				$prow_end .= '<a href="#" class="child-clone-row-remove macho-shortcodes-button">Remove</a>' . "\n";
				$prow_end .= '<span class="row-alert">You need a minimum of one row</span>';
				$prow_end .= '</div>' . "\n";     // end .child-clone-row

				$prow_end .= '</div>' . "\n";     // end .child-clone-rows

				$prow_end .= '<div id="_macho_cshortcode" class="hidden">' . $cshortcode_sintax . '</div>' . "\n";
				$prow_end .= '<a href="#" id="form-child-add">' . $msm_shortcodes_config[ $this->popup ]['child_shortcode']['clone_button'] . '</a>' . "\n";
				$prow_end .= '</td>' . "\n";
				$prow_end .= '</tr>' . "\n";
				$prow_end .= '</tbody>' . "\n";

				// add $prow_end to output
				$this->append_output( $prow_end );
			}// End if().
		}// End if().
	}

	// --------------------------------------------------------------------------

	function append_output( $output ) {
		$this->output = $this->output . "\n" . $output;
	}

	// --------------------------------------------------------------------------

	function reset_output( $output ) {
		$this->output = '';
	}

	// --------------------------------------------------------------------------

	function append_error( $error ) {
		$this->errors = $this->errors . "\n" . $error;
	}
}


