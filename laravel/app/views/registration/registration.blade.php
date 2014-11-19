@extends('registration.layout')

@section('head')
@parent
{{HTML::script('js/jqueryui/jquery-1.9.1.js')}}
{{HTML::script('js/jqueryui/jquery.ui.core.js')}}
{{HTML::script('js/jqueryui/jquery.ui.widget.js')}}
{{HTML::script('js/jqueryui/jquery.ui.position.js')}}
{{HTML::script('js/jqueryui/jquery.ui.menu.js')}}
{{HTML::script('js/jqueryui/jquery.ui.autocomplete.js')}}

{{HTML::style('css/jqueryui/themes/base/jquery.ui.all.css')}}
{{HTML::style('css/jqueryui/demos.css')}}
{{ Html::script('/js/jstz-1.0.4.min.js') }}
@stop


@section('page-content')
    <h2 class="page-title">{{ trans("Botangle Sign Up") }}</h2>
    <div class="row-fluid">
        <div class="span9">
            <div class="StaticPageRight-Block">
                <div class="PageLeft-Block">
                    <p class="FontStyle20"><?php echo trans("Create your Botangle Account") ?></p>
                    <p>{{ trans('It only takes a few minutes to register with Botangle and you get amazing features! Fill out the information below!') }}</p>
                    {{ Former::open()
                    ->method('POST')
                    ->route($route)
                    ->class('form-base form-horizontal')
                    }}

                    {{ Former::hidden('mode', $mode) }}

                    @if ($mode == 'expert')
                        @include('registration.expert-fields')
                    @elseif ($mode == 'student')
                        @include('registration.student-fields')
                    @endif


                    <p><strong>Account Information:</strong></p>

                    {{ Former::text('password')
                        ->addClass('textbox')
                        ->id('UserPassword')
                        ->type('password')
                        ->placeholder(trans('Password'))
                        ->label(trans('Password:'))
                        ->required()
                    }}
                    <div class="controls password-strength-indicator">
                        <div class="password-security" id="result">
                            <div class="security"></div>
                            Level of Security
                        </div>
                    </div>

                    {{ Former::text('password_confirmation')
                        ->addClass('textbox')
                        ->type('password')
                        ->placeholder(trans('Confirm Password'))
                        ->label(trans('Confirm Password:'))
                        ->required()
                    }}

                    <div class="control-group">
                        <div class="controls">
                            <label class="checkbox termcls">
                                {{ Form::checkbox('terms') }}
                                <label>&nbsp;I agree with Botangle's {{ Html::link(route('terms'), trans('Terms of Use and Privacy Policy'), array('target'=>'_blank')) }}.</label>
                            </label>
                        </div>
                    </div>

                    <div class="row-fluid">
                        {{ Former::actions(
                        Former::submit(trans('Create My Account'))
                        ->addClass('btn btn-primary')
                        ->name('update_info'),
                        Former::reset(trans('Reset'))
                        ->addClass('btn btn-reset')
                        )->addClass('control-group')
                        }}
                    </div>
                    {{ Former::close() }}
                </div>
            </div>
        </div><!-- span9 -->
        @include('_partials._signin')
    </div><!-- row-fluid -->

@stop

@section('jsFiles')
@parent
{{ Html::script(url('js/subject-autocomplete-multiselect.js')) }}
{{ Html::script(url('js/password-strength.js')) }}
<script>
    var tz = jstz.determine(); // Determines the time zone of the browser client
    jQuery('#timezone').val(tz.name());
</script>

@stop
