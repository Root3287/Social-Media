<?php
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validation = $val->check($_POST, array(
			'oldPassword'=>array('required'=>true),
			'newPassword'=>array('required'=>true, 'min'=>8),
			'password_conf'=>array('required'=>true, 'matches'=>'newPassword'),
		));
		if($validation->passed()){
			if(Hash::make(escape(Input::get('oldPassword')), $user->data()->salt) == $user->data()->password);
			$newPass = Hash::make(escape(Input::get('newPassword')), $user->data()->salt);
			try{$user->update(array('password'=>$newPass), $user->data()->id); session::flash('complete', 'You have changed your password!');Redirect::to('');}catch (Exception $e){Session::flash('error', $e->getMessage()); Redirect::to('');}
		}
	}
}
?>
<form action="" method="post">
	<div class="form-group">
		<input type="password" name="oldPassword" placeholder="Old Password" class="form-control input-lg">
	</div>
	<div class="form-group">
		<input type="password" name="newPassword" placeholder="New Password" class="form-control input-lg">
	</div>
	<div class="form-group">
		<input type="password" name="password_conf" placeholder="Password Confirm" class="form-control input-lg">
	</div>
	<div class="form-group">
		<input type="hidden" name="token" value="<?php echo Token::generate()?>">
		<input type="reset" class="btn btn-lg btn-default">
		<input type="submit" class="btn btn-lg btn-primary">
	</div>
</form>