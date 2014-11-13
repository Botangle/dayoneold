<div class="row-fluid">
    {{-- Former::text('timezone_country')
    ->addClass('textbox')
    ->placeholder(trans('Country'))
    ->label(__('Country:'))
    ->required()
    --}}

    {{ Former::select('timezone')
    ->options(User::getTimezoneOptions())
    ->addClass('select')
    ->placeholder(trans('Timezone'))
    ->label(__('Timezone:'))
    ->id('timezone')
    ->required()
    }}
</div>
<div class="row-fluid" id="timezone-update">
    <div class="control-group">
        {{ Form::label('timezone_update', trans("Timezone Update:"), array('class' => 'control-label')) }}
        <div class="controls">
            <label>
                {{ Form::radio('timezone_update', 'auto', ($user->timezone_update == User::TIMEZONE_UPDATE_AUTO), array('class' => 'timezone-setting')) }}
                {{ trans('Automatically detect my timezone everytime that I login') }}
            </label>
            <label>
                {{ Form::radio('timezone_update', 'ask', $user->timezone_update == User::TIMEZONE_UPDATE_ASK) }}
                {{ trans('Ask before changing timezone if a different one is detected at login') }}
            </label>
            <label>
                {{ Form::radio('timezone_update', 'never', $user->timezone_update == User::TIMEZONE_UPDATE_NEVER) }}
                {{ trans('Always use the timezone that I set here') }}
            </label>
        </div>
    </div>

</div>