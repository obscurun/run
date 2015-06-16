<?php
// ****************************************************************************************************************************
class Render{

	//*************************************************************************************************************************
	function Render(){
		Debug::log("Iniciando Core/Render.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);

	}
	
	//*************************************************************************************************************************
	public static function setResponse($message="", $type="info", $idAlert="default", $class=""){
		Debug::log("Render->setResponse:".$message, __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		//Debug::p("response_".$type, $textType);
		Run::$session->set(array("render", "response", $idAlert), array("type"=>$type, "message"=>$message, "class"=>$class));
	}
	//*************************************************************************************************************************
	public static function getResponse($idAlert="", $delete=true){
		Debug::log("Render->getResponse:".$alert, __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		//Debug::p($_SESSION);
		$response = "";
		if($idAlert != ""){
			$response = Run::$session->get(array("render", "response", $idAlert));
			if($delete) Run::$session->del(array("render", "response", $idAlert));
		}else{
			$response = Run::$session->get(array("render", "response"));
			if($delete) Run::$session->del(array("render", "response"));
		}
		if($response != "") return $response;
		else return array();
	}
	//*************************************************************************************************************************
	public static function echoResponse($idAlert="", $close=true, $delete=true){
		$messages = self::getResponse($idAlert, $delete);

		$alert = "\n<div class=\"render-response bs-component\">\n";
		if(array_key_exists("type", $messages)){
			$alert .= self::getModelResponse($messages['type'], $messages['message'], $messages['class'], $close);
		}else{
			foreach($messages as $k => $v){
				//$alert .= $v;
				if(array_key_exists("type", $v)){
					$alert .= self::getModelResponse($v['type'], $v['message'], $v['class'], $close);
				}else{
					foreach($v as $kI =>$vI){
						$alert .= self::getModelResponse($vI['type'], $vI['message'], $vI['class'], $close);
					}
				}
			}
		}
		$alert .= "\n</div>";
		echo $alert;
	}
	//*************************************************************************************************************************
	public static function getModelResponse($type="", $message=true, $class="", $close=true){
		$textType = Language::get("response_".$type);
	    $alert .= 	"    \t	<div class=\"alert alert-dismissible alert-$type $class\">\n";
	    if($close === true) $alert .= 	"    \t\t    <button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>\n";
	    if(!(strpos($textType, "response_") >= 0) && $textType != "") $alert .= 	"    \t\t    <h4>$textType</h4>\n";
	    $alert .= 	"    \t\t    <p>$message</p>\n";
	    $alert .= 	"    \t </div>\n";
		return $alert;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function html($str){
		//Debug::log("View->html: $str", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(Config::VIEW_HTML_ENTITIES == true) $str = htmlentities($str);
		return $str;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function htmlHeader(){
		Debug::log("View->renderHeader:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		Config::$PATH_LINK 	= (UrlController::$useLanguage == true) ? Config::$PATH.Config::$LANGUAGE_ACTIVE."/" : Config::$PATH;
		$headerHTML = "";
		$headerHTML .= "<title>". Config::$TITLE ."</title>";
		$headerHTML .= "\n\t<script> \n\t <!-- \n\t";
		$headerHTML .= "\n\t page = \"". Config::$PATH.UrlController::getRelativePath(20) ."/\";";
		$headerHTML .= "\n\t PAGE = \"". Config::$PATH.UrlController::getRelativePath(20) ."/\";";
		$headerHTML .= "\n\t PATH = \"". Config::$PATH ."\";";
		$headerHTML .= "\n\t PATH_LINK = \"". Config::$PATH_LINK ."\";";
		if(Config::$PATH_SITE != "") $headerHTML .= "\n\t PATH_SITE = \"". Config::$PATH_SITE ."\";";
		$headerHTML .= "\n\t // --> \n\t</script> \n";
		/* $headerHTML .= "\n\t<script type=\"text/javascript\" src=\"". Config::$PATH ."js/jquery.js\"> </script>";
		//$headerHTML .= "\n\t<script type=\"text/javascript\" src=\"". Config::$PATH ."js/functions.js\"></script>\n"; */
		$headerHTML .= self::$header_extras;
		$headerHTML .= "\n";
		self::$header_default = $headerHTML;
		self::$header_default .= self::googleAnalytics();
		echo self::$header_default;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function applyPaging($obj="", $url_index=false, $link="?", $gets="", $name="items"){
		if(!isset($obj->PAGING_TOTAL) || !isset($obj->PAGING)) Error::show(0, "Query:: Há um erro na classe, não será possivel renderizar a paginação corretamente.");

		$html = "";
		if(!$url_index) $html .=  "$link{$obj->SETTINGS['PAGING_REF']}index=[index]?";
		else $html .= "$link/[index]?";
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'ordem'])) $html .=  "&{$obj->SETTINGS['PAGING_REF']}ordem=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'ordem'];
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'modo']))  $html .=  "&{$obj->SETTINGS['PAGING_REF']}modo=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'modo'];
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'num']))   $html .=  "&{$obj->SETTINGS['PAGING_REF']}num=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'num'];
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'busca'])) $html .=  "&{$obj->SETTINGS['PAGING_REF']}busca=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'busca'];
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'fk_template_busca'])) $html .=  "&{$obj->SETTINGS['PAGING_REF']}fk_template_busca=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'fk_template_busca'];

		echo Paging::get($obj->PAGING, $html, $name);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function renderReporting($obj="", $url_index=false, $link="?", $gets="", $name="items"){
		if(!isset($obj->PAGING_TOTAL) || !isset($obj->PAGING)) Error::show(0, "Query:: Há um erro na classe, não será possivel renderizar a paginação corretamente.");

		$html = "";
		if(!$url_index) $html .=  "$link{$obj->SETTINGS['PAGING_REF']}index=[index]";
		else $html .= "$link/[index]";
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'ordem'])) $html .=  "&{$obj->SETTINGS['PAGING_REF']}ordem=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'ordem'];
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'modo']))  $html .=  "&{$obj->SETTINGS['PAGING_REF']}modo=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'modo'];
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'num']))   $html .=  "&{$obj->SETTINGS['PAGING_REF']}num=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'num'];
		if(isset($_GET[$obj->SETTINGS['PAGING_REF'].'busca'])) $html .=  "&{$obj->SETTINGS['PAGING_REF']}busca=". $obj->DATA_INT[$obj->SETTINGS['PAGING_REF'].'busca'];

//		echo "<div class=\"getExcel\"><a href='?".$html."&". $obj->SETTINGS['REF'] ."export=excel' title='Gerar Relatório em Excel'><img src='". Config::$PATH ."cms/img/themes/default/logo_excel.gif' alt='relatório' /></a></div>";
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function googleAnalytics(){
		$js_ana = "";
		//echo ">>>>>>>>>>>>>>>>>>>>>> ".Run::$session->get(array('CONFIG', 0, 'id_analytics'));
		if(strlen(Run::$session->get(array('CONFIG', 0, 'id_analytics'))) >=3){
			$js_ana = "\r\n\t<script type='text/javascript'>
						  var _gaq = _gaq || [];
						  _gaq.push(['_setAccount', '". trim(Run::$session->get(array('CONFIG', 0, 'id_analytics'))) ."']);
						  _gaq.push(['_setDomainName', 'none']);
						  _gaq.push(['_setAllowLinker', true]);
						  _gaq.push(['_trackPageview']);

						  (function() {
							var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
							ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
							var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
						  })();
					</script>\r\n";
		}
		return $js_ana;
	}
}
// ****************************************************************************************************************************
?>