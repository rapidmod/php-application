<?php
/**
 * @author RapidMod.com
 * @author 813.330.0522
 */


namespace Rapidmod;


class Redirect {
	public static function toUrl($uri,$perm = true){
		if(!empty($uri)){
			if($perm){
				$update = true;
				$code = 301;
			} else {
				$update = false;
				$code = 302;
			}
			header("location: ".$uri,$update,$code);
			exit;
		}
	}

	/**
	 * * @param $name
	 * ${name}
	 * @return mixed
	 *
	 * @author RapidMod.com
	 * @author 813.330.0522
	 *
	 *  the thought behind removing the params from this function is that they
	 *  should already be set in the request and we are not tampering with the o
	 * 	original request, although it shouldnt be terribly difficult to manipulate
	 * 	the data accordingly inside of the controllers;
	 */
	public function toController($name){
		try{
			$name = "Controller".$name;
			$controller = new $name();
			return $controller->execute();
		}catch(Exception $e){
			die("WTF NO CONTROLLER");
		}
	}
}