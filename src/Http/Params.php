<?php
/**
 * @author RapidMod.com
 * @author 813.330.0522
 */


namespace Rapidmod\Http;
use \Rapidmod\Data\Model;

class Params extends Model{
    const VERSION = "0.0.1";
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
        if(!\Rcore::config()->_get("use_globals")){return parent::_get($key);}
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
        if(isset($this->route_path_index[$key])){ return $this->route_path_index[$key];}
        $this->route_path_index[$key] = "";
        if(!$this->_uri){$this->setUri($_SERVER["REQUEST_URI"]);}
        if(empty($this->_uri)){return false;}
        if($key === "id"){
            if($this->_uri){
                if(stristr($this->_uri,"/")){
                    $x = explode("/",$this->_uri);
                    $this->route_path_index[$key] = $x[0];
                    return $x[0];
                }else{

                    $this->route_path_index[$key] = $this->_uri;
                    return $this->_uri;
                }
            }
        }elseif($key==="slug"){
            if(strstr($this->_uri,"/")){
                $x = explode("/",$this->_uri);
                $this->route_path_index[$key] = array_pop($x);
            }else{
                $this->route_path_index[$key] =  $this->_uri;
            }
            return $this->route_path_index[$key];
        }elseif(stristr($this->_uri,$key)){
            $x = explode("/",$this->_uri);
            if(is_array($x)){
                $found = false;
                foreach($x as $a){
                    if($found){
                        $this->route_path_index[$key] = $a;
                        return $a;
                    }elseif($a === $key){
                        $found = true;
                    }
                }
            }
        }
        return false;
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

    public function setUri($controllerPath){
        $len = mb_strlen(trim($controllerPath,"/"));
        $uri = trim($_SERVER["REQUEST_URI"],"/");
        $this->_uri = substr($uri,$len);
        return $this->_uri = $uri;
    }

}