<div class="span3 LeftMenu-Block">

    @include('lessons.whiteboard-video', array('model' => $model))

    {{ Form::hidden('status', $model->sync_status, ['id' => 'status']) }}
    <div id="sync-status" class="sync-status"></div>

    {{-- The number of seconds since this user arrived on this page --}}
    {{ Form::hidden('absTime', 0, ['id' => 'absTime']) }}

    {{-- The number of seconds before the lesson syncing will complete --}}
    {{ Form::hidden('countdown', 0, ['id' => 'countdown']) }}

    {{-- The duration of the lesson in seconds --}}
    {{ Form::hidden('max', $model->duration * 60, ['id' => 'max']) }}

    {{-- The lesson time used in seconds --}}
    {{ Form::hidden('secondsUsed', $model->seconds_used, ['id' => 'secondsUsed']) }}

    {{ Form::token(); }}

    {{-- @TODO: put a chat log down this left sidebar long-term, per our mockups --}}

    <input type="button" value="Exit / Complete Lesson" class="btn btn-primary" id="exitlesson" onclick="exitLesson({{ $model->id }}, {{ $model->roleType }})" {{ $model->exitDisabled }} />

    @if($model->roleType == 4)
        <div class="price-area" style="display: none">You will pay $<span></span> when you finish.</div>
    @endif

</div>
