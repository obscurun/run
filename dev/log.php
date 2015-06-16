<?
	error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Log de Erros</title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" 		content="text/html; charset=utf8" />
	<meta name="Keywords"					content="palavra,chave" />
	<meta name="Description"				content="website limpo" />
	<meta name="robots" 					content="index, follow" />
	<meta name="robots" 					content="noarchive" />
	<meta http-equiv="Content-Language"		content="pt-br" />
	<meta http-equiv="Cache-Control"		content="no-cache, no-store" />
	<meta http-equiv="Pragma"				content="no-cache, no-store" />
	<meta http-equiv="Expires" 				content="Wed, 4 Apr 1984 00:00:01 GMT" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style>
	::-webkit-scrollbar              {
	background: transparent; width:18px;
 }
::-webkit-scrollbar-button       {  							}
::-webkit-scrollbar-track        {  background-image: url("../img/bg/white_wall_hash.png"); }
::-webkit-scrollbar-track-piece  {	 background-image: url("../img/bg/white_wall_hash.png");	}
::-webkit-scrollbar-thumb        {   background-color: rgba(100, 100, 100, .75);							}
::-webkit-scrollbar-corner       { 								}
::-webkit-resizer                {  							}
		*{
			padding:0 0 0 0;
			margin:0 0 0 0;
		}
		body{
			background-color: rgba(10, 10, 10, .1);
			color:#000;
			font-family: futura, arial, sans;
			padding: 30px;
			font-size: 11px;
		}
		h1{
			float: left;
			clear: both;
			width: 100%;
			padding: 5px;
			font-size:22px;
			font-weight: normal;
			border-bottom: 1px dashed rgba(10, 10, 10, .6);
		}
		h2{
			float: left;
			clear: both;
			width: 100%;
			padding: 5px;
			padding-bottom: 0px;
			margin-top: 30px;
			font-size:16px;
			font-weight: normal;
			border-bottom: 1px dashed rgba(10, 10, 10, .6);
		}
		.log{
			float: left;
			width: 100%;
			padding: 5px;
			border: 1px dotted rgba(10, 10, 10, .3);
			border-bottom: 0px;
		}
		.log:nth-child(odd){
			background-color: rgba(255, 255, 255, .15);
		}
		.intern{
			display: none;
		}
		.msg{
			float:left;
		}
		.function{
			float:right;
			clear: none;
			width: 140px;
			padding-right: 10px;
		}
		.class{
			float:right;
			clear: none;
			width: 140px;
			padding-right: 10px;
		}
		.inicio{
			font-weight: bold;
			margin-top: 35px;
			color:#662D91;
			background-color: rgba(41, 171, 226, .2) !important;
		}
		.Mysql, .kit, .run, .Warning{
			color:#9E005D;
		}
		.View{
			color:#0071BC;
		}
		.RouterMethods, .Notice{
			color:#00A99D;
		}
		.RouterPage{
			color:#22B573;
		}
		.RouterBase{
			color:#009245;
		}
		.Debug{
			color:#662D91;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.Language{
			color:#6B9922;
		}
		.client{
			float:right;
		}
		.left{
			float:left;
			width: auto;
		}
		.right{
			float:right;
			width: 35%;
			width: auto;
		}
		a{
			text-decoration: none;
			color:#C1272D !important;
			margin-top: 10px;
			display: block;
			float: left;
			clear: both;
		}
		a.right{
			clear: none;
		}
		a:hover{
			color:#ED1C24 !important;
		}
		h2 a {
			font-size: 11px;
			display: block;
			float: right;
			padding-top: 5px;
			margin-top: 0px;
		}
		h1 a {
			font-size: 12px;
			display: block;
			float: right;
			padding-top: 11px;
			margin-top: 0px;
		}
		hr{
			float: left;
			clear: both;
			width: 100%;
			border:1px dotted rgba(10,10,10, .2);
		}
		.dados_servidor div.left{
			margin-right: 20px;
		}
	</style>

</head>
<body>
	<h1>Logs Registrados <a href='?' class="right"> Listar Todos os Logs </a></h1>
