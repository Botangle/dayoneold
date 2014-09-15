<p>I booked a lesson ({{{ $model->subject }}}) with you for {{ $model->formatLessonDate('M d') }}
 at {{ $model->formatLessonTime('G:i') }}.</p>

<p>Notes:<br>
    {{ $model->notesForEmail }}</p>

<p>{{ Html::link(route('user.lessons','#lesson'.$model->id), 'Click here for more details.') }} </p>