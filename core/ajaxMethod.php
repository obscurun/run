<?php 
// ****************************************************************************************************************************
// Criado inicialmente em 11/02/2010 - v.1.0
class AjaxMethod{
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	//Execute - função para executar um método no própria página php através de ajax
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function start(){
		if(isset($_GET["ajax_method"])) $method_request = $_GET["ajax_method"];
		else $method_request = false;
		if($method_request){
		//	@header("Content-Type: text/html;  charset=ISO-8859-1",true);
			@header("Content-Type: text/html; charset=ISO-8859-1",true);
			$__obj = (Router::$content);
			if(method_exists(get_class(Router::$content), $method_request)) $__obj = Router::$controller;
			else if(method_exists(get_class(Router::$content->model), $method_request)) $__obj = Router::$controller->model;
			else{ Error::show(0, "O método $method_request não foi encontrado: ".get_class(Router::$controller) ); exit; }

			try{
				if(isset($_GET["ajax_value1"]) && !(isset($_GET["ajax_value2"]))) $str = $__obj->$method_request($_GET["ajax_value1"]);
				else if(isset($_GET["ajax_value2"]) && !(isset($_GET["ajax_value3"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"]);
				else if(isset($_GET["ajax_value3"]) && !(isset($_GET["ajax_value4"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"]);
				else if(isset($_GET["ajax_value4"]) && !(isset($_GET["ajax_value5"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"],$_GET["ajax_value4"]);
				else if(isset($_GET["ajax_value5"]) && !(isset($_GET["ajax_value6"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"],$_GET["ajax_value4"],$_GET["ajax_value5"]);
				else if(isset($_GET["ajax_value6"]) && !(isset($_GET["ajax_value7"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"],$_GET["ajax_value4"],$_GET["ajax_value5"],$_GET["ajax_value6"]);
				else if(isset($_GET["ajax_value7"]) && !(isset($_GET["ajax_value8"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"],$_GET["ajax_value4"],$_GET["ajax_value5"],$_GET["ajax_value6"],$_GET["ajax_value7"]);
				else if(isset($_GET["ajax_value8"]) && !(isset($_GET["ajax_value9"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"],$_GET["ajax_value4"],$_GET["ajax_value5"],$_GET["ajax_value6"],$_GET["ajax_value7"],$_GET["ajax_value8"]);
				else if(isset($_GET["ajax_value9"]) && !(isset($_GET["ajax_value10"]))) $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"],$_GET["ajax_value4"],$_GET["ajax_value5"],$_GET["ajax_value6"],$_GET["ajax_value7"],$_GET["ajax_value8"],$_GET["ajax_value9"]);
				else $str = $__obj->$method_request($_GET["ajax_value1"],$_GET["ajax_value2"],$_GET["ajax_value3"],$_GET["ajax_value4"],$_GET["ajax_value5"],$_GET["ajax_value6"],$_GET["ajax_value7"],$_GET["ajax_value8"],$_GET["ajax_value9"],$_GET["ajax_value10"]);
				echo $str;
				exit;
			}
			catch(Exception $e){ echo "<div class='error'> Função $method_request (ajax) não executada. </div>";exit; }
		}
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function execute($class, $method_request, $value, $extras){
		if($method_request){		
			try{
				@header("Content-Type: text/html;  charset=ISO-8859-1",true);
				if(!method_exists($class, $method_request)){ return "Método $method_request (ajax) não existe."; }
				for($i =0;$i<=10;$i++){ if(!isset($extras[$i])) $extras[$i] = null;	}
				$str = $class->$method_request($value, $extras[0], $extras[1], $extras[2], $extras[3], $extras[4], $extras[5], $extras[6], $extras[7], $extras[8], $extras[9], $extras[10]);
			}
			catch(Exception $e){ $str = "<div class='error'> Função $method_request (ajax) não executada. </div>"; }
			return $str;
		}
		return false;
	}
	
	//adicionando funçoes JS do ajaxMethod
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function addFunctions($method_names){
		$page = $run->PAGE;
		
		$str = "<script type=\"text/javascript\" language=\"javascript\">";
		
		if (gettype($method_names) != "array"){
			$method_names = array($method_names);
		}
		
		
		for($i=0; $i<count($method_names); $i++){
			$method_name = $method_names[$i];
			$str .= "
				function $method_name(target, value1, value2, value3, value4, value5, value6, value7, value8, value9, value10){
					loadPage('$page', target, 'ajax_func=$method_name&ajax_value1=' + value1 + '&ajax_value2=' + value2 + '&ajax_value3=' + value3 + '&ajax_value4=' + value4 + '&ajax_value5=' + value5 + '&ajax_value6=' + value6 + '&ajax_value7=' + value7 + '&ajax_value8=' + value8 + '&ajax_value9=' + value9 + '&ajax_value10=' + value10, false, false);
				}
			";
		}
		$str .= "</script>";
		return $str;
	}
	
	//adicionando funçoes JS para FORMS do loadAjaxMethod
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function addForm($method_names){
		$page = $run->PAGE;
		
		$str = "<script type=\"text/javascript\" language=\"javascript\">";
		
		if (gettype($method_names) != "array"){
			$method_names = array($method_names);
		}
		
		for($i=0; $i<count($method_names); $i++){
			$method_name = $method_names[$i];
			$str .= "
				function $method_name(form, target){
					sendForm(form, '$page?ajaxMethod=$method_name', target, false, true);
				}
			";
		}
		$str .= "</script>";
		return $str;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	
}

?>