<?php
//*********************************************************************************************************************************
class Levels{ 
	public			 	$path			= array(); 	// paths usados na aplicação e no framework
	public		static  $levels			= array(); 	// níveis da URL / página
	public		static  $levelRef		= 0;		// referência do indice levels, tirando a linguagem
	public		static  $controller		= false; 	// controle carregado no routerMethods
	public		static  $params			= array();  // especifica quais são os parametros depois de carregar o método referente aos levels/path/url.
	private				$templateData	= array();  // dados para serem usados na página. Denifir setTemplateData nos controls
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){
		Run::$benchmark->mark("Router/Levels/Inicio");
		Debug::log("Iniciando libraries/router/Levels.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		self::$levels 	= $this->getParameterLevels();
		Run::$benchmark->writeMark("Router/Levels/Inicio", "Router/Levels/getParameterLevels");
		$this->applyLanguageByUrl();
		Run::$benchmark->writeMark("Router/Levels/Inicio", "Router/Levels/Final");
		/*
		echo "<br /> ";
		Debug::print_r(self::$levels);
		Debug::print_r($_SERVER);
		 */
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	private function getParameterLevels($level=false){
		$request 			= $_SERVER['REQUEST_URI'];
		$request			= explode("?", $request);
		$request_query 		= $request[1];
		$request 			= $request[0];
		$request_relative 	= $_SERVER['SCRIPT_NAME'];
		$request_relative 	= explode("/",$request_relative);
		$request_script 	= $request_relative[count($request_relative)-1];
		unset($request_relative[count($request_relative)-1]);
		$request_relative 	= implode("/",$request_relative);
		$request 			= str_replace($request_relative,"",$request);
		$request 			= str_replace("/".$request_script,"",$request);
		$request 			= substr_replace($request, '', 0, 1);
		$levels 			= explode("/",$request);
		$request_last 		=& $levels[count($levels)-1];

		//Debug::p($request_last);
		if($request_last == "" ) unset($levels[count($levels)-1]);
		if(strrpos($request_last, "?") === 0) unset($levels[count($levels)-1]);

		$request 				= $request."/";
		$request_relative 		= $request_relative."/";
		$request 				= str_replace("//","/",$request);
		$request_relative 		= str_replace("//","/",$request_relative);
		$this->path['base'] 	= $request_relative;
		$this->path['page'] 		= $request_relative."".$request."?".$request_query;
		$this->path['pageBase']		= $request_relative."".$request;
		$this->path['pageQuery'] 	= $request_query;
		$this->path['run'] 			= $request_relative."run/";
		$this->path['view']	 		= $request_relative.Run::PATH_PAG."view/";
		$this->path['files'] 		= $request_relative."files/";

		if(!isset($_SERVER['RUN_MOD_REWRITE'])){
			$this->path['files'] 	= $request_relative.Run::PATH_FILES."";
			$this->path['base'] 	= $request_relative.Run::PATH_PAG."view/";
			$this->path['url'] 		= $_SERVER['SCRIPT_NAME']."/";
		}
		else $this->path['url'] 	= $request_relative;

		return $levels;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public static function getPath($level_num=-1){
		$path_url = "";
		if($level_num == -1) $level_num = count(self::$levels);
		for($n=0;$n<=$level_num;$n++){
			if(isset(self::$levels[$n])){
				if($n>0)$path_url .=  "/";
				$path_url .= self::$levels[$n];
			}
		}
		return $path_url;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public static function getLevel($limit=false, $invert=false){
		$limit = ($limit === false) ? (count(self::$levels)):$limit;
		if($invert === true) $limit = (count(self::$levels)-$limit)-1;
		if(!isset(self::$levels[$limit])) return false;
		return self::$levels[$limit];
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
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function applyLanguageByUrl(){
		Run::$benchmark->mark("Router/Levels/LanguageByUrl/Inicio");
		Debug::log("Iniciando applyLanguageByUrl.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		//verifica se possui referencia de idioma
		//Debug::print_r(Language::$AVALIABLE);
		if(isset(self::$levels[0])){
			if(in_array(Levels::$levels[0], Language::$AVALIABLE)){
				Language::$ENABLED 		= self::$levels[0];
				Language::$USING   		= true;
				self::$levelRef 		= 1;
			}else{//seta idioma default
				Language::$ENABLED 		= Run::LANGUAGE_DEFAULT;
				Language::$USING   		= false;
			}
		}else{
			Language::$ENABLED = Run::LANGUAGE_DEFAULT;
			Language::$USING == false;
		}
	

		if((Run::ROUTER_FIXED_LANGUAGE && !in_array(Levels::$levels[0], Language::$AVALIABLE)) || (Run::ROUTER_FIXED_START && self::$levels[self::$levelRef] == "") ){
			Debug::log("Redirecionando para idioma default.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			//self::$levelRef 		= 1;
			$start = (Run::ROUTER_FIXED_START && self::$levels[self::$levelRef] == "") ? Run::ROUTER_START : $this->getPath();
			$redirect = $this->path_url;
			
			//Debug::p("REDIRECT: ".$redirect);
			if((self::$levels[0] != Language::$ENABLED) && Run::ROUTER_FIXED_LANGUAGE) $redirect .= $this->path['base'].Language::$ENABLED."/";
			//Debug::p("REDIRECT: ".$redirect);
			$redirect .= $start;

			//Debug::p("REDIRECT: ".$redirect);
			//exit;
			header("Location: ".$redirect);
			exit;
		}else{
			//Language::$ENABLED = Run::LANGUAGE_DEFAULT;
			//Language::$USING   = false;
		}
		Run::$benchmark->writeMark("Router/Levels/LanguageByUrl/Inicio", "Router/Levels/LanguageByUrl/ChecklanguageRouter");
		if(Run::LANGUAGE_AUTO_LOAD_PHRASES) Language::loadPhrasesFromProperties(Language::$ENABLED);		
		Run::$benchmark->writeMark("Router/Levels/LanguageByUrl/ChecklanguageRouter", "Router/Levels/LanguageByUrl/loadPhrasesFromProperties");
		//Debug::print_r(self::$levels);
		Run::$benchmark->writeMark("Router/Levels/LanguageByUrl/Inicio", "Router/Levels/LanguageByUrl/Final");
	}

}
//*********************************************************************************************************************************
?>