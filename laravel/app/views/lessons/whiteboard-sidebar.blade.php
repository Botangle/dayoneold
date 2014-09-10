<div class="span3 LeftMenu-Block">
    @include('lessons.whiteboard-video', array('model' => $model))

    {{ Form::token(); }}

    {{-- @TODO: put a chat log down this left sidebar long-term, per our mockups --}}

    <input type="button" value="Exit / Complete Lesson" class="btn btn-primary" id="exitlesson" onclick="exitLesson({{ $model->id }}, {{ $model->roleType }})" {{ $model->exitDisabled }} />

    @if($model->roleType == 4)
        <div class="price-area" style="display: none">You will pay $<span></span> when you finish.</div>
    @endif

</div>
