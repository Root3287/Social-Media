<?php
$user = new User();
$db = DB::getInstance();
if($user->isAdmLoggedIn()){
	if($user->data()->group != 2){
		Redirect::to('/admin/login/');
	}
}else{
	Redirect::to('/admin/login/');
}
$f = null;
if(Input::exists()){
	If(Token::check(Input::get('token'))){
		$val= new Validation();
		$validation = $val->check($_POST, array(
			'search'=> array('required'=>true),
		));
		if($validation->passed()){
			$q = escape(Input::get('search'));
			$t = escape(Input::get('option'));
			$f= $db->query("SELECT * FROM `users` WHERE {$t} LIKE '%{$q}%'")->results();
		}
	}
}else{
	$f = $db->query("SELECT * FROM `users` WHERE 1=1")->results();
}
$page = (Input::get('p') !=null)?Input::get('p'):1;
$limit = (Input::get('l') !=null)? Input::get('l'):10;

$pagination = new PaginateArray($f);
$userData = $pagination->getArrayData($limit, $page);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require 'assets/head.php';?>
	</head>
	<body>
		<?php require 'assets/nav.php';?>
		<div class="container">
			<div class="row">
				<div class="col-md-3"><?php require 'pages/admin/sidebar.php';?></div>
				<div class="col-md-9">
					<form class="form-inline" method="post" action="/admin/users/">
						<div class="form-group">
							<input name="search" type="text" class="form-control input-md" placeholder="Search">
						</div>
						<div class="form-group">
							<select name="option">
								<option value="id">
									ID
								</option>
								<option selected="selected" value="username">
									Username
								</option>
								<option value="name">
									Name
								</option>
								<option value="email">
									Email
								</option>
							</select>
						</div>
						<div class="form-group">
							<input type="hidden" name="token" value="<?php echo Token::generate()?>">
							<input class="btn btn-md btn-primary" type="submit" value="find">
						</div>
					</form>
					<table class="table">
						<thead>
							<tr>
								<td>
									ID
								</td>
								<td>
									Name
								</td>
								<td>
									Username
								</td>
								<td>
									Email
								</td>
								<td>
									Group
								</td>
								<td>
									Joined
								</td>
								<td>
									Action
								</td>
							</tr>
						</thead>
						<tbody>
							<?php if($f == null):?>
								<?php foreach($userData as $users):?>
								<tr>
									<td>
										<?php echo $users->id?>
									</td>
									<td>
										<?php echo $users->name?>
									</td>
									<td>
										<?php echo $users->username?>
									</td>
									<td>
										<?php echo $users->email?>
									</td>
									<td>
										<?php echo $users->group?>
									</td>
									<td>
										<?php echo $users->joined?>
									</td>
								</tr>
								<?php endforeach;?>
							<?php else:?>
								<?php foreach ($userData as $users):?>
								<tr>
									<td>
										<?php echo $users->id?>
									</td>
									<td>
										<?php echo $users->name?>
									</td>
									<td>
										<?php echo $users->username?>
									</td>
									<td>
										<?php echo $users->email?>
									</td>
									<td>
										<?php echo $users->joined?>
									</td>
								</tr>
								<?php endforeach;?>
							<?php endif;?>
						</tbody>
					</table>	
				</div>
				<div class="row">
					<ul class="pagination">
						<?php for($i = 1; $i<=$pagination->getTotalPages(); $i++):?>
							<li><a href="?p=<?php echo $i?>"><?php echo $i;?></a></li>
						<?php endfor; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>
