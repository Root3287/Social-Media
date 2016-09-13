<?php
$user = new User();
$u = new User($u2);
$db = DB::getInstance();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/admin/login/');
	}
}else{
	Redirect::to('/admin/login/');
}

if(!$u->exists()){
	Redirect::to(404);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<h1><?php echo $GLOBALS['language']->get('admincp')?></h1>
			<ol class="breadcrumb">
			  <li><a href="/admin"><?php echo $GLOBALS['language']->get('admincp')?>:</a></li>
			  <li><a href="/admin/users/"><?php echo $GLOBALS['language']->get('users')?></a></li>
			  <li><a href="/admin/users/?s=<?php echo $u->data()->username;?>"><?php echo $u->data()->username;?></a></li>
			  <li><a class="active" href="/admin/user/edit/<?php echo $u->data()->username;?>/">Edit</a></li>
			</ol>
			<div class="col-md-3"><?php require 'sidebar.php';?></div>
			<div class="col-md-9">
				<h1>Editing: <?php echo $u->data()->name;?></h1>
				<form action="" autocomplete="off">
					<div class="form-group"><label for="name">Name:</label><input type="text" class="form-control" value="<?php echo $u->data()->name;?>"></div>
					<div class="form-group"><label for="username">Username:</label><input type="text" class="form-control" value="<?php echo $u->data()->username;?>"></div>
					<div class="form-group"><label for="email">Email: </label><input type="email" class="form-control" value="<?php echo $u->data()->email;?>"></div>
					<div class="form-group">
						<label for="group">Group:</label>
						<select id="group" class="form-control" name="group">
						 <?php $groups = $db->get('groups', ['1','=','1'])->results(); foreach($groups as $g):?>
						<option value="<?php echo $g->id?>" <?php if($g->id == $u->data()->group):?>selected<?php endif;?>><?php echo $g->id." - ".$g->group_name;?></option>
						<?php endforeach;?>
						</select>
					</div>
				</form>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>