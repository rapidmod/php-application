<?php
namespace Rapidmod;
class Session {
	private static $_seskey = "RCORE";
	public static function _get($key){
		self::init();
		if(isset($_SESSION[self::$_seskey][$key])){
			return $_SESSION[self::$_seskey][$key];
		}
		return false;
	}

	public static function _set($key,$value){
		if(empty($key) || strtolower($key) === "user"){
			return;
		}
		self::init();
		return $_SESSION[self::$_seskey][$key] = $value;
	}

	public static function init(){
		if(isset($_SESSION) && isset($_SESSION[self::$_seskey])){return;}
		if(!isset($_SESSION)){ session_start();}
		if(!isset($_SESSION[self::$_seskey])){$_SESSION[self::$_seskey] = array();}
		return;
	}

	public static function toArray(){
		self::init();
		return $_SESSION[self::$_seskey];
	}

	public static function id(){
		self::init();
		return session_id();
	}

	public static function user(){
		self::init();
		if( !isset($_SESSION[self::$_seskey]["User"]) || is_array($_SESSION[self::$_seskey]["User"])){
			$_SESSION[self::$_seskey]["User"] = new \stdClass();
		}
		return self::_get("User");
	}

	public static function userLogout(){
		$_SESSION[self::$_seskey]["User"] = new \stdClass();
	}
}
?>