(function( $ ) {// jscs:ignore validateLineBreaks

  'use strict';

  /* ==========================================================================
   Page Preloader
   ========================================================================== */

  function PagePreloader() {
    var sec = 500;

    $( '#page-loader .page-loader-inner' ).delay( sec ).fadeIn( 10, function() {
      $( this ).fadeOut( sec, function() {
        $( '#page-loader' ).fadeOut( sec );
      } );
    } );
  }

  jQuery( window ).load( function( $ ) {
    PagePreloader();
  } );

})( jQuery );
