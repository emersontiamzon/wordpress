tinymce.PluginManager.add('macho_button', function(ed, url) {
	ed.addCommand("machoSMPopup", function ( a, params )
	{
		var popup = 'shortcode-generator';

		if(typeof params != 'undefined' && params.identifier) {
			popup = params.identifier;
		}

		// load thickbox
		tb_show("Macho Shortcodes", ajaxurl + "?action=macho_sm_popup&popup=" + popup);

		jQuery('#TB_window').hide();
	});

	// Add a button that opens a window
	ed.addButton('macho_button', {
		text: '',
		icon: true,
		image: MachoShortcodesManager.plugin_folder + '/tinymce/images/icon.png',
		cmd: 'machoSMPopup'
	});
});