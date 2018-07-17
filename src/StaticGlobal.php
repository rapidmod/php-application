<?php
/**
 * @author RapidMod.com
 * @author 813.330.0522
 */


namespace Rapidmod;


class StaticGlobal {
	protected static $_data = array();
	public static function __callStatic($name, $arguments)
	{

		if(!empty($arguments)){
			self::$_data[$name] = $arguments;
		}
		if (isset(self::$_data[$name])){
			return self::$_data[$name];
		}else{
			return false;
		}
		// Note: value of $name is case sensitive.
		echo "Calling static method '$name' "
		     . implode(', ', $arguments). "\n";
	}
}