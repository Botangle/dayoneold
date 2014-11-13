<p>I reviewed a lesson ({{{ $model->subject }}}) from {{ $model->formattedLessonAt('M d', $recipient) }}
 at {{ $model->formattedLessonAt('G:i', $recipient) }}<br>
    Timezone: {{ $recipient->getTimezoneForHumans() }}.</p>

<p>For more details, {{ Html::link(route('user.profile', $model->tutorUser->username), 'click here to view your profile') }} and then click on the My Reviews tab.</p>