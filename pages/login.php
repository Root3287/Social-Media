<?php 
$user = new User();
$db = DB::getInstance();
$redirect = false;
$text = "";
$page = "";
if($user->isLoggedIn()){
	Redirect::to('/timeline');
}
if(Input::exists('get')){
	if(Input::get('p') !== null && Input::get('t') !== null){
		$redirect = true;
		$text = Input::get('t');
		$page= Input::get('p');
	}
}
$login = false;
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$val->check($_POST, array(
			'username' => array(
				'required' => true
			),
			'password' => array(
				'required' => true
			)
		));
		if($val->passed()){
			$remember = (Input::get('remember') == 'on')? true:false;
			$user2 = $db->get('users', ['username', '=', escape(Input::get('username'))])->first();
			$mfa = json_decode($user2->mfa);
			if(Setting::get('enable-mfa') == 1 && Setting::get('enable-mfa-email') == 1 && Setting::get('enable-email') == 1){
				if($mfa->enable == 1){
					if(Input::get('tfaEmail') !==null){
						if(Input::get('tfaEmail') == $mfa->code){
							$user3 = new User();
							$login = $user3->login(escape(Input::get('username')), Input::get('password'), $remember);
							
							$mfa = json_decode($user3->data()->mfa, true);
							$mfa['code'] = "";
							$db->update('users', $user3->data()->id, ['mfa'=>json_encode($mfa)]);

							if($redirect){
								if($login){
									Redirect::to('/'.$page.'/?t='.$text);
								}
							}else{
								if($login){
									if($user2->confirmed !=1){
										$user2->logout();
									}else{
										Session::flash('complete', '<div class="alert alert-success">You have been logged in!</div>');
										Redirect::to('/');
									}
								}
							}
						}
					}else{
						$mfa_array = json_decode($user2->mfa, true);
						$mfa_code = Hash::unique_length(5);
						$mfa_array['code'] = $mfa_code;

						if($db->update('users', $user2->id, ["mfa"=>json_encode($mfa_array)])){
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
							$mail->addAddress($user2->email, escape($user2->name));
							
							$mail->Subject = 'Social-Media Multi-Factor Code';
							$html = file_get_contents('assets/email/emailMFA.html');
							$content = "This is one of your multi-factor authication code!";
							$html = str_replace(['[Name]','[Content]','[Code]'], [$user2->username ,$content, $mfa_code], $html);
							
							$mail->msgHTML($html);
							$mail->isHTML(true);
							$mail->Body = $html;
							$send_error = false;
							if(!$mail->send()){
								$send_error = true;
							}
						}
						require 'inc/includes/email2FA.php';
						die();
					}
				}else{
					$user3 = new User();
					$login = $user3->login(escape(Input::get('username')), Input::get('password'), $remember);
				
					if($redirect){
						if($login){
							Redirect::to('/'.$page.'/?t='.$text);
						}
					}else{
						if($login){
							if($user3->data()->confirmed !=1){
								$user3->logout();
							}else{
								Session::flash('complete', '<div class="alert alert-success">You have been logged in!</div>');
								Redirect::to('/');
							}
						}
					}
				}
			}else{
				$user3 = new User();
				$login = $user3->login(escape(Input::get('username')), Input::get('password'), $remember);
				if($redirect){
					if($login){
						Redirect::to('/'.$page.'/?t='.$text);
					}
				}else{
					if($login){
						if($user3->data()->confirmed == 1){
							Session::flash('complete', '<div class="alert alert-success">You have been logged in!</div>');
							Redirect::to('/');
						}else{
							$user3->logout();
						}
					}
				}
			}
		}
	}
}
?>
<html>
	<head>
	<?php include 'assets/head.php';?>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<?php if(Input::exists()): if(Token::check(Input::get('token'))): if(!$val->passed()):?>
				<div class="alert alert-danger"><?php foreach ($val->errors() as $error){echo $error.'<br/>';}?></div>
				<?php endif;endif;endif;?>
				<?php if(Input::exists()): if(!$login):?>
				<div class="alert alert-danger">Invalid Credentials!</div>
				<?php endif;endif;?>
				<h1>Login</h1>
				<form action="" method="post" autocomplete="off">
					<div class="form-group">
						<input type="text" name="username" placeholder="Username" value="<?php echo Input::get('username')?>" class="form-control input-lg">
					</div>
					<div class="form-group">
						<input type="password" name="password" placeholder="Password" class="form-control input-lg">
					</div>
					<div class="form-group">
						<label for="remember">Remember me?
							<input type="checkbox" name="remember" id="remember"/>
						</label>
					</div>
					<div class="row">
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<a class="btn btn-lg btn-danger btn-block" href="../register">Register</a>
							</div>
						</div>
						<div class="col-xs-12 col-md-6">
							<div class="form-group">
								<input type="hidden" name="token" value="<?php echo Token::generate()?>"/>
								<input class="btn btn-lg btn-primary btn-block" type="submit" value="Submit" id="Submit" name="submit"/>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php include 'assets/foot.php';?>
	</body>
</html>