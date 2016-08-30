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
			<h1><?php echo $GLOBALS['language']->get('admincp')?></h1>
			<ol class="breadcrumb">
			  <li><a href="/admin"><?php echo $GLOBALS['language']->get('admincp')?></a></li>
			  <li><a class="active" href="/admin/mfa/"><?php echo $GLOBALS['language']->get('multi-factor_authication')?></a></li>
			</ol>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1><?php echo $GLOBALS['language']->get('email').' '.$GLOBALS['language']->get('settings');?></h1>
				<form action="" method="post" autocomplete="off">
					<label for="enable-mfa">
						<?php echo $GLOBALS['language']->get('enable').' '.$GLOBALS['language']->get('multi-factor_authication');?>:
						<input type="checkbox" name="enable-mfa" id="enable-mfa" <?php echo (Setting::get('enable-mfa') == 1)? 'checked="checked"':'';?>>
					</label>
					<br>
					<label for="enable-mfa-email">
						<?php echo $GLOBALS['language']->get('enable').' '.$GLOBALS['language']->get('multi-factor').' '.$GLOBALS['language']->get('email');?>:
						<input type="checkbox" name="enable-mfa-email" id="enable-mfa-email" <?php echo (Setting::get('enable-mfa-email') == 1)? 'checked="checked"':'';?>>
					</label>
					</div>
					<div class="form-group"><input type="submit" value="<?php echo $GLOBALS['language']->get('acp-save');?>" class="btn btn-primary"><input type="hidden" name="token" value="<?php echo Token::generate();?>"></div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>