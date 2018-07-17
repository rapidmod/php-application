<?php
namespace Rapidmod\Http\Response;
use \Rapidmod\Data\Model;
use \Rapidmod\Application;
class Cms extends Model{

	private $_layout_handle = "default";
	private $_layout_file = NULL;
	/**
	 * @var not sure if I am going to use these
	 */
	protected $app_dir = "";
	protected $default_dir = "";
	protected $lib_dir = "";
	/**
	 * @var end not sureness
	 */

	protected $app_view_dir = "";
	protected $lib_view_dir = "";
	protected $default_view_dir = "";
	protected $page_content = "";
	protected $package_view_dir = "";
	protected $view_directories = array();
	protected $viewModel = NULL;


	public function __construct(\ViewModel $DataObject,$printData = true){
		$this->view_directories = Application::app()->view_directories();
		if(Application::config()->_get("layout_handle")){
			$this->_layout_handle = Application::config()->_get("layout_handle");
		}
		//Rcore::config()->_set("layout_disabled",true);
		$this->viewModel = $DataObject;
		if($printData){return $this->toHtml();}
		return $this;
	}


	private function getLayout(){
		$this->setLayoutFile();
		if(!is_null($this->_layout_file)){return $this->_layout_file;}
		$directory = $this->default_view_dir."layout" .DIRECTORY_SEPARATOR."default.phtml";
		clearstatcache();
		if(file_exists($directory)){
			$this->_layout_handle = "default";
			$this->_layout_file = $directory;
			return $this->_layout_file;
		}

	}

	private function sendHtmlResponse($useHeaders=true,$html=""){
		if(!empty($html)){$this->page_content = $html;}
		if($useHeaders){
			header("Content-Type: text/html; charset=utf-8");
			header("Content-Length: ".mb_strlen($this->page_content,"8bit"));
			header("Server: Yo Mama");
			header('X-Powered-By: Yo Mama');
		}
		 echo $this->page_content; return;
	}

	public function setLayoutFile($layout = ""){
		if(empty($layout)){
			if(Application::config()->_get("layout_handle")){
				$layout = Application::config()->_get("layout_handle");
			}
		}

		if(empty($layout)){$layout = $this->_layout_handle;}
		clearstatcache();
		$name = str_replace("_",DIRECTORY_SEPARATOR,$layout).".phtml";
		if(is_array($this->view_directories) && !empty($this->view_directories)){
			foreach($this->view_directories as $dir){
				clearstatcache();
				$path = $dir."layout".DIRECTORY_SEPARATOR.$name;
				if(file_exists($path)){
					$this->_layout_handle = $layout;
					$this->_layout_file = $path;
					Application::config()->_set("layout_handle",$layout);
				}else{
					Application::config()->_set("layout_handle","default");
				}
				return $this;
			}
		}

		return $this;
	}

	public function toHtml($html = ""){

		if(!empty($html)){return $this->sendHtmlResponse(true,$html);}

		$blocks = $this->viewModel->getBlocks();
		$return_html = "";
		if(!Application::config()->_get("layout_disabled")){
			$layoutPath = $this->getLayout();
			clearstatcache();
			if(file_exists($layoutPath)){
				if(is_array($blocks) && !empty($blocks)){
					foreach ($blocks as $key => $block){
						$this->_set($key,$block);
					}
				}
				ob_start();
				include $layoutPath;
				$this->page_content .=  trim(ob_get_clean());
			}

			return $this->sendHtmlResponse(true);
		}else{
			if(is_array($blocks) && !empty($blocks)){
				foreach ($blocks as $block){
					$this->page_content .= trim($block).PHP_EOL;
				}
			}
			return $this->sendHtmlResponse(true);
		}
	}
}