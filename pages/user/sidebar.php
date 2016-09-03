<div class="well">
	<strong><?php echo $GLOBALS['language']->get('usercp');?></strong><br>
	<a href="/user/notification/"><?php echo $GLOBALS['language']->get('notification');?> <?php if(Notification::getUnreadCount($user->data()->id) > 0){?><span class="badge"><?php echo Notification::getUnreadCount($user->data()->id);?></span><?php }?></a><br>
	<a href="/user/update/"><?php echo $GLOBALS['language']->get('usercp-update');?></a><br>
	<a href="/user/privacy/"><?php echo $GLOBALS['language']->get('privacy').' '.$GLOBALS['language']->get('settings');?></a><br>
	<a href="/user/friends/"><?php echo $GLOBALS['language']->get('friends');?></a><br>
	<a href="/user/following/"><?php echo $GLOBALS['language']->get('people');?></a><br>
	<a href="/user/mfa/"><?php echo $GLOBALS['language']->get('multi-factor_authication');?></a>
</div>