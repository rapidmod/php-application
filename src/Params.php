<?php
/**
 * @author RapidMod.com
 * @author 813.330.0522
 */


namespace Rapidmod;
use \Rapidmod\Application;

class Params extends \Rapidmod\Data\Model {
	const VERSION = "0.0.1";
	public static $urlParts  = array();
	public $useGet = false;
	private $_uri = NULL;
	private $route_path_index = array();

	public function _get($key)
	{
		if(parent::_get($key)){
			return parent::_get($key);
		}elseif($this->fromRoute($key)){
			$this->_set($key,$this->fromRoute($key));
			return parent::_get($key);
		}elseif ($this->fromPost($key)){
			$this->_set($key,$this->fromPost($key));
			return parent::_get($key);
		}elseif ($this->fromQuery($key)){
			$this->_set($key,$this->fromQuery($key));
			return parent::_get($key);
		}
		if(!Application::config()->_get("use_globals")){return parent::_get($key);}
	}

	public function fromEnvironment($key = false){
		if(!$key){return $_ENV;}
		elseif (isset($_ENV[$key])){return $_ENV[$key];}
		else{return false;}
	}

	public function fromGlobals($key = false){
		if(!$key){return $GLOBALS;}
		elseif (isset($GLOBALS[$key])){return $GLOBALS[$key];}
		else{return false;}

	}

	public function fromPost($key = false){
		if(!$key){return $_POST;}
		elseif (isset($_POST[$key])){return $_POST[$key];}
		else{return false;}
	}

	public function fromQuery($key = false){
		if(!$key){return $_GET;}
		elseif (isset($_GET[$key])){return $_GET[$key];}
		else{return false;}
	}

	public function fromRoute($key){
		if(!empty(self::$urlParts)){
			$parts = self::$urlParts;
			if($key === "id"){
				return $parts[0];
			}elseif ($key === "slug"){
				if(count($parts) < 2){
					return $parts[0];
				}else{
					return array_pop($parts);
				}
			}elseif (in_array($key,$parts)){
				foreach ($parts as $i => $v){
					if($key === $v){
						$next = $i+1;
						if(isset($parts[$next])){return $parts[$next];}
						else{return false;}
					}
				}
			}
		}
	}

	public function fromServer($key = false){
		if(!$key){return $_SERVER;}
		elseif (isset($_SERVER[$key])){return $_SERVER[$key];}
		else{return false;}
	}

	/**
	 *
	 * @param string $key
	 * @param bool   $disableRcore // by default the system creates its own namespace under rcore for sessions
	 *  this helps when you are integrating with other systems and the two applications dont bump heads
	 *
	 * @important rcore session takes precedence over plain session
	 * IE: if a key is set for _SESSION["rcore"][<key>]
	 * you will recieve that value before it looks in _SESSION[<key>]
	 * @todo use RcoreSession
	 *
	 */
	public function fromSession($key = false ,$disableRcore = false){
		if(isset($_SESSION)){session_start();}
		if(!isset($_SESSION["rcore"])){$_SESSION["rcore"] = array();}
		if(!$key){return $_SESSION;}
		elseif (!$disableRcore && isset($_SESSION["rcore"][$key])){return $_SESSION["rcore"][$key];}
		elseif (isset($_SESSION[$key])){return $_SESSION[$key];}
		else{return array();}
	}
}