/*
 * This is JavaScript common to the entire site.
 *
 * It's fine as it is, but if this site is going to be more interactive, then
 * this will need to be refactored. Perhaps we should implement an MVC-style
 * framework such as Backbone.js... see [TodoMVC](http://todomvc.com/)
 */

$(document).ready(function () {
    /*jQuery("#step1").click(function(e){
     step1();
     e.stopPropagation();
     })*/
    jQuery("#addjourny2").click(function (e) {

        if (jQuery(this).is(':checked') == true) {
            jQuery("#addjourny").css('display', '');
            jQuery("#addition_journey_from").val('');
        } else {
            jQuery("#addjourny").css('display', 'none');

        }
    });
    jQuery("#booknow4,#booknow6").click(function (e) {
        jQuery("#bookcarid").val(jQuery(this).attr('data-attr'))
    })

});


var customeCheck = true;
var ajaxSubmit = false;
var customdata = {
    checkValidation: function (form) {
        var validation = "";

        switch (form) {
            case "step1" :
                return true;
                break;
            case "BookingdetailFormStep2Form" :

                if (jQuery("#acceptterm").is(':checked') == false) {
                    return false;
                } else {
                    return true;
                }
                break;

            default :
                break;
        }
    },

} 