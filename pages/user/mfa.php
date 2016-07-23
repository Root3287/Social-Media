<?php
$user= new User();
if(!$user->isLoggedIn()){
	Redirect::to('/');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, []);
		if($validate->passed()){
			$mfa = json_decode($user->data()->mfa, true);
			$mfa["enable"] = (Input::get('enable-mfa') == "on")? 1:0;
			$mfa = json_encode($mfa);
			try{
				$user->update(['mfa'=>$mfa]);
				Session::flash('success', '<div class="alert alert-success">Settings are saved!</div>');
				Redirect::to('');
			}catch(Exception $e){

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
		<?php if(Session::exists('success')): echo Session::flash('success'); endif; ?>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1>Multi-Factor Authication</h1>
				<form action="" method="post">
					<div class="form-group">
						<label for="mfa">
							Enable Multi Factor Authication:
							<input type="checkbox" name="enable-mfa" id="mfa" <?php $cmfa = json_decode($user->data()->mfa, true); echo ($cmfa['enable'] == 1)? "checked=\"checked\"":"";?>>
						</label>
					</div>
					<div class="form-group">
						<input type="submit" value="submit" class="btn btn-primary">
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>