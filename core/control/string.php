<?php
require_once('encoding.php');
// ****************************************************************************************************************************
class String{	

	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){

	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -

//*****************************************************************************************************************************
	// Converte encoding de strings >>>
//*****************************************************************************************************************************
	public function encodeFixUtf8($text){ // enconding para UTF-8
		return $text;
		return ForceUTF8\Encoding::fixUTF8($text);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function encodeUtf8($text){ // enconding para UTF-8
		return $text;
		return ForceUTF8\Encoding::toUTF8($text);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function encodeIso($text){ // encoding para ISO 8859-1
		return $text;
		return ForceUTF8\Encoding::toLatin1($text);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function encoding($text){ // encoding para ISO 8859-1
		return $text;
		if(strrpos(Run::ENCODING, "utf") != "") return ForceUTF8\Encoding::toUTF8($text);
		else return ForceUTF8\Encoding::toLatin1($text);
	}

//*****************************************************************************************************************************
	// Tratamento especial / mudando strings >>>
//*****************************************************************************************************************************
	//-------------------------------------------------------------------------------------------------------------------------
	public function removeSpecialsNormalize($value, $isFile = false){		
		$value =  iconv("UTF-8", "ASCII//TRANSLIT", $value);
		$specialsCh = array();
		for($i=127;$i<256;$i++){
			$specialsCh[chr($i)] = "";
		}
		$specialsWord =	array(
			"\xe2\x80\x98" => "'", 
			"\xe2\x80\x99" => "'", 
			"\xe2\x80\x9c" => "\"", 
			"\xe2\x80\x9d" => "\"", 
			"\xe2\x80\x93" => "-", 
			"\xe2\x80\x94" => "-", 
			"\xe2\x80\xa6" => "_", 
			"\xe2\x80\xa6" => "_", 
			"  " => "_",
			"  " => "_",
			" " => "_",
			" " => "_",
			"___" => "_", 
			"___" => "_", 
			"___" => "_", 
			"__" => "_", 
			"__" => "_",
			"__" => "_",
			"__" => "_",
			"-" => "_",
			"(" => "",
			")" => "",
			"," => "",
			"\"" => "",
			";" => "",
			"&" => "",
			"+" => "",
			"ç" => "",
			"´" => "",
			"`" => "",
			"*" => "",
			"~" => "",
			"'" => "",
			"´" => "",
			"`" => "",
			"^" => "",
			"/" => "",
			"\\" => "",
			"#" => "",
			"$" => "",
			"@" => "",
			"º" => "",
			"..." => ".",
			"..." => ".",
			".." => ".",
			".." => ".",
		);
	    $value = preg_replace('/[^\w.]+/', '_', $value);
	    $value = str_replace( array_keys( $specialsCh ),      array_values( $specialsCh ), 		$value);
	    $value = str_replace( array_keys( $specialsWord ),    array_values( $specialsWord ), 	$value);
	    $value = str_replace( "__", "_",	$value);
	    $value = str_replace( "__", "_",	$value);
	    $value = preg_replace("/^_/", "", 	$value);
	    $value = preg_replace("/_$/", "", 	$value);
	    $posDot = strrpos($value, ".");
	    if($isFile !== false && $posDot > 0)$value = substr_replace($value, "\"", $posDot, 0);
	    $value = str_replace( ".", "",	$value);
	    $value = str_replace( "\"", ".",	$value);
		$value = $this->lower($value);
		//Debug::print_r($value);
	    return $value;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function fixSpecials($value){

		$specialsWord =	array(
			"\xe2\x80\x98" => "'", 
			"\xe2\x80\x99" => "'", 
			"\xe2\x80\x9c" => "\"", 
			"\xe2\x80\x9d" => "\"", 
			"\xe2\x80\x93" => "-", 
			"\xe2\x80\x94" => "-", 
			"\xe2\x80\xa6" => "_", 
			"\xe2\x80\xa6" => "_"
		);
	    $value = str_replace( array_keys( $specialsWord ),    array_values( $specialsWord ), 	$value);
	    return  $value;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function fixMSWord($string){
	    $map = Array(
	        '33' => '!', '34' => '"', '35' => '#', '36' => '$', '37' => '%', '38' => '&', '39' => "'", '40' => '(', '41' => ')', '42' => '*', 
	        '43' => '+', '44' => ',', '45' => '-', '46' => '.', '47' => '/', '48' => '0', '49' => '1', '50' => '2', '51' => '3', '52' => '4', 
	        '53' => '5', '54' => '6', '55' => '7', '56' => '8', '57' => '9', '58' => ':', '59' => ';', '60' => '<', '61' => '=', '62' => '>', 
	        '63' => '?', '64' => '@', '65' => 'A', '66' => 'B', '67' => 'C', '68' => 'D', '69' => 'E', '70' => 'F', '71' => 'G', '72' => 'H', 
	        '73' => 'I', '74' => 'J', '75' => 'K', '76' => 'L', '77' => 'M', '78' => 'N', '79' => 'O', '80' => 'P', '81' => 'Q', '82' => 'R', 
	        '83' => 'S', '84' => 'T', '85' => 'U', '86' => 'V', '87' => 'W', '88' => 'X', '89' => 'Y', '90' => 'Z', '91' => '[', '92' => '\\', 
	        '93' => ']', '94' => '^', '95' => '_', '96' => '`', '97' => 'a', '98' => 'b', '99' => 'c', '100'=> 'd', '101'=> 'e', '102'=> 'f', 
	        '103'=> 'g', '104'=> 'h', '105'=> 'i', '106'=> 'j', '107'=> 'k', '108'=> 'l', '109'=> 'm', '110'=> 'n', '111'=> 'o', '112'=> 'p', 
	        '113'=> 'q', '114'=> 'r', '115'=> 's', '116'=> 't', '117'=> 'u', '118'=> 'v', '119'=> 'w', '120'=> 'x', '121'=> 'y', '122'=> 'z', 
	        '123'=> '{', '124'=> '|', '125'=> '}', '126'=> '~', '127'=> ' '
	    );

	    $search = array();
	    $replace = array();
	    $i = 0;
	    foreach ($map as $s => $r) {
	        $search[] = chr((int)$s);
	        $replace[] = $r;
	    }
	    //Debug::print_r($search);
	    //Debug::print_r( str_replace($search, $replace, $string));

	    return str_replace($search, $replace, $string); 
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function multiImplode($array, $glue){ // implode arrays multidimencionais para string
	    $ret = '';
	    foreach($array as $item) {
	        if (is_array($item)) {
	            $ret .= "\n\r\t". $this->multiImplode($item, $glue) . $glue;
	        } else {
	            $ret .= $item . $glue;
	        }
	    }

	    $ret = substr($ret, 0, 0-strlen($glue));

	    return $ret;
	} 
	//-------------------------------------------------------------------------------------------------------------------------
	public function pad($str, $padLength=5, $padString=" ", $padType=STR_PAD_LEFT){
		return str_pad($str, $padLength, $padString, $padType);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function replace($what, $by, $in_text){
		return str_replace($what, $by, $in_text);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function cropText($text, $pos, $clear=true){
		if($clear) $text = preg_replace("/<(.*?)>/", " ", $text);
		if (strlen($text) <= $pos) $ret = "";
		else $ret = "...";
		
		if (strlen($text)>$pos){
			$text = substr($text, 0, $pos);
			$text = explode(" ", $text);
			array_pop($text);
			$text = implode(" ", $text);
		}
		return $text.$ret;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function cropStr($text, $pos, $isFile=false){
		$text = preg_replace("/<(.*?)>/", " ", $text);		
		if($isFile && strlen($text) > $pos){
			$ext = explode(".", $text);
			$ext = $ext[1];
			$text = substr($text, 0, $pos-strlen($ext)-1);
			$text = $text.".".$ext;
		}else{
			$text = substr($text, 0, $pos);
		}
		return $text;
	}
	//-------------------------------------------------------------------------------------------------------------------------






//*****************************************************************************************************************************
	// Tratamento HTML de strings >>>
//*****************************************************************************************************************************

    public function entityDecode($text){ 
        return html_entity_decode($text);
    }
	//-------------------------------------------------------------------------------------------------------------------------
    public function entityEncode($text){ 
        return htmlentities($text);
    }
	//-------------------------------------------------------------------------------------------------------------------------
	public function html($text=""){
		$text = htmlentities(($text));	
		return $text;
	}






//*****************************************************************************************************************************
	// Converte Cases em strings >>>
//*****************************************************************************************************************************

	public function upper($text=""){
		$text = strtoupper($text);
		$text = str_replace('ç', "Ç", $text);
		$text = str_replace('ã', "Â", $text);
		$text = str_replace('õ', "Õ", $text);
		$text = str_replace('ú', "Ú", $text);
		$text = str_replace('ó', "Ó", $text);
		$text = str_replace('à', "À", $text);
		$text = str_replace('á', "Á", $text);
		return $text;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function lower($text=""){
		$text = strtolower($text);		
		return $text;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function upperFirst($text="", $encoding="utf8"){
		$strlen = mb_strlen($text, $encoding);
	    $firstChar = mb_substr($text, 0, 1, $encoding);
	    $then = mb_substr($text, 1, $strlen - 1, $encoding);
	    return mb_strtoupper($firstChar, $encoding) . $then;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function upperWords($text="", $encoding="utf8"){
		$text = ucwords(mb_strtolower($text, $encoding));	
		return $text;
	}






//*****************************************************************************************************************************
	// Searchs strings >>>
//*****************************************************************************************************************************

	public function indexOf($string, $search){
		$pos = strpos($string, $search);
		if($pos === false) return -1;
		else return $pos;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function searchInText($text, $size, $busca){
		//$text	= strip_tags($text);
		$text = preg_replace("</h6>", "&nbsp;", $text);
		$text = preg_replace("</h5>", "&nbsp;", $text);
		$text = preg_replace("</h4>", "&nbsp;", $text);
		$text = preg_replace("</h3>", "&nbsp;", $text);
		$text = preg_replace("/<(.*?)>/", "", $text);
		$text = strip_tags($text);
		if($busca == "") $busca = " ";
		$pos	= strpos($this->lower($text), $this->lower($busca));
		$posIni = $pos - $size/2;
		$posFim = $size/2+100 ;
		if($posIni < 0) $posIni = 0;
		$text = substr($text, $posIni, $posFim);
		$ret = "...";

		$textA = $text;
		$text = substr($text, 0, $posFim);
		$text = explode(" ", $text);
		array_pop($text);
		unset($text[0]);
		$textN = implode(" ", $text);
		 $textN = strip_tags($textN);
		if(strlen($busca) > 1) $textN = str_replace($this->lower($busca), "<strong>". $this->lower($busca) ."</strong>", $textN);
		if(strlen($busca) > 1) $textN = str_replace($this->upper($busca), "<strong>". $this->upper($busca) ."</strong>", $textN);
		if(strlen($busca) > 1) $textN = str_replace($this->upperFirst($busca), "<strong>". $this->upperFirst($busca) ."</strong>", $textN);
		if(strlen($busca) > 1) $textN = str_replace($this->upperWords($busca), "<strong>". $this->upperWords($busca) ."</strong>", $textN);
		if(strlen($busca) > 1) $textN = str_replace($busca, "<strong>". $busca ."</strong>", $textN);
		if(!strpos($textN, $busca)){
		//	$textN .= " ".$busca;
		}

		return "...". $textN . $ret;
	}





//*****************************************************************************************************************************
	// Tratamento de strings >>>
//*****************************************************************************************************************************

	public function clearPoints($str){
		return preg_replace("/(['-.,:\/])/", "", $str);
	} 
	//-------------------------------------------------------------------------------------------------------------------------
	public function clearMoney($str){
		return preg_replace("/[^0-9,]/", "", $str);
	} 
	//-------------------------------------------------------------------------------------------------------------------------
	public function getNumbersPoints($str){
		return preg_replace("/[^0-9.,]/", "", $str);
	} 
	//-------------------------------------------------------------------------------------------------------------------------
	public function getOnlyNumbers($str){
		return preg_replace("/[^0-9]/", "", $str);
	} 
	//-------------------------------------------------------------------------------------------------------------------------
	public function clearSpecialPoints($str){
		return preg_replace("/[^A-Za-z0-9\-_?!]/", "", $str);
	} 
	//-------------------------------------------------------------------------------------------------------------------------
	public function trim($str){
		return trim($str);
		//return ereg_replace("/^\s+|\s+$/", "", $str);
	} 






//*****************************************************************************************************************************
	// Máscaras em strings >>>
//*****************************************************************************************************************************

	public function mask($val, $mask){
		$val = $this->clearPoints($val);
		$maskared = '';
		$k = 0;
		for($i = 0; $i<=strlen($mask)-1; $i++){
			if($mask[$i] == '#'){
				if( isset($val[$k]) ) $maskared .= $val[$k++];
			}
			else{
				if( isset($mask[$i]) ) $maskared .= $mask[$i];
			}
		}
		return $maskared;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function maskReal($val, $prefix=true){
		//echo $val; //getOnlyNumbers clearMoney
		$val = $this->clearMoney((string)$val);
		if(trim($val) == "") $val = "000";
		//$val = sprintf("%01,2d", $val);
		//$val = floor($val * 100) / 100;
		//$val = number_format($val, 2, ',', '.');
		$val = substr(number_format(str_replace(',', '.', $val), 3, ',', ''), 0, -1);
		$val = number_format($val, 2, ',', '.');
		//$val = money_format('%.2n', $val);
		//$locale = localeconv();
 		//$val = number_format($val, 2, $locale['decimal_point'], $locale['thousands_sep']);
		//$fmt = new NumberFormatter( 'de_DE', NumberFormatter::CURRENCY );
		//setlocale(LC_MONETARY, 'pt_BR');
		//$val = $this->my_money_format($val);
		if($prefix) return "R$".$val;
		else return $val;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	function moneyFormat($valor, $formato = '%n'){ //my_money_format
	    if (function_exists('money_format')) {
	        // Comente a linha abaixo caso possua a funcao money_format
	        return money_format($formato, $valor);
	    }

	    $locale = localeconv();

	    // Extraindo opcoes do formato
	    $regex = '/^'.             // Inicio da Expressao
	             '%'.              // Caractere %
	             '(?:'.            // Inicio das Flags opcionais
	             '\=([\w\040])'.   // Flag =f
	             '|'.
	             '([\^])'.         // Flag ^
	             '|'.
	             '(\+|\()'.        // Flag + ou (
	             '|'.
	             '(!)'.            // Flag !
	             '|'.
	             '(-)'.            // Flag -
	             ')*'.             // Fim das flags opcionais
	             '(?:([\d]+)?)'.   // W  Largura de campos
	             '(?:#([\d]+))?'.  // #n Precisao esquerda
	             '(?:\.([\d]+))?'. // .p Precisao direita
	             '([in%])'.        // Caractere de conversao
	             '$/';             // Fim da Expressao

	    if (!preg_match($regex, $formato, $matches)) {
	        trigger_error('Formato invalido: '.$formato, E_USER_WARNING);
	        return $valor;
	    }

	    // Recolhendo opcoes do formato
	    $opcoes = array(
	        'preenchimento'   => ($matches[1] !== '') ? $matches[1] : ' ',
	        'nao_agrupar'     => ($matches[2] == '^'),
	        'usar_sinal'      => ($matches[3] == '+'),
	        'usar_parenteses' => ($matches[3] == '('),
	        'ignorar_simbolo' => ($matches[4] == '!'),
	        'alinhamento_esq' => ($matches[5] == '-'),
	        'largura_campo'   => ($matches[6] !== '') ? (int)$matches[6] : 0,
	        'precisao_esq'    => ($matches[7] !== '') ? (int)$matches[7] : false,
	        'precisao_dir'    => ($matches[8] !== '') ? (int)$matches[8] : $locale['int_frac_digits'],
	        'conversao'       => $matches[9]
	    );

	    // Sobrescrever $locale
	    if ($opcoes['usar_sinal'] && $locale['n_sign_posn'] == 0) {
	        $locale['n_sign_posn'] = 1;
	    } elseif ($opcoes['usar_parenteses']) {
	        $locale['n_sign_posn'] = 0;
	    }
	    if ($opcoes['precisao_dir']) {
	        $locale['frac_digits'] = $opcoes['precisao_dir'];
	    }
	    if ($opcoes['nao_agrupar']) {
	        $locale['mon_thousands_sep'] = '';
	    }

	    // Processar formatacao
	    $tipo_sinal = $valor >= 0 ? 'p' : 'n';
	    if ($opcoes['ignorar_simbolo']) {
	        $simbolo = '';
	    } else {
	        $simbolo = $opcoes['conversao'] == 'n' ? $locale['currency_symbol']
	                                               : $locale['int_curr_symbol'];
	    }
	    $numero = number_format(abs($valor), $locale['frac_digits'], $locale['mon_decimal_point'], $locale['mon_thousands_sep']);

	    $sinal = $valor >= 0 ? $locale['positive_sign'] : $locale['negative_sign'];
	    $simbolo_antes = $locale[$tipo_sinal.'_cs_precedes'];

	    // Espaco entre o simbolo e o numero
	    $espaco1 = $locale[$tipo_sinal.'_sep_by_space'] == 1 ? ' ' : '';

	    // Espaco entre o simbolo e o sinal
	    $espaco2 = $locale[$tipo_sinal.'_sep_by_space'] == 2 ? ' ' : '';

	    $formatado = '';
	    switch ($locale[$tipo_sinal.'_sign_posn']) {
	    case 0:
	        if ($simbolo_antes) {
	            $formatado = '('.$simbolo.$espaco1.$numero.')';
	        } else {
	            $formatado = '('.$numero.$espaco1.$simbolo.')';
	        }
	        break;
	    case 1:
	        if ($simbolo_antes) {
	            $formatado = $sinal.$espaco2.$simbolo.$espaco1.$numero;
	        } else {
	            $formatado = $sinal.$numero.$espaco1.$simbolo;
	        }
	        break;
	    case 2:
	        if ($simbolo_antes) {
	            $formatado = $simbolo.$espaco1.$numero.$sinal;
	        } else {
	            $formatado = $numero.$espaco1.$simbolo.$espaco2.$sinal;
	        }
	        break;
	    case 3:
	        if ($simbolo_antes) {
	            $formatado = $sinal.$espaco2.$simbolo.$espaco1.$numero;
	        } else {
	            $formatado = $numero.$espaco1.$sinal.$espaco2.$simbolo;
	        }
	        break;
	    case 4:
	        if ($simbolo_antes) {
	            $formatado = $simbolo.$espaco2.$sinal.$espaco1.$numero;
	        } else {
	            $formatado = $numero.$espaco1.$simbolo.$espaco2.$sinal;
	        }
	        break;
	    }

	    // Se a string nao tem o tamanho minimo
	    if ($opcoes['largura_campo'] > 0 && strlen($formatado) < $opcoes['largura_campo']) {
	        $alinhamento = $opcoes['alinhamento_esq'] ? STR_PAD_RIGHT : STR_PAD_LEFT;
	        $formatado = str_pad($formatado, $opcoes['largura_campo'], $opcoes['preenchimento'], $alinhamento);
	    }

	    return $formatado;
	}

}
//*****************************************************************************************************************************
?>