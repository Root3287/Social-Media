<?php
$user = new User();
$post = new Post();
$poke = new Pokes();
$like = new Like();
$user2= new User(escape($profile_user));
$token = Token::generate();

$page = (Input::get('p') !=null)?Input::get('p'):1;
$limit = (Input::get('l') !=null)? Input::get('l'):10;

if(!$user2->exists()){
	Redirect::to(404);
}

$post_for_user = $post->getPostForUser($user2->data()->id);

$pagination = new PaginateArray($post_for_user);
$postData = $pagination->getArrayData($limit, $page);

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
		<script src="/assets/js/follow.js"></script>
		<script src="/assets/js/unfollow.js"></script>
		<script src="/assets/js/reply.js"></script>
		<script src="/assets/js/like.js"></script>
		<script src="/assets/js/dislike.js"></script>
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
								<?php 
								if($user->isLoggedIn()){
								if(!$user->isFollowing($user2->data()->id)):?>
									<button id="follow" class="btn btn-primary btn-md" data-user="<?php echo $user2->data()->id;?>" data-token="<?php echo $token;?>">Follow</button>
								<?php else:?>
									<button id="unfollow" class="btn btn-primary btn-md" data-user="<?php echo $user2->data()->id;?>" data-token="<?php echo $token;?>">UnFollow</button>
								<?php endif;}?>
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
								<h4><u>Bio</u></h4>
								<?php echo escape($user2->data()->bio);?>
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
					<?php if($user->data()->id !== $user2->data()->id):?>
					<div class="panel panel-primary">
						<div class="panel-heading">Function</div>
						<div class="panel-body">
							<?php if($poke->hasNoPokesPending($user->data()->id, $user2->data()->id)){?><a href="/pokes?token=<?php echo $token;?>&user2=<?php echo $user2->data()->id;?>">Poke</a><?php }?><br>
							<a href="/report/u/<?php echo $user2->data()->id;?>">Report User</a>
						</div>
					</div>
				<?php endif;?>
				</div>
				<div class="col-md-9">
					<h1>Timeline</h1>
					<?php
						foreach($postData as $uPost):
							$userPost = new User($uPost['user_id']);
							if($userPost->data()->id === $user->data()->id){ //if its the owner
					?>
							<div class='well'>
								<div class='post-header'>
									<h2><a href="/u/<?php echo $userPost->data()->username;?>/"><?php echo $userPost->data()->username; ?></a></h2>
								</div>
								<p><?php echo $uPost['content'];?></p>
								<hr>
								<?php foreach($post->getComments($uPost['id']) as $pComment): 
								$commentUser = new User($pComment->user_id);?>
								<div class="media">
									<div class="media-left"><a href="/u/<?php echo $commentUser->data()->username;?>/"><img src="<?php echo $commentUser->getAvatarURL(48);?>" alt="{user.png}" class="media-object"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/u/<?php echo $commentUser->data()->username;?>/"><?php echo $commentUser->data()->name;?></a></h4>
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
										   <?php if($user->isLoggedin()):?><input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										   <?php endif;?>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
							</div>
					<?php
							}else{
								if($uPost['active'] !=0){ //if its still active...
							
									$private = $uPost["privacy"];

									switch ($private) {
									
										case "0": //Public 
					?>
							<div class='well'>
								<div class='post-header'>
									<h2><a href="/u/<?php echo $userPost->data()->username;?>/"><?php echo $userPost->data()->username; ?></a></h2>
								</div>
								<p><?php echo $uPost['content'];?></p>
								<hr>
								<?php foreach($post->getComments($uPost['id']) as $pComment): 
								$commentUser = new User($pComment->user_id);?>
								<div class="media">
									<div class="media-left"><a href="/u/<?php echo $commentUser->data()->username;?>/"><img src="<?php echo $commentUser->getAvatarURL(48);?>" alt="{user.png}" class="media-object"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/u/<?php echo $commentUser->data()->username;?>/"><?php echo $commentUser->data()->name;?></a></h4>
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
										   <?php if($user->isLoggedin()):?><input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										   <?php endif;?>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
							</div>
					<?php
											break;

										case "1": //Following
											$db = DB::getInstance();
											$mentioned = $db->get('mensions', ['post_hash', '=', $uPost['hash']]);
											if($user->isFollowing($userPost->data()->id) || $mentioned->count() && $user->data()->id == $mentioned->first()->user_id):
					?>
							<div class='well'>
								<div class='post-header'>
									<h2><a href="/u/<?php echo $userPost->data()->username;?>/"><?php echo $userPost->data()->username; ?></a></h2>
								</div>
								<p><?php echo $uPost['content'];?></p>
								<hr>
								<?php foreach($post->getComments($uPost['id']) as $pComment): 
								$commentUser = new User($pComment->user_id);?>
								<div class="media">
									<div class="media-left"><a href="/u/<?php echo $commentUser->data()->username;?>/"><img src="<?php echo $commentUser->getAvatarURL(48);?>" alt="{user.png}" class="media-object"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/u/<?php echo $commentUser->data()->username;?>/"><?php echo $commentUser->data()->name;?></a></h4>
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
										   <?php if($user->isLoggedin()):?><input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										   <?php endif;?>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
							</div>
					<?php
											endif;
											break;

										case "2": //
											break;

										case "3": // Friends
										$db = DB::getInstance();
										$mentioned = $db->get('mensions', ['post_hash', '=', $uPost['hash']]);
										if($user->isFriends($userPost->data()->id) || $mentioned->count() && $user->data()->id == $mentioned->first()->user_id){
					?>
							<div class='well'>
								<div class='post-header'>
									<h2><a href="/u/<?php echo $userPost->data()->username;?>/"><?php echo $userPost->data()->username;?></a></h2>
								</div>
								<p><?php echo $uPost['content'];?></p>
								<hr>
								<?php foreach($post->getComments($uPost['id']) as $pComment): 
								$commentUser = new User($pComment->user_id);?>
								<div class="media">
									<div class="media-left"><a href="/u/<?php echo $commentUser->data()->username;?>/"><img src="<?php echo $commentUser->getAvatarURL(48);?>" alt="{user.png}" class="media-object"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/u/<?php echo $commentUser->data()->username;?>/"><?php echo $commentUser->data()->name;?></a></h4>
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
										   <?php if($user->isLoggedin()):?><input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										   <?php endif;?>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
							</div>
					<?php
											}
											break;

										case "4": //Private
											$db = DB::getInstance();
											$mentioned = $db->get('mensions', ['post_hash', '=', $uPost['hash']]);
										
											if($user->data()->id == $userPost->data()->id || $mentioned->count() && $user->data()->id == $mentioned->first()->user_id){
					?>
							<div class='well'>
								<div class='post-header'>
									<h2><a href="/u/<?php echo $userPost->data()->username;?>/"><?php echo $userPost->data()->username; ?></a></h2>
								</div>
								<p><?php echo $uPost['content'];?></p>
								<hr>
								<?php foreach($post->getComments($uPost['id']) as $pComment): 
								$commentUser = new User($pComment->user_id);?>
								<div class="media">
									<div class="media-left"><a href="/u/<?php echo $commentUser->data()->username;?>/"><img src="<?php echo $commentUser->getAvatarURL(48);?>" alt="{user.png}" class="media-object"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading"><a href="/u/<?php echo $commentUser->data()->username;?>/"><?php echo $commentUser->data()->name;?></a></h4>
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
										   <?php if($user->isLoggedin()):?><input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										   <?php endif;?>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
							</div>
					<?php
											}
											break;
									}
								}
							}
					
						endforeach;
					?>
					<div class="row">
						<ul class="pagination">
							<?php for($i = 1; $i<=$pagination->getTotalPages(); $i++):?>
								<li><a href="?p=<?php echo $i?>&l=<?php echo $limit;?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						</ul>
					</div>	
				</div>
			</div>
		</div>
		<?php include 'assets/foot.php';?>
	</body>
</html>