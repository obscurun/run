<?php 
//*********************************************************************************************************************************
class Error{
	public static  $ERROR_EXECUTION		= true;
	public static  $SHOW_USER			= true;
	public static  $DEBUG				= false;
	public static  $SHOW_MSG_DEFAULT 	= true;
	public static  $ERROR_SHOW_NOTICE	= false;
	public static  $ERROR_SHOW_WARNING	= false;
	public static  $ERROR_EMAIL			= "";
	public static  $ERROR_PROJECT		= "RUN";
	public static  $SEND_MAIL		= false;	// ENVIA E-MAIL QUANDO O ERRO OCORRE
	public static  $REC_LOG			= true;		// GRAVA ERRO EM TXT .LOG NA RAIZ DA APLICAÇÃO
	public static  $REC_VAR			= true;		// GRAVA ERRO EM TXT .LOG NA RAIZ DA APLICAÇÃO
	public static  $DEBUG_MSG		= "";
	public static  $DEBUG_DATE		= "";
	public static  $ERROR_TYPE		= array (
							0 		=>	"RUN",
							1   	=>  "Error",
							2   	=>  "Warning",
							4   	=>  "Parsing Error",
							8   	=>  "Notice",
							16  	=>  "Core Error",
							32  	=>  "Core Warning",
							64  	=>  "Compile Error",
							128 	=>  "Compile Warning",
							256 	=>  "User Error",
							512 	=>  "User Warning",
							1024	=>  "User Notice",
							1025	=>  "Sql",
							2048	=>  "E_Strict - Sugestões do PHP",
							5200	=>  "Model",
							5552	=>  "Mysql Query Error",
							5553	=>  "Mysql MultiQuery Error",
							4096	=>  "E_RECOVERABLE_ERROR",
							8191	=>  "E_ALL",
							8192	=>  "E_ALL"
							);
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){
		if(isset(Config::$ERROR_EXECUTION)) 		Error::$ERROR_EXECUTION			= Config::$ERROR_EXECUTION;
		if(isset(Config::$ERROR_SHOW_USER))  		Error::$SHOW_USER 				= Config::$ERROR_SHOW_USER;
		if(isset(Config::$ERROR_SHOW_DEBUG)) 		Error::$DEBUG 					= Config::$ERROR_SHOW_DEBUG;
		if(isset(Config::$ERROR_SHOW_MSG_DEFAULT))  Error::$SHOW_MSG_DEFAULT 		= Config::$ERROR_SHOW_MSG_DEFAULT;
		if(isset(Config::$ERROR_REC_LOG)) 			Error::$REC_LOG 				= Config::$ERROR_REC_LOG;
		if(isset(Config::$ERROR_REC_VAR)) 			Error::$REC_VAR 				= Config::$ERROR_REC_VAR;
		if(isset(Config::$ERROR_SHOW_NOTICE)) 		Error::$ERROR_SHOW_NOTICE 		= Config::$ERROR_SHOW_NOTICE;
		if(isset(Config::$ERROR_SHOW_WARNING)) 		Error::$ERROR_SHOW_WARNING 		= Config::$ERROR_SHOW_WARNING;
		if(isset(Config::$ERROR_EMAIL)) 			Error::$ERROR_EMAIL 			= Config::$ERROR_EMAIL;
		if(isset(Config::$ERROR_SEND_EMAIL)) 		Error::$SEND_MAIL 				= Config::$ERROR_SEND_EMAIL;
		if(isset(Config::$NAME)) 					Error::$ERROR_PROJECT 			= Config::$NAME ." - ". Config::$TITLE;
		if(Error::$ERROR_EXECUTION == false) error_reporting(1);
		if(Error::$ERROR_EXECUTION == true) ini_set('display_errors', true);
		else ini_set('display_errors', false);
		if(Error::$ERROR_EXECUTION == true){
			$old_error_handler = set_error_handler(array(&$this, 'show'));
		}
	}
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	static public function show($_error_num=0, $_error_msg="Erro encontrado", $_error_file="", $_error_line="", $_error_vars=""){
		self::$DEBUG_DATE = @date("d-m-Y H:i:s");

		if(! ((self::$ERROR_SHOW_NOTICE == false && $_error_num == 8) || (self::$ERROR_SHOW_WARNING == false && $_error_num == 2)) ){
			$msgRec  = "\r\n<errorentry>\r\n";
			$msgRec .= "\t <type>"	 		. self::$ERROR_TYPE[$_error_num] 			. "</type>\r\n";
			$msgRec .= "\t <msg>" 			. $_error_msg 								. "</msg>\r\n";
			$msgRec .= "\t <file>" 			. $_error_file 								. "</file>\r\n";
			$msgRec .= "\t <uri>" 			. $_SERVER['REQUEST_URI']					. "</uri>\r\n";
			$msgRec .= "\t <line>" 			. $_error_line 								. "</line>\r\n";
			$msgRec .= "\t <num>" 			. $_error_num 								. "</num>\r\n";
			$msgRec .= "\t <date>" 			. self::$DEBUG_DATE							. "</date>\r\n";
			$msgRec .= "\t <ip>" 			. $_SERVER['REMOTE_ADDR'] 					. "</ip>\r\n";
			$msgRec .= "\t <client>" 		. $_SERVER['HTTP_USER_AGENT']				. "</client>\r\n";
			$msgRec .= "\t <memory>" 		. memory_get_peak_usage(true)				. "</memory>\r\n";
			if(Error::$REC_VAR == true) $msgRec .= "\t <vartrace>" 		. wddx_serialize_value($_error_vars, "Variables")	. "</vartrace>\r\n";
			$msgRec .= "</errorentry>\r\n";
			
			$msgDebug 	= "";
			$msgUser 	= "";
			
			if($_error_num <= Config::$ERROR_LEVEL){
				if(self::$SHOW_USER = true ){
					ob_flush();
        			flush();
					$msgDebug .= "<div style='display:block; float:left;clear:both;width:90%; border:#EEEEEE solid 1px; padding:5px; color:#C0210A; background-color:#F7F7F7;'>\n<div class='error_reporting_manager'>";
					if(self::$DEBUG !== false){
						$msgDebug .= "<b>". self::$ERROR_TYPE[$_error_num] ."</b> : ";
						$msgDebug .= "$_error_msg";
						if($_error_file) $msgDebug .= " em <b>$_error_file</b> ";
						if($_error_line) $msgDebug .= " na linha <b>$_error_line</b>";
						$msgDebug .= "</div>\n</div>";
						$msgUser 	.= "<div>";
					}
					$msgUser .= "<b style='float:left;clear:both;width:90%;'>Ocorreu um erro interno. Por favor, volte em instantes. Caso o erro persista entre em contato com o suporte.</b><br />";
					$msgUser .= "</div>";
				}
			}

			if((self::$ERROR_SHOW_NOTICE == false && $_error_num == 8) || (self::$ERROR_SHOW_WARNING == false && $_error_num == 2)){
				return false;
			}
			
			if(self::$DEBUG == true || self::$SHOW_USER = true && self::$SHOW_MSG_DEFAULT != true && $_error_num != 2 && $_error_num != 8 )	echo $msgUser;
			else if(self::$SHOW_USER = true && self::$SHOW_MSG_DEFAULT == true)	echo "<!-- ".$msgDebug." -->";
			if(self::$DEBUG == true && self::$SHOW_USER == true)	echo $msgDebug;
			else{ 
				if(self::$SHOW_USER == true && $_error_num != 2 && $_error_num != 8) echo $msgUser;
			}
			ob_flush();
        	flush();
			
			if(self::$REC_LOG == true){
				if(!is_dir(APP_PATH."run_logs")) mkdir(APP_PATH."run_logs", 0700);
				error_log($msgRec, 3, APP_PATH."run_logs/_php_error_". date("Ymd") .".log");
			}
			return $msgRec;
		}
	}
	// -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	static function sqlError($mensagem="Erro SQL.", $erro = 'Erro desconhecido.', $sql ='Erro sql não declarado.'){
		self::show(1025, $erro, $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"], $sql, "");
		$msg  = "";
		if(self::$DEBUG == true && self::$SHOW_MSG_DEFAULT == true) echo "<!--  ";
		if(self::$DEBUG == true ){
			$msg .= "<div class='sqlerror' style='display:block; float:left;clear:both;width:90%;border:#eeeeee solid 1px;padding:10px;color:#B70428;background-color:#F8F8FA;'> \t\n";
			$msg .= "<div>$mensagem</div>";
			$msg .= "<b>Erro:</b> $erro";
			$msg .= "<br><i><pre>$sql</pre></i>";
			$msg .= "</div>";
		}
		if(self::$ERROR_EXECUTION == true && self::$SHOW_USER){
			echo $msg;

			if(self::$DEBUG == true){
				 Debug::showLog();
			}
			if(isset(Config::$ERROR_SHOW_BACKTRACE) && Config::$ERROR_SHOW_BACKTRACE == true ) Debug::showBacktrace();
			if(self::$DEBUG == true && self::$SHOW_MSG_DEFAULT == true) echo " -->";


			if(self::$SEND_MAIL == true){
				//TODO: Usar classe smtp mail para enviar.
				//TODO: Criar linha no config para enviar os erros
				//mail("dev@rafaelteixeira.com", "Erro no site", $msgRec);
				Run::loadLibrary('mail');
				$email 		= new Mail();
				$de 		= array(Run::$control->string->mixed_to_latin1(self::$ERROR_EMAIL), Run::$control->string->mixed_to_latin1(self::$ERROR_PROJECT));
				$para 		= array(Run::$control->string->mixed_to_latin1(self::$ERROR_EMAIL), Run::$control->string->mixed_to_latin1(self::$ERROR_PROJECT));
				ob_flush();
	        	flush();

				$msgEmail .= "<p>Página: ". $_SERVER['SERVER_NAME']."".$_SERVER['REQUEST_URI'] ."</p>";
				$msgEmail .= "<b>". self::$ERROR_TYPE[$_error_num] ."</b> : <br />";
				$msgEmail .= "<p> <pre> $_error_msg  </pre></p>";
				if($_error_file) $msgEmail .= " <br /><b>Error file:</b> $_error_file ";
				if($_error_line) $msgEmail .= " <br /><b>Error line:</b> <pre> $_error_line </pre>";
				
				$msgEmail .= "<div><pre>". Debug::getBacktrace() ."</pre></div>";
				//$msgEmail .= "<div><pre>". Run::$control->string->multiImplode(debug_backtrace(), "\t> ") ."</pre></div>";

				/*
				foreach(debug_backtrace() as $k => $v){
					$msgEmail .= "<div><pre>". $v ."</pre></div>";
					if(is_array($v)) foreach($v as $k2 => $v2){ 
						$msgEmail .= "<div><pre>". implode("<br />", $v2) ."</pre></div>";
						if(is_array($v2)) foreach($v2 as $k3 => $v3){
							$msgEmail .= "<div><pre>". implode("<br />", $v3) ."</pre></div>";
						}
					};
				}*/
				$result = $email->sendMail($de, $para, Run::$control->string->mixed_to_latin1("Erro: ".self::$ERROR_PROJECT), Run::$control->string->mixed_to_latin1($msgEmail));
				if($result) echo "<p>Não se preocupe, um e-mail foi enviado para o administrador do sistema informando o problema.</p>";
				ob_flush();
	        	flush();
			}
			exit;
		}
	}
}
//*********************************************************************************************************************************
$Error = new Error;

?>