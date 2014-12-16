@extends('layout')

@section('content')
<!--Wrapper HomeServices Block Start Here-->

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12"> </div>
        </div>
        <div class="row-fluid">
            <div class="span3 LeftMenu-Block">

                <p class="FontStyle20 mar-left15">Filter by category</p>
                <ul>

                    <li><a href="{{ url('users/top-chart')}}" title="Messages">All Categories <span class="badge pull-right" id="totalCategory">0</span></a></li>
                    <?php
                    $totalResult = 0;
                    foreach ($categories as $category) {
                        ?>
                        <li><a href="{{ url('users/top-chart') .'/'. $category->id }}" title="Lessons">{{{ $category->name }}}<span class="badge pull-right"><?php
                                    $resultsCount = $category->getUserCount();
                                    echo $resultsCount;
                                    $totalResult = $resultsCount + $totalResult;
                                    ?></span></a></li>
                    <?php } ?>
                    <script> jQuery('#totalCategory').html('<?php echo $totalResult ?>');
                    </script>

                </ul>

            </div>
            <div class="span9">
                <div class="StaticPageRight-Block">
                    <div class="row-fluid">

                        @include('users._cards', array('users' => $users))

                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        <!-- @end .row -->

        @include('_partials.get-in-touch')
    </div>
    <!-- @end .container -->
</div>


@overwrite

@section('jsFiles')
@parent
<script type="text/javascript" src="/js/bootstrap-rating-input.min.js"></script>
@stop

