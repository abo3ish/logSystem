<?php
class Redirect{
	public static function to($seconds,$url){
		header("refresh:$seconds;url=$url");
	}
}