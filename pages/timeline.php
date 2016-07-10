<?php
$user = new User();
$post = new Post();
$pokes= new Pokes();
$like = new Like();
$pagination = new PaginateArray($post->getPostForTimeline($user->data()->id));
$page = (Input::get('p') !=null)?Input::get('p'):1;
$limit = (Input::get('l') !=null)? Input::get('l'):10;
$timelineData = $pagination->getArrayData($limit, $page);
$token = Token::generate();

if(!$user->isLoggedIn()){
	Redirect::to('/');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require 'assets/head.php';?>
		<!--<script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script>-->
		<style type="text/css">
			.name{
				display: inline;
			}
		</style>
		<script src="assets/js/like.js" async></script>
		<script src="assets/js/dislike.js" async></script>
		<script src="assets/js/status.js" async></script>
		<script src="assets/js/reply.js" async></script>
		<script src="assets/js/picture.js" async></script>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<?php
			if(Session::exists('success')){
				echo Session::flash('success');
			}else if(Session::exists('error')){
				echo Session::flash('error');
			}else if(Session::exists('complete')){
				echo Session::flash('complete');
			}
			?>
			<div class="col-sm-6 col-md-6 col-sm-push-3">
				<?php
				if(Setting::get('enable-uploadcare') == 1):
				?>
				<div class="row">
					<ul class="nav nav-tabs">
  						<li role="presentation" id="tb" class="active"><a href="">TextBox</a></li>
 						<li role="presentation" id="mm"><a href="">Multi-Media</a></li>
					</ul>
				</div>
				<?php endif;?>
				<div class="row"><!-- Posts a status -->
					<form id="status" action="/timeline" method="post">
						<div class="form-group">
							<textarea style="resize: none;" class="form-control" name="post_status" id="post_status" rows="10" placeholder="Talk about your life here!"></textarea>
						</div>
						<div class="form-group">
							<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
							<span class="pull-right"><button id="submit" class="btn btn-sm btn-primary">Post!</button></span>
						</div>
					</form>
					<?php if(Setting::get('enable-uploadcare')):?>
					<form id="media-upload" action="/timeline" method="post">
						<input type="hidden" name="picture_link" role="uploadcare-uploader">
						<div class="form-group">
							<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
							<span class="pull-right"><button id="submit" class="btn btn-sm btn-primary">Post!</button></span>
						</div>
					</form>
					<?php endif;?>
				</div>
				<br>
				<div class="row"><!-- Posted Status -->
					<?php 
					foreach($timelineData as $timeline):
						$post_user_private = false;
						$timelineUser = new User($timeline['user_id']);
						if($timelineUser->data()->id != $user->data()->id){ //Is it someone else viewing their own post
							if($timelineUser->data()->private == 1){ // is the timeline user private?
								if(!$user->isFriends($timelineUser->data()->id)){
									$post_user_private = true;
								}
							}
						}
						if($timeline['active'] !=0){
							if(!$post_user_private){

						// if the user is the same as the topic we can display it without checking if its friends;
						// if the topic is not disabled
						?>
						<div class="well">
							<div class="page-header">
								<div class="name">
								<h1 class="name"><?php echo "<a class=\"name\" href='/u/{$timelineUser->data()->username}'>".$timelineUser->data()->username."</a>";?></h1> <?php if($timelineUser->data()->verified == 1){?><h4 class="name"><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span></h4><?php }?>
								</div>
							</div>
							<?php echo $timeline['content'];?>
							<div class="row">
								<form id="reply" action="" class="form-inline" method="post" autocomplete="off">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-btn">
										   		<?php if($like->hasLike($user->data()->id, $timeline['id']) <= 0){?>
										   		<a href="" id="like" data-token="<?php echo $token;?>" data-post="<?php echo $timeline['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a>
										   		<?php }else{?>
										   		<a href="" id="dislike" data-token="<?php echo $token;?>" data-post="<?php echo $timeline['id'];?>" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a>
										   		<?php }?>
										   </span>
										   <input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default"><span class="glyphicon glyphicon-send"></span></button>
										   </span>
										   <span class="input-group-btn">
										   		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#PostModel<?php echo $timeline['id'];?>">
													<span class="glyphicon glyphicon-list"></span>
												</button>
										   </span>
										</div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $timeline['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $timeline['id'];?>">
								</form>
							</div>
						</div>

						<!-- Model for post -->
						<div id="PostModel<?php echo $timeline['id'];?>" class="modal fade" role="dialog">
						  <div class="modal-dialog">

						    <!-- Modal content-->
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						        <h4 class="modal-title"><?php echo "<a href='/u/{$timelineUser->data()->username}'>".$timelineUser->data()->username."</a>";?> <?php if($timelineUser->data()->verified == 1){?><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span><?php }?></h4>
						        <span class="pull-right">
						        <div class="dropdown">
						        	<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="PostMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								    ...
								    <span class="caret"></span>
								  </button>
									<ul class="dropdown-menu pull-right" aria-labelledby="PostMenu">
								    <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo getSelfURL()."/p/".$timeline['hash'];?>">Tweet</a></li>
								    <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo getSelfURL()."/p/".$timeline['hash'];?>">Share on Facebook</a></li>
								    <li><a href="/p/<?php echo $timeline['hash'];?>">Post</a></li>
								  </ul>
								</div>
						        </span>
						      </div>
						      <div class="modal-body">
						        <div class="row">
						        	<div class="col-md-7"><p><?php echo $timeline['content'];?></p></div>
						        	<div class="col-md-5">
						      			<strong>Comments</strong><br>
						      			<?php if($post->getComments($timeline['id'])){
						      					foreach($post->getComments($timeline['id']) as $comment): 
						      						$commentUser = new User($comment->user_id)?>
						      				<div class="row">
							      				<ul class="media-list">
												  <li class="media">
												    <div class="media-left">
												      <a href="<?php echo "/u/{$timelineUser->data()->username}";?>">
												        <img class="media-object img-circle" src="<?php echo $commentUser->getAvatarURL('48')?>" alt="<?php echo $commentUser->data()->username;?>"> 
												      </a>
												    </div>
												    <div class="media-body">
												      <h4 class="media-heading"><a href="<?php echo "/u/{$timelineUser->data()->username}";?>"><?php echo $commentUser->data()->username;?> <?php if($timelineUser->data()->verified == 1){?><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span><?php }?></h4></a>
												      <p><?php echo $comment->content;?></p>
												    </div>
												  </li>
												</ul>
						      				</div>
						      			<?php endforeach;}?>
						        	</div>
						        </div>
						      </div>
						      <div class="modal-footer">
								<form id="reply" action="" class="form-inline" method="post" autocomplete="off">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-btn">
											   		<?php if($like->hasLike($user->data()->id, $timeline['id']) <= 0){?><a id="like" href="" id="like" class="btn btn-primary" data-token="<?php echo $token;?>" data-post="<?php echo $timeline['id'];?>"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a><?php }else{?><a href="" id="dislike" class="btn btn-primary" data-token="<?php echo $token;?>" data-post="<?php echo $timeline['id'];?>"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a><?php }?>
											   </span>
										   <input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <button type="submit" value="Post Comment" class="btn btn-default">
													<span class="glyphicon glyphicon-send"></span>
												</button>
										   </span>
										   <span class="input-group-btn">
										        <button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove-circle"></span></button>
										   </span>
										</div>
									</div>
									<input type="hidden" id="orignal_post" name="original_post" value="<?php echo $timeline['id'];?>"></input>
									<input type="hidden" id="token" name="token" value="<?php echo $token;?>">
								</form>
						      </div>
						    </div>
						  </div>
						</div>
					<?php }}else{} endforeach;?>
					<ul class="pagination">
						<?php for($i = 1; $i<=$pagination->getTotalPages(); $i++):?>
							<li><a href="?p=<?php echo $i?>"><?php echo $i;?></a></li>
						<?php endfor; ?>
					</ul>
				</div>
			</div>
			<div class="col-sm-3 col-md-3 col-sm-pull-6">
				<div class="list-group">
					<a href="/user" class="list-group-item active">
						<img src="<?php echo $user->getAvatarURL(16);?>" alt="{userimg.png}">
						<?php echo $user->data()->name;?>
					</a>
					<a href="/u/<?php echo $user->data()->username;?>" class="list-group-item">Profile</a>
					<a href="/pokes" class="list-group-item"><span class="glyphicon glyphicon-hand-right"></span> Pokes <?php if($pcount = $pokes->getPendingPokesCount($user->data()->id) >=1){?><span class="badge"><?php echo $pcount;?></span></a><?php }?>
					<a href="/user/friends/" class="list-group-item"><span class="glyphicon glyphicon-heart"></span> Friends <?php if($user->hasFriendRequest()){if(count($user->getFriendRequest()) >= 1){?><span class="badge"><?php echo count($user->getFriendRequest()); ?></span><?php }}?></a>
					<a href="/user/following/" class="list-group-item"><span class="glyphicon glyphicon-user"></span> People</a>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="list-group">
					<a href="/user/friends" class="list-group-item active">People</a>
					<?php foreach ($user->getFollowing() as $following){
						$following_user = new User($following->following_id);
						$following_user_online =($following_user->data()->last_online <= strtotime("-10 minutes"))? false: true;
					?>
					<a href="/u/<?php echo $following_user->data()->username;?>" class="list-group-item"><img src="<?php echo $following_user->getAvatarURL();?>" alt="friend_user"> <?php echo $following_user->data()->username;?> <?php if($following_user_online){echo "<span class=\"pull-right\"><span class=\"label label-success\">Online!</span></span>";}else{echo "<span class=\"pull-right\"><span class=\"label label-danger\">Offline!</span></span>";}?></a>
					<?php }?>
				</div>		
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<?php if(Setting::get('enable-uploadcare') == 1):?>
			<script src="assets/js/timeline.js"></script>
			<script src="//ucarecdn.com/widget/2.9.0/uploadcare/uploadcare.full.min.js"></script>
			<script>
				UPLOADCARE_LOCALE = "en";
				UPLOADCARE_LIVE = false;
				UPLOADCARE_PUBLIC_KEY = "<?php echo Setting::get('uploadcare-public-key');?>";
			</script>
		<?php endif;?>
	</body>
</html>