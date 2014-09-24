@extends('user.layout')

@section('page-content')
    <h2 class="page-title">{{ trans('Billing') }}</h2>
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <p class="FontStyle20 color1">{{ trans("Payment Type") }}</p>
            <p>In order for students to sign up for lessons, we'll need you to specify your rates as well. Please do so here.</p>
            {{ Form::open(array(
                'action' => 'UserController@postRateChange',
                'class'=>'form-horizontal',
                'id' => 'UserRate',
            )) }}

            {{ Form::hidden('userid', $user->id) }}
            {{ Form::hidden('current_rate_id', $rate->id) }}

            <?php
            if($rate->price_type == UserRate::RATE_PER_MINUTE){
                $pchecked = "checked='checked'";
                $phchecked= "";
            } else {
                $pchecked = "";
                $phchecked = "checked='checked'";
            }
            ?>
            <div class="control-group">
                <label class="control-label span5">{{ trans("Rate:") }}</label>
                <div class="controls">
                    <label class="radio inline span4">
                        <input type="radio" name="price_type" value="permin" <?php echo $pchecked?>> Per Minute Price
                    </label>
                    <label class="radio inline span4 mar0">
                        <input type="radio" name="price_type" value="perhour" <?php echo $phchecked?>> Per Hour Price
                    </label>
                </div>
                <div class="control-group">
                    <label class="control-label span5" for="rate">&nbsp;</label>
                    <div class="controls span5">
                        {{ Form::text('rate', $rate->rate, array('class' => 'textbox', 'placeholder' => 'rate')) }}
                    </div>
                </div>
            </div>
            <div class="row-fluid Add-Payment-blocks">
                <div class="span5"></div>
                <div class="span5">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>

@stop
