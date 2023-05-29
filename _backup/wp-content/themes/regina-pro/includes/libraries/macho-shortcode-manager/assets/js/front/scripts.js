 function teamsettings() {

  jQuery('.mt-team').parent().addClass('align-center');
  jQuery('.mt-team').on('mouseenter', function(){
    jQuery(this).find('.mt-team-description').addClass('mt-is-visible')
  }).on('mouseleave', function(){
     jQuery(this).find('.mt-team-description').removeClass('mt-is-visible');
  });
}

jQuery(document).ready(function($) {

	teamsettings();

	// Animate Pie Charts
	if(typeof $.fn.easyPieChart !== 'undefined') {
        $('.mt-chart').each(function(){
           
                var $t = $(this),
                    n = $t.parent().width(),
                    l = "round";
                
                if ($t.attr("data-lineCap") !== undefined) {
                    l = $t.attr("data-lineCap");
                } 

                // Set dimensions for Pie Charts
                $(this).css({ "width" : n, "height": n, "line-height" : n + "px", "font-size": n/3  });
                

            $t.easyPieChart({
                lineCap: l,
                lineWidth: $t.attr("data-lineWidth"),
                size: n,
                barColor: $t.attr("data-barColor"),
                trackColor: $t.attr("data-trackColor"),
                scaleColor: "transparent"
            });
        });
    }

    // LazyLoad - delays loading of images in long paged websites
    // https://github.com/tuupola/jquery_lazyload
    if (typeof $.fn.lazyload !== 'undefined') {

        $('.lazy').each(function() {

            $(this).show().lazyload({
                effect : "fadeIn",
                skip_invisible : true
            })

        });

    }

})