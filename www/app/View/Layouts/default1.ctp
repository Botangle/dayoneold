<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<link rel="shortcut icon" href="ico/favi.png">
<title>Botangle - A new concept for Online Education</title>

<!-- Bootstrap core CSS -->
<link href="<?php echo $this->webroot?>css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $this->webroot?>css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $this->webroot?>css/bootstrap-responsive.min.css">
<link href="<?php echo $this->webroot?>css/global.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="<?php echo $this->webroot?>css/navbar-fixed-top.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy this line! -->
<!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<!--Wrapper Main Navi Block Start Here-->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
 <?php echo $this->Element('header') ?>
</div>

<!--Wrapper HomeQuoteBlock Block End Here-->
<!--Wrapper main-content Block Start Here-->
<div id="main-content">
	 
	<?php 
	
	echo $this->fetch('content') ?>
</div>
<!--Wrapper main-content Block End Here-->
<!--Wrapper main-content1 Block Start Here-->
<div id="main-content1"> 
  
	<?php echo $this->Element('footersection'); ?>

</div>
<!--Wrapper main-content1 Block End Here-->
<!--Wrapper footer Block Start Here-->
<div id="footer">
	<?php echo $this->Element('footer'); ?>

</div>
<!--Wrapper footer Block End Here-->

<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="<?php echo $this->webroot?>js/jquery-1.js"></script> 
<script src="<?php echo $this->webroot?>js/bootstrap.js"></script>
</body>
</html>