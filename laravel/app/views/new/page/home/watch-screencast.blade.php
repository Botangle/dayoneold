<script type="text/javascript" charset="utf-8">
	TB.on("exception", exceptionHandler);

	var apiKey = '{{ Config::get('services.openTok.apiKey') }}';
	var sessionId = "{{ $model->opentok_session_id }}";
	var token = "{{ $model->openTokToken }}";

	var session = TB.initSession(sessionId); // Replace with your own session ID. See https://dashboard.tokbox.com/projects
	session.once("sessionConnected", sessionConnectedHandler);
	session.once("streamCreated", streamCreatedHandler);
	session.connect(apiKey, token);

	function sessionConnectedHandler(event) {
		subscribeToStreams(event.streams);
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
		div.setAttribute('class', 'live-stream');
		$('#live-stream-{{ $model->id }}').html(div);
		theirStream = session.subscribe(stream, 'stream' + stream.streamId);
	}
	function exceptionHandler(event) {
		console.error("Exception: " + event.code + "::" + event.message);
	}

</script>

<div id="live-stream-{{ $model->id }}">Loading ...</div>
