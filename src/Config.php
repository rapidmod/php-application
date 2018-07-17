<?php
/**
 * @author RapidMod.com
 * @author 813.330.0522
 */


namespace Rapidmod;


class Config extends \Rapidmod\Data\Model {
	private $package_config_dir = NULL;
	private $package_sys_dir = NULL;
	private $package_cache_dir = NULL;


	public function __construct(){
		$base = \Rapidmod\Application::$package_dir."etc".DIRECTORY_SEPARATOR;

		$this->package_config_dir = $base."config".DIRECTORY_SEPARATOR;
		$this->package_sys_dir = $base."sys".DIRECTORY_SEPARATOR;
		$this->package_cache_dir = $base."cache".DIRECTORY_SEPARATOR;
	}

	public function _get($key){
		if(empty($key)){return false;}
		if(parent::_get($key)){
			return parent::_get($key);
		}
		$this->package_config_dir = \Rapidmod\Application::$package_dir."etc"
		                            .DIRECTORY_SEPARATOR."config".DIRECTORY_SEPARATOR;
		clearstatcache();
		if(file_exists($this->package_config_dir.$key.".php")){
			$configuration = "";
			include($this->package_config_dir.$key.".php");
			$this->setConfig($configuration);
		}



		return parent::_get($key);

	}

	public function loadFromCache($key){
		$configuration = array();
		clearstatcache();
		$filePath = $this->package_cache_dir.$key.".json";
		if(file_exists($filePath)){
			$configuration = json_decode(file_get_contents($filePath,1));
			return $this->setConfig($configuration);

		}
	}

	private function setConfig($configuration){
		if(is_array($configuration) && !empty($configuration)){
			foreach($configuration as $k => $v){
				$this->_set( $k, $v );
			}
			return true;
		}else {return false;}
	}
}