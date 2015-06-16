<?php
// ****************************************************************************************************************************
class String{	
    private $latin1_to_utf8; 
    private $utf8_to_latin1; 

	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){

	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -




//*****************************************************************************************************************************
	// Converte encoding de strings >>>
//*****************************************************************************************************************************
	/**
	 * Parse Encoding to default encoding config
	 * @param type string $text 
	 * @param type string $encoding 
	 * @return type string $text
	 */
	public function encoding($text="", $encoding="default"){
		if($encoding == "default") $encoding = Run::ENCODING;
		if($encoding == "utf8"){
			return Run::$control->string->mixed_to_utf8($text);
		}
		else if($encoding == "iso8859-1"){
			return Run::$control->string->mixed_to_utf8($text);
		}
		else{
			return $text;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
    public function mixed_to_latin1($text){
    	if($this->utf8_to_latin1 == null) $this->setEncodingMix();
        foreach( $this->utf8_to_latin1 as $key => $val ) { 
            $text = str_replace($key, $val, $text);
        } 
        return $text; 
    } 
	//-------------------------------------------------------------------------------------------------------------------------
    public function mixed_to_utf8($text){
       	// return utf8_encode(($text)); 
        return utf8_encode($this->mixed_to_latin1($text)); 
    }
	//-------------------------------------------------------------------------------------------------------------------------
    private function setEncodingMix(){
    	/*
    	$maps = array_fill ( 32 , 255 , true );
    	foreach($maps as $k => $v){
            $this->latin1_to_utf8[chr($k)] = utf8_encode(chr($k)); 
            $this->utf8_to_latin1[utf8_encode(chr($k))] = chr($k);
    	}
    	*/
        for($i=32; $i<256; $i++) { 
            $this->latin1_to_utf8[chr($i)] = utf8_encode(chr($i)); 
            $this->utf8_to_latin1[utf8_encode(chr($i))] = chr($i); 
        }
    }
	//-------------------------------------------------------------------------------------------------------------------------
	public function parseToAscii($str, $size=false, $replace=array(), $delimiter='_') {
		setlocale(LC_ALL, "pt_BR.utf-8");		
		if( !empty($replace) ){ $str = str_replace((array)$replace, ' ', $str); }		
		$clean = iconv('ISO-8859-1', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);		
		if($size)$clean = substr($clean,0,$size);		
		return $clean;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function parseToAsciiIso($str, $size=false, $replace=array(), $delimiter='_') {
		setlocale(LC_ALL, "pt_BR.iso-8859-1");		
		if( !empty($replace) ){ $str = str_replace((array)$replace, ' ', $str); }		
		$clean = iconv('ISO-8859-1', 'ASCII//TRANSLIT', $str);
		//$clean = $str;
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);		
		if($size)$clean = substr($clean,0,$size);		
		return $clean;
	}







//*****************************************************************************************************************************
	// Tratamento especial / mudando strings >>>
//*****************************************************************************************************************************
	//-------------------------------------------------------------------------------------------------------------------------
	public function clearSpecials($str, $size=false) {
		if( Config::ENCODING == "utf8" ){
			$str = str_replace(
				array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
				array(
				 	Run::$control->string->mixed_to_utf8("'"), 
				 	Run::$control->string->mixed_to_utf8("'"), 
				 	Run::$control->string->mixed_to_utf8('"'), 
				 	Run::$control->string->mixed_to_utf8('"'), 
				 	Run::$control->string->mixed_to_utf8('-'), 
				 	Run::$control->string->mixed_to_utf8('--'), 
				 	Run::$control->string->mixed_to_utf8('...')
				),
				$str
			);
		}
		else{
			$str = str_replace(
				array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
				array(
				 	"'", 
				 	"'", 
				 	'"', 
				 	'"', 
				 	'-', 
				 	'-', 
				 	'_'
				),
				$str
			);
		}
		$chars_specials = array('á','à','ã','â','é','ê','í','ó','ô','õ','ú','ü','ç','Á','À','Ã','Â','É','Ê','Í','Ó','Ô','Õ','Ú','Ü','Ç',' ' );
        $chars_normals  = array( 'a','a','a','a','e','e','i','o','o','o','u','u','c','A','A','A','A','E','E','I','O','O','O','U','U','C','_' );
		$str = str_replace( $chars_specials, $chars_normals, $str );
		$str = str_replace('-','_',$str);
		$str = str_replace('(','_',$str);
		$str = str_replace(')','_',$str);
		$str = str_replace(',','_',$str);
		$str = str_replace("'",'',$str);
		$str = str_replace('"','',$str);
		$str = str_replace(';','',$str);
		$str = str_replace('&','',$str);
		$str = str_replace('+','',$str);
		$str = str_replace('ç','',$str);
		$str = str_replace('´','',$str);
		$str = str_replace('`','',$str);
		$str = str_replace('*','',$str);

		$str = str_replace('/','',$str);
		$str = str_replace('\\','',$str);
		$str = str_replace('#','',$str);
		$str = str_replace('$','',$str);
		$str = str_replace('@','',$str);
		$str = str_replace('@','',$str);
		$str = str_replace('º','',$str);
		$str = str_replace('...','.',$str);
		$str = str_replace('..','.',$str);
		$str = str_replace('..','_',$str); 
		$str = str_replace(' ','_',$str);
		$str = str_replace(' ','_',$str);
		if( substr($str, -4, -4) == "_") $str = substr_replace($str, '.', -4, -4);
		$str = str_replace('___','_',$str);
		$str = str_replace('__','_',$str);

		if($size)$str = substr($str,0,$size);
		
		return $str;
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
	public function cropStr($text, $pos){
		$text = preg_replace("/<(.*?)>/", " ", $text);
		if (strlen($text) <= $pos) $ret = "";
		else $ret = "";
		if (strlen($text)>$pos){
			$text = substr($text, 0, $pos);
		}
		return $text;
	}






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