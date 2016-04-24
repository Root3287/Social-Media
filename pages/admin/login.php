<?php
$user = new User();
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$val->check($_POST, array(
			'username' => array(
				'required' => true
			),
			'password' => array(
				'required' => true
			)
		));
		if($val->passed()){
			$remember = (Input::get('remember') == 'on')? true:false;
			$user2 = new User();
			$login = $user2->admlogin(escape(Input::get('username')), Input::get('password'), $remember);
			if($login){
				Session::flash('complete', '<div class="alert alert-success">You have been logged in!</div>');
				Redirect::to('/admin/');
			}
		}else{
			
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
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<h1>AdminCP: Login</h1>
				<form action="" method="post" autocomplete="off">
					<div class="form-group">
						<input type="text" name="username" placeholder="Username" value="<?php echo Input::get('username')?>" class="form-control input-lg">
					</div>
					<div class="form-group">
						<input type="password" name="password" placeholder="Password" class="form-control input-lg">
					</div>
					<div class="form-group">
						<label for="remember">Remember me?
							<input type="checkbox" name="remember" id="remember"/>
						</label>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<a class="btn btn-lg btn-danger btn-block" href="/register">Register</a>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<input type="hidden" name="token" value="<?php echo Token::generate()?>"/>
								<input class="btn btn-lg btn-primary btn-block" type="submit" value="Submit" id="Submit" name="submit"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>