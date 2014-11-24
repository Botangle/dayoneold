/**
 * Code pulled out from the original Botangle and placed in a file.
 * TODO: Bugs to fix: strength indicator doesn't adjust correctly if you delete characters
 */

jQuery(document).ready(function(){
    var lengthCheck = false;
    jQuery(".btn-reset").click(function(){
        jQuery("#username-result").removeClass().text('');
    });

    jQuery("#username").keyup(function(){
        checkUsername(jQuery("#username").val());
    });

    function checkUsername(username){
        if (username.length > 3){
            lengthCheck = true;
            jQuery.post(
                BotangleBasePath+"user/validateUsername",
                {
                    _token: jQuery("input[name=_token]").val(),
                    username: username
                },
                function(data,v){
                    jQuery('#username-result').text(data.text).removeClass().addClass(data.class);
                }
            );

        } else {
            if (lengthCheck){
                jQuery('#username-result').text("Username must be longer than 3 characters.")
                    .removeClass().addClass('alert alert-danger');
            }
        }
    }
});