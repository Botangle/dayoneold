<div class="span9">
    <h2 class="page-title">{{ $title }}</h2>
    <p class="FontStyle20 color1">{{ $subtitle }}</p>

    <div class="modal-flash-wrapper alert alert-error">

    </div>

    {{ Former::open()
    ->method('POST')
    ->class('form-horizontal')
    ->route($submit)
    ->data_async()
    ->id('lessonForm')
    }}

    {{ Former::populate($model) }}

    {{-- Lesson date/time requires some special work to stop the additional 00 seconds messing with the validation
         and to display in the user's timezone
    --}}
    {{ Former::populateField('lesson_time', $model->formattedLessonAt('G:i')) }}
    {{ Former::populateField('lesson_date', $model->formattedLessonAt('Y-m-d')) }}

    {{ Former::hidden('id') }}

    {{ Former::hidden('tutor') }}


    {{ Former::hidden('other_timezone', $otherUser ? $otherUser->timezone : '') }}

    <div class="row-fluid">
        {{ Former::text('expert_name')
        ->addClass('textbox')
        ->placeholder(trans('Expert'))
        ->label(trans('Expert:'))
        ->value($model->tutorUser->fullName)
        ->disabled()
        }}
    </div>

    <div class="row-fluid">
    @if ($model->student != null)
        {{ Former::hidden('student') }}
        {{ Former::text('expert_name')
            ->addClass('textbox')
            ->placeholder(trans('Student'))
            ->label(trans('Student:'))
            ->value($model->studentUser->fullName)
            ->disabled()
        }}
    @else
        {{-- Allow the user to select from their previous students  --}}
        {{ Former::select('student')
            ->options($model->tutorUser->getStudents())
            ->placeholder(trans('Choose from existing students...'))
            ->label(trans('Student:'))
            ->required()
        }}
    @endif
    </div>

    {{-- date and time fields --}}
    <div class="control-group">
        <label class="control-label" for="lesson-time">Lesson Time:</label>
        <div class="controls">
            {{ Former::hidden('lesson_date')
            ->id('dtp_input2')
            }}

            <div class=" input-append date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="width:47%;">

                <input size="16" type="text" value="{{ $model->formattedLessonAt('d F Y') }}" readonly class="textbox2" style="width:57%" />
                <span class="add-on"><i class="icon-remove"></i></span>
                <span class="add-on"><i class="icon-th"></i></span>

            </div>
            {{ Former::hidden('lesson_time')->id('dtp_input3') }}
            <div class=" input-append date form_time" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii" style="width:33%;">
                <input size="16" class="textbox2" type="text" value="{{ $model->formattedLessonAt('G:i') }}" readonly>
                <span class="add-on"><i class="icon-remove"></i></span>
                <span class="add-on"><i class="icon-th"></i></span>

            </div>
            <p>Time shown in your timezone: {{ Auth::user()->getTimezoneForHumans() }} ({{ HTML::link(route('user.timezone'), 'change timezone') }})</p>
            @if($otherUser && Auth::user()->timezone != $otherUser->timezone)
            <p><em>Note: {{ $otherUser->fullName }}'s timezone: {{ $otherUser->getTimezoneForHumans() }}</em></p>
            @endif
        </div>
    </div>

    {{-- duration field --}}
    {{ Former::select('duration')
    ->options(Lesson::getDurationOptions())
    ->placeholder(trans('-- Please choose --'))
    ->id('Lessonduration')
    ->label(trans('Duration:'))
    ->required()
    }}

    {{ Former::select('subject')
    ->options($model->tutorUser->getSubjectsArray())
    ->placeholder(trans('-- Please choose --'))
    ->id('LessonSubject')
    ->label(trans('Subject:'))
    ->required()
    }}

    {{-- note --}}
    {{ Former::textarea('notes')
    ->placeholder(trans('Type Your Note'))
    ->addClass('textarea')
    ->label(trans('Note:'))
    }}

    {{ Former::actions(
    Former::submit(trans('Submit'))
    ->addClass('btn btn-primary')
    ->name('submit'),
    Former::reset(trans('Cancel'))
    ->addClass('btn btn-reset')
    ->dataDismiss('modal')

    )->addClass('control-group')
    }}

    {{ Former::close() }}

</div><!-- @end .span9 -->

<script>
    var currentdate = new Date();
    var y = currentdate.getFullYear();
    var m = currentdate.getMonth()+1;
    var d = currentdate.getDate();
    dd = y+"-"+m+"-"+d;
    jQuery('.form_date').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        startDate: dd
    });
    jQuery('.form_time').datetimepicker({
        language:  'en',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });
</script>
