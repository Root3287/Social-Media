<?php
$error_message = "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove-circle\"></span></span>";
$success_message = "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok-circle\"></span></span>";
$error = false;
?>
<ul>
	<li>
		PHP 5.3 <?php if(version_compare(phpversion(), '5.3', '<')): echo $error_message; $error = true; else: echo $success_message; endif;?>
	</li>
	<li>
		MCrypt <?php if(!function_exists("mcrypt_encrypt")): echo $error_message; $error = true; else: echo $success_message; endif;?>
	</li>
	<li>
		PDO <?php if(!extension_loaded('PDO')): echo $error_message; $error = true; else: echo $success_message; endif;?>	
	</li>
	<li>
		Writeable Install <?php if(is_writable('install.php')): echo $error_message; $error = true; else: echo $success_message; endif;?>
	</li>
	<li>
		Writeable Init <?php if(is_writable('/inc/init.php')):echo $error_message;  $error = true; else: echo $success_message;endif;?>
		</li>
	<li>Writeable Config <?php if(is_writable('/inc/config.php')): echo $error_message; $error = true; else: echo $success_message; endif;?></li>
	<li>Writeable Cache <?php if(is_writable('/cache')): echo $error_message; $error = true; else: echo $success_message; endif;?></li>
</ul>
<a href="/install?step=3" class="btn btn-default <?php if($error): echo "disabled"; endif;?>">Next</a>