<p>I have a lesson proposal for you:</p>

@include('_partials.lesson-stacked', ['lesson' => $model, 'recipient' => $recipient])

<p>{{ Html::link(route('user.lessons','#lesson'.$model->id), 'Click here for more details.') }} </p>