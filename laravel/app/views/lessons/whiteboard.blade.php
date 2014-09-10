@extends('layout')

@section('head')
@parent
<meta http-equiv="X-UA-Compatible" content="chrome=1" />
@stop

@section('content')
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
        <div class="row-fluid">
            @include('lessons.whiteboard-sidebar', array('model' => $model))

            <div class="span9">
                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">

                        <div class="Lesson-row active">
                            <div class="row-fluid">

                                <div>
                                    <div id="{{ $model->countdownId }}">{{ $model->whiteboardTimer }}</div>
                                </div>

                                <input type="hidden" name="roletype" id="roletype" value="{{ $model->roleType }}" />
                                @if ($model->secondsRemaining <= 0)
                                    {{ Form::open(array(
                                            'url' => route('lesson.payment', $model->id). '/?role='. $model->userIsTutor(Auth::user()) ? 'tutor' : 'student'
                                    )) }}
                                    {{ Form::submit('Make Payment') }}
                                    {{ Form::close() }}
                                @else
                                    <iframe src="{{ $model->twiddlaMeetingUrl }}" frameborder="0" width="787" height="600" style="border:solid 1px #555;"></iframe>
                                @endif
                            </div>
                        </div><!-- Lesson-row -->
                    </div><!-- PageLeft-Block -->
                </div><!-- StaticPageRight-Block -->
            </div>
            <!-- @end .span9 -->
        </div>
        <!-- @end .row -->
    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

@overwrite
@section('jsFiles')
@parent
<script>
    var BotangleBasePath = "{{ url('/') }}/";
</script>
{{ Html::script('js/countdown.js') }}
{{ Html::script('js/whiteboard.js') }}
<script>
    var timer = "";
    jQuery(document).ready (function () {
        startCount({{ $model->id }}, {{ $model->roleType }});
    });
</script>
@stop
