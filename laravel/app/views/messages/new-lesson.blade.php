<p>I've booked a lesson ({{{ $model->subject }}}) with you for {{ $model->formatLessonDate('M d') }}
 at {{ $model->formatLessonTime('G:i') }}.</p>

<p>For more details, {{ Html::link(route('user.lessons','#lesson'.$model->id), 'click here') }} </p>