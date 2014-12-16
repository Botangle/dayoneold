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
            @include('user._sidebar')

            <div class="span9">
                @yield('page-content')
            </div>
            <!-- @end .span9 -->
        </div>
        <!-- @end .row -->
    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

@overwrite