<?php
require_once 'core/init.php';
$user = new User();
if(!$user->isLogedIn()){
	header('location: index.php');
}else{
	if(Input::exists()){
		$name = Input::get('name');
		$validate = new Validate();
		$validate->check($_POST,array(
			'name' => array(
				'require' => true,
				'min' 	  => 2,
				'max' 	  => 30
			)
		));
		if($validate->getPassed()){
			try{
				$update = $user->update(array(
					'name' => $name
				));
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else{
			foreach($validate->getErrors() as $error){
				echo $error ."<br>";
			}
			
		}
	}
}
?>
<form action="" method="post">
	<div class="field">
		<label for="name">name</label>
		<input type="text" id="name" name="name" value="<?php echo $user->data()->name ?>">
		<input type="submit" value="update">
	</div>
</form>
