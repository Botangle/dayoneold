/**
 * Created by mjl on 26/08/14. <mling@str8-4ward.com>
 * Got broken code working, then pulled it out into a js file since used on multiple pages
 */
var suburl = "/subject/search";
var datasubject = "";
jQuery(function() {
    jQuery.getJSON(suburl, function(response) {
        datasubject = response;
    })

});

jQuery(function() {
    function split(val) {
        return val.split(/,\s*/);
    }
    function extractLast(term) {
        return split(term).pop();
    }
    jQuery("#UserSubject").autocomplete({
        minLength: 0,
        source: function(request, response) {
            // delegate back to autocomplete, but extract the last term
            response(jQuery.ui.autocomplete.filter(
                datasubject, extractLast(request.term)));
        },
        focus: function() {
            // prevent value inserted on focus
            return false;
        },
        select: function(event, ui) {
            var terms = split(this.value);
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push(ui.item.value);
            // add placeholder to get the comma-and-space at the end
            terms.push("");
            this.value = terms.join(", ");
            return false;
        }
    });
});