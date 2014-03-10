<!--Wrapper HomeServices Block Start Here-->

<?php 
 
echo $this->element("breadcrame",array('breadcrumbs'=>
array('Popular Categories'=>'Popular Categories'))
);

?>
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
    <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <h2 class="page-title"></h2>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12 PageLeft-Block">
                <div class="search-box">
                    <?php echo $this->Form->create('Category',array('class'=>'form-inline
                    form-horizontal',"role"=>"form"));

                    ?>
                    <div class="row-fluid">
                        <div class="span10"><input name="data[search]" type="text" class="textbox01"
                                                   placeholder="Search"></div>
                        <div class="span2">
                            <?php
			echo $this->Form->button(__('Search'), array('type' => 'submit','class'=>'btn btn-primary btn-primary2'));
                            ?>

                        </div>
                    </div>
                    <?php echo $this->Form->end();?>
                </div>

                <div class="row-fluid">
                    <?php  if(!empty($categories)){
				$i = 0;
					 
				foreach($categories['Category'] as $k=>$v){
                    echo '
                    <div class="span3 Category-block">
                        <div class="title01">'.$k.'</div>
                        <ul>';
                            foreach($v as $indx=>$value1){
                            foreach($value1 as $key=>$value){
                            echo '
                            <li><a href="'.$this->webroot.'users/topchart/'.$key.'">'.$value.'</a></li>
                            ';
                            }
                            }
                            echo '
                        </ul>
                    </div>
                    ';
                    $i++;
                    if($i%4==0){
                    echo '
                </div>
                <div class="row-fluid">';
                    }
                    }
                    }
                    ?>

                </div>


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