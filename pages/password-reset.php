<?php
$user= new User();
$val = new Validation();
$db = DB::getInstance();
if($user->isLoggedIn()){
	Redirect::to('/');
}
if(Input::exists('get')){
	$get_validate = $val->check($_GET, [
		'email'=> [
			"required" => true,
		],
		"hash"=>[
			"required" => true,
		],
	]);
	if($get_validate->passed()){
		$email = $db->get('users', ['email', '=', escape(Input::get('email'))]);

		if(!$email->count()){
			Redirect::to(404);
		}
		if(!$email->first()->recover_hash){
			Redirect::to(404);
		}
		if(!$email->first()->recover_hash == escape(Input::get('hash'))){
			Redirect::to(404);
		}

	}else{
		Redirect::to(404);
	}
}else{
	Redirect::to(404);
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$post_validate = $val->check($_POST, [
			"newPass"=> [
				"required" => true,
				"min" => 8,
			],
			"confPass"=>[
				"required" => true,
				"matches" => "newPass",
			],
			"email"=>[
				"required" => true,
			],
			"hash" => [
				"required" => true,
			],
		]);
		if($post_validate->passed()){
			$email = $db->get('users', ['email', '=', escape(Input::get('email'))]);
			$newPass = Hash::make(Input::get('newPass'), $email->first()->salt);

			if($email->count()){
				if($email->first()->recover_hash){
					if($email->first()->recover_hash == escape(Input::get('hash'))){
						if($db->update('users', $email->first()->id, ['password'=>$newPass, 'recover_hash'=>NULL,])){
							if(Setting::get('enable-email') == 1){
								$email = $db->get('users', ['email', '=', escape(Input::get('email'))]);
								require_once 'inc/includes/phpmailer/PHPMailerAutoload.php';
								$mail = new PHPMailer;
								
								$mail->IsSMTP(); 
								
								$mail->SMTPDebug = 0;
								$mail->Debugoutput = 'html';
								
								$mail->Host = $GLOBALS['config']['email']['host'];
								$mail->Port = $GLOBALS['config']['email']['port'];
								$mail->SMTPSecure = $GLOBALS['config']['email']['secure'];
								$mail->SMTPAuth = $GLOBALS['config']['email']['smtp_auth'];
								$mail->Username = $GLOBALS['config']['email']['user'];
								$mail->Password = $GLOBALS['config']['email']['pass'];
								
								$mail->setFrom($GLOBALS['config']['email']['user'], $GLOBALS['config']['email']['name']);
								$mail->From = $GLOBALS['config']['email']['user'];
								$mail->FromName = $GLOBALS['config']['email']['name'];
								$mail->addAddress(escape(Input::get('email')), escape($email->first()->name));
								
								$mail->Subject = 'Social-Media Password Reset';
								
								$html = file_get_contents('assets/email/confirm-pass-change.html');
								$content = $GLOBALS['language']->get('email-password-reset');
								$html = str_replace(['[Name]','[Content]'], [$email->first()->name ,$content], $html);
								
								$mail->msgHTML($html);
								$mail->isHTML(true);
								$mail->Body = $html;

								if(!$mail->send()){
									Session::flash('error', '<div class="alert alert-danger">'.$GLOBALS['language']->get('alert-password-reset-email-error').'</div>');
								}else{
									Session::flash('complete', '<div class="alert alert-success">'.$GLOBALS['language']->get('alert-password-reset-email-complete').'</div>');
									Redirect::to('/');
								}
							}else{
								Session::flash('complete', '<div class="alert alert-success">'.$GLOBALS['language']->get('alert-password-reset-complete').'</div>');
								Redirect::to('/');
							}
						}			
					}
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
		<?php if(Input::exists()):?>
			<?php if(!$post_validate->passed()):?>
				<div class="alert alert-danger">
				<?php foreach($post_validate->errors() as $pv):?>
					<?php echo $pv;?><br> 
				<?php endforeach;?>
				</div>
			<?php endif;?>
		<?php endif;?>
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<form action="" method="post">
					<div class="form-group">
						<label for="newPass"><?php echo $GLOBALS['language']->get('new-password');?></label>
						<input type="password" name="newPass" id="" class="form-control">
					</div>
					<div class="form-group">
						<label for="confPass"><?php echo $GLOBALS['language']->get('confirm-password');?></label>
						<input type="password" name="confPass" id="" class="form-control">
					</div>
					<div class="form-group">
						<input type="submit" value="Submit" class="btn btn-primary">
						<input type="hidden" name="email" value="<?php echo Input::get('email');?>">
						<input type="hidden" name="hash" value="<?php echo Input::get('hash');?>">
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>