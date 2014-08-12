@extends('user.layout')

@section('page-content')
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Active Lesson Proposals") }}</p>
            @foreach($proposals as $lesson)
            <div class="Lesson-row active">
                <div class="row-fluid">
                    <?php
                    if ($lesson->userIsTutor(Auth::user())){
                        $otherUser = $lesson->studentUser;
                        $otherDesc = 'student';
                    } else {
                        $otherUser = $lesson->tutorUser;
                        $otherDesc = 'tutor';
                    }
                    ?>

                    @include('user.lesson.partial-fields', array('lesson' => $lesson, 'otherUser' => $otherUser))

                    <div class="span2 mark">
                        {{ Html::link(url('users/changelesson', $lesson->id), trans('Change'), array(
                            'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','data-toggle'=>"modal"
                        )) }}
                    </div>

                    <div class="span2 mark">
                        @if($lesson->userCanConfirm(Auth::user()))
                            {{ Html::link(url('users/confirm', $lesson->id), trans('Confirm'), array(
                            'class'=>'btn btn-primary btn-primary3','style'=>'width:125px'
                            )) }}
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Upcoming Lessons") }}</p>
            @foreach($upcomingLessons as $lesson)
            <div class="Lesson-row active">
                <div class="row-fluid">
                    <?php
                    if ($lesson->tutor == Auth::user()->id){
                        $otherUser = $lesson->studentUser;
                        $otherDesc = 'student';
                    } else {
                        $otherUser = $lesson->tutorUser;
                        $otherDesc = 'tutor';
                    }
                    ?>

                    @include('user.lesson.partial-fields', array('lesson' => $lesson, 'otherUser' => $otherUser))

                    @if($lesson->userIsTutor(Auth::user()))
                        <div class="span2 mark">
                            @if($lesson->lesson_date == date('Y-m-d'))
                                {{ Html::link(url('users/whiteboarddata', $lesson->id), trans('Go to Lesson'), array('class' => 'btn btn-primary btn-primary3')) }}
                            @else
                                {{ Html::link(url('#'), trans('Go to Lesson'), array('class' => 'btn btn-primary btn-primary3', 'disabled' => 'disabled')) }}
                            @endif
                        </div>
                    @else
                        <div class="span2 mark">
                            {{ Html::link(url('users/changelesson', $lesson->id), trans('Change'), array(
                            'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','data-toggle'=>"modal"
                            )) }}
                        </div>
                        <div class="span2 mark">
                            @if($lesson->lesson_date == date('Y-m-d'))
                            {{ Html::link(url('users/whiteboarddata', $lesson->id), trans('Go to Lesson'), array('class' => 'btn btn-primary btn-primary3')) }}
                            @else
                            {{ Html::link(url('#'), trans('Go to Lesson'), array('class' => 'btn btn-primary btn-primary3', 'disabled' => 'disabled')) }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Past Lessons") }}</p>

            @foreach($pastLessons as $lesson)
            <div class="Lesson-row active">
                <div class="row-fluid">
                    <?php
                    if ($lesson->tutor == Auth::user()->id){
                        $otherUser = $lesson->studentUser;
                        $otherDesc = 'student';
                    } else {
                        $otherUser = $lesson->tutorUser;
                        $otherDesc = 'tutor';
                    }
                    ?>

                    @include('user.lesson.partial-fields', array('lesson' => $lesson, 'otherUser' => $otherUser))

                    <div class="span2 mark lessonrating">
                        @if ($lesson->review)
                            <p>Rating: <input type="number"  id="{{ $lesson->id }}" value="{{ $lesson->review->rating }}" class="rating" /></p>
                        @else
                            {{ Html::link('#', trans('Reviews'), array('class'=>'btn btn-primary btn-primary3 reviews','data-url'=> url('users/lessonreviews',$lesson->id), 'style'=>'width:125px','data-toggle'=>"modal")) }}
                        @endif
                    </div>

                    <div class="span2 mark">
                        {{ Html::link(url('users/whiteboarddata', $lesson->id), trans('Go to Lesson'), array('class' => 'btn btn-primary btn-primary3')) }}
                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>

@stop

@section('jsFiles')
@parent
<script>
$ = jQuery.noConflict();
</script>
<script type="text/javascript" src="/js/bootstrap-rating-input.min.js"></script>
@stop