<?php
require(RUN_PATH."core/view/write.php");
require(RUN_PATH."core/view/form.php");
require(RUN_PATH."core/view/paging.php");
require(RUN_PATH."core/view/render.php");
//*****************************************************************************************************************************
class View{
	public 		  $write				= ""; // Object/Classe Write;
	public 		  $form					= ""; // Object/Classe Form;
	public 		  $paging				= ""; // Object/Classe Paging;
	public static $flagLoadedView404	= false; // flag para não carregar a pág.404 2x.
	//*************************************************************************************************************************
	function View(){
		Debug::log("Iniciando Core/View.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->write 	= new Write();
		$this->form 	= new Form();
		$this->paging 	= new Paging();
	}
	//*************************************************************************************************************************
	public static function includeContent($page, $line=__LINE__, $function=__FUNCTION__, $class=__CLASS__, $file=__FILE__){
		self::load($page, 0, $line, $function, $class, $file);
	}
	//*************************************************************************************************************************
    public static function redirect($path){
        Debug::log("View->redirect:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        
        if($path == "back"){	        	
	        header("Location: ".$_SERVER['HTTP_REFERER']);
	        exit;
        }

        $checkPos = strpos($path, Run::$router->path['base']);
        $redirect = ( $checkPos  === 0 || $checkPos > 0 ) ? ($path) : (Run::$router->path['base'].$path) ;

        header("Location: ".$redirect);
        exit;
    }
	//*************************************************************************************************************************
	public static function load($page, $accept=false){
		Debug::log("View->load: $page ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
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
	//*************************************************************************************************************************
	public static function loadView($file=""){
		if($file == "404" && self::$flagLoadedView404 == false){
			self::$flagLoadedView404 = true;
			return false;
		}
		if($file == "") Error::show(0, "loadView: É preciso definir um view a ser carregado.");
		if(file_exists(Run::PATH_PAG."view/".$file.".php")){
			require_once(Run::PATH_PAG."view/".$file.".php");
		}else{
			Error::show(0, "loadView: view $file não encontrado.");
		}
	}
	//*************************************************************************************************************************
	public static function loadModel($file=""){
		if($file == "") Error::show(0, "loadModel: É preciso definir um modelo a ser carregado.");
		if(file_exists(Run::PATH_PAG."model/".$file."_model.php")){
			require_once(Run::PATH_PAG."model/".$file."_model.php");
		}else{
			Error::show(0, "loadModel: modelo $file não encontrado.");
		}
	}
	//*************************************************************************************************************************
	public static function loadController($file=""){
		if($file == "") Error::show(0, "loadController: É preciso definir um controle a ser carregado.");
		if(file_exists(Run::PATH_PAG."control/".$file."_control.php")){
			require_once(Run::PATH_PAG."control/".$file."_control.php");
		}else{
			Error::show(0, "loadController: controle $file não encontrado.");
		}
	}
	//*************************************************************************************************************************
	public static function loadPath($path, $evalPath=false, $show404 = true, $checkFileExist = false){
		if($evalPath){
			if(defined('CMS_PATH')){
				$path = file_exists(APP_PATH.$path)?APP_PATH.$path:CMS_PATH.$path;
			}else{
				$path = APP_PATH.$path;
			}
		}
		Debug::log("View->loadPath: $path", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if($checkFileExist){
			if(file_exists($path)){
				require_once($path);
				return true;
			}
			else{
				Error::show(2, "View->loadPath: $path não encontrado.");
				if($show404) View::load("404");
				return false;
				//exit;
			}
		}else{
			try{ require_once($path); }
			catch(Exception $e){
				throw new Exception('View::loadPath - Arquivo não encontrado.'.$path);
			}
		}
	}
	//*************************************************************************************************************************
	//*************************************************************************************************************************
	public function writeVersion(){
		echo "?v=".Run::VERSION;
	}
}
//*****************************************************************************************************************************
?>