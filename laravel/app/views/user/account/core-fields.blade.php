<div class="row-fluid">
    {{ Former::text('username')
    ->addClass('textbox')
    ->placeholder(trans('Username'))
    ->label(trans('Username:'))
    ->disabled()
    ->required()
    }}
</div>
<div class="row-fluid">
    {{ Former::text('name')
    ->addClass('textbox')
    ->placeholder(trans('First Name'))
    ->label(trans('First Name:'))
    ->required()
    }}
</div>
<div class="row-fluid">
    {{ Former::text('lname')
    ->addClass('textbox')
    ->placeholder(trans('Last Name'))
    ->label(trans('Last Name:'))
    ->required()
    }}
</div>
<div class="row-fluid">
    {{ Former::text('email')
    ->addClass('textbox')
    ->placeholder(trans('Email Address'))
    ->label(trans('Email Address:'))
    ->disabled()
    ->required()
    }}
</div>
@include('user.account.timezone-fields')