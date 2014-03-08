<!--Wrapper HomeServices Block Start Here-->
 
<?php
echo $this->element("breadcrame",array('breadcrumbs'=>
	array(__("Whiteboard")=>__("Whiteboard")))
	);?>
 <script src="<?php echo $this->webroot?>croogo/js/countdown.js" type="text/javascript"></script>

 
 
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
      
      <div class="StaticPageRight-Block">
      <div class="PageLeft-Block">
        
        <div class="Lesson-row active">
         <div class="row-fluid">
        	 <?php
				
				$twiddlaid = $lesson[0]['Lesson']['twiddlameetingid'];
			 
			  if($this->Session->read('Auth.User.role_id')==4){ ?>
				 <iframe src="http://www.twiddla.com/api/start.aspx?sessionid=<?php echo $twiddlaid?>&controltype=2&loginusername=deepakjain&password=123456789" frameborder="0" width="617" height="600" style="border:solid 1px #555;"></iframe> 
			 <?php } else {?>
				 <iframe src="http://www.twiddla.com/api/start.aspx?sessionid=<?php echo $twiddlaid?>&controltype=1&loginusername=deepakjain&password=123456789&guestname=deep" frameborder="0" width="617" height="600" style="border:solid 1px #555;"></iframe> 
			 <?php } ?>
            </div>
            </div>
        
        
<script type="application/javascript">
var myCountdown1 = new Countdown({
				time: 86400 * 3, // 86400 seconds = 1 day
				width:300, 
				height:60,  
				rangeHi:"day",
				style:"flip"	// <- no comma on last item!
				});

</script>
        
       </div>
        
      
      </div>
      </div>
    </div>
    <!-- @end .row --> 
    


    
    
    
  </div>
  <!-- @end .container --> 
</div>
<!--Wrapper main-content Block End Here--> 