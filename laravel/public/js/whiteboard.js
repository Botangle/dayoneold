/**
 * Created by martyn on 08 September 2014.
 * Moved the code out of the original whiteboard view and then parameterized existing functions
 */

function startCount(lessonId, roleType){
    timer = setInterval(function() { count(lessonId, roleType); },1000);
}

function exitLesson(lessonId, roleType){

    updatetime = 0;

    if(roleType==4){
        var r = window.confirm("Thanks, we'll pay your expert from your credit balance now.  Your receipt will be on the next page.");
        if(r){
            jQuery("#exitlesson").attr('disabled','disabled');
            jQuery.post(
                BotangleBasePath+"lesson/"+lessonId+"/updatetimer",
                {
                    _token: jQuery("input[name=_token]").val(),
                    time: 1,
                    roleType: roleType,
                    completeLesson: 1
                },
                function(data,v){
                    clearInterval(timer);
                    location.href= (BotangleBasePath+'lesson/'+lessonId+'/payment/?role=student');
                    return false;
                }
            );
        }
    }
    if(roleType==2){
        var r = window.confirm("Are you sure you want to complete the lesson early?");
        if(r) {
            jQuery("#exitlesson").attr('disabled','disabled');
            jQuery.post(
                BotangleBasePath+"lesson/"+lessonId+"/updatetimer",
                {
                    _token: jQuery("input[name=_token]").val(),
                    time: 1,
                    roleType: roleType,
                    completeLesson: 1
                },
                function(data,v){
                    clearInterval(timer);

                    location.href = (BotangleBasePath+'lesson/'+lessonId+'/payment/?role=tutor');
                    return false;
                }
            );
        }
    }
}

function count(lessonId, roleType){

    if( jQuery("#realtime").text()== jQuery("#max").text()){
        clearInterval(timer);
        return false;
    }
    var time_shown = jQuery("#realtime").text();
    var time_chunks = time_shown.split(":");
    var hour, mins, secs,updatetime;

    hour=Number(time_chunks[0]);
    mins=Number(time_chunks[1]);
    secs=Number(time_chunks[2]);
    updatetime=Number(time_chunks[2]);
    secs++;
    updatetime++;
    if (secs==60){
        secs = 0;
        mins=mins + 1;
        }
    if (mins==60){
        mins=0;
        hour=hour + 1;
        }
    if (hour==13){
        hour=1;
        }

    // Update the db every minute with how much time has been spent in the lesson
    if(updatetime%60==0){
        updatetime = 0;
        jQuery.post(
            BotangleBasePath+"lesson/"+lessonId+"/updatetimer",
            {
                _token: jQuery("input[name=_token]").val(),
                time: 1,
                roleType: roleType
            },
            function(data,v){
                var response = data;
                if(response.lessonComplete == 1){
                    if(roleType == 4){
                        alert("Your expert has finished the lesson. Redirecting you to the payment page now ...");
                        location.href= (BotangleBasePath+'lesson/'+lessonId+'/payment/?role=student');
                    } else {
                        alert("Your student has finished the lesson. Redirecting you to the payment page now ...");
                        location.href= (BotangleBasePath+'lesson/'+lessonId+'/payment/?role=tutor');
                    }
                    clearInterval(timer);
                    return false;
                } else {
                    jQuery('.price-area').show();
                    jQuery('.price-area span').html(response.newPrice);
                }
            }
        );
    }
    jQuery("#realtime").text(plz(hour) +":" + plz(mins) + ":" + plz(secs));
}

function plz(digit){
    var zpad = digit + '';
    if (digit < 10) {
    zpad = "0" + zpad;
    }
    return zpad;
}
