<?php
session_start();
$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => '127.0.0.1',
		'username'  => 'root',
		'password'  => '',
		'dbname'	=> 'registerSys'
	),
	'remember' => array(
		'cookie_name' 	=> 'hash',
		'cookie_expiry' => 100
	),
	'session' => array(
		'session_name' => 'user'
	)
);
spl_autoload_register(function ($class){
	require_once 'classes/' . $class . '.php'; 
});
require_once 'functions/sanitize.php';
if(Cookie::exists(Config::getConfig('remember/cookie_name')) && !Session::exists(Config::getConfig('session/session_name'))){
	$hash = Cookie::get(Config::getConfig('remember/cookie_name'));
	$hashCheck = DB::getInstance()->select('*','users_session','hash','=',$hash);
	if($hashCheck->count()){
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}