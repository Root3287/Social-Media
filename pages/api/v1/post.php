<?php
$user = new User();
$post = new Post();
if(!$user->isLoggedIn()){
	Redirect::to('/login/?p=api/v1/post&t='.Input::get('t'));
}
if (Input::exists()) {
	if(Token::check(Input::get('token'))){
		$val = new Validation();
			$validate = $val->check($_POST, [
				'post_status'=>[
					'required'=>true,
				],
			]);
			if($validate->passed()){
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
				try{
					if($g_reponse == null || $g_reponse->success){
						$post->create(escape(Input::get('post_status')),$user->data()->id);
						$user->update([
							'score'=> $user->data()->score+1,
						]);
						Session::flash('complete', '<div class="alert alert-success">You have submitted a post!</div>');
						Redirect::to('/');
					}
				}catch(Exception $e){
					
				}
			}
	}
}
$token = Token::generate();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
		<?php if(Setting::get('enable-recaptcha') == 1){?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php }?>
		<style>
			.divider{
				margin-bottom: 30px;
			}
		</style>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<?php if(Session::exists('completed')){echo Session::flash('completed');}?>
			<form id="status" action="" method="post">
				<div class="form-group">
					<textarea style="resize: none;" class="form-control" name="post_status" id="post_status" rows="10" placeholder="Talk about your life here!"><?php echo Input::get('t');?></textarea>
				</div>
				<?php if(Setting::get('enable-recaptcha')){?>
					<div class="form-group">
						<div class="g-recaptcha" data-sitekey="<?php echo Setting::get('recaptcha-site-key');?>"></div>
					</div>
				<?php }?>
				<div class="form-group">
					<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
					<span class="pull-right"><button id="submit" class="btn btn-primary">Post!</button></span>
				</div>
			</form>
		</div>
		<?php require 'assets/foot.php';?>
		<script type="text/javascript">
			$(document).ready(function(){
				var inner = $(".alert").text();
				$(".alert").html(inner+" <strong><a href=\"/\">Go home</a></strong>");
				if ($(".alert").length === 1){
    				window.location.href = "/";
				}
			});
		</script>
	</body>
</html>