<?php 
$db = db::getInstance();
if(Input::exists('get')){
	if(Input::get('id') !=null && Input::get('a') !=null){
		if(Input::get('a')=='read'  && Input::get('val') !=null){
			if(!$db->query('UPDATE `notification` SET `read` ='.Input::get('val').' WHERE id ='.Input::get('id'))->error()){
				Session::flash('complete', 'You marked it as read!');
				Redirect::to('?page=notification');
			}else{
				session::flash('error', 'there been an error marking this as read!');
				Redirect::to('?page=notification');
			}
		}elseif (Input::get('a')=='delete'){
			if($db->delete('notification', array('id', '=',Input::get('id')))){
				Session::flash('complete', 'You deleted a message');
				Redirect::to('?page=notification');
			}else{
				session::flash('error', 'there been an error deleting this message!');
				Redirect::to('?page=notification');
			}
		}
	}
}
?>
<table class="table">
	<thead>
		<tr>
			<td>
				ID
			</td>
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
		<?php foreach (Notifaction::get($user->data()->id) as $message){
		echo '<tr>';
			echo '<td>';
				echo $message->id;
			echo '</td>';
			echo '<td>';
				echo $message->message;
			echo '</td>';
			echo '<td>';
				if($message->read == 0){
				echo "<a href='?page=notification&id={$message->id}&a=read&val=1'>Mark as read</a>";
				}else if($message->read == 1){
				echo "<a href='?page=notification&id={$message->id}&a=read&val=0'>Mark as un-read</a>";
				 }
			echo '</td>';
			echo '<td>';
				echo "<a href='?page=notification&id={$message->id}&a=delete'>delete</a>";
			echo '</td>';
		echo '</tr>';
		}?>
	</tbody>
</table>