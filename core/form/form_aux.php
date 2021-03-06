<?php
// ****************************************************************************************************************************
class FormAux{
	public $dataFormSequencial	= array();
	public $session				= NULL;
	public $model				= NULL;
	//*************************************************************************************************************************
	function FormAux($model){
		Debug::log("Iniciando Core/Form/Form.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->model = $model;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getData($field, $i1=false, $i2=false, $i3=false){
		if(count($this->model->dataFormSequencial) ==  0){
			$this->model->dataFormSequencial = $this->model->session->getDataSession();
		}
		$valueData = false;
		if($i1 !== false && $i2 !== false && $i3 !== false){
			if(isset($_POST[$field][$i1][$i2][$i3])) $valueData = $_POST[$field][$i1][$i2][$i3];
			else if(isset($this->model->dataFormSequencial[$field][$i1][$i2][$i3])) $valueData = $this->model->dataFormSequencial[$field][$i1][$i2][$i3];			
		}
		else if($i1 !== false && $i2 !== false){
			if(isset($_POST[$field][$i1][$i2])) $valueData = $_POST[$field][$i1][$i2];
			else if(isset($this->model->dataFormSequencial[$field][$i1][$i2])) $valueData = $this->model->dataFormSequencial[$field][$i1][$i2];			
		}
		else if($i1 !== false){
			if(isset($_POST[$field][$i1])) $valueData = $_POST[$field][$i1];
			else if(isset($this->model->dataFormSequencial[$field][$i1])) $valueData = $this->model->dataFormSequencial[$field][$i1];			
		}else{
			if(isset($_POST[$field])) $valueData = $_POST[$field];
			else if(isset($this->model->dataFormSequencial[$field])) $valueData = $this->model->dataFormSequencial[$field];
		}
		//echo $valueData;
		return $valueData;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoData($field, $parseHTML = true, $i1=false, $i2=false, $i3=false){
		$valueData = $this->getData($field, $i1, $i2, $i3);
		if($parseHTML === true) $valueData = htmlentities($valueData);
		echo $valueData;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getPkData($fieldPk, $field, $valueField, $i1=false, $i2=false, $i3=false){
		if($i2 === false){
			$valueData = $this->getData($field);
			$valuePkData = $this->getData($fieldPk);
		}
		else if($i3 === false){
			$valueData = $this->getData($field, $i1);
			$valuePkData = $this->getData($fieldPk, $i1);
		}
		//Debug::p("valueData $i1,$i2", $valueData);
		//Debug::p("valuePkData", $valuePkData);
		if($valueData !== false){
			//Debug::p($valueData, $valuePkData);
			foreach($valueData as $k => $val){
				//echo "$val === $valueField // ";
				if(Run::$control->string->lower($val) === Run::$control->string->lower($valueField)){
					$valuePkData = $valuePkData[$k];
					//Debug::p($k."?".$valuePkData, $val." === ".$valueField);
					if($valuePkData == "") return "";
					if($valuePkData == "0") return 0;
					if(!is_array($valuePkData) && (int)$valuePkData > $k) return $valuePkData;
					if((int)$valuePkData[$k] > 0) return $valuePkData[$k];
					return $k;
					break;
				}
			}
			if(is_array($valuePkData) && $i2 === false) return $valuePkData[$i1];
			if(is_array($valuePkData) && $i2 !== false) return $valuePkData[$i2];
			return $valuePkData;
		}
		//if( (int)$valuePkData > 0 ) return $valuePkData;
		return 0;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoPkData($fieldPk, $field, $valueField, $i1=false, $i2=false, $i3=false){
		echo $this->getPkData($fieldPk, $field, $valueField, $i1, $i2, $i3);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function checkData($field, $valueField, $i1=false, $i2=false, $i3=false){
		$valueData = $this->getData($field, $i1, $i2, $i3);
		if($valueData !== false){
			foreach($valueData as $k => $val){
				if($val === $valueField){
					echo " checked='checked' ";
					break;
				}
			}
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function selectData($field="", $labelList=array(), $useIndexValue=true, $i1=false, $i2=false, $i3=false){
		//return;
		$valueData = $this->getData($field, $i1, $i2, $i3);
		$valueLabel = $this->getLabelValue($field, $value);
		if(count($labelList) == 0 || $v == false) $labelList = $this->getLabelsList($field);
		foreach($labelList as $k => $val){
			if(isset($val["value"])) $val = $val["value"];
			if($useIndexValue) $selected = ($k == $valueData) ? "selected=\"selected\" " : " $valueData";
			else $selected = ($val == $valueData) ? "selected=\"selected\" " : " $valueData";
			$class = (isset($val["class"])) ? $val["class"] : " ";
			$value = $useIndexValue ? $k : $val;
			echo "\n\r\t<option value=\"".$value."\" $class $selected >".$val."</option>";
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getLabelsList($field){
		if(isset($this->model->schema['fields'][$field]["labelList"])){
			return $this->model->schema['fields'][$field]["labelList"];
		}else{
			return array();
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getLabelValue($field, $value){
		if(isset($this->model->schema['fields'][$field]["labelList"][$value])){
			return $this->model->schema['fields'][$field]["labelList"][$value];
		}
		else if(isset($this->model->schema['fields'][$field]["labelList"][$value]['value'])){
			return $this->model->schema['fields'][$field]["labelList"][$value]['value'];
		}
		else{
			return $value;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function checkFileEmpty($fileName, $i1=false, $i2=false, $i3=false){
		$check_fileinput = $this->getData($fileName, $i1, $i2, $i3);
		//Debug::p($check_fileinput);
		if( (isset($check_fileinput['name']) && $check_fileinput['name'] !== "" ) ) return $check_fileinput;
		else if( (is_array($check_fileinput) == false && $check_fileinput !== "" && $check_fileinput !== false) ) return $check_fileinput;
		
		//

		$check_fileinput = $this->getData($fileName."_ref", $i1, $i2, $i3);
		//Debug::p("$i1, $i2", $check_fileinput);
		if($check_fileinput !== "" && $check_fileinput !== false) return true;

		return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoFile($fileName, $pathName, $idPath, $idFile, $i1=false, $i2=false, $i3=false, $iF1="", $iF2="", $iF3=""){
		Run::$DEBUG_PRINT = 1;
		if($i3 !== false) $fileInputName = $fileName."[$iF1][$iF2][$iF3]";
		else if($i2 !== false) $fileInputName = $fileName."[$iF1][$iF2]";
		else if($i1 !== false) $fileInputName = $fileName."[$iF1]";
		else $fileInputName = $fileName;

		$fileInputNameRef = str_replace($fileName, $fileName."_ref", $fileInputName);
		$fileInputNamePath = str_replace($fileInputNameRef, $fileName."_path", $fileInputNameRef);

		$file = $this->getData($fileName, $i1, $i2, $i3);
		$filePath = $this->getData($pathName, $i1, $i2, $i3);
		$filePath = Run::$router->path['files'].$filePath."$idPath/".Run::$control->string->pad($idFile, $this->model->settings['default_pad'], "0", STR_PAD_LEFT)."-".$file;
		$fileRef = $this->getData($fileName."_ref", $i1, $i2, $i3);
		if(!isset($file['name']) && $fileRef != "") $file = $fileRef;
		//Debug::p(Run::$router->path);

		echo "<div class=\"file_name\">";
		if(isset($file['name'])) echo $file['name']; else echo $file;
		echo "<button type=\"button\" class=\"btn deleteFile right btn-xs btn-danger\">".Language::get("form_bt_delete_file")."</button>";
		echo "</div>";

		echo "\t\n<input type=\"file\" name=\"$fileInputName\" class=\"hide\" />";
		echo "\t\n<input type=\"hidden\" name=\"$fileInputNamePath\" value=\"$filePath\" class=\"hide file_path\" />";
		if(!is_array($file)) echo "\t\n<input type=\"hidden\"   name=\"$fileInputNameRef\" value=\"".$file."\" class=\"hide\" />";
		echo "\t\n<input type=\"hidden\"   name=\"tmpf[]\" value=\"".basename($file['tmp_name'], ".tmp")."\" class=\"hide\" />";
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoValue($valueData, $parseHTML = true){
		if($parseHTML === true) $valueData = htmlentities($valueData);
		echo $valueData;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function echoBtLabel(){
		if((int)$this->model->dataIntern['ref'] > 0){
			echo $this->model->settings['bt_update_label'];
		}else{
			echo $this->model->settings['bt_insert_label'];
		}
	}
}
// ############################################################################################################################
?>