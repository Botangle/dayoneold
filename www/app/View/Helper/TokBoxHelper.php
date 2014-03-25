<?php
/**
 * TokBoxComponent.php
 * Makes it possible to use the TokBox system as needed in the system
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 3/25/14
 * Time: 9:56 AM
 */

App::uses('AppHelper', 'View/Helper');
class TokBoxHelper extends AppHelper {

    public $helpers = array('Html');

    /**
     * Settings array contains:
     * - scriptUrl
     * - apiKey
     */

    /**
     * Adds the appropriate script and meta tags in to help with adding in our WebRTC system
     */
    public function addHeadItems()
    {
        $this->Html->script($this->settings['scriptUrl'], array('inline' => false));
        $this->Html->meta(array('http-equiv' => 'X-UA-Compatible', 'content' => 'chrome=1'), null, array('inline' => false));
    }

    /**
     * Returns the appropriate video information
     * @return string
     */
    public function videoInformation()
    {
        $videoHtml = <<<THEEND

        <script type="text/javascript" charset="utf-8">
        TB.addEventListener("exception", exceptionHandler);

        var apiKey = '{$this->settings['apiKey']}';
        var sessionId = "1_MX40NDY5MjkxMn5-TW9uIE1hciAyNCAxMDo0NDoxNSBQRFQgMjAxNH4wLjE4MDkwMzAyfg";
        var token = "T1==cGFydG5lcl9pZD00NDY5MjkxMiZzZGtfdmVyc2lvbj10YnJ1YnktdGJyYi12MC45MS4yMDExLTAyLTE3JnNpZz05YmUxNzFlMjhlYTEwNmIzMjUxOTkxYzA4NTE4YjA2MTg1MzgxMWU3OnJvbGU9cHVibGlzaGVyJnNlc3Npb25faWQ9MV9NWDQwTkRZNU1qa3hNbjUtVFc5dUlFMWhjaUF5TkNBeE1EbzBORG94TlNCUVJGUWdNakF4Tkg0d0xqRTRNRGt3TXpBeWZnJmNyZWF0ZV90aW1lPTEzOTU2ODMwNTUmbm9uY2U9MC44MTE0NzU4NTM2NTg2ODUxJmV4cGlyZV90aW1lPTEzOTU3Njk0NTcmY29ubmVjdGlvbl9kYXRhPQ==";

        var session = TB.initSession(sessionId); // Replace with your own session ID. See https://dashboard.tokbox.com/projects
        session.addEventListener("sessionConnected", sessionConnectedHandler);
        session.addEventListener("streamCreated", streamCreatedHandler);
        session.connect(apiKey, token);

        function sessionConnectedHandler(event) {
            subscribeToStreams(event.streams);
            var properties = {
                width: 75,
                height: 56,
//					style: {
//						buttonDisplayMode: 'off', // this is to disable the mute button
//					}
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
            var streamsContainer = document.getElementById('videoChatBox');
            streamsContainer.appendChild(div);
            subscriber = session.subscribe(stream, 'stream' + stream.streamId);
        }

        function exceptionHandler(event) {
            alert("Exception: " + event.code + "::" + event.message);
        }
    </script>

THEEND;
        return $videoHtml;
    }

} 