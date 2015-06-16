<?php
// ****************************************************************************************************************************
class Properties{
	private $session	= "";
	private $string		= "";
	//-------------------------------------------------------------------------------------------------------------------------
	function Properties(){
		$this->session = new Session();
		$this->string = new String();
		if(isset(Config::$PROPERTIES_SESSION_LOAD)){
			foreach(Config::$PROPERTIES_SESSION_LOAD as $k => $v){
				$this->getPropertiesToSession(CONFIG_PATH.$v);
			}
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getProperties($file, $path=false){
		if($path === false) $file = CONFIG_PATH."properties/".$file;
		else if($path !== false) $file = $path.$file;
		
		if(strrpos($file, ".properties") < 1) $file_path = $file.".properties";
		//echo ">>>> ".$file_path;
		$open_config = fopen($file_path, "r") or Error::show(0, "$file_path não foi encontrado ou não pode ser aberto");
		$settings = fread($open_config, filesize($file_path) );
		$settings = $this->string->encoding($settings);
		$settings = explode("\n", $settings);
		$sc = count($settings);
		$i = 0;
		$array_return = array();
		foreach($settings as $k => $linha){
			$prop = explode("=", $linha);
			$prop[0] = trim($prop[0]);  // (trim($prop[0]));
			$prop[0] = $this->string->removeSpecialsNormalize($prop[0]);
			if($prop[0] != "" && substr(trim($prop[0]), 0, 1) != "#" && substr(trim($prop[0]), 0, 1)!= "//") $array_return[$prop[0]] = trim($prop[1]);
		}
		return $array_return;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getPropertiesToSession($file, $path=false){
		if($path === false) $file = CONFIG_PATH."properties/".$file;
		else if($path !== false) $file = $path.$file;
		
		$name = explode("/",$file);
		$name = $name[count($name)-1];
		$this->session->set(array('PROPERTIES', $name), false);

		if(strrpos($file, ".properties") < 1) $file_path = $file.".properties";
		
		$open_config = fopen($file_path, "r") or Error::show(0, "$file_path não foi encontrado ou não pode ser aberto");
		$settings = fread($open_config, filesize($file_path) );
		$settings = $this->string->encodeUtf8($settings);
		$settings = explode("\n", $settings);
		$sc = count($settings);
		$i = 0;
		$array_return = array(0);
		foreach($settings as $k => $linha){
			$prop = explode("=", $linha);
			$prop[0] = (trim($prop[0])); // $this->string->clearSpecials(trim($prop[0]));
			$prop[0] = $this->string->removeSpecialsNormalize($prop[0]);
			if($prop[0] != "" && substr(trim($prop[0]), 0, 1) != "#" && substr(trim($prop[0]), 0, 1)!= "//") $array_return[$prop[0]] = trim($prop[1]);
		}
		$propertie_session = $this->session->get(array('PROPERTIES', $name));
		return $propertie_session;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function get($name, $propertie){
		$propertie_session = $this->session->get(array('PROPERTIES', $name, $propertie));
		//echo gettype($propertie_session);
		//Debug::print_r($this->session->get(array('PROPERTIES')));
		return  $propertie_session;
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ****************************************************************************************************************************
?>