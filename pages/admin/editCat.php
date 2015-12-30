<?php
$forums = new Forums();
$user = new User();
if(!$user->hasPermission('Admin') || !$user->isLoggedIn()){
	Session::flash('error', 'You have to be admin/login for that!');
	Redirect::to('/');
}
if(Input::get('c') == null){
	session::flash('error', 'you don\'t have the proper link');
	Redirect::to('/admin');
}
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validation = $val->check($_POST, array(
			'title'=> array(
				'required' => true
			),
		));
		if($validation->passed()){
			$db = DB::getInstance();
			$parent = (Input::get('cat_par') == "NULL")? null:Input::get('cat_par');
			$update = $db->update('cat', Input::get('c'), array(
				'name'=>escape(Input::get('title')),
				'parent' => $parent,
			));
			if($update){
				session::flash('complete', 'You have updated the category');
				Redirect::to('/admin');
			}
		}
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
		<form method="post" action="">
			<div class="form-group">
			<input class="form-control" name="title" type="text" placeholder="Title" value="<?php echo $forums->getCat(Input::get('c'))[0]->name?>">
			</div>
			<div class="form-group">
				 <select name="cat_par">
				  <option <?php if(!$forums->getCat(Input::get('c'))[0]->parent):?>selected="selected"<?php endif;?> value="NULL">No parent</option>
				  <?php foreach ($forums->getCatParent() as $cat):?>
				  	<option <?php if($forums->getCat(Input::get('c'))[0]->parent == $cat->id):?>selected="selected"<?php endif;?> value="<?php echo $cat->id;?>"><?php echo $cat->name;?></option>
				  <?php endforeach;?>
				</select> 
			</div>
			<div class="from-group">
				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				<input class="btn btn-primary" type="submit" value="Update">
			</div>
		</form>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>
