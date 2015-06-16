<?php
/**
 * Router utiliza a url como parametro para chamar Classe e Método
 * Exemplo: www.dominio.com.br/Classe_control.php/metodoASerChamado/$acceptLevels=0
 */
//*****************************************************************************************************************************
include_once(RUN_PATH.'libraries/router/router_base.php');
//*****************************************************************************************************************************
class Router extends RouterBase{
	private $level_to_load_method = 1;
	private $executedCheckAccepts = false;
	public $acceptNextIndexUnknownLevels = 0;
	private static $instance;
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){ 
		self::$instance =& $this;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public static function getInstance(){
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function startRouter(){
		Debug::log("Iniciando libraries/router/Router/startRouter. ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		Run::$benchmark->mark("startRouter/Inicio");
		parent::__construct();
		Debug::log('Classe Router criada.');
		$this->checkUrl();
		Run::$benchmark->writeMark("startRouter/Inicio", "startRouter/Final");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function checkUrl(){
		$pag = isset(self::$levels[self::$levelRef]) ? (self::$levels[self::$levelRef]) : Run::ROUTER_START;
		$this->level_to_load_method = self::$levelRef+1;
		
		$pag	= explode("?", $pag);
		$pag = $pag[0];
		//echo "PAG ".$pag;
		//Debug::log("Router->checkUrl - pag = ".$pag, __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if($pag){
			$this->loadPageFile($pag);
			$this->loadPageContent($pag);
		}else{
			Debug::log("Router - checkUrl = $pag ".Run::ROUTER_START, __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			$this->load("404");
		}
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function loadPageFile($pag){
		Run::$benchmark->mark("loadPageFile/Inicio");
		Debug::log("Router - loadPageFile() ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$page_control = APP_PATH.Run::PATH_PAG."control/".$pag."_control.php";
		$page_view = APP_PATH.Run::PATH_PAG."view/".$pag."_view.php";
		$page_single = APP_PATH.Run::PATH_PAG."view/". implode(self::$levels, "__");

		//tenta carregar o controle ou o view
		if(file_exists($page_control)){
			$this->loadControl($pag);
			Run::$benchmark->writeMark("loadPageFile/Inicio", "loadPageFile/if/Final");
		}else if(file_exists($page_view)){
			$this->loadView($pag);
			Run::$benchmark->writeMark("loadPageFile/Inicio", "loadPageFile/else1/Final");
			exit;
		}else if(file_exists($page_single.".php")){
			$this->loadPath($page_single.".php");
			Run::$benchmark->writeMark("loadPageFile/Inicio", "loadPageFile/else2/Final");
			exit;
		}else if(file_exists($page_single.".htm")){
			$this->loadPath($page_single.".htm");
			Run::$benchmark->writeMark("loadPageFile/Inicio", "loadPageFile/else3/Final");
			exit;
		}else{
			$this->load("404");
			Run::$benchmark->writeMark("loadPageFile/Inicio", "loadPageFile/else/Final");
			Error::show(0, "Router->loadPageFile: Pág Control ou View <b>$pag</b> não existe. (control/".$pag."_control.php).", __FILE__, __LINE__);
			exit;
		}

	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function loadPageContent($pag){
		Debug::log("Router - loadPageContent() ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$class 	= ucwords($pag)."Controller";
		$method	= isset(self::$levels[$this->level_to_load_method]) ? self::$levels[$this->level_to_load_method] : "index";
		$method = $this->checkFullUrlExist($class, $method);
			

		//Debug::p($class, $method);

		//echo "CHECK $class, $method :".method_exists($class,$method);
		//exit;

		if(class_exists($class)){
			self::$controller = new $class();
			//Debug::print_r("acceptNextIndexUnknownLevels: ".self::$controller->acceptNextIndexUnknownLevels);
		}		
		if(class_exists($class) && method_exists($class,$method)){
			for($i=self::$levelRef+2;$i<self::$levelRef;$i++) $this->$params[$i-(self::$levelRef+2)] = self::$levels[$i];
			Debug::log("Router->loadPageContent : Chamando metodo {$method} para a URL. (control/".$pag."_control.php) - $class");
			Action::registerAccess();
			Run::$ajaxMethod->start();
			if(!isset(self::$controller->autoLoadMethod) || self::$controller->autoLoadMethod !== false){
				Run::$benchmark->writeMark("startRouter/Inicio", "loadPageContent/if/controller/method");
				self::$controller->$method();
			}
		}else if((int)self::$controller->acceptNextIndexUnknownLevels > 0){
			if(!isset(self::$controller->autoLoadMethod) || self::$controller->autoLoadMethod !== false){
				$method	= "index";
				Debug::log("Router->loadPageContent : Chamando metodo index/{self::$controller->acceptNextIndexUnknownLevels} para a URL. (control/".$pag."_control.php) - $class");
				Action::registerAccess();
				Run::$benchmark->writeMark("startRouter/Inicio", "loadPageContent/else/controller/method");
				self::$controller->$method();
			}
		}else{
			if(!class_exists($class)) Error::show(0, "Router->loadPageContent: Classe <b>$class</b> não existe. (control/".$pag."_control.php).", __FILE__, __LINE__);
			else Error::show(0, "Router->loadPageContent: Metodo <b>$method</b> não existe. {self::$controller->acceptNextUnknownLevels} (control/".$pag."_control.php).", __FILE__, __LINE__);
			$this->load("404");
		}
		$this->flush();
		//Run::$benchmark->writeMark("loadPageContent1", "loadPageContent2");
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	// Deve ser estático, pois a instância Run::$router não terminou de ser carregada
	public function acceptNextLevels($accept=0){
		$this->executedCheckAccepts 	= true;
		Debug::log("Router - checkNextLevels() ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$total_levels = count(self::$levels);
		$founds = 0;
		for($i=$this->level_to_load_method+1; $i<$total_levels; $i++){
			if((int)self::$levels[$i] < 1) $founds += 1;
		}
		if($accept < $founds){
			Debug::log("Router - acceptNextLevels($accept) > $founds = Exit", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			$this->load("404");
			exit;
		}
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function checkFullUrlExist($class, $method){
		$path = "";
		$level_ref = self::$levelRef;
		foreach(self::$levels as $k => $v){
			if($k >= self::$levelRef){
				if((int)$v > 0) continue;
				if($k > 1) $path .= "__"; 
				if($k > 0) $path .= $v;
				$level_ref = $k;
			}
		}
		$path = $this->parseUrlToMethod($path);
		$isMethod = method_exists($class, $path);
		//echo "CHECKFULL $path $class, $path :".$isMethod;
		//exit;
		Debug::log("Router - checkFullUrlExist($class, $path) = ". (int)$isMethod, __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if($isMethod){
			$this->level_to_load_method = $level_ref;
			return $path;
		}else return $this->parseUrlToMethod($method);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function parseUrlToMethod($url){
		$url = str_replace('-','_',$url);
		return $url;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function startBuffer(){
		//ob_start();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function flush(){
		flush();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function writeBuffer(){
		//echo ob_get_contents();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function clhjkhjkhjseBuffer(){
		//ob_end_flush();
		//ob_end_clean();
	}
}
//*****************************************************************************************************************************
?>