<div class="row-fluid">
    {{ Former::textarea('subject')
    ->addClass('textarea ui-autocomplete-input')
    ->rows(3)
    ->autocomplete('off')
    ->placeholder(trans('Teaching experience'))
    ->label(__('Subject:'))
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
    ->label(__('Qualification:'))
   }}
</div>
<div class="row-fluid">
    {{ Former::textarea('teaching_experience')
    ->addClass('textarea')
    ->rows(3)
    ->placeholder(trans('Teaching experience'))
    ->label(__('Teaching Experience:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::textarea('extracurricular_interests')
    ->addClass('textarea')
    ->rows(3)
    ->placeholder(trans('Extracurricular Interests'))
    ->label(__('Extracurricular Interests:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('other_experience')
    ->addClass('textbox')
    ->placeholder(trans('e.g. English with a Concentration in Theater'))
    ->label(__('Other Experience:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('university')
    ->addClass('textbox')
    ->placeholder(trans('Barnard/University, Class of 2013'))
    ->label(__('University:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('expertise')
    ->addClass('textbox')
    ->placeholder(trans('Top Subjects'))
    ->label(__('Expertise in (Subject):'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_fb')
    ->addClass('textbox')
    ->placeholder(trans('Your Facebook link'))
    ->label(__('Facebook Link:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_twitter')
    ->addClass('textbox')
    ->placeholder(trans('Your Twitter link'))
    ->label(__('Twitter Link:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_googleplus')
    ->addClass('textbox')
    ->placeholder(trans('Your Google+ link'))
    ->label(__('Google+ Link:'))
    }}
</div>
<div class="row-fluid">
    {{ Former::text('link_thumblr')
    ->addClass('textbox')
    ->placeholder(trans('Your Thumblr link'))
    ->label(__('Thumblr Link:'))
    }}
</div>