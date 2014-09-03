@extends('layout')

@section('content')
<!--Wrapper HomeServices Block Start Here-->

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <h2 class="page-title"></h2>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12 PageLeft-Block">
                <div class="search-box">
                    {{ Form::open([
                        'class' => 'form-inline form-horizontal',

                    ]) }}
                    <div class="row-fluid">
                        <div class="span10">
                            {{ Form::text('search', '', array('class' => 'textbox01', 'placeholder' => 'Search')) }}
                        </div>
                        <div class="span2">
                            {{ Form::submit(trans('Search'), array('class' => 'btn btn-primary btn-primary2')) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>

                <div class="row-fluid">
                    @if(!empty($categories))
                    <?php $i = 0; ?>

                    @foreach ($categories as $letter => $categoryOptions)
                    <div class="span3 Category-block">
                        <div class="title01">{{{ $letter }}}</div>
                        <ul>
                            @foreach ($categoryOptions as $item)
                            @foreach ($item as $id => $name)
                            <li><a href="/users/topchart/$id">{{{ $name }}}</a></li>
                            @endforeach
                            @endforeach
                        </ul>
                    </div>

                    <?php $i++; ?>
                    @if ($i % 4 == 0)
                </div><div class="row-fluid">
                    @endif
                    @endforeach
                    @endif

                </div>


            </div>

        </div>
        <!-- @end .row -->

        @include('_partials.get-in-touch')

    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
@overwrite