<div class="PageLeft-Block">
    <p class="FontStyle20 color1"><?php echo trans("Change Password") ?></p>
    {{ Former::open()
    ->secure()
    ->method('POST')
    ->class('form-inline form-horizontal')
    ->route('user.change-password')
    ->role('form')
    }}

    {{ Former::framework('Nude') }}

    {{ Former::hidden('id', $user->id) }}

    <div class="row-fluid">
        <div class="form-group span5">
            <label class="sr-only">Old Password</label>
            <div class="input password required">
                {{ Former::text('old_password')
                ->addClass('form-control textbox1')
                ->type('password')
                ->placeholder(trans('Old Password'))
                ->label('')
                ->required()
                }}
            </div>
        </div>
    </div>
    <br>
    <div class="row-fluid">
        <div class="form-group span5">
            <label class="sr-only">New Password</label>
            <div class="input password required">
                {{ Former::text('new_password')
                ->addClass('form-control textbox1')
                ->type('password')
                ->placeholder(trans('New Password'))
                ->label('')
                ->required()
                }}
            </div>
        </div>
        <div class="form-group span5">
            <label class="sr-only">Confirm Password</label>
            <div class="input password required">
                {{ Former::text('new_password_confirmation')
                ->addClass('form-control textbox1')
                ->type('password')
                ->placeholder(trans('Confirm Password'))
                ->label('')
                ->required()
                }}
            </div>
        </div>
    </div><br>

    <div class="row-fluid">
        <div class="span12">
            {{ Former::submit(trans('Update Password'))
                ->addclass('btn btn-primary')
                ->name('change_password')
            }}
        </div>
    </div>
    {{ Former::close() }}
</div>
