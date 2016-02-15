<?php
$user = new User();
$post = new Post();
$pokes= new Pokes();
$pagination = new PaginateArray($post->getPostForTimeline($user->data()->id));
$page = (Input::get('p') !=null)?Input::get('p'):1;
$limit = (Input::get('l') !=null)? Input::get('l'):10;
$timelineData = $pagination->getArrayData($limit, $page);
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			'post'=>[
				'required'=>true,
			],
		]);
		if($validate->passed()){
			try{
				$post->create(Input::get('post'),$user);
				Session::flash('success', '<div class="alert alert-success">You created your post</div>');
				Redirect::to('/');
			}catch(Exception $e){
				Session::flash('error', '<div class="alert alert-danger">There was an error making a post!</div>');
				Redirect::to('/');
			}
		}
	}
}
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
					<form action="/" method="post">
						<div class="form-group">
							<textarea name="post" id="message" rows="1" placeholder="Talk about your life here!"></textarea>
						</div>
						<div class="form-group">
							<input name="token" type="hidden" id="token" value="<?php echo Token::generate();?>">
							<span class="pull-right"><button id="submit" class="btn btn-sm btn-primary">Post!</button></span>
						</div>
					</form>
				</div>
				<br>
				<div class="row"><!-- Other people status -->
					<?php foreach($timelineData as $timeline):?>
						<div class="well">
							<div class="page-header">
								<h1><?php $timelineUser = new User($timeline['user_id']); echo "<a href='/profile/{$timelineUser->data()->username}'>".$timelineUser->data()->username."</a>";?></h1>
							</div>
							<p><?php echo $timeline['content'];?></p>
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
		<script type="text/javascript">
			$().ready(function(){
				CKEDITOR.replace('message', {
					removeButtons: 'Source'
				});
			});
		</script>
	</body>
</html>