<?php
$user = new User();
$db = DB::getInstance();
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validation = $val->check($_POST, array(
			'name'=>array('required'=>true),
			//'username'=>array('required'=>true,'min'=>2, 'max'=>50),
			'email'=>array('required'=>true,),	
		));
		if($validation->passed()){
			if(Input::get('username') != $user->data()->username){
				if($db->get('users', array('username','=',escape(Input::get('username'))))->count() > 1){ // Check if the user name does not belong to the user table
					try{
					$user->update(array(
							'name'=>escape(Input::get('name')),
							'email'=>escape(Input::get('email')),
							//'username'=>escape(Input::get('username')),
					), $user->data()->id);
					session::flash('complete', '<div class="alert alert-success">You have updated your details</div>');
					Redirect::to('');
					}catch (Exception $e){
						
					}
				}
			}else{
				try{
				$user->update(array(
						'name'=>escape(Input::get('name')),
						'email'=>escape(Input::get('email')),
						//'username'=>escape(Input::get('username')),
				), $user->data()->id);
				session::flash('complete', '<div class="alert alert-success">You have updated your details</div>');
				Redirect::to('');
				}Catch(Exception $e){
					
				}
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
				<form action="" method="post">
					<div class="form-group">
						<input type="text" name="name" value="<?php echo $user->data()->name?>" class="form-control input-lg" placeholder="Name">
					</div>
					<fieldset disabled>
					<div class="form-group">
						<input type="text" name="username" value="<?php echo $user->data()->username?>" class="form-control input-lg" placeholder="Username">
					</div>
					</fieldset>
					<div class="form-group">
						<input type="email" name="email" value="<?php echo $user->data()->email?>" class="form-control input-lg" placeholder="Email">
					</div>
					<div class="form-group">
						<input type="reset" class="btn btn-lg btn-default">
						<input type="hidden" name="token" value="<?php echo Token::generate()?>">
						<input type="submit" value="Submit" class="btn btn-lg btn-primary">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>