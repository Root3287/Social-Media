<?php
if(Input::exists('post')){
	if(Token::check(Input::get('token'))){
		$private=(Input::get('private') == 'on')? '1':'0';
		$val = new Validation();
		$validation = $val->check($_POST, array());
		if($validation->passed()){
			if($db->update('users', $user->data()->id, array('private'=>$private,))){
				Session::flash('complete', 'You have updated your profile');
				Redirect::to('?page=profile');
			}else{
				session::flash('error', 'Something went wrong updating the profile');
				Redirect::to('?page=profile');
			}
		}
	}
}
?>
<form action="?page=profile" method="post">
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