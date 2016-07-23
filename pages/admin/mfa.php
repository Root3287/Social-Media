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
			
		]);
		if($validate->passed()){
			$mfa = (Input::get('enable-mfa') == "on")? "1":"0"; 
			$mfa_email = (Input::get('enable-mfa-email') == "on")? "1":"0"; 
			Setting::update('enable-mfa', $mfa);
			Setting::update('enable-mfa-email', $mfa_email);
			Session::flash('complete', '<div class="alert alert-success">Saved!</div>');
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
			  <li><a class="active" href="/admin/mfa/">Multi-Factor Authication</a></li>
			</ol>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1>Email Configuration</h1>
				<form action="" method="post" autocomplete="off">
					<label for="enable-mfa">
						Enable Multi Factor Authentication:
						<input type="checkbox" name="enable-mfa" id="enable-mfa" <?php echo (Setting::get('enable-mfa') == 1)? 'checked="checked"':'';?>>
					</label>
					<br>
					<label for="enable-mfa-email">
						Enable Multi-Factor Email:
						<input type="checkbox" name="enable-mfa-email" id="enable-mfa-email" <?php echo (Setting::get('enable-mfa-email') == 1)? 'checked="checked"':'';?>>
					</label>
					</div>
					<div class="form-group"><input type="submit" value="Submit" class="btn btn-primary"><input type="hidden" name="token" value="<?php echo Token::generate();?>"></div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>