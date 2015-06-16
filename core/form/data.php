<?php
namespace FormModel{
// ****************************************************************************************************************************
class Data{
	private $settings 		= NULL;
	private $schema 		= NULL;
	private $dataInt 		= array();
	private $database 		= NULL;
	public 	$_POST_FILES    = array();
	public 	$dataErrors    	= array();
	public 	$formId    		= "";
	//*************************************************************************************************************************
	function __construct($schema, $settings){
		//\Run::$DEBUG_PRINT = 1;
		\Debug::log("Iniciando Core/Form/Data.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->settings = $settings;
		//\Debug::print_r($settings);
		//$dataFormInt 	= $this->getRequests();
		$this->formId 	= $formSessionId;
		$this->schema 	= $schema;
		$this->database = \Model::getInstance();
	}
	//*************************************************************************************************************************
	public function getDataInternal(){
		\Debug::log("Model->getDataInternal:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(!isset($dataFormInt[$this->settings['paging_ref'].'busca'])) $dataFormInt[$this->settings['paging_ref'].'busca'] 	 = "";
		$dataFormInt[$this->settings['paging_ref'].'use_default'] = (isset($dataFormInt[$this->settings['paging_ref'].'ordem'])) ? false : $this->schema['orderby'];

		if(!isset($dataFormInt[$this->settings['paging_ref']."index"])){
			if((int)\Run::$router->getLevel($this->settings['paging_param_ref'], true) > 0 && $this->settings['use_url_ref'] === true) $dataFormInt[$this->settings['paging_ref'].'index'] = (int)\Run::$router->getLevel($this->settings['paging_param_ref'], true);
			else if(isset($_GET[$this->settings['ref']]) && (int)$_GET[$this->settings['ref']] > 0) $dataFormInt[$this->settings['paging_ref'].'index'] = (int)$_GET[$this->settings['ref']];
		}
		if((int)$dataFormInt[$this->settings['paging_ref'].'index'] < 1) 	$dataFormInt[$this->settings['paging_ref'].'index']	= 1;
		//echo "???? ".$dataFormInt[$this->settings['paging_ref'].'index'];
		if(!isset($dataFormInt[$this->settings['paging_ref'].'ordem'])) 	$dataFormInt[$this->settings['paging_ref'].'ordem']	= $this->schema['from']['pk'];
		if(!isset($dataFormInt[$this->settings['paging_ref'].'modo'])) 		$dataFormInt[$this->settings['paging_ref'].'modo']	= "desc";
		if(!isset($dataFormInt[$this->settings['paging_ref'].'num'])) 		$dataFormInt[$this->settings['paging_ref'].'num']	= $this->settings['paging_num'];
		
		$this->settings['paging_num'] = $dataFormInt[$this->settings['paging_ref'].'num'];
		if($dataFormInt[$this->settings['paging_ref'].'modo'] == 'asc')		$dataFormInt[$this->settings['paging_ref'].'contramodo']	= 'desc'; 
		else $dataFormInt[$this->settings['paging_ref'].'contramodo'] = 'asc';
		
		if(isset($_GET[$this->settings['ref']])){
			if((int)$_GET[$this->settings['ref']] > 0) $dataFormInt[$this->settings['ref']] = (int)$_GET[$this->settings['ref']];		
		}else if(!isset($dataFormInt[$this->settings['ref']])){
			if((int)\Run::$router->getLevel($this->settings['paging_param_ref'], true) > 0) $dataFormInt[$this->settings['ref']] = (int)\Run::$router->getLevel($this->settings['paging_param_ref'], true);		
		}
		\Debug::print_r("dataFormInt", $dataFormInt);
		return $dataFormInt;
	}
	//*************************************************************************************************************************
	public function getFormId($dataPost){
		if( isset($dataPost[$this->schema['from'][0]['pk']]) ) return $dataPost[$this->schema['from'][0]['pk']];
		else{
			$dataIntern = $this->getDataInternal();
			return $dataIntern[$this->settings['paging_ref'].'index'];
		}
	}
	//*************************************************************************************************************************
	private function checkSettingsValue($value, $paramField){
		$isFile = false;
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['value'] !== false && trim($value) == ""){
			 $value = $paramField['value'];
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "file_name"){
			$isFile = true;
			if(isset($value['name'])) $value = \Run::$control->string->removeSpecialsNormalize($value['name'], true);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "file_size"){
			$isFile = true;
			//\Debug::print_r($paramField['name']);
			//\Debug::print_r($value);
			if(isset($value['size'])) $value = $value['size'];
			else $value = "";
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "file_error"){
			$isFile = true;
			if(isset($value['error'])) $value = $value['error'];
			else $value = "";
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "file_type"){
			$isFile = true;
			if(isset($value['size'])) $value = $value['type'];
			else $value = "";
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "file_extension"){
			$isFile = true;
			if(isset($value['name'])){
				$value = explode(".", $value['name']);
				$value = $value[count($value)-1];
			}
			else $value = "";
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($this->settings['encode_utf8'] === true){
			$value = \Run::$control->string->encodeUtf8($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($this->settings['encode_iso'] === true){
			$value = \Run::$control->string->encodeIso($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($this->settings['encoding'] === true){
			$value = \Run::$control->string->encoding($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['removeSpecials'] === true){
			$value = \Run::$control->string->removeSpecialsNormalize($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['convertSpecials'] === true){
			// $value = \Run::$control->string->fixSpecials($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['protectData'] === true){
			$value = \Run::$control->protect->getProtectData($value, true);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['addSlashe'] !== false && $paramField['protectData'] === false){
			$value = \Run::$control->protect->addSlashe($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['realScape'] === true){
			//$value = \Run::$model->dataBase->escape($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "datetime"){
			$value = \Run::$control->date->fullConversion($value, 'DATETIME');
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "string"){
			if($paramField['allowJS'] !== true) 	$value =  \Run::$control->protect->clearHTML($value);
			if($paramField['allowHTML'] === false)	$value =  strip_tags($value);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "integer" || $paramField['type'] == "int"){
			$value  = (int)$value;
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if((int)$paramField['maxLength'] > 0){
			$value = \Run::$control->string->cropStr($value, $paramField['maxLength'], $isFile);
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if($paramField['type'] == "date_insert" || $paramField['type'] == "date_update" || $paramField['type'] == "dateinsert" || $paramField['type'] == "dateupdate"){
			$value = \Date::$TODAY['DATETIME'];
		}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if(is_array($paramField['convertValue']) || is_string($paramField['convertValue']) && $paramField['convertValue'] != ""){
			if(is_string($paramField['convertValue'])){
				$paramField['convertValue'] = explode(',', $paramField['convertValue']);
			}
			foreach($paramField['convertValue'] as $action => $func){
				if(trim($func) == "sha1"){
					$value = sha1($value);
				}
				if(trim($func) == "md5"){
					$value = md5($value);
				}
				if(trim($func) == "base64_encode"){
					$value = base64_encode($value);
				}
				if(trim($func) == "base64_decode"){
					$value  = base64_decode($value);
				}
				if(trim($func) == "htmlentities"){
					$value  = htmlentities($value);
				}
				if(trim($func) == "encode_iso"){
					$value  = \Run::$control->string->encodeIso($value);
				}
				if(trim($func) == "encode_utf8"){
					$value = \Run::$control->string->encodeUtf8($value);
				}
				if(trim($func) == "breakLines"){
					$value  = preg_replace("/(\\r)?\\n/i", "<br />", $value);
				}
			}
		}
		return $value;
	}
	//*************************************************************************************************************************
	public function checkData($schema_fields, $dataForm){
		$temp = $schema_fields;
		$dataFormCheck = array();
		foreach($temp['fields'] as $key=>$fieldParams){
			if(isset($dataForm[ $key ])){
				$dataFormCheck[ $key ] = $this->getValueCheckedArray($dataForm[ $fieldParams['fieldRef'] ], $fieldParams);
			}else if(
				$fieldParams['type'] == "date_insert" 
				|| $fieldParams['type'] == "date_update"
				|| strpos($fieldParams['type'], "file") === 0
			){
				//\Debug::p($fieldParams['fieldRef'] ." TYPE: ".$fieldParams['type']);
				$dataFormCheck[ $key ] = $this->getValueCheckedArray($dataForm[ $fieldParams['fieldRef'] ], $fieldParams);
			}
		}
		//\Debug::print_r($dataFormCheck);
		//$dataFormCheck = $this->checkEmptyDataMulti($schema_fields, $dataForm);
		//\Debug::print_r($dataFormCheck);

		return $dataFormCheck;
	}

	//*************************************************************************************************************************
	public function checkEmptyDataMulti($schema_fields, $dataForm){
		foreach($schema_fields['join'] as $keyTable=>$tableParams){
			foreach($dataForm as $keyField=>$value){
				if($keyField == $tableParams['pk']){
					if(is_array($value)){
						foreach($value as $k => $v){
							if(is_array($v)){
								foreach($v as $ki => $vi){
									//\Debug::print_r($keyField ."/".$ki ."/". (int)$vi);
									if($vi === 0 || $vi == "") unset($dataForm[$keyField][$k][$ki]);
								}
								if(count($dataForm[$keyField]) == 0){
									unset($dataForm[$keyField]);
								}
							}else{
								//\Debug::print_r($keyField ."/".$ki ."/".$vi);
								if($v === 0 || $v == "") unset($dataForm[$keyField][$k]); 
							}
						}
					}else{
						//\Debug::print_r($keyField ."/".$value);
						if($value === 0 || $value == "") unset($dataForm[$keyField]);
					}
				}
			}
		}
		return $dataForm;
	}
	//*************************************************************************************************************************
	public function checkEmptyFiles($schema_fields, $dataForm){
		$index1 = -1;
		$index2 = -1;
		foreach($schema_fields['fields'] as $key=>$fieldParams){
			if($fieldParams['type'] === "file_name"){
				foreach($dataForm[$key] as $field => $value){
					$index1 = $field;
					if(is_array($value)){
						foreach($value as $sk => $sv){
							if($sv == ""){
								$index2 = $sk;
								$dataForm = $this->removeEmptyFiles($schema_fields, $dataForm, $fieldParams['belongsTo'], $index1, $index2);
							}
						}
					}
					else if($value == "") $dataForm = $this->removeEmptyFiles($schema_fields, $fieldParams['belongsTo'], $dataForm, $index1, $index2);
				}
			}
		}
		//\Debug::p($dataForm);
		return $dataForm;
	}
	//*************************************************************************************************************************
	public function removeEmptyFiles($schema_fields, $dataForm, $table, $i1, $i2){
		foreach($schema_fields['fields'] as $key=>$fieldParams){
			if($fieldParams['belongsTo'] === $table){
				foreach($dataForm[$key] as $field => $value){
					if($i2 >= 0){
						unset($dataForm[$key][$i1][$i2]);
					}else{
						unset($dataForm[$key][$i1]);
					}
				}
			}
		}
		return $dataForm;
	}
	//*************************************************************************************************************************
	private function getValueCheckedArrayNEW($value, $paramField){
		if(is_array($value)){
			foreach($value as $k=> $val){
				if(is_array($val) && strpos($paramField['type'], "file") === ""){
					//\Debug::print_r($paramField['type'] ."|if ".strpos($paramField['type'], "file"));
					//\Debug::print_r($val);
					foreach($value as $k2=> $val2){
						$value[$k][$k2] = $this->checkSettingsValue($val2, $paramField);
					}
				}
				else{
				//	\Debug::print_r($paramField['type'] ."|else ".strpos($paramField['type'], "file"));
				//	\Debug::print_r($val);
					$value[$k] = $this->checkSettingsValue($val, $paramField);
				}
			}
			return $value;
		}else{
			return $this->checkSettingsValue($value, $paramField);
		}
	}
	//*************************************************************************************************************************
	private function getValueCheckedArray($value, $paramField){
		if(is_array($value)){
			if(isset($value['tmp_name']) && strpos($paramField['type'], "file") !== ""){
				return $this->checkSettingsValue($value, $paramField);					
			}
			foreach($value as $k=> $val){
				if(isset($val['tmp_name']) && strpos($paramField['type'], "file") !== ""){
					if(isset($val['tmp_name'][0]) && is_array($val['tmp_name'][0])){
						foreach($val as $kF=> $valF){
							//\\Debug::p($k ." // ".$kF." // ".$valF);
							$value[$k][$kF] = $this->checkSettingsValue($valF, $paramField);
						}
					}
					else $value[$k] = $this->checkSettingsValue($val, $paramField);					
				}
				else{
					$value[$k] = $this->getValueCheckedArray($val, $paramField);
				}
			}
			return $value;
		}else{
			return $this->checkSettingsValue($value, $paramField);
		}
	}



	//*************************************************************************************************************************



	public function getRequests(){
		//\Debug::log("Requests->getAll:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests 	 = array();



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
		// jogando arquivos para pasta tmp e array _POST_FILES
		$_POST_FILES = array();
		//\Debug::p($requests);
		foreach($_FILES as $key => $val){
			foreach($val as $keyParam => $valParam){
				if(is_array($valParam)){
					foreach($valParam as $keyIndex => $valIndex){
						if(is_array($valIndex)){
							foreach($valIndex as $keyIndexs => $valIndexs){
								if($keyParam == "tmp_name" && basename($valIndexs) != ""){
									if(!strpos($valIndexs,FILES_PATH)) \Run::$control->file->moveUploadedFile($valIndexs, basename($valIndexs), "tmp/");
									$_POST_FILES[$key][$keyIndex][$keyIndexs][$keyParam] = FILES_PATH."tmp/".basename($valIndexs);
								}else $_POST_FILES[$key][$keyIndex][$keyIndexs][$keyParam] = ($valIndexs);
							}
						}else{
							if($keyParam == "tmp_name" && basename($valIndex) != ""){
								if(!strpos($valIndex,FILES_PATH)) \Run::$control->file->moveUploadedFile($valIndex, basename($valIndex), "tmp/");
								$_POST_FILES[$key][$keyIndex][$keyParam] = FILES_PATH."tmp/".basename($valIndex);
							}else $_POST_FILES[$key][$keyIndex][$keyParam] = ($valIndex);
						}
					}
				}else{
					if($keyParam == "tmp_name" && basename($valParam) != ""){
						if(!strpos($valParam,FILES_PATH)) \Run::$control->file->moveUploadedFile($valParam, basename($valParam), "tmp/");
						$_POST_FILES[$key][$keyParam] = FILES_PATH."tmp/".basename($valParam);
					}else $_POST_FILES[$key][$keyParam] = ($valParam);
				}
			}
		}
		$_FILES = $_POST_FILES;
		\Debug::p($_FILES);


		//-------------------------------------------------------------------------------------------
		// pegando arquivos enviados anteriormente e guardados na sessão.
		$this->formId = $this->settings['form_id']."_". $this->getFormId($requests);
		$tmp_files = \Run::$session->get(array("forms", $this->formId, "dataFiles"));

		//\Debug::p($this->schema['from'][0]['pk'],$this->formId);
		
		foreach($tmp_files as $key => $params){
			if(isset($params['name'])){
				if($_POST_FILES[$key]['name'] == ""){
					$_POST_FILES[$key] = $tmp_files[$key];
					if(count($requests) > 2 && in_array(basename($tmp_files[$key]['tmp_name'], ".tmp"), $requests['tmpf'])){
						if(file_exists($tmp_files[$key]['tmp_name'])){
							$_POST_FILES[$key] = $tmp_files[$key];
						}else{
							\Run::$session->del(array("forms", $this->formId, "dataForm", $key));
							//\Run::$session->del(array("forms", $this->formId, "dataFiles", $key));
							array_push($this->dataErrors, "O arquivo <b>". $tmp_files[$key]['name'] ."</b> não está salvo no servidor.");
						}
					}else if(count($requests) > 2){
						\Run::$control->file->deleteFile($tmp_files[$key]['tmp_name']);
					}
				}
			}else{
				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// pegando arquivos que vieram do selectdata e enviados por inputs com o nome do arquivo + _ref
				// contando arquivos _referenciados para os arquivos reais em tmp serem contados de forma correta
				$n = 0;
				$nnRef = array();
				//\Debug::p($_REQUEST[$key.'_ref']);
				foreach($_REQUEST[$key.'_ref'] as $keyRef => $valRef){
					if(is_array($valRef)){
						foreach($valRef as $keyRefInt => $valRefInt){
							if(!is_int($nnRef[$keyRef])) $nnRef[$keyRef] = 0;
							$nnRef[$keyRef]++;
						}
					}else{
						$n++;
					}
				}
				//$n++;
				//$nnRef++;
				//\Debug::p($params);
				//\Debug::p($n, $nnRef);
				//\Debug::p($tmp_files[$key]);
				//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				// foreach para verificar consistencia dos arquivos
				// Verificando se o arquivo na session foi apagado no form
				// verificando se o arquivo na session está no form mas foi apagado do tmp do servidor
				foreach($params as $keyParam => $valParam){
					// verificação de arquivo sem array
					if(isset($valParam['name'])){
						if($_POST_FILES[$key][$n]['name'] == ""){
							if(count($requests) > 2 && in_array(basename($tmp_files[$key][$keyParam]['tmp_name'], ".tmp"), $requests['tmpf'])){
								if(file_exists($tmp_files[$key][$keyParam]['tmp_name'])){
									if($nn != "") $_POST_FILES[$key][$keyParam][$nn] = $tmp_files[$key][$keyParam];
									else $_POST_FILES[$key][$keyParam]= $tmp_files[$key][$keyParam];
									\Debug::p("adicionando FILE / $key / $keyParam / $nn ", $_POST_FILES[$key] );
								}else{									
									\Run::$session->del(array("forms", $this->formId, "dataForm", $key, $keyParam));
									array_push($this->dataErrors, "O arquivo <b>". $tmp_files[$key][$keyParam]['name'] ."</b> não está salvo no servidor.");
								}
								$n++;
							}else if(count($requests) > 2){
								\Run::$control->file->deleteFile($tmp_files[$key][$keyParam]['tmp_name']);
							}
						}
					}else{
						if(!is_int($nnRef[$keyParam])) $nnRef[$keyParam] = 0;
						$nn = $nnRef[$keyParam];
						// verificação de arquivo em array (arquivo[n][n])
						foreach($valParam as $keyParam2 => $valParam2){
							if(isset($valParam2['name'])){
								if($_POST_FILES[$key][$keyParam][$nn]['name'] == ""){
									\Debug::p(basename($tmp_files[$key][$keyParam][$keyParam2]['tmp_name'], ".tmp"), $requests['tmpf']);
									if(count($requests) > 2 && in_array(basename($tmp_files[$key][$keyParam][$keyParam2]['tmp_name'], ".tmp"), $requests['tmpf'])){
										if(file_exists($tmp_files[$key][$keyParam][$keyParam2]['tmp_name'])){
											\Debug::p("caiu ali");
											$_POST_FILES[$key][$keyParam][$nn] = $tmp_files[$key][$keyParam][$keyParam2];
										}else{
											\Debug::p("caiu aqui");
											\Run::$session->del(array("forms", $this->formId, "dataForm", $key, $keyParam, $keyParam2));
											array_push($this->dataErrors, "O arquivo <b>". $tmp_files[$key][$keyParam][$keyParam2]['name'] ."</b> não está salvo no servidor.");
										}
										$nn++;
									}else if(count($requests) > 2){
										\Run::$control->file->deleteFile($tmp_files[$key][$keyParam][$keyParam2]['tmp_name']);
									}
								}
							}
						}
					}
				}
			}
		}
		\Debug::p($_POST_FILES);
		//exit;
		$this->_POST_FILES = array();
		$_FILES = $_POST_FILES;


		//-------------------------------------------------------------------------------------------
		// verificando se arquivos não estão vazios e jogando para request
		foreach($_FILES as $key => $val){
			foreach($val as $keyParam => $valParam){
				if(is_array($valParam)){
					foreach($valParam as $keyIndex => $valIndex){
						if(is_array($valIndex)){
							\Debug::p("AAA", $valIndex);
							foreach($valIndex as $keyIndexs => $valIndexs){
								if($keyIndexs == "name" && $valIndexs == ""){
									continue 2;
								}else{
									$this->_POST_FILES[$key][$keyParam][$keyIndex][$keyIndexs] = $_FILES[$key][$keyParam][$keyIndex][$keyIndexs];
									$requests[$key][$keyParam][$keyIndex][$keyIndexs] = $_FILES[$key][$keyParam][$keyIndex][$keyIndexs];
								}
							}
						}else{
							\Debug::p("BBB", $valParam);
							if($valParam['name'] == ""){
								continue;
							}else{
								$this->_POST_FILES[$key][$keyParam][$keyIndex] = $valIndex;
								$requests[$key][$keyParam][$keyIndex] = $valIndex;
							}
						}
					}
				}else{
					\Debug::p("CCC", $valParam);
					if($val['name'] == ""){
						continue;
					}else{
					\Debug::p("CCC", $valParam);
						$this->_POST_FILES[$key][$keyParam] = $valParam;
						$requests[$key][$keyParam] = $valParam;
					}
				}
			}
		}
		\Debug::p($this->_POST_FILES);
		//exit;
		//-------------------------------------------------------------------------------------------
		//\Debug::p($requests);
		if($this->settings['decode_utf8'] === true) $requests = $this->decodeUTF8($requests);

		//\Run::$DEBUG_PRINT = 1;
		\Debug::p("REQUESTS ", $requests);
		\Debug::p("_POST_FILES ", $this->_POST_FILES);
		\Debug::p("FILES ", $_FILES);
		\Debug::p($tmp_files);
		//exit;
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
		if($this->settings['decode_utf8'] === true) $requests = $this->decodeUTF8($requests);
		//if($this->settings['encode_utf8'] === true) $requests = \Run::$control->string->mixed_to_utf8($requests);
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
		if($this->settings['decode_utf8'] === true) $requests = $this->decodeUTF8($requests);
		//if($this->settings['encode_utf8'] === true) $requests = \Run::$control->string->mixed_to_utf8($requests);
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
		if($this->settings['decode_utf8'] === true) $requests = $this->decodeUTF8($requests);
		//if($this->settings['encode_utf8'] === true) $requests = \Run::$control->string->mixed_to_utf8($requests);
		return $requests;
	}
	//*************************************************************************************************************************
	public function getFILES(){
		Debug::log("Requests->getFILES:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$requests = array();
		//-------------------------------------------------------------------------------------------
		foreach($_FILES as $key => $val){
			if(is_array($val)){
				foreach($val as $k2 => $v2){ $val[$k2] = ($v2); }
				$requests[$key] = $val;
			}
			else $requests[$key] = ($val);
		}
		if($this->settings['decode_utf8'] === true) $requests = $this->decodeUTF8($requests);
		//if($this->settings['encode_utf8'] === true) $requests = \Run::$control->string->mixed_to_utf8($requests);
		return $requests;
	}
}
// ############################################################################################################################
}
?>