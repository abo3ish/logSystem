<?php
	require_once "core/init.php";

	if(Input::exists()){
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'require' => true,
				'min' 	  => 1,
				'max' 	  => 20,
				'unique'  => 'users'
			),
			'password' => array(
				'require' => true,
				'min' 	  => 1,
			),
			'password_again' => array(
				'require' => true,
				'matches' => 'password'
			),
			'name' => array(
				'require' => true,
				'min' 	  => 1,
				'max' 	  => 20,
			)
		));
		if($validate->getPassed()){
			$user = new User();
			$salt = Hash::salt(32);
			try{
				$user->create(array(
					'username' => Input::get('username'),
					'salt' 	   => $salt,
					'password' => Hash::makeHash(Input::get('password'),$salt),
					'name'     => Input::get('name'),
					'join'     => date('Y:m:d H:i:s'),
					'group'    => 1

				));
				echo "registered";
				header("refresh:2; url=login.php");

			} catch(Exception $e){
				die($e->getMessage());
			}


			// Session::flash('success','Register successfully ');
			// header('location: index.php');
		} else{
			foreach($validate->getErrors() as $error){
				echo $error . "<br>";
			}
		}
	}
?>

<form action="" method="post">
	<div class="fieled">
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo Input::get('username') ?>">
	</div>
	<div class="fieled">
		<label for="password">Password</label>
		<input type="password" name="password" id="password">
	</div>
	<div class="fieled">
		<label for="password_again">Password Again</label>
		<input type="password" name="password_again" id="password_again">
	</div>
	<div class="fieled">
		<label for="name">Name</label>
		<input type="text" name="name" id="name" value="<?php echo Input::get('name') ?>">
	</div>
	<input type="submit" value="Register">
</form>	