( function( api ) {

	api.AntreasTinymceControl = api.Control.extend( {

		ready: function() {
			var control = this;

			wp.editor.initialize( control.id , {
				mediaButtons: true,
				tinymce: {
					wpautop: false,
					toolbar1: control.params.toolbar1,
					toolbar2: control.params.toolbar2,
				},
				quicktags: true
			});

			jQuery( document ).on( 'tinymce-editor-init' , function( event, editor ) {
				editor.on( 'Change Paste ExecCommand NodeChange' , function( e ) {
					jQuery( '#'+editor.id ).trigger( 'change' );
					tinyMCE.triggerSave();
				});
			});

		}
	});

	jQuery.extend( api.controlConstructor, {
		'antreas-tinymce-control': api.AntreasTinymceControl,
    });

})( wp.customize );