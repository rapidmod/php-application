<?php
/**
 * @author RapidMod.com
 * @author 813.330.0522
 */


namespace Rapidmod\Http;


use Rapidmod\Application;

class Response {
	public $response_type;

	public function __construct(\Rapidmod\Http\Model $Data){
		if(Application::$redirect_uri){
			\Rapidmod\Session::user()->rediret_uri = Application::$redirect_uri;
		}
		switch($Data->data_type()){

			case "html" : return new \Rapidmod\Http\Response\Html($Data); break;
			case "json" : return new \Rapidmod\Http\Response\Json($Data); break;
			case "cms" 	: return new \Rapidmod\Http\Response\Cms($Data); break;
		}
	}
}