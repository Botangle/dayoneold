@extends('page.layout')

@section('page-title')
{{ trans("Our Policies") }}
@stop

@section('content')
<div class="PageLeft-Block">
    <p class="FontStyle20"><?php echo trans("Privacy") ?></p>
    <p>We collect IP addresses, information about how you use the site and all necessary information to schedule
        lessons. We don’t market, sell or otherwise disclose that information unless required by law.</p>
</div>
<div class="PageLeft-Block">
    <p class="FontStyle20"><?php echo trans("Refunds") ?></p>
    <p>We will refund your money for credit purchases under $101 if valid. No refunds over $101
        without special authorization. Please contact us at
        <a href="mailto:contactus@botangle.com">contactus@botangle.com</a> to work that out. Thanks!</p>
</div>

@stop
