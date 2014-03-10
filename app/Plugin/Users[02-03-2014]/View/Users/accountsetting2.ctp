<?php
 echo $this->Layout->js();
echo $this->Html->script(array(
'/croogo/js/fileupload',
'/croogo/js/jquery/bootstrap',
));
echo $this->element("breadcrame",array('breadcrumbs'=>
array('My Dashboard'=>'My Dashboard'))
);

?>
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
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
        <div class="row-fluid">
            <?php echo $this->Element("myaccountleft") ?>
            <div class="span9">
                <h2 class="page-title"><?php echo __("Account Settings")?></h2>

                <div class="StaticPageRight-Block">
                    <div class="PageLeft-Block">
                        <p class="FontStyle20 color1"><?php echo __("Change Password")?></p>
                        <?php echo $this->Form->create('User',array('class'=>'form-inline
                        form-horizontal',"role"=>"form"));
                        $this->request->data = $this->Session->read("Auth.User");
                        echo $this->Form->input('id',array('value'=>$this->request->data['id']));
                        ?>

                        <div class="row-fluid">
                            <div class="form-group span5">
                                <label class="sr-only" for="Username2"><?php echo __("Old Password")?></label>

                                <?php echo $this->Form->input('password',array('class'=>'form-control
                                textbox1','placeholder'=>"Old Password",'label' =>
                                false,'id'=>'old_password','name'=>'data[User][oldpassword]'));?>
                            </div>

                        </div>
                        <br>

                        <div class="row-fluid">
                            <div class="form-group span5">
                                <label class="sr-only" for="Username2">New Password</label>

                                <?php echo $this->Form->input('password',array('class'=>'form-control
                                textbox1','placeholder'=>"New Password",'label' => false));?>
                            </div>

                            <div class="form-group span5">
                                <label class="sr-only" for="Password2">Confirm Password</label>
                                <?php echo $this->
                                Form->input('verify_password',array('type'=>'password','class'=>'form-control
                                textbox1','placeholder'=>"Confirm Password",'label' => false));?>

                            </div>
                        </div>
                        <br>

                        <div class="row-fluid">
                            <div class="span12">
                                <?php
			echo $this->Form->button(__('Update Password'), array('type' => 'submit','class'=>'btn btn-primary'));
                                ?>

                            </div>
                        </div>
                        <?php echo $this->Form->end();?>


                    </div>

                    <div class="PageLeft-Block">
                        <p class="FontStyle20 color1">Update Profile Pic</p>


                        <?php echo $this->Form->create('User',array('class'=>'form-inline
                        form-horizontal',"role"=>"form",'type' => 'file'));
                        $this->request->data = $this->Session->read("Auth.User");
                        echo $this->Form->input('id',array('value'=>$this->request->data['id']));
                        echo $this->Form->input('posttype',array('value'=>'pic','type'=>'hidden'));
                        ?>
                        <div class="row-fluid">
                            <div class="form-group span7">
                                <label class="sr-only" for="Username2">Select Your Pic</label>
                                <?php
			 echo $this->Form->input('profilepic',array( 'type' => 'file','label'=>false));
                                ?>
                            </div>

                            <div class="form-group span2">
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </div>

                        </div>

                        <?php echo $this->Form->end();?>


                    </div>
                </div>
            </div>
        </div>
        <!-- @end .row -->


    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here--> 