<?php 
$f = null;
if(Input::exists()){
	If(Token::check(Input::get('token'))){
		$val= new Validation();
		$validation = $val->check($_POST, array(
			'search'=> array('required'=>true),
		));
		if($validation->passed()){
			$q = escape(Input::get('search'));
			$t = Input::get('option');
			$f= $db->query("SELECT * FROM `users` WHERE {$t} LIKE '%{$q}%'")->results();
		}
	}
}
?>
<form class="form-inline" method="post" action="?page=user">
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
			<?php foreach($db->get('users', array('1','=','1'))->results() as $users):?>
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
			<?php foreach ($f as $users):?>
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