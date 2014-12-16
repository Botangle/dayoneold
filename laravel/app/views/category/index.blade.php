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
                            <li><a href="{{ route('users.topcharts', $id) }}">{{{ $name }}}</a></li>
                            @endforeach
                            @endforeach
                        </ul>
                    </div>

                    <?php $i++; ?>
                    @if ($i % 4 == 0)
                </div>
                <div class="row-fluid">
                    @endif
                    @endforeach
                    @endif

                </div>
                @if (Auth::check())
                <div class="row-fluid">
                    <div class="pull-right">
                        {{ HTML::link('#', trans('Request New Category'),
                            ['class' => 'btn btn-primary btn-small', 'id' => 'requestNew']
                        ) }}
                    </div>
                </div>
                @endif

            </div>

        </div>

        <!-- @end .row -->
        @if (Auth::check())
        <div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            @include('category.modal-request-new')
        </div>
        @endif

        @include('_partials.get-in-touch')

    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
@overwrite

@section('jqueryReady')
@parent
$('#requestNew').click(function(){
    @if (Auth::check())
        $('#myModal').on('shown.bs.modal', function(){
            $(this).css({
                height: $('#myModal .span9').outerHeight()
            });
        });
        $('#myModal').modal('show');
    @else
        window.location.assign('/login');
    @endif
});

$('form[data-async]').on('submit', function(event) {
    var $form = $(this);

    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: $form.serialize(),

        success: function(data, status) {
            if (data.result === 'failed'){
                // Set a flash message with the errors
                var flashError = "<p>" + data.errorMessage + "</p>";
                $('#modal-flash-wrapper').empty().append(flashError).show();
                $('#myModal').css('height', $('#myModal .span9').outerHeight());
            } else {
                // Clear the form and the form's flash div
                $("#lessonForm").trigger('reset');
                $('#modal-flash-wrapper').empty().hide();
                // Hide the modal
                $('#myModal').modal('hide');
                window.alert("Your request has been noted. Thank you.");
            }
        }
    });
    event.preventDefault();
});
@stop