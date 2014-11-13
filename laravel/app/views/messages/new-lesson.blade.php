<p>I booked a lesson ({{{ $model->subject }}}) with you for {{ $model->formattedLessonAt('M d', $recipient) }}
 at {{ $model->formattedLessonAt('G:i', $recipient) }}<br>
 Timezone: {{ $recipient->getTimezoneForHumans() }}.</p>

<p>Notes:<br>
    {{ $model->notesForEmail }}</p>

<p>{{ Html::link(route('user.lessons','#lesson'.$model->id), 'Click here for more details.') }} </p>