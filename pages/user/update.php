<?php
$user = new User();
$db = DB::getInstance();
$password_failed = false;
$cache_settings = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);
if(Input::exists()){
	$val = new Validation();
	$validation = $val->check($_POST, array(
			'name'=>array('required'=>true),
			//'username'=>array('required'=>true,'min'=>2, 'max'=>50),
			'email'=>array('required'=>true,),
			'oldpassword' => [
				'required' => true,
			],
			'new_password' => [
				'matches' => 'confirm_new_password',
			],
			'confirm_new_password' => [
				'matches' => 'new_password',
			],
		));
	if(Token::check(Input::get('token'))){
		if($validation->passed()){
			if(Hash::make(Input::get('oldpassword'), $user->data()->salt) == $user->data()->password){
				$password = (Input::get('new_password') == null)? $user->data()->password : Hash::make(Input::get('new_password'), $user->data()->salt);
				$update = [
					'name'=>escape(Input::get('name')),
					'email'=>escape(Input::get('email')),
					'number' => escape(Input::get('number')),
					'password'=> $password,
					'location' => escape(Input::get('location')),
					'dob'=> escape(Input::get('dob')),
					'dod'=> escape(Input::get('dod')),
					'gender' => escape(Input::get('gender')),
					'bio'=> escape(Input::get('bio')),
					'about_me' => escape(Input::get('aboutME')),
				];

				if(Input::get('rmBanner') == "on"){
					$update['banner'] = "";
				}
				if(Input::get('banner')){
					$update['banner'] = Input::get('banner');
				}

				try{
					$user->update($update, $user->data()->id);
				session::flash('complete', '<div class="alert alert-success">You have updated your details</div>');
				Redirect::to('');
				}Catch(Exception $e){
					
				}
			}else{
				$password_failed = true;
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
		<link href="/assets/js/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<?php 
			if(Session::exists('complete')){
				echo Session::flash('complete');
			}
			if(Input::exists() && !$validation->passed()):
			?>
			<div class="alert alert-danger"><?php foreach($validation->errors() as $error){echo $error."<br/>";}?></div>
			<?php
			endif;
			if($password_failed):
			?>
			<div class="alert alert-danger">Your password does not mached the database password!</div>
			<?php
			endif;
			?>
			<div class="col-md-3"><?php require 'pages/user/sidebar.php';?></div>
			<div class="col-md-9">
				<form action="" method="post">
					<div class="form-group">
						<label for="name">Name: </label>
						<input type="text" name="name" value="<?php echo $user->data()->name?>" class="form-control input-md" placeholder="Name">
					</div>
					<fieldset disabled>
					<div class="form-group">
						<label for="username">Username: </label>
						<input type="text" name="username" value="<?php echo $user->data()->username?>" class="form-control input-md" placeholder="Username">
					</div>
					</fieldset>
					<div class="form-group">
						<label for="email">Email: </label>
						<input type="email" name="email" value="<?php echo $user->data()->email?>" class="form-control input-md" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="number">Phone Number:</label>
						<input type="tel" name="number" value="<?php echo $user->data()->number?>" class="form-control input-md" placeholder="Phone Number">
						<span class="help-block">This number would be used for multi-step verification! Please use in the format of xxxxxxxxxx not (xxx)xxx-xxxx.</span>
					</div>
					<div class="form-group">
						<label for="location">Location: </label>
						<input class="form-control" type="text" name="location" value="<?php echo $user->data()->location?>">
					</div>
					<div class="form-group">
						<label for="dob">Date of birth:</label>
						<input class="form-control datepicker" type="text" name="dob" value="<?php echo $user->data()->dob?>">
					</div>
					<div class="form-group">
						<label for="dob">Date of death:</label>
						<input class="form-control datepicker" type="text" name="dod" value="<?php echo $user->data()->dod?>">
					</div>
					<div class="radio">
						<strong>Gender</strong>:<br>
						<label for="male"><input type="radio" name="gender" id="male" value="m" <?php if($user->data()->gender == "m"){echo 'checked="checked"';}?>>Male</label><br>
						<label for="female"><input type="radio" name="gender" id="female" value="f <?php if($user->data()->gender == "f"){echo 'checked="checked"';}?>">Female</label><br>
						<label for="gother"><input type="radio" name="gender" id="gother" value="o" <?php if($user->data()->gender == "o"){echo 'checked="checked"';}?>>Other</label><br>
						<label for="gna"><input type="radio" name="gender" id="gna" value="na" <?php if($user->data()->gender == "na"){echo 'checked="checked"';}?>>N/A</label><br>
					</div>
					<div class="form-group">
						<label for="">Bio:</label>
						<textarea style="resize: vertical;" name="bio" class="form-control" cols="30" rows="10"><?php echo $user->data()->bio;?></textarea>
					</div>
					<div class="form-group">
						<label for="">About me:</label>
						<textarea style="resize: vertical;" name="aboutME" class="form-control" cols="30" rows="10"><?php echo $user->data()->about_me?></textarea>
					</div>
					<div class="form-group">
						<a href="<?php echo $user->data()->banner?>" class="btn btn-default" target="_blank">Banner</a>
						<?php
						$cache_settings = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);
						if($cache_settings->isCached('enable-uploadcare')){
							if($cache_settings->retrieve('enable-uploadcare') == 1):?>
							<br>
							<input 
								type="hidden" 
								name="banner" 
								role="uploadcare-uploader"
								data-tabs="<?php echo $cache_settings->retrieve('uploadcare-tabs');?>" 
								data-clearable="<?php echo $cache_settings->retrieve('uploadcare-clearable');?>" 
								data-images-only="<?php echo $cache_settings->retrieve('uploadcare-image-only');?>"
								data-crop="<?php echo $cache_settings->retrieve('uploadcare-crop');?>"
							>
					<?php endif;
					 }else{if(Setting::get('enable-uploadcare')):?>
							<br>
							<input 
								type="hidden" 
								name="banner" 
								role="uploadcare-uploader"
								data-tabs="<?php echo Setting::get('uploadcare-tabs');?>" 
								data-clearable="<?php echo Setting::get('uploadcare-clearable');?>" 
								data-images-only="<?php echo Setting::get('uploadcare-image-only');?>"
								data-crop="<?php echo Setting::get('uploadcare-crop');?>"
							>
					<?php endif;}?>
					</div>
					<div class="checkbox">
						<label for="rmBanner"><input type="checkbox" name="rmBanner" id="rmBanner">Remove Banner</label>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<label for="password">New Password:</label>
								<input type="password" name="new_password" value="" class="form-control input-md" placeholder="new password">
							</div>
							<div class="col-md-6">
								<label for="cnewPass">Confirm New Password:</label>
								<input type="password" name="confirm_new_password" value="" class="form-control input-md" placeholder="confirm password">
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="oldPass">Old Password:</label>
						<input type="password" name="oldpassword" value="" class="form-control input-md" placeholder="password">
					</div>
					<div class="form-group">
						<input type="reset" class="btn btn-md btn-default">
						<input type="hidden" name="token" value="<?php echo Token::generate()?>">
						<input type="submit" value="Submit" class="btn btn-md btn-primary">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<script src="/assets/js/bootstrap-datepicker.min.js"></script>
		<script>
			$(document).ready(function(){
				$('.datepicker').datepicker({
					orientation: 'bottom'
				});
			});
		</script>
		<?php if($cache_settings->isCached('enable-uploadcare')){
			if($cache_settings->retrieve('enable-uploadcare') == 1):?>
			<script charset="utf-8" src="https://ucarecdn.com/widget/2.10.0/uploadcare/uploadcare.full.min.js"></script>
			<script>
				UPLOADCARE_LOCALE = "en";
				UPLOADCARE_LIVE = true;
				UPLOADCARE_PUBLIC_KEY = "<?php echo Setting::get('uploadcare-public-key');?>";
			</script>
		<?php endif;}else{if(Setting::get('enable-uploadcare') == 1):?>
			<script charset="utf-8" src="https://ucarecdn.com/widget/2.10.0/uploadcare/uploadcare.full.min.js"></script>
			<script>
				UPLOADCARE_LOCALE = "en";
				UPLOADCARE_LIVE = true;
				UPLOADCARE_PUBLIC_KEY = "<?php echo Setting::get('uploadcare-public-key');?>";
			</script>
		<?php endif;}?>
	</body>
</html>