<div class="well">
	<strong>UserCP</strong><br>
	<a href="/user/notification/">Notification <?php if(Notification::getUnreadCount($user->data()->id) > 0){?><span class="badge"><?php echo Notification::getUnreadCount($user->data()->id);?></span><?php }?></a><br>
	<a href="/user/profile/">Profile</a><br>
	<a href="/user/update/">Update</a>
</div>