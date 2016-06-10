<?php
$user = new User();
$post = new Post();
$like = new Like();
$original_post = $post->getPostByHash($pid);
if(!$post){
	Redirect::to('/404');
}

$user2 = new User($original_post->user_id);
$token = Token::generate();
?>
<!DOCTYPE HTML>
<html>
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
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="well">
					<div class="row">
						<div class="page-header">
  							<h1><img class="img-circle" src="<?php echo $user2->getAvatarURL('64')?>"><a href="/u/<?php echo $user2->data()->username;?>"><?php echo $user2->data()->username;?></a></h1>
						</div>
						<?php echo $original_post->content;?>
						<hr>
						<?php if($user->isLoggedIn()):?>
						<form id="reply" action="/action/reply/" class="form-inline" method="post" autocomplete="off">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-btn">
										<?php if($like->hasLike($user->data()->id, $original_post->id) <= 0){?><a href="/action/like" id="like" class="btn btn-primary"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($original_post->id)->count();?></a><?php }else{?><a href="/action/dislike/" id="dislike" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($original_post->id)->count();?></a><?php }?>
									</span>
									<input style="width: 100%" placeholder="comment" name="post" type="text" id="comment" class="form-control">
									<span class="input-group-btn">
								    	<button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
									</span>
									<span class="input-group-btn">
										<div class="dropdown">
											<button class="btn btn-default dropdown-toggle" type="button" id="PostMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
											    ...
											    <span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" aria-labelledby="PostMenu">
										 		<li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo getSelfURL()."/p/".$original_post->hash;?>">Tweet</a></li>
												<li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo getSelfURL()."/p/".$original_post->hash;?>">Share on Facebook</a></li>
											</ul>
										</div>
									</span>
								</div>
							</div>
							<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
							<input name="original_post" type="hidden" id="post" value="<?php echo $original_post->id;?>">
						</form>
						<?php endif;?>
					</div>
				</div>
				<div class="row">
					<h1>Replies</h1>
					<?php if($post->getComments($original_post->id)){
						foreach($post->getComments($original_post->id) as $reply){
							$reply_user = new User($reply->user_id);?>
						<div class="well">
							<h2><?php echo $reply_user->data()->username;?></h2>
							<hr>
							<p><?php echo $reply->content?></p>
						</div>
					<?php }}?>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
		<?php require 'assets/foot.php';?>
		<script>
			$(function(){
				$("#like").click(function(e){
					e.preventDefault();
					$.post(
						"/action/like",
						{
							"token": $("input#token").val(), 
							"post": $("input#post").val(),
						},
						function(data){
							if(data["success"]){
								location.reload();
							}
						}, 
						"json"
					);
				});
				$("#dislike").click(function(e){
					e.preventDefault();
					$.post(
						"/action/dislike",
						{
							"token": $("input#token").val(), 
							"post": $("input#post").val()
						},
						function(data){
							if(data["success"]){
								location.reload();
							}
						}, 
						"json"
					);
				});
				$("form#reply").submit(function (e) {
					e.preventDefault();
					$.post("/action/reply", $("form#reply").serialize(), function(data){
						if(data["success"] == true){
							location.reload();
						}
					}, "json");
				});
			});
		</script>
	</body>
</html>