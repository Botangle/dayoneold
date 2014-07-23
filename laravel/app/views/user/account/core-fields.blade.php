<div class="row-fluid">
    {{ Former::text('username')
    ->addClass('textbox')
    ->placeholder(trans('Username'))
    ->label(__('Username:'))
    ->disabled()
    ->required()
    }}
</div>
<div class="row-fluid">
    {{ Former::text('name')
    ->addClass('textbox')
    ->placeholder(trans('First Name'))
    ->label(__('First Name:'))
    ->required()
    }}
</div>
<div class="row-fluid">
    {{ Former::text('lname')
    ->addClass('textbox')
    ->placeholder(trans('Last Name'))
    ->label(__('Last Name:'))
    ->required()
    }}
</div>
<div class="row-fluid">
    {{ Former::text('email')
    ->addClass('textbox')
    ->placeholder(trans('Email Address'))
    ->label(__('Email Address:'))
    ->disabled()
    ->required()
    }}
</div>