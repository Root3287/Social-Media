<?php
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('/');
}
$token = Token::generate();
$db = DB::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		 <?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="col-md-8">
				<h1>Friends</h1>
				<?php
				foreach ($user->getFriends() as $friend) {
					if($friend->accepted == 1){
						if($friend->user_id == $user->data()->id){
							$f = new User($friend->friend_id);
						}else if($friend->friend_id == $user->data()->id){
							$f = new User($friend->user_id);
						}
				?>
				<div class="user">
					<div class="media">
						<div class="media-left"><a href="/u/<?php echo $f->data()->username;?>"><img src="<?php echo $f->getAvatarURL(70);?>" alt="{user.png}" class="media-object"></a>
						</div>
						<div class="media-body">
							<h3 class="media-heading"><a href="/u/<?php echo $f->data()->username;?>"><?php echo $f->data()->name;?></a></h3>
						</div>
					</div>
				</div>
				<?php
					}
				}
				?>
			</div>
			<div class="col-md-4">
				<div class="row">
					<h1>Friend Request</h1>
					<?php 
						if($user->hasFriendRequest()):
							foreach ($user->getFriendRequest() as $request):
								$potential_friend = new User($request->user_id);
					?>
						<div class="row">
							<div class="user">
								<div class="media">
								<?php if($request->accepted == 0):?>
									<div class="media-left"><a href="?user="><img src="<?php echo $potential_friend->getAvatarURL(70);?>" alt="{user.png}" class="media-object"></a></div>
									<div class="media-body">
										<h3 class="media-heading"><?php echo $potential_friend->data()->name;?></h3>
										<button id="accept" class="btn btn-sm btn-success" data-token="<?php echo $token;?>" data-user="<?php echo $potential_friend->data()->id;?>">Accept</button>
										<button id="declined" class="btn btn-sm btn-danger" data-token="<?php echo $token;?>" data-user="<?php echo $potential_friend->data()->id;?>">Decline</button>
									</div>
								<?php endif;?>
								</div>
							</div>
						</div>
					<?php endforeach;else:?>
						You don't have any friend request at the moment.
					<?php endif;?>
				</div>
				<div class="row">
					<h1>Send Friend Request</h1>
					<?php 
						$sfq_count = 1;
						$sfq_following = $user->getFollowing();
						// Convert the stdclss to an array
						$sfq_following = json_decode(json_encode($sfq_following), true);
						shuffle($sfq_following);
						foreach($sfq_following as $pf){
							$pf_user = new User($pf['following_id']);
							$sfq_sent_request = $db->query("SELECT * FROM friends WHERE user_id=? AND friend_id=? OR user_id=? AND friend_id=?", [$user->data()->id, $pf_user->data()->id, $pf_user->data()->id, $user->data()->id])->count();
							if($sfq_count<=5){
								if(!$sfq_sent_request >= 1){
					?>
					<div class="user">
						<div class="media">
							<div class="media-left"><a href="/u/<?php ?>"><img src="<?php echo $pf_user->getAvatarURL(70);?>" alt="{user.png}" class="media-object"></a>
							</div>
							<div class="media-body">
								<h3 class="media-heading"><?php echo $pf_user->data()->name;?></h3>
								<button id="request" class="btn btn-sm btn-default" data-token="<?php echo $token;?>" data-button="<?php echo $sfq_count?>" data-user="<?php echo $pf_user->data()->id;?>">Send Request</button>
							</div>
						</div>
					</div>
					<br>
					<?php
								}
							}else{
								break;
							}
							$sfq_count++;
					 	}
					?>
				</div>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<script>
			$(document).ready(function(){
				$("button#declined").click(function(e){
					e.preventDefault();
					$.post(
						"/action/friend",
						{
							"token": $(this).data('token'), 
							"user": $(this).data('user'),
							"accept": 2, //Declined
						},
						function(data){
							if(data["success"] == true){
								location.reload();
							}
						}, 
						"json"
					);
					return false;
				});
				$("button#accept").click(function(e){
					e.preventDefault();
					$.post(
						"/action/friend",
						{
							"token": $(this).data('token'), 
							"user": $(this).data('user'),
							"accept": 1, //Accept
						},
						function(data){
							if(data["success"] == true){
								location.reload();
							}
						}, 
						"json"
					);
					return false;
				});
				$("button#request").click(function(e){
					e.preventDefault();
					$.post(
						"/action/request",
						{
							"token": $(this).data('token'), 
							"user": $(this).data('user'),
							'button': $(this).data('button'),
						},
						function(data){
							if(data["success"] == true){
								$("button[id='request'][data-button="+data["button"]+"]").text("Request Sent!");
								$("button[id='request'][data-button="+data["button"]+"]").attr("id", "request-sent");
							}
						}, 
						"json"
					);
					return false;
				});
			});
		</script>
	</body>
</html>