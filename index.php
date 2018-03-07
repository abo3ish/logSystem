<?php
	require_once 'core/init.php';
	// if(Session::exists('success')){
	// 	echo Session::flash('success');
	// }
	// echo Session::getSession(Config::getConfig('session/session_name'));
	$user = new User();
	if($user->isLogedIn()){
		echo "hello " . $user->data()->username . "<br>";
		echo "<a href='changepassword.php'>change password </a>" . "<br>";
		echo "<a href='logout.php'>Logout</a>";
	} else{
		echo "You need to <a href='login.php'>Login</a> or <a href='register.php'>Register</a>";
	}
?>