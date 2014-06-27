<?php
/**
 * student.ctp
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 6/25/14
 * Time: 8:00 AM
 */
?>
<?php echo $this->Form->create('RegisterStudentForm', array('class' => 'form-horizontal')); ?>
<div class="control-group">
    <label class="control-label"><?php echo __("I am a...:") ?></label>
    <div class="controls">
        <?php echo $this->Form->radio(
            'role_id',
            array(
                '2' => ' Expert',
                '4' => ' Student'
            ),
            array(
                'legend'    => false,
                'checked'   => $default,
                'value'     => $default,
                'onclick'   => 'update(this.value)',
                'label' => array(
                    'class' => 'radio inline',
                    'style' => 'padding-left:1px;padding-right:10px'
                ),
            )
        ); ?>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail"><?php echo __("Email Address:") ?></label>
        <div class="controls">
            <?php echo $this->Form->input('email', array('class' => 'textbox', 'placeholder' => "email@email.com", 'label' => false)); ?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="firstName"><?php echo __("Username:") ?></label>
        <div class="controls">
            <?php echo $this->Form->input('username', array('class' => 'textbox', 'placeholder' => "Username", 'label' => false)); ?>
        </div>

        <div class="control-group">
            <label class="control-label" for="firstName"><?php echo __("First Name:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('firstname', array('class' => 'textbox', 'placeholder' => "First Name", 'label' => false)); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="lastName"><?php echo __("Last Name:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('lastname', array('class' => 'textbox', 'placeholder' => "Last Name", 'label' => false)); ?>
            </div>
        </div>


        <p><strong><?php echo __("Account Information:") ?></strong></p>
        <div class="control-group">
            <label class="control-label" for="inputPassword"><?php echo __("Password:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('password', array('class' => 'textbox', 'placeholder' => "Password", 'label' => false)); ?>
            </div>
            <div class="controls">
                <div class="password-security" id="result" style="width:269px; height:10px;">
                    <div class="security"></div>
                    <?php echo __("Level of Security") ?></div>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="confirmPassword"><?php echo __("Confirm Password:") ?></label>
            <div class="controls">
                <?php echo $this->Form->input('password_confirmation', array('type' => 'password', 'class' => 'textbox', 'placeholder' => "Confirm Password", 'label' => false)); ?>
            </div>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox termcls">

                <?php echo $this->Form->checkbox('terms', array('hiddenField' => false)); ?>
                <label><?php echo __("&nbsp;I agree with Botangle's <a href='/demos/botangle/privacy'>Terms of Use and Privacy Policy.</a>.") ?></label></label>
        </div>
    </div>
    <div class="control-group form-actions">
        <?php
        echo $this->Form->button('Create My Account', array('type' => 'submit', 'class' => 'btn btn-primary'));
        echo $this->Form->button('Reset', array('type' => 'reset', 'class' => 'btn btn-reset'));
        ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
