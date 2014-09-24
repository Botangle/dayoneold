@extends('user.layout')

@section('page-content')
<h2 class="page-title">
    {{ trans('Payment Made') }}
</h2>

<div class="StaticPageRight-Block">
    <div class="PageLeft-Block">
        <div class="control-group">
            @if ($lesson->payment)

                @if($isStudent)
                    <p>Thanks!  Your payment for the lesson you just finished has successfully gone through.</p>
                @else
                    <p>Payment for that lesson has gone through successfully and should show up in your account soon!.</p>
                @endif

                <p>Payment details:</p>
                <p>
                    @if($isStudent)
                        <b>Amount: {{ $lesson->payment->amount }}</b><br>
                    @else
                        <b>Amount: {{ $lesson->payment->amountMinusFee }}</b> (your payment minus our fee)<br>
                    @endif
                    Charge ID: {{{ $lesson->payment->stripe_charge_id }}}<br>
                </p>

                <p>Please do let us know if you have any questions.</p>
            @else
                <p>Whoops, looks like we had a problem, please get in touch with us to resolve your issues.</p>
            @endif
        </div>
    </div>
</div>
@stop
