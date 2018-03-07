<?php
require_once 'core/init.php';
$user = new User();
if(!$user->isLogedIn()){
	header('location:index.php');
}
if(Input::exists()){
	$validate = new Validate();
	$validation = $validate->check($_POST,array(
		'current_password' => array(
			'require' => true,
			'min' => 2
		),
		'new_password' => array(
			'require' => true,
			'min' => 2
		),
		'password_again' => array(
			'require' => true,
			'min' => 2,
			'matches' => 'new_password'
		)
	));
	if($validation->getPassed()){
		$current_password = Input::get('current_password');
		$new_password 	  = Input::get('new_password');
		if(Hash::makeHash($current_password,$user->data()->salt) === $user->data()->password){
			$salt = Hash::salt(32);
			try{
				$update = $user->update(array(
					'password' => Hash::makeHash($new_password,$salt),
					'salt'	=> $salt
				));
				echo "your password has been updated";
				header("refresh:2; url=index.php");
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else{
			echo "Password is wrong";
		}


	} else{
		foreach($validate->getErrors() as $error){
				echo $error ."<br>";
			}
	}
}

?>
<form action="" method="post">
	<div class="field">
		<label for="name">Current Password</label>
		<input type="text" id="current_password" name="current_password">
	</div>
	<div class="field">
		<label for="name">New Password</label>
		<input type="text" id="new_password" name="new_password">
	</div>
	<div class="field">
		<label for="name">Password Again</label>
		<input type="text" id="password_again" name="password_again">
	</div>
	<input type="submit" value="update">
</form>