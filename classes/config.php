<?php
class Config{
	public static function getConfig($path = null){
		if($path){
			$config = $GLOBALS['config'];   
			$path = explode('/',$path);  // array('mysql','host')
			
			foreach($path as $bit){  // $bit = mysql  , host
				if(isset($config[$bit])){  /* 	in first time it will be $config = $GLOBALS['config']['mysql'] 
												in second time it will be 
												$config = $GLOBALS['config']['mysql']['host'] => 127.0.0.1
											*/	
					$config = $config[$bit];
				}
			}
			return $config;
			
			
		}
	}
}