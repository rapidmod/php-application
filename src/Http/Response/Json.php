<?php
namespace Rapidmod\Http\Response;
use \Rapidmod\Data\Model;
use \JsonModel;
class Json extends Model{

	public function __construct(JsonModel $DataObject){
		/**
		 * set some header information here
		 *
		 */
		header('Content-Type: application/json');

		$data = $DataObject->toArray();
		if(empty($data)){
			if(empty($data["status"])){
				$data["status"] = 204;
			}
		}
		if(empty($data["status"])){
			$data["status"] = 500;
		}
		echo json_encode($data);
		return;

	}


}