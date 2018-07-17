<?php
/**
 * @author RapidMod.com
 * @author 813.330.0522
 */


namespace Rapidmod\Http;
use \Rapidmod\Data\Model as DM;

class Model extends DM{
	private $_data_type = "html";
	private $allowed_data_types = array("json","html");

	public function __construct($dataArray = array()){
		if(is_array($dataArray) && !empty($dataArray)){
			$this->setData($dataArray);
		}
	}

	public function data_type(){
		return $this->_data_type;
	}

	/**
	 * @author RapidMod.com
	 * @author 813.330.0522
	 */
	public function setDataType($type){
		if(in_array($type,$this->allowed_data_types)){
			$this->_data_type = $type;
		}
		return $this;
	}
}