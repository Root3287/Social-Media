<?php
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('/404');
}
if(!$user->data()->group == 2){
	Redirect::to('/404');
}
if(!$user->isAdmLoggedIn()){
	if(Input::exists()){
		if(Token::check(Input::get('token'))){
			$val = new Validation();
			$validate = $val->check($_POST, array(
				"username"=>array(
					"required"=>true,
				),
				"password"=>array(
					"required"=>true,
				),
			));
			if(!$validate->passed()){
				$message .= "<div class=\"alert alert-danger\">";
				foreach ($validate->errors() as $error) {
					$message .= $error."<br>";
				}
				$message .= "</div>";
				Session::flash('error', $message);
				Redirect::to('/admin');
			}else{
				$remember = (Input::get('remember') === 'On')? true:false;
				if($user->admlogin(escape(Input::get('username')), escape(Input::get('password')), $remember)){
					Redirect::to('');
				}else{
					Session::flash('error', '<div class="alert alert-danger">Your username/password was invalid!</div>');
				}
			}
		}
	}
?>
<html>
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="conatiner">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<?php if(Session::exists('error')){echo Session::flash('error');}?>
				<h1>AdminCP</h1>
				<form method="post" action="" autocomplete="off">
					<div class="form-group">
						<input class="form-control input-lg" name="username" type="text" placeholder="Username">
					</div>
					<div class="form-group">
						<input class="form-control input-lg" name="password" type="password" placeholder="Password">
					</div>
					<div class="form-group">
						<input type="hidden" value="<?php echo Token::generate();?>" name="token">
						<input type="Submit" value="Login" class="form-control input-lg btn btn-block btn-lg btn-primary ">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>
<?php
}
if($user->isAdmLoggedIn()){
?>
<html>
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="jumbotron">
				<h1>AdminCP</h1>
			</div>
			
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>
<?php
}
?>