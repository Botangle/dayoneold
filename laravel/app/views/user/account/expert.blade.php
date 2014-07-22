@extends('user.account.student')
@section('profile-fields')
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="postalAddress"><?php echo trans("Subject:") ?></label>
								<div class="controls">
									<?php //echo $this->Form->textarea('subject', array('class' => 'textarea', 'placeholder' => "Teaching Experience", 'rows' => 3)); ?>
									<br>
									<span class="FontStyle11"><em><?php echo trans("Separate Subjects with commas") ?></em></span> </div>
							</div>
						</div>
@parent
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="postalAddress">Qualification:</label>
								<div class="controls">
									<?php // echo $this->Form->textarea('qualification', array('class' => 'textarea', 'placeholder' => "type your Qualification", 'rows' => 3)); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="postalAddress">Teaching Experience:</label>
								<div class="controls">
									<?php // echo $this->Form->textarea('teaching_experience', array('class' => 'textarea', 'placeholder' => "Teaching Experience", 'rows' => 3)); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="postalAddress">Extracurricular Interests:</label>
								<div class="controls">
									<?php // echo $this->Form->textarea('extracurricular_interests', array('class' => 'textarea', 'placeholder' => "Extracurricular Interests", 'rows' => 3)); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="inputEmail"><?php echo trans("Other experience:") ?></label>
								<div class="controls">
									<?php // echo $this->Form->input('other_experience', array('class' => 'textbox', 'placeholder' => "English with a Concentration in Theater", 'label' => false)); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="inputEmail"><?php echo trans("University:") ?></label>
								<div class="controls">
									<?php // echo $this->Form->input('university', array('class' => 'textarea', 'placeholder' => "Barnard/University, Class of 2013", 'label' => false)); ?>


								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="postalAddress"><?php echo trans("Expertise in (Subject)") ?>:</label>
								<div class="controls">
									<?php // echo $this->Form->textarea('expertise', array('class' => 'textarea', 'placeholder' => "Top Subjects")); ?>

								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="inputEmail">FB link:</label>
								<div class="controls">
									<?php // echo $this->Form->input('link_fb', array('class' => 'textbox', 'placeholder' => "Your Facebook link", 'label' => false)); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="inputEmail">Twitter link:</label>
								<div class="controls">
									<?php // echo $this->Form->input('link_twitter', array('class' => 'textbox', 'placeholder' => "Your Twitter link", 'label' => false)); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="inputEmail">Google+ link:</label>
								<div class="controls">
									<?php // echo $this->Form->input('link_googleplus', array('class' => 'textbox', 'placeholder' => "Your Google+ link", 'label' => false)); ?>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="control-group">
								<label class="control-label" for="inputEmail">Thumblr link:</label>
								<div class="controls">
									<?php // echo $this->Form->input('link_thumblr', array('class' => 'textbox', 'placeholder' => "Your Thumblr link", 'label' => false)); ?>
								</div>
							</div>
						</div>
@stop