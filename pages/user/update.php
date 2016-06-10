<?php
$user = new User();
$db = DB::getInstance();
$password_failed = false;
if(Input::exists()){
	$val = new Validation();
	$validation = $val->check($_POST, array(
			'name'=>array('required'=>true),
			//'username'=>array('required'=>true,'min'=>2, 'max'=>50),
			'email'=>array('required'=>true,),
			'password' => [
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
			if(Hash::make(Input::get('password'), $user->data()->salt) == $user->data()->password){
				$password = (Input::get('new_password') == null)? $user->data()->password : Hash::make(Input::get('new_password'), $user->data()->salt);
				try{
					$user->update(array(
						'name'=>escape(Input::get('name')),
						'email'=>escape(Input::get('email')),
						//'username'=>escape(Input::get('username')),
						'password'=> $password,
				), $user->data()->id);
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
						<input type="text" name="name" value="<?php echo $user->data()->name?>" class="form-control input-md" placeholder="Name">
					</div>
					<fieldset disabled>
					<div class="form-group">
						<input type="text" name="username" value="<?php echo $user->data()->username?>" class="form-control input-md" placeholder="Username">
					</div>
					</fieldset>
					<div class="form-group">
						<input type="email" name="email" value="<?php echo $user->data()->email?>" class="form-control input-md" placeholder="Email">
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-md-6">
								<input type="password" name="new_password" value="" class="form-control input-md" placeholder="new password">
							</div>
							<div class="col-md-6">
								<input type="password" name="confirm_new_password" value="" class="form-control input-md" placeholder="confirm password">
							</div>
						</div>
					</div>
					<div class="form-group">
						<input type="password" name="password" value="" class="form-control input-md" placeholder="password">
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
	</body>
</html>