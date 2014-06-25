<?php
/**
 * tutor.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/25/14
 * Time: 7:58 AM
 */
?>
<?php echo $this->Form->create('User', array('class' => 'form-horizontal', 'type' => 'file')); ?>
    <div class="control-group">
        <label class="control-label">I am a...:</label>
        <div class="controls">

            <?php
            $options = array('2' => ' Expert', '4' => ' Student');
            $attributes = array('legend' => false, 'checked' => $default, 'value' => $default,
                'onclick' => 'update(this.value)',
                'label' => array(
                    'class' => 'radio inline', 'style' => 'padding-left:1px;padding-right:10px'));
            echo $this->Form->radio('role_id', $options, $attributes);
            ?>
        </div>

        <div class="control-group">
            <label class="control-label" for="postalAddress">Subject:</label>
            <div class="controls">
                <?php echo $this->Form->textarea('subject', array('class' => 'textarea', 'placeholder' => 'Type Your Subjects', 'rows' => 3)); ?>

                <br>
                <span class="FontStyle11"><em><?php echo __("Separate Subjects with commas") ?></em></span> </div>
        </div>

        <div class="row-fluid">
            <div class="control-group">
                <label class="control-label" for="UserProfilepic">Upload Your Pic</label>
                <div class="form-group span7 controls">
                    <?php
                    echo $this->Form->file('profilepic', array('label' => false));
                    ?>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="firstName"><?php echo __("Username:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('username', array('class' => 'textbox', 'placeholder' => "Username", 'label' => false)); ?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="firstName"><?php echo __("First Name:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('name', array('class' => 'textbox', 'placeholder' => "First Name", 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="lastName"><?php echo __("Last Name:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('lname', array('class' => 'textbox', 'placeholder' => "Last Name", 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="inputEmail"><?php echo __("Email Address:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('email', array('class' => 'textbox', 'placeholder' => "email@email.com", 'label' => false)); ?>


            </div>
        </div>
        <div id="signupTuter">
            <div class="control-group">
                <label class="control-label" for="postalAddress"><?php echo __("Qualification:") ?></label>
                <div class="controls">
                    <?php echo $this->Form->textarea('qualification', array('class' => 'textarea', 'placeholder' => "Type Your Qualification")); ?>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="postalAddress"><?php echo __("Teaching Experience:") ?></label>
                <div class="controls">
                    <?php echo $this->Form->textarea('teaching_experience', array('class' => 'textarea', 'placeholder' => "Teaching Experience")); ?>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="postalAddress"><?php echo __("Extracurricular Interests:") ?></label>
                <div class="controls">
                    <?php echo $this->Form->textarea('extracurricular_interests', array('class' => 'textarea', 'placeholder' => "Extracurricular Interests")); ?>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail"><?php echo __("Other experience:") ?></label>
                <div class="controls">
                    <?php echo $this->Form->input('other_experience', array('class' => 'textbox', 'placeholder' => "English with a Concentration in Theater", 'label' => false)); ?>


                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputEmail"><?php echo __("University:") ?></label>
                <div class="controls">
                    <?php echo $this->Form->input('university', array('class' => 'textarea', 'rows' => 2,'placeholder' => "Barnard/University, Class of 2013", 'label' => false)); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="postalAddress"><?php echo __("Expertise in (Subject)") ?>:</label>
                <div class="controls">
                    <?php echo $this->Form->textarea('expertise', array('class' => 'textarea', 'placeholder' => "Top Subjects")); ?>

                </div>
            </div>
        </div>

        <p><strong><?php echo __("Account Information:") ?></strong></p>
        <div class="control-group">
            <label class="control-label" for="inputPassword"><?php echo __("Password:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('password', array('class' => 'textbox', 'placeholder' => "Password", 'label' => false)); ?></div>
            <div class="controls">
                <div class="password-security" id="result" style="width:269px; height:10px;">
                    <div class="security"></div>
                    <?php echo __("Level of Security") ?></div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="confirmPassword"><?php echo __("Confirm Password:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('verify_password', array('type' => 'password', 'class' => 'textbox', 'placeholder' => "Confirm Password", 'label' => false)); ?>
            </div>
        </div>

    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">

                <?php echo $this->Form->checkbox('terms', array('hiddenField' => false)); ?>
                <label><?php echo __("&nbsp;I agree with Botangle's <a href='/demos/botangle/privacy'>Terms of Use and Privacy Policy.</a>") ?>.</label></label>
        </div>
    </div>
    <div class="control-group form-actions">
        <?php
        echo $this->Form->button('Create My Account', array('type' => 'submit', 'class' => 'btn btn-primary'));
        echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-reset'));
        ?>

    </div>
<?php echo $this->Form->end(); ?>
