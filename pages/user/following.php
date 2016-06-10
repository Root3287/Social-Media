<?php
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('/');
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
			<div class="col-md-6">
				<h1>Following</h1>
				<?php foreach($user->getFollowing() as $following): $following_user = new User($following->following_id);?>
					<div class="user">
						<div class="media">
							<div class="media-left"><a href="/u/<?php echo $following_user->data()->username;?>"><img src="<?php echo $following_user->getAvatarURL(70);?>" alt="{user.png}" class="media-object"></a>
							</div>
							<div class="media-body">
								<h3 class="media-heading"><a href="/u/<?php echo $following_user->data()->username;?>"><?php echo $following_user->data()->name;?></a></h3>
							</div>
						</div>
					</div>
				<?php endforeach;?>
			</div>
			<div class="col-md-6">
				<h1>Followers</h1>
				<?php foreach ($user->getFollowers() as $followers): $followers_user = new User($followers->user_id);?>
					<div class="user">
						<div class="media">
							<div class="media-left"><a href="/u/<?php echo $followers_user->data()->username;?>"><img src="<?php echo $following_user->getAvatarURL(70);?>" alt="{user.png}" class="media-object"></a>
							</div>
							<div class="media-body">
								<h3 class="media-heading"><a href="/u/<?php echo $followers_user->data()->username;?>"><?php echo $followers_user->data()->name;?></a></h3>
							</div>
						</div>
					</div>
				<?php endforeach;?>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>