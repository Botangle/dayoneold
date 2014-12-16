@extends('user.layout')

@section('page-content')
<h2 class="page-title">
    {{ trans('Credits') }} ({{{ number_format($user->creditAmount, 2) }}} available)
    <p class="pull-right">
        {{ Html::link(route('transaction.buy'),
        trans('+  Add Credits'),
        array(
        'title' => trans('+  Add Credits'),
        'class' => 'btn btn-primary btn-primary3',
        )
        );
        }}
    </p>

</h2>

<div class="StaticPageRight-Block">
    <div class="PageLeft-Block">
        <div class="span12">
            <p class="FontStyle20 color1">
                {{ trans("Transactions") }}
            </p>
        </div>
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>{{ trans('Date') }}</th>
                <th>{{ trans('Transaction') }}</th>
                <?php /* can't believe I'm doing this in the name of expediency, please take it out */ ?>
                <th style="width: 50px; padding-right: 2em;">{{ trans('Amount') }}</th>
                <th>{{ trans('Reference #') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($user->transactions as $transaction)
                <tr>
                    <td>{{ date("M d, Y", strtotime($transaction->created)) }}</td>
                    <td>
                        {{ $transaction->typeDisplay }}
                    </td>
                    <?php /* can't believe I'm doing this in the name of expediency, please take it out */ ?>
                    <td style="text-align: right !important; width: 50px; padding-right: 2em;">
                        {{ number_format($transaction->amount, 2); }}
                    </td>
                    <td>{{{ $transaction->transaction_key }}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($enableCreditSales)
    <div class="StaticPageRight-Block">
        <div class="PageLeft-Block">
            <div class="span12">
                <p class="FontStyle20 color1">
                    {{ trans("Sell Credits") }}
                </p>
            </div>
            @include('transaction.sell', array(
                    'email' => Auth::user()->email,
                ))
        </div>
    </div>
@endif

@stop
