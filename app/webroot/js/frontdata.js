<!--
var classprefix ="verify";
var mustCheck = true;
var validatefrom = {
    validation : function (formName,customfunction){
       var formId = formName; 
	
       jQuery("FORM").submit(function(event){
            if(mustCheck) { 
			
                    if( !validatefrom.checkForm(this) ) {
                            event.preventDefault();
                    }else{   
						if(customfunction!=undefined){ 
							if(!customdata.checkValidation(formId)){ 
								event.preventDefault();
							}
						}
					}
		} else {
			mustCheck = !mustCheck;
		}
       })
      
    },
    checkForm : function (form) { 
        var send = true;
        var password = '';
        radioGroups = Array();
        checkboxGroups = Array();
		
        jQuery( form ).removeClass ( "haserrors" );
        inputs = jQuery(form).find('INPUT[class*="' + classprefix + '"]:visible, .required INPUT:visible, .required TEXTAREA:visible, .required SELECT:visible');
		 
        jQuery.each(inputs, function(i, val) {  
            input = jQuery(val);
            if( input.attr('offsetWidth') != 0 ) {
                var tag = input.get(0).tagName;
                var inputType = '';
			 
                if( tag == 'INPUT' ) {
                    inputType = input.attr('type');
                } else if( tag == 'SELECT' ) {
                    inputType = 'select-one';
                } else if( tag == 'TEXTAREA' ) {
                    inputType = 'textarea';					
                }
               
                switch(inputType) {
                   
                    case 'select-one':  
					if( input.val() == '' || input.val() == null) { 
						if( send ) validatefrom.moveTo(input);
						validatefrom.showErrorOn( input );
						send = false;
					}
				break;

                    case 'select-multiple':  
                            if( input.get(0)[ input.attr('selectedIndex') ].value == '' || input.val() == null) {  
                                    if( send ) validatefrom.moveTo(input);
                                    validatefrom.showErrorOn( input );
                                    send = false;
                            }
                    break;
                    case 'file':
                            if( !validatefrom.isFilled(input) ) {
                                    if( send ) validatefrom.moveTo(input);
                                    validatefrom.showErrorOn( input );
                                    send = false;
                            }
                    break;
                    case 'password':
                         
                             
					if( input.hasClass( classprefix + 'PasswordConfirm' ) ) {
                                         
						if( input.val() != password ) {
							if( send ) validatefrom.moveTo(input);
							validatefrom.showErrorOn( input );
							send = false;
						}
						break;
					} else {
						password = input.val();
                                                
					}
                    case 'textarea':
                    case 'text':
                         
                        var isFieldValid = validatefrom.isValid(input); 
                       
                        var fieldOK = true;
                        if ( validatefrom.isRequired(input) && !isFieldValid ){ fieldOK = false; }
                        if ( validatefrom.isFilled(input) && !isFieldValid  ){ fieldOK = false; }
                        
                        if( !fieldOK ){ 
                        if( send ) validatefrom.moveTo(input);
                            validatefrom.showErrorOn( input );
                            send = false;
                        }
                    break;
                    default:
                    break;
                }
           }		
    });
   
    
    // Add class haserrors to each row that has at least one field with errors.
    var rows = $(form).find('DIV.row');
    jQuery.each(rows, function(i, val) {
    var row = $(val);
        validatefrom.rowHasErrors(row);
    });

    return send;
    },
   isValid : function ( input ) {
	if( !validatefrom.isFilled(input) ) return false;
	string = input.attr('class');
	value = input.val();
	 
	if(string==undefined){
		input.attr('class','n')
		string = input.attr('class');
	} 
	start = string.indexOf(classprefix);	
	type = '';
	result = true; 
	while(result) {
		if( 
			start == -1 || 
			string.charAt( (start+classprefix.length) ) == ' ' || 
			string.charAt( (start+classprefix.length) ) != string.charAt( (start+classprefix.length) ).toUpperCase() 
		) {	
			break;
		} else {
			for( i=start; i < string.length; i++ ) {
				if(string.charAt(i) == ' ') {
					break;
				}
				type += string.charAt(i);
			}
			if( !validatefrom.isTypeValid( input, type, value ) ) {
				result = false;
				break;
			}
			start = string.indexOf(classprefix,start+1);
		}
	}
	return result;
},
isFilled : function (input) {
	hintText = '';
	if(typeof(input.attr('title')) !== 'undefined') {
		//clear HINTs before validation
                if( input.attr('placeholder').indexOf("**")==0) {
			var hintText = input.attr('placeholder');
		}
		else if( input.attr('title').indexOf("**")==0) {
			var hintText = input.attr('title').substring(2);
		} else if( input.attr('title').indexOf("*")==0) {
			var hintText = input.attr('title').substring(1);
		}//end clear hints
		return input.val() != hintText && input.val() != '' ;
	} 
	return input.val();
},
isRequired : function (input) {
	return input.parents( ".required" ).length != 0;
},
// Set your validation rules 
isTypeValidExt : function ( input, classprefix, type, value ) {
	/* RULE EXAMPLE (Accept only integer values)
	if( type == classprefix + 'Integer' ) {
		return ( ( value.match(/^[\d|,|\.|\s]*$/) ) && ( value != '' ) );
	} 
	*/
	return true;
},
isTypeValid : function ( input, type, value ) {

	if( type == classprefix + 'Text' ) {
		return true;
	}
	
	if( type == classprefix + 'Integer' ) {
		return ( ( value.match(/^[\d|,|\.|\s]*$/) ) && ( value != '' ) );
	}
	
	if( type == classprefix + 'Url' ) {
		return ( value.match( /^(https?:\/\/)?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?(([0-9]{1,3}\.){3}[0-9]{1,3}|([0-9a-z_!~*'()-]+\.)*([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.[a-z]{2,6})(:[0-9]{1,4})?((\/?)|(\/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+\/?)$/ ) );
	}
	
	if( type == classprefix + 'MultipleWords' ) {
		return value.match(/^.*[^^]\s[^$].*$/);
	}
	
	if( type == classprefix + 'Mail' ) {
		if( value.indexOf("@example.com")>-1){return false;};
		var emailFilter=/^.+@.+\..{2,}$/;
		//var emailFilter=/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/;
		var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/;
		if(!(emailFilter.test(value))||value.match(illegalChars)){return(false);}else{return (true);}
		return false;
	}

	if(typeof validatefrom.isTypeValidExt == 'function') {
		fr = validatefrom.isTypeValidExt( input, classprefix, type, value );
		if( validatefrom.isTypeValidExt( input, classprefix, type, value ) === null ) {
			return false;
		} else {
			return fr;
		}
	}
	return true;
},
moveTo : function ( input ) {
	var targetOffset = input.offset().top - 90;
	jQuery('html,body').animate({scrollTop: targetOffset}, 200 );
	/*if( !jQuery.browser.msie ) {
		input.get(0).focus();
	}*/
},

showErrorOn : function ( input ) {
/* BUG FIXED FOR IE: when submited it auto focuses to the first required field, so the hint and red box aren't there. Might be confusing to a user!

	input.bind('focus.rmErrorClass', function(){
		rmErrorClass( this );
	});
*/
	input.bind('mousedown.rmErrorClass', function(){
		validatefrom.rmErrorClass( this );
	});
	input.bind('keydown.rmErrorClass', function(){
		validatefrom.rmErrorClass( this );
	});	
	input.bind('change.rmErrorClass', function(){
		validatefrom.rmErrorClass( this );
	});	
	input.bind('focus.rmErrorClass', function(){
		validatefrom.rmErrorClass( this );
	});	
	input.bind('blur.rmErrorClass', function(){  
		validatefrom.rmErrorClass( this );
	}); 
         
	input.addClass( "error" );
	input.closest( ".required, .field" ).addClass( "error" );
},
rmErrorClass : function ( elm ) {
	var etag=jQuery(elm).parents(".error");
	var eform = jQuery(elm).parents( 'FORM' );
	jQuery(elm).removeClass("error");
	jQuery(elm).unbind('.rmErrorClass'); //no further clicks will trigger rmErrorClass();
	if(etag){ jQuery(etag).removeClass( "error" ); };
	var row = jQuery(elm).closest('.row.haserrors');
	validatefrom.rowHasErrors(row);
},
rowHasErrors : function (row) {
	var haserrors = jQuery(row).find('.error');
	if( haserrors.length > 0 ) {
		jQuery(row).addClass('haserrors');
		return true;
	}
	jQuery(row).removeClass('haserrors');
	return false;
}                     
    
}

function changeMemberType(){
 jQuery("#usertype").change(function(e){ 
      if(this.value=='buyer'){ 
          jQuery("#dealerData").css('display','none');
          jQuery("#bueryData").css('display','');
      }
      if(this.value=='dealer'){
          jQuery("#dealerData").css('display','');
          jQuery("#bueryData").css('display','none');
      }
 })
}
 
-->