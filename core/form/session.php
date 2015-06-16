<?php
// ****************************************************************************************************************************
class SessionForm{

	public 			 $model 				= array();
	//*************************************************************************************************************************
	function SessionForm($model){
		Debug::log("Iniciando Core/Form/SessionForm.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->model = $model;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getFormSessionId(){ // método para guardar o dataForm na sessão
		$form_data = $this->model->dataIntern;
		//Debug::p("formID ".$formID, $form_data);
		//Debug::p("formID ".$formID, $_REQUEST);
		if(!isset($form_data[$this->model->schema['from'][0]['pk']]) && !isset($form_data["ref"]) ) $form_data = $_REQUEST;
		//Debug::p("formID ".$formID, $form_data);
		$form_data = ( isset($form_data[$this->model->schema['from'][0]['pk']]) ) ? $form_data[$this->model->schema['from'][0]['pk']] : $this->model->dataIntern["ref"] ;
		$formID = $this->model->settings['form_id']."_".$form_data;
		//echo "<br clear='all' /><p>   >>>>>>>>>>> $formID </p>";
		//Debug::p("formID ".$formID, $form_data);
		//Debug::p("dataFormSequencial ", $dataFormSequencial);
		return $formID;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setPKListSession($pkList){ // método para guardar o dataForm na sessão
		Run::$session->set(array("forms", $this->getFormSessionId(), "pkList"), 	$pkList);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function setDataSession(){ // método para guardar o dataForm na sessão
		//Debug::p("setDataSession:".$this->getFormSessionId(), $dataFormSequencial);
		//Debug::p("setDataSession:".$this->getFormSessionId(), $this->model->dataErrors);
		Run::$session->set(array("forms", $this->getFormSessionId(), "dataForm"), 	$this->model->dataFormSequencial);
		Run::$session->set(array("forms", $this->getFormSessionId(), "pkList"), 	$this->model->pkList);
		Run::$session->set(array("forms", $this->getFormSessionId(), "dataFiles"), 	$this->model->data->_POST_FILES);
		Run::$session->set(array("forms", $this->getFormSessionId(), "dataErrors"),	$this->model->dataErrors);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getDataSession(){ // método para pegar o dataForm na sessão
		$dataForm = Run::$session->get(array("forms", $this->getFormSessionId(), "dataForm"));
		if(!(is_array($dataForm) && count($dataForm) > 1)) $dataForm = false;
		Debug::p("getDataSession", $dataForm);
		return $dataForm;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getPKListSession(){ // método para pegar o dataForm na sessão
		$dataForm = Run::$session->get(array("forms", $this->getFormSessionId(), "pkList"));
		if(!(is_array($dataForm) && count($dataForm) > 1)) $dataForm = false;
		Debug::p("getPKListSession", $dataForm);
		return $dataForm;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getErrorsSession(){ // método para pegar o dataForm na sessão
		$dataForm = Run::$session->get(array("forms", $this->getFormSessionId(), "dataErrors"));
		if(!(is_array($dataForm) && count($dataForm) > 0)) $dataForm = false;
		return $dataForm;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function delDataSession(){ // método para deletar o dataForm na sessão
		Debug::print_r("delDataSession");
		Run::$session->del(array("forms", $this->getFormSessionId()));
	}
}
// ############################################################################################################################
?>