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
					if(Input::exists()){
						$val = new Validation();
						$validate = $val->check($_POST, [
							'dbAddr' => [
								'required' => true,
							],
							'dbPort' =>[
								'required' => true,
							],
							'dbUser'=>[
								'required' => true,
							],
							'dbDatabase'=> [
								'required' => true,
							],
							'cookie' =>[
								'required' => true,
							],
							'session'=>[
								'required' => true,
							],
							'token_val'=>[
								'required' => true,
							],
						]);
						if($validate->passed()){
							$mysqli = new mysqli(escape(Input::get('dbAddr').":".Input::get('dbPort')), escape(Input::get('dbUser')), escape(Input::get('dbPass')), escape(Input::get('dbDatabase')));
							if($mysqli->connect_error){
								Session::flash('error', "<div class=\"alert alert-danger\">".$mysqli->connect_error."</div>");
							}else{
								$insert = 			'<?php'.PHP_EOL.
													'$GLOBALS[\'config\'] = array('.PHP_EOL.
													'		"config"=>array("name" => "Social-Media"),'.PHP_EOL.
													'		"mysql" => array('.PHP_EOL.
													'		"host" => "'.escape(Input::get("dbAddr")).'", //127.0.0.1.'.PHP_EOL.
													'		"user" => "'.escape(Input::get("dbUser")).'", //root'.PHP_EOL.
													'		"password" => "'.escape(Input::get("dbPass")).'", //password'.PHP_EOL.
													'		"db" => "'.escape(Input::get("dbDatabase")).'", //social-media'.PHP_EOL.
													'		"port" => "'.escape(Input::get("dbPort")).'", //3306'.PHP_EOL.
													'	),'.PHP_EOL.
													'	"remember" => array('.PHP_EOL.
													'		"expiry" => 604800,'.PHP_EOL.
													'	),'.PHP_EOL.
													'	"session" => array ('.PHP_EOL.
													'		"token_name" => "'.escape(Input::get("token_val")).'",'.PHP_EOL.
													'		"cookie_name"=>"'.escape(Input::get("cookie")).'",'.PHP_EOL.
													'		"session_name"=>"'.escape(Input::get("session")).'"'.PHP_EOL.
													'	),'.PHP_EOL.
													');';
								if(is_writable('inc/init.php')){
									$config = file_get_contents('inc/init.php');
									$config = substr($config, 5);

									$file = fopen('inc/init.php', 'w');
									fwrite($file, $insert.$config);
									fclose($file);

									echo '<script>window.location.replace("/install?step=4");</script>';
									die();
								}else{
									$config = file_get_contents('inc/init.php');
									$config = substr($config, 5);
									$config = nl2br(escape($insert.$config));

								?>
								Your <strong>inc/init.php</strong> file is not writeable. Please copy/paste the following into your <strong>inc/init.php</strong> file, overwriting all existing text.
								  <div class="well">
									<?php
									echo $config;
									?>
								  </div>
								  <a href="/install/?step=4" class="btn btn-primary">Continue</a>
								<?php
								die();
								}
							}
						}else{
							$message = "<div class=\"alert alert-danger\">";
							foreach ($validate->errors() as $error) {
								$message.=$error."<br/>";
							}
							$message .= "</div>";
							Session::flash('error', $message);
						}
					}
			?>
			<?php 
				if(Session::exists('error')){
					echo Session::flash('error');
				}
			?>
			<form action="" method="post">
				<div class="form-group">
					<input name="dbAddr" placeholder="Database Address" type="text" class="form-control" value="<?php echo Input::get('dbAddr');?>">
				</div>
				<div class="form-group">
					<input name="dbPort" placeholder="Database Port" type="text" class="form-control" value="<?php echo Input::get('dbPort');?>">
				</div>
				<div class="form-group">
					<input name="dbUser" placeholder="Database Username" type="text" class="form-control" value="<?php echo Input::get('dbUser');?>">
				</div>
				<div class="form-group">
					<input name="dbPass" placeholder="Database Password" type="password" class="form-control">
				</div>
				<div class="form-group">
					<input name="dbDatabase" placeholder="Database" type="text" class="form-control" value="<?php echo Input::get('dbDatabase');?>">
				</div>
				<div class="form-group">
					<input name="cookie" placeholder="Cookie Name" type="text" class="form-control" value="<?php echo Input::get('cookie');?>">
				</div>
				<div class="form-group">
					<input name="session" placeholder="Session Name" type="text" class="form-control" value="<?php echo Input::get('session');?>">
				</div>
				<div class="form-group">
					<input name="token_val" placeholder="Token Name" type="text" class="form-control" value="<?php echo Input::get('token_val');?>">
				</div>
				<input type="submit" value="Submit" class="btn btn-default">
			</form>
			<?php
				}elseif($step === 4){
					$db = DB::getInstance();

					$dbInsert = "
					CREATE TABLE `groups` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`group_name` TEXT NOT NULL,
						`permissions` TEXT NOT NULL,
						PRIMARY KEY (`id`)
					)
					COLLATE='latin1_swedish_ci'
					ENGINE=InnoDB
					;
					CREATE TABLE `settings` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`name` TEXT NOT NULL,
						`value` TEXT NULL,
						PRIMARY KEY (`id`)
					)
					COLLATE='latin1_swedish_ci'
					ENGINE=InnoDB
					;
					CREATE TABLE `users` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`username` VARCHAR(50) NOT NULL,
						`password` LONGTEXT NOT NULL,
						`salt` LONGTEXT NOT NULL,
						`name` VARCHAR(50) NOT NULL,
						`email` TEXT NOT NULL,
						`group` INT(11) NOT NULL,
						`joined` DATETIME NOT NULL,
						`private` INT(11) NULL DEFAULT '0',
						PRIMARY KEY (`id`)
					)
					COLLATE='latin1_swedish_ci'
					ENGINE=InnoDB
					;
					CREATE TABLE `user_session` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`user_id` INT(11) NOT NULL,
						`hash` LONGTEXT NOT NULL,
						PRIMARY KEY (`id`)
					)
					COLLATE='latin1_swedish_ci'
					ENGINE=InnoDB
					;
					CREATE TABLE `adm_user_session` (
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`user_id` INT(11) NOT NULL,
						`hash` LONGTEXT NOT NULL,
						PRIMARY KEY (`id`)
					)
					COLLATE='latin1_swedish_ci'
					ENGINE=InnoDB
					;
					CREATE TABLE `notification` (
						`id` BIGINT(255) NOT NULL AUTO_INCREMENT,
						`user` INT(255) NOT NULL,
						`message` MEDIUMTEXT NULL,
						`read` INT(11) NULL DEFAULT '0',
						PRIMARY KEY (`id`)
					)
					COLLATE='latin1_swedish_ci'
					ENGINE=InnoDB
					;
					CREATE TABLE `posts` (
						`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
						`user_id` INT(10) UNSIGNED NOT NULL,
						`content` LONGTEXT NOT NULL,
						`hash` TEXT NULL,
						`time` DATETIME NULL DEFAULT NULL,
						PRIMARY KEY (`id`)
					)
					COLLATE='latin1_swedish_ci'
					ENGINE=InnoDB
					;
					CREATE TABLE `following` (
						`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
						`user_id` BIGINT(20) UNSIGNED NOT NULL,
						`following_id` BIGINT(20) UNSIGNED NOT NULL,
						PRIMARY KEY (`id`)
					)
					ENGINE=InnoDB
					;
					CREATE TABLE `mensions` (
						`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
						`user_id` BIGINT(20) UNSIGNED NOT NULL,
						`post_hash` TEXT NOT NULL,
						PRIMARY KEY (`id`)
					)
					ENGINE=InnoDB
					;
					CREATE TABLE `likes` (
						`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
						`user_id` INT(10) UNSIGNED NOT NULL,
						`post_hash` INT(10) UNSIGNED NOT NULL,
						PRIMARY KEY (`id`)
					)
					ENGINE=InnoDB
					;
					CREATE TABLE `friends` (
						`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
						`user_id` INT(10) UNSIGNED NOT NULL,
						`friend_id` INT(10) UNSIGNED NOT NULL,
						`accepted` INT(10) UNSIGNED NOT NULL,
						PRIMARY KEY (`id`)
					)
					ENGINE=InnoDB
					;

					INSERT INTO `settings` (`id`, `name`, `value`) VALUES (1, 'install', '1');
					INSERT INTO `settings` (`id`, `name`, `value`) VALUES (2, 'title', 'Socal-Media');
					INSERT INTO `settings` (`id`, `name`, `value`) VALUES (3, 'bootstrap-theme', '3');
					INSERT INTO `settings` (`id`, `name`, `value`) VALUES (4, 'motd', '');
					INSERT INTO `settings` (`id`, `name`, `value`) VALUES (5, 'debug', 'On');
					INSERT INTO `settings` (`id`, `name`, `value`) VALUES (6, 'inverted-nav', '1');
					INSERT INTO `groups` (`id`, `group_name`, `permissions`) VALUES (1, 'Standard', '');
					INSERT INTO `groups` (`id`, `group_name`, `permissions`) VALUES (2, 'Admin', '{\"Admin\":\"1\"}');
					";

					if(!$db->query($dbInsert)->error()){
						echo "<div class=\"alert alert-success\">Databaes Installed!</div>";
					}else{
						echo "<div class=\"alert alert-danger\">Error Installing databases!</div><br/><div class=\"well\">{$dbInsert}</div>";
					}
			?>
			<?php 
				}
			?>
		</div>
	</body>
</html>