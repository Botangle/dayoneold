@extends('layout')

@section('content')
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
                @yield('page-content')
    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

@overwrite