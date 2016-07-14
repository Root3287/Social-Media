<?php
$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('/login/?p=api/post&t='.Input::get('t'));
}
$token = Token::generate();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
		<script type="text/javascript" src="/assets/js/status.js"></script>
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
			<form id="status" action="/" method="post">
				<div class="form-group">
					<textarea style="resize: none;" class="form-control" name="post_status" id="post_status" rows="10" placeholder="Talk about your life here!"><?php echo Input::get('t');?></textarea>
				</div>
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
			});
		</script>
	</body>
</html>