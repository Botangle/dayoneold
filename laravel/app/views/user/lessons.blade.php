@extends('user.layout')

@section('head')
@parent
{{--HTML::script('js/jqueryui/jquery-1.9.1.js')--}}
{{HTML::script('js/jqueryui/jquery.ui.core.js')}}
{{HTML::script('js/jqueryui/jquery.ui.widget.js')}}
{{HTML::script('js/jqueryui/jquery.ui.position.js')}}
{{HTML::script('js/jqueryui/jquery.ui.menu.js')}}
{{HTML::script('js/jqueryui/jquery.ui.autocomplete.js')}}

{{HTML::style('css/jqueryui/themes/base/jquery.ui.all.css')}}
{{HTML::style('css/jqueryui/demos.css')}}

{{HTML::script('js/bootstrap-datetimepicker.js')}}
{{HTML::style('css/bootstrap-datetimepicker.css')}}
@stop

@section('page-content')
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Active Lesson Proposals") }}</p>
            @foreach($proposals as $lesson)
                <?php
                if ($lesson->userIsTutor(Auth::user())){
                    $otherUser = $lesson->studentUser;
                    $otherDesc = 'student';
                } else {
                    $otherUser = $lesson->tutorUser;
                    $otherDesc = 'tutor';
                }
                ?>
            <div class="Lesson-row active {{ $otherDesc }}" id="{{ 'lesson'. $lesson->id }}">
                <div class="row-fluid">

                    @include('user.lesson.partial-fields', array('lesson' => $lesson, 'otherUser' => $otherUser, 'otherDesc' => $otherDesc))

                    <div class="span2 mark">
                        {{ Html::link('#', trans('Change'), array(
                            'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','data-toggle'=>"modal",
                            'data-url' => url('lesson', $lesson->id).'/edit'
                        )) }}
                    </div>

                    <div class="span2 mark">
                        @if($lesson->userCanConfirm(Auth::user()))
                            {{ Html::link(url('lesson', $lesson->id) . '/confirm', trans('Confirm'), array(
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
                <?php
                if ($lesson->tutor == Auth::user()->id){
                    $otherUser = $lesson->studentUser;
                    $otherDesc = 'student';
                } else {
                    $otherUser = $lesson->tutorUser;
                    $otherDesc = 'tutor';
                }
                ?>
            <div class="Lesson-row active {{ $otherDesc }}" id="{{ 'lesson'. $lesson->id }}">
                <div class="row-fluid">

                    @include('user.lesson.partial-fields', array('lesson' => $lesson, 'otherUser' => $otherUser, 'otherDesc' => $otherDesc))

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
                            {{ Html::link('#', trans('Change'), array(
                            'class'=>'btn btn-primary btn-primary3','style'=>'width:125px','data-toggle'=>"modal",
                            'data-url' => url('lesson', $lesson->id).'/edit'
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
                <?php
                if ($lesson->tutor == Auth::user()->id){
                    $otherUser = $lesson->studentUser;
                    $otherDesc = 'student';
                } else {
                    $otherUser = $lesson->tutorUser;
                    $otherDesc = 'tutor';
                }
                ?>
            <div class="Lesson-row active {{ $otherDesc }}" id="{{ 'lesson'. $lesson->id }}">
                <div class="row-fluid">

                    @include('user.lesson.partial-fields', array('lesson' => $lesson, 'otherUser' => $otherUser, 'otherDesc' => $otherDesc))

                    <div class="span2 mark lessonrating">
                        @if ($lesson->review)
                            <p>Rating: <input type="number"  id="{{ $lesson->id }}" value="{{ $lesson->review->rating }}" class="rating" /></p>
                        @elseif ($lesson->userIsStudent(Auth::user()))
                            {{ Html::link('#', trans('Review'), array(
                                'class'=>'btn btn-primary btn-primary3 reviews',
                                'data-url'=> url('lesson',$lesson->id) . '/review', 
                                'style'=>'width:125px', 
                                'data-toggle'=>"modal"
                            )) }}
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

<script>
jQuery('[data-toggle="modal"]').click(function(e) {
    var url = jQuery(this).attr('data-url');
    jQuery.get(url, function(data) {

        jQuery("#myModal").empty().html(data);
        jQuery('#myModal').on('shown.bs.modal', function(){
            jQuery(this).css({
                height: jQuery('#myModal .span9').outerHeight()
            });

            jQuery('form[data-async]').on('submit', function(event) {
                var $form = jQuery(this);

                jQuery.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),

                    success: function(data, status) {
                        if (data.result === 'failed'){
                            // Set a flash message with the errors
                            var flashError = "<p>" + data.errorMessage + "</p><div id='modal-errors'><ul>";
                            jQuery.each(data.errors, function(i,v){
                                flashError += '<li>'+ v +'</li>';
                            });
                            flashError += '</ul></div>';
                            jQuery('#modal-flash-wrapper').empty().append(flashError).show();
                            jQuery('#myModal').css('height', jQuery('#myModal .span9').outerHeight());
                        } else {
                            // Refresh the page to show the changes made
                            window.location.reload();
                        }
                    }
                });
                event.preventDefault();
            });

        });
        jQuery('#myModal').modal('show');

//        jQuery('#myModal').css('height',jQuery('.StaticPageRight-Block').outerHeight()+300)
//        jQuery('.PageLeft-Block').css({'border-top':0,'box-shadow':'none'}).parent('div.span9').css({width:825+'px'})

        jQuery('.btn-reset').click(function(e){
            jQuery("#myModal").modal('hide');
        })

    });
});

</script>
@stop