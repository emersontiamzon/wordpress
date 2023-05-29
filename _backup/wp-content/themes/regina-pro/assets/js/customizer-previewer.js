(function( $ ) {// jscs:ignore validateLineBreaks

  'use strict';

  wp.customize.bind( 'preview-ready', function() {
    wp.customize.preview.bind( 'section-highlight', function( data ) {
      var selector = '#' + data.section;

      // Only on the front page.
      if ( ! $( selector ).length ) {
        return;
      }
      $( 'html,body' ).animate( {
        scrollTop: $( selector ).offset().top
      }, 1000 );
    } );
  } );
})( jQuery );
