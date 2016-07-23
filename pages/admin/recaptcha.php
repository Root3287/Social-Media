<?php
$user = new User();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/');
	}
}else{
	Redirect::to('/admin');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$enable_recaptcha = (Input::get('enable-recaptcha') == 'on')? '1':'0';
		if(Setting::update('enable-recaptcha', $enable_recaptcha) && Setting::update('recaptcha-site-key', escape(Input::get('site-key'))) && Setting::update('recaptcha-secret-key', escape(Input::get('secret-key')))){
			Session::flash('complete', "<div class='alert alert-success'>You updated information!</div>");
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
			if(Session::exists('complete')){
				echo Session::flash('complete');
			}
			?>
			<h1>AdminCP</h1>
			<ol class="breadcrumb">
			  <li><a href="/admin">AdminCP</a></li>
			  <li><a class="active" href="/admin/recaptcha/">Recaptcha</a></li>
			</ol>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1>Recatcha Settings</h1>
				<form action="" method="post">
					<div class="checkbox">
				    	<label>
				      		<input type="checkbox" name="enable-recaptcha" <?php if(Setting::get('enable-recaptcha')){echo "checked='checked'";}?>> Enable recaptcha
				    	</label>
				  	</div>
					<div class="form-group">
						<input type="text" name="site-key" placeholder="Site Key" class="form-control" value="<?php echo Setting::get('recaptcha-site-key');?>">
					</div>
					<div class="form-group">
						<input type="password" name="secret-key" placeholder="Secret" class="form-control" value="<?php echo Setting::get('recaptcha-secret-key');?>">
					</div>
					<div class="form-group">
						<input type="hidden" name="token" value="<?php echo Token::generate()?>">
						<input type="submit" value="Update" class="btn btn-primary">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>