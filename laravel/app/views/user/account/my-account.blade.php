@extends('layout')

@section('content')
<style>
    .fileinput-exists .fileinput-new, .fileinput-new .fileinput-exists {
        display: none;
    }
    .btn-file > input {
        cursor: pointer;
        direction: ltr;
        font-size: 23px;
        margin: 0;
        opacity: 0;
        position: absolute;
        right: 0;
        top: 0;
        transform: translate(-300px, 0px) scale(4);
    }
    input[type="file"] {
        display: block;
    }
</style>
<script>
    jQuery(function() {

        function split(val) {
            return val.split(/,\s*/);
        }
        function extractLast(term) {
            return split(term).pop();
        }

        {{-- @TODO: can we take this out?  seems like we've already got all of this in the header-inner setup --}}
            jQuery("#UserSubject")
                // don't navigate away from the field on tab when selecting an item
                .bind("keydown", function(event) {
                    if (event.keyCode === jQuery.ui.keyCode.TAB &&
                        jQuery(this).data("ui-autocomplete").menu.active) {
                        event.preventDefault();
                    }
                })
                .autocomplete({
                    source: function(request, response) {
                        $.getJSON("/subject/search", {
                            term: extractLast(request.term)
                        }, response);
                    },
                    search: function() {
                        // custom minLength
                        var term = extractLast(this.value);
                        if (term.length < 2) {
                            return false;
                        }
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function(event, ui) {
                        var terms = split(this.value);
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push(ui.item.value);
                        // add placeholder to get the comma-and-space at the end
                        terms.push("");
                        this.value = terms.join(", ");
                        return false;
                    }
                });
        });
</script>
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
                <h2 class="page-title"><?php echo trans("My Account") ?></h2>
                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">
                        <p class="FontStyle20 color1"><?php echo trans("Update Info") ?></p>
                        {{ Former::open()
                        ->method('POST')
                        ->class('form-base form-horizontal')
                        }}

                        {{ Former::populate($user) }}

                        {{ Former::hidden('id') }}

                        <div class="row-fluid">
                            <div class="control-group">
                                <label class="control-label"></label>
                                <div class="form-group span4 controls">
                                    {{ Html::image(url($user->picture), 'student', array('class' => 'img-circle img-profilepic')) }}
                                </div>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <div class="control-group">
                                {{ Form::label('profilepic', 'Upload Your Pic', array('class' => 'control-label')) }}
                                <div class="form-group span7 controls">
                                    {{ Form::file('profilepic', array()) }}
                                </div>
                            </div>
                        </div>

                        @if ($mode == 'expert')
                            @include('user.account.expert-fields')
                        @elseif ($mode == 'student')
                            @include('user.account.student-fields')
                        @endif

                        <div class="row-fluid">
                            {{ Former::actions(
                            Former::submit(trans('Update Info'))
                            ->addClass('btn btn-primary')
                            ->name('update_info')
                            )->addClass('control-group')
                            }}
                        </div>
                        {{ Former::close() }}
                    </div>
                    <!-- @end .PageLeft-Block -->
                </div>
                <!-- @end .StaticPageRight-Block -->

                @include('user.account.change-password', array('user' => $user))
            </div>
            <!-- @end .span9 -->
        </div>
        <!-- @end .row -->
    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
@overwrite