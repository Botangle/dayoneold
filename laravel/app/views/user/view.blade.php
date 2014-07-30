@extends('layout')

@section('breadcrumbs')
{{ Breadcrumbs::renderIfExists('user', $model->full_name) }}
@overwrite

@section('content')

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
	<div class="container">
		<div class="row-fluid">
			<div class="span12">
				<h2 class="page-title"></h2>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span9 PageLeft-Block">
				<div class="row-fluid">
					<div class="span4">
                        {{ HTML::image($model->picture, '', ['class' => 'img-circle', 'alt' => 'student', 'style' => 'width: 242px; height: 242px']) }}
					</div>
					<div class="span8">
						<p class="pull-right">Rate : <span class="FontStyle16 color1"><strong>{{ $model->activeRate }}</strong></span></p>
						<p class="FontStyle28 color1">{{ $model->full_name }}
							<?php /* if($model->['is_online'] == 1){ ?>
							  <img src="<?php echo $this->webroot?>images/online.png" alt="online">
							  <?php } else {  ?>
							  <img src="<?php echo $this->webroot?>images/offline.png" alt="onffline">
							  <?php } */ ?>
							<br /><span style="font-size:13px">{{{ $model->username }}}</span>
							<br/>
                            @if (count($userStatuses) > 0)
                            <span style="font-size:13px"><b>Mood: </b>{{{ $model->latestStatus->status_text }}}</span>
                            @endif

						</p>

						<p>{{ $model->university }}<br>
							<br>
							{{ $model->other_experience }}</p>
						<p>Subject:
							@foreach ($subjects as $k => $v)
								<span class="tag01"> {{ $v }} </span>
							@endforeach
						</p>
						<div class="row-fluid">
							<div class="span6"><span class="pull-left">Botangle Star: &nbsp; </span> <input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="0" value="{{ $model->average_rating }}"/></div>
							<div class="span3"><span class="color1">{{ $model->review_count }} {{ trans("Reviews") }}</span></div>
							<div class="span3"><span class="color1">{{ ($userLessonStats ? $userLessonStats->lessons_count : 0) }} {{ trans("Classes") }}</span></div>
						</div>
						<div class="row-fluid Rate-this-tutor message-tutor">
							<!--<div class="span6"><span class="pull-left">Give your Rating: &nbsp; </span> <input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="remove"/></div>-->
							<!--<div class="span6"><span class="color1" style="line-height:20px;"><a href="#"><i class=" icon-comment"></i>Place your Review</a></span></div>-->
							<p class="option-msg">
                                {{ Html::link(
										url('/users/messages/' . $model->username), trans(''),
                                        array('data-toggle' => 'Message', 'title' => trans('Message')))
								}}
							</p>

						</div>


						<?php /*
						  <p>Share:
						  <span class="profile-share">
						  <a href="#">
						  <img src="<?php echo $this->webroot?>images/fb.png" alt="email">
						  </a>
						  </span>
						  <span class="profile-share">
						  <a href="#">
						  <img src="<?php echo $this->webroot?>images/twitter.png" alt="email">
						  </a>
						  </span>
						  <span class="profile-share">
						  <a href="#">
						  <img src="<?php echo $this->webroot?>images/mail.png" alt="email">
						  </a>
						  </span></p>
						 */ ?>

					</div>

				</div>

                <!-- Student Profile tabs-->
                <div class="row-fluid">
					<span class="span12 profile-tabs">
						<ul id="myTab" class="nav nav-tabs">
                            <li class="active"><a href="#home" data-toggle="tab">Feed</a></li>
                            <li class=""><a href="#aboutprofile" data-toggle="tab">About Me</a></li>
                            <li class=""><a href="#profile" data-toggle="tab">My Reviews</a></li>


                        </ul>
						<div id="myTabContent" class="tab-content">

                            <!-- tab 1 -->
                            <div class="tab-pane fade active in" id="home">
                                <div class="col-lg-12">
                                    <!--timeline start-->
                                    <section class="panel">
                                        <div class="panel-body">
                                            <div class="row-fluid Add-Payment-blocks bottomrow">

                                                @if ($isOwnTutorProfile)

                                                <div class="span12">
                                                    <p class="FontStyle20 color1"><?php echo trans("What's on your mind:") ?></p>
                                                </div>

                                                {{ Former::open()
                                                ->method('POST')
                                                ->class('form-base')
                                                ->route('user.status')
                                                }}

                                                {{ Former::hidden('id')->value($model->id) }}

                                                <div class="row-fluid">
                                                    {{ Former::textarea('status_text')
                                                    ->addClass('textarea')
                                                    ->placeholder(trans("What's on your mind?"))
                                                    ->label('')
                                                    ->maxlength(300)
                                                    }}
                                                </div>

                                                <div class="row-fluid">
                                                    {{ Former::actions(
                                                    Former::submit(trans('Update'))
                                                    ->addClass('btn btn-primary')
                                                    ->name('update')
                                                    )->addClass('control-group')
                                                    }}
                                                </div>
                                                {{ Former::close() }}

                                            @endif
                                            <div class="timeline1">
                                                @if (count($userStatuses) > 0)
                                                    <?php $index = 0; ?>
                                                    @foreach($userStatuses as $userStatus)
                                                        <?php
                                                            if (($index % 2) != 1){
                                                                $wrapperClass = 'left width50';
                                                                $innerClass = 'timelinestatus';
                                                            }  else {
                                                                $wrapperClass = 'right width50';
                                                                $innerClass = 'timelinestatusright right';
                                                            }
                                                            $index++;
                                                        ?>
                                                    <div class="{{ $wrapperClass }}">
                                                        <div class="{{ $innerClass }}">
                                                            <p class="status">{{{ $userStatus->status_text }}}</p>
                                                            <p class="time">{{ date('d M Y | l', strtotime($userStatus->created_at)) }}
                                                                {{ date('h:i a', strtotime($userStatus->created_at)) }}</p>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    {{ trans("No Status") }}
                                                @endif
                                            </div>

                                            <div class="clearfix">&nbsp;</div>
                                        </div>
                                    </section>
                                    <!--timeline end-->
                                </div>

                            </div>



                            <div class="tab-pane fade" id="aboutprofile">
                                <div class="student-profile">
                                    <a class="pull-left" href="#">
                                        <img src="/images/aboutme-img.png" alt="about"> </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{ trans("Teaching Experience") }}</h4>
                                        <p>{{{ $model->teaching_experience }}}</p>
                                    </div></div>

                                <div class="student-profile">
                                    <a class="pull-left" href="#">
                                        <img src="/images/interests-img.png" alt="about"> </a>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{ trans("Extracurricular Interests") }}</h4>
                                        <p>{{{ $model->extracurricular_interests }}}</p>
                                    </div></div>
                                @if ($model->expertise != "")
                                    <div class="student-profile">
                                        <a class="pull-left" href="#">
                                            <img src="/images/subjects.png" alt="subjects"> </a>
                                        <div class="media-body">
                                            {{{ $model->expertise }}}

                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade" id="profile">
                                <div class="class-timeinfo">
                                    @if ($userLessonStats)
                                    Total Classes: {{ $userLessonStats->lessons_count }} &nbsp; &nbsp;   | &nbsp; &nbsp;   Total Time of Classes: {{ $userLessonStats->total_duration }} hours
                                    @else
                                    Total Classes: 0 &nbsp; &nbsp;   | &nbsp; &nbsp;   Total Time of Classes: 0 hours
                                    @endif
                                </div>

                                @if (count($model->reviews) > 0)
                                    @foreach($model->reviews as $review)

                                <div class="Myclass-list row-fluid">
                                    <div class="span2">
                                        {{ Html::image(url($review->reviewer->picture), 'tutor', array('class' => 'img-circle')) }}
                                    </div>
                                    <div class="span3">
                                        <p class="FontStyle16">Class: {{ Html::link('#', $review->lesson->subject) }}</p>
                                        <p class="FontStyle11">Student: <strong>{{{ $review->reviewer->username }}}</strong></p>
                                    </div>
                                    <div class="span5">
                                        {{{ $review->reviews }}}
                                    </div>
                                    <div class="span2">
                                        <p><input type="number" name="your_awesome_parameter" id="some_id" class="rating" value="{{ $review->rating }}"/></p>
                                    </div>
                                </div>

                                    @endforeach
                                @else

                                <div class="Myclass-list row-fluid">
                                    <div class="span2">{{ trans("No reviews yet") }}</div>
                                </div>

                                @endif
                            </div>
                        </div>
					</span>
                </div>

			</div>

			<div class="span3 PageRight-Block-Cal PageRight-TopBlock">
				<div class="calendar">

					<div id="calendari_lateral1"></div>

				</div>

			</div>
		</div>
		<!-- @end .row -->

		@include('_partials.get-in-touch')

	</div>
	<!-- @end .container -->
