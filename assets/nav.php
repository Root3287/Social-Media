<?php 
$user = new User();
$cache_settings = new Cache(['name'=>'settings', 'path'=>'cache/', 'extension'=>'.cache']);
?>
<nav id="top" class="navbar navbar-default <?php if($cache_settings->isCached('inverted-nav')){ if($cache_settings->retrieve('inverted-nav') == 1){echo 'navbar-inverse';} }else{ if(Setting::get('inverted-nav') == 1){ echo 'navbar-inverse';}}?>">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/"><?php if($cache_settings->isCached('title')){echo $cache_settings->retrieve('title');}else{echo Setting::get('title');}?> <span class="label label-danger">Alpha</span></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <li><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
      <?php if($user->isLoggedIn()):?><li><a href="/search"><span class="glyphicon glyphicon-search"></span></a></li><?php endif;?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <!-- Make mail -->
        <?php if($user->isLoggedIn()){?>
        <li>
          <a href="/user/notification/">
            <span class="glyphicon glyphicon-inbox"></span>
            <?php if(Notification::getUnreadCount($user->data()->id) > 0){?>
              <span class="badge"><?php echo Notification::getUnreadCount($user->data()->id);?></span>
            <?php }?>
          </a>
        </li>
        <?php }?>
        <!-- <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-inbox"><span class="badge">999</span></span></a>
        <ul class="dropdown-menu">
        <li><a href="#">Action</a></li>
        <li><a href="#">Another action</a></li>
        <li><a href="#">Something else here</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="#">Separated link</a></li>
        </ul>
        </li>-->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <?php 
            if($user->isLoggedIn()){
              echo "<img src='".$user->getAvatarURL(16)."' alt='{Avatar}'> ".$user->data()->name;
            }else{
              echo 'Guest';
            }
            ?><span class="caret"></span>
          </a>
          <?php if(!$user->isLoggedIn()){?>
          <ul class="dropdown-menu">
            <li><a href="/login"><?php echo $GLOBALS['language']->get('login');?></a></li>
            <li><a href="/register"><?php echo $GLOBALS['language']->get('register');?></a></li>
          </ul>
          <?php }else{?>
          <ul class="dropdown-menu">
            <li><a href="/u/<?php echo $user->data()->username;?>/"><?php echo $GLOBALS['language']->get('profile');?></a></li>
            <li><a href="/user"><?php echo $GLOBALS['language']->get('usercp');?></a></li>
            <?php 
            if($user->hasPermission("Admin")){
            ?>
            <li><a href="/admin"><?php echo $GLOBALS['language']->get('admincp');?></a></li>
            <?php 
            }
            ?>
            <li role="separator" class="divider"></li>
            <li><a href="/logout"><?php echo $GLOBALS['language']->get('logout');?></a></li>
          </ul>
        <?php }?>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
