<?php
//Run::$DEBUG_PRINT = 1;
require_once(RUN_PATH."core/form/order_data.php");
// ****************************************************************************************************************************
class SaveData{
	private $model 			= NULL;
	private $settings 			= NULL;
	private $dataInt 			= array();
	private $schema_pk_list 	= array();
	private $query_errors 		= 0;
	private $dataDeletes 		= array();
	private $database 			= NULL;
	private $query 				= NULL;
	private $dataDeletesDate	= array();
	private $orderData 			= NULL;
	public 	$dataErrors 		= array();
	//*************************************************************************************************************************
	function saveData($model, $database, $query){
		//Debug::log("Iniciando Core/Form/Save.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->query_errors = 0;
		$this->database 	= $database;
		$this->query 		= $query;
		$this->orderData 	= new OrderData();
		$this->model 		= $model;
	}
	//*************************************************************************************************************************
	public function save($data, $dataIntern, $schema, $settings){
		$this->settings = $settings;
		$this->query_errors = 0;
		$data = $this->prepareSave($data, $schema);
		//exit;
		//Debug::p($this->dataErrors);
		return $data;
	}
	//*************************************************************************************************************************
	public function prepareSave($data, $schema){	
		Debug::p("prepareSaveDATA", $data);
		if(isset($data[$schema['from'][0]['pk']])) $schema_pk_list[$this->settings['pk']] = $data[$schema['from'][0]['pk']];
		foreach($schema['join'] as $k => $a){
			if(isset($data[$a['pk']])) $schema_pk_list[$a['pk']] = $data[$a['pk']];
		}
		$dataSave = $this->buildDataSave($data, $schema);
		//Debug::print_r("_POST", $_POST);
		//Debug::print_r($schema['fields']);
		//Debug::print_r("DATA", $data);
		$orderTables = $this->orderData->getOrderedTables($data, $schema);
		//Debug::print_r($dataSave);
		foreach($orderTables as $table => $childrens){
			$dataSaved = $this->buildSaveSQL($orderTables, $dataSave, $schema);
		}
		//exit;
		return $dataSaved;
		//Debug::p($dataSaved);
	}
	//*************************************************************************************************************************
	//*************************************************************************************************************************
	private function buildSaveSQL($orderTables, $dataTables, $schema, $refTableIndex=false, $refPkId=0){
		//Run::$DEBUG_PRINT = 1;

		$fieldsRec = array();
		//Debug::print_r($orderTables);
		foreach($orderTables as $k => $refs){
			if(!isset($refs['table'])) continue;
			$this->deletePKFromMultipleRegistersTable($refs, $k, $schema);
			$data = $dataTables[$k];
			$tableIsMultiple = false;
			if(isset($refs['multiple']) && $refs['multiple'] === true){
				//$dataCheck			= (is_array($data[$refTableIndex])) ? $data[$refTableIndex] : $data;
				//$totalMultiple 		= $this->getTotalMultipleFieldsBytable($k, $dataCheck, $schema, $refs['pk'], $refTableIndex);
				$dataLevel 		= $this->getLevelDataMulti($data);
				$dataIndexs 	= $this->getIndexsMultipleFieldsByTable($k, $data, $schema, $refs['pk'], $refTableIndex, $refPkId, $dataLevel);
				$totalMultiple 	= count($dataIndexs);
				//Debug::print_r("dataIndexs Array:: ",$dataIndexs);
				$tableIsMultiple 	= true;
			}
			else $totalMultiple 	= 1;
			Debug::print_r("buildSaveSQL $k | refTableIndex : $refTableIndex | refPkId : $refPkId - - - - - - - - - - SAVE {$refs['table']}: $k  / multiplos: ".$totalMultiple ." - - refTableIndex: $refTableIndex- - - - - - - - - - - - - - - - -");

			//reset($data);
			for($i=0 ; $i<$totalMultiple ; $i++){
				$reIndexed = $dataIndexs[$i];
				$fields 		= "";
				$values			= "";
				$update_fields 	= "";

				if(!$tableIsMultiple){
					$dataPK	= $data[ $refs['pk'] ];
				}else{
					if($dataLevel == 2)  	 $dataPK = $data[$reIndexed][$refs['pk']];
					else if($dataLevel == 3) $dataPK = $data[$refTableIndex][$reIndexed][$refs['pk']];				
				}
				//Debug::print_r(" data:", $data);
				//Debug::print_r("dataPK $k : ".$dataPK ." // dataLevel:".$dataLevel);
				$typeSave 		= ($dataPK > 0) ? "update" : "insert";
				$saveFileData   = array("index"=>false, "name"=>false, "fieldRef"=> false, "path"=> false);
				//Debug::print_r("for $i / dataPK: $dataPK ");
				//Debug::print_r($data[$i]);
				// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				if(isset($refs['fk_ref'])){
					$fields 	.= ",\n\t". $refs['fk_ref'] ."";
					$values		.= ",\n\t\"". $orderTables['fk_ref_value'] ."\"";
					$update_fields .= ",\n\t". $refs['fk_ref'] . " = \"". $orderTables['fk_ref_value'] ."\"";
				}
				// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				$skipRec = false;
				foreach($schema['fields'] as $kF => $field){
					if($field['belongsTo'] == $k && (($dataPK > 0 && $kF == $refs['pk']) || $kF != $refs['pk']) ){
						if( 
							($typeSave == "insert" && $field[$typeSave] !== false) || ($typeSave == "update" && $field[$typeSave] !== false) 
						){
							$field_name  = $field['name']!="" ? $field['name'] : $kF;
							$fields 	.= ",\n\t". $field_name ."";
							if($tableIsMultiple){
								//if($k == "et")	//Debug::print_r($data[$refTableIndex][$i]);
								//if($k == "et")	//Debug::print_r("$k /kF $kF /i $i =".$data[$refTableIndex][$i][$kF]);
								if(	$field['type'] == "date_insert" || $field['type'] == "date_update" ){
									$data_multiple = $data[$kF];
								}
								else if($field['type'] == "int" || $field['type'] == "integer"){
									$data_multiple = (is_array($data[$refTableIndex][$reIndexed])) ? $data[$refTableIndex][$reIndexed][$kF] : (int)$data[$reIndexed][$kF];
								}else{
									$data_multiple = (is_array($data[$refTableIndex][$reIndexed])) ? $data[$refTableIndex][$reIndexed][$kF] : $data[$reIndexed][$kF];
									//Debug::p("data ", $data);
									//Debug::p("data_multiple  / $reIndexed / $kF /".$data[$reIndexed][$kF], $data);
									//Debug::p("data_multiple ", $data[$reIndexed][$kF]);
								}
								if($field['skipRecEmpty'] === true && $this->getValueArray($data_multiple) == ""){
									continue 2;
								}
								if($field['skipFieldEmpty'] === true && $this->getValueArray($data_multiple) == ""){
									continue;
								}
								$values	.= ",\n\t\"". $this->getValueArray($data_multiple) ."\"";
								$update_fields .= ",\n\t". $field_name . " = \"". $this->getValueArray($data_multiple) ."\"";
								if($field['type'] == "file_name"){ $saveFileData['name'] = $this->getValueArray($data_multiple); }
								if($field['type'] == "file_path"){ 
									$saveFileData['index'] 		= $reIndexed; 
									$saveFileData['fieldRef'] 	= $field['fieldRef']; 
									$saveFileData['path'] 		= $this->getValueArray($data_multiple);
									//Debug::p("saveFileData", $saveFileData);
								}
								if($field['type'] == "file_path" && $saveFileData['path'] == $saveFileData['name'] && (!is_array($data_multiple) )) continue 2;

								//Debug::p("add: ($reIndexed)".$field_name ." / PK: ". $dataPK ." /kF = ". $kF ." /Total = ". $totalMultiple ." /reIndexed = ".$reIndexed);
								//if($kF == "tipo") //Debug::p("$i $kF :".$data_multiple);
								//if($kF == "tipo") //Debug::p($data[$refTableIndex][$i][$kF]);
							}
							else{
								if($field['type'] == "file_name"){ $saveFileData['name'] = $data[$kF]; }
								if($field['type'] == "file_path"){ 
									$saveFileData['index'] 		= $reIndexed; 
									$saveFileData['fieldRef'] 	= $field['fieldRef']; 
									$saveFileData['path'] 		= $data[$kF];
									//Debug::p("saveFileData", $saveFileData);
								}
								if($field['type'] == "file_path" && $saveFileData['path'] == $saveFileData['name'] && (!is_array($data_multiple) )) continue 2;
								
								if($field['skipRecEmpty'] === true && $data[$kF] == ""){
									continue 2;
								}
								if($field['skipFieldEmpty'] === true && $data[$kF] == ""){
									continue;
								}
								if($field['type'] == "int" || $field['type'] == "integer"){
									$data[$kF] = (int)$data[$kF];
								}
								$values	.= ",\n\t\"". $data[$kF] ."\"";
								if($field['skipFieldEmpty'] !== true || ($field['skipFieldEmpty'] === true && $data[$kF] != $field['value'])) $update_fields .= ",\n\t". $field_name . " = \"". $data[$kF] ."\"";
							}
						}
					}
				}
				// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
				$fields = substr($fields, 2, strlen($fields));
				$values = substr($values, 2, strlen($values));
				$update_fields = substr($update_fields, 2, strlen($update_fields));
				//Run::$DEBUG_PRINT = 1;
				Debug::p("data", $data);
				Debug::p("update_fields", $update_fields);
				if($dataPK > 0){
					// não pode ser com replace, pois os campos não atualizados são apagados do registro.
					//$sql_query = "REPLACE INTO ". $refs['table'] ." \n(\n".$fields ."\n) \nVALUES(\n".$values."\n)";
					$sql_query = "UPDATE ". $refs['table'] ." SET \n".$update_fields ."\n \n WHERE \n\t {$refs['pk']} = $dataPK \n";
				}else{
					$sql_query = "INSERT INTO ". $refs['table'] ." \n(\n".$fields ."\n) \nVALUES(\n".$values."\n)";
				}
				Debug::p("sql_query", $sql_query);
				if($saveFileData['name'] === "" && $saveFileData['path'] !== false ) continue;
				//	//Debug::p("pqp ------------{$saveFileData['name']}------------------------- {$saveFileData['path']} ");
				$sql_obj = $this->database->query($sql_query, __LINE__, __FUNCTION__, __CLASS__, __FILE__, $this->settings['database_connection']);
				$warMsg = $this->database->getMysqlWarning();
				if(is_integer($sql_obj) || $warMsg != ""){ 
					$this->query_errors++;  
					Error::show(5200, "Model-> Erro no SQL:\n ".$warMsg."\n  ". $this->database->getMysqlError() ."  \n$sql_query".__FUNCTION__, __FILE__, __LINE__, '');
				}
				else{
					//Debug::log("Model: SQL Executado com sucesso (returnID:". $this->database->getID($this->settings['database_connection']) ."): \n $sql_query", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
				}
				if($dataPK > 0){
					$id_pf_ref = $data[ $refs['pk'] ] = $dataPK;
				}
				else $id_pf_ref  = $this->database->getID($this->settings['database_connection']);
					
				if(isset($dataTables[$k][$refTableIndex][$reIndexed][ $refs['pk'] ])){
					$dataTables[$k][$refTableIndex][$reIndexed][ $refs['pk'] ] = $id_pf_ref;
				}
				else if($tableIsMultiple){
					$dataTables[$k][$reIndexed][$refs['pk']] = $id_pf_ref;			
				}
				else $dataTables[$k][ $refs['pk'] ] = $id_pf_ref; 

				//Debug::print_r("- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ".$refs['pk'] ." = " . $id_pf_ref ." -- totalMultiple: ".$totalMultiple);
				//Debug::print_r($sql_query);

				if($this->query_errors < 1){
					Debug::p("TESTE", $saveFileData);
					$refs["joineds"]['fk_ref_value'] = $id_pf_ref;
					if($reIndexed == "") $reIndexed = (int)$reIndexed;
					Debug::p("TESTE2", $saveFileData);
					Debug::p("TESTE3", $saveFileData);
					Debug::p("FILE SAVE:", $_FILES[$saveFileData['fieldRef']]['tmp_name']);
					if($saveFileData['path'] !== false && $saveFileData['name'] !== false && $saveFileData['name'] !== "" && $saveFileData['index'] !== false && ( isset($_FILES[$saveFileData['fieldRef']][$refTableIndex][$saveFileData['index']]['tmp_name']) || isset($_FILES[$saveFileData['fieldRef']][$saveFileData['index']]['tmp_name']) ) || (isset($_FILES[$saveFileData['fieldRef']]['tmp_name']) && !is_array($_FILES[$saveFileData['fieldRef']]['tmp_name']) && $_FILES[$saveFileData['fieldRef']]['tmp_name'] != "" ) ){
						Debug::p("vai salvar", $id_pf_ref);
						$tmp_file 	= (!is_string($_FILES[$saveFileData['fieldRef']][(int)$refTableIndex]['tmp_name'])) ? ($_FILES[$saveFileData['fieldRef']][$refTableIndex][$saveFileData['index']]['tmp_name']) : $_FILES[$saveFileData['fieldRef']][$saveFileData['index']]['tmp_name'];
						if($saveFileData['index'] == "") $tmp_file 	= ($_FILES[$saveFileData['fieldRef']]['tmp_name']);
						Debug::p("tmp_file", $tmp_file);
						if((int) $refPkId == 0) $refPkId = $id_pf_ref;
						$salvou		= false;
						if($tmp_file != ""){
							$salvou	= Run::$control->file->saveFile($tmp_file, Run::$control->string->pad($id_pf_ref, $this->settings['default_pad'], "0", STR_PAD_LEFT)."-".$saveFileData['name'], $saveFileData['path'].$refPkId, "");
							Debug::p("FILE SAVE CHECK ", $salvou);
							if($salvou === false) array_push( $this->dataErrors, "O arquivo ". $saveFileData['name'] ." não pode ser salvo.");
						}
					}
					$dataTables = $this->buildSaveSQL( $refs["joineds"], $dataTables, $schema, $reIndexed, $id_pf_ref);
				}else{
					array_push( $this->dataErrors, "Ocorreu um erro ao gravar os dados da tabela ". $refs['table'] .".");
				}
			}
		}
		//Run::$DEBUG_PRINT = 0;
		//exit;
		return $dataTables;
	}
	//*************************************************************************************************************************
	private function getValueArray($value){
		if(is_array($value)){
			foreach($value as $k=> $val){
				$value[$k] = $this->getValueArray($val);
			}
		}else{
			return $value;
		}
	}
	//*************************************************************************************************************************
	private function getTotalMultipleFieldsBytable($k, $data, $schema, $ignorePk, $refTableIndex){
		$contagens = array();
		$i = 0;
		$iT = 0;
		//Debug::print_r($data);
		foreach($data as $field => $values){
			//Debug::print_r($values);
			//Debug::print_r("IF $k >>>> ". current(array_keys($values)) ." ==== ". $ignorePk); // reset($array) = first value
			if(is_array($values)){ //if(array_key_exists($values, $ignorePk)){			//if(is_array($values) && current(array_keys($values)) === $ignorePk){
				//Debug::print_r("IFED $k >>>> ". $ignorePk ." = ". isset($values[$ignorePk]));
				$iT = 0;
				foreach($values as $kv => $fieldName){
					if($schema['fields'][$fieldName]['type'] != "date_insert" && $schema['fields'][$fieldName]['type'] != "date_update" ){
						//Debug::print_r("CHECK: ".$kv ." === ". $ignorePk);
						if($kv === $ignorePk) continue;
				 		//Debug::print_r("CONTANDO: K: $k > KV: ".$kv ." FIELD: ". $fieldName);
						$iT++;
					}
				}
				if($iT > 0 ) $i++;
			}else{
				if($schema['fields'][$field]['type'] != "date_insert" && $schema['fields'][$field]['type'] != "date_update" ){
					if($ignorePk !== false && $field === $ignorePk) continue;
				 	//Debug::print_r("CONTANDO: K: $k > values: ".$values ." FIELD: ". $field);
					$i++;
				}
			}
		}
		return $i;
	}
	//*************************************************************************************************************************
	private function getIndexsMultipleFieldsByTable($k, $data, $schema, $ignorePk, $refTableIndex, $refPkId, $level){
		$array_keys = array_keys($data);
		$refLevel = $refTableIndex;
		//Debug::p("getIndexsMultipleFieldsByTable refLevel:".$level, $array_keys);
		if($level == 2){
			$dataT = $data;
		}
		if($level == 3){
			$dataT = $data[$refTableIndex];
		}

		/*
		if( !isset($data[$refTableIndex]) && (int)$refTableIndex < 1){
			$dataT = current($data);
			//Debug::p("getIndexsMultipleFieldsByTable refLevel:".$refLevel, $data);
			if(count($dataT) == 0) $dataT = $data[$refTableIndex];
		}else{
			$dataT = $data[$refTableIndex];
		}
		*/
		//Debug::p($ignorePk ." /ref ".$refTableIndex);
		//Debug::p($data[$refTableIndex]);
		//Debug::p($array_keys);
		if(in_array($ignorePk, $data)){
			return array();
		}else{
			$array_keys = array_keys($dataT);
			//Debug::p($data);
			//Debug::p($data[$refTableIndex]);
			//Debug::p("getIndexsMultipleFieldsByTable $refTableIndex data",$dataT);
			//Debug::p("getIndexsMultipleFieldsByTable $refTableIndex array_keys",$array_keys);
			//Debug::p("getIndexsMultipleFieldsByTable","getLevelDataMulti pk($ignorePk): ".$this->getLevelDataMulti($dataT));
			if($this->getLevelDataMulti($dataT) === 2){
				$array_keys = array_keys($data);
				//Debug::p($data);
			}
			foreach($array_keys as $k=>$v){
				//Debug::p($k ." / ".$v);
				if($schema['fields'][$v]['type'] == 'date_insert' || $schema['fields'][$v]['type'] == 'date_update'){
					unset($array_keys[$k]);
				}
			}
			//Debug::p($array_keys);
			return $array_keys;
		}
	}
	//*************************************************************************************************************************
	private function getLevelDataMulti($a) {
	    foreach ($a as $v) {
	        if (is_array($v)){
	        	foreach ($v as $vI) {
		        	if (is_array($vI)) return 3;
		        }
	        }else return 2;
	    }
	    return 1;
	}
	//*************************************************************************************************************************
	private function checkEmptyIndexMultipleFieldsByTable($iField=false, $k, $data, $schema, $ignorePk=false){
		$contagens = array();
		$i = 0;
		foreach($data as $field => $value){
			//Debug::print_r("contagem $field = ".$schema['fields'][$field]['belongsTo']);
			if($schema['fields'][$field]['belongsTo'] == $k && is_array($value)){
				if($ignorePk !== false && $field == $ignorePk) continue;
				if($value[$iField] != ""){
					$i++;
					//Debug::print_r(" ADD  $k / $field [$iField] --- VALUE: ".$value[$iField] ." === " .$i);
				}
			}
		}
		return $i;
	}
	//*************************************************************************************************************************
	public function autoDeletePKs($dataForm){
		foreach($this->model->schema['from'] as $index => $paramTable){
			if($paramTable['delete_pk_empties']) $dataTable = $this->autoDeleteByPKData($paramTable, $this->model->schema, $dataForm);
		}
		foreach($this->model->schema['join'] as $index => $paramTable){
			if($paramTable['delete_pk_empties']) $dataTable = $this->autoDeleteByPKData($paramTable, $this->model->schema, $dataForm);
		}
		//exit;
	}
	//*************************************************************************************************************************
	private function autoDeleteByPKData($paramTable, $schema, $dataForm){
		$pks_to_del = array();
		$pkList = $this->model->session->getPKListSession();
		$pksChecked = array();
		$foundPkInFormData = false;
		//Debug::p("autoDeleteByPKData", $this->model->dataForm);
		//exit;
		// VERIFICAR SE VALOR $VI ESTÁ DENTRO DO ARRAY DA TABELA NO SESSION PARA ADICIONAR NO PKS_TO_DEL
		
		Debug::p("SQL_DELETE pkList:".$paramTable['table_nick'], $pkList);

		foreach($dataForm as $k=>$v){
			if($schema['fields'][$k]['name'] == $paramTable['pk']){
				$foundPkInFormData = true;
				if(is_array($v)){
					foreach($v as $ki=>$vi){
						if(is_array($vi)){
							foreach($pkList[$paramTable['table_nick']] as $pkk => $pkv){
								if(in_array($pkv, $vi)){
									array_push($pksChecked, $pkv);
									$pks_to_del = array_diff($pks_to_del, array($pkv)); // removendo ID(pkv) de pks_to_del
								}
								else if( !in_array($pkv, $vi) && !in_array($pkv, $pksChecked) && !in_array($pkv, $pks_to_del) ){
									//Debug::p("adicionado :".$paramTable['pk'] , $pkv);
									array_push($pks_to_del, $pkv );
								}
							}
						}else{
							//Debug::p("CHECK:".$paramTable['table_nick']." / $k /".$schema['fields'][$k]['name'] , $v);
							foreach($pkList[$paramTable['table_nick']] as $pk_k=>$pk_v){
								//Debug::p("in_array:".$pk_v."/".in_array($pk_v, $v)  , $v);
								if(in_array($pk_v, $v)){
									array_push($pksChecked, $pk_v );
									$pks_to_del = array_diff($pks_to_del, array($pk_v)); // removendo ID(pkv) de pks_to_del
								}
								else if( !in_array($pk_v, $v) && !in_array($pk_v, $pksChecked) && !in_array($pk_v, $pks_to_del) ){
									array_push($pks_to_del, $pk_v );
								}
							}							
						}
					}
				}else{
				}
			}
		}
		if($foundPkInFormData == false){
			$field = $this->model->selectData->findFieldByName($paramTable['pk'], $paramTable, $schema['fields']);
			if(!isset($dataForm[$field])) $pks_to_del = implode(", ", $pkList[$paramTable['table_nick']]);
		}else{
			 $pks_to_del = implode(", ", $pks_to_del);
		}

		//Debug::p("SQL_DELETE pks_to_del:".$paramTable['table_nick'], $_REQUEST);
		//Debug::p("SQL_DELETE pksChecked:".$paramTable['table_nick'], $pksChecked);
		//Debug::p("SQL_DELETE pks_to_del:".$paramTable['table_nick'], $pks_to_del);

		if($pks_to_del == "") return false;
		//return false;

		$updateTime = $this->getDateUpdateName($paramTable, $schema);
		$sql_query = "UPDATE ". $paramTable['table'] ." SET $updateTime ". $paramTable['status_name'] ."='-1'  WHERE ". $paramTable['pk'] ." IN (".$pks_to_del.")";
		//Debug::p("SQL_DELETE sql_query:", $sql_query);
		//return false;
		$sql_obj 	= $this->database->query($sql_query, __LINE__, __FUNCTION__, __CLASS__, __FILE__, $this->settings['database_connection']);
		//Debug::p($this->database->getMysqlError());
		$warMsg = $this->database->getMysqlWarning();
		if(is_integer($sql_obj) || $warMsg != ""){ 
			$this->query_errors++;  
			Error::show(5200, "Model-> Erro ao deletar multiplo registro não recebido do form:\n ".$warMsg."\n  ". $this->database->getMysqlError() ."  \n$sql_query".__FUNCTION__, __FILE__, __LINE__, '');
		}else{
			$log = "Model: SQL Executado com sucesso (returnID:". $this->database->getID($this->settings['database_connection']) ."): \n $sql_query";
			// Debug::print_r($log);
			// Debug::log($log, __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		}	
	}
	//*************************************************************************************************************************
	//delete_pk_empties = Classe SaveData deleta do servidor os registros cujo dataForm vieram pk, mas nenhum valor de outras colunas
	private function deletePKFromMultipleRegistersTable($refs, $table, $schema){
		//	//Debug::p("deletePKFromMultipleRegistersTable ".$table, $this->dataDeletes);
		foreach($this->dataDeletes[$table] as $k=>$v){
			// $sql_query 	= "DELETE FROM ". $refs['table'] ." WHERE ". $refs['pk'] ." = ". $v ."";
			// USANDO DELETE LÓGICO
			$updateTime = $this->getDateUpdateName($refs, $schema);
			$sql_query = "UPDATE ". $refs['table'] ." SET $updateTime ". $refs['status_name'] ."='-1'  WHERE ".$refs['pk']." = $v ";
			//Debug::p("SQL_DELETE", $sql_query);
			$sql_obj 	= $this->database->query($sql_query, __LINE__, __FUNCTION__, __CLASS__, __FILE__, $this->settings['database_connection']);
			//Debug::p($this->database->getMysqlError());
			$warMsg = $this->database->getMysqlWarning();
			if(is_integer($sql_obj) || $warMsg != ""){ 
				$this->query_errors++;  
				Error::show(5200, "Model-> Erro ao deletar multiplo registro não selecionado no form:\n ".$warMsg."\n  ". $this->database->getMysqlError() ."  \n$sql_query".__FUNCTION__, __FILE__, __LINE__, '');
			}else{
				$log = "Model: SQL Executado com sucesso (returnID:". $this->database->getID($this->settings['database_connection']) ."): \n $sql_query";
				// Debug::print_r($log);
				// Debug::log($log, __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			}
		}
	}
	//*************************************************************************************************************************
	private function getDateUpdateName($table, $schema){
		if(isset($this->dataDeletesDate[$table['table']])){
			return $this->dataDeletesDate[$table['table']];
		}else{
			foreach($schema['fields'] as $fieldSchema => $params){
				if($params['type'] == 'date_update' && ($params['belongsTo'] == $table['table_nick'] || $params['belongsTo'] == $table['table'])){
					$this->dataDeletesDate[$table['table']] = $params['name']."='".date("Y-m-d H:i:s")."', ";
					break;
				}
			}
			return $this->dataDeletesDate[$table['table']];
		}
	}
	//*************************************************************************************************************************
	public function buildDataSave($dataForm, $schema){	
		$dataTable = array();
		foreach($schema['from'] as $index => $paramTable){
			$dataTable = $this->eachDataSave($paramTable, $dataTable, $dataForm, $schema);
		}
		foreach($schema['join'] as $index => $paramTable){
			$dataTable = $this->eachDataSave($paramTable, $dataTable, $dataForm, $schema);
		}
		//Debug::print_r("_POST", $_POST);
		//Debug::print_r("dataForm",$dataForm);
		//Debug::print_r("DELETES: ",$this->dataDeletes);
		//Debug::print_r("dataTable", $dataTable);
		//exit;
		return $dataTable;
	}
	//*************************************************************************************************************************
	private function checkEmptyDataMulti($schema_fields, $dataForm){
		//Debug::p("checkEmptyDataMulti", $dataForm);
		foreach($schema_fields['join'] as $keyTable=>$tableParams){
			foreach($dataForm as $keyField => $value){

				if(is_array($value) && count($value) > 1){
					$foundFile = false;
					foreach($value as $kIn => $vIn){
						if($schema_fields['fields'][$kIn]['type'] == "file_name"){
							if((int)$dataForm[$tableParams['pk']][$kIn] === 0 && $dataForm[$keyField][$kIn] == ""){						
								//Debug::p("APAGANDO: ",$keyField ."/".($value)."/PK: ".$tableParams['pk']." = ".$dataForm[$tableParams['pk']]);
								//Debug::p("checkEmptyDataMulti", $dataForm[$keyField]);
								unset($dataForm[$keyField]);
								$foundFile = true;
							}
						}
					}
					if($foundFile) return $dataForm;
					$dataForm[$keyField] = $this->checkEmptyDataMulti($schema_fields, $value);
				}else{
					if($schema_fields['fields'][$keyField]['type'] == "file_name" && array_key_exists($tableParams['pk'], $dataForm)){
						if((int)$dataForm[$tableParams['pk']] === 0 && $dataForm[$keyField] == ""){						
							//Debug::p("APAGANDO: ",$keyField ."/".($value)."/PK: ".$tableParams['pk']." = ".$dataForm[$tableParams['pk']]);
							return array();
						}
					}
					foreach($value as $kI => $vI){
						if(is_array($vI)){
							foreach($vI as $kI2 => $vI2){
								if($schema_fields['fields'][$kI2]['type'] == "file_name" ){
									if((int)$dataForm[$tableParams['pk']] === 0 && $vI2 == ""){						
										$dataForm[$keyField][$kI] = array();
									}
								}
								if(count($dataForm[$keyField][$kI]) === 0){		
									//Debug::p("checkEmptyDataMulti $kI2: $vI2 /" );		
										unset($dataForm[$keyField][$kI]);
								}
							}
						}
						if($kI == $tableParams['pk'] && !is_array($vI)){
							//Debug::print_r($keyField ."/".$kI."/".($value)."/".count($value));
							//if((int)$vI === 0 ){
								unset($dataForm[$keyField][$kI]);
							//}
						}else if($kI == $tableParams['pk']){
							if(count($vI) == 1){
								unset($dataForm[$keyField][$kI]);
							}
						}
					}
				}
				if(is_array($value) && count($value) === 0){
					unset($dataForm[$keyField]);
				}
			}
		}
		return $dataForm;
	}
	//*************************************************************************************************************************
	private function checkEmptyDeletes($table, $schema_fields, $dataForm){
		$deletesList = array();
		foreach($dataForm as $k => $v){
			$level = $this->getLevelDataMulti($dataForm);
			//if($table = "et") //Debug::p($table." / ".$k." / LEVELS $level /". $this->getLevelDataMulti($dataForm), $dataForm);
			if($level === 3){
				//if($table = "et") //Debug::p($k, $v);
				foreach($v as $k2 => $v2){
					if(is_array($v2) && count($v2) == 1){
						//if($table = "et") //Debug::p($k2, current($v2));
						$indexValue = current($v2);
						if((int)$indexValue >0) array_push($deletesList, $indexValue);
					}
				}
			}
			else if($level === 2){
					if(is_array($v) && count($v) == 1){
						//if($table = "et") //Debug::p($v);
						$indexValue = current($v);
						if((int)$indexValue >0) array_push($deletesList, $indexValue);
					}
					/*
				foreach($v as $k2 => $v2){
					if(is_array($v2) && count($v2) == 1){
						array_push($deletesList, ($k2));
					}else{

					}
				} */
			}
			else if(is_array($v) && count($v) == 1){
				//if($table = "et") //Debug::p($k, current($v));
				$indexValue = current($v);
				if((int)$indexValue >0) array_push($deletesList, $indexValue);
			}
		}
		$this->dataDeletes[$table] = $deletesList;
		//if($table = "et") //Debug::print_r("DELETES $table",$deletesList);
	}
	//*************************************************************************************************************************
	private function eachDataSave($tableParams, $dataTable, $dataForm, $schema){
		foreach($schema['fields'] as $fieldSchema => $params){
			if( ($params['belongsTo'] == $tableParams['table_nick'] || $params['belongsTo'] == $tableParams['table'])){
				if(isset($dataForm[$fieldSchema])){
					if(is_array($dataForm[$fieldSchema])){
						foreach($dataForm[$fieldSchema] as $k => $value){
							if(is_array($value)){
								foreach($value as $subK => $subValue){
									//if($fieldSchema == $tableParams['pk'] && $subValue === 0 ) continue;
									$dataTable[$tableParams['table_nick']][$k][$subK][$fieldSchema] = $subValue;
								}
							}
							else $dataTable[$tableParams['table_nick']][$k][$fieldSchema] = $value ;
						}
					}
					else $dataTable[$tableParams['table_nick']][$fieldSchema] = $dataForm[$fieldSchema];
				}
			}
		}
		//Debug::print_r($dataTable[$tableParams['table_nick']]);	
		$this->checkEmptyDeletes($tableParams['table_nick'], $schema, $dataTable[$tableParams['table_nick']]);
		$dataTable[$tableParams['table_nick']] = $this->checkEmptyDataMulti($schema, $dataTable[$tableParams['table_nick']]);
		return $dataTable;
	}
	//*************************************************************************************************************************
}
// ############################################################################################################################
?>