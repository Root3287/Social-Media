<?php
if(!isset($_GET['step'])){
	$step = 1;
}else{
	$step = intval($_GET['step']);
}
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Install</title> 
		<meta name="author" content="Timothy Gibbons">
		<meta name="copyright" content="Copyright (C) Timothy Gibbons 2015;">
		<meta name="description" content="Social-Media">
		<meta name="keywords" content="Social-Media, Beta">
		<meta charset="UTF-8">
		<link rel="stylesheet" href="/assets/css/3.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="/assets/js/jquery.js"></script>
		<script src="/assets/js/bootstrap.js"></script>
	</head>
	<body>
		<div class="container">
			<ul class="nav nav-tabs">
				<li role="presentation" <?php if($step === 1): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Welcome</a></li>
				<li role="presentation" <?php if($step === 2): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Requirements</a></li>
				<li role="presentation" <?php if($step === 3): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Database & Setup</a></li>
				<li role="presentation" <?php if($step === 4): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Install</a></li>
				<li role="presentation" <?php if($step === 5): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>User</a></li>
				<li role="presentation" <?php if($step === 6): echo "class='active'"; else: echo "class='disabled'"; endif;?> ><a>Finished</a></li>
			</ul>
			<?php
				if($step === 1){
			?>
			<h2>Installing Social-Media</h2>
			<p>Thank you for installing Social-Media! Here is a few things you should have:</p>
			<ul>
				<li>PHP 5.3(Assuming you already installed it)</li>
				<li>MCrypt</li>
				<li>PDO</li>
				<li>MySql</li>
				<li>Apache (Assuming you already installed it)</li>
			</ul>
			<a href="/install?step=2" class="btn btn-default">Next</a>
			<?php	
				}elseif($step === 2){
					$error_message = "<span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove-circle\"></span></span>";
					$success_message = "<span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok-circle\"></span></span>";
					$error = false;
			?>
			<ul>
				<li>PHP 5.3 <?php if(version_compare(phpversion(), '5.3', '<')): echo $error_message; $error = true; else: echo $success_message; endif;?></li>
				<li>MCrypt <?php if(!function_exists("mcrypt_encrypt")): echo $error_message; $error = true; else: echo $success_message; endif;?></li>
				<li>PDO <?php if(!extension_loaded('PDO')): echo $error_message; $error = true; else: echo $success_message; endif;?></li>
			</ul>
			<a href="/install?step=3" class="btn btn-default <?php if($error): echo "disabled"; endif;?>">Next</a>
			<?php
				}elseif($step === 3){
			?>
			<form action="/install?step=4" method="post">
				<div class="form-group">
					<input name="dbAddr" placeholder="Database Address" type="text" class="form-control">
				</div>
				<div class="form-group">
					<input name="dbUser" placeholder="Database Username" type="text" class="form-control">
				</div>
				<div class="form-group">
					<input name="dbPass" placeholder="Database Password" type="password" class="form-control">
				</div>
				<div class="form-group">
					<input name="dbDatabase" placeholder="Database" type="text" class="form-control">
				</div>
				<div class="form-group">
					<input name="cookie" placeholder="Cookie Name" type="text" class="form-control">
				</div>
				<div class="form-group">
					<input name="session" placeholder="Session Name" type="text" class="form-control">
				</div>
				<div class="form-group">
					<input name="token_val" placeholder="Token Name" type="text" class="form-control">
				</div>
				<input type="submit" value="Submit" class="btn btn-default">
			</form>
			<?php
				}elseif($step === 4){
					if(Input::exists()){
						$val = new Validation();
						$validate = $val->check($_POST, [
							'dbAddr' => [
								'required' => true,
							],
							'dbUser'=>[
								'required' => true,
							],
							'dbDatabase'=> [
								'required' => true,
							],
						]);
						if($validate->passed()){
							$mysqli = new mysqli(Input::get('dbAddr'), Input::get('dbUser'), Input::get('dbPass'), Input::get('dbDatabase'));
							if($mysqli->connect_error){
								echo $mysqli->connect_error;
							}else{
								echo 1;
							}
						}
					}
			?>
			<?php 
				}
			?>
		</div>
	</body>
</html>