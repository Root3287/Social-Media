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
?>
<html>
	<head>
		<?php require 'assets/head.php';?>
		<script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script>	
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
				<div class="row"><!-- Posts a status -->
					<form id="status" action="/timeline" method="post">
						<div class="form-group">
							<textarea name="post" id="message" rows="1" placeholder="Talk about your life here!"></textarea>
						</div>
						<div class="form-group">
							<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
							<span class="pull-right"><button id="submit" class="btn btn-sm btn-primary">Post!</button></span>
						</div>
					</form>
				</div>
				<br>
				<div class="row"><!-- Posted Status -->
					<?php foreach($timelineData as $timeline):?>
						<div class="well">
							<div class="page-header">
								<h1><?php $timelineUser = new User($timeline['user_id']); echo "<a href='/profile/{$timelineUser->data()->username}'>".$timelineUser->data()->username."</a>";?></h1>
							</div>
							<p><?php echo $timeline['content'];?></p>
							<div class="row">
								<form id="reply" action="/action/reply" class="form-inline" method="post" autocomplete="off">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-btn">
										   		<?php if($like->hasLike($user->data()->id, $timeline['id']) <= 0){?><a href="/action/like/" id="like" class="btn btn-primary"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a><?php }else{?><a href="/action/dislike/" id="dislike" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a><?php }?>
										   </span>
										   <input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <input type="submit" value="Post Comment" class="btn btn-default" type="button">
										   </span>
										   <span class="input-group-btn">
										   		<button type="button" class="btn btn-info" data-toggle="modal" data-target="#PostModel<?php echo $timeline['id'];?>">Comments</button>
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
						        <h4 class="modal-title"><?php echo "<a href='/profile/{$timelineUser->data()->username}'>".$timelineUser->data()->username."</a>";?></h4>
						      </div>
						      <div class="modal-body">
						        <div class="row">
						        	<div class="col-md-7"><p><?php echo $timeline['content'];?></p></div>
						        	<div class="col-md-5">
						      			<strong>Comments</strong><br>
						      			<?php if($post->getComments($timeline['id'])){foreach($post->getComments($timeline['id']) as $comment): $commentUser = new User($comment->user_id)?>
						      				<div class="row">
							      				<ul class="media-list">
												  <li class="media">
												    <div class="media-left">
												      <a href="<?php echo "/profile/{$timelineUser->data()->username}";?>">
												        <img class="media-object img-circle" src="<?php echo $commentUser->getAvatarURL('48')?>" alt="<?php echo $commentUser->data()->username;?>">
												      </a>
												    </div>
												    <div class="media-body">
												      <h4 class="media-heading"><a href="<?php echo "/profile/{$timelineUser->data()->username}";?>"><?php echo $commentUser->data()->username;?></h4></a>
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
								<form id="reply" action="/action/reply/" class="form-inline" method="post" autocomplete="off">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-btn">
											   		<?php if($like->hasLike($user->data()->id, $timeline['id']) <= 0){?><a id="like" href="/action/like/&token=<?php echo $token;?>" id="like" class="btn btn-primary"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a><?php }else{?><a href="/action/dislike/" id="dislike" class="btn btn-primary"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($timeline['id'])->count();?></a><?php }?>
											   </span>
										   <input name="post" type="text" class="form-control">
										   <span class="input-group-btn">
										        <input type="submit" value="Post Comment" class="btn btn-default" type="button">
										   </span>
										   <span class="input-group-btn">
										        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
					<?php endforeach;?>
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
					<a href="/pokes" class="list-group-item"><span class="glyphicon glyphicon-hand-right"></span> Pokes <?php if($pcount = $pokes->getPendingPokesCount($user->data()->id) >=1){?><span class="badge"><?php echo $pcount;?></span></a><?php }?>
					<a href="#" class="list-group-item">{link}</a>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="list-group">
					<a href="/user/friends" class="list-group-item active">Friends</a>
					<?php foreach ($user->getFriends() as $friend){ $friend_user = new User($friend->friend_id);?>
					<a href="/profile/<?php echo $friend_user->data()->username;?>" class="list-group-item"><img src="<?php echo $friend_user->getAvatarURL();?>" alt="friend_user"> <?php echo $friend_user->data()->username;?></a>
					<?php } ?>
				</div>		
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<script>
			$(function(){
				$("#like").click(function(e){
					e.preventDefault();	
					$.post(
						"/action/like",
						{
							"token": $(this).parent().parent().parent().children("input#token").val(), 
							"post": $(this)parent().parent().parent().children("input#oringal_post").val(),
						},
						function(data){
							if(data["success"]){
								location.reload();
							}
						}, 
						"json"
					);
				});
				/*)
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
				*/
				$("#dislike").click(function(e){
					e.preventDefault();
					$.post(
						"/action/dislike/",
						{
							"token": $(this).parent().children("input#token").val(), 
							"post": $(this).parent().child("input#post").val()
						},
						function(data){
							if(data["success"] == true){
								location.reload();
							}
						}, 
						"json"
					);
				});
				$("form#status").submit(function(e){
					e.preventDefault();

					$.post("/action/status", $("form#status").serialize(), function(data){
						if(data["success"]){
							location.reload();
						}
					}, "json");
				});
				$("form#reply").submit(function(e){
					e.preventDefault();

					$.post("/action/reply", $(this).serialize(), function(data){
						if(data["success"]){
							location.reload();
						}
					}, "json");
				});
			});
		</script>
		<script type="text/javascript">
			$().ready(function(){
				CKEDITOR.replace('message', {
					removeButtons: 'Source'
				});
			});
		</script>
	</body>
</html>
