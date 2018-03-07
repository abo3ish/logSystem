<?php
class Session{
	public static function exists($name){
		return (isset($_SESSION[$name])) ? true : false; 
	}
	public static function setSession($name,$value){
		return $_SESSION[$name] = $value;
	}
	public static function getSession($name){
		return $_SESSION[$name];
	}
	public static function deleteSession($name){
		if(self::exists($name)){
			unset($_SESSION[$name]);
		}
	}

	public static function flash($name,$string=''){
		if(self::exists($name)){
			$session = $_SESSION[$name];
			self::deleteSession($name);
			return $session;
		} else{
			self::setSession($name,$string);
		}
	}
}