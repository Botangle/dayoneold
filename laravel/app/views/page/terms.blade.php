@extends('page.layout')

@section('page-title')
{{ trans("Botangle Terms of Use and Privacy Policy") }}
@stop

@section('content')
<div class="PageLeft-Block">
    <p class="FontStyle20"><?php echo trans("Terms of Use") ?></p>
    <p>Terms of use text required here.</p>
</div>
<div class="PageLeft-Block">
    <p class="FontStyle20"><?php echo trans("Privacy Policy") ?></p>
    <p>Privacy policy text required here.</p>

</div>

@stop