<?
	function convert_size($size){
	    $unit=array('b','kb','mb','gb','tb','pb');
	    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}

	if($_GET['f']){
		if($_GET['f'] == "todos"){
			$dir_log = "../../../run_logs";

			$logs = scandir($dir_log);

			//print_r($logs);
			foreach($logs as $k => $file){
				if($file == "." || $file == "..") continue;
				unlink("../../../run_logs/".$file);
			}
			echo "Todos os arquivos deletados;";
		}else{
			$f = @unlink("../../../run_logs/".$_GET['f']);
			if($f) echo "Arquivo {$_GET['f']} deletado.";

			$logs = scandir("../../../run_logs/");

			echo "<br /><pre>";
			print_r($logs);
			echo "</pre>";

			echo "<br /><a href='?f=todos'> Deletar Todos os Logs </a>";
		}
		exit;
	}


	$dir_log = "../../../run_logs";

	$logs = scandir($dir_log);

	//print_r($logs);
	foreach($logs as $k => $file){
		if($file == "." || $file == "..") continue;
		//echo "<br>Abrindo: ".$dir_log."/".$file."<br>";
		if(isset($_GET['list'])){
			if($_GET['list'] !=  $file) continue;
		}else{
			echo "<br /><a href='?list=$file'> Abrir $file / ". number_format(filesize($dir_log."/".$file)/1014, 3) ." kb </a>";
			echo "<a href='?f=$file' class='right'> Apagar $file </a>";
			echo "<hr />";
			continue;
		}
		echo "\n\r<div class='arquivo'>";
		$file_opened = fopen($dir_log."/".$file, "r") or die("Unable to open file : ".$file);
		/* $xml = simplexml_load_string("<?xml version='1.0'?> <document><tag atributo='exemplo' /></document> ") or die ("erro no parse"); */
		$c = (fread($file_opened,filesize($dir_log."/".$file)));
		$c = str_replace("&","&amp;", $c);
		//echo $c." AAAAAAAAAAAA"; exit;
		$xml = simplexml_load_string("<?xml version='1.0'?> <logs> ".  $c  ." </logs> ") or die ("erro no parse");
		fclose($file_opened);
 		/*
 		$xml = simplexml_load_string("<?xml version='1.0'?> <document><tag atributo='exemplo'>conteudo1</tag><tag atributo='exemplo'>conteudo2</tag></document> ") or die ("erro no parse"); 
		echo $xml;
		echo "teste ". $xml->count()."<br>"; //.$xml->logs[0]->log[1]->msg;
		echo "teste ". $xml->tag[0]."<br>";
		foreach($xml as $k => $v){
			echo "$k <br>";
		}
		exit;
		*/
		//echo "kd?".$xml->count();
		$total = count($xml->children());
		//$total = $xml->count();
		if(($total) <= 0) $total = $xml->length;

		$listar = (isset($_GET['tudo'])) ? "<a href='?list=$file'> Listar Apenas 600 </a>" : "<a href='?tudo=true&list=$file'> Listar Tudo </a>";

		echo "<h2> Arquivo <b>". $file ."</b> | ". number_format(filesize($dir_log."/".$file)/1014, 3) ." kbytes / $total logs $listar </h2>";


		$reverse = false;
		if($reverse){
			$ix = $xml->count();
			//echo $ix;
			while($ix){
				$ix--;
				$classe_ = (strpos($xml->log[$ix]->msg, "RUNNING") > 1) ? "inicio":"";
				echo "\n\r\t<div class='log ". $xml->log[$ix]->class ." " . $classe_ . "'>";

				$f = explode("run\php", $xml->log[$ix]->file);
				$f = $f[1];

				if($classe_ == "inicio"){
					echo "\n\r\t\t<div class='msg left'>". $xml->log[$ix]->msg ."</div>";
					echo "\n\r\t<div class='client'>". $xml->log[0]->client ." / ". $xml->log[0]->ip ." / ". $xml->log[0]->date ."</div>";
				}
				else echo "\n\r\t\t<div class='msg' title='". $f ." / " . $xml->log[$ix]->memory . "'>". $xml->log[$ix]->msg ."</div>";
				echo "\n\r\t\t<div class='function'>". $xml->log[$ix]->function ."</div>";
				echo "\n\r\t\t<div class='class'>". $xml->log[$ix]->class ."</div>";
				echo "\n\r\t</div>";
			}
		}else{
			$ix = -1;
			if($total > 600) $ix = $total-600;
			if(isset($_GET['tudo'])) $ix = -1;
			while($ix < $total-1){
				$ix++;
				if($xml->errorentry[$ix]->type){
					echo "\n\r\t<div class='log ". $xml->errorentry[$ix]->type ."'>";

					$f = explode("run\php", $xml->errorentry[$ix]->file);
					$f = $f[1];	

					$m = $xml->errorentry[$ix]->msg;

					echo "\n\r\t\t<div class='msg left' title='". $xml->errorentry[$ix]->client ." / IP ". $xml->errorentry[$ix]->ip ." / Tipo ". $xml->errorentry[$ix]->type  ." / RAM ".  convert_size((int)$xml->errorentry[$ix]->memory) ."'>". $m ."</div>";
					echo "<div class='client' title='URI: ". $xml->errorentry[$ix]->uri ."'' >". $xml->errorentry[$ix]->file ." / Linha ". $xml->errorentry[$ix]->line ." / num ". $xml->errorentry[$ix]->num ." / <span>". $xml->errorentry[$ix]->date ."</span></div>";
					
					//echo "<br /><div class='file' title=". $xml->errorentry[0]->client ." / ". $xml->errorentry[0]->ip ." / ". $xml->errorentry[0]->type .">". $xml->errorentry[$ix]->file ." / Linha ". $xml->errorentry[$ix]->line ." / Col ". $xml->errorentry[$ix]->num ."</div>";
					echo "\n\r\t</div>";

				}else{
					$classe_ = (strpos($xml->log[$ix]->msg, "RUNNING") > 1) ? "inicio":"";
					echo "\n\r\t<div class='log ". $xml->log[$ix]->class ." " . $classe_ . "'>";

					$f = explode("run\php", $xml->log[$ix]->file);
					$f = $f[1];	

					$m = $xml->log[$ix]->msg;

					$m = str_replace(" VALUES","\r\nVALUES", $m);

					if($classe_ == "inicio"){
						echo "\n\r\t\t<div class='msg left'>". $m ."<br />URL: ". $xml->log[$ix]->uri ."</div>";
						echo "\n\r\t<div class='client'>". $xml->log[$ix]->client ." / ". $xml->log[$ix]->ip ." / ". $xml->log[$ix]->date ."</div>";
					}
					else echo "\n\r\t\t<div class='msg' title='". $f ." / " . convert_size((int)$xml->log[$ix]->memory) . " '><pre>". $m ."</pre></div>";
					echo "<div class='right'><div class='function'>". $xml->log[$ix]->function ."</div>";
					echo "<div class='class'>". $xml->log[$ix]->class ."</div></div>";
					echo "\n\r\t</div>";
				}
			}

		}



		echo "\n\r</div>";
	}
?>

<br clear="all" />
<br clear="all" />
<? 		echo "<a href='?f=$file' class='right'> <b>Apagar Arquivo Log</b> </a>"; ?>
<br clear="all" />

	<br clear="all" />
	<br clear="all" />
	<br clear="all" />
	<h1>Dados do Servidor</h1>
	<div class="log dados_servidor">
		<div class="left"><b>Memória Limite:</b><span> <? echo ini_get('memory_limit'); ?> </span></div>
		<div class="left"><b>Memória Usada :</b><span> <? echo convert_size(memory_get_peak_usage(true)); ?> </span></div>

		<?
			function get_memory() {
			  foreach(file('/proc/meminfo') as $ri)
			    $m[strtok($ri, ':')] = strtok('');
			  return 100 - round(($m['MemFree'] + $m['Buffers'] + $m['Cached']) / $m['MemTotal'] * 100);
			}
			//echo get_memory();  
		?>
	</div>

</body>
</html>