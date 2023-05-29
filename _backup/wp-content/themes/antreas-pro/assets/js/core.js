//CORE JS FUNCTIONALITY
//Contains only the most essential functions for the theme. 

// Get the header
const header = document.getElementById('header')

// Get the offset position of the navbar
const sticky = header.offsetTop

/* ==========================================================================
Mobile Menu Toggle
========================================================================== */
document.addEventListener('DOMContentLoaded', function () {
  var menu_element = document.getElementById('menu-mobile-open')
  var menu_exists = !!menu_element
  if (menu_exists) {
    menu_element.addEventListener('click', function () {
      document.body.classList.add('menu-mobile-active')
    })

    document.getElementById('menu-mobile-close').addEventListener('click', function () {
      document.body.classList.remove('menu-mobile-active')
    })
  }
})

/* ==========================================================================
handleStickyHeader
========================================================================== */

// When the user scrolls the page, execute myFunction
window.onscroll = function () {
  handleSticky()
}

// Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
function handleSticky () {

  if (document.body.classList.contains('cpo-sticky-header')) { // only run if sticky header is actually enabled
    if (window.pageYOffset >= sticky) {
	  header.classList.add('cpo-sticky')
	  document.body.style.marginTop = header.offsetHeight + "px";
    } else {
	  header.classList.remove('cpo-sticky')
	  document.body.style.marginTop = 0;
    }
  }
}

// animated columns.
jQuery('body.enable-animations:not(.customizer-preview) .section').each( function() {

	var $section = jQuery( this );
	var $columns = $section.find('.column');

	$columns.addClass('column--animated');
	
	var waypoint = new Waypoint({
		element: $section,
		offset: '50%',
		handler: function() {
			$columns.each( function(index) {
				jQuery( this ).delay( index*200 ).queue(function(next){
					jQuery( this ).removeClass('column--animated');
					next();
				});
			});
		}
	  }) 
});

// scroll to animation for main menu links.
jQuery( 'a[href*="#"]:not([href="#"])' ).on( 'click', function(e) {
	var target;
	if ( location.pathname.replace( /^\//, '' ) === this.pathname.replace( /^\//, '' ) && location.hostname === this.hostname ) {
		target = jQuery( this.hash );
		target = target.length ? target : jQuery( '[name=' + this.hash.slice( 1 ) + ']' );
		if ( target.length ) {
			e.preventDefault();
			jQuery( 'html, body' ).animate( { scrollTop: target.offset().top }, 1000, 'swing' );
		}
	}
});

// scroll to top
jQuery('#back-to-top').on('click', function(e) {
	e.preventDefault();
	jQuery('html, body').animate( { scrollTop: 0 }, 'slow');
});
 
