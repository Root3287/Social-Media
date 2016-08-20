<?php
$user = new User();
$post = new Post();
$like = new Like();
$original_post = $post->getPostByHash($pid);
if(!$post || $original_post->active==0){
	Redirect::to(404);
}

$user2 = new User($original_post->user_id);
$token = Token::generate();

$db = DB::getInstance();

$show = true;

//Public
if($original_post->privacy == 0){

}
//following
else if($original_post->privacy == 1){
	if($user->isLoggedIn()){

		if(!$user->isFollowing($user2->data()->id)){
			$show = false;
		}else{
			$show = true;
		}
		
		if(!$show){
			if($user->data()->id == $user2->data()->id){
				$show = true;
			}else{
				$show = false;
			}
		}
		$mentioned = $db->get('mensions', ['post_hash', '=', $original_post->hash]);
		
		if(!$show){
			if ($mentioned->count()) {
				foreach ($mentioned->results() as $m) {
					if($user->data()->id == $m->user_id){
						$show = true;
						break;
					}else{
						$show = false;
					}
				}
			}
		}
	}else{
		$show = false;
	}
}
//follwer
else if($original_post->privacy == 2){
	$show = false;
}
//friend
else if($original_post->privacy == 3){
	if($user->isLoggedIn()){

		if(!$user->isFriends($user2->data()->id)){
			$show = false;
		}else{
			$show = true;
		}

		if(!$show){
			if($user->data()->id != $user2->data()->id){
				$show = false;
			}else{
				$show = true;
			}
		}

		$mentioned = $db->get('mensions', ['post_hash', '=', $original_post->hash]);
		if(!$show){
			if ($mentioned->count()) {
				foreach ($mentioned->results() as $m) {
					if($user->data()->id == $m->user_id){
						$show = true;
						break;
					}else{
						$show = false;
					}
				}
			}
		}
	}else{
		$show = false;
	}
}
//private
else if($original_post->privacy == 4){
	if($user->isLoggedIn()){
		if(!$show){
			if($user->data()->id != $user2->data()->id){
				$show = false;
			}else{
				$show = true;
			}
		}

		$mentioned = $db->get('mensions', ['post_hash', '=', $original_post->hash]);
		
		if(!$show){
			if ($mentioned->count()) {
				foreach ($mentioned->results() as $m) {
					if($user->data()->id == $m->user_id){
						$show = true;
						break;
					}else{
						$show = false;
					}
				}
			}
		}

	}else{
		$show = false;
	}
}

if(!$show){
	Redirect::to(404);
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<?php require 'assets/head.php';?>
		<script src="/assets/js/like.js"></script>
		<script src="/assets/js/dislike.js"></script>
		<script src="/assets/js/reply.js"></script>
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
  							<h1><img class="img-circle" src="<?php echo $user2->getAvatarURL('64')?>"><a href="/u/<?php echo $user2->data()->username;?>/"><?php echo $user2->data()->username;?></a></h1>
						</div>
						<?php echo $original_post->content;?>
						<hr>
						<?php if($user->isLoggedIn()):?>
						<form id="reply" action="" class="form-inline" method="post" autocomplete="off">
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-btn">
										<?php if($like->hasLike($user->data()->id, $original_post->id) <= 0){?>
											<a href="" id="like" class="btn btn-primary" data-token="<?php echo $token;?>" data-post="<?php echo $original_post->id;?>">
												<span class="glyphicon glyphicon-star-empty"></span>
												<?php echo $like->getLikesByPost($original_post->id)->count();?>
											</a>
										<?php }else{?>
											<a href="" id="dislike" class="btn btn-primary" data-token="<?php echo $token;?>" data-post="<?php echo $original_post->id;?>">
												<span class="glyphicon glyphicon-star"></span> 
												<?php echo $like->getLikesByPost($original_post->id)->count();?>
											</a>
										<?php }?>
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
												<li><a href="/report/p/<?php echo $pid;?>">Report Post</a></li>
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
					<h1><?php echo $GLOBALS['language']->get('comment');?></h1>
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
	</body>
</html>