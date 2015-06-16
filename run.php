<?php 
session_start();
date_default_timezone_set(Config::TIMEZONE);
require(RUN_PATH. "core/error/error_reporting.php");
require(RUN_PATH. "core/log/debug.php");
require(RUN_PATH. "core/log/benchmark.php");
require(RUN_PATH. "core/control.php");
require(RUN_PATH. "libraries/router/router.php");
require(RUN_PATH. "core/log/action_log.php");
require(RUN_PATH. "core/ajaxMethod.php");
require(RUN_PATH. "core/model.php");
require(RUN_PATH. "core/view.php");
require(RUN_PATH. "core/session.php");
require(RUN_PATH. "core/properties.php");
require(RUN_PATH. "core/language.php");
require(RUN_PATH. "core/cookie.php");
require(RUN_PATH. "core/json.php");/**/
//*********************************************************************************************************************************
class Run extends Config{

	public static  $control;
	public static  $model;
	public static  $debug;
	public static  $view;
	public static  $session;
	public static  $properties;
	public static  $cookie;
	public static  $json;
	public static  $action;
	public static  $router;
	public static  $ajaxMethod;
	public static  $benchmark;
	public static  $language;

	//*****************************************************************************************************************************
	function __construct(){
		Debug::log("Running", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		self::$debug 			= new Debug(); 
		self::$benchmark 		= new Benchmark();
		self::$benchmark->mark("Run/Inicio");
		$this->startBasicConfig();
		self::$control 			= new Control(); 
		self::$model 			= new Model();
		self::$view 			= new View();
		self::$session 			= new Session();
		self::$cookie 			= new Cookie();
		self::$properties 		= new Properties();
		self::$language 		= new Language();
		self::$json 			= new JSON();
		self::$ajaxMethod 		= new AjaxMethod();
		self::$action 			= new Action();
		self::$benchmark->writeMark("Run/Inicio", "Run/Classes Instanciadas");
		//é 0.003 mais rápido carregar td de uma vez
		//$this->startExtrasClasses();
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if(Run::USE_ROUTER){
			self::$benchmark->mark("USE_ROUTER/Inicio");
			self::$router 	= new Router();
			self::$router->startRouter();
			self::$benchmark->mark("USE_ROUTER/Final");
		}
		self::$benchmark->writeMark("Run/Inicio", "Run/Final");
	}
	//*****************************************************************************************************************************
	static function loadCore($file=""){
		if($file == "") Error::show(0, "loadCore: É preciso definir um núcleo a ser carregado.");
		if(file_exists(APP_PATH."core/$file.php")){
			require_once(APP_PATH."core/$file.php");
		}else{
			Error::show(0, "loadCore: Núcleo $file não encontrado.");
		}
	}
	//*****************************************************************************************************************************
	static function loadLibrary($file=""){
		if($file == "") Error::show(0, "loadLibrary: É preciso definir uma biblioteca a ser carregada.");
		if(file_exists(RUN_PATH."libraries/$file.php")){
			require_once(RUN_PATH."libraries/$file.php");
		}else{
			Error::show(0, "loadLibrary: biblioteca ".RUN_PATH."libraries/$file.php não encontrada.");
		}
	}
	//*****************************************************************************************************************************
	static function loadHelper($file=""){
		if($file == "") Error::show(0, "loadHelper: É preciso definir um helper a ser carregado.");
		if(file_exists(RUN_PATH."helpers/$file.php")){
			require_once(RUN_PATH."helpers/$file.php");
		}else{
			Error::show(0, "loadHelper: helper $file não encontrado.");
		}
	}
	//*****************************************************************************************************************************
	static function loadPlugin($file=""){
		if($file == "") Error::show(0, "loadPlugin: É preciso definir um plugin a ser carregado.");
		if(file_exists(RUN_PATH."plugins/control/".$file."_plugin_control.php")){
			require_once(RUN_PATH."plugins/control/".$file."_plugin_control.php");
		}else{
			Error::show(0, "loadPlugin: plugin ".$file."_plugin_control não encontrado.");
		}
	}
	//*****************************************************************************************************************************
	static function loadPluginModel($file=""){
		if($file == "") Error::show(0, "loadPluginModel: É preciso definir um plugin/model a ser carregado.");
		if(file_exists(APP_PATH."pags/model/".$file."_model.php")){
			require_once(APP_PATH."pags/model/".$file."_model.php");
		}elseif(file_exists(APP_PATH."plugins/model/".$file."_model.php")){
			require_once(APP_PATH."plugins/model/".$file."_model.php");
		}else{
			Error::show(0, "loadPluginModel: plugin ".$file."_model não encontrado.");
		}
	}
	//*****************************************************************************************************************************
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	//*****************************************************************************************************************************
	public function startBasicConfig(){
		$this->configLocation();
		$this->configLoadBasic();
		$this->configHeader();
		$this->startConfig();
	}
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function startConfig(){
		$this->onStartConfig();
	}
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function configLocation($cod="ptb", $state="BR", $timezone="America/Sao_Paulo"){
		setlocale(LC_CTYPE, $cod,$state);
		date_default_timezone_set($timezone);
	}
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function configLoadBasic(){
	}
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function configHeader(){
		if(Config::ENCODING == "utf8") header('Content-Type: text/html; charset=utf-8');
		else header('Content-Type: text/html; charset=ISO-8859-1');
	}
	//*****************************************************************************************************************************
	static function startExtrasClasses(){
		//	public static	 $START_EXTRAS	= array("core/model","core/view","core/session","core/cookie","core/properties","core/json","core/ajaxMethod");
		foreach(self::$START_EXTRAS as $_k => $_class){
			//require(RUN_PATH.  $_class.".php");
			$_class = explode("/", $_class);
			$_class = $_class[count($_class)-1];
			$_classU = Run::$control->string->upperFirst($_class);
			self::$$_class = new $_classU($_value);
		}
	}
}
//*********************************************************************************************************************************
?>