<?php
//*****************************************************************************************************************************
class Write{
	//*************************************************************************************************************************
	function Write(){
		Debug::log("Iniciando Core/View/Write.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);

	}
	//*************************************************************************************************************************
	public static function html($str){
		//Debug::log("View->html: $str", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(Config::VIEW_HTML_ENTITIES == true) $str = htmlentities($str, ENT_COMPAT, "UTF-8");
		return $str;
	}
	//*************************************************************************************************************************
	public static function header(){
		Debug::log("View->renderHeader:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		Config::$PATH_LINK 	= (RouterBase::$useLanguage == true) ? Config::$PATH.Config::$LANGUAGE_ACTIVE."/" : Config::$PATH;
		$headerHTML = "";
		$headerHTML .= "<title>". Config::$TITLE ."</title>";
		$headerHTML .= "\n\t<script> \n\t <!-- \n\t";
		$headerHTML .= "\n\t page = \"". Config::$PATH.RouterBase::getRelativePath(20) ."/\";";
		$headerHTML .= "\n\t PAGE = \"". Config::$PATH.RouterBase::getRelativePath(20) ."/\";";
		$headerHTML .= "\n\t PATH = \"". Config::$PATH ."\";";
		$headerHTML .= "\n\t PATH_LINK = \"". Config::$PATH_LINK ."\";";
		if(Config::$PATH != "") $headerHTML .= "\n\t PATH = \"". Config::$PATH ."\";";
		$headerHTML .= "\n\t // --> \n\t</script> \n";
		/* $headerHTML .= "\n\t<script type=\"text/javascript\" src=\"". Config::$PATH ."js/jquery.js\"> </script>";
		//$headerHTML .= "\n\t<script type=\"text/javascript\" src=\"". Config::$PATH ."js/functions.js\"></script>\n"; */
		//$headerHTML .= self::$header_extras;
		$headerHTML .= "\n";
		self::$header_default = $headerHTML;
		self::$header_default .= self::googleAnalytics();
		echo self::$header_default;
	}
	//*************************************************************************************************************************
	public static function googleAnalytics($print=true){
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
		if($print) echo $js_ana;
		else return $js_ana;
	}
}
//*****************************************************************************************************************************
?>