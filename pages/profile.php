<?php
$user = new User();
$post = new Post();
$poke = new Pokes();
$like = new Like();
$user2= new User(escape($profile_user));
$token = Token::generate();

if(!$user2->exists()){
	Redirect::to(404);
}

if($user->data()->username !== $user2->data()->username){ // Users is not viewing their own page
	if(!$user->isLoggedIn() && $user2->data()->private == 1){
		Redirect::to(404);
	}
?>
<html>
	<head>
		<?php include 'assets/head.php';?>
		<style>
		.name{
			display: inline;
		}
		</style>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<?php 
				if(Session::exists('complete')){
					echo Session::flash('complete');
				}
				if(Session::exists('error')){
					echo Session::flash('error');
				}
			?>
			<div class="row">
				<div class="jumbotron">
					<div class="media">
							<div class="media-left">
								<img class="media-object img-circle" alt="{user.img}" src="<?php echo $user2->getAvatarURL('96');?>">
							</div>
							<div class="media-body">
								<div class="name">
									<h1 class="name"><?php echo $user2->data()->username;?></h1>
									<?php if($user2->data()->verified == 1){?><h4 class="name"><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span></h4><?php }?>
								</div>
								<br>
									<?php if(!$user->isFollowing($user2->data()->id)):?>
										<button id="follow" class="btn btn-primary btn-md" data-user="<?php echo $user2->data()->id;?>" data-token="<?php echo $token;?>">Follow</button>
									<?php else:?>
										<button id="unfollow" class="btn btn-primary btn-md" data-user="<?php echo $user2->data()->id;?>" data-token="<?php echo $token;?>">UnFollow</button>
									<?php endif;?>
							</div>
						</div>
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
								<h4><u>Score</u></h4>
								<p><?php echo $user2->data()->score;?></p>
							<?php else:?>
							<h3>This is a private profile</h3>
							<?php endif;?>
						</div>
					</div>
					<div class="panel panel-primary">
						<div class="panel-heading">Function</div>
						<div class="panel-body">
							<?php if($poke->hasNoPokesPending($user->data()->id, $user2->data()->id)){?><a href="/pokes?token=<?php echo $token;?>&user2=<?php echo $user2->data()->id;?>">Poke</a><?php }?><br>
							<a href="/report/u/<?php echo $user2->data()->id;?>">Report User</a>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<h1>Timeline</h1>
					<?php
					if($user2->data()->private == 0 || $user->isFriends($user2->data()->id)) {
						foreach($post->getPostForUser($user2->data()->id) as $uPost){
							$userPost = new User($uPost['user_id']);
					?>
							<div class='well'>
								<div class='post-header'>
									<h2><?php echo $userPost->data()->username; ?></h2>
								</div>
								<p><?php echo $uPost['content'];?></p>
								<hr>
								<?php foreach($post->getComments($uPost['id']) as $pComment): 
								$commentUser = new User($pComment->user_id);?>
								<div class="media">
									<div class="media-left"><a href="/u/<?php echo $commentUser->data()->username;?>"><img src="<?php echo $commentUser->getAvatarURL(48);?>" alt="{user.png}" class="media-object"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/u/<?php echo $commentUser->data()->username;?>"><?php echo $commentUser->data()->name;?></a></h4>
										<?php 
										echo $pComment->content;
										?>
									</div>
								</div>
								<?php endforeach;?>
								<hr>
								<form id="reply" action="" class="form-inline" method="post" autocomplete="off">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-btn">
										   		<?php if($like->hasLike($user->data()->id, $uPost['id']) <= 0){?>
										   		<a href="" id="like" data-token="<?php echo $token;?>" data-post="<?php echo $uPost['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($uPost['id'])->count();?></a>
										   		<?php }else{?>
										   		<a href="" id="dislike" data-token="<?php echo $token;?>" data-post="<?php echo $uPost['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($uPost['id'])->count();?></a>
										   		<?php }?>
										   </span>
										   <input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
							</div>
					<?php
						}
					}else{
							echo "<h2>This user is private! You need to follow or friend them to get their info.</h2>";
						}
					?>
				</div>
			</div>
		</div>
		<?php include 'assets/foot.php';?>
		<script>
			$().ready(function(){
				$("button#follow").click(function(e){
					e.preventDefault();
					$.post(
						"/action/follow",
						{
							"token": $(this).data('token'), 
							"user": $(this).data('user'),
							"action": 1,
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
				$("button#unfollow").click(function(e){
					e.preventDefault();
					$.post(
						"/action/follow",
						{
							"token": $(this).data('token'), 
							"user": $(this).data('user'),
							"action": 0,
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
				$("form#reply").keypress(function(e){
					if (e.which == 13) {
						$(this).submit();
						return false;
					}
				}).submit(function(e){
					e.preventDefault();

					$.post("/action/reply", $(this).serialize(), function(data){
						if(data["success"]){
							location.reload();
						}
					}, "json");
					return false;
				});
				$("[id='like']").click(function(e){
					e.preventDefault();	
					$.post(
						"/action/like",
						{
							"token": $(this).data('token'), 
							"post": $(this).data('post'),
						},
						function(data){
							if(data["success"]){
								location.reload();
							}
						}, 
						"json"
					);
					return false;
				});

				$("[id='dislike']").click(function(e){
					e.preventDefault();

					$.post(
						"/action/dislike",
						{
							"token": $(this).data('token'), 
							"post": $(this).data('post')
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
			});
		</script>
	</body>
</html>
<?php }else{ // user is viewing their own page ?>

<html>
	<head>
		<?php include 'assets/head.php';?>
		<style>
		.name{
			display: inline;
		}
		</style>
	</head>
	<body>
		<?php include 'assets/nav.php';?>
		<div class="container">
			<div class="row">
				<div class="jumbotron">
					<div class="media">
							<div class="media-left">
								<img class="media-object img-circle" alt="{user.img}" src="<?php echo $user->getAvatarURL('96');?>">
								<!--<p>This is you</p>-->
							</div>
							<div class="media-body">
								<div class="name">
									<h1 class="name"><?php echo $user->data()->username;?></h1>
									<?php if($user->data()->verified == 1){?><h4 class="name"><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span></h4><?php }?>
								</div>
							</div>
						</div>
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
							<h4><u>Score</u></h4>
							<p><?php echo $user->data()->score;?></p>
						</div>
					</div>
				</div>
				<div class="col-md-9">
					<div class="row">
						<form id="status" action="/timeline" method="post">
							<div class="form-group">
								<textarea style="resize: none;" class="form-control" name="post_status" id="post_status" rows="10" placeholder="Talk about your life here!"></textarea>
							</div>
							<div class="form-group">
								<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
								<span class="pull-right"><button id="submit" class="btn btn-sm btn-primary">Post!</button></span>
							</div>
						</form>
					</div>
					<div class="row">
						<h1>Timeline</h1>
						<?php
						foreach($post->getPostForUser($user2->data()->id) as $uPost){
							$userPost = new User($uPost['user_id']);
						?>
							<div class='well'>
								<div class='post-header'>
									<a href='/u/<?php echo $userPost->data()->username;?>'>
										<h2><?php echo $userPost->data()->username;?></h2>
									</a>
								</div>
								<p><?php echo $uPost['content'];?></p>
								<hr>
								<?php foreach($post->getComments($uPost['id']) as $pComment): 
								$commentUser = new User($pComment->user_id);?>
								<div class="media">
									<div class="media-left"><a href="/u/<?php echo $commentUser->data()->username;?>"><img src="<?php echo $commentUser->getAvatarURL(48);?>" alt="{user.png}" class="media-object"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/u/<?php echo $commentUser->data()->username;?>"><?php echo $commentUser->data()->name;?></a></h4>
										<?php 
										echo $pComment->content;
										?>
									</div>
								</div>
								<?php endforeach;?>
								<hr>
								<form id="reply" action="" class="form-inline" method="post" autocomplete="off">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-btn">
										   		<?php if($like->hasLike($user->data()->id, $uPost['id']) <= 0){?>
										   		<a href="" id="like" data-token="<?php echo $token;?>" data-post="<?php echo $uPost['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($uPost['id'])->count();?></a>
										   		<?php }else{?>
										   		<a href="" id="dislike" data-token="<?php echo $token;?>" data-post="<?php echo $uPost['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($uPost['id'])->count();?></a>
										   		<?php }?>
										   </span>
										   <input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
							</div>
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<?php include 'assets/foot.php';?>
		<script>
			$(document).ready(function(){
				$("form#status").submit(function(e){
					e.preventDefault();

					$.post("/action/status", $(this).serialize(), function(data){
						if(data["success"]){
							location.reload();
						}
					}, "json");
					return false;
				});
				$("form#reply").keypress(function(e){
					if (e.which == 13) {
						$(this).submit();
						return false;
					}
				}).submit(function(e){
					e.preventDefault();

					$.post("/action/reply", $(this).serialize(), function(data){
						if(data["success"]){
							location.reload();
						}
					}, "json");
					return false;
				});
				$("[id='like']").click(function(e){
					e.preventDefault();	
					$.post(
						"/action/like",
						{
							"token": $(this).data('token'), 
							"post": $(this).data('post'),
						},
						function(data){
							if(data["success"]){
								location.reload();
							}
						}, 
						"json"
					);
					return false;
				});

				$("[id='dislike']").click(function(e){
					e.preventDefault();

					$.post(
						"/action/dislike",
						{
							"token": $(this).data('token'), 
							"post": $(this).data('post')
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
			});
		</script>
	</body>
</html>
<?php } ?>