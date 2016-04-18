<?php
$db = DB::getInstance();
$user = new User();
if(Input::exists('post')){
	if(Token::check(Input::get('token'))){
		$private=(Input::get('private') == 'on')? '1':'0';
		$val = new Validation();
		$validation = $val->check($_POST, array());
		if($validation->passed()){
			if($db->update('users', $user->data()->id, array('private'=>$private,))){
				Session::flash('complete', '<div class="alert alert-success">You have updated your profile</div>');
				Redirect::to('/user/profile/');
			}else{
				session::flash('error', '<div class="alert alert-danger">Something went wrong updating the profile</div>');
				Redirect::to('/user/profile/');
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
			?>
			<div class="col-md-3"><?php require 'pages/user/sidebar.php';?></div>
			<div class="col-md-9">
				<form action="/user/profile/" method="post">
					<div class="checkbox">
				    		<input name="private" id="private" type="checkbox" <?php if($user->data()->private == 1){echo 'checked';}?>>
				    		<label for="private">
				    		Profile Private
				    		</label><br>
				    		
					</div>
					<!--<div class="form-group"><textarea class="form-control" id="sign" name="sign"><?php echo $user->data()->signature;?></textarea></div>--><br/>
					<div class="form-group">
				    		<input type="hidden" name="token" value="<?php echo Token::generate()?>">
				    		<input class="form-control btn btn-primary" type="submit" value="Submit">
				    	</div>
				</form>
			</div>
		</div>			
		<?php require 'assets/foot.php';?>
	</body>
</html>