<?php
require(RUN_PATH."core/control/protect.php");
require(RUN_PATH."core/control/string.php");
require(RUN_PATH."core/control/date.php");
require(RUN_PATH."core/control/data.php");
require(RUN_PATH."core/control/file.php");
// ****************************************************************************************************************************
class Control{
	public $string	= "";
	public $date	= "";
	public $data	= "";
	public $protect	= "";
	public $file	= "";
	//*************************************************************************************************************************
	function __construct(){
		Debug::log("Iniciando Core/Control.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->date 	= new Date();
		$this->data 	= new Data();
		$this->protect 	= new Protect();
		$this->string 	= new String();
		$this->file 	= new File();
	}
	//*************************************************************************************************************************
	public function getRequest($val="", $addSlash=true){
		 if(isset($_REQUEST[$val])){
		 	if($addSlash) return $this->protect->addSlashe($_REQUEST[$val]);
		 	else 		  return $_REQUEST[$val];
		 }
		 else return null;
	}
	//*************************************************************************************************************************
	public function getPost($val="", $addSlash=true){
		 if(isset($_POST[$val])){
		 	if($addSlash) return $this->protect->addSlashe($_POST[$val]);
		 	else 		  return $_POST[$val];
		 }
		 else return null;
	}
	//*************************************************************************************************************************
	public function getGet($val="", $addSlash=true){
		 if(isset($_GET[$val])){
		 	if($addSlash) return $this->protect->addSlashe($_GET[$val]);
		 	else 		  return $_GET[$val];
		 }
		 else return null;
	}
	//*************************************************************************************************************************
	public function isAjax(){
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	//*************************************************************************************************************************
	public function typeof($var){
		if(is_array($var))		return "array";
		else if(is_string($var))return "string";
		else if(is_float($var))	return "float";
		else if(is_int($var)) 	return "int";
		else if(is_object($var))return "object";
		else if(is_bool($var))	return "boolean";
		else return "unknown";
	}
	//*************************************************************************************************************************
	public function checkBaseConfig(){
		if(isset(Config::$USE_BASE_CONFIG)){
			if(Config::$USE_BASE_CONFIG === true && count(Config::getConnectionData()) > 0){
				if(!is_array($this->session->get(array('CONFIG')))){
					Run::loadHelper("query");
					$q = new Query();
					$configs = $q->execute("SELECT * FROM ".Config::DB."config_view")->returnAssoc();
					$this->session->set('CONFIG', $configs );
					if(isset($configs[0]['email']))	Config::$SEND_MAIL = $configs[0]['email'];
				}
			}
			// Debug::print_r($this->session->get(array('CONFIG')));
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function objectToArray($object){
        if( !is_object( $object ) && !is_array( $object ) ){
            return $object;
        }
        if( is_object( $object ) ) {
            $object = get_object_vars( $object );
        }
        return array_map( 'objectToArray', $object );
    }
}
// ****************************************************************************************************************************

?>