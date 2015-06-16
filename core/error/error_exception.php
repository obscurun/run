<?php
// ****************************************************************************************************************************
 class Erro extends Exception{
	//-------------------------------------------------------------------------------------------------------------------------
     private $cod		= 0,
             $msg		= "",
             $erromsg  	= "";
	//-------------------------------------------------------------------------------------------------------------------------
	public function __construct( $cod = 0 , $msg="Ocorreu um erro." , $erromsg="Erro padrão." ) {
		$this->cod		= $cod;
		$this->msg 		= $msg;
		$this->erromsg	= $erromsg; 
		parent::__construct( $msg , $cod );
		$this->writeLog(); 
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function showError($msg = "Erro encontrado.") {
		echo "<div class='error_exception'>". $msg ."</div>";
		//echo	"<div class='error_exception'>". $this->msg ."</div>\r\n<!-- Erro:: ". $this->cod ." -- ". $this->erromsg ." -->";
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getError() {
		return	"<hr><pre>" .
				"<br>" 		.
				"Erro[ {$this->cod} ] {$this->msg}<br>{$this->erromsg}" .
				"</pre></hr>";
	}
	//-------------------------------------------------------------------------------------------------------------------------
	private function writeLog() {
      	if(Config::$ERROR_REC_LOG === true){
			$msgRec  = "<log>\r\n";
			$msgRec .= "\t<cod>" 			. $this->cod 					. "</cod>\r\n";
			$msgRec .= "\t<msg>" 			. $this->msg 					. "</msg>\r\n";
			$msgRec .= "\t<erromsg>" 		. $this->erromsg				. "</erromsg>\r\n";
			$msgRec .= "\t<date>" 			. date("d-m-Y H:i:s")			. "</date>\r\n";
			$msgRec .= "\t<ip>" 			. $_SERVER['REMOTE_ADDR']		. "</ip>\r\n";
			$msgRec .= "\t<client>" 		. $_SERVER['HTTP_USER_AGENT']	. "</client>\r\n";
			$msgRec .= "\t<memory>" 		. memory_get_usage()			. "</memory>\r\n";
			$msgRec .= "</log>\r\n";
			error_log($msgRec, 3, APP_PATH."run_app/_php_exceptions.log");
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	static public function throwError( $cod , $msg){
		throw new Error($cod , $msg);
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ****************************************************************************************************************************
?>