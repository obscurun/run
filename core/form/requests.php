<?php
// ****************************************************************************************************************************
class Requests{
	//*************************************************************************************************************************
	function Requests(){
		Debug::log("Iniciando Core/Form/Requests.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
	//*************************************************************************************************************************
	public function getAll(){
		Debug::log("Requests->getAll:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests = array();
		//-------------------------------------------------------------------------------------------
		foreach($_POST as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){ $val[$k2] = ($v2); }
				$requests[$key] = $val;
			}
			else $requests[$key] = ($val);
		}
		//-------------------------------------------------------------------------------------------
		foreach($_GET as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){ $val[$k2] = ($v2); } 
				$requests[$key] = $val;
			}
			else $requests[$key] = ($val);
		}
		//-------------------------------------------------------------------------------------------
		foreach($_FILES as $key => $val){
			foreach($val as $skey => $sval){
				$requests[$skey][$key] = $sval;
			}
		}
		return $requests;
	}
	//*************************************************************************************************************************
	public function getREQUEST(){
		Debug::log("Requests->getREQUEST:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests = array();
		//-------------------------------------------------------------------------------------------
		foreach($_REQUEST as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){ $val[$k2] = ($v2); }
				$requests[$key] = $val;
			}
			else $requests[$key] = ($val);
		}
		return $requests;
	}
	//*************************************************************************************************************************
	public function getPOST(){
		Debug::log("Requests->getPOST:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests = array();
		//-------------------------------------------------------------------------------------------
		foreach($_POST as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){ $val[$k2] = ($v2); }
				$requests[$key] = $val;
			}
			else $requests[$key] = ($val);
		}
		return $requests;
	}
	//*************************************************************************************************************************
	public function getGET(){
		Debug::log("Requests->getGET:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests = array();
		//-------------------------------------------------------------------------------------------
		foreach($_GET as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){ $val[$k2] = ($v2); }
				$requests[$key] = $val;
			}
			else $requests[$key] = ($val);
		}
		return $requests;
	}
	//*************************************************************************************************************************
	public function getFILES(){
		Debug::log("Requests->getGET:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests = array();
		echo 123;
		exit;
		//-------------------------------------------------------------------------------------------
		foreach($_FILES as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){ $val[$k2] = ($v2); }
				$requests[$key] = $val;
			}
			else $requests[$key] = ($val);
		}
		return $requests;
	}
}
// ############################################################################################################################

?>