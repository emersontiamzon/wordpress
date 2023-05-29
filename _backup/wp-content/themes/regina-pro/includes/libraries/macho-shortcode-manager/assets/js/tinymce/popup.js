// start the popup specefic scripts
// safe to use $
var old_tb_remove = window.tb_remove;
var using_text_editor = false;
var text_editor_toggle;
var html_editor_toggle;
var editor_area;
var cursor_position = 0;

macho_popup = function ( a, params ) {
	var popup = 'shortcode-generator';

	if(typeof params != 'undefined' && params.identifier) {
		popup = params.identifier;
	}

	// load thickbox
	tb_show("Macho Shortcodes", ajaxurl + "?action=macho_sm_popup&popup=" + popup);



	jQuery('#TB_window').hide();
};

jQuery(document).ready(function($) {
	var tb_remove = function() {
		// check if text editor shortcode button was used; if so return to it
		if ( using_text_editor ) {
			using_text_editor = false;
			window.switchEditors.go( 'content' );
		}

		old_tb_remove();
	};

	window.macho_sm_tb_height = (92 / 100) * jQuery(window).height();
	window.macho_sm_macho_shortcodes_height = (71 / 100) * jQuery(window).height();
	if(window.macho_sm_macho_shortcodes_height > 550) {
		window.macho_sm_macho_shortcodes_height = (74 / 100) * jQuery(window).height();
	}

	jQuery(window).resize(function() {
		window.macho_sm_tb_height = (92 / 100) * jQuery(window).height();
		window.macho_sm_macho_shortcodes_height = (71 / 100) * jQuery(window).height();

		if(window.macho_sm_macho_shortcodes_height > 550) {
			window.macho_sm_macho_shortcodes_height = (74 / 100) * jQuery(window).height();
		}
	});

	machothemes_shortcodes = {
		loadVals: function()
		{
			var shortcode = $('#_macho_shortcode').text(),
				uShortcode = shortcode;

			// fill in the gaps eg {{param}}
			$('.macho-input').each(function() {
				var input = $(this),
					id = input.attr('id'),
					id = id.replace('macho_', ''),		// gets rid of the macho_ prefix
					re = new RegExp("{{"+id+"}}","g");
					var value = input.val();
					if(value == null) {
					  value = '';
					}
					if ( input.attr('type') == 'checkbox' ) {
						if ( !input.is(":checked") ) {
							value = false;
						}
					};
				uShortcode = uShortcode.replace(re, value);
			});

			// adds the filled-in shortcode as hidden input
			$('#_macho_ushortcode').remove();
			$('#macho-sc-form-table').prepend('<div id="_macho_ushortcode" class="hidden">' + uShortcode + '</div>');
			

		},
		cLoadVals: function()
		{
			var shortcode = $('#_macho_cshortcode').text(),
				pShortcode = '';

				if(shortcode.indexOf("<li>") < 0) {
					shortcodes = '<br />';
				} else {
					shortcodes = '';
				}

			// fill in the gaps eg {{param}}
			$('.macho-shortcodes-popup .child-clone-row').each(function() {
				var row = $(this),
					rShortcode = shortcode;

				if($(this).find('#macho_slider_type').length >= 1) {
					if($(this).find('#macho_slider_type').val() == 'image') {
						rShortcode = '[slide type="{{slider_type}}" link="{{image_url}}" linktarget="{{image_target}}" lightbox="{{image_lightbox}}"]{{image_content}}[/slide]';
					} else if($(this).find('#macho_slider_type').val() == 'video') {
						rShortcode = '[slide type="{{slider_type}}"]{{video_content}}[/slide]';
					}
				}
				$('.macho-cinput', this).each(function() {
					var input = $(this),
						id = input.attr('id'),
						id = id.replace('macho_', '')		// gets rid of the macho_ prefix
						re = new RegExp("{{"+id+"}}","g");
						var value = input.val();
						if(value == null) {
						  value = '';
						}
						if ( input.attr('type') == 'checkbox' ) {
							if ( !input.is(":checked") ) {
								value = false;
							}
						};
					rShortcode = rShortcode.replace(re, value);
				});

				if(shortcode.indexOf("<li>") < 0) {
					shortcodes = shortcodes + rShortcode + '<br />';
				} else {
					shortcodes = shortcodes + rShortcode;
				}
			});

			// adds the filled-in shortcode as hidden input
			$('#_macho_cshortcodes').remove();
			$('.macho-shortcodes-popup .child-clone-rows').prepend('<div id="_macho_cshortcodes" class="hidden">' + shortcodes + '</div>');

			// add to parent shortcode
			this.loadVals();
			pShortcode = $('#_macho_ushortcode').html().replace('{{child_shortcode}}', shortcodes);

			// add updated parent shortcode
			$('#_macho_ushortcode').remove();
			$('#macho-sc-form-table').prepend('<div id="_macho_ushortcode" class="hidden">' + pShortcode + '</div>');
			//$('#macho_select_shortcode').selectize({'copyClassesToDropdown':false});
		},
		children: function()
		{
			// assign the cloning plugin
			$('.macho-shortcodes-popup .child-clone-rows').appendo({
				subSelect: '> div.child-clone-row:last-child',
				allowDelete: false,
				focusFirst: false,
				onAdd: function(row) {
					// Get Upload ID
					var prev_upload_id = jQuery(row).prev().find('.macho-upload-button').data('upid');
					var new_upload_id = prev_upload_id + 1;
					jQuery(row).find('.macho-upload-button').attr('data-upid', new_upload_id);

					// activate chosen
					jQuery('.macho-form-multiple-select').chosen({
						width: '100%',
						placeholder_text_multiple: 'Select Options or Leave Blank for All'
					});

					// activate color picker
					jQuery('.wp-color-picker-field').wpColorPicker({
						change: function(event, ui) {
							machothemes_shortcodes.loadVals();
							machothemes_shortcodes.cLoadVals();
						}
					});

					// changing slide type
					var type = $(row).find('#macho_slider_type').val();

					if(type == 'video') {
						$(row).find('#macho_image_content, #macho_image_url, #macho_image_target, #macho_image_lightbox').closest('li').hide();
						$(row).find('#macho_video_content').closest('li').show();

						$(row).find('#_macho_cshortcode').text('[slide type="{{slider_type}}"]{{video_content}}[/slide]');
					}

					if(type == 'image') {
						$(row).find('#macho_image_content, #macho_image_url, #macho_image_target, #macho_image_lightbox').closest('li').show();
						$(row).find('#macho_video_content').closest('li').hide();

						$(row).find('#_macho_cshortcode').text('[slide type="{{slider_type}}" link="{{image_url}}" linktarget="{{image_target}}" lightbox="{{image_lightbox}}"]{{image_content}}[/slide]');
					}

					machothemes_shortcodes.loadVals();
					machothemes_shortcodes.cLoadVals();
				}
			});

			// remove button
			$('.macho-shortcodes-popup .child-clone-row-remove').live('click', function() {
				var	btn = $(this),
					row = btn.parent();

				if( $('.macho-shortcodes-popup .child-clone-row').size() > 1 )
				{
					row.remove();
				}
				else
				{
					$(this).parent().find('.row-alert').show();
				}

				return false;
			});

			// assign jUI sortable
			$( ".macho-shortcodes-popup .child-clone-rows" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.child-clone-row',
				cancel: 'div.iconpicker, input, select, textarea, a'
			});
		},
		resizeTB: function()
		{
			var	ajaxCont = $('#TB_ajaxContent'),
				tbWindow = $('#TB_window'),
				machoPopup = $('#macho-popup');

			tbWindow.css({
				height: window.macho_sm_tb_height,
				width: machoPopup.outerWidth(),
				marginLeft: -(machoPopup.outerWidth()/2)
			});

			ajaxCont.css({
				paddingTop: 0,
				paddingLeft: 0,
				paddingRight: 0,
				height: window.macho_sm_tb_height,
				overflow: 'auto', // IMPORTANT
				width: machoPopup.outerWidth()
			});

			tbWindow.show();

			$('#macho-popup').addClass('no_preview');
			$('#macho-sc-form-wrap #macho-sc-form').height(window.macho_sm_macho_shortcodes_height);
		},
		load: function()
		{

			var	macho = this,
				popup = $('#macho-popup'),
				form = $('#macho-sc-form', popup),
				shortcode = $('#_macho_shortcode', form).text(),
				popupType = $('#_macho_popup', form).text(),
				uShortcode = '';

			// if its the shortcode selection popup
			if($('#_macho_popup').text() == 'shortcode-generator') {
				$('.macho-sc-form-button').hide();
			}

			// resize TB
			machothemes_shortcodes.resizeTB();
			$(window).resize(function() { machothemes_shortcodes.resizeTB() });

			// initialise
			machothemes_shortcodes.loadVals();
			machothemes_shortcodes.children();
			machothemes_shortcodes.cLoadVals();

			// update on children value change
			$('.macho-cinput', form).live('change', function() {
				machothemes_shortcodes.cLoadVals();
			});

			// update on value change
			$('.macho-input', form).live('change', function() {
				machothemes_shortcodes.loadVals();
			});

			// change shortcode when a user selects a shortcode from choose a dropdown field
			$('#macho_select_shortcode').change(function() {
				var name = $(this).val();
				var label = $(this).text();

				if(name != 'select') {
					macho_popup(false, {
						title: label,
						identifier: name
					});

					$('#TB_window').hide();
				}
			});

			// activate chosen
			$('.macho-form-multiple-select').chosen({
				width: '100%',
				placeholder_text_multiple: 'Select Options'
			});

			// update upload button ID
			jQuery('.macho-upload-button:not(:first)').each(function() {
				var prev_upload_id = jQuery(this).data('upid');
				var new_upload_id = prev_upload_id + 1;
				jQuery(this).attr('data-upid', new_upload_id);
			});
			jQuery('[data-toggle="tooltip"]').tooltip();
		}
	}

	// run
	$('#macho-popup').livequery(function() {
		machothemes_shortcodes.load();

		$('#macho-popup').closest('#TB_window').addClass('macho-shortcodes-popup');

		$('#macho_video_content').closest('li').hide();

			// activate color picker
			$('.wp-color-picker-field').wpColorPicker({
				change: function(event, ui) {
					setTimeout(function() {
						machothemes_shortcodes.loadVals();
						machothemes_shortcodes.cLoadVals();
					},
					1);
				}
			});
	});

	// when insert is clicked
	$('.macho-insert').live('click', function() {

		var required = 0;
		$('.required-field .macho-input').each(function(){
			if ( $(this).val() == '' ) {
				required = required+1;
				$(this).parents('.required-field').addClass('need-value');
			};
		});

		if ( required > 0 ) {return false;};

		if( using_text_editor ) {
			if( $('#macho_select_shortcode').val() != 'table' ) {
				using_text_editor = false;

				// switch back to text editor mode
				window.switchEditors.go( 'content', html_editor_toggle );

				var html = $('#_macho_ushortcode').html().replace( /<br>/g, '' );

				// inserting the new shortcode at the correct position in the text editor content field
				editor_area.val( [ editor_area.val().slice(0, cursor_position), html, editor_area.val().slice(cursor_position)].join( '' ) );

				tb_remove();
			}

		} else if(window.tinyMCE)
		{
			window.tinyMCE.activeEditor.execCommand('mceInsertContent', false, $('#_macho_ushortcode').html());
			tb_remove();
		}
	});

	//tinymce.init(tinyMCEPreInit.mceInit['macho_content']);
	//tinymce.execCommand('mceAddControl', true, 'macho_content');
	//quicktags({id: 'macho_content'});

	// activate upload button
	$('.macho-upload-button').live('click', function(e) {
		e.preventDefault();

		upid = $(this).attr('data-upid');

		if($(this).hasClass('remove-image')) {
			$('.macho-upload-button[data-upid="' + upid + '"]').parent().find('img').attr('src', '').hide();
			$('.macho-upload-button[data-upid="' + upid + '"]').parent().find('input').attr('value', '');
			$('.macho-upload-button[data-upid="' + upid + '"]').text('Upload').removeClass('remove-image');

			return;
		}

		var file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select Image',
			button: {
				text: 'Select Image',
			},
			frame: 'post',
			multiple: false  // Set to true to allow multiple files to be selected
		});

		file_frame.open();

		$('.media-menu a:contains(Insert from URL)').remove();

		file_frame.on( 'select', function() {
			var selection = file_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				$('.macho-upload-button[data-upid="' + upid + '"]').parent().find('img').attr('src', attachment.url).show();
				$('.macho-upload-button[data-upid="' + upid + '"]').parent().find('input').attr('value', attachment.id);

				machothemes_shortcodes.loadVals();
				machothemes_shortcodes.cLoadVals();
			});

			$('.macho-upload-button[data-upid="' + upid + '"]').text('Remove').addClass('remove-image');
			$('.media-modal-close').trigger('click');
		});

		file_frame.on( 'insert', function() {
			var selection = file_frame.state().get('selection');
			var size = jQuery('.attachment-display-settings .size').val();

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if(!size) {
					attachment.url = attachment.url;
				} else {
					attachment.url = attachment.sizes[size].url;
				}
				$('.macho-upload-button[data-upid="' + upid + '"]').parent().find('img').attr('src', attachment.url).show();
				$('.macho-upload-button[data-upid="' + upid + '"]').parent().find('input').attr('value', attachment.id);

				machothemes_shortcodes.loadVals();
				machothemes_shortcodes.cLoadVals();
			});

			$('.macho-upload-button[data-upid="' + upid + '"]').text('Remove').addClass('remove-image');
			$('.media-modal-close').trigger('click');
		});
	});

	// activate iconpicker
	$('.iconpicker i').live('click', function(e) {
		e.preventDefault();

		var iconWithPrefix = $(this).attr('class');
		var fontName = $(this).attr('data-name');

		if($(this).hasClass('active')) {
			$(this).parent().find('.active').removeClass('active');

			$(this).parent().parent().find('input').attr('value', '');
		} else {
			$(this).parent().find('.active').removeClass('active');
			$(this).addClass('active');

			$(this).parent().parent().find('input').attr('value', fontName);
		}

		machothemes_shortcodes.loadVals();
		machothemes_shortcodes.cLoadVals();
	});

	// table shortcode
	$('#macho-sc-form-table .macho-insert').live('click', function(e) {
		e.stopPropagation();

		var shortcodeType = $('#macho_select_shortcode').val();

		if(shortcodeType == 'table') {
			var type = $('#macho-sc-form-table #macho_type').val();
			var columns = $('#macho-sc-form-table #macho_columns').val();

			var text = '<div class="macho-table table-' + type + '"><table width="100%"><thead><tr>';

			for(var i=0;i<columns;i++) {
				text += '<th>Column ' + (i + 1) + '</th>';
			}

			text += '</tr></thead><tbody>';

			for(var i=0;i<columns;i++) {
				text += '<tr>';
				if(columns >= 1) {
					text += '<td>Item #' + (i + 1) + '</td>';
				}
				if(columns >= 2) {
					text += '<td>Description</td>';
				}
				if(columns >= 3) {
					text += '<td>Discount:</td>';
				}
				if(columns >= 4) {
					text += '<td>$' + (i + 1) + '.00</td>';
				}
				if(columns >= 5) {
					text += '<td>$ 0.' + (i + 1) + '0</td>';
				}
				if(columns >= 6) {
					text += '<td>$ 0.' + (i + 1) + '0</td>';
				}
				text += '</tr>';
			}

			text += '<tr>';

			if(columns >= 1) {
				text += '<td><strong>All Items</strong></td>';
			}
			if(columns >= 2) {
				text += '<td><strong>Description</strong></td>';
			}
			if(columns >= 3) {
				text += '<td><strong>Your Total:</strong></td>';
			}
			if(columns >= 4) {
				text += '<td><strong>$10.00</strong></td>';
			}
			if(columns >= 5) {
				text += '<td><strong>Tax: $10.00</strong></td>';
			}
			if(columns >= 6) {
				text += '<td><strong>Gross: $10.00</strong></td>';
			}
			text += '</tr>';
			text += '</tbody></table></div>';

			if( using_text_editor ) {
				using_text_editor = false;

				// switch back to text editor mode
				window.switchEditors.go( 'content', html_editor_toggle );

				// inserting the new shortcode at the correct position in the text editor content field
				editor_area.val( [ editor_area.val().slice(0, cursor_position), text, editor_area.val().slice(cursor_position)].join( '' ) );

				tb_remove();
			} else if(window.tinyMCE)
			{
				window.tinyMCE.activeEditor.execCommand('mceInsertContent', false, text);
				tb_remove();
			}
		}
	});

	// slider shortcode
	$('#macho_slider_type').live('change', function(e) {
		e.preventDefault();

		var type = $(this).val();
		if(type == 'video') {
			$(this).parents('ul').find('#macho_image_content, #macho_image_url, #macho_image_target, #macho_image_lightbox').closest('li').hide();
			$(this).parents('ul').find('#macho_video_content').closest('li').show();

			$('#_macho_cshortcode').text('[slide type="{{slider_type}}"]{{video_content}}[/slide]');
		}

		if(type == 'image') {
			$(this).parents('ul').find('#macho_image_content, #macho_image_url, #macho_image_target, #macho_image_lightbox').closest('li').show();
			$(this).parents('ul').find('#macho_video_content').closest('li').hide();

			$('#_macho_cshortcode').text('[slide type="{{slider_type}}" link="{{image_url}}" linktarget="{{image_target}}" lightbox="{{image_lightbox}}"]{{image_content}}[/slide]');
		}
	});

	$('.macho-add-video-shortcode').live('click', function(e) {
		e.preventDefault();

		var shortcode = $(this).attr('href');
		var content = $(this).parents('ul').find('#macho_video_content');

		content.val(content.val() + shortcode);
		machothemes_shortcodes.cLoadVals();
	});

	$('#macho-popup textarea').live('focus', function() {
		var text = $(this).val();

		if(text == 'Your Content Goes Here') {
			$(this).val('');
		}
	});

	$('.macho-gallery-button').live('click', function(e) {
		var gallery_file_frame;

		e.preventDefault();

		alert('To add images to this post or page for attachments layout, navigate to "Upload Files" tab in media manager and upload new images.');

		gallery_file_frame = wp.media.frames.gallery_file_frame = wp.media({
			title: 'Attach Images to Post/Page',
			button: {
				text: 'Go Back to Shortcode',
			},
			frame: 'post',
			multiple: true  // Set to true to allow multiple files to be selected
		});

		gallery_file_frame.open();

		$('.media-menu a:contains(Insert from URL)').remove();

		$('.media-menu-item:contains("Upload Files")').trigger('click');

		gallery_file_frame.on( 'select', function() {
			$('.media-modal-close').trigger('click');

			machothemes_shortcodes.loadVals();
			machothemes_shortcodes.cLoadVals();
		});
	});

	// text editor shortcode button was used
	jQuery(window).resize(function() {
		$('.quicktags-toolbar input[id*=macho_shortcodes_text_mode]').addClass( 'macho-shortcode-generator-button' );
	});
	$( '.switch-html, .macho-expand-child' ).live('click', function(e) {
		$('.quicktags-toolbar input[id*=macho_shortcodes_text_mode]').addClass( 'macho-shortcode-generator-button' );
	});

	$('.quicktags-toolbar input[id*="macho_shortcodes_text_mode"]').each(function() {
		$(this).addClass( 'macho-shortcode-generator-button' );
	})

	$('.quicktags-toolbar input[id*=macho_shortcodes_text_mode]').live('click', function(e) {

		var popup = 'shortcode-generator';

		// set the flag for text editor, change to visual editor
		using_text_editor = true;
		text_editor_toggle = 'tmce';
		html_editor_toggle = 'html';
		editor_area = $( this ).parents( '.wp-editor-container' ).find( '.wp-editor-area' );

		cursor_position = editor_area.getCursorPosition();

		window.switchEditors.go( 'content' );

		// load thickbox
		tb_show("Macho Shortcodes", ajaxurl + "?action=macho_sm_popup&popup=" + popup);

		jQuery('#TB_window').hide();
	});



});


// Helper function to check the cursor position of text editor content field before the shortcode generator is opened
(function($, undefined) {
    $.fn.getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
})(jQuery);