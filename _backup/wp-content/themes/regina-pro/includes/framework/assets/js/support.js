(function($) {


    'use strict';

    /* ==========================================================================
     When document is ready, do
     ========================================================================== */
    $(document).ready(function() {

        //$.macho = $.macho || {},
           $(document).on("click", ".macho-support-form a", function() {

            var b = $(this).closest(".macho-support-form");

            return b.find(".response ul li").fadeOut("slow", function() {
                $(this).remove()
            }), b.find("img").fadeIn("slow", function() {
                $.post(b.find("#macho-support-form-url").val(), $(":input", b).serialize(), function(c) {
                    var d = $.parseJSON(c);
                    b.find("img").fadeOut("slow"), b.find(".response ul").append('<li class="' + d.status + '"><p>' + d.message + "</p></li>"), setTimeout(function() {
                        b.find(".response ul li").fadeOut("slow", function() {
                            $(this).remove()
                        })
                    }, 3e3)
                })
            }).css("display", "block")
        })

    });

})(window.jQuery);