<?php
// ****************************************************************************************************************************
class Debug{
	public static $log = ""; 
	//*************************************************************************************************************************
	function __construct(){
		Debug::log("Iniciando Core/Debug.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(isset($_GET['debug-print'])) Run::$DEBUG_PRINT = true;
	}
	//*************************************************************************************************************************
	public static function log($_debug_msg="Mensagem não incluida", $_debug_line=__LINE__, $_debug_function=__FUNCTION__, $_debug_class=__CLASS__, $_debug_file=__FILE__){		
		if(Config::$DEBUG_REC_LOG === true){
			$msgRec  = "<log>\r\n";
			$msgRec .= "\t<msg>" 			. $_debug_msg 					. "</msg>\r\n";
			$msgRec .= "\t<file>" 			. $_debug_file 					. "</file>\r\n";
			$msgRec .= "\t<uri>" 			. $_SERVER['REQUEST_URI']					. "</uri>\r\n";
			if($_debug_line) 		$msgRec .= "\t<line>" 		. $_debug_line 			. "</line>\r\n";
			if($_debug_function) 	$msgRec .= "\t<function>" 	. $_debug_function		. "</function>\r\n";
			if($_debug_class)		$msgRec .= "\t<class>" 		. $_debug_class			. "</class>\r\n";
			$msgRec .= "\t<date>" 			. date("d-m-Y H:i:s")			. "</date>\r\n";
			$msgRec .= "\t<ip>" 			. $_SERVER['REMOTE_ADDR']		. "</ip>\r\n";
			$msgRec .= "\t<client>" 		. $_SERVER['HTTP_USER_AGENT']	. "</client>\r\n";
			$msgRec .= "\t<memory>" 		. memory_get_peak_usage(true)	. "</memory>\r\n";
			$msgRec .= "</log>\r\n";
			if(!is_dir(APP_PATH."run-logs")) mkdir(APP_PATH."run-logs", 0700);
			error_log($msgRec, 3, APP_PATH."run-logs/_php_debug_". date("Ymd") .".log");
		}
		self::$log .= "\r\n";
		$_debug_style_span=" style='font-size:82%;'";
		$_debug_style=" style='";
		if($_debug_class == "Model") 		$_debug_style .= " color:#663300 ";
		else if($_debug_class == "Mysql") 	$_debug_style .= " color:#FF3333 ";
		else if($_debug_class == "Query") 	$_debug_style .= " color:#CC3300";
		else if($_debug_class == "View") 	$_debug_style .= " color:#660066";
		$_debug_style .= "'";
		self::$log .= "<div>";
		self::$log .= "<div><span $_debug_style >$_debug_msg</span></div>";
		if($_debug_file) 		self::$log .= "<span $_debug_style_span><i>ARQUIVO</i>: <b $_debug_style>$_debug_file</b></span>";
		if($_debug_line) 		self::$log .= "<span $_debug_style_span><i> /LINHA</i>: <b $_debug_style>$_debug_line</b></span>";
		if($_debug_function) 	self::$log .= "<span $_debug_style_span><i> /FUNÇÂO</i>: <b $_debug_style>$_debug_function</b></span>";
		if($_debug_class) 		self::$log .= "<span $_debug_style_span><i> /CLASSE</i>: <b $_debug_style>$_debug_class</b></span>\r\n";
		self::$log .= "</div>";
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function showLog($title = "SHOWLOG" ){
		if(Config::$DEBUG_REC_LOG === true){
			echo "<div style='background:rgba(255, 255, 255, .8); color:#000; float:left;clear:both;width:90%; font-size:12px;'>$title<br /><pre class='debug_log'>". self::$log ."</pre></div>";
			echo "<div style='background:rgba(255, 255, 255, .8); color:#000; float:left;clear:both;width:90%; font-size:12px;'><pre class='debug_log'>\r\n IP::". $_SERVER['REMOTE_ADDR'] ."\r\n BROWSER::".$_SERVER['HTTP_USER_AGENT']."\r\n MEMORY::".memory_get_usage()."</pre></div>";
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function showBacktrace($title = "SHOWLOG" ){
		if(Config::$DEBUG_REC_LOG === true){
			echo "<div style='background:rgba(255, 255, 255, .8); color:#000; float:left;clear:both;width:90%; font-size:12px;'>$title<br /><pre class='debug_log'>". self::print_r(debug_backtrace()) ."</pre></div>";
			echo "<div style='background:rgba(255, 255, 255, .8); color:#000; float:left;clear:both;width:90%; font-size:12px;'><pre class='debug_log'>\r\n IP::". $_SERVER['REMOTE_ADDR'] ."\r\n BROWSER::".$_SERVER['HTTP_USER_AGENT']."\r\n MEMORY::".memory_get_usage()."</pre></div>";
		}
	}

	//-------------------------------------------------------------------------------------------------------------------------
	public static function getBacktrace($ignore = 0){ 
    $trace = ''; 
    foreach (debug_backtrace() as $k => $v) { 
        if ($k < $ignore) { 
            continue; 
        } 
        try{
	    //    array_walk($v['args'], function (&$item, $key) { 
	    //        $item = var_export($item, true); 
	     //   }); 
        }
        catch (Exception $e) {
		 // echo "Exceção pega: ",  $e->getMessage(), "\n";
		}

        $trace .= "\r\n #" . ($k - $ignore) . ' ' . $v['file'] . '(' . $v['line'] . '): ' . (isset($v['class']) ? $v['class'] . '->' : '') . $v['function'] . '(' . implode(', ', $v['args']) . ')' . "\n"; 
    } 

    return $trace; 
} 
	//-------------------------------------------------------------------------------------------------------------------------
	/*
	public static function generateCallTrace(){
	    $e = new Exception();
	    $trace = explode("\n", $e->getTraceAsString());
	    // reverse array to make steps line up chronologically
	    $trace = array_reverse($trace);
	    array_shift($trace); // remove {main}
	    array_pop($trace); // remove call to this method
	    $length = count($trace);
	    $result = array();
	    
	    for ($i = 0; $i < $length; $i++){
	        $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
	    }
	    
	    return "\t" . implode("\n\t", $result);
	}*/
	//-------------------------------------------------------------------------------------------------------------------------
	public function showGET(){
		echo "//------   GET's  --------// <br>";
		for ($i=0; $i<count($_GET); $i++){
			echo key($_GET) . " - " . current($_GET) . "<br>";
			next($_GET);
		}//-------------------------------------------
	}	
	//-------------------------------------------------------------------------------------------------------------------------
	public function showPOST(){	
		echo "//------   POST's  --------// <br>";
		for ($i=0; $i<count($_POST); $i++){			
			echo key($_POST) . " - " . current($_POST) . "<br>";
			next($_POST);
		} //-------------------------------------------
		echo "//------   GET's  --------// <br>";
		for ($i=0; $i<count($_GET); $i++){			
			echo key($_GET) . " - " . current($_GET) . "<br>";
			next($_GET);
		} //-------------------------------------------	
	}	
	//-------------------------------------------------------------------------------------------------------------------------
	public function showREQUEST(){
		echo "//------   REQUEST's  --------// <br>";
		for ($i=0; $i<count($_REQUEST); $i++){
			echo key($_REQUEST) . " - " . current($_REQUEST) . "<br>";
			next($_REQUEST);
		} //-------------------------------------------
	}
	//-------------------------------------------------------------------------------------------------------------------------	
	public static function p($dado1, $dado2 = false){
		if(!Run::$DEBUG_PRINT) return false;
		$from0 = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0];
		$from1 = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1];
		echo "<pre class='debug_php' style='background:rgba(0,0,0,.8); color:#fff; padding:15px; font-size:10px; line-height:10px;float:left;clear:both;padding-right:35px;'> <span class='d'>Debug: {$from1['class']} / Linha: {$from0['line']}</span> \r\n";
		//print_r((debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3))); //array_reverse 
		if(is_array($dado1)) print_r($dado1);
		else echo "<span class='d'>>></span>".$dado1."<span class='d'><<</span>";
		if($dado2 !== false){
			if(is_array($dado2)){print_r($dado2); }
			else echo "<span class='d'>|>></span>".$dado2."<span class='d'><<</span>";
		}
		echo "</pre>";
	}
	//-------------------------------------------------------------------------------------------------------------------------	
	public static function print_r($dado1, $dado2 = false) {
		if(!Run::$DEBUG_PRINT) return false;
		$from0 = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[0];
		$from1 = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1];
		echo "<pre class='debug_php' style='background:rgba(0,0,0,.8); color:#fff; padding:15px; font-size:10px; line-height:10px;float:left;clear:both;padding-right:35px;'> <span class='d'>Debug: {$from1['class']} / Linha: {$from0['line']}</span> \r\n";
		//print_r((debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 3))); //array_reverse 
		if(is_array($dado1)) print_r($dado1);
		else echo "<span class='d'>>></span>".$dado1."<span class='d'><<</span>
";
		if($dado2 !== false){
			if(is_array($dado2)){print_r($dado2); }
			else echo "<span class='d'>|>></span>".$dado2."<span class='d'><<</span>";
		}
		echo "</pre>";
	}
	//-------------------------------------------------------------------------------------------------------------------------	
}

?>