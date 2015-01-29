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
@include('_partials.loading', ['title' => 'Adding Lesson'])
@stop

@section('jsFiles')
@parent
@if (!$lesson->review)
    <script>
        $ = jQuery.noConflict();
    </script>
    <script type="text/javascript" src="/js/bootstrap-rating-input.min.js"></script>

    <script>
    jQuery(document).ready(function($) {
        jQuery.get("{{ url('lesson',$lesson->id) . '/review' }}", function(data) {

            jQuery("#myModal").empty().html(data);
            jQuery('#myModal').on('shown.bs.modal', function(){
                jQuery(this).css({
                    height: jQuery('#myModal .span9').outerHeight()
                });

                jQuery('#myModal form[data-async]').on('submit', function(event) {
                    var $form = jQuery(this);
                    $("#loading-div-background").show();
                    $("#loading-div-background").css({ opacity: 0.9 });

                    jQuery.ajax({
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        data: $form.serialize(),

                        success: function(data, status) {
                            if (data.result === 'failed'){
                                // Set a flash message with the errors
                                var flashError = "<p>" + data.errorMessage + "</p><div id='modal-errors'><ul>";
                                jQuery.each(data.errors, function(i,v){
                                    flashError += '<li>'+ v +'</li>';
                                });
                                flashError += '</ul></div>';
                                jQuery('.modal-flash-wrapper').empty().append(flashError).show();
                                jQuery('#myModal').css('height', jQuery('#myModal .span9').outerHeight());
                                $("#loading-div-background").hide();
                            } else {
                                jQuery('#myModal').modal('hide');
                                $("#loading-div-background").hide();
                            }
                        },

                        error: function(){
                            $("#loading-div-background").hide();
                        }
                    });
                    event.preventDefault();
                });

            });
            jQuery('#myModal').modal('show');

    //        jQuery('#myModal').css('height',jQuery('.StaticPageRight-Block').outerHeight()+300)
    //        jQuery('.PageLeft-Block').css({'border-top':0,'box-shadow':'none'}).parent('div.span9').css({width:825+'px'})

            jQuery('.btn-reset').click(function(e){
                jQuery("#myModal").modal('hide');
            })

        });
    });
    </script>
@endif
@stop
