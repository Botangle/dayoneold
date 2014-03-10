<header id="Bannerblock2">
    <div class="container text-center">
        <div class="row-fluid">
            <div class="span4 Header-title01">
                <p><?php echo __("Join")?><br>
                    <span><?php echo __("Botangle")?></span></p>
            </div>
            <div class="span3 pull-right">
                <div class="Header-search">
                    <input name="" type="text">
                    <img src="images/search-img.jpg" class="submit" alt="search"></div>
                <div class="Header-Free-info"><?php echo __("Find help immediatly?")?><br>
                    <span><?php echo __("Try for 7 days free!")?></span></div>
            </div>
        </div>
    </div>
</header>

<div id="HomeServices">
    <?php echo $this->Element("breadcrumbs",array('breadcrumbs'=>array('Sign Up'=>'Sign Up'))) ?>
</div>

<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <h2 class="page-title"><?php echo __("Botangle Sign Up")?></h2>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span9 PageLeft-Block">
                <p class="FontStyle20"><?php echo __("Create your Botangle Account") ?></p>

                <p><?php echo __("It only takes a few minutes to register with Botangle and you get amazing features! Fill out the information below! ")?></p>

                <div class="Signup">
                    <?php echo $this->Session->flash();
                    echo $this->Form->create('User',array('class'=>'form-horizontal','novalidate'=>'novalidate'))
                    ?>

                    <div class="control-group">
                        <label class="control-label"><?php echo __("I am a...")?>:</label>

                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" name="genderRadios" value=" <?php echo __(" Tutor")?> "
                                selected="selected">
                                <?php echo __("Tutor")?> </label>
                            <label class="radio inline">
                                <input type="radio" name="genderRadios" value=" <?php echo __(" Student")?>" >
                                <?php echo __("Student")?> </label>
                        </div>
                        <div class="control-group required">
                            <label class="control-label" for="postalAddress"><?php echo __("Subject")?>:</label>

                            <div class="controls">
                                <textarea class="textarea" rows="3" id="select-subject"
                                          placeholder="type your subjects"></textarea>
                                <br>
                                <span class="FontStyle11"><em><?php echo __("Separate Subjects with commas")?></em></span>
                            </div>
                        </div>
                        <div class="control-group required">
                            <label class="control-label" for="firstName"><?php echo __("First Name")?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('name',array('label' =>
                                false,'class'=>'textbox','placeholder'=>__('First Name')));?>

                            </div>
                        </div>
                        <div class="control-group required">
                            <label class="control-label" for="lastName"><?php echo __("Last Name")?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('lname',array('label' =>
                                false,'class'=>'textbox','placeholder'=>__('Last Name')));?>

                            </div>
                        </div>
                        <div class="control-group  required">
                            <label class="control-label" for="inputEmail"><?php echo __("Email Address")?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('email',array('label' =>
                                false,'class'=>'textbox','placeholder'=>__('Email')));?>

                            </div>
                        </div>
                        <div class="control-group required">
                            <label class="control-label" for="postalAddress"><?php echo __("Qalification")?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->textarea('qualification',array('label' =>
                                false,'class'=>'textarea','placeholder'=>__('type your subjects'),'rows'=>3));?>

                            </div>
                        </div>
                        <div class="control-group required">
                            <label class="control-label" for="postalAddress"><?php echo __("Teaching Experience")?>
                                :</label>

                            <div class="controls">
                                <?php echo $this->Form->textarea('teaching_experience',array('label' =>
                                false,'class'=>'textarea','placeholder'=>__('type your Experience'),'rows'=>3));?>
                            </div>
                        </div>
                        <div class="control-group required">
                            <label class="control-label"
                                   for="postalAddress"><?php echo __("Extracurricular Interests")?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->textarea('extracurricular_interests',array('label' =>
                                false,'class'=>'textarea','placeholder'=>__('type your Extracurricular
                                Interests'),'rows'=>3));?>
                            </div>
                        </div>
                        <p><strong><?php echo __("Account Information")?>:</strong></p>

                        <div class="control-group required">
                            <label class="control-label" for="inputPassword"><?php echo __("Password")?>:</label>

                            <div class="controls">
                                <?php echo $this->Form->input('password',array('label' =>
                                false,'class'=>'textbox','type'=>'password','placeholder'=>__('Password')));?>
                            </div>
                            <div class="controls">
                                <div class="password-security">
                                    <img src="images/password-security.jpg" alt="password"
                                         align="absmiddle"><?php echo __("Level of Security")?></div>
                            </div>
                        </div>
                        <div class="control-group required">
                            <label class="control-label" for="confirmPassword"><?php echo __("Confirm Password")?>
                                :</label>

                            <div class="controls">
                                <?php echo $this->Form->input('Confpassword',array('label' =>
                                false,'class'=>'textbox','type'=>'password','placeholder'=>__('Password')));?>

                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <label class="checkbox">
                                <input type="checkbox">
                                <?php echo __('I agree with Botangle\'s <a href="#">Terms of Use and Privacy Policy.</a>
                                ')?> .</label>
                        </div>
                    </div>
                    <div class="control-group form-actions">
                        <button type="submit" class="btn btn-primary"
                                onclick="return validatefrom.validation('UserRegistrationForm')">Create My Account
                        </button>
                        <button type="reset" class="btn btn-reset"><?php echo __("Reset")?></button>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>
            </div>
            <div class="span3 PageRight-Block">
                <p class="FontStyle20"><?php echo __("Already a member? Sign In here")?></p>

                <p><?php echo __("Click here to sign In in the Botangle ! ")?></p><br>
                <br>
                <button type="submit" class="btn btn-primary"><?php echo __("Sign In")?></button>
            </div>
        </div>
        <!-- @end .row -->

        <div class="row-fluid ">
            <div class="Get-in-Touch offset6">
                <p class="FontStyle20"><strong><?php echo __("Get in touch with us")?>:</strong></p>
            </div>

        </div>
        <div class="row-fluid ">
            <div class="Social-Boxs Social-Email span3">
                <p class="FontStyle20"><a href="#"><?php echo __("Email Us")?></a></p>
            </div>

            <div class="Social-Boxs Social-FB span3">
                <p class="FontStyle20"><a href="#"> <?php echo __("Facebook Us")?> </a></p>
            </div>

            <div class="Social-Boxs Social-Tweet span3">
                <p class="FontStyle20"><a href="#"><?php echo __(" Follow Us")?></a></p>
            </div>

            <div class="Social-Boxs Social-Linkedin span3">
                <p class="FontStyle20"><a href="#"> <?php echo __("LinkedIn")?> </a></p>
            </div>

        </div>


    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here-->
<?php
echo $this->Html->script(array('common','frontdata'));
echo $this->fetch('script');?>