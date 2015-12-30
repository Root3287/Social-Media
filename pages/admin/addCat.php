<?php
$user = new User();
if(!$user->isLoggedIn() && !$user->hasPermission('Admin')){
	Session::flash('error', 'You are not admin/logged in!');
	Redirect::to('/');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validation = $val->check($_POST, array(
			'title'=>array(
				'required'=>true,
			),
			'cat_par'=>array(
				'required'=>true,
			)
		));
		if($validation->passed()){
			$parent =(Input::get('cat_par')=="NULL")? null:Input::get('cat_par');
			try{
			$forums->createCat(array(
				'name'=>escape(Input::get('title')),	
				'parent'=>$parent,
			));
			Session::flash('complete', 'You added a cat!');
			Redirect::to('/admin');
			}catch (Exception $e){
				
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
			<input class="form-control" name="title" type="text" placeholder="Title">
			</div>
			<div class="form-group">
				 <select name="cat_par">
				  <option selected="selected" value="NULL">Parent</option>
				  <?php foreach ($forums->getCatParent() as $cat):?>
				  	<option value="<?php echo $cat->id;?>"><?php echo $cat->name;?></option>
				  <?php endforeach;?>
				</select> 
			</div>
			<div class="from-group">
				<input type="hidden" name="token" value="<?php echo Token::generate();?>">
				<input class="btn btn-primary" type="submit" value="Add Cat">
			</div>
		</form>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>