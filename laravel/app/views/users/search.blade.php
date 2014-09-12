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
                {{ Form::open(array('route' => 'users.search', 'id' => 'UserSearchForm')) }}
                    <div style="display:none;"><input type="hidden" name="_method" value="POST"><input type="hidden" name="data[_Token][key]" value="2379dfa76b6c33823bd158929839cf7739d804db" id="Token1897982701"></div>
                    <div class="Search-filter-Block">
                        <p class="FontStyle20">Search by Keywords</p>
                        <input type="text" value="" id="keyword" class="textbox1" name="searchvalue">
                        <br>
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                {{ Form::close() }}
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

