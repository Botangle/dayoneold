<div class="row-fluid">
    {{ Former::text('email')
    ->addClass('textbox')
    ->placeholder(trans('Email Address'))
    ->label(trans('Email Address:'))
    ->required()
    }}
</div>
<div class="row-fluid">
    {{ Former::text('username')
    ->addClass('textbox')
    ->placeholder(trans('Username'))
    ->label(trans('Username:'))
    ->id('username')
    ->required()
    }}
    <div class="controls username-availability-indicator">
        <div id="username-result">
        </div>
    </div>
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
@include('user.account.timezone-fields', array(
    'user' => new User,
))
