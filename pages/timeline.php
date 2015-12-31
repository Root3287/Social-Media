<?php
$user = new User();
$post = new Post();
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			'post'=>[
				'required'=>true,
			],
		]);
		if($validate->passed()){
			try{
				$post->create(Input::get('post'),$user);
				Session::flash('success', '<div class="alert alert-success">You created your post</div>');
				Redirect::to('/');
			}catch(Exception $e){
				Session::flash('error', '<div class="alert alert-danger">There was an error making a post!</div>');
				Redirect::to('/');
			}
		}
	}
}
?>
<html>
	<head>
		<?php require 'assets/head.php';?>
		<script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script>	
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<?php
			if(Session::exists('success')){
				echo Session::flash('success');
			}else if(Session::exists('error')){
				echo Session::flash('error');
			}
			?>
			<div class="col-md-2">
			</div>
			<div class="col-md-8">
				<div class="row"><!-- Posts a status -->
					<form action="/" method="post">
						<div class="form-group">
							<textarea name="post" id="message" rows="1" placeholder="Talk about your life here!"></textarea>
						</div>
						<div class="form-group">
							<input name="token" type="hidden" id="token" value="<?php echo Token::generate();?>">
							<span class="pull-right"><button id="submit" class="btn btn-sm btn-primary">Post!</button></span>
						</div>
					</form>
				</div>
				<div class="row"><!-- Other people status -->
					<?php foreach($post->getPostForTimeline($user->data()->id) as $timeline):?>
						<div class="well">
							<div class="page-header">
								<h1><?php $timelineUser = new User($timeline['user_id']); echo "<a href='/profile/{$timelineUser->data()->username}'>".$timelineUser->data()->username."</a>";?></h1>
							</div>
							<p><?php echo $timeline['content'];?></p>
						</div>
					<?php endforeach;?>
				</div>
			</div>
			<div class="col-md-2">
				<p id="me"></p>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<script type="text/javascript">
			$().ready(function(){
				CKEDITOR.replace('message', {
					removeButtons: 'Source'
				});
			});
		</script>
	</body>
</html>