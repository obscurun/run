<?php
// ****************************************************************************************************************************
class Date{
	public static $TODAY = array();
	//*************************************************************************************************************************
	function __construct(){
		$this->getVals();
	}
	//*************************************************************************************************************************
	public function getVals(){
		self::$TODAY = $this->fullConversion(@date("Y-m-d H:i:s"));
	}
	
    public function getDate(){
        return self::$TODAY;
    }
	//-------------------------------------------------------------------------------------------------------------------------
	public function fullConversion($data, $return_type='ARRAY'){
		if(is_string($data) == 'string'){ if(((int)strripos($data, '/') == 5 || (int)strripos($data, '-') == 5)){ $data = $this->convertInvert($data);} }
		$data_us 	= $data; 
		//echo "<br>  $data  ==== ".strripos($data, '/');
			
		if(is_numeric($data_us)) $data_us	= @date("Y-m-d H:i:s", $data_us);
		if (!is_numeric($data_us)) {
			$data_us	= $this->convertInvert($data_us);
			$data_us	= $this->convertInvert($data_us);
			$data_br	= $this->convertInvert($data_us);
			$data		= explode(' ',$data_us);
			$_data		= explode('/',$data[0]);
			$ano		= (int)$_data[0];
			$mes		= (int)$_data[1];
			$dia		= (int)$_data[2];
			$_horario	= explode(':', $data[1]);
			$hora		= (int)$_horario[0];
			$minuto		= (int)$_horario[1];
			$segundo	= (int)$_horario[2];
			$mktime		= @mktime((int)$hora, (int)$minuto, (int)$segundo, (int)$mes, (int)$dia, (int)$ano);
		}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		$array_day_nick 		= array("Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab");
		$array_day_name 		= array("Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado");
		$array_month_nick 		= array("Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez");
		$array_month_name 		= array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
		$array_day_nick_eng		= array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
		$array_day_name_eng 	= array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
		$array_month_nick_eng 	= array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Set", "Sept", "Oct", "Dec");
		$array_month_name_eng 	= array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		$diasemana		= @date("w", $mktime);
		$data_ex 		= explode(" ", $data_us);
		if(strripos($data_ex[0], "-")){		$date_partes 	= explode("-", $data_ex[0]); }
		else{ 								$date_partes 	= explode("/", $data_ex[0]); }
		$time_partes 	= explode(":", $data_ex[1]);
		$dia 			= $date_partes[2];
		$mes 			= $date_partes[1];
		$ano 			= $date_partes[0];
		$hora 			= $time_partes[0];
		$minuto 		= $time_partes[1];
		$segundo 		= $time_partes[2];
		$data 			= $dia . "/" . $mes . "/" . $ano;
		$time 			= $data_ex[1];
		
		if(!isset($array_month_nick[$mes-1])) $array_month_nick[$mes-1] = null;
		if(!isset($array_month_name[$mes-1])) $array_month_name[$mes-1] = null;
		if(!isset($array_month_nick_eng[$mes-1])) $array_month_nick_eng[$mes-1] = null;
		if(!isset($array_month_name_eng[$mes-1])) $array_month_name_eng[$mes-1] = null;
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		$array_data 	= array("MKTIME"			=> $mktime,
								"DATE"				=> $data,
								"DATE_US"			=> $data_us,
								"DATE_BR"			=> $data_br,
								"DATETIME"			=> $data_us,
								"DATE_MYSQL"		=> $ano."-".$mes."-".$dia, //.' '.$hora.':'.$minuto.':'.$segundo,
								"DATEPHP"			=> $data.' '.$hora.':'.$minuto.':'.$segundo,
								"TIME"				=> $time,
								"DAY_NICK"			=> $array_day_nick[$diasemana],
								"DAY_NAME"			=> $array_day_name[$diasemana],
								"DAY_NICK_ENG"		=> $array_day_nick_eng[$diasemana],
								"DAY_NAME_ENG"		=> $array_day_name_eng[$diasemana],
								"DAY"				=> $dia,
								"DAY_WEEK"			=> $diasemana,
								"TOTAL_DAYS"		=> @date("t", $mktime),
								"MONTH"				=> $mes,
								"MONTH_NICK"		=> $array_month_nick[$mes-1],
								"MONTH_NAME"		=> $array_month_name[$mes-1],
								"MONTH_NICK_ENG"	=> $array_month_nick_eng[$mes-1],
								"MONTH_NAME_ENG"	=> $array_month_name_eng[$mes-1],
								"FORMAT_DATE"		=> $array_day_nick_eng[$diasemana].', '.$dia.' '.$array_month_nick_eng[$mes-1].' '.$ano.' '.$hora.':'.$minuto.':'.$segundo.' ' ."-0200",
								"YEAR" 				=> $ano,
								"HOURS" 			=> $hora.":".$minuto,
								"HOUR" 				=> $hora,
								"MINUTE" 			=> $minuto,
								"SECOND" 			=> $segundo,
								"ONLY_DATE_BR"		=> $dia."/".$mes."/".$ano,
								"TIME_BR_FORMATED"	=> $dia."/".$mes."/".$ano ." ás ".$hora.":".$minuto,
								"TIME_BR_FORMATED_FULL"	=> $dia."/".$mes."/".$ano ." ás ".$hora.":".$minuto.":".$segundo,
								"ONLY_DATE_BR"		=> $dia."/".$mes."/".$ano,
								"ONLY_HOURS_MINUTES"=> $hora.':'.$minuto);
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		switch($return_type){
			Case "MKTIME":
				return $mktime;			break;
			Case "DATE":
				return $data;			break;
			Case "DATE_US":
				return $data_us;		break;
			Case "DATE_BR":
				return $data_br;		break;
			Case "DATETIME":
				return $data_us;		break;
			Case "DATEPHP":
				return $array_data["DATEPHP"];			break;
			Case "TIME":
				return $time;							break;
			Case "DAY_WEEK":
				return $diasemana;						break;
			Case "DAY_NICK":
				return $array_day_nick[$diasemana];		break;
			Case "DAY_NAME":
				return $array_day_name[$diasemana];		break;
			Case "DAY_NICK_ENG":
				return $array_day_nick_eng[$diasemana];	break;
			Case "DAY_NAME_ENG":
				return $array_day_name_eng[$diasemana];	break;
			Case "FORMAT_DATE":
				return $array_data["FORMAT_DATE"];		break;
			Case "TOTAL_DAYS":
				return @date("t", $mktime);				break;
			Case "TIME":
				return $time;			break;
			Case "DAY":
				return $dia;			break;
			Case "MONTH":
				return $mes;			break;
			Case "YEAR":
				return $ano;			break;
			Case "HOUR":
				return $hora;			break;
			Case "MINUTE":
				return $minuto;			break;
			Case "SECOND":
				return $segundo;		break;
			Case "ONLY_DATE_BR":
				return $array_data["ONLY_DATE_BR"];		break;
			Case "TIME_BR_FORMATED":
				return $array_data["TIME_BR_FORMATED"];		break;
			Case "TIME_BR_FORMATED_FULL":
				return $array_data["TIME_BR_FORMATED_FULL"];		break;
			Case "ONLY_HOURS_MINUTES":
				return $array_data["ONLY_HOURS_MINUTES"];		break;
			Default:
				return $array_data;		break;
		}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function subtractDate($date, $dias=0, $meses=0, $anos=0, $horas=0, $minutos=0, $segundos=0){
		if(!is_array($date)){ $date = $this->fullConversion($date);	}
		$date = @mktime((int)$date['HOUR']-(int)$horas, (int)$date['MINUTE']-(int)$minutos, (int)$date['SECOND']-(int)$segundos, (int)$date['MONTH']-(int)$meses, (int)$date['DAY']-(int)$dias, (int)$date['YEAR']-(int)$anos);
		return $this->fullConversion($date);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function sumDate($date, $dias=0, $meses=0, $anos=0, $horas=0, $minutos=0, $segundos=0){
		if(!is_array($date)){ $date = $this->fullConversion($date);	}
		$date = @mktime((int)$date['HOUR']+(int)$horas, (int)$date['MINUTE']+(int)$minutos, (int)$date['SECOND']+(int)$segundos, (int)$date['MONTH']+(int)$meses, (int)$date['DAY']+(int)$dias, (int)$date['YEAR']+(int)$anos);
		return $this->fullConversion($date);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function convertToDate($date){
		$data = explode("/", $date);
		$data = @date("Y-m-d H:i:s", @mktime(0, 0, 0, $data[1], $data[0], $data[2]));

		return $data;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function convertInvert($date){
		$data_ = explode(" ", trim($date));
		$data_b = $data_; 
		if(strripos($data_[0], "/"))	$data = explode("/", $data_[0]);
		if(strripos($data_b[0], "-"))	$data = explode("-", $data_b[0]);
		if(isset($data_[1])){
			if(strripos($data_[1], ":"))	$horario = explode(":", $data_[1]);					
		}
		if(isset($data_b[1])){
			if(strripos($data_b[1], "-"))	$horario = explode("-", $data_b[1]);				
		}
		if(!isset($data[0])) $data[0] = "00";
		if(!isset($data[1])) $data[1] = "00";
		if(!isset($data[2])) $data[2] = "00";
		if(!isset($horario[0])) $horario[0] = "00";
		if(!isset($horario[1])) $horario[1] = "00";
		if(!isset($horario[2])) $horario[2] = "00";
		$date = $data[2] . "/" . $data[1] . "/" . $data[0] ." ". $horario[0] .":". $horario[1] .":". $horario[2];
		
		return $date;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function convertMysqltoBr($date){
		$data_ = explode(" ", trim($date));
		$data = explode("-", $data_[0]);
		return $data[2] . "/" . $data[1] . "/" . $data[0] ." ". $data_[1];
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function convertBrToUs($date){
		$data_ = explode(" ", trim($date));
		$data = explode("/", $data_[0]);
		$horario = explode("/", $data_[1]);		
		
		$date = $data[2] . "/" . $data[1] . "/" . $data[0] ." ". $data_[1];
		
		return $date;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function convertToMysql($date){
		$data = $this->dateConversion($date);		
		$data = $data['DATETIME'];		
		return $data;
	}
	//-------------------------------------------------------------------------------------------------------------------------
}

?>