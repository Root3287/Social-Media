<div class="well">
	<div class="page-header">
		<div class="name">
			<h1 class="name">
				<?php echo "<a class=\"name\" href='/u/{$timelineUser->data()->username}/'>".$timelineUser->data()->username."</a>";?>
			</h1> 
			<?php if($timelineUser->data()->verified == 1){?>
				<h4 class="name"><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span></h4>
			<?php }?>
			<span class="pull-right">
				<?php $postTimeAgo = new TimeAgo(); echo $postTimeAgo->inWords($timeline['date']);?>
			</span>
		</div>
	</div>
	
	<div class="row">
		<p id="content" style="word-wrap:break-word";><?php echo $timeline['content'];?></p>
	</div>

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
				<div class="label label-primary"><span class="<?php echo $picon; ?>"></span></div>
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
        <h4 class="modal-title">
        	<?php echo "<a href='/u/{$timelineUser->data()->username}/'>".$timelineUser->data()->username."</a>";?> 
        	<?php if($timelineUser->data()->verified == 1){?>
        		<span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span>
        	<?php }?>
        	&bull;
        	<?php $postTimeAgo = new TimeAgo(); echo $postTimeAgo->inWords($timeline['date']);?>
        </h4>
        <span class="pull-right">
	        <div class="dropdown">
	        	<button class="btn btn-xs btn-default dropdown-toggle" type="button" id="PostMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			    ...
			    <span class="caret"></span>
			  </button>
				<ul class="dropdown-menu pull-right" aria-labelledby="PostMenu">
			    <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo getSelfURL()."/p/".$timeline['hash'];?>"><?php echo $GLOBALS['language']->get('share_twitter');?></a></li>
			    <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo getSelfURL()."/p/".$timeline['hash'];?>"><?php echo $GLOBALS['language']->get('share_facebook');?></a></li>
			    <li><a href="/p/<?php echo $timeline['hash'];?>"><?php echo $GLOBALS['language']->get('post_link');?></a></li>
			  </ul>
			</div>
        </span>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-md-7"><p><?php echo $timeline['content'];?></p></div>
        	<div class="col-md-5">
      			<strong><?php echo $GLOBALS['language']->get('comments');?></strong><br>
      			<?php if($post->getComments($timeline['id'])){
      					foreach($post->getComments($timeline['id']) as $comment): 
      						$commentUser = new User($comment->user_id)?>
      				<div class="row">
	      				<ul class="media-list">
						  <li class="media">
						    <div class="media-left">
						      <a href="<?php echo "/u/{$timelineUser->data()->username}/";?>">
						        <img class="media-object img-circle" src="<?php echo $commentUser->getAvatarURL('48')?>" alt="<?php echo $commentUser->data()->username;?>"> 
						      </a>
						    </div>
						    <div class="media-body">
						      <h4 class="media-heading">
						      	<a href="<?php echo "/u/{$timelineUser->data()->username}/";?>"><?php echo $commentUser->data()->username;?> <?php if($timelineUser->data()->verified == 1){?><span class="label label-primary name"><span class="glyphicon glyphicon-ok"></span></span><?php }?>
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