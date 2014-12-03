@extends('layout')

@section('content')
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
        <div class="row-fluid">
            {{ $leftPanel }}
            <div class="span9">
                <h2 class="page-title">@yield('page-title')</h2>
                <div class="StaticPageRight-Block">
					@yield('content')
                </div>
            </div>
        </div>
        <!-- @end .row -->

        @include('_partials.get-in-touch')


    </div>
    <!-- @end .container -->
</div>
@overwrite