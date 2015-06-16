<?php 
// ********************************************************************************************************************************
class Cookie{
	//-----------------------------------------------------------------------------------------------------------------------------
	function Cookie(){
		Debug::log("Iniciando Core/Cookie.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);		
	}
	
	function set($name, $value, $expire=0, $path="", $domain="", $secure=0, $httponly=true){
		$expire = $expire>0?time()+$expire:0;
		
		if(is_array($value)){
			foreach($value as $k => $val){				//setcookie(Config::$NAME."_".$name."[$k]", $val, $expire, $path, $domain, $secure);
				setcookie(Run::NAME."_".$name."[$k]", $val, $expire, $httponly);
			}
		}else 
			//setcookie(Config::$NAME.'_'.$name, $value, $expire, $path, $domain, $secure);
			setcookie(Run::NAME.'_'.$name, $value, $expire, $httponly); 		
			//Debug::print_r($_COOKIE[Config::$NAME.'_'.$name]);
		return true;
	}
	
	function get($name){
		if(isset($_COOKIE[Run::NAME."_".$name]))
			return $_COOKIE[Run::NAME."_".$name];
		else
			return false;
	}
	
	function destroy($name){	
		if(isset($_COOKIE[Run::NAME."_".$name])) {
			if(is_array($_COOKIE[Run::NAME."_".$name])) 
				foreach($_COOKIE[Run::NAME."_".$name] as $k=>$val){
					setcookie(Run::NAME."_".$name."[$k]", '', 1, true);
				}
			else
				setcookie(Run::NAME.'_'.$name, '', time()-3600, true);
		}	
	}
}
// ********************************************************************************************************************************
?>