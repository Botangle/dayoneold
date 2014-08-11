@extends('user.layout')

@section('page-content')
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Active Lesson Proposals") }}</p>
            @foreach($proposals as $lesson)
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

                    <div class="span1 tutorimg">
                        {{ Html::image(url($otherUser->picture), $otherDesc, array('class' => 'img-circle', 'style' => 'width="242px" height="242px"')) }}
                    </div>

                    <div class="span2 tutor-name">
                        {{ Html::link(url('user', $otherUser->username), $otherUser->username) }}
                    </div>

                    <div class="span1 date">
                        {{ $lesson->displayDate }}
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
        </div>

        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Past Lessons") }}</p>
        </div>
    </div>

@stop
