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
    <?php include('leftpanel.ctp') ?> 
      <div class="span9">
      <h2 class="page-title">Report a Bug</h2>
      <div class="StaticPageRight-Block">
     
    
       <div class="PageLeft-Block">
        
        <form class="form-inline form-horizontal" role="form" action="<?php echo $this->webroot?>reportbug"  method="post">
         <div class="row-fluid">
		 
          <div class="form-group span6 ">
            <label class="sr-only" for="your_name">Your Name</label>
            <input type="text" class="form-control textbox1 " name="data[page][name]" id="your_name" placeholder="Your Name" required="required">
          </div>
          <div class="form-group span6">
            <label class="sr-only" for="emial">Your Email Address</label>
            <input type="email" class="form-control textbox1" id="emial" placeholder="Your Email Address" name="data[page][email]" required="required">
          </div>
          </div>
           <div class="row-fluid marT10">
         <div class="span12 form-group">
          <label class="sr-only" for="category">Subject</label>
            <input type="text" class="form-control textbox1" id="category" placeholder="Select Category" required="required"  name="data[page][subject]">
       </div></div>
        <div class="row-fluid">
         <div class="span12 form-group marT10">
          <label class="sr-only" for="message">Error</label>
           <textarea id="select-subject" class="textarea" placeholder="Your Message" rows="3" required="required"  name="data[page][error]"></textarea>
       </div></div>
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