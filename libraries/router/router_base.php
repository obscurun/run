<?php
//*********************************************************************************************************************************
include_once(RUN_PATH.'libraries/router/levels.php');
//*********************************************************************************************************************************
class RouterBase extends Levels{ 
	public static $flagLoadedView404	= false; // flag para não carregar a pág.404 2x.
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){
		Debug::log("Iniciando libraries/router/RouterBase.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);		
		parent::__construct();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function loadControl($page){
		require(APP_PATH. Run::PATH_PAG ."control/". $page ."_control.php");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function loadView($page){
		if(strpos($page, ".php")) require(APP_PATH. Run::PATH_PAG ."view/". $page); 
		else require(APP_PATH. Run::PATH_PAG ."view/". $page ."_view.php");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function loadPath($path){
		require($path);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public static function load($page, $accept=false){
		Debug::log("RouterBase->load: $page ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$page_path = Config::PATH_PAG."view/". $page .".php";
		if( 
			( ((count(Router::$params)<=3 && $accept===false) || $accept===true) && file_exists($page_path) )
			|| (Router::$useLanguage==true && file_exists($page_path))
		){
			include($page_path);
		}else if(self::$flagLoadedView404 != true){
			//echo Debug::print_r($_SERVER);
			self::$flagLoadedView404 = true;
			Debug::log("View->load: Não existe $page_path ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			if(file_exists(Config::PATH_PAG."view/404.php")) include(Config::PATH_PAG."view/404.php");
			else Debug::log("View->load: {Config::PATH_PAG}.'view/404.php' não existe. ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			exit;
		}else{
			echo "Erro 404. <!-- Erro 404 já aplicado, mas existe erro na página 404 -->";
			exit;
		}
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function getTemplateData($data){
		if(isset($this->templateData[$data])) return $this->templateData[$data];
		else return null;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function setTemplateData($data, $value){
		$this->templateData[$data] = $value;
	}

}
//*********************************************************************************************************************************
?>