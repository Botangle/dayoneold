<p>
    <strong>Subject:</strong> {{ $lesson->subject }}<br>
    <strong>When:</strong> {{ $lesson->formattedLessonAt('M d G:i', $recipient) }}<br>
    <strong>Timezone:</strong> {{ $recipient->getTimezoneForHumans() }}<br>
    <strong>Duration:</strong> {{ $lesson->displayDuration }}<br>
    <strong>Rate:</strong> {{ $lesson->displayRate }}
</p>

<p>Notes:<br>
    {{ $model->notesForEmail }}</p>
