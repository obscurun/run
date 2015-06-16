<?php
//*****************************************************************************************************************************
class Form{

	//*************************************************************************************************************************
	function Form(){
		Debug::log("Iniciando Core/View/Form.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);

	}
	//*************************************************************************************************************************
	public function setSelectOptions($data, $selected) {
		$html = "";
		foreach($data as $k=>$val){
			$select = ($k == $selected) ? " selected='selected' " : "" ;
			$html .="\n\r\t <option value=\"". htmlspecialchars($k) ."\" $select >". $val ."</option>";
		}
		return $html;
	}
	//*************************************************************************************************************************
	public function decodeUTF8($REQUEST){
		$tratado = array();
		if(is_array($REQUEST)){
			foreach($REQUEST as $key => $val){
				if(is_array($val)){
					$val = $this->decodeUTF8($val);
				}
				$tratado[$key] = $val;
			}
		}
		else{
			$tratado = utf8_decode($REQUEST);
		}
		return $tratado;
	}
	//*************************************************************************************************************************
	public function encodeUTF8($REQUEST){
		$tratado = array();
		if(is_array($REQUEST)){
			foreach($REQUEST as $key => $val){
				if(is_array($val)){
					$val = $this->encodeUTF8($val);
				}
				$tratado[$key] = $val;
			}
		}
		else{
			$tratado = utf8_encode($REQUEST);
		}
		return $tratado;
	}
}
//*****************************************************************************************************************************
?>