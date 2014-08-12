<div class="span1 tutorimg">
    {{ Html::image(url($otherUser->picture), $otherDesc, array('class' => 'img-circle', 'style' => 'width="242px" height="242px"')) }}
</div>

<div class="span2 tutor-name">
    {{ Html::link(url('user', $otherUser->username), $otherUser->username) }}
</div>

<div class="span1 date">
    {{ $lesson->formatLessonDate('M d') }}
</div>
<div class="span1 time">
    {{ $lesson->lesson_time }}
</div>
<div class="span1 mins">
    {{ $lesson->displayDuration }}
</div>
<div class="span2 subject">
    {{ $lesson->subject }}
</div>