<?php
// *******************************************************************************************************************************
class Benchmark{
	public $marker = array();
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){
		Debug::log("Iniciando Core/log/Benchmark.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(isset($_GET['benchmark'])) Run::$DEBUG_BENCHMARK = true;
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function mark($name){
		if(!Run::$DEBUG_BENCHMARK) return false;
		$this->marker[$name] = microtime(TRUE);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function elapsedTime($point1 = '', $point2 = '', $decimals = 4){
		if(!Run::$DEBUG_BENCHMARK) return false;
		if($point1 === ''){
			return '{elapsed_time}';
		}
		if(! isset($this->marker[$point1])){
			return '';
		}
		if(! isset($this->marker[$point2])){
			$this->marker[$point2] = microtime(TRUE);
		}
		return number_format($this->marker[$point2] - $this->marker[$point1], $decimals);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function writeMark($point1 = '', $point2 = '', $decimals = 4){ 
		if(!Run::$DEBUG_BENCHMARK) return false;
		$result = $this->elapsedTime($point1, $point2, $decimals);
		//$pointsNames = Run::$control->string->pad($point1." /> ".$point2."   ", 120, " ", STR_PAD_LEFT);
		$pointsNames = Run::$control->string->pad($point1.' ', 56, ' ', STR_PAD_LEFT);
		$pointsNames .= ' /> ';
		$pointsNames .= Run::$control->string->pad($point2.'  ', 56, ' ', STR_PAD_LEFT);
		echo "<script> if(console.debug) console.debug('Benchmark:". $pointsNames ."$result'); </script>";
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	public function memory_usage(){
		return '{memory_usage}';
	}
}

?>