<?php
$user = new User();
$db = DB::getInstance();
$q = null;
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			"search"=>[
				'required'=>true,
			],
			'table'=>[
				'required'=>true,
			],
		]);
	}
}
?>
<html>
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="row">
				<form class="form-inline" method="post" action="">
					<div class="form-group">
						<lable for="search">
							<input class="form-control input-md" id="search" name="search" type="text" value="<?php echo Input::get('search');?>">
						</lable>
					</div>
					<div class="form-group">
						<select class="form-control" name="table">
						  <option value="">Groups</option>
						  <option <?php if(Input::get('table') == "users"):?>selected="selected"<?php endif;?>value="users">Users</option>
						  <option value="friends">Friends</option>
						  <option value="following">Following</option>
						</select>
					</div>
					<div class="form-group">
						<input name="token" value="<?php echo Token::generate();?>" type="hidden">
						<input type="submit" value="Search" class="btn btn-md btn-primary">
					</div>
				</form>
			</div>
			<div class="row">
				<h1>Search</h1>
				<?php
				if(Input::exists()){
					if($validate->passed()){
						if(escape(Input::get('table')) == "friends"){
							$q = $db->query('SELECT * FROM friends WHERE accepted=1 AND user_id='.$user->data()->id.' OR friend_id='.$user->data()->id.' AND user_id LIKE %'.escape(Input::get('search')).'% OR friend_id LIKE %'.escape(Input::get('search')).'%');
						}
						if(escape(Input::get('table')) == "users"){
							$q = $db->query("SELECT * FROM users WHERE username LIKE '%".escape(Input::get('search'))."%'");
							if($q->results()){
								foreach ($q->results() as $result) {
									$searchUser = new User($result->id);
									echo "<div class='col-md-3'><img class='img-circle img-responsive' src=". $searchUser->getAvatarURL('96')."><h3><a href='/profile/".$searchUser->data()->username."'>".$searchUser->data()->username."</a></h3></div>";
								}
							}
						}
						if(escape(Input::get('table')) == "following"){
							$q=$db->query("SELECT * FROM following WHERE user_id = ".$user->data()->id." AND following_id LIKE `%".escape(Input::get('search'))."%`");
						}
					}				
				}
				?>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>