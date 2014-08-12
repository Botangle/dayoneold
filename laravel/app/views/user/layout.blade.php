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

        {{-- @TODO: can we take this out?  seems like we've already got all of this in the header-inner setup
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
                }); --}}
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