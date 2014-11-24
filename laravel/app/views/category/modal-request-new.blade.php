    <div class="span9">
        <h2 class="page-title">{{ trans('Categories') }}</h2>
        <p class="FontStyle20 color1">{{ trans("Request for New Category") }}</p>

        <div id="modal-flash-wrapper" class="alert alert-error">

        </div>

        {{ Former::open()
        ->method('POST')
        ->class('form-horizontal')
        ->route('categories.requestNew')
        ->data_async()
        ->id('requestNewCategory')
        }}

        <div class="row-fluid">
            {{ Former::text('username')
            ->addClass('textbox')
            ->placeholder(trans('User'))
            ->label(trans('User:'))
            ->value(Auth::user()->username)
            ->disabled()
            }}
        </div>

        {{-- note --}}
        {{ Former::textarea('request')
        ->placeholder(trans('What categories would you like to be added?'))
        ->addClass('textarea')
        ->label(trans('Requests:'))
        }}

        {{ Former::actions(
        Former::submit(trans('Submit'))
        ->addClass('btn btn-primary')
        ->name('submit'),
        Former::reset(trans('Cancel'))
        ->addClass('btn btn-reset')
        ->dataDismiss('modal')

        )->addClass('control-group')
        }}

        {{ Former::close() }}

    </div><!-- @end .span9 -->
