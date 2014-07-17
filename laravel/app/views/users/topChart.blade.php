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


                        <?php
                        $i = 1;
                        if (!empty($users)) {
                            foreach ($users as $user) {
                                ?>
                                <div class="span4 search-result-box">
                                    <div class="search-result-img">
                                        <a href="{{ url('user') .'/'. $user->username }}">
                                            {{ Html::image(url($user->picture), 'tutor', array('class' => 'img-circle', 'style' => 'width: 117px; height: 117px')) }}
                                        </a>
                                    </div>
                                    <div class="search-result-options">
                                        <div class="pull-left"><input type="number" name="your_awesome_parameter" id="some_id" class="rating" data-clearable="0" value="{{ $user->average_rating }}"/></div>
                                        <div class="search-result-chat pull-right">
                                            <p class="option-pro">
                                                {{ Html::link(url('user') .'/'. $user->username, '', array('data-toggle' => 'tooltip', 'title' => 'Profile')) }}
                                            </p>
                                            <p class="option-msg">
                                                {{ Html::link(url('users/messages') .'/'. $user->username, '', array('data-toggle' => 'Message', 'title' => 'Message')) }}
                                            </p>

                                        </div>
                                    </div>
                                    <div class="search-result-title">

                                        <p class="FontStyle20">
                                            {{ Html::link(url('user') .'/'. $user->username, $user->fullName, array('title' => $user->username)) }}
                                        </p>
                                        <span>{{ $user->qualification }}</span></div>
                                    <div class="search-result-details">{{ $user->extracurricular_interests }}</div>
                                </div>

                                <?php
                                if ($i % 3 == 0) {
                                    echo '</div>  <div class="row-fluid">';
                                }
                                $i++;
                            }
                        }
                        ?>

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

