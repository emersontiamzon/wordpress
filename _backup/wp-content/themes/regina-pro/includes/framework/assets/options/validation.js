(function($) {
    'use strict';
    
    $.macho = $.macho || {};

    //required
    $.macho.filter.add('macho/validate/required', function(obj){
        if(obj.supplied === ''){
            return false;
        }
    });

    //equals
    $.macho.filter.add('macho/validate/==', function(obj){
        if(obj.value !== obj.supplied){
            return false;
        }
    });

    //nequal
    $.macho.filter.add('macho/validate/!=', function(obj){
        if(obj.value === obj.supplied){
            return false;
        }
    });

    //contains
    $.macho.filter.add('macho/validate/contains', function(obj){
        if(obj.supplied.indexOf(obj.value) === -1){
            return false;
        }
    });

    //!contains
    $.macho.filter.add('macho/validate/!contains', function(obj){
        if(obj.supplied === '' || obj.supplied.indexOf(obj.value) >= 0){
            return false;
        }
    });

    //starts with
    $.macho.filter.add('macho/validate/starts_with', function(obj){
        if(obj.supplied.slice(0, obj.value.length) !== obj.value){
            return false;
        }
    });

    //ends with
    $.macho.filter.add('macho/validate/ends_with', function(obj){
        if(obj.supplied.slice(-obj.value.length) !== obj.value){
            return false;
        }
    });

    //more than
    $.macho.filter.add('macho/validate/>', function(obj){
        if(obj.supplied === '' || parseInt(obj.supplied) <= parseInt(obj.value)){
            return false;
        }
    });

    //more than or equal to
    $.macho.filter.add('macho/validate/>=', function(obj){
        if(obj.supplied === '' || parseInt(obj.supplied) < parseInt(obj.value)){
            return false;
        }
    });

    //less than or equal to
    $.macho.filter.add('macho/validate/<=', function(obj){
        if(obj.supplied === '' || parseInt(obj.supplied) > parseInt(obj.value)){
            return false;
        }
    });

    //less than
    $.macho.filter.add('macho/validate/<', function(obj){
        if(obj.supplied === '' || parseInt(obj.supplied) >= parseInt(obj.value)){
            return false;
        }
    });

    //between
    $.macho.filter.add('macho/validate/between', function(obj){
        var values = obj.value.split('|');
        if(obj.supplied === '' || parseInt(obj.supplied) < parseInt(values[0])){
            return false;
        }
        if(parseInt(obj.supplied) > parseInt(values[1])){
            return false;
        }
    });

    //not between
    $.macho.filter.add('macho/validate/!between', function(obj){
        var values = obj.value.split('|');
        if(obj.supplied === '' || parseInt(obj.supplied) > parseInt(values[0]) && parseInt(obj.supplied) < parseInt(values[1])){
            return false;
        }
    });

})(jQuery);