</div>


<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left:40%; width:auto; right:20%; height:320px; overflow:hidden; top:25%">
	<?php
        $studentView = true;
    // TODO: fix this lesson create modal
//        include('lessoncreate.ctp');
    ?>
</div>
@overwrite

@section('jsFiles')
@parent
<script type="text/javascript" src="/js/bootstrap-rating-input.min.js"></script>

<script type="text/javascript">
    //	$(document).ready(function() {
    //		var text_max = 300;
    //		var charre = text_max - jQuery('#UsersStatusText').val().length;
    //		$('#textarea_feedback').html(charre + ' characters remaining');
    //
    //		$('#UsersStatusText').keyup(function() {
    //			var text_length = $('#UsersStatusText').val().length;
    //			var text_remaining = text_max - text_length;
    //
    //			$('#textarea_feedback').html(text_remaining + ' characters remaining');
    //		});
    //	});
    function callPopup() {

        var currentclass = jQuery(this).hasClass('reviews')
        var url = Croogo.basePath + 'users/createlessons/ajax/{{ $model->id }}';
        jQuery('body').append('<div class="modal-backdrop in"></div>')
        jQuery("#myModal").css({'display': 'block', 'height': 'auto', 'top': '25%', 'position': 'absolute'});

        jQuery('#myModal').css('height', jQuery('.StaticPageRight-Block').outerHeight() + 120)
        jQuery('.PageLeft-Block').css({'border-top': 0, 'box-shadow': 'none'}).parent('div.span9').css({width: 825 + 'px'})
        jQuery('.ui-autocomplete').css({'z-index': '999999'})

        jQuery('button[type="reset"]').click(function() {
            jQuery(".modal-backdrop").remove();
            jQuery("#myModal").css('display', 'none');
        })

    }
    function removebackground() {
        jQuery(".modal-backdrop").remove();
        jQuery("#myModal").html('').css('display', 'none');
    }
</script>
<script>
    jQuery(function() {
        jQuery('#myTab a[href="#home"]').tab('show');
    })
</script>
<script>
    // Calendar requires jQuery to be accessible as $
    var $ = jQuery.noConflict();
</script>
{{ Html::script('/js/calendar/bic_calendar.js', array('type' => "text/javascript")) }}
<script>
    $(function() {

        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        var dayNames = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];

        var events = [
            {
                date: "",
                title: '',
                link: '',
                linkTarget: '',
                color: '',
                content: '',
                class: '',
                displayMonthController: true,
                displayYearController: true,
                nMonths: 6
            }
        ];

        $('#calendari_lateral1').bic_calendar({
            //list of events in array
            events: events,
            //enable select
            enableSelect: true,
            //enable multi-select
            multiSelect: true,
            //set day names
            dayNames: dayNames,
            //set month names
            monthNames: monthNames,
            //show dayNames
            showDays: true,
            //show month controller
            displayMonthController: true,
            //show year controller
            displayYearController: true,
            //set ajax call
            reqAjax: {
                type: 'get',
                url: '/user/calendarEventsProfile/<?php echo $model->id ?>'
            }
        });
    });
</script>

@stop
