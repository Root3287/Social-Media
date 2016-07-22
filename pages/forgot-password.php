<?php
$user = new User();
if($user->isLoggedIn()){
	Redirect::to('/');
}
if(Setting::get('enable-email') != 1 && Setting::get('enable-email-recover-password') != 1){
	Redirect::to('/');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$db = DB::getInstance();
		$val = new Validation();
		$validate = $val->check($_POST, [
			'email'=>[
				'required'=> true,
			],
		]);
		if($validate->passed()){
			$email = $db->get('users', ['email', '=', escape(Input::get('email'))]);
			if($email->count()){
				$hash = Hash::unique_length(128);
				$db->update('users', $email->first()->id, ['recover_hash'=>$hash]);
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
				$html = file_get_contents('assets/email/forgot-password.html');
				$link =  getSelfUrl()."password-reset/?email=".escape(Input::get('email'))."&hash={$hash}";
				$content = "This email is to reset your password for Social-Media. If you do not remember preforming this action you can delete this email.";
				$html = str_replace(['[Name]','[Content]','[Link]'], [$email->first()->username ,$content, $link], $html);
				
				$mail->msgHTML($html);
				$mail->isHTML(true);
				$mail->Body = $html;
				if(!$mail->send()){
					Session::flash('error', '<div class="alert alert-danger">Error sending email!</div>');
				}else{
					Session::flash('complete', '<div class="alert alert-success">A email has been sent to your account!</div>');
				}
				Redirect::to('/');
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
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<h1>Forgot Password</h1>
				<form action="" method="post" autocomplete="off">
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" name="email" placeholder="email" class="form-control">
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