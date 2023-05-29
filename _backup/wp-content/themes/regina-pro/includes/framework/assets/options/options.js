(function($) {
    'use strict';

    $.macho = $.macho || {};

    $.macho.filter = {

        filters: {},

        add: function (tag, filter) {
            (this.filters[tag] || (this.filters[tag] = [])).push(filter);
        },

        apply: function (tag, val) {
            if(this.filters[tag]){
                var filters = this.filters[tag];
                for(var i = 0; i < filters.length; i++){
                    val = filters[i](val);
                    if(val === false){
                        break;
                    }
                }
            }
            return val;
        }
    };

    $.macho.is_clone_field = function( input )
    {
        if( input.attr('name') && input.attr('name').indexOf('-clone##]') != -1 )
        {
            return true;
        }

        return false;
    };

    $.macho.layout = function() {

        if(macho.context == 'page' || macho.context == 'meta'){

            $('.wrap').addClass($('.options-layout-input:checked').val());

            $(document).on('click', '.options-layout-input', function(){
                var options_layout = $('.options-layout-input:checked').val();
                $.post(ajaxurl, {
                    action: 'options_save_layout',
                    options_layout: options_layout,
                    page: pagenow,
                    screenoptionnonce: $('#screenoptionnonce').val()
                });
                if(options_layout == 'options-normal'){
                    $('.wrap').addClass('options-normal').removeClass('options-block');
                }else{
                    $('.wrap').addClass('options-block').removeClass('options-normal');
                }
            });

        }

    };

    $.macho.restore = function() {
        $(document).on('click', '.options-restore-default', function(){
            $.macho.proccess_required = false;

            if(!confirm(macho.L10n.warning_defaults)){
                return false;
            }

            var input = $('<input>').attr('type', 'hidden').attr('name', 'restore-defaults').val('true');
            $('.wrap form').append($(input)).submit();
            $.macho.proccess_required = true;
            return false;
        });
    };

    $.macho.passes = true;

    $.macho.proccess_required = true;

    $.macho.required = function() {

        $(document).on('submit', 'form', function(){
            if($.macho.proccess_required === false){
                return;
            }
            $.macho.passes = true;
            $(document).trigger('macho/pre_validate');
            $.each(macho.required, function( id, data ){
                $(document).trigger('macho/validate/'+data.type, [id, data]);
            });
            $(document).trigger('macho/post_validate');

            if($.macho.passes !== true){
                $('#publish.button-primary-disabled').removeClass('button-primary-disabled');
                $('#publishing-action .spinner').hide();
                $(document).trigger('macho/validation_failed');
                $('tr.options-required input').first().focus();
                return false;
            }
            $(document).trigger('macho/validation_passed');
        });

    };

    $.macho.required_postboxes = function() {

        if($('.postbox').length > 0){
            $('.postbox').each(function(){
                if($(this).find('tr.options-required').length > 0){
                    $(this).find('h3.hndle').addClass('options-required');
                }else{
                    $(this).find('h3.hndle').removeClass('options-required');
                }
            });
        }

    };

    $.macho.conditionals = function() {

        $(document).on('macho/field/change', function(e, el){
            var changed_field = $(el).closest('tr');
            $.each(macho.conditionals, function(index, conditional){
                var conditional_field = $('tr#field-'+index);

                //dont proccess ourself
                if(conditional_field.attr('id') == changed_field.attr('id')){
                    return;
                }

                //or loop
                var $globalshow = false;
                $.each(conditional, function(inx, cond){
                    //and loop
                    var $show = true;
                    $.each(cond, function(_index, _cond){
                        //hide if conditional is hidden
                        if($('tr#field-'+_cond.id).hasClass('condition-failed')){
                            $show = false;
                            return false;
                        }
                        var val = null;
                        val = $.macho.filter.apply('macho/field/'+_cond.field_type+'/value', _cond.id);
                        //console.log(_cond.id+':'+val);
                        if($.macho.filter.apply('macho/validate/'+_cond.type, {value: _cond.value, supplied: val}) === false){
                            $show = false;
                        }
                    });
                    if($show === true){
                        $globalshow = true;
                        return false;
                    }
                    $show = false;
                });

                if($globalshow === false){
                    conditional_field.closest('tr').fadeOut().addClass('condition-failed');
                }else{
                    $globalshow = false;
                    conditional_field.closest('tr').fadeIn().removeClass('condition-failed');
                }
            });
        });

        //trigger field change on basic field changes - specific triggers added via field js
        $(document).on('input change', 'input', function(){
            $(document).trigger('macho/field/change', $(this));
        });

    };

    $(document).ready(function() {

        //add field layout options
        $.macho.layout();

        //add toggles to options pages
        if(macho.context == 'page'){

            //make meta boxes function correctly
            postboxes.add_postbox_toggles(pagenow);

            //add restore action for pages
            $.macho.restore();
        }

        //run validation
        $.macho.required();

        //run conditions
        $.macho.conditionals();

        $(document).on('macho/validation_failed', function(){
            $.macho.required_postboxes();
        });

        //hide spinner and show fields on creation
        $(document).on('macho/created_fields', function(){
            $('.options-spinner').each(function(){
                $(this).fadeOut('fast', function(){
                    $(this).closest('div').find('.options-table').fadeIn();
                    $(this).closest('div').find('.options-section-locked,.options-field-locked').fadeIn();
                });
            });
        });

        $(document).on('click', '.options-tabs-tabs > a', function(){
            if($(this).hasClass('active')){
                return false;
            }
            var target = $(this).attr('data-tab');
            var container = $(this).closest('.inside');
            container.find('.options-tabs-tabs a.active').removeClass('active');
            $(this).addClass('active');
            container.find('.options-tabs-tables .options-tab.active').removeClass('active');
            container.find('.options-tabs-tables .options-tab#'+target).addClass('active');
            return false;
        });

        //field vallidation
        $(document).on('macho/validate/text macho/validate/password macho/validate/email macho/validate/url macho/validate/number macho/validate/textarea macho/validate/date macho/validate/media macho/validate/gallery', function(e, id, data){
            var msg = data.msg;
            if(!$.trim($('.options-table #' + id).val()).length){
                if(!$('.options-table #' + id).closest('tr').hasClass('options-required')){
                    $('.options-table #' + id).closest('tr').addClass('options-required');
                    $('.options-table #' + id).closest('td').append('<p class="description options-error">'+msg+'</p>');
                }
                $.macho.passes = false;
            }
            $(document).on('input', '.options-table #' + id, function(){
                if($.trim($(this).val()).length > 0){
                    if($(this).closest('tr').hasClass('options-required')){
                        $(this).closest('tr').removeClass('options-required');
                        $('p.options-error', $(this).closest('td')).remove();
                    }
                }else{
                    if(!$(this).closest('tr').hasClass('options-required')){
                        $(this).closest('tr').addClass('options-required');
                        $(this).closest('td').append('<p class="description options-error">'+msg+'</p>');
                    }
                }
            });
        });

        $(document).on('macho/validate/checkbox', function(e, id, data){
            var msg = data.msg;
            if(!$('.options-table #field-' + id + ' input[type="checkbox"]:checked').length){
                if(!$('.options-table #field-' + id + ' input[type="checkbox"]').closest('tr').hasClass('options-required')){
                    $('.options-table #field-' + id + ' input[type="checkbox"]').closest('tr').addClass('options-required');
                    $('.options-table #field-' + id + ' input[type="checkbox"]').closest('td').append('<p class="description options-error">This is a required field!</p>');
                }
                $.macho.passes = false;
            }
            $(document).on('change', '.options-table #field-' + id + ' input[type="checkbox"]', function(){
                if($('input[type="checkbox"]:checked', $(this).closest('td')).length > 0){
                    if($(this).closest('tr').hasClass('options-required')){
                        $(this).closest('tr').removeClass('options-required');
                        $('p.options-error', $(this).closest('td')).remove();
                    }
                }else{
                    if(!$(this).closest('tr').hasClass('options-required')){
                        $(this).closest('tr').addClass('options-required');
                        $(this).closest('td').append('<p class="description options-error">'+msg+'</p>');
                    }
                }
            });
        });

        $(document).on('macho/validate/editor', function(e, id, data){
            var msg = data.msg;
            if(typeof(tinyMCE) == "object"){
                tinyMCE.triggerSave();
                var eid = $('#field-' + id).find('.wp-editor-area').attr('id'),
                    editor = tinyMCE.get( eid );
                if( editor && !editor.getContent() ){
                    if(!$('.options-table #' + id).closest('tr').hasClass('options-required')){
                        $('.options-table #' + id).closest('tr').addClass('options-required');
                        $('.options-table #' + id).closest('td').append('<p class="description options-error">'+msg+'</p>');
                    }
                    $.macho.passes = false;
                }
            }
        });

    });

    //trigger create fields after 10 milliseconds
    $(window).load(function(){

        setTimeout(function(){
            // setup fields
            $(document).trigger('macho/create_fields', [ $('.wrap') ]);
            // after setup
            $(document).trigger('macho/created_fields', [ $('.wrap') ]);
            $(document).trigger('macho/field/change', [ $('.wrap') ]);

            $('.wrap').find('.options-tabs-tabs,.options-section-description').fadeIn();
            $('.wrap').find('.options-tabs-tabs a').first().addClass('active');
            $('.wrap').find('.options-tabs-tables .options-tab').first().addClass('active');

        }, 10);

    });


})(jQuery);