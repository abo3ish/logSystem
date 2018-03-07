<?php
require_once 'core/init.php';
if(Input::exists()){
	$validate = new validate();
	$validation = $validate->check($_POST,array(
		'username' => array(
			'require' => true
		),
		'password' => array(
			'require' => true
		)
	));
	if($validation->getPassed()){
		$user = new User();
		$remember = (Input::get('remember') === 'on') ? true : false;
		$login = $user->login(Input::get('username') , Input::get('password'),$remember);
		if($login){
			// echo Session::getSession('session_name');
			header('location:index.php');
		} else{
			echo "faild";
		}

	} else{
		foreach($validation->getErrors() as $error){
			echo $error ."<br>";
		}
	}
}
?>
<form action="" method="post">
	<div class="field">
		<label for='username'>Username</label>
		<input type="text" id='username' name='username'>
	</div>
	<div class="field">
		<label for='password'>Password</label>
		<input type="password" id='password' name='password'>
	</div>
	<div class="field">
		<input type="checkbox" id='remember' name='remember'>
		<label for='remember'>remember Me</label>
	</div>

	<input type="submit" value="login">
</form>	