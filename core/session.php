<?php
// ********************************************************************************************************************************
class Session{
	//-----------------------------------------------------------------------------------------------------------------------------
	function Session(){
		Debug::log("Iniciando Core/Session.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->setSessionID();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function set($name, $value){
		if(is_array($name)){
			if(isset($name[9])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]][$name[8]][$name[9]] = $value;
				return 19;
			}
			if(isset($name[8])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]][$name[8]] = $value;
				return 18;
			}
			if(isset($name[7])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]] = $value;
				return 17;
			}
			if(isset($name[6])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]] = $value;
				return 16;
			}
			if(isset($name[5])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]] = $value;
				return 15;
			}
			if(isset($name[4])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]] = $value;
				return 14;
			}
			if(isset($name[3])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]] = $value;
				return 13;
			}
			if(isset($name[2])){
				$_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]] = $value;
				return 12;
			}
			if(isset($name[1])){
				$_SESSION[Run::NAME][$name[0]][$name[1]] = $value;
				return 11;
			}
			if(isset($name[0])){
				$_SESSION[Run::NAME][$name[0]] = $value;
				return 10;
			}
		}
		else{
			$_SESSION[Run::NAME][$name] = $value;
			return 1;
		}
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function get($name="Run::NAME"){
		if(is_array($name)){
			if(isset($name[9])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]][$name[8]][$name[9]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]][$name[8]][$name[9]];
				else return null;
			}
			if(isset($name[8])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]][$name[8]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]][$name[8]];
				else return null;
			}
			if(isset($name[7])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]];
				else return null;
			}
			if(isset($name[6])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]];
				else return null;
			}
			if(isset($name[5])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]];
				else return null;
			}
			if(isset($name[4])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]][$name[4]];
				else return null;
			}
			if(isset($name[3])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]][$name[3]];
				else return null;
			}
			if(isset($name[2])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]])) return $_SESSION[Run::NAME][$name[0]][$name[1]][$name[2]];
				else return null;
			}
			if(isset($name[1])){
				if(isset($_SESSION[Run::NAME][$name[0]][$name[1]])) return $_SESSION[Run::NAME][$name[0]][$name[1]];
				else return null;
			}
			if(isset($name[0])){
				if(isset($_SESSION[Run::NAME][$name[0]])) return $_SESSION[Run::NAME][$name[0]];
				else return null;
			}
		}
		else if($name === "Run::NAME") return $_SESSION[Run::NAME];
		else if(isset($_SESSION[Run::NAME][$name])) return $_SESSION[Run::NAME][$name];
		else return null;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function del($name){

		$temp = $_SESSION[Run::NAME];
		//Debug::p($temp);
		//exit;
		if(is_array($name)){
			if(isset($name[8])){
				unset($temp[$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]][$name[8]]);
			}
			else if(isset($name[7])){
				unset($temp[$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]][$name[7]]);
			}
			else if(isset($name[6])){
				unset($temp[$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]][$name[6]]);
			}
			else if(isset($name[5])){
				unset($temp[$name[0]][$name[1]][$name[2]][$name[3]][$name[4]][$name[5]]);
			}
			else if(isset($name[4])){
				unset($temp[$name[0]][$name[1]][$name[2]][$name[3]][$name[4]]);
			}
			else if(isset($name[3])){
				unset($temp[$name[0]][$name[1]][$name[2]][$name[3]]);
			}
			else if(isset($name[2])){
				unset($temp[$name[0]][$name[1]][$name[2]]);
			}
			else if(isset($name[1])){
				unset($temp[$name[0]][$name[1]]);
			}
			else if(isset($name[0])){
				unset($temp[$name[0]]);
			}
		}
		else{ unset($temp[$name]); }
		$_SESSION[Run::NAME] = $temp;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function setSessionID(){
		//if(!isset($_SESSION[Run::NAME]['SESSION_ID'])) $_SESSION[Run::NAME]['SESSION_ID'] = Run::$control->data->getUniqueID(); 
		//session_destroy();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function getSessionID(){
		return session_id(); 
		//session_destroy();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function destroy(){
		unset($_SESSION[Run::NAME]);
		//session_destroy();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function destroyAllSessions(){
		session_destroy();
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
}
// ********************************************************************************************************************************
//self::$control->data->getUniqueID()
?>