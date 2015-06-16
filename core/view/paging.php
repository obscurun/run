<?php
// ****************************************************************************************************************************
class Paging{
	//*************************************************************************************************************************
	function __construct(){
	}
	//*************************************************************************************************************************
	static public function set($p_index=1, $p_items=10, $p_total=5, $p_num=3, $total=50){
		$array = array();
		$p_I = (($p_index-1) <= 0) ? 1  : ($p_index-1);
		if($p_index>1) array_push($array, array("<span> « </span>", ($p_I), 'setae'));
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		$array_inicial = ceil(($p_index-(ceil($p_items-1)/2)) >= 1 ? ($p_index-(ceil($p_items-1)/2)): 1) ;
		$array_final = round(($p_index+(floor($p_items-1)/2)) >= $p_items ? ($p_index+(floor($p_items-1)/2)): $p_items) ;
		$array_final = ($array_final >= $p_total) ? $p_total : $array_final;
		$array_dif = $array_final - $array_inicial;
		
		if( $array_dif < $p_items-1 && $p_total > ($p_items-1) ) $array_inicial = $array_final - $p_items+1;
		if($p_total == 0) $array_final = 1;
		//- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		for($i=$array_inicial; $i<=$array_final; $i++){
			if($i == $p_index) $class = 'press'; else $class = '';
			if($i <= 9) $i2 = "0".$i;
			else $i2 = $i;
			array_push($array, array($i2, $i, $class));
		}
		$tt = (($p_index+1) >= $p_total ) ? $p_total:$p_index+1;
		if($p_index<$p_total) array_push($array, array("<span> » </span>", ($tt), 'setad'));
		array_push($array, $total);
		//Debug::print_r($array);
		return $array;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public static function get($list=array(), $link="?", $name="items", $setaLeft="«", $setaRight="»", $showOneResult=true){
		Debug::log("Paging->get:", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(count($list)-2 == 0 && !$showOneResult)return "";

		$list[0] 					= str_replace("&lt;", $setaLeft, $list[0]);
		$list[count($list)-2][0]	= str_replace("&gt;", $setaRight, $list[count($list)-2][0]);
		//Debug::print_r($list);
		$html = "\n\n<ul class='paging pagination '>";
		for($i=0; $i<count($list)-1; $i++){
			$html .= "\n<li class='".$list[$i][2]."'>\n\t<a href=\"";
			$alink = $link;
			$alink = str_replace("[index]", $list[$i][1], $alink);
			$alink = str_replace("[INDEX]", $list[$i][1], $alink);
			$html .= "$alink";
			$html .= "\"><span>".$list[$i][0]."</span></a>\n</li>";
		}
		$pag_count = (isset($list[count($list)-1]))? ($list[count($list)-1]):"0";
		$html .=  "\n<li class='paging_total'> <span>Total: <b>".$pag_count."</b> $name</span> </li> \n";
		$html .= "\n</ul>\n\n";
		return $html;
	}
	//-------------------------------------------------------------------------------------------------------------------------
}

?>