<p>I've reviewed our lesson:</p>

@include('_partials.lesson-stacked', ['lesson' => $model, 'recipient' => $recipient])

<p>For more details, {{ Html::link(route('user.profile', $model->tutorUser->username), 'click here to view your profile') }} and then click on the My Reviews tab.</p>