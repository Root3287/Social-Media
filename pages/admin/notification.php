<?php
$user = new User();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/');
	}
}else{
	Redirect::to('/admin');
}
$db = DB::getInstance();
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
				try{Notification::createMessage(Input::get('message'), $userAcc->id); Session::flash('complete', '<div class="alert alert-success"> You sent a mass message!</div>');Redirect::to('/admin/notification');}catch (Exception $e){}
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
		<div class="col-md-3"><?php require 'pages/admin/sidebar.php';?></div>
		<div class="col-md-9"><div class="row">
			<h1>Send Mass Message</h1>
		</div>
		<div class="row">
			<form action="/admin/notification" method="post">
			 	<div class="form-group">
			 		<textarea class="form-control" rows="20" cols="20" name="message" id="message"></textarea><br/>
			 	</div>
			 	<input type="hidden" name="token" value="<?php echo Token::generate()?>">
			 	<div class="form-group">
			 		<input class="form-control btn btn-primary" type="submit" value="Submit">
			 	</div>
			</form>
		</div></div>
		
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>