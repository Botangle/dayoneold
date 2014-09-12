/**
 * Created by mjl on 26/08/14. <mling@str8-4ward.com>
 * Got broken code working, then pulled it out into a js file since used on multiple pages
 * Not the most elegant, but an improvement on having slightly different duplicate code spread
 * through multiple views.
 */
var suburl = "/subject/search";

var $j = jQuery.noConflict();
datasubject = "";
jQuery(function() {
    jQuery.getJSON(suburl, function(response) {
        datasubject = response;
        $j("#searchvalue,#keyword").autocomplete({
            minLength: 0,
            source: datasubject,
            focus: function(event, ui) {

                $j("#searchvalue,#keyword").val(ui.item.label);
                return false;
            },
            select: function(event, ui) {

                $j("#searchvalue,#keyword").val(ui.item.label);
                return false;
            }
        })
            .data("ui-autocomplete")._renderItem = function(ul, item) {
            return $j("<li>")
                .append("<a>" + item.label + "</a>")
                .appendTo(ul);
        };
    })
});
