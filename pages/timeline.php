<?php
$user = new User();
$post = new Post();
$pokes= new Pokes();
$like = new Like();
$pagination = new PaginateArray($post->getPostForTimeline($user->data()->id));
$page = (Input::get('p') !=null)?Input::get('p'):1;
$limit = (Input::get('l') !=null)? Input::get('l'):100;
$timelineData = $pagination->getArrayData($limit, $page);
$token = Token::generate();
$ps = json_decode($user->data()->privacy_settings);
$cache_settings = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);

if(!$user->isLoggedIn()){
	Redirect::to('/');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require 'assets/head.php';?>
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
		<script src="assets/js/jQuery.browser.mobile.js" async></script>
		<script src="assets/js/jQuery.fittext.js" async></script>
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
				
				<?php if($cache_settings->isCached('enable-uploadcare') && $cache_settings->retrieve('enable-uploadcare') == 1){ ?>

				<div class="row">
					<ul class="nav nav-tabs">
  						<li role="presentation" id="tb" class="active"><a href=""><?php echo $GLOBALS['language']->get('textbox');?></a></li>
 						<li role="presentation" id="mm"><a href=""><?php echo $GLOBALS['language']->get('photo');?></a></li>
					</ul>
				</div>
				<?php }else{if(Setting::get('enable-uploadcare') == 1): ?>
				
				<div class="row">
					<ul class="nav nav-tabs">
  						<li role="presentation" id="tb" class="active"><a href=""><?php echo $GLOBALS['language']->get('textbox');?></a></li>
 						<li role="presentation" id="mm"><a href=""><?php echo $GLOBALS['language']->get('photo');?></a></li>
					</ul>
				</div>
				
				<?php endif;}?>
				
				<div class="row"><!-- Posts a status -->
					
					<form id="status" action="/timeline" method="post">
						<div class="form-group">
							<textarea style="resize: none;" class="form-control" name="post_status" id="post_status" rows="10" placeholder="<?php echo $GLOBALS['language']->get('textbox_placeholder');?>"></textarea>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select class="form-control input-sm" name="privacy" id="">
									<option value="0" <?php if($ps->display_post == 0):?>selected<?php endif;?>><?php echo $GLOBALS['language']->get('public');?></option>
									<option value="1" <?php if($ps->display_post == 1):?>selected<?php endif;?>><?php echo $GLOBALS['language']->get('followers');?></option>
									<option value="3" <?php if($ps->display_post == 3):?>selected<?php endif;?>><?php echo $GLOBALS['language']->get('friends');?></option>
									<option value="4" <?php if($ps->display_post == 4):?>selected<?php endif;?>><?php echo $GLOBALS['language']->get('private');?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
							<span class="pull-right">
								<button id="submit" class="btn btn-primary"><?php echo $GLOBALS['language']->get('post');?></button>
							</span>
						</div>
					</form>
					<?php 
					if($cache_settings->isCached('enable-uploadcare') && $cache_settings->isCached('uploadcare-tabs') && $cache_settings->isCached('uploadcare-clearable') && $cache_settings->isCached('uploadcare-image-only') && $cache_settings->isCached('uploadcare-crop') && $cache_settings->isCached('uploadcare-multiple') && $cache_settings->isCached('uploadcare-multiple-min') && $cache_settings->isCached('uploadcare-multiple-max')){

						if($cache_settings->retrieve('enable-uploadcare') == 1):?>
						
						<form id="media-upload" action="/timeline" method="post">
							<br>
							<input 
								type="hidden" 
								name="picture_link" 
								role="uploadcare-uploader"
								data-tabs="<?php echo $cache_settings->retrieve('uploadcare-tabs');?>" 
								data-clearable="<?php echo $cache_settings->retrieve('uploadcare-clearable');?>" 
								data-images-only="<?php echo $cache_settings->retrieve('uploadcare-image-only');?>"
								data-crop="<?php echo $cache_settings->retrieve('uploadcare-crop');?>"
								<?php if($cache_settings->retrieve('uploadcare-multiple') == "true") { ?>
								data-multiple="true"
								data-multiple-min="<?php echo $cache_settings->retrieve('uploadcare-multiple-min');?>"
								data-multiple-max="<?php echo $cache_settings->retrieve('uploadcare-multiple-max');?>"
								<?php }?>
							>
							<div class="form-group">
								<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
								<span class="pull-right"><button id="submit" class="btn btn-primary"><?php echo $GLOBALS['language']->get('post');?></button></span>
							</div>
						</form>

					<?php endif;

					 }else{if(Setting::get('enable-uploadcare')):?>
						
						<form id="media-upload" action="/timeline" method="post">
							<br>
							<input 
								type="hidden" 
								name="picture_link" 
								role="uploadcare-uploader"
								data-tabs="<?php echo Setting::get('uploadcare-tabs');?>" 
								data-clearable="<?php echo Setting::get('uploadcare-clearable');?>" 
								data-images-only="<?php echo Setting::get('uploadcare-image-only');?>"
								data-crop="<?php echo Setting::get('uploadcare-crop');?>"
								<?php if(Setting::get('uploadcare-multiple') == "true") { ?>
								data-multiple="true"
								data-multiple-min="<?php echo Setting::get('uploadcare-multiple-min');?>"
								data-multiple-max="<?php echo Setting::get('uploadcare-multiple-max');?>"
								<?php }?>
							>
							<div class="form-group">
								<input name="token" type="hidden" id="token" value="<?php echo $token;?>">
								<span class="pull-right"><button id="submit" class="btn btn-primary"><?php echo $GLOBALS['language']->get('post');?></button></span>
							</div>
						</form>

					<?php endif;}?>

					<div id="mobileBottom"></div>

				</div>

				<br>

				<div class="row"><!-- Posted Status -->
					
					<?php 
					foreach($timelineData as $timeline):
						$timelineUser = new User($timeline['user_id']);
						$show = true;
							
							if($timeline['active'] =0){
								$show = false;
							}
							
							$private = $timeline["privacy"];

							$picon="glyphicon glyphicon-globe"; 
							if($private == 1){$picon="glyphicon glyphicon-eye-open";}
							if($private == 2){}
							if($private == 3){$picon="glyphicon glyphicon-eye-close";}
							if($private == 4){$picon="glyphicon glyphicon-lock";}

							//Start out db if needed 
							$db = DB::getInstance();

							//Public
							if($private == 0){

							}
							//following
							else if($private == 1){
								if($user->isLoggedIn()){

									if(!$user->isFollowing($timelineUser->data()->id)){
										$show = false;
									}
									
									if(!$show){
										if($user->data()->id == $timelineUser->data()->id){
											$show = true;
										}else{
											$show = false;
										}
									}
									$mentioned = $db->get('mensions', ['post_hash', '=', $timeline['hash']]);
									
									if(!$show){
										if ($mentioned->count()) {
											foreach ($mentioned->results() as $m) {
												if($user->data()->id == $m->user_id){
													$show = true;
													break;
												}else{
													$show = false;
												}
											}
										}
									}
								}else{
									$show = false;
								}
							}
							//follwer
							else if($private == 2){
								$show = false;
							}
							//friend
							else if($private == 3){
								if($user->isLoggedIn()){

									if(!$user->isFriends($timelineUser->data()->id)){
										$show = false;
									}else{
										$show = true;
									}

									if(!$show){
										if($user->data()->id != $timelineUser->data()->id){
											$show = false;
										}else{
											$show = true;
										}
									}

									$mentioned = $db->get('mensions', ['post_hash', '=', $timeline['hash']]);
									if(!$show){
										if ($mentioned->count()) {
											foreach ($mentioned->results() as $m) {
												if($user->data()->id == $m->user_id){
													$show = true;
													break;
												}else{
													$show = false;
												}
											}
										}
									}
								}else{
									$show = false;
								}
							}
							//private
							else if($private == 4){
								if($user->isLoggedIn()){
									if(!$show){
										if($user->data()->id != $timelineUser->data()->id){
											$show = false;
										}else{
											$show = true;
										}
									}

									$mentioned = $db->get('mensions', ['post_hash', '=', $timeline['hash']]);
									
									if(!$show){
										if ($mentioned->count()) {
											foreach ($mentioned->results() as $m) {
												if($user->data()->id == $m->user_id){
													$show = true;
													break;
												}else{
													$show = false;
												}
											}
										}
									}

								}else{
									$show = false;
								}
							}

							if($show){
								require 'inc/includes/postTimelineTemplate.php';
							}
						endforeach;
					?>
					<?php // endforeach;?>
					
					<ul class="pagination">
						<?php for($i = 1; $i<=$pagination->getTotalPages(); $i++):?>
							<li><a href="?p=<?php echo $i?>&l=<?php echo $limit;?>"><?php echo $i;?></a></li>
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
					<a href="/u/<?php echo $user->data()->username;?>/" class="list-group-item">
						<?php echo $GLOBALS['language']->get('profile');?>
					</a>
					<a href="/pokes" class="list-group-item">
						<span class="glyphicon glyphicon-hand-right"></span> 
						<?php echo $GLOBALS['language']->get('pokes');?> 
						<?php if($pcount = $pokes->getPendingPokesCount($user->data()->id) >=1){?>
							<span class="badge"><?php echo $pcount;?></span>
						<?php }?>
					</a>
					<a href="/user/friends/" class="list-group-item">
						<span class="glyphicon glyphicon-heart"></span> 
						<?php echo $GLOBALS['language']->get('friends');?> 
						<?php if($user->hasFriendRequest()){if(count($user->getFriendRequest()) >= 1){?>
							<span class="badge">
								<?php echo count($user->getFriendRequest()); ?></span>
						<?php }}?>
					</a>
					<a href="/user/following/" class="list-group-item">
						<span class="glyphicon glyphicon-user"></span> 
						<?php echo $GLOBALS['language']->get('people');?>
					</a>
				</div>
			</div>
			<div class="col-sm-3 col-md-3">
				<div class="list-group">
					<a href="/user/friends" class="list-group-item active"><?php echo $GLOBALS['language']->get('people');?></a>
					<?php foreach ($user->getFollowing() as $following){
						$following_user = new User($following->following_id);
						$following_user_online=($following_user->data()->last_online <= strtotime("-10 minutes"))? false: true;
					?>
						<a href="/u/<?php echo $following_user->data()->username;?>/" class="list-group-item">
							<img src="<?php echo $following_user->getAvatarURL();?>" alt="friend_user">
							<?php echo $following_user->data()->username;?> 
							<?php if($following_user_online){?>
								<span class="pull-right"><span class="label label-success"><?php echo $GLOBALS['language']->get('online');?></span></span>
							<?php
							}else{
							?>
								<span class="pull-right"><span class="label label-danger"><?php echo $GLOBALS['language']->get('offline');?></span></span>
							<?php }?>
						</a>
					<?php }?>
				</div>
				<div id="mobileTop"></div>	
			</div>
		</div>
		<?php require 'assets/foot.php';?>
		<script src="assets/js/timeline.js"></script>
		<?php if($cache_settings->isCached('enable-uploadcare')){
			if($cache_settings->retrieve('enable-uploadcare') == 1):?>
			<script src="https://ucarecdn.com/widget/2.9.0/uploadcare/uploadcare.full.min.js"></script>
			<script>
				UPLOADCARE_LOCALE = "en";
				UPLOADCARE_LIVE = true;
				UPLOADCARE_PUBLIC_KEY = "<?php echo Setting::get('uploadcare-public-key');?>";
			</script>
		<?php endif;}else{if(Setting::get('enable-uploadcare') == 1):?>
			<script src="https://ucarecdn.com/widget/2.9.0/uploadcare/uploadcare.full.min.js"></script>
			<script>
				UPLOADCARE_LOCALE = "en";
				UPLOADCARE_LIVE = true;
				UPLOADCARE_PUBLIC_KEY = "<?php echo Setting::get('uploadcare-public-key');?>";
			</script>
		<?php endif;}?>
	</body>
</html>