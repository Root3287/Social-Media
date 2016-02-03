<?php
$user = new User();
$post = new Post();
$poke = new Pokes();
$token = Token::generate();
if(!$user->isLoggedIn()){Redirect::to('/404');}
if(!$profile_user){
	Redirect::to('/404');//MAke 404
}
$db = DB::getInstance();
$user_exists= $db->get('users', ['username','=', escape($profile_user)]);
if(!$user_exists->first()){
	Redirect::to('/404');
}
if(strcasecmp($user_exists->first()->username, $profile_user)){
	Redirect::to('/404'); // Make 404
}
$user2= new User(escape($profile_user));
if(!$user2->exists()){
	Redirect::to('/404');
}
if($user->data()->username !== $user2->data()->username){
?>
<html>
	<head>
		<?php include 'assets/head.php';?>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<?php if(Session::exists('complete')){echo Session::flash('complete');}if(Session::exists('error')){echo Session::flash('error');}?>
			<div class="row">
				<div class="jumbotron">
					<h1>
						<img class="img-circle" src="<?php echo $user2->getAvatarURL('96')?>">
						<?php echo $user2->data()->username?>
						<?php if(!$user->isFollowing($user2->data()->id)):?>
							<a class="btn btn-primary btn-md" href="/action/profile/?action=Follow&user=<?php echo $user2->data()->id;?>">Follow</a>
						<?php else:?>
							<a class="btn btn-primary btn-md" href="/action/profile/?action=UnFollow&user=<?php echo $user2->data()->id;?>">UnFollow</a>
								<?php if($user->isFriends($user2->data()->id)){?>
								<a class="btn btn-primary btn-md" href="/action/profile/?action=unFriend&user=<?php echo $user2->data()->id;?>">UnFriend</a>
								<?php }else{?>
									<?php if(!$user->hasFriendRequest($user2->data()->id)){?>
									<a class="btn btn-primary btn-md" href="/action/profile/?action=Friend&user=<?php echo $user2->data()->id;?>">Friend</a>
									<?php }else{?>
									<a href="/action/profile/?action=unFriend&user=<?php echo $user2->data()->id;?>" class="btn btn-primary btn-md">Request Sent!</a>
								<?php }} ?>
						<?php endif;?>

					</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="panel panel-primary">
						<div class="panel-heading">
							More Information
						</div>
						<div class="panel-body">
								<h4><u>Followers</u></h4>
								<?php echo count($user2->getFollowers());?>
								<h4><u>Following</u></h4>
								<?php echo count($user2->getFollowing());?>
							<?php if($user2->data()->private == 0 || $user->isFriends($user2->data()->id)):?>
								<h4><u>Email</u></h4>
								<p><?php echo $user2->data()->email?></p>
								<h4><u>Joined Date</u></h4>
								<p><?php echo $user2->data()->joined?></p>
								<h4><u>Number of posts</u></h4>
								<p><?php echo $post->getPostByUser($user2->data()->id)->count();?></p>
							<?php else:?>
							<h3>This is a private profile</h3>
							<?php endif;?>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Function</div>
						<div class="panel-body">
							<?php if($poke->hasNoPokesPending($user->data()->id, $user2->data()->id)){?><a href="/pokes?token=<?php echo $token;?>&user2=<?php echo $user2->data()->id;?>">Poke</a><?php }?>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<h1>Timeline</h1>
					<?php
					if($user2->data()->private == 0 || $user->isFriends($user2->data()->id)) {
						foreach($post->getPostForUser($user2->data()->id) as $uPost){
							$userPost = new User($uPost['user_id']);
							echo "<div class='well'>";
							echo "<div class='post-header'><h2>".$userPost->data()->username."</h2></div>";
							echo "<p>".$uPost['content']."</p>";
							echo "</div>";
						}
					}else{
							echo "<h2>This user is private! You need to follow them to get their info.</h2>";
						}
					?>
				</div>
			</div>
		</div>
		<?php include 'assets/foot.php'?>
	</body>
</html>
<?php }else{?>
<html>
	<head>
		<?php include 'assets/head.php';?>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<div class="row">
				<div class="jumbotron">
					<h1>
						<img class="img-circle" src="<?php echo $user->getAvatarURL('96')?>">
						<?php echo $user->data()->username?>
						<p>This is you</p>
					</h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="panel panel-primary">
						<div class="panel-heading">
							More Information
						</div>
						<div class="panel-body">
							<h4><u>Followers</u></h4>
							<?php echo count($user->getFollowers());?>
							<h4><u>Following</u></h4>
							<?php echo count($user->getFollowing());?>
							<h4><u>Email</u></h4>
							<p><?php echo $user->data()->email?></p>
							<h4><u>Joined Date</u></h4>
							<p><?php echo $user->data()->joined?></p>
							<h4><u>Number of posts</u></h4>
							<p><?php echo $post->getPostByUser($user->data()->id)->count();?></p>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<h1>Timeline</h1>
					<?php
					foreach($post->getPostForUser($user2->data()->id) as $uPost){
						$userPost = new User($uPost['user_id']);
						echo "<div class='well'>";
						echo "<div class='post-header'><h2>".$userPost->data()->username."</h2></div>";
						echo "<p>".$uPost['content']."</p>";
						echo "</div>";
					}
					?>
				</div>
			</div>
		</div>
		<?php include 'assets/foot.php'?>
	</body>
</html>
<?php } ?>