<?php
// ****************************************************************************************************************************
class Protect{ 
	//*************************************************************************************************************************
	function __construct(){
		Debug::log("Iniciando Core/Protect.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->getVals();
	}
	//*************************************************************************************************************************
	public function getVals(){
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function forceProtection($str){
		$str = $this->decodeURL($str);
		$str = $this->clearHTML($str);
		$str = $this->stripJS($str);
		$str = $this->forceSlashe($str);
		return $str;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function decodeURL($value){
			$value = @preg_replace("/"."%20"."/", " ", $value );
			return $value;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function addSlashe($value){
		if (!get_magic_quotes_gpc()) { // verifica se o addSlashes está automático no server
		   return addSlashes($value); // insere \ nas aspas
		} else {
		   return $value;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getProtectData($value, $fixOffice = true){
		////return $value;
		$value = $this->clearHTML($value);
		if($fixOffice) $value = $this->fixOffice($value);
		$value = $this->addSlashe($value);
		return $value;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function fixOffice($value){
		if(is_array($value)){
			//Debug::print_r($value);
			/*
			foreach($value as $k => $v){
				$value[$k] = $this->addSlashe($value);
			}*/
			return $value;
		}else{
			//if($utf8_encode !== true){
			/*
				$str = $value;
				$str = @preg_replace( "/".chr(ord("`"))."/", "'", $str );        # `
				//$str = @preg_replace( "/".chr(ord("´"))."/", "'", $str );        # ´
				//$str = str_replace( chr(ord("„")), ",", $str );        # „
				$str = str_replace( chr(ord("`")), "'", $str );        # `
				$str = str_replace( chr(ord("´")), "'", $str );        # ´
				$str = str_replace( chr(ord("“")), "'", $str );        # “
				$str = str_replace( chr(ord("”")), "'", $str );        # ”
				$str = str_replace( chr(ord("´")), "'", $str );        # 
				$str = str_replace( chr(ord("–")), "-", $str );        # 
				$value = $str;

				$value = str_replace("“", '"', $value);
				$value = str_replace("“", '"', $value);
				$value = str_replace("“", '"', $value);
				$value = str_replace("”", '"', $value);
				$value = str_replace("”", '"', $value);
				$value = str_replace("–", "-", $value);
				$value = str_replace("–", "-", $value);*/
			/*	
			//}
			/**/
			// Next, replace their Windows-1252 equivalents.

			if( Config::ENCODING == "utf8" ){/*
				$value = str_replace(
					array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
					array(
					 	Run::$control->string->mixed_to_utf8("'"), 
					 	Run::$control->string->mixed_to_utf8("'"), 
					 	Run::$control->string->mixed_to_utf8('"'), 
					 	Run::$control->string->mixed_to_utf8('"'), 
					 	Run::$control->string->mixed_to_utf8('-'), 
					 	Run::$control->string->mixed_to_utf8('--'), 
					 	Run::$control->string->mixed_to_utf8('...')
					),
					$value
				);*/
				$value = str_replace(
					array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
					array(
					 	Run::$control->string->encodeUtf8("'"), 
					 	Run::$control->string->encodeUtf8("'"), 
					 	Run::$control->string->encodeUtf8('"'), 
					 	Run::$control->string->encodeUtf8('"'), 
					 	Run::$control->string->encodeUtf8('-'), 
					 	Run::$control->string->encodeUtf8('--'), 
					 	Run::$control->string->encodeUtf8('...')
					),
					$value
				);
			}
			else{
				/*
				$value = str_replace(
					array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
					array(
					 	"'", 
					 	"'", 
					 	'"', 
					 	'"', 
					 	'-', 
					 	'--', 
					 	'...'
					),
					$value
				); */
				$value = str_replace(
					array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
					array(
					 	"'", 
					 	"'", 
					 	'"', 
					 	'"', 
					 	'-', 
					 	'--', 
					 	'...'
					),
					$value
				);
			}
		}
		return $value;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function forceSlashe($value){
		$value = str_replace("“", "\"", $value);
		$value = str_replace("“", "\"", $value);
		$value = str_replace("”", "\"", $value);
		$value = str_replace("”", "\"", $value);
		$value = str_replace("–", "-", $value);
		$value = str_replace("–", "-", $value);
		$value = str_replace("–", "-", $value);
		$value = str_replace( chr(149), "&#8226;", $value );    # bullet •
		$value = str_replace( chr(150), "&ndash;", $value );    # en dash
		$value = str_replace( chr(151), "&mdash;", $value );    # em dash
		$value = str_replace( chr(153), "&#8482;", $value );    # trademark
		//$value = str_replace( chr(169), "&copy;", $value );    # copyright mark
		$value = str_replace( chr(174), "&reg;", $value );        # registration mark

		return addslashes($value); // insere \ nas aspas
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function removeSlashe($value){
		//return stripslashes($value);
		$value = array("valor" => $value);
		if(get_magic_quotes_gpc()) {
			$process = array(&$value);
			while (list($key, $val) = each($process)) {
				foreach ($val as $k => $v) {
					unset($process[$key][$k]);
					if (is_array($v)) {
						$process[$key][stripslashes($k)] = $v;
						$process[] = &$process[$key][stripslashes($k)];
					} else {
						$process[$key][stripslashes($k)] = stripslashes($v);
					}
				}
			}
			unset($process);
		}
		return $value['valor'];
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function treatValue($str){
		$str = str_replace("[", "", $str);
		$str = str_replace("]", "", $str);
		return $str;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function clearHTML($str){
		$str = str_replace("style=", "estilo=", 	$str);
		$str = $this->stripJS($str);
		$str = strip_tags($str, '<p><i><u><b><strong><br><div><span><em><table><tr><td><th><h3><h4><h5><h6><ul><li><ol><dl><dh>');
		$str = nl2br($str);
		return $str;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function clearURL($str){
		$str = str_replace("'", 		"-", 		$str);
		return $str;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function stripJS($filter){
		// realign javascript href to onclick
		$filter = preg_replace("/href=(['\"]).*?javascript:(.*)?\\1/i", "onclick=' $2 '", $filter);
		//remove javascript from tags
		while( preg_match("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", $filter))
			$filter = preg_replace("/<(.*)?javascript.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "<$1$3$4$5>", $filter);
		// dump expressions from contibuted content
		if(0) $filter = preg_replace("/:expression\(.*?((?>[^(.*?)]+)|(?R)).*?\)\)/i", "", $filter);
		while( preg_match("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", $filter))
			$filter = preg_replace("/<(.*)?:expr.*?\(.*?((?>[^()]+)|(?R)).*?\)?\)(.*)?>/i", "<$1$3$4$5>", $filter);
		// remove all on* events
		while( preg_match("/<(.*)?\s?on.+?=?\s?.+?(['\"]).*?\\2\s?(.*)?>/i", $filter))
		   $filter = preg_replace("/<(.*)?\s?on.+?=?\s?.+?(['\"]).*?\\2\s?(.*)?>/i", "<$1$3>", $filter);
		return $filter;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function checkRequests($clearHTML=true,$clearSCRIPT=true){
		Debug::log("protect->checkRequests:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests = array();
		//-------------------------------------------------------------------------------------------
		foreach($_GET as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){
					if($clearHTML)		$v2 = $this->clearHTML($v2);
					if($clearSCRIPT)	$v2 = $this->stripJS($v2);
					$val[$k2] = $this->addSlashe($v2);
				}
				$requests[$key] = $val;
			}
			else{
				if($clearHTML) 		$val = $this->clearHTML($val);
				if($clearSCRIPT) 	$val = $this->stripJS($val);
				$requests[$key] = $this->addSlashe($val);
			}
		}
		//-------------------------------------------------------------------------------------------
		foreach($_POST as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){
					if($clearHTML) 		$v2 = $this->clearHTML($v2);
					if($clearSCRIPT) 	$v2 = $this->stripJS($v2);
					$val[$k2] = $this->addSlashe($v2);
				}
				$requests[$key] = $val;
			}
			else{
				if($clearHTML) 		$val = $this->clearHTML($val);
				if($clearSCRIPT) 	$val = $this->stripJS($val);
				$requests[$key] = $this->addSlashe($val);
			}
		}
		//-------------------------------------------------------------------------------------------
		foreach($_FILES as $key => $val){
			$requests[$key] = $val;
		}
		return $requests;
		//-------------------------------------------------------------------------------------------
	}
}

?>