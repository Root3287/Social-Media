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
if(Input::exists('get')){
	$val= new Validation();
	$validation = $val->check($_GET, array(
		//'s'=> array('required'=>true),
	));
	if($validation->passed()){
		$s = (Input::get('s') !=null)? Input::get('s'):"";
		$q = escape($s);
		$t = escape(Input::get('o'));
		$f= $db->query("SELECT * FROM `".Config::get('mysql/prefix')."users` WHERE {$t} LIKE '%{$q}%'")->results();
	}
}else{
	$f = $db->get('users', ['1','=','1'])->results();
}
$page = (Input::get('p') !=null)?Input::get('p'):1;
$limit = (Input::get('l') !=null)? Input::get('l'):10;
if($f == null){
	$f = [];
}
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
			<h1>AdminCP</h1>
			<ol class="breadcrumb">
			  <li><a href="/admin">AdminCP</a></li>
			  <li><a class="active" href="/admin/users/">Users</a></li>
			  <?php if(Input::get('o') !=null):?>
			  <li><a href="/admin/users/?o=<?php echo escape(Input::get('o'));?>"><?php echo escape(Input::get('o'));?></a></li>
			  <?php if(Input::get('s') !=null): ?>
			  	<li><a href="/admin/users/?o=<?php echo escape(Input::get('o'));?>&s=<?php echo escape(Input::get('s'));?>"><?php echo escape(Input::get('s'));?></a></li>
			  	<?php if($page !=null): ?>
			  	<li><a href="/admin/users/?o=<?php echo escape(Input::get('o'));?>&p=<?php echo $page;?>&s=<?php echo escape(Input::get('s'));?>"><?php echo $page;?></a></li>
			  <?php endif; endif; endif;?>
			</ol>
			<div class="row">
				<div class="col-md-3"><?php require 'pages/admin/sidebar.php';?></div>
				<div class="col-md-9">
					<div class="row">
					<form class="form-inline" method="get" action="">
						<div class="form-group">
							<input name="s" type="text" class="form-control input-md" placeholder="Search">
						</div>
						<div class="form-group">
							<select name="o">
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
							<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
						</div>
					</form>
					</div>
					<div class="row">
						<div class="table-responsive">
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
										<td>Private</td>
										<td>Banned</td>
										<td>Last On</td>
										<td>Last IP</td>
										<td>Verified</td>
										<td>Score</td>
										<td>Email Confirm</td>
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
											<td><?php echo $users->private?></td>
											<td><?php echo $users->banned?></td>
											<td><?php echo $users->last_online?></td>
											<td><?php echo $users->last_ip?></td>
											<td><?php echo $users->verified?></td>
											<td><?php echo $users->score?></td>
											<td><?php echo $users->confirmed?></td>
											<td><a href="/admin/user/edit/<?php echo $users->username;?>/"><span class="glyphicon glyphicon-pencil"></span></a></td>
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
												<?php echo $users->group?>
											</td>
											<td>
												<?php echo $users->joined?>
											</td>
											<td><?php echo $users->private?></td>
											<td><?php echo $users->banned?></td>
											<td><?php echo $users->last_online?></td>
											<td><?php echo $users->last_ip?></td>
											<td><?php echo $users->verified?></td>
											<td><?php echo $users->score?></td>
											<td><?php echo $users->confirmed?></td>
											<td><a class="btn btn-warning" href="/admin/user/edit/<?php echo $users->username;?>/"><span class="glyphicon glyphicon-pencil"></span></a></td>
										</tr>
										<?php endforeach;?>
									<?php endif;?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<ul class="pagination">
							<?php for($i = 1; $i<=$pagination->getTotalPages(); $i++):?>
								<li><a href="?p=<?php echo $i?>&l=<?php echo Input::get('l');?>&o=<?php echo Input::get('o');?>&s=<?php echo Input::get('s');?>"><?php echo $i;?></a></li>
							<?php endfor; ?>
						</ul>
					</div>	
				</div>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>
