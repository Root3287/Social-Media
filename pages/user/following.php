<?php
$user = new User();
$timeAgo = new TimeAgo();
if(!$user->isLoggedIn()){
	Redirect::to('/');
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
		<style>
			.user {
				padding-bottom: 10px;
			}
		</style>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="col-md-6">
				<h1><?php echo  $GLOBALS['language']->get('followings')?></h1>
				<?php foreach($user->getFollowing() as $following): 
				$following_user = new User($following->following_id);
				$following_user_online =($following_user->data()->last_online <= strtotime("-10 minutes"))? false: true;
				?>
					<div class="user">
						<div class="media">
							<div class="media-left">
								<a href="/u/<?php echo $following_user->data()->username;?>/"><img src="<?php echo $following_user->getAvatarURL(70);?>" alt="{user.png}" class="media-object"></a>
							</div>
							<div class="media-body">
								<h3 class="media-heading">
									<a href="/u/<?php echo $following_user->data()->username;?>/"><?php echo $following_user->data()->name;?></a>
								</h3>
								<?php
									$last = $following_user->data()->last_online;
									$dt = new DateTime("@$last");
									$timeAgo_words = $timeAgo->inWords($dt->format('Y/m/d H:i:s'));
									if($following_user_online){
										echo "<span class='label label-success'>".$GLOBALS['language']->get('online')."</span>";
									}else{
										echo "<span class=\"label label-danger\">".$GLOBALS['language']->get(
												'offline')." $timeAgo_words ".$GLOBALS['language']->get('time-ago')."</span>";
									}
								?>
							</div>
						</div>
					</div>
				<?php endforeach;?>
			</div>
			<div class="col-md-6">
				<h1>Followers</h1>
				<?php foreach ($user->getFollowers() as $followers): 
					$followers_user = new User($followers->user_id);
					$follower_user_online =($followers_user->data()->last_online <= strtotime("-10 minutes"))? false: true;
				?>
					<div class="user">
						<div class="media">
							<div class="media-left">
								<a href="/u/<?php echo $followers_user->data()->username;?>/"><img src="<?php echo $followers_user->getAvatarURL(70);?>" alt="{user.png}" class="media-object"></a>
							</div>
							<div class="media-body">
								<h3 class="media-heading">
									<a href="/u/<?php echo $followers_user->data()->username;?>/"><?php echo $followers_user->data()->name;?></a>
									</h3>
									<?php 
										$last = $followers_user->data()->last_online;
										$dt = new DateTime("@$last");
										$timeAgo_words = $timeAgo->inWords($dt->format('Y/m/d H:i:s'));
										if($follower_user_online){
											echo "<span class='label label-success'>".$GLOBALS['language']->get('online')."</span>";
										}else{
											echo "<span class=\"label label-danger\">".$GLOBALS['language']->get(
												'offline')." $timeAgo_words ".$GLOBALS['language']->get('time-ago')."</span>";
										}
									?>
							</div>
						</div>
					</div>
				<?php endforeach;?>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>