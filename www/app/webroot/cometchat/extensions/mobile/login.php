<?php

       include_once dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."cometchat_init.php";
       
       if(isset($_REQUEST['username']) && isset($_REQUEST['password']) && $_REQUEST['password']!= '' && $_REQUEST['username']!= '' ) {
               echo chatLogin($_REQUEST['username'],$_REQUEST['password']);    
       }
?>