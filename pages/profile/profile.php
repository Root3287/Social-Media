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
		<?php if($user2->data()->banner){?>
		.jumbotron{
			background-image: url(<?php echo $user2->data()->banner;?>);
			background-size: cover;
		}
		<?php }?>
		</style>
		<script src="/assets/js/follow.js"></script>
		<script src="/assets/js/unfollow.js"></script>
		<script src="/assets/js/reply.js"></script>
		<script src="/assets/js/like.js"></script>
		<script src="/assets/js/dislike.js"></script>
		<script src="/assets/js/status.js"></script>
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
								if($user->isLoggedIn() && $user->data()->id !== $user2->data()->id){
								if(!$user->isFollowing($user2->data()->id)):?>
									<button id="follow" class="btn btn-primary btn-md" data-user="<?php echo $user2->data()->id;?>" data-token="<?php echo $token;?>"><?php echo $GLOBALS['language']->get('follow');?></button>
								<?php else:?>
									<button id="unfollow" class="btn btn-primary btn-md" data-user="<?php echo $user2->data()->id;?>" data-token="<?php echo $token;?>"><?php echo $GLOBALS['language']->get('unfollow');?></button>
								<?php endif;}?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<?php echo $GLOBALS['language']->get('more-information');?>
						</div>
						<div class="panel-body">
								<h4><u><?php echo $GLOBALS['language']->get('bio');?></u></h4>
								<?php echo escape($user2->data()->bio);?>
								<h4><u><?php echo $GLOBALS['language']->get('followers');?></u></h4>
								<?php echo count($user2->getFollowers());?>
								<h4><u><?php echo $GLOBALS['language']->get('followings');?></u></h4>
								<?php echo count($user2->getFollowing());?>
							<?php if($user2->data()->private == 0 || $user->isFriends($user2->data()->id)):?>
								<h4><u><?php echo $GLOBALS['language']->get('email');?></u></h4>
								<p><?php echo $user2->data()->email?></p>
								<h4><u><?php echo $GLOBALS['language']->get('joined-date');?></u></h4>
								<p><?php echo $user2->data()->joined?></p>
								<h4><u><?php echo $GLOBALS['language']->get('number-post');?></u></h4>
								<p><?php echo $post->getPostByUser($user2->data()->id)->count();?></p>
								<h4><u><?php echo $GLOBALS['language']->get('score');?></u></h4>
								<p><?php echo $user2->data()->score;?></p>
							<?php else:?>
							<h3><?php echo $GLOBALS['language']->get('private-profile');?></h3>
							<?php endif;?>
						</div>
					</div>
					<?php if($user->isLoggedIn() && $user->data()->id !== $user2->data()->id):?>
					<div class="panel panel-primary">
						<div class="panel-heading"><?php echo $GLOBALS['language']->get('function');?></div>
						<div class="panel-body">
							<?php if($poke->hasNoPokesPending($user->data()->id, $user2->data()->id)){?><a href="/pokes?token=<?php echo $token;?>&user2=<?php echo $user2->data()->id;?>"><?php echo $GLOBALS['language']->get('poke');?></a><?php }?><br>
							<a href="/report/u/<?php echo $user2->data()->id;?>"><?php echo $GLOBALS['language']->get('report-user');?></a>
						</div>
					</div>
				<?php endif;?>
				</div>
				<div class="col-md-9">
					<h1><?php echo $GLOBALS['language']->get('timeline');?></h1>
					<?php if($user->isLoggedIn() && $user->data()->id == $user2->data()->id){?>
					<div class="row">
						<form id="status" action="" method="post">
							<div class="form-group">
								<textarea style="resize: none;" class="form-control" name="post_status" id="post_status" rows="10" placeholder="<?php echo $GLOBALS['language']->get('textbox_placeholder');?>"></textarea>
							</div>
							<div class="form-group">
								<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
								<span class="pull-right">
									<button id="submit" class="btn btn-primary"><?php echo $GLOBALS['language']->get('post');?></button>
								</span>
							</div>
						</form>
					</div>
					<br>
					<?php }?>
					<div class="row">
					<?php
						foreach($postData as $uPost):
							$userPost = new User($uPost['user_id']);
							if($uPost['active'] !=0){
							
								$private = $uPost["privacy"];

								$picon="glyphicon glyphicon-globe"; 
								if($private == 1){$picon="glyphicon glyphicon-eye-open";}
								if($private == 2){}
								if($private == 3){$picon="glyphicon glyphicon-eye-close";}
								if($private == 4){$picon="glyphicon glyphicon-lock";}

								//Check if were mentioned
								$db = DB::getInstance();
								$mentioned = $db->get('mensions', ['post_hash', '=', $uPost['hash']]);
								$show_post = true;

								if($private != 0){ // if its not public;
									if($user->data()->id != $userPost->data()->id){ // if we are not the owner of the file
												
										if($private == 4){ // if private is 4
											if($mentioned->count() > 0){ // Check if there is any mentions
												if($user->data()->id != $mentioned->results()[0]->user_id){ // we are not part of it
													$show_post = false;
												}
											}else{
												$show_post = false;
											}
										}
										if($private == 1 && !$user->isFollowing($userPost->data()->id)){ //IF the privacy is 1 and if we're not following; Dont display
											if($mentioned->count() > 0){ // Check if there is any mentions
												if($user->data()->id != $mentioned->results()[0]->user_id){ // we are not part of it
													$show_post = false;
												}
											}else{
												$show_post = false;
											}
										}
										if($private == 2){
											if($mentioned->count() > 0){ // Check if there is any mentions
												if($user->data()->id != $mentioned->results()[0]->user_id){ // we are not part of it
													$show_post = false;
												}
											}else{
												$show_post = false;
											}
										}
										if($private == 3 && !$user->isFriends($userPost->data()->id)){ //if privacy is 3 and if we're not friends; dont display
											if($mentioned->count() > 0){ // Check if there is any mentions
												if($user->data()->id != $mentioned->results()[0]->user_id){ // we are not part of it
													$show_post = false;
												}
											}else{
												$show_post = false;
											}
										}
									}// Display the post anyway

								}// Display the post anyways

								if($show_post){
					?>
							<div class="well">
								<div class="page-header">
									<div class="name">
										<h1 class="name">
											<?php echo "<a class=\"name\" href='/u/{$userPost->data()->username}/'>".$userPost->data()->username."</a>";?>
										</h1> 
										<?php if($userPost->data()->verified == 1){?>
											<h4 class="name"><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span></h4>
										<?php }?>
										<span class="pull-right">
											<?php $postTimeAgo = new TimeAgo(); echo $postTimeAgo->inWords($uPost['date']);?>
										</span>
									</div>
								</div>
						
								<div class="row">
									<p id="content" style="word-wrap:break-word";><?php echo $uPost['content'];?></p>
									<?php if($post->getComments($uPost['id'])){ echo "<hr>"; foreach($post->getComments($uPost['id']) as $comment): $commentUser = new User($comment->user_id)?>
				      				<div class="row">
					      				<ul class="media-list">
										  <li class="media">
										    <div class="media-left">
										      <a href="<?php echo "/u/{$userPost->data()->username}/";?>">
										        <img class="media-object img-circle" src="<?php echo $commentUser->getAvatarURL('48')?>" alt="<?php echo $commentUser->data()->username;?>"> 
										      </a>
										    </div>
										    <div class="media-body">
										      <h4 class="media-heading">
										      	<a href="<?php echo "/u/{$userPost->data()->username}/";?>"><?php echo $commentUser->data()->username;?> <?php if($userPost->data()->verified == 1){?><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span><?php }?>
										      	</a>
										      </h4>
										      <p><?php echo $comment->content;?></p>
										    </div>
										  </li>
										</ul>
				      				</div>
					      			<?php endforeach;}?>
								</div>

							<div class="row">
								<?php if($user->isLoggedIn()){?>
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
										<div class="label label-primary"><span class="<?php echo $picon; ?>"></span></div>
									</div>
									<input type="hidden" name="original_post" value="<?php echo $uPost['id'];?>"></input>
									<input type="hidden" name="token" value="<?php echo $token;?>">
									<input id="orignal_post" type="hidden" value="<?php echo $uPost['id'];?>">
								</form>
								<?php }?>
							</div>

							<!-- Model for post -->
							<div id="PostModel<?php echo $uPost['id'];?>" class="modal fade" role="dialog">
							  <div class="modal-dialog">

							    <!-- Modal content-->
							    <div class="modal-content">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal">&times;</button>
							        <h4 class="modal-title">
							        	<?php echo "<a href='/u/{$userPost->data()->username}/'>".$userPost->data()->username."</a>";?> 
							        	<?php if($userPost->data()->verified == 1){?>
							        		<span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span>
							        	<?php }?>
							        	&bull;
							        	<?php $postTimeAgo = new TimeAgo(); echo $postTimeAgo->inWords($uPost['date']);?>
							        </h4>
							        <span class="pull-right">
								        <div class="dropdown">
								        	<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="PostMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										    ...
										    <span class="caret"></span>
										  </button>
											<ul class="dropdown-menu pull-right" aria-labelledby="PostMenu">
										    <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo getSelfURL()."/p/".$uPost['hash'];?>"><?php echo $GLOBALS['language']->get('share_twitter');?></a></li>
										    <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo getSelfURL()."/p/".$uPost['hash'];?>"><?php echo $GLOBALS['language']->get('share_facebook');?></a></li>
										    <li><a href="/p/<?php echo $uPost['hash'];?>"><?php echo $GLOBALS['language']->get('post_link');?></a></li>
										  </ul>
										</div>
							        </span>
							      </div>
							      <div class="modal-body">
							        <div class="row">
							        	<div class="col-md-7"><p><?php echo $uPost['content'];?></p></div>
							        	<div class="col-md-5">
							      			<strong><?php echo $GLOBALS['language']->get('comments');?></strong><br>
							      			<?php if($post->getComments($uPost['id'])){
							      					foreach($post->getComments($uPost['id']) as $comment): 
							      						$commentUser = new User($comment->user_id)?>
							      				<div class="row">
								      				<ul class="media-list">
													  <li class="media">
													    <div class="media-left">
													      <a href="<?php echo "/u/{$userPost->data()->username}/";?>">
													        <img class="media-object img-circle" src="<?php echo $commentUser->getAvatarURL('48')?>" alt="<?php echo $commentUser->data()->username;?>"> 
													      </a>
													    </div>
													    <div class="media-body">
													      <h4 class="media-heading">
													      	<a href="<?php echo "/u/{$userPost->data()->username}/";?>"><?php echo $commentUser->data()->username;?> <?php if($userPost->data()->verified == 1){?><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span><?php }?>
													      	</a>
													      </h4>
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
												   		<?php if($like->hasLike($user->data()->id, $uPost['id']) <= 0){?><a id="like" href="" id="like" class="btn btn-primary" data-token="<?php echo $token;?>" data-post="<?php echo $uPost['id'];?>"><span class="glyphicon glyphicon-star-empty"></span> <?php echo $like->getLikesByPost($uPost['id'])->count();?></a><?php }else{?><a href="" id="dislike" class="btn btn-primary" data-token="<?php echo $token;?>" data-post="<?php echo $uPost['id'];?>"><span class="glyphicon glyphicon-star"></span> <?php echo $like->getLikesByPost($uPost['id'])->count();?></a><?php }?>
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
										<input type="hidden" id="orignal_post" name="original_post" value="<?php echo $uPost['id'];?>"></input>
										<input type="hidden" id="token" name="token" value="<?php echo $token;?>">
									</form>
							      </div>
							    </div>
							  </div>
							</div>
						</div>
					<?php
							}}
						endforeach;
					?>
					</div>
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