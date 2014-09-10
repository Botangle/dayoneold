{{ Html::style('css/whiteboard.css') }}
{{ Html::script('https://swww.tokbox.com/webrtc/v2.0/js/TB.min.js') }}

<script type="text/javascript" charset="utf-8">
    TB.addEventListener("exception", exceptionHandler);

    var apiKey = '{{ Config::get('services.openTok.apiKey') }}';
    var sessionId = "{{ $model->opentok_session_id }}";
    var token = "{{ $model->openTokToken }}";

    var session = TB.initSession(sessionId); // Replace with your own session ID. See https://dashboard.tokbox.com/projects
    session.addEventListener("sessionConnected", sessionConnectedHandler);
    session.addEventListener("streamCreated", streamCreatedHandler);
    session.connect(apiKey, token);

    function sessionConnectedHandler(event) {
        subscribeToStreams(event.streams);
        var properties = {
            width: 75,
            height: 56,
            style: {
                buttonDisplayMode: 'off', // this is to disable the mute button
            }
        };
        var publisher = TB.initPublisher(apiKey, 'small-stream', properties);
        session.publish(publisher);
    }

    function streamCreatedHandler(event) {
        subscribeToStreams(event.streams);
    }

    function subscribeToStreams(streams) {
        for (var i = 0; i < streams.length; i++) {
            var stream = streams[i];
            if (stream.connection.connectionId != session.connection.connectionId) {
                displayOtherStream(stream);
            }
        }
    }

    function displayOtherStream(stream) {
        var div = document.createElement('div');
        div.setAttribute('id', 'stream' + stream.streamId);
        div.setAttribute('class', 'small-stream');
        $('#videoChatBox').html(div);
        theirStream = session.subscribe(stream, 'stream' + stream.streamId);
    }

    function exceptionHandler(event) {
        alert("Exception: " + event.code + "::" + event.message);
    }
</script>

<div class="video-chat">
    @if($model->userIsStudent(Auth::user()))
        <div id="videoChatBox">Your expert</div>
        <div id="small-stream">You</div>
    @elseif($model->userIsTutor(Auth::user()))
        <div id="videoChatBox">Your student</div>
        <div id="small-stream">You</div>
    @endif
</div>
