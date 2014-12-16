/**
 * Created by martyn on 08 September 2014.
 * Moved the code out of the original whiteboard view and then parameterized existing functions
 */

function startCount(lessonId, roleType, syncFrequency){
    timer = setInterval(function() { count(lessonId, roleType, syncFrequency); },1000);
}

function exitLesson(lessonId, roleType){

    if(roleType==4){
        var r = window.confirm("Thanks, we'll pay your expert from your credit balance now.  Your receipt will be on the next page.");
        if(r){
            jQuery("#exitlesson").attr('disabled','disabled');
            jQuery.post(
                BotangleBasePath+"lesson/"+lessonId+"/updatetimer",
                {
                    _token: jQuery("input[name=_token]").val(),
                    roleType: roleType,
                    secondsUsed: jQuery("#secondsUsed").val(),
                    status: "finished"
                },
                function(data,v){
                    var response = data;
                    clearInterval(timer);
                    if (response.status == 'finished'){
                        location.href = (BotangleBasePath+'lesson/'+lessonId+'/payment');
                    } else {
                        location.href= (BotangleBasePath+'user/lessons');
                    }
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
                    roleType: roleType,
                    secondsUsed: jQuery("#secondsUsed").val(),
                    status: "finished"
                },
                function(data,v){
                    var response = data;
                    clearInterval(timer);
                    if (response.status == 'finished'){
                        location.href = (BotangleBasePath+'lesson/'+lessonId+'/payment');
                    } else {
                        location.href= (BotangleBasePath+'user/lessons');
                    }
                    return false;
                }
            );
        }
    }
}

function count(lessonId, roleType, syncFrequency){

    var sync_status = jQuery("#status").val();
    if (sync_status == 'finished'){
        clearInterval(timer);
        return false;
    }

    var absTime = jQuery("#absTime").val();
    jQuery("#absTime").val(++absTime);

    switch(sync_status){
        case 'syncing':
            decrementCountdown();
            break;
        case 'starting':
            decrementCountdown();
            // If the countdown has ended
            if (jQuery("#countdown").val() == 0){
                jQuery("#status").val('active');
            }
            break;
        case 'active':
            var seconds_used = jQuery("#secondsUsed").val();
            jQuery("#secondsUsed").val(++seconds_used)
            jQuery("#realtime").text(formattedTimeFromSecs(seconds_used));
            break;
    }

    // Contact the server every syncFrequency to either sync the start (or restart) of the lesson
    //  or update the db with the lesson time used up
    if(absTime % syncFrequency == 0){
        jQuery.post(
            BotangleBasePath+"lesson/"+lessonId+"/updatetimer",
            {
                _token: jQuery("input[name=_token]").val(),
                roleType: roleType,
                secondsUsed: jQuery("#secondsUsed").val(),
                status: jQuery("#status").val()
            },
            function(data,v){
                var response = data;
                if(response.lessonComplete == 1){
                    if(roleType == 4){
                        alert("Your expert has finished the lesson. Redirecting you to the payment page now ...");
                        location.href= (BotangleBasePath+'lesson/'+lessonId+'/payment');
                    } else {
                        alert("Your student has finished the lesson. Redirecting you to the payment page now ...");
                        location.href= (BotangleBasePath+'lesson/'+lessonId+'/payment');
                    }
                    clearInterval(timer);
                    return false;
                } else {
                    jQuery('.price-area').show();
                    jQuery('.price-area span').html(response.newPrice);
                    var prev_status = jQuery("#status").val();
                    jQuery("#status").val(response.status);
                    switch(response.status){
                        case 'syncing':
                        case 'starting':
                            jQuery("#countdown").val(response.countdown);
                        case 'waiting':
                            if (prev_status == 'active'){
                                // Sync the displayed lesson time with the last recorded db value
                                // Note: only the student usually updates the lesson time
                                jQuery("#secondsUsed").val(response.totalTime)
                                jQuery("#realtime").text(formattedTimeFromSecs(response.totalTime))
                            }
                    }
                }
            }
        ).fail(function() {
                jQuery("#status").val('connection-problem');
            });
    }

    updateStatusDisplay(syncFrequency);
}

function decrementCountdown(){
    var countdown = jQuery("#countdown").val();
    if (countdown > 0){
        jQuery("#countdown").val(--countdown);
    }
}

function updateStatusDisplay(syncFrequency){
    // TODO Manipulate the classes on the div to provide enhanced presentation
    switch(jQuery("#status").val()){
        case 'waiting':
            jQuery("#sync-status").removeClass().addClass('alert alert-danger');
            jQuery("#sync-status").text('Waiting for other user...');
            break;
        case 'syncing':
            var countdown = jQuery("#countdown").val();
            jQuery("#sync-status").removeClass().addClass('alert alert-info');
            jQuery("#sync-status").text('Connecting (estimated start in ' + jQuery("#countdown").val() + ' seconds)');
            break;
        case 'starting':
            countdown = jQuery("#countdown").val();
            if (countdown > 1){
                var secondsText = countdown + ' seconds';
            } else {
                secondsText = countdown + ' second';
            }
            jQuery("#sync-status").removeClass().addClass('alert alert-info');
            jQuery("#sync-status").text('Starting in ' + secondsText);
            break;
        case 'active':
            jQuery("#sync-status").removeClass().addClass('alert alert-success');
            jQuery("#sync-status").text('In Progress');
            break;
        case 'finished':
            jQuery("#sync-status").removeClass().addClass('alert alert-success');
            jQuery("#sync-status").text('Finished');
            break;
        case 'connection-problem':
            jQuery("#sync-status").removeClass().addClass('alert alert-danger');
            jQuery("#sync-status").text("Connection problem. Retrying...");
    }
}

function formattedTimeFromSecs(totalSecs){
    var hours = parseInt( totalSecs / 3600 );
    var minutes = parseInt( totalSecs / 60 ) % 60;
    var seconds = totalSecs % 60;

    return zeroPad(hours) +":" + zeroPad(minutes) + ":" + zeroPad(seconds);
}

function zeroPad(digit){
    var zpad = digit;
    if (digit < 10) {
        zpad = "0" + digit;
    }
    return zpad;
}
