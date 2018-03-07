<?php
class User{
	private $_db,
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLogedIn = false;
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::getConfig('session/session_name'); 		// 'user'
		$this->_cookieName  = Config::getConfig('remember/cookie_name');
		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::getSession($this->_sessionName);
				if($this->find($user)){
					$this->_isLogedIn = true;
				}
				
			}
		} else{
			$this->find($user);
		}
	}
	public function create($fields = array()){
		if(!$this->_db->insert('users',$fields)){
			throw new Exception('User can not be created ');
		}
	}
	public function find($user = null){
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->select('*','users',$field,'=',$user);
			if($data->count()){
				$this->_data = $data->first();
				return true;
			}
		}	
		return false;		
	}

	public function login($username = null , $password = null, $remember = false){
		if(!$username && !$password && $this->exists()){
			Session::setSession($this->_sessionName,$this->data()->id);	
		} else{
			$user = $this->find($username);
			if($user){
				if($this->data()->password === Hash::makeHash($password,$this->data()->salt)){
					Session::setSession($this->_sessionName,$this->data()->id);
					if($remember){
						$hashCheck = $this->_db->select('*','users_session','user_id','=',$this->data()->id);
						if(!$hashCheck->count()){
							$hash = Hash::unique();
							$this->_db->insert('users_session',array(
								'user_id' => $this->data()->id,
								'hash' => $hash
							));
						} else{
							$hash = $hashCheck->first()->hash;
						}
						Cookie::put($this->_cookieName,$hash ,Config::getConfig('remember/cookie_expiry'));
					}
					return true;
				} 
				
			}	
			return false;
		}	
	}
	public function update($fields = array(),$id = null){
		if(!$id && $this->isLogedIn()){
			$id = $this->data()->id;
		}
		if(!$this->_db->update('users',$id,$fields)){
			throw new Exception("there is a problem");
		}
	}
	public function exists(){
		return (!empty($this->_data)) ? true : false;
	}
	public function logout(){
		Cookie::delete($_cookieName);
		$this->_db->delete('users_session','user_id','=',$this->data()->id);
		Session::deleteSession($this->_sessionName);
		header('location:index.php');
	}
	public function data(){
		return $this->_data;
	}
	public function isLogedIn(){
		return $this->_isLogedIn;
	}
}