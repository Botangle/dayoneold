<?php
/**
 * Default Theme for Croogo CMS
 *
 * @author Fahad Ibnay Heylaal <contact@fahad19.com>
 * @link http://www.croogo.org
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php //echo $title_for_layout; &raquo;?> <?php echo Configure::read('Site.title'); ?></title>
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'>
	<?php
		echo $this->Meta->meta();
        echo $this->fetch('meta');
		echo $this->Layout->feed();
		echo $this->Html->css(array(
			'/croogo/css/bootstrap',
			'/croogo/css/bootstrap.min',
			'/croogo/css/bootstrap-responsive.min',
			'/croogo/css/global',
			'/croogo/css/navbar-fixed-top',
			'/croogo/css/dev',
		));
		echo $this->Layout->js();
		echo $this->Html->script(array(
			'/croogo/js/jquery/jquery-1',
			'/croogo/js/jquery/bootstrap',
			));
		echo $this->Blocks->get('css');
		echo $this->Blocks->get('script');
		$CurrentController = $this->params['controller'];
		$CurrentAction = $this->params['action'];
?>
</head>
<body>
<?php echo $this->element('navigation'); ?>
<!--Wrapper Main Navi Block End Here--> 
<!--Wrapper Bannerblock Block Start Here-->
<?php if($CurrentController=='nodes' && $CurrentAction =='promoted'){
			echo $this->element('header'); 
		}else{ 
			echo $this->element('headerinner'); 
		 } 
?>


<!--Wrapper Bannerblock Block End Here--> 
<!--Wrapper HomeQuoteBlock Block End Here-->
<!--Wrapper main-content Block Start Here-->

								<?php
								echo $content_for_layout;
						   ?>
<!--Wrapper main-content Block End Here-->
<!--Wrapper main-content1 Block Start Here-->
<?php echo $this->element('footermiddle'); ?>
<!--Wrapper main-content1 Block End Here-->

<?php echo $this->element('footerbottom'); ?>
	<?php
		//echo $this->Blocks->get('scriptBottom');
		//echo $this->Js->writeBuffer();
	?>
	</body>
</html>