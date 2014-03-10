<!--Wrapper HomeServices Block Start Here-->

<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
array(__('Password Recovery Confirmation')=>__('Password Recovery Confirmation')))
);

?>
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <h2 class="page-title">Botangle Password Recovery</h2>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span9 PageLeft-Block">
                <p class="FontStyle20">Check your email</p>

                <p><?php echo $this->Layout->sessionFlash();?></p><br>

                <div class="Signup">

                    <form class="form-inline form-horizontal" role="form">
                        <div class=" span12 text-center">
                            <?php
echo $this->Html->link(__("Return Home"), array('action'=> 'login'), array( 'class' => 'btn btn-primary'))

                            ?>

                        </div>

                    </form>


                </div>
            </div>
            <div class="span3 PageRight-Block">
                <p class="FontStyle20">Not a member? Sign Up here</p>

                <p>Get a Free Account for 7 days. Sign Up here.</p><br>
                <br>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </div>
        </div>
        <!-- @end .row -->

        <div class="row-fluid ">
            <div class="Get-in-Touch offset6">
                <p class="FontStyle20"><strong>Get in touch with us:</strong></p>
            </div>

        </div>
        <div class="row-fluid ">
            <div class="Social-Boxs Social-Email span3">
                <p class="FontStyle20"><a href="#"> Email Us</a></p>
            </div>

            <div class="Social-Boxs Social-FB span3">
                <p class="FontStyle20"><a href="#"> Facebook Us</a></p>
            </div>

            <div class="Social-Boxs Social-Tweet span3">
                <p class="FontStyle20"><a href="#"> Follow Us</a></p>
            </div>

            <div class="Social-Boxs Social-Linkedin span3">
                <p class="FontStyle20"><a href="#"> LinkedIn</a></p>
            </div>

        </div>


    </div>
    <!-- @end .container -->
</div>
<!--Wrapper main-content Block End Here--> 