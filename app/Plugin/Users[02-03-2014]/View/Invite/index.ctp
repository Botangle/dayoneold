<!--Wrapper HomeServices Block Start Here-->
<?php 

echo $this->element("breadcrame",array('breadcrumbs'=>
array('Report a Bug'=>'Report a Bug'))
)
?>

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
                <h2 class="page-title">Invite Friends</h2>

                <div class="StaticPageRight-Block">


                    <div class="PageLeft-Block">

                        <form class="form-inline form-horizontal" role="form"
                              action="<?php echo $this->webroot?>users/invite" method="post">
                            <input type="hidden" class="form-control textbox1 " name="data[Invite][invited_by]"
                                   id="your_name" placeholder="Friend Name"
                                   value="<?php echo $this->Session->read('Auth.User.id'); ?>">

                            <div class="row-fluid">
                                <div class="form-group span6 ">
                                    <label class="sr-only" for="your_name">Name</label>
                                    <input type="text" class="form-control textbox1 " name="data[Invite][name]"
                                           id="your_name" placeholder="Friend Name" required="required">
                                </div>
                                <div class="form-group span6">
                                    <label class="sr-only" for="emial">Email Address</label>
                                    <input type="email" class="form-control textbox1" id="emial" placeholder="Message"
                                           name="data[Invite][email]" required="required">
                                </div>
                            </div>

                            <div class="row-fluid">
                                <div class="span12 form-group marT10">
                                    <label class="sr-only" for="message">Message</label>
                                    <textarea id="select-subject" class="textarea" placeholder="Your Message" rows="3"
                                              required="required" name="data[Invite][message]"></textarea>
                                </div>
                            </div>
                            <div class="row-fluid marT10">
                                <div class="span12 ">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- @end .row -->


    </div>
    <!-- @end .container -->
</div> 