<?php
$user = new User();
$user->admLogout();
$user->logout();
Redirect::to('/');
