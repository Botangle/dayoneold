<div class="row-fluid">
    {{ Former::textarea('subject')
    ->id('UserSubject')
    ->addClass('textarea ui-autocomplete-input')
    ->rows(3)
    ->autocomplete('off')
    ->placeholder(trans('Teaching experience'))
    ->label(trans('Subject:'))
    ->required()
    }}
    <p class="help-block field-helper-below">{{ trans("Separate Subjects with commas") }}</p>
</div>

@include('user.account.core-fields')

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