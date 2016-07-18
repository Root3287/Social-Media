<?php
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			'name'=>[
				'required' => true,
			],
			'username'=>[
				'required' => true,
				'min' => 2,
				'max' => 50,
				'spaces'=>false,
			],
			'email'=>[
				'required'=> true,
			],
			'password'=>[
				'required' => true,
				'min' => 8
			],
			'password_conf'=>[
				'required' => true,
				'matches'=> 'password',
			],
		]);
		if($validate->passed()){
			$user = new User();

			$salt = Hash::salt(32);

			$password = Hash::make(escape(Input::get('password')) , $salt);
			
			try{
				$user->create(array(
						'username' => escape(Input::get('username')),
						'password'=> $password,
						'salt' => $salt,
						'name'=> escape(Input::get('name')),
						'joined'=> date('Y-m-d H:i:s'),
						'group'=> 2,
						'email'=> escape(Input::get('email')),
				));
			}catch (Exception $e){
				die($e->getMessage());
			}
			if($user->login(escape(Input::get('username')), escape(Input::get('password')), false)){
				Notification::createMessage('Welcome to the Social-Media '. $user->data()->name, $user->data()->id);
				Session::flash('complete', '<div class="alert alert-info">You need to delete install-disable.php! Hacker could use this to their advantage!</div>');
				Redirect::to('?step=6');
			} 
		}
	}
}
?>
<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
<br>
	<?php 
	if(Input::exists()){
	?>
	<div class="alert alert-danger">
	<?php
		if(!$validate->passed()){
			foreach ($validate->errors() as $error) {
				echo $error."<br>";
			}
		}
	?>
	</div>
	<?php
	}
	?>
	<h1>Register</h1>
	<form action="" method="post" autocomplete="off">
		<div class="form-group">
			<input name="name" value="<?php echo Input::get('name');?>" placeholder="Name" type="text" class="form-control input-lg">
		</div>
		<div class="form-group">
			<input name="username" value="<?php echo Input::get('username');?>" placeholder="username" type="text" class="form-control input-lg">
		</div>
		<div class="form-group">
			<input name="email" value="<?php echo Input::get('email');?>" placeholder="email" type="email" class="form-control input-lg">
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input name="password" value="<?php echo Input::get('password');?>" placeholder="Password" type="password" class="form-control input-lg">
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input name="password_conf" placeholder="Confirm Password" type="password" class="form-control input-lg">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<a href="/login" class="btn btn-lg btn-block btn-danger">Login</a>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input type="submit" class="btn btn-lg btn-block btn-primary" value="Register">
					<input type="hidden" name="token" value="<?php echo Token::generate();?>"/>
				</div>
			</div>
		</div>
	</form>
</div>