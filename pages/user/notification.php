<?php 
$user = new User();
$db = db::getInstance();
if(Input::exists('get')){
	if(Input::get('id') !=null && Input::get('a') !=null){
		if(Input::get('a')=='read'  && Input::get('val') !=null){
			if(!$db->query('UPDATE `notification` SET `read` ='.Input::get('val').' WHERE id ='.Input::get('id'))->error()){
				Session::flash('complete', '<div class="alert-success">You marked it as read!</div>');
				Redirect::to('?page=notification');
			}else{
				session::flash('error', '<div class="alert alert-danger">There been an error marking this as read!</div>');
				Redirect::to('?page=notification');
			}
		}elseif (Input::get('a')=='delete'){
			if($db->delete('notification', array('id', '=',Input::get('id')))){
				Session::flash('complete', '<div class="alert alert-success">You deleted a message</div>');
				Redirect::to('?page=notification');
			}else{
				session::flash('error', '<div class="alert alert-danger">There been an error deleting this message!</div>');
				Redirect::to('?page=notification');
			}
		}
	}
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
			<?php
			if(Session::exists('complete')){
				echo Session::flash('complete');
			}
			?>
			<div class="col-md-3"><?php require 'pages/user/sidebar.php';?></div>
			<div class="col-md-9">
				<table class="table">
					<thead>
						<tr>
							<td>
								Message
							</td>
							<td>
								Mark as Read
							</td>
							<td>
								Delete Message
							</td>
						</tr>
					</thead>
					<tbody>
						<?php foreach (Notification::get($user->data()->id) as $message){
						echo '<tr>';
							echo '<td>';
								echo $message->message;
							echo '</td>';
							echo '<td>';
								if($message->read == 0){
									echo "<a href='?id={$message->id}&a=read&val=1'>Mark as read</a>";
								}else if($message->read == 1){
									echo "<a href='?id={$message->id}&a=read&val=0'>Mark as un-read</a>";
								 }
							echo '</td>';
							echo '<td>';
								echo "<a href='?id={$message->id}&a=delete'>delete</a>";
							echo '</td>';
						echo '</tr>';
						}?>
					</tbody>
				</table>		
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>