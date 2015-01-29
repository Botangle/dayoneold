<div class="span9">
    <h2 class="page-title">{{ $title }}</h2>
    <p class="FontStyle20 color1">{{ $subtitle }}</p>

    <div id="modal-flash-wrapper" class="alert alert-error">

    </div>

    {{ Former::open()
    ->method('POST')
    ->class('form-horizontal')
    ->route($submit)
    ->data_async()
    ->id('reviewForm')
    }}

    {{ Former::hidden('lesson_id')->value($model->id) }}
    {{ Former::hidden('rate_by')->value($model->student) }}
    {{ Former::hidden('rate_to')->value($model->tutor) }}

    <div class="control-group">
        <label class="control-label">{{ trans("Rating") }}</label>
        <div class="controls">
            <div class="span2 mark lessonratingdata">
                {{ Former::text('rating')
                    ->addClass('ratingAdd')
                    ->data_add(true)
                    ->type('number')
                    ->label('')
                    ->div(false)
                    ->value(5)
                }}
            </div>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">{{ trans('What did you like or dislike about your lesson:') }}</label>
        <div class="controls">
            {{ Former::textarea('reviews')
                ->addClass('textarea')
                ->label('')
                ->placeholder(trans('type your review'))
                ->rows(3)
            }}
        </div>
    </div>

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

<script>
    jQuery('.ratingAdd').rating();
</script>
