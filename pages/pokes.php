<?php
$user = new User();
$pokes = new Pokes();
if(!$user->isLoggedIn()){
	Redirect::to(404);
}
if(Input::exists('get')){
	if(Token::check(Input::get('token'))){ 
		try{
			$pokes->poke($user->data()->id, escape(Input::get('user2')));
			Session::flash('complete', "<div class=\"alert alert-success\">".$GLOBALS['language']->get('alert-poke-success')."</div>");
			Redirect::to('/pokes');
		}catch(Exception $e){
			Session::flash('error', "<div class=\"alert alert-danger\">".$GLOBALS['language']->get('alert-poke-error')."</div>");
			Redirect::to('/pokes');
		}
	}else{
		Redirect::to('/pokes');
	}
}
$token = Token::generate();
?>
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
			if(Session::exists('error')){
				echo Session::flash('error');
			}
			?>
			<div class="row">
				<h1><?php echo $GLOBALS['language']->get('pokes');?></h1>
				<?php 
				foreach ($pokes->getPendingPokes($user->data()->id) as $ppoke) {
				$user2 = new User($ppoke->user1);
				?>
				<div class="user">
					<div class="media">
						<div class="media-left"><a href="?user="><img src="<?php echo $user->getAvatarURL(64);?>" alt="" class="media-object"></a></div>
						<div class="media-body">
							<h4 class="media-heading"><?php echo $user2->data()->name;?></h4>
							<p class="media-heading"><?php echo $GLOBALS['language']->get('pokes-p1').' '.$pokes->getCount($user->data()->id,$user2->data()->id).' '.$GLOBALS['language']->get('pokes-p2');?><a class="btn btn-sm btn-primary" href="?token=<?php echo $token;?>&user2=<?php echo $user2->data()->id?>"><?php echo $GLOBALS['language']->get('pokes');?></a></p>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		<?php require 'assets/foot.php';?>
	</body>
</html>