<p>I've confirmed my lesson ({{{ $model->subject }}}) with you for {{ $model->formatLessonDate('M d') }}
 at {{ $model->formatLessonTime('G:i') }}.</p>

<p>{{ Html::link(route('user.lessons','#lesson'.$model->id), 'Click here for more details.') }} </p>