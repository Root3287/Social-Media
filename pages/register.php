<?php
$user = new User();
if($user->isLoggedIn()){
	Redirect::to('/');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$val->check($_POST, array(
				'name' => array(
						'required' => true,
				),
				'username' => array(
					'required' => true,
					'min' => 2,
					'max' => 50,
					'unique' => 'users',
					'spaces'=>false,
				),
				'email'=> array(
					'required'=> true,
					'unique' => 'users',
				),
				'password' => array(
					'required' => true,
					'min' => 8
				),
				'password_conf' => array(
						'required' => true,
						'matches'=> 'password',
				),
		));
		if(!$val->passed()){
			
		}else{
			$g_reponse = null;
			if(Setting::get('enable-recaptcha') == 1){
				$curl = curl_init();
				curl_setopt_array($curl, [
					CURLOPT_RETURNTRANSFER => 1, 
					CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => [
						'secret'=> Setting::get('recaptcha-secret-key'),
						'response'=> Input::get('g-recaptcha-response'),
						'remoteip' => getClientIP(),
					],
				]);

				$g_reponse = json_decode(curl_exec($curl));
			}
			if($g_reponse == null || $g_reponse->success){
				$user = new User();
				
				$salt = Hash::salt(32);
				
				$password = Hash::make(escape(Input::get('password')) , $salt);

				try{
					$email_confirmed = 1;
					$email_hash = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 50);
					if(Setting::get('enable-email') == "1" && Setting::get('enable-email-confirm') == "1"){
						$email_confirmed = 0;
					}
					$user->create(array(
							'username' => escape(Input::get('username')),
							'password'=> $password,
							'salt' => $salt,
							'name'=> escape(Input::get('name')),
							'joined'=> date('Y-m-d H:i:s'),
							'group'=> 1,
							'email'=> escape(Input::get('email')),
							'score'=> 1,
							'confirmed' => $email_confirmed,
							'confirm_hash'=> $email_hash,
					));

					if($email_confirmed == 0){
						require('inc/includes/phpmailer/PHPMailerAutoload.php');
									
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
						$mail->setFrom($GLOBALS['config']['email']['user'], $GLOBALS['email']['name']);
						$mail->From = $GLOBALS['config']['email']['user'];
						$mail->FromName = $GLOBALS['config']['email']['name'];
						$mail->addAddress(htmlspecialchars(Input::get('email')), htmlspecialchars(Input::get('username')));
						$mail->Subject = 'Social-Media Register';
						$html = file_get_contents('assets/email/confirm.html');
						$link =  getSelfUrl()."email-confirm/{$email_hash}/";
						$content = "This is an email confirming your email address. If you don't remember registering with us you can ignore this email.";
						$html = str_replace(['[Content]','[Link]'], [$content, $link], $html);
						$mail->msgHTML($html);
						$mail->isHTML(true);
						$mail->Body = $html;
						if(!$mail->send()){
							Session::flash('error', '<div class="alert alert-danger">Error sending email!</div>');
						}else{
							Session::flash('complete', '<div class="alert alert-success">A email has been sent to your account!</div>');
						}
						Redirect::to('/');
					}else{
						if($user->login(escape(Input::get('username')), escape(Input::get('password')), false)){
							Notification::createMessage('Welcome to the Social-Media '. $user->data()->name, $user->data()->id);
							Session::flash('complete', '<div class="alert alert-success">You completely register and you just got logged in.</div>');
							Redirect::to('/');
						}
					}
				}catch (Exception $e){
					die($e->getMessage());
				}
			}
		}
	}
}
?>
<html>
	<head>
	<?php Include 'assets/head.php';?>
	<?php if(Setting::get('enable-recaptcha') == 1){?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	<?php }?>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<?php if(Input::exists()): if(Token::check(Input::get('token'))): if(!$val->passed()):?>
				<div class="alert alert-danger"><?php foreach ($val->errors() as $error){echo $error.'<br/>';}?></div>
			<?php endif;endif;endif;?>
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<h1>Register</h1>
				<form action="" method="post" autocomplete="off">
					<div class="form-group">
						<input name="name" value="<?php echo Input::get('name');?>" placeholder="Name" type="text" class="form-control input-lg">
					</div>
					<div class="form-group">
						<input name="username" value="<?php echo Input::get('username');?>" placeholder="username" type="text" class="form-control input-lg">
					</div>
					<div class="form-group">
						<input name="email" value="<?php echo Input::get('email');?>" placeholder="email" type="email" class="form-control input-lg">
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<input name="password" value="<?php echo Input::get('password');?>" placeholder="Password" type="password" class="form-control input-lg">
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<input name="password_conf" placeholder="Confirm Password" type="password" class="form-control input-lg">
							</div>
						</div>
					</div>
					<?php if(Setting::get('enable-recaptcha')){?>
					<div class="form-group">
						<div class="g-recaptcha" data-sitekey="<?php echo Setting::get('recaptcha-site-key');?>"></div>
					</div>
					<?php }?>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<a href="/login" class="btn btn-lg btn-block btn-danger">Login</a>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<input type="submit" class="btn btn-lg btn-block btn-primary" value="Register">
								<input type="hidden" name="token" value="<?php echo Token::generate();?>"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php include 'assets/foot.php';?>

	</body>
</html>
