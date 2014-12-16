<p>I've made some changes to the lesson:</p>

@include('_partials.lesson-stacked', ['lesson' => $model, 'recipient' => $recipient])

<p>{{ Html::link(route('user.lessons','#lesson'.$model->id), 'Click here for more details.') }} </p>