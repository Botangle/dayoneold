@extends('registration.layout')

@section('page-content')
    <h2 class="page-title">{{ trans("Botangle Terms of Use and Privacy Policy") }}</h2>
    <div class="row-fluid">
        <div class="span9">
            <div class="StaticPageRight-Block">
                <div class="PageLeft-Block">
                    <p class="FontStyle20"><?php echo trans("Terms of Use") ?></p>
                    <p>Terms of use text required here.</p>

                    <p class="FontStyle20"><?php echo trans("Privacy Policy") ?></p>
                    <p>Privacy policy text required here.</p>

                </div>
            </div>
        </div><!-- span9 -->
        @if (!Auth::check())
        @include('_partials._signin')
        @endif
    </div><!-- row-fluid -->

@stop
