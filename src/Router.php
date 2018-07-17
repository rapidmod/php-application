<?php
namespace Rapidmod;
use \Rapidmod\Application;
use \Rapidmod\Params;

/**
 * Class Router
 * @package Rapidmod
 *
 * @author RapidMod.com
 * @author 813.330.0522
 * @TODO: Refer to router 0.0.3 for additional routing options
 */

class Router Extends \Rapidmod\Data\Model {

	const VERSION = "0.0.4";

	/**
	 *
	 * Name route
	 * @return bool|string
	 * @static static
	 * @throws
	 *
	 * @author RapidMod.com
	 * @author 813.330.0522
	 *
	 *
	 */
	public static function route(){
		return self::_routeFromUri( Application::$url );
	}

	/**
	 * *
	 * @param $uri
	 * @return string|bool
	 *
	 * @author RapidMod.com
	 * @author 813.330.0522
	 *
	 *
	 * The maximium controller / uri parts that will be processed is 15.
	 * That means you can nest controllers up to 15 levels deep
	 */
	private static function _routeFromUri($uri){

		if(strstr($uri,"?")){ $uri = explode("?",$uri); $uri = $uri[0];}
		$uri = trim( $uri, "/ ");

		Application::config()->_set("request_path_variables",$uri);
		if(stristr($uri,"/")){ $url_parts = explode("/",$uri); }
		else{ $url_parts = array($uri);}

		Params::$urlParts = $url_parts;

		if(empty($url_parts) || empty($url_parts[0])){
			Application::$controller = "\\Contoller\\Index";
			Application::$controller_path = "default";
		}

		$ds = DIRECTORY_SEPARATOR;
		$directories = Application::include_directories();
		$redirectUrl = "";

		if(!empty($directories)){
			$i = 0;
			$parts= array("Controller");
			if(!empty($url_parts)){
				$subPath = "Controller{$ds}";
				if(!empty(Application::$frontName)){
					foreach (Application::$frontName as $i => $k){
						if($url_parts[$i] === Application::$frontName[$i]){
							$redirectUrl .= "/{$k}";
							unset($url_parts[$i]);
						}
					}
				}

				foreach($url_parts as $index => $part) {
					$redirectUrl .= "/{$part}";
					$slug = ucfirst( preg_replace( '/\PL/u', '', strtolower( $part ) ) );
					$subPath .= $slug;
					$i++;
					if( $i < 15){
						$result = false;
						foreach ($directories as $dir){
							if($result){continue;}
							$path = $dir.$subPath;
							clearstatcache();
							if(file_exists($path.".php")){
								Application::$controller_path = "{$path}.php";
								$result = true;
							}elseif(file_exists($path.$ds."Index.php")){
								Application::$controller_path = $path.$ds."Index.php";
								$result = true;
							}elseif (file_exists($path)){
								unset($url_parts[$index]);
								$parts[] = $slug;
							}
							if($result){
								unset($url_parts[$index]);
								$parts[] = $slug;
								self::_setControllerName($parts);
							}
						}
						$subPath .= $ds;
					}
				}
			}
		}
		if(!stristr($redirectUrl,"login") && !stristr($redirectUrl,"logout")){
			Application::$redirect_uri = $redirectUrl;
		}else{
			Application::$redirect_uri = "";
		}
		if(!empty($url_parts)){Params::$urlParts = array_merge( $url_parts ); //array merge to re organize the key indexes
		}else{ Params::$urlParts = array();}
		if(stristr(Application::$controller_path,"Index.php")){Application::$controller .= "\\Index";}
		return Application::$controller;
	}

	private function _setControllerName($parts){
		Application::$controller = "";
		foreach ($parts as $part){
			if(!empty($part)){
				Application::$controller .= "\\{$part}";
			}

		}
		return Application::$controller;
	}

}
?>