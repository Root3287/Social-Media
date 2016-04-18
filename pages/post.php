<?php
$user = new User();
$post = new Post();
$post = $post->getPostByHash($pid);
if(!$post){
	Redirect::to('/404');
}

$user2 = new User($post->user_id);
?>
<html>
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="well">
					<div class="row">
						<div class="page-header">
  							<h1><img class="img-circle" src="<?php echo $user2->getAvatarURL('64')?>"><a href="/profile/<?php echo $user2->data()->username;?>"><?php echo $user2->data()->username;?></a></h1>
						</div>
						<?php echo $post->content;?>
						<hr>
						<?php if($user->isLoggedIn()):?>
						<a href="">__ Likes</a>
						<a href="">Repost</a>
						<?php endif;?>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>