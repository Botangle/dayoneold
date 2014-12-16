@section('head')
@parent
{{ HTML::style('/js/select2/select2.css') }}
@stop

<div class="row-fluid">
    <div class="control-group">
        {{ Form::label('subject', trans("Subjects:*"), array('class' => 'control-label')) }}
        <div class="controls">
            {{ Form::select(
            'subject[]',
            Category::getSelect2List(),
            Input::old('subject'),
            array('multiple','id'=>'UserSubject2','placeholder'=>'Choose subjects', 'class' => 'textbox')
            ) }}
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="control-group">
        {{ Form::label('profilepic', 'Upload Your Pic', array('class' => 'control-label')) }}
        <div class="form-group span7 controls">
            {{ Form::file('profilepic', array()) }}
        </div>
    </div>
</div>

@include('registration.core-fields')

<div class="row-fluid">
    {{ Former::textarea('qualification')
    ->addClass('textarea')
    ->rows(3)
    ->placeholder(trans('Type in your qualifications'))
    ->label(trans('Qualification:'))
   }}
</div>
<div class="row-fluid">
    {{ Former::textarea('teaching_experience')
    ->addClass('textarea')
    ->rows(3)
    ->placeholder(trans('Teaching experience'))
    ->label(trans('Teaching Experience:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::textarea('extracurricular_interests')
    ->addClass('textarea')
    ->rows(3)
    ->placeholder(trans('Extracurricular Interests'))
    ->label(trans('Extracurricular Interests:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('other_experience')
    ->addClass('textbox')
    ->placeholder(trans('e.g. English with a Concentration in Theater'))
    ->label(trans('Other Experience:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('university')
    ->addClass('textbox')
    ->placeholder(trans('Barnard/University, Class of 2013'))
    ->label(trans('University:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('expertise')
    ->addClass('textbox')
    ->placeholder(trans('Top Subjects'))
    ->label(trans('Expertise in (Subject):'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_fb')
    ->addClass('textbox')
    ->placeholder(trans('Your Facebook link'))
    ->label(trans('Facebook Link:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_twitter')
    ->addClass('textbox')
    ->placeholder(trans('Your Twitter link'))
    ->label(trans('Twitter Link:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_googleplus')
    ->addClass('textbox')
    ->placeholder(trans('Your Google+ link'))
    ->label(trans('Google+ Link:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_thumblr')
    ->addClass('textbox')
    ->placeholder(trans('Your Thumblr link'))
    ->label(trans('Thumblr Link:'))
    }}
</div>

@section('jsFiles')
@parent
{{ HTML::script('/js/select2/select2.min.js') }}
@stop

@section('jqueryReady')
@parent
jQuery('#UserSubject2').select2();
@stop
