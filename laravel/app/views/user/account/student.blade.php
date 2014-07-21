@extends('layout')

@section('content')
{{--
echo $this->Html->script(array('/croogo/js/autocomplete/jquery-1.9.1',
	'/croogo/js/autocomplete/jquery.ui.core', '/croogo/js/autocomplete/jquery.ui.widget', '/croogo/js/autocomplete/jquery.ui.position', '/croogo/js/autocomplete/jquery.ui.menu', '/croogo/js/autocomplete/jquery.ui.autocomplete',
));

echo $this->Html->css(array(
	'/croogo/css/autocomplete/themes/base/jquery.ui.all', '/croogo/css/autocomplete/demos',
));
--}}
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

						<div class="row-fluid">
							<div class="control-group">
                                {{ Former::text('username')
                                ->addClass('textbox')
                                ->placeholder(trans('Username'))
                                ->label(__('Username'))
                                ->disabled()
                                ->required()
                                }}
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
                                {{ Former::text('name')
                                ->addClass('textbox')
                                ->placeholder(trans('First Name'))
                                ->label(__('First Name'))
                                ->required()
                                }}
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="lastName">Last Name:</label>
								<div class="controls">
									<?php // echo $this->Form->input('lname', array('class' => 'textbox', 'placeholder' => "Last Name", 'label' => false)); ?>

								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="inputEmail">Email Address:</label>
								<div class="controls">
									<?php // echo $this->Form->input('email', array('class' => 'textbox', 'placeholder' => "test@test.com", 'label' => false, 'disabled' => 'disabled')); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
                            {{ Former::actions(
                                Former::submit(trans('Update Info'))
                                ->addclass('btn btn-primary')
                                ->name('update_info')
                            )->addclass('control-group')
                            }}
						</div>
                        {{ Form::close() }}
					</div>
				</div>

                @include('user.account.change-password', array('user' => $user))
			</div>
		</div>
	</div>
	<!-- @end .row --> 
</div>
<!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here-->
@overwrite