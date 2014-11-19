@extends('user.layout')

@section('page-content')
    <h2 class="page-title">{{ trans("My Account") }}</h2>
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1"><?php echo trans("Update Info") ?></p>
            {{ Former::open_for_files()
            ->method('POST')
            ->class('form-base form-horizontal')
            }}

            {{ Former::populate($user) }}

            {{ Former::hidden('id') }}

            <div class="row-fluid">
                <div class="control-group">
                    <label class="control-label"></label>
                    <div class="form-group span4 controls">
                        {{ Html::image(url($user->picture), 'student', array('class' => 'img-circle img-profilepic')) }}
                    </div>
                </div>
            </div>

            <div class="row-fluid">
                {{ Former::file('profilepic')
                ->label(trans('Upload Your Pic'))
                }}
            </div>

            @if ($mode == 'expert')
                @include('user.account.expert-fields')
            @elseif ($mode == 'student')
                @include('user.account.student-fields')
            @endif

            <div class="row-fluid">
                {{ Former::actions(
                Former::submit(trans('Update Info'))
                ->addClass('btn btn-primary')
                ->name('update_info')
                )->addClass('control-group')
                }}
            </div>
            {{ Former::close() }}
        </div>
    </div>

    @include('user.account.change-password', array('user' => $user))

@stop

@section('jsFiles')
@parent
{{ Html::script(url('js/subject-autocomplete-multiselect.js')) }}
@stop
