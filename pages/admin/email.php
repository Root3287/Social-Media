<?php 
$user = new User();
$db = DB::getInstance();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/');
	}
}else{
	Redirect::to('/admin');
}
$not_write = false;
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			"host"=>[
				"required"=> true,
			],
			"port"=>[
				"required"=> true,
			],
			"name"=>[
				"required"=> true,
			],
			"user"=>[
				"required"=> true,
			],
			"pass"=>[
				"required"=> true,
			],
		]);
		if($validate->passed()){
			if(is_writable('inc/email.php')){
				$config = 	'<?php'.PHP_EOL.
							'$GLOBALS[\'config\'][\'email\'] = ['			.PHP_EOL.
							'	\'host\' => \''.Input::get('host').'\','	.PHP_EOL.
							'	\'port\' => \''.Input::get('port').'\','	.PHP_EOL.
							'	\'name\' => \''.Input::get('name').'\','	.PHP_EOL.
							'	\'user\' => \''.Input::get('user').'\','	.PHP_EOL.
							'	\'pass\' => \''.Input::get('pass').'\','	.PHP_EOL.
							'	\'secure\' => \'tls\','						.PHP_EOL.
							'	\'smtp_auth\' => true,'						.PHP_EOL.
							'];';
				$file = fopen('inc/email.php', 'w');
				fwrite($file, $config);
				fclose($file);
			}else{
				$not_write = true;
			}
			$email = (Input::get('en-email') == "on")? "1":"0"; 
			$email_confirm = (Input::get('en-email-confirm') == "on")? "1":"0"; 
			$email_rec_pass = (Input::get('en-email-recover-pass') == "on")? "1":"0"; 
			Setting::update('enable-email', $email);
			Setting::update('enable-email-confirm', $email_confirm);
			Setting::update('enable-email-recover-password', $email_rec_pass);
			Session::flash('complete', '<div class="alert alert-success">Saved! Some of the settings will take a short while to update!</div>');
			Redirect::to('');
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
		<?php
		if(Session::exists('complete')){echo Session::flash('complete');}
		?>
			<h1>AdminCP</h1>
			<ol class="breadcrumb">
			  <li><a href="/admin">AdminCP</a></li>
			  <li><a class="active" href="/admin/email/">Email Configuration</a></li>
			</ol>
			<?php if($not_write){?>
				<div class="alert alert-danger">Your <strong>inc/email.php</strong> is not writeable!</div>
			<?php }?>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1>Email Configuration</h1>
				<form action="" method="post" autocomplete="off">
					<div class="form-group"><label for="host">Host:</label><input type="text" name="host" id="host" class="form-control" value="<?php echo Config::get('email/host');?>"></div>
					<div class="form-group"><label for="port">Port:</label><input type="text" name="port" id="port" class="form-control" value="<?php echo Config::get('email/port');?>"></div>
					<div class="form-group"><label for="name">Name:</label><input type="text" name="name" id="name" class="form-control" value="<?php echo Config::get('email/name');?>"></div>
					<div class="form-group"><label for="user">Username:</label><input type="text" name="user" id="user" class="form-control" value="<?php echo Config::get('email/user');?>"></div>
					<div class="form-group"><label for="pass">Password</label><input type="password" name="pass" id="pass" class="form-control" value="<?php echo Config::get('email/pass');?>"></div>
					<div class="form-group">
						<label for="en-email">
							Enable Email:
							<input type="checkbox" name="en-email" id="en-email" <?php echo (Setting::get('enable-email') == 1)? 'checked="checked"':'';?>>
						</label>
						<br>
						<label for="en-email-confirm">
							Enable Register Email:
							<input type="checkbox" name="en-email-confirm" id="en-email-confirm" <?php echo (Setting::get('enable-email-confirm') == 1)? 'checked="checked"':'';?>>
						</label>
						<br>
						<label for="en-email-recover-pass">
							Enable Email Password Recovery:
							<input type="checkbox" name="en-email-recover-pass" id="en-email-recover-pass" <?php echo (Setting::get('enable-email-recover-password') == 1)? 'checked="checked"':'';?>>
						</label>
					</div>
					<div class="form-group"><input type="submit" value="Submit" class="btn btn-primary"><input type="hidden" name="token" value="<?php echo Token::generate();?>"></div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>