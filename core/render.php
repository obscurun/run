<?php
Run::loadHelper("paging");
// ****************************************************************************************************************************
class Render{

	//*************************************************************************************************************************
	function Render(){
		Debug::log("Iniciando Core/Render.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);

	}
	//*************************************************************************************************************************
	public static function load($page, $accept=false){
		Debug::log("View->load: $page ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(defined('CMS_PATH')){
              $page_path = file_exists(Config::PATH_PAG."view/". $page .".php") ? Config::PATH_PAG."view/". $page .".php":CMS_PATH.Config::PATH_PAG."view/". $page .".php";	  
		}
        else{
			Debug::log("View->load: Não existe A: ".Config::PATH_PAG."view/". $page .".php ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			$page_path = Config::PATH_PAG."view/". $page .".php";
		}
		if( ( ((count(View::$URL_PARAMS) <= 3 && $accept===false) || $accept===true) && file_exists($page_path) ) || ( RouterBase::$useLanguage == true && file_exists($page_path) )){
			include($page_path);
		}else{
			Debug::log("View->load: Não existe B: $page_path ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);

			if(file_exists(Config::PATH_PAG."view/404.php")) include(Config::PATH_PAG."view/404.php");
			else Debug::log("View->load: {Config::PATH_PAG}.'view/404.php' não existe. ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			exit;
		}
	}
	//*************************************************************************************************************************
	public static function includeContent($page, $line=__LINE__, $function=__FUNCTION__, $class=__CLASS__, $file=__FILE__){
		self::load($page, 0, $line, $function, $class, $file);
	}
	//-------------------------------------------------------------------------------------------------------------------------
    public static function redirect($path){
        Debug::log("View->redirect:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        header("Location: ".Config::$PATH.$path);
        exit;
    }
	//-------------------------------------------------------------------------------------------------------------------------
	public static function loadModel($file=""){
		if($file == "") Error::show(0, "loadModel: É preciso definir um modelo a ser carregado.");
		if(file_exists(Config::PATH_PAG."model/".$file."_model.php")){
			require_once(Config::PATH_PAG."model/".$file."_model.php");
		}else{
			Error::show(0, "loadModel: modelo $file não encontrado.");
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function loadController($file=""){
		if($file == "") Error::show(0, "loadController: É preciso definir um controle a ser carregado.");
		if(file_exists(Config::PATH_PAG."control/".$file."_control.php")){
			require_once(Config::PATH_PAG."control/".$file."_control.php");
		}else{
			Error::show(0, "loadController: controle $file não encontrado.");
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function call($path, $evalPath=false){
		if($evalPath){
			if(defined('CMS_PATH')){
				$path = file_exists(APP_PATH.$path)?APP_PATH.$path:CMS_PATH.$path;
			}else{
				$path = APP_PATH.$path;
			}
		}

		Debug::log("View->call: $path", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(file_exists($path))
			require_once($path);
		else{
			View::load("404");
			exit;
		}
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