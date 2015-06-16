<?php
// ****************************************************************************************************************************
class Data{
	//*************************************************************************************************************************
	function Data(){

	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getUniqueID( $opt = false ){       //  Set to true/false as your default way to do this.
	    if( function_exists('com_create_guid') ){
	        if( $opt ){ return com_create_guid(); }
	        else { return trim( com_create_guid(), '{}' ); }
        }
        else{
            mt_srand( (double)microtime() * 10000 );    // optional for php 4.2.0 and up.
            $charid = strtoupper( md5(uniqid(rand(), true)) );
            $hyphen = chr( 45 );    // "-"
            $left_curly = $opt ? chr(123) : "";     //  "{"
            $right_curly = $opt ? chr(125) : "";    //  "}"
            $uuid = $left_curly
                . substr( $charid, 0, 8 ) . $hyphen
                . substr( $charid, 8, 4 ) . $hyphen
                . substr( $charid, 12, 4 ) . $hyphen
                . substr( $charid, 16, 4 ) . $hyphen
                . substr( $charid, 20, 12 )
                . $right_curly;
            return $uuid;
        }
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function formatNumber($num, $format){
		return sprintf($format, $num);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function isArrayAssociative($arr){
		return (bool)count(array_filter(array_keys($arr), 'is_string'));
	   // return array_keys($arr) !== range(0, count($arr) - 1);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getEducation() {
		$formacao = array();
		$formacao["sem escolaridade"]			= "Sem Escolaridade";
		$formacao["primeiro grau"]				= "Primeiro Grau";
		$formacao["primeiro grau incompleto"]	= "Primeiro Grau Incompleto";
		$formacao["segundo grau"]				= "Segundo Grau";
		$formacao["segundo grau incompleto"]	= "Segundo Grau Incompleto";
		$formacao["terceiro grau"]				= "Terceiro Grau";
		$formacao["terceiro grau incompleto"]	= "Terceiro Grau Incompleto";
		$formacao["pos graduacao"]				= "Pós Graduação";
		$formacao["pos graduacao incompleta"]	= "Pós Graduação Incompleta";
		$formacao["mestrado"]					= "Mestrado";
		$formacao["mestrado incompleto"]		= "Mestrado Incompleto";
		$formacao["doutorado"]					= "Doutorado";
		$formacao["doutorado incompleto"]		= "Doutorado Incompleto";
		return $formacao;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getStatesBrazil() {
		$estados = array();
		$estados['AC'] = 'Acre';
		$estados['AL'] = 'Alagoas';
		$estados['AP'] = 'Amapá';
		$estados['AM'] = 'Amazônas';
		$estados['BA'] = 'Bahia';
		$estados['CE'] = 'Ceará';
		$estados['DF'] = 'Distrito Federal';
		$estados['ES'] = 'Espírito Santo';
		$estados['GO'] = 'Goiás';
		$estados['MA'] = 'Maranhão';
		$estados['MT'] = 'Mato Grosso';
		$estados['MS'] = 'Mato Grosso do Sul';
		$estados['MG'] = 'Minas Gerais';
		$estados['PA'] = 'Pará';
		$estados['PB'] = 'Paraíba';
		$estados['PR'] = 'Paraná';
		$estados['PE'] = 'Pernambuco';
		$estados['PI'] = 'Piauí';
		$estados['RJ'] = 'Rio de Janeiro';
		$estados['RN'] = 'Rio Grande do Norte';
		$estados['RS'] = 'Rio Grande do Sul';
		$estados['RO'] = 'Rondônia';
		$estados['RR'] = 'Roraima';
		$estados['SC'] = 'Santa Catarina';
		$estados['SP'] = 'São Paulo';
		$estados['SE'] = 'Sergipe';
		$estados['TO'] = 'Tocantins';
		return $estados;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getMeses($mes="") {
		$meses 	= array();
		$meses[0] = 'Mês não encontrado';
		$meses[1] = 'Janeiro';
		$meses[2] = 'Fevereiro';
		$meses[3] = 'Março';
		$meses[4] = 'Abril';
		$meses[5] = 'Maio';
		$meses[6] = 'Junho';
		$meses[7] = 'Julho';
		$meses[8] = 'Agosto';
		$meses[9] = 'Setembro';
		$meses[10] = 'Outubro';
		$meses[11] = 'Novembro';
		$meses[12] = 'Dezembro';
		if($mes == "") return $meses;
		else return $meses[(int)$mes];
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getCountrys(){
		$paises	 = array();
		$paises["África do Sul"] 			= "África do Sul";
		$paises["Albânia"] 					= "Albânia";
		$paises["Alemanha"] 				= "Alemanha";
		$paises["Andorra"] 					= "Andorra";
		$paises["Angola"] 					= "Angola";
		$paises["Anguilla"] 				= "Anguilla";
		$paises["Antigua"] 					= "Antigua";
		$paises["Arábia Saudita"] 			= "Arábia Saudita";
		$paises["Argentina"] 				= "Argentina";
		$paises["Armênia"] 					= "Armênia";
		$paises["Aruba"] 					= "Aruba";
		$paises["Austrália"] 				= "Austrália";
		$paises["Áustria"] 					= "Áustria";
		$paises["Azerbaijão"] 				= "Azerbaijão";
		$paises["Bahamas"] 					= "Bahamas";
		$paises["Bahrein"] 					= "Bahrein";
		$paises["Bangladesh"] 				= "Bangladesh";
		$paises["Barbados"] 				= "Barbados";
		$paises["Bélgica"] 					= "Bélgica";
		$paises["Benin"] 					= "Benin";
		$paises["Bermudas"] 				= "Bermudas";
		$paises["Botsuana"] 				= "Botsuana";
		$paises["Brasil"] 					= "Brasil";
		$paises["Brunei"] 					= "Brunei";
		$paises["Bulgária"] 				= "Bulgária";
		$paises["Burkina Fasso"] 			= "Burkina Fasso";
		$paises["botão"] 					= "botão";
		$paises["Cabo Verde"]				= "Cabo Verde";
		$paises["Camarões"] 				= "Camarões";
		$paises["Camboja"] 					= "Camboja";
		$paises["Canadá"] 					= "Canadá";
		$paises["Cazaquistão"] 				= "Cazaquistão";
		$paises["Chade"] 					= "Chade";
		$paises["Chile"] 					= "Chile";
		$paises["China"] 					= "China";
		$paises["Cidade do Vaticano"] 		= "Cidade do Vaticano";
		$paises["Colômbia"] 				= "Colômbia";
		$paises["Congo"] 					= "Congo";
		$paises["Coréia do Sul"] 			= "Coréia do Sul";
		$paises["Costa do Marfim"]			= "Costa do Marfim";
		$paises["Costa Rica"] 				= "Costa Rica";
		$paises["Croácia"] 					= "Croácia";
		$paises["Dinamarca"]				= "Dinamarca";
		$paises["Djibuti"] 					= "Djibuti";
		$paises["Dominica"] 				= "Dominica";
		$paises["EUA"] 						= "EUA";
		$paises["Egito"] 					= "Egito";
		$paises["El Salvador"] 				= "El Salvador";
		$paises["Emirados Árabes"] 			= "Emirados Árabes";
		$paises["Equador"] 					= "Equador";
		$paises["Eritréia"] 				= "Eritréia";
		$paises["Escócia"] 					= "Escócia";
		$paises["Eslováquia"] 				= "Eslováquia";
		$paises["Eslovênia"] 				= "Eslovênia";
		$paises["Espanha"] 					= "Espanha";
		$paises["Estônia"] 					= "Estônia";
		$paises["Etiópia"] 					= "Etiópia";
		$paises["Fiji"] 					= "Fiji";
		$paises["Filipinas"] 				= "Filipinas";
		$paises["Finlândia"]				= "Finlândia";
		$paises["França"] 					= "França";
		$paises["Gabão"] 					= "Gabão";
		$paises["Gâmbia"] 					= "Gâmbia";
		$paises["Gana"] 					= "Gana";
		$paises["Geórgia"] 					= "Geórgia";
		$paises["Gibraltar"]				= "Gibraltar";
		$paises["Granada"] 					= "Granada";
		$paises["Grécia"] 					= "Grécia";
		$paises["Guadalupe"] 				= "Guadalupe";
		$paises["Guam"] 					= "Guam";
		$paises["Guatemala"] 				= "Guatemala";
		$paises["Guiana"] 					= "Guiana";
		$paises["Guiana Francesa"] 			= "Guiana Francesa";
		$paises["Guiné-bissau"] 			= "Guiné-bissau";
		$paises["Haiti"] 					= "Haiti";
		$paises["Holanda"] 					= "Holanda";
		$paises["Honduras"] 				= "Honduras";
		$paises["Hong Kong"] 				= "Hong Kong";
		$paises["Hungria"] 					= "Hungria";
		$paises["Iêmen"] 					= "Iêmen";
		$paises["Ilhas Cayman"] 			= "Ilhas Cayman";
		$paises["Ilhas Cook"] 				= "Ilhas Cook";
		$paises["Ilhas Curaçao"] 			= "Ilhas Curaçao";
		$paises["Ilhas Marshall"] 			= "Ilhas Marshall";
		$paises["Ilhas Turks & Caicos"] 	= "Ilhas Turks & Caicos";
		$paises["Ilhas Virgens (brit.)"] 	= "Ilhas Virgens (brit.)";
		$paises["Ilhas Virgens(amer.)"] 	= "Ilhas Virgens(amer.)";
		$paises["Ilhas Wallis e Futuna"] 	= "Ilhas Wallis e Futuna";
		$paises["Índia"] 					= "Índia";
		$paises["Indonésia"] 				= "Indonésia";
		$paises["Inglaterra"] 				= "Inglaterra";
		$paises["Irlanda"] 					= "Irlanda";
		$paises["Islândia"] 				= "Islândia";
		$paises["Israel"] 					= "Israel";
		$paises["Itália"] 					= "Itália";
		$paises["Jamaica"] 					= "Jamaica";
		$paises["Japão"] 					= "Japão";
		$paises["Jordânia"] 				= "Jordânia";
		$paises["Kuwait"] 					= "Kuwait";
		$paises["Latvia"] 					= "Latvia";
		$paises["Líbano"] 					= "Líbano";
		$paises["Liechtenstein"] 			= "Liechtenstein";
		$paises["Lituânia"] 				= "Lituânia";
		$paises["Luxemburgo"] 				= "Luxemburgo";
		$paises["Macau"] 					= "Macau";
		$paises["Macedônia"]				= "Macedônia";
		$paises["Madagascar"] 				= "Madagascar";
		$paises["Malásia"] 					= "Malásia";
		$paises["Malaui"] 					= "Malaui";
		$paises["Mali"] 					= "Mali";
		$paises["Malta"] 					= "Malta";
		$paises["Marrocos"] 				= "Marrocos";
		$paises["Martinica"] 				= "Martinica";
		$paises["Mauritânia"] 				= "Mauritânia";
		$paises["Mauritius"] 				= "Mauritius";
		$paises["México"] 					= "México";
		$paises["Moldova"] 					= "Moldova";
		$paises["Mônaco"] 					= "Mônaco";
		$paises["Montserrat"] 				= "Montserrat";
		$paises["Nepal"] 					= "Nepal";
		$paises["Nicarágua"] 				= "Nicarágua";
		$paises["Niger"] 					= "Niger";
		$paises["Nigéria"] 					= "Nigéria";
		$paises["Noruega"] 					= "Noruega";
		$paises["Nova Caledônia"] 			= "Nova Caledônia";
		$paises["Nova Zelândia"] 			= "Nova Zelândia";
		$paises["Omã"] 						= "Omã";
		$paises["Palau"]					= "Palau";
		$paises["Panamá"] 					= "Panamá";
		$paises["Papua-nova Guiné"] 		= "Papua-nova Guiné";
		$paises["Paquistão"] 				= "Paquistão";
		$paises["Peru"] 					= "Peru";
		$paises["Polinésia Francesa"] 		= "Polinésia Francesa";
		$paises["Polônia"] 					= "Polônia";
		$paises["Porto Rico"] 				= "Porto Rico";
		$paises["Portugal"] 				= "Portugal";
		$paises["Qatar"] 					= "Qatar";
		$paises["Quênia"] 					= "Quênia";
		$paises["Rep. Dominicana"] 			= "Rep. Dominicana";
		$paises["Rep. Tcheca"] 				= "Rep. Tcheca";
		$paises["Reunion"] 					= "Reunion";
		$paises["Romênia"] 					= "Romênia";
		$paises["Ruanda"] 					= "Ruanda";
		$paises["Rússia"] 					= "Rússia";
		$paises["Saipan"] 					= "Saipan";
		$paises["Samoa Americana"] 			= "Samoa Americana";
		$paises["Senegal"]					= "Senegal";
		$paises["Serra Leone"] 				= "Serra Leone";
		$paises["Seychelles"] 				= "Seychelles";
		$paises["Singapura"] 				= "Singapura";
		$paises["Síria"] 					= "Síria";
		$paises["Sri Lanka"] 				= "Sri Lanka";
		$paises["St. Kitts & Nevis"] 		= "St. Kitts & Nevis";
		$paises["St. Lúcia"] 				= "St. Lúcia";
		$paises["St. Vincent"] 				= "St. Vincent";
		$paises["Sudão"] 					= "Sudão";
		$paises["Suécia"] 					= "Suécia";
		$paises["Suiça"] 					= "Suiça";
		$paises["Suriname"] 				= "Suriname";
		$paises["Tailândia"] 				= "Tailândia";
		$paises["Taiwan"] 					= "Taiwan";
		$paises["Tanzânia"]					= "Tanzânia";
		$paises["Togo"] 					= "Togo";
		$paises["Trinidad & Tobago"] 		= "Trinidad & Tobago";
		$paises["Tunísia"] 					= "Tunísia";
		$paises["Turquia"] 					= "Turquia";
		$paises["Ucrânia"] 					= "Ucrânia";
		$paises["Uganda"] 					= "Uganda";
		$paises["Uruguai"] 					= "Uruguai";
		$paises["Venezuela"] 				= "Venezuela";
		$paises["Vietnã"]					= "Vietnã";
		$paises["Zaire"] 					= "Zaire";
		$paises["Zâmbia"] 					= "Zâmbia";
		$paises["Zimbábue"] 				= "Zimbábue";
		$paises["Outro"] 					= "Outro";
		return $paises;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getDocumentTypes(){
		$tipo	 = array();
		$tipo["Pl"] = "Cart.Ident.Exp.Conselho Prof.Liberais";
		$tipo["Pf"] = "Cart.Ident.Expedida Policia Federal";
		$tipo["Ar"] = "Carteira Ident.Expedida For&Ccedil;As Armadas";
		$tipo["Jz"] = "Carteira Identidade De Ju&Iacute;Zes";
		$tipo["Rn"] = "CNE - Cart. Identidade De Estrangeiros";
		$tipo["Cn"] = "CNH - Carteira Nacional De Habilitacao";
		$tipo["Rp"] = "CONRERP - Cons. Reg. Prof. Rel. Publicas";
		$tipo["Rc"] = "CORE - Cons. Reg. De Repres. Comerciais";
		$tipo["Ef"] = "COREN - Conselho Reg. De Enfermagem";
		$tipo["Bt"] = "CRB - Cons. Reg. De Biblioteconomia";
		$tipo["Ct"] = "CRC - Conselho Reg. De Contabilidade";
		$tipo["Ec"] = "CRE - Conselho Reg. De Economia";
		$tipo["En"] = "CREA - Cons. Reg. Engen. E Arquitetura";
		$tipo["Ci"] = "CRECI - Cons. Reg. De Corret. De Imoveis";
		$tipo["As"] = "CRESS - Cons. Reg. De Assist. Sociais";
		$tipo["Fa"] = "CRF - Conselho Reg. De Farmacia";
		$tipo["Md"] = "CRM - Conselho Reg. De Medicina";
		$tipo["Mv"] = "CRMV - Cons. Reg. De Med. Veterinaria";
		$tipo["Nt"] = "CRN - Conselho Reg. De Nutricionista";
		$tipo["Od"] = "CRO - Conselho Reg. De Odontologia";
		$tipo["Ps"] = "CRP - Conselho Reg. De Psicologia";
		$tipo["Ks"] = "CRQ - Conselho Reg. De Quimica";
		$tipo["Am"] = "CRTA - Conselho Reg. Tecnicos De Admin.";
		$tipo["Ae"] = "Ministerio Da Aeronautica";
		$tipo["Mj"] = "Ministerio Da Justica";
		$tipo["Mr"] = "Ministerio Da Marinha";
		$tipo["Me"] = "Ministerio Das Relacoes Exteriores";
		$tipo["Ex"] = "Ministerio Do Exercito";
		$tipo["Ad"] = "OAB - Ordem Dos Advogados Do Brasil";
		$tipo["Pp"] = "Passaporte";
		$tipo["Rg"] = "RG - Registro Geral";
		$tipo["Te"] = "Titulo De Eleitor";
		return $tipo;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getAcronyms(){ // silgas de documentos
		$tipo	 = array();
		$tipo["PL"] = "CI -CPF";
		$tipo["PF"] = "CI-PF";
		$tipo["AR"] = "CI FOR ARM";
		$tipo["JZ"] = "CIJ";
		$tipo["RN"] = "CNE";
		$tipo["CN"] = "CNH";
		$tipo["RP"] = "CONRERP";
		$tipo["RC"] = "CORE";
		$tipo["EF"] = "COREN";
		$tipo["BT"] = "CRB";
		$tipo["CT"] = "CRC";
		$tipo["EC"] = "CRE";
		$tipo["EN"] = "CREA";
		$tipo["CI"] = "CRECI";
		$tipo["AS"] = "CRESS";
		$tipo["FA"] = "CRF";
		$tipo["MD"] = "CRM";
		$tipo["MV"] = "CRMV";
		$tipo["NT"] = "CRN";
		$tipo["OD"] = "CRO";
		$tipo["PS"] = "CRP";
		$tipo["KS"] = "CRQ";
		$tipo["AM"] = "CRTA";
		$tipo["AE"] = "MAER";
		$tipo["MJ"] = "MJ";
		$tipo["MR"] = "MMAR";
		$tipo["ME"] = "M REL EXT";
		$tipo["EX"] = "ME";
		$tipo["AD"] = "OAB";
		$tipo["PP"] = "PASS";
		$tipo["RG"] = "RG";
		$tipo["TE"] = "TIT ELET";
		return $tipo;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getEmitters(){
		$emissor	 = array();
		$emissor["CERP"] 	= "CERP";
		$emissor["CNT"] 	= "CNT";
		$emissor["CORE"]	= "CORE";
		$emissor["CRB"] 	= "CRB";
		$emissor["CRC"] 	= "CRC";
		$emissor["CRE"] 	= "CRE";
		$emissor["CREA"] 	= "CREA";
		$emissor["CREC"] 	= "CREC";
		$emissor["CREN"]	= "CREN";
		$emissor["CRES"]	= "CRES";
		$emissor["CRF"] 	= "CRF";
		$emissor["CRM"] 	= "CRM";
		$emissor["CRMV"]	= "CRMV";
		$emissor["CRN"] 	= "CRN";
		$emissor["CRO"] 	= "CRO";
		$emissor["CRP"] 	= "CRP";
		$emissor["CRQ"] 	= "CRQ";
		$emissor["CRTA"] 	= "CRTA";
		$emissor["DPF"]	 	= "DPF";
		$emissor["DETRAN"]	= "DETRAN";
		$emissor["IFP"] 	= "IFP";
		$emissor["MAER"] 	= "MAER";
		$emissor["ME"] 		= "ME";
		$emissor["MFA"] 	= "MFA";
		$emissor["MMA"] 	= "MMA";
		$emissor["MTR"] 	= "MTR";
		$emissor["OAB"] 	= "OAB";
		$emissor["PM"] 		= "PM";
		$emissor["SSP"]		= "SSP";
		return $emissor;
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ****************************************************************************************************************************
?>