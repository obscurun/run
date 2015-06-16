<?php
// ATENÇÃO - O gerenciamento principal da linguagem é feita no Router/levels com applyLanguageByUrl
// ****************************************************************************************************************************
class Language{
	public static $phrases 		= array();
	public static $phrase 		= array();
	public static $DEFAULT 		= "";
	public static $AVALIABLE 	= array();
	public static $ENABLED 		= false;
	public static $USING 		= false;
	//*************************************************************************************************************************
	function Language(){
		if(Run::ROUTER_MODE == "METHODS" && is_array(Run::$LANGUAGES_AVAILABLE)) $this->loadLanguagesFromConfig();
		else $this->loadLanguagesFromDatabase();
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function loadLanguagesFromDatabase(){
		Debug::log("Iniciando loadLanguagesFromDatabase.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(!Config::$MYSQL || !Config::$USE_BASE_CONFIG){
				return;
		}
		$result = Run::$mysql->query("SELECT acronym, pk_language FROM ".Config::DB."languages;");
		if($result->num_rows <= 0)
			$idiomas = false;
		else
			$idiomas = array();
		while($row = $result->fetch_assoc()){
			$idiomas[Run::$control->string->lower($row["acronym"])] = $row["pk_language"];
		}
		self::$AVALIABLE = $idiomas;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function loadLanguagesFromConfig(){
		Debug::log("Iniciando loadLanguagesFromConfig.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		self::$AVALIABLE = Run::$LANGUAGES_AVAILABLE;
	}

	//-------------------------------------------------------------------------------------------------------------------------
	public static function loadPhrasesFromProperties($acronym){
		Debug::log("Iniciando loadLanguagesProperties.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		//echo "loadPhrasesFromProperties ".$acronym;
		if(isset($_GET['clean-cache']) && $_GET['clean-cache'] == "language" || $_GET['clean-cache'] == "all" ){
			Run::$session->del(array('Language', 'properties'));
		}
		$propertieSession = Run::$session->get(array('Language', 'properties', $acronym));
		if( count($propertieSession) > 0 ){
			self::$phrase[$acronym] = $propertieSession;
		}else{
			$phrases = Run::$properties->getProperties("languages/".$acronym);		
			Run::$session->set(array('Language', 'properties', $acronym), $phrases);
			self::$phrase[$acronym] = $phrases;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function set($index="sentença não definida", $idiom="", $phrase=" --- "){
		 self::$phrase[$idiom][$index] = $phrase; 
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function get($index="sentença não encontrada", $idiom=""){
		if($idiom=="") $idiom = self::$ENABLED;
		if(isset(self::$phrase[$idiom][$index])) return self::$phrase[$idiom][$index];
		else{ return $index; }	
		//else{ return self::$phrase['phrase_error'][$idiom]; }		
	}
}
// ****************************************************************************************************************************
?>