<?php
$user = new User();
Session::put(''.Config::get('session/session_name'), 1);
//Redirect::to(404);