<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">

            </div>
        </div>
        <div class="row-fluid">
            <?php echo $this->Element("myaccountleft") ?>
            <div class="span9">
                <h2 class="page-title"><?php echo __("Messages")?></h2>
                <div class="StaticPageRight-Block">
                        <div class="PageLeft-Block">
                            <p>You haven't sent any messages yet, so there is nothing here to see :-)</p>
                            <p>To start sending folks messages, browse through the people in
                                <a href="<?php echo $this->webroot?>users/topcharts/">Top Charts</a> and
                                <a href="<?php echo $this->webroot?>categories">Categories</a>
                            and message someone there!</p>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>