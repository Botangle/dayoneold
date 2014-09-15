<p>I reviewed a lesson ({{{ $model->subject }}}) from {{ $model->formatLessonDate('M d') }}
 at {{ $model->formatLessonTime('G:i') }}.</p>

<p>For more details, {{ Html::link(route('user.profile', $model->tutorUser->username), 'click here to view your profile') }} and then click on the My Reviews tab.</p>