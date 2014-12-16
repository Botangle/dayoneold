/**
 * Code pulled out from the original Botangle and placed in a file.
 * TODO: Bugs to fix: strength indicator doesn't adjust correctly if you delete characters
 */

jQuery(document).ready(function(){
    jQuery(".btn-reset").click(function(){
            jQuery(".security").removeClass().addClass("security");
        });

    jQuery("#UserPassword").keyup(function(){
        jQuery(".security").addClass(checkStrength(jQuery("#UserPassword").val()));
    });

    function checkStrength(password){
        var strength = 0;
        if (password.length < 6) {
            return "weak";
        }
        if (password.length > 7) strength += 1;
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1;
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1;
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1;
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
        if (strength < 2 ){
            return "weak1";
        }
        else if (strength == 2){
            return "Good";
        }
        else{
            return "Strong";
        }
    }
});