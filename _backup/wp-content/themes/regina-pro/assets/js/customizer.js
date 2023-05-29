(function( $ ) {// jscs:ignore validateLineBreaks

  /* Multi-level panels in customizer */

  var api = wp.customize;

  // Extend Panel
  var _panelEmbed = wp.customize.Panel.prototype.embed;
  var _panelIsContextuallyActive = wp.customize.Panel.prototype.isContextuallyActive;
  var _panelAttachEvents = wp.customize.Panel.prototype.attachEvents;

  api.EpsilonTabs = [];

  api.bind( 'pane-contents-reflowed', function() {

    // Reflow panels
    var panels = {};

    api.panel.each( function( panel ) {

      if (
          'regina_panel' !== panel.params.type ||
          'undefined' === typeof panel.params.panel
      ) {

        return;

      }

      if ( undefined === panels[ panel.params.panel ] ) {
        panels[ panel.params.panel ] = [];
      }

      panels[ panel.params.panel ].push( panel );

    } );

    $.each( panels, function( parentID, children ) {
      var sections = api.panel( parentID ).sections(),
          newElements;

      if ( sections.length > 0 ) {
        newElements = children.concat( sections );
      } else {
        newElements = children;
      }

      newElements.sort( api.utils.prioritySort ).reverse();
      $.each( newElements, function( i, panel ) {

        var parentContainer = $( '#sub-accordion-panel-' + panel.params.panel );
        parentContainer.children( '.panel-meta' ).after( panel.headContainer );

      } );

    } );

  } );

  wp.customize.Panel = wp.customize.Panel.extend( {
    attachEvents: function() {
      var panel = this;
      if (
          'regina_panel' !== this.params.type ||
          'undefined' === typeof this.params.panel
      ) {

        _panelAttachEvents.call( this );

        return;

      }

      _panelAttachEvents.call( this );

      panel.expanded.bind( function( expanded ) {

        var parent = api.panel( panel.params.panel );

        if ( expanded ) {

          parent.contentContainer.addClass( 'current-panel-parent' );

        } else {

          parent.contentContainer.removeClass( 'current-panel-parent' );

        }

      } );

      panel.container.find( '.customize-panel-back' ).off( 'click keydown' ).on( 'click keydown', function( event ) {

        if ( api.utils.isKeydownButNotEnterEvent( event ) ) {

          return;

        }

        event.preventDefault(); // Keep this AFTER the key filter above

        if ( panel.expanded() ) {

          api.panel( panel.params.panel ).expand();

        }

      } );

    },
    embed: function() {
      var panel = this;
      var parentContainer = $( '#sub-accordion-panel-' + this.params.panel );

      if (
          'regina_panel' !== this.params.type ||
          'undefined' === typeof this.params.panel
      ) {

        _panelEmbed.call( this );

        return;

      }

      _panelEmbed.call( this );

      parentContainer.append( panel.headContainer );

    },
    isContextuallyActive: function() {
      var panel = this;
      var children = this._children( 'panel', 'section' );
      var activeCount = 0;

      if (
          'regina_panel' !== this.params.type
      ) {

        return _panelIsContextuallyActive.call( this );

      }

      api.panel.each( function( child ) {

        if ( ! child.params.panel ) {

          return;

        }

        if ( child.params.panel !== panel.id ) {

          return;

        }

        children.push( child );

      } );

      children.sort( api.utils.prioritySort );

      _( children ).each( function( child ) {

        if ( child.active() && child.isContextuallyActive() ) {

          activeCount += 1;

        }

      } );

      return ( 0 !== activeCount );

    }

  } );

  // Redirect previewer depending on section expanded
  api.section( 'regina_blog_category_options', function( section ) {
    section.expanded.bind( function( isExpanding ) {
      var newURL = ReginaCustomizer.categoryURL;
      if ( isExpanding ) {
        if ( ! $.inArray( newURL, api.previewer.allowedUrls ) ) {
          api.previewer.allowedUrls.push( newURL );
        }
        wp.customize.previewer.previewUrl.set( newURL );
      } else {
        wp.customize.previewer.previewUrl.set( ReginaCustomizer.siteURL );
      }

    } );
  } );

  api.section( 'regina_blog_tag_options', function( section ) {
    section.expanded.bind( function( isExpanding ) {
      var newURL = ReginaCustomizer.tagURL;
      if ( isExpanding ) {
        if ( ! $.inArray( newURL, api.previewer.allowedUrls ) ) {
          api.previewer.allowedUrls.push( newURL );
        }
        wp.customize.previewer.previewUrl.set( newURL );
      } else {
        wp.customize.previewer.previewUrl.set( ReginaCustomizer.siteURL );
      }

    } );
  } );

  api.section( 'regina_blog_author_options', function( section ) {
    section.expanded.bind( function( isExpanding ) {
      var newURL = ReginaCustomizer.authorURL;
      if ( isExpanding ) {
        if ( ! $.inArray( newURL, api.previewer.allowedUrls ) ) {
          api.previewer.allowedUrls.push( newURL );
        }
        wp.customize.previewer.previewUrl.set( newURL );
      } else {
        wp.customize.previewer.previewUrl.set( ReginaCustomizer.siteURL );
      }

    } );
  } );

  api.section( 'regina_blog_archive_options', function( section ) {
    section.expanded.bind( function( isExpanding ) {
      var newURL = ReginaCustomizer.postsURL;
      if ( isExpanding ) {
        if ( ! $.inArray( newURL, api.previewer.allowedUrls ) ) {
          api.previewer.allowedUrls.push( newURL );
        }
        wp.customize.previewer.previewUrl.set( newURL );
      } else {
        wp.customize.previewer.previewUrl.set( ReginaCustomizer.siteURL );
      }

    } );
  } );

  api.section( 'regina_blog_search_options', function( section ) {
    section.expanded.bind( function( isExpanding ) {
      var newURL = ReginaCustomizer.searchURL;
      if ( isExpanding ) {
        if ( ! $.inArray( newURL, api.previewer.allowedUrls ) ) {
          api.previewer.allowedUrls.push( newURL );
        }
        wp.customize.previewer.previewUrl.set( newURL );
      } else {
        wp.customize.previewer.previewUrl.set( ReginaCustomizer.siteURL );
      }

    } );
  } );

  api.section( 'regina_single_post_section', function( section ) {
    section.expanded.bind( function( isExpanding ) {
      var newURL = ReginaCustomizer.postURL;
      if ( isExpanding ) {
        if ( ! $.inArray( newURL, api.previewer.allowedUrls ) ) {
          api.previewer.allowedUrls.push( newURL );
        }
        wp.customize.previewer.previewUrl.set( newURL );
      } else {
        wp.customize.previewer.previewUrl.set( ReginaCustomizer.siteURL );
      }

    } );
  } );

  api.EpsilonTab = api.Control.extend( {

    ready: function() {
      var control = this;
      control.container.find( 'a.epsilon-tab' ).click( function( evt ) {
        var tab = $( this ).data( 'tab' );
        evt.preventDefault();
        control.container.find( 'a.epsilon-tab' ).removeClass( 'active' );
        $( this ).addClass( 'active' );
        control.toggleActiveControls( tab );
      } );

      api.EpsilonTabs.push( control.id );
    },

    toggleActiveControls: function( tab ) {
      var control = this,
          currentFields = control.params.buttons[ tab ].fields;
      _.each( control.params.fields, function( id ) {
        var tabControl = api.control( id );
        if ( undefined !== tabControl ) {
          tabControl.container.addClass( 'tab-element' );
          if ( tabControl.active() && $.inArray( id, currentFields ) >= 0 ) {
            tabControl.toggle( true );
          } else {
            tabControl.toggle( false );
          }
        }
      } );
    }

  } );

  api.ReginaPluginInstall = api.Control.extend( {
    ready: function() {
      var control = this,
          actionButton = control.container.find( 'a.regina-pro-install-button' );

      control.container.find( 'a.regina-pro-install-button' ).click( function( evt ) {
        evt.preventDefault();

        actionButton.addClass( 'updating-message' );
        actionButton.html( control.params.labels[ control.params.action ] );

        if ( 'install' == control.params.action ) {
          wp.updates.installPlugin( {
            slug: control.params.slug
          } );
        }else{
          control.activePlugin( control.params.plugin_link );
        }

      });

      jQuery( document ).on( 'wp-plugin-install-success', function( response, data ) {

          console.log( control );

          if ( data.slug == control.params.slug ) {
            control.activePlugin( data.activateUrl );
          }

      } );

    },

    activePlugin: function( url ){
      var control = this,
      actionButton = control.container.find( 'a.regina-pro-install-button' );

      actionButton.html( control.params.labels['activate'] );

      jQuery.ajax( {
          async: true,
          type: 'GET',
          dataType: 'html',
          url: url,
          success: function() {
            location.reload();
          }
      } );

    }
  });

  api.NewSection = api.Section.extend( {

    isContextuallyActive: function() {
      return true;
    },

    /**
     * Add behaviors for the accordion section.
     *
     * @since 4.3.0
     */
    attachEvents: function() {
      var section = this,
          container = section.container;

      section.headContainer.find( '.accordion-section-title' ).replaceWith(
          wp.template( 'nav-menu-create-menu-section-title' )
      );

      /*
       * We have to manually handle section expanded because we do not
       * apply the `accordion-section-title` class to this button-driven section.
       */
      container.on( 'click', '.add-new-menu-item', function() {
        $( this ).toggleClass( 'open' );
        section.container.find( '.section-content' ).slideToggle( 'slow' );
        section.container.find( '.menu-name-field' ).first().focus();
      } );

      container.on( 'keydown', '#new-section-title', function( event ) {
        if ( 13 === event.which ) { // Enter.
          section.submit();
        }
      } );
      container.on( 'click', '#create-new-section-submit', function( event ) {
        section.submit();
        event.stopPropagation();
        event.preventDefault();
      } );

    },

    submit: function() {
      var section = this,
          contentContainer = section.container,
          titleInput = contentContainer.find( '.menu-name-field' ).first(),
          name = contentContainer.find( '.menu-name-field' ).first().val(),
          request;

      contentContainer.find( '.spinner' ).addClass( 'is-active' );

      if ( ! name ) {
        titleInput.addClass( 'invalid' );
        titleInput.focus();
        return;
      }

      request = wp.ajax.post( 'add_section', {
        'title': name,
        'nonce': ReginaCustomizer.nonce
      } );

      request.always( function( response ) {
        var data,
            customizeId,
            reginaSection,
            description;

        if ( '0' !== response ) {
          data = JSON.parse( response );
          customizeId = 'regina_section_' + data.slug;
          description = ReginaCustomizer.editSectionDescription.replace( '%d', data.id );
          reginaSection = new api.reginaSection( customizeId, {
            params: {
              id: customizeId,
              panel: 'regina_frontpage_panel',
              slug: data.slug,
              section_id: data.id,
              title: name,
              description: description,
              type: 'regina_section',
              customizeAction: 'Customizing ▸ Front Page Sections',
              priority: 70
            }
          } );
          api.section.add( customizeId, reginaSection );
          api.section( customizeId ).focus();
        }

        contentContainer.find( '.spinner' ).removeClass( 'is-active' );
      } );

      titleInput.val( '' );
      titleInput.removeClass( 'invalid' );
      section.container.find( '.add-new-menu-item' ).removeClass( 'open' );
      section.container.find( '.section-content' ).slideToggle( 'slow' );

    }

  } );

  api.reginaSection = api.Section.extend( {
    isContextuallyActive: function() {
      return true;
    },

    ready: function() {
      var section = this;
      section.expanded.bind( function( isExpanding ) {
        if ( isExpanding ) {
          api.previewer.send( 'section-highlight', { section: section.params.slug } );
        }
      } );
    }
  } );

  function reginaSectionsOrder() {
    var sections = $( '#sub-accordion-panel-regina_frontpage_panel' ).sortable( 'toArray' ),
        sOrdered = [],
        request;
    $.each( sections, function( index, sID ) {
      var section;
      sID = sID.replace( 'accordion-section-', '' );
      section = api.section( sID );
      sOrdered.push( section.params.section_id );
    } );

    request = wp.ajax.post( 'order_section', {
      'sections': sOrdered,
      'nonce': ReginaCustomizer.nonce
    } );

    request.always( function( response ) {
      wp.customize.previewer.refresh();
    } );

  }



  /**
   * Extends wp.customize.sectionConstructor with section constructor for menu.
   */
  $.extend( api.sectionConstructor, {
    regina_new_section: api.NewSection,
    regina_section: api.reginaSection
  } );

  api.section( 'regina_homepage_options', function( section ) {
    section.expanded.bind( function( isExpanding ) {
      if ( isExpanding ) {
        api.previewer.send( 'section-highlight', { section: 'home-slider' } );
      }
    } );
  } );

  $.extend( api.controlConstructor, {
    'epsilon-tab': api.EpsilonTab,
    'regina-pro-install-plugin': api.ReginaPluginInstall
  } );

  api.bind( 'ready', function() {
    _.each( api.EpsilonTabs, function( epsilonTab ) {
      var control = api.control( epsilonTab );
      control.toggleActiveControls( 0 );
    } );

    // Sortable Sections
    $( '#sub-accordion-panel-regina_frontpage_panel' ).sortable( {
      helper: 'clone',
      items: '> li.control-section:not(#accordion-section-regina_homepage_options)',
      cancel: 'li.ui-sortable-handle.open',
      delay: 150,
      update: function( event, ui ) {
        reginaSectionsOrder();
      }

    } );

     // Show hide controls based on booking form type
     api.control( 'regina_booking_form_opions', function( control ) {
      control.setting.bind( function ( formType ) {
        var controls = {
		  'kali-forms' : [ 'regina_kaliforms', 'regina_kaliforms_form_id' ],
        };

        _.each( controls, function( values, key ) {
          _.each( values, function( controlID, index ){
            var currentControl = api.control( controlID );

            // Check if we have this control
            if ( 'undefined' != typeof currentControl ) {
              if ( key == formType ) {
                currentControl.toggle( true );
              }else{
                currentControl.toggle( false );
              }
            }
          });
        });

      });
     });

  } );

})( jQuery );
