{{-- Html::style('css/whiteboard.css') --}}
{{ Html::script('//static.opentok.com/v2/js/opentok.min.js') }}

<script type="text/javascript" charset="utf-8">
	TB.on("exception", exceptionHandler);

	var apiKey = '{{ Config::get('services.openTok.apiKey') }}';
	var sessionId = "{{ $model->opentok_session_id }}";
	var token = "{{ $model->openTokToken }}";

	var session = TB.initSession(sessionId); // Replace with your own session ID. See https://dashboard.tokbox.com/projects
	session.once("sessionConnected", sessionConnectedHandler);
//	session.once("streamCreated", streamCreatedHandler);
	session.connect(apiKey, token);

	function sessionConnectedHandler(event) {
//		subscribeToStreams(event.streams);
		var properties = {
			width: $("#broadcast-stream").width(),
			height: $("#broadcast-stream").width() / 2,
			style: {
//				buttonDisplayMode: 'off', // this is to disable the mute button
			}
		};
		var publisher = TB.initPublisher(apiKey, 'broadcast-stream', properties);
		session.publish(publisher);
	}

	// Erik's ID: ekceebhmfflghhcllmlnaombhgomnkcf
	OT.registerScreenSharingExtension('chrome', 'gkcgdapfanaaiekajdfdhclhcjhldlco');

	OT.checkScreenSharingCapability(function(response) {
		if(!response.supported || response.extensionRegistered === false) {
			// This browser does not support screen sharing.
			alert("Your browser doesn't support screen sharing");
		} else if(response.extensionInstalled === false) {
			// Prompt to install the response.extensionRequired extension.
			alert("Please install the required extension");
		} else {
			// Screen sharing is available. Publish the screen.
			OT.initPublisher('broadcast-stream',
					{videoSource: 'screen'},
					function(error) {
						if (error) {
							alert("something went wrong earlier in here");
							// Look at error.message to see what went wrong.
						} else {
							session.publish(publisher, function(error) {
								if (error) {
									alert("something went wrong in here");
									// Look error.message to see what went wrong.
								}
							});
						}
					}
			);
		}
	});

	//	function streamCreatedHandler(event) {
//		subscribeToStreams(event.streams);
//	}
//
//	function subscribeToStreams(streams) {
//		for (var i = 0; i < streams.length; i++) {
//			var stream = streams[i];
//			if (stream.connection.connectionId != session.connection.connectionId) {
//				displayOtherStream(stream);
//			}
//		}
//	}

	function exceptionHandler(event) {
		console.error("Exception: " + event.code + "::" + event.message);
	}
</script>

<div id="broadcast-stream">Loading ...</div>
