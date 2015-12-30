<?php
if(Input::exists()){
	if(token::check(Input::get('token'))){
		$val = new Validation();
		$validation = $val->check($_POST, array(
			'message'=>array(
				'required'=>true,
			),
		));
		if($validation->passed()){
			foreach($db->get('users', array('1','=','1'))->results() as $userAcc){
				try{Notifaction::createMessage(Input::get('message'), $userAcc->id); Session::flash('complete', 'You sent a mass message!');Redirect::to('?page=notification');}catch (Exception $e){}
			}
		}
	}
}
?>
<div class="row">
	<h1>Send Mass Message</h1>
</div>
<div class="row">
	<form action="?page=notification" method="post">
	 	<div class="form-group">
	 		<textarea class="form-control" rows="20" cols="20" name="message" id="message"></textarea><br/>
	 	</div>
	 	<input type="hidden" name="token" value="<?php echo Token::generate()?>">
	 	<div class="form-group">
	 		<input class="form-control btn btn-primary" type="submit" value="Submit">
	 	</div>
	</form>
</div>