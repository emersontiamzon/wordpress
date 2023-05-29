(function( $ ) {// jscs:ignore validateLineBreaks

  'use strict';

  var homeSlider = '',
      testimonialSlider = '',
      element,
      elementID,
      scrollToID,
      newURL,
      accordionUL,
      accordionLink;

  $( window ).load( function() {

    if ( $( '#home-slider .bxslider' ).length ) {
      homeSlider.reloadSlider();
    }

    if ( $( '#testimonials-slider .bxslider' ).length ) {
      testimonialSlider.reloadSlider();
    }

  } );

  $( document ).ready( function() {

    // Owl Carousel - used to create carousels throughout the site
    // http://owlgraphic.com/owlcarousel/
    if ( 'undefined' !== typeof $.fn.owlCarousel ) {

        $( '.owlCarousel' ).each(function() {

            var sliderSelector = '#owlCarousel-' + $( this ).data( 'slider-id' ); // This is the slider selector
            var sliderItems         = $( this ).data( 'slider-items' );
            var sliderSpeed         = $( this ).data( 'slider-speed' );
            var sliderAutoPlay      = $( this ).data( 'slider-auto-play' );
            var sliderNavigation    = $( this ).data( 'slider-navigation' );
            var sliderPagination    = $( this ).data( 'slider-pagination' );
            var sliderSingleItem    = $( this ).data( 'slider-single-item' );

            //Conversion of 1 to true & 0 to false
            // auto play
            if ( 0 === sliderAutoPlay || 'false' === sliderAutoPlay ) {
                sliderAutoPlay = false;
            } else {
                sliderAutoPlay = true;
            }

            // Pager
            if ( 0 === sliderPagination || 'false' === sliderPagination ) {
                sliderPagination = false;
            } else {
                sliderPagination = true;
            }

            // Navigation
            if ( 0 === sliderNavigation || 'false' === sliderNavigation ) {
                sliderNavigation = false;
            } else {
                sliderNavigation = true;
            }

            // Custom Navigation events outside of the owlCarousel mark-up
            $( '.mt-owl-next' ).on( 'click', function() {
                $( sliderSelector ).trigger( 'owl.next' );
            });
            $( '.mt-owl-prev' ).on( 'click', function() {
                $( sliderSelector ).trigger( 'owl.prev' );
            });

            // Instantiate the slider with all the options
            $( sliderSelector ).owlCarousel({
                items: sliderItems,
                slideSpeed: sliderSpeed,
                navigation: sliderNavigation,
                autoPlay: sliderAutoPlay,
                pagination: sliderPagination,
                navigationText: [ // Custom navigation text (instead of bullets). navigationText : false to disable arrows / bullets
                    '<i class=\'fa fa-angle-left\'></i>',
                    '<i class=\'fa fa-angle-right\'></i>'
                ]
            });

        });

    } // End

    /* ---------------------------------------------------------------------- */
    /*  Menu
     /* ---------------------------------------------------------------------- */

    $( '.mobile-nav-btn' ).on( 'click', function() {
      $( '#navigation' ).slideToggle();
    } );

    /* ---------------------------------------------------------------------- */
    /*  Sliders
     /* ---------------------------------------------------------------------- */
    homeSlider = $( '#home-slider .bxslider' ).bxSlider( {
      mode: 'fade',
      nextText: '<span class="nc-icon-glyph arrows-1_bold-down"></span>',
      prevText: '<span class="nc-icon-glyph arrows-1_bold-up"></span>',
      auto: regina.autoplay,
      speed: parseInt( regina.speed, 10 ),
      pause: parseInt( regina.pause, 10 )
    } );

    $( '#home-slider .bx-wrapper .bx-controls' ).css( { 'margin-top': '-' + ( $( '#home-slider .bx-wrapper .bx-controls' ).outerHeight() / 2 ) + 'px' } );

    testimonialSlider = $( '#testimonials-slider .bxslider' ).bxSlider( {
      mode: 'fade',
      nextText: '<span class="nc-icon-glyph arrows-1_bold-right"></span>',
      prevText: '<span class="nc-icon-glyph arrows-1_bold-left"></span>'
    } );

    /* ---------------------------------------------------------------------- */
    /*  Back to Top & Waypoint
     /* ---------------------------------------------------------------------- */

    $( '.back-to-top' ).on( 'click', function( event ) {
      event.preventDefault();
      $( 'body,html' ).animate( {
            scrollTop: 0
          }, 1000
      );
    } );

    $( '#footer' ).waypoint( function() {
      $( '.back-to-top' ).fadeIn( 1000 );
    }, { offset: '50%' } );

    /* ---------------------------------------------------------------------- */
    /*  Lazyload
     /* ---------------------------------------------------------------------- */

    if ( 'undefined' !== typeof $.fn.lazyload ) {
      $( '.lazy' ).show().lazyload( {
        effect: 'fadeIn',
        skip_invisible: false
      } );
    }

    /* ---------------------------------------------------------------------- */
    /*  Accordion
     /* ---------------------------------------------------------------------- */

    accordionUL = $( '.accordion .inner' );
    accordionLink = $( '.accordion > ul > li > a' );

    accordionUL.hide();

    accordionLink.on( 'click', function( e ) {
      e.preventDefault();
      if ( ! $( this ).hasClass( 'active' ) ) {
        accordionLink.removeClass( 'active' );
        accordionUL.filter( ':visible' ).slideUp( 'normal' );
        $( this ).addClass( 'active' ).next().stop( true, true ).slideDown( 'normal' );
      } else {
        $( this ).removeClass( 'active' );
        $( this ).next().stop( true, true ).slideUp( 'normal' );
      }
    } );

    /* ---------------------------------------------------------------------- */
    /*  Height Functions
     /* ---------------------------------------------------------------------- */

    function setHeights() {

      // Only set the height of last block on the team members page, after the LazyLoad has finished
      $( 'img.lazy' ).load( function() {

        var aboutHeight = $( '#we-care-block .image' ).outerHeight();
        var teamHeight = $( '.team-member' ).outerHeight();

        $( '.team-join' ).css( { height: teamHeight } );

        if ( $( window ).width() > 992 ) {
          $( '#we-care-block .content' ).css( { height: aboutHeight } );
        }
      } );

    }

    /* ---------------------------------------------------------------------- */
    /*  Init Functions
     /* ---------------------------------------------------------------------- */

    setHeights();

    /* Book an appointment form */

    $( '#send-appointment' ).click( function( evt ) {
      var errno = 0;
      var data = { 'action': 'send_appointment_email' };

      evt.preventDefault();
      evt.stopPropagation();

      if ( '' === $( '#appointment-form .name input' ).val() ) {
        $( '#appointment-form .name input' ).addClass( 'error' );
        errno = errno + 1;
      } else {
        data.name = $( '#appointment-form .name input' ).val();
      }

      if ( '' === $( '#appointment-form .email input' ).val() ) {
        $( '#appointment-form .email input' ).addClass( 'error' );
        errno = errno + 1;
      } else {
        data.email = $( '#appointment-form .email input' ).val();
      }

      if ( '' === $( '#appointment-form .phone input' ).val() ) {
        $( '#appointment-form .phone input' ).addClass( 'error' );
        errno = errno + 1;
      } else {
        data.phone = $( '#appointment-form .phone input' ).val();
      }

      if ( '' === $( '#appointment-form .date input' ).val() ) {
        $( '#appointment-form .date input' ).addClass( 'error' );
        errno = errno + 1;
      } else {
        data.date = $( '#appointment-form .date input' ).val();
      }

      if ( '' === $( '#appointment-form .message textarea' ).val() ) {
        $( '#appointment-form .message textarea' ).addClass( 'error' );
        errno = errno + 1;
      } else {
        data.message = $( '#appointment-form .message textarea' ).val();
      }

      if ( 0 === errno ) {
        $.ajax( {
          method: 'POST',
          url: regina.ajax_url,
          data: data
        } ).done( function( msg ) {
          if ( 'succes' === msg ) {
            $( '#appointment-form' ).hide();
            $( '#mt-popup-modal .succes' ).show();
          }
        } );
      }
    } );

    $( '#appointment-form' ).on( 'keyup', '.input .error', function() {
      if ( $( this ).hasClass( 'error' ) ) {
        $( this ).removeClass( 'error' );
      }
    } );

    // Menu Items
    $( '#header #navigation .menu-item a[href^=#]:not([href=#]' ).click( function( evt ) {
      evt.preventDefault();
      elementID = $( this ).attr( 'href' );
      if ( $( elementID ).length > 0 ) {
        $( 'html,body' ).animate( {
          scrollTop: $( elementID ).offset().top - 10
        }, 1000 );
      }
    } );

    // Smooth scroll
    if ( '' !== window.location.hash ) {
      element = $( '#header #navigation .menu-item a[href=' + window.location.hash + ']' );
      if ( element ) {
        scrollToID = window.location.hash;
        $( 'html,body' ).animate( {
          scrollTop: $( scrollToID ).offset().top
        }, 1000 );

        newURL = window.location.href.replace( window.location.hash, '' );
        window.history.replaceState( {}, document.title, newURL );

      }
    }

  } );

})( window.jQuery );
