<?
	$dir_log = "../../../run_logs";

	$logs = scandir($dir_log);

	//print_r($logs);
	foreach($logs as $k => $file){
		if($file == "." || $file == "..") continue;
		$file_opened = fopen($dir_log."/".$file, "r") or die("Unable to open file : ".$file);
		echo fread($file_opened,filesize($dir_log."/".$file));
		fclose($file_opened);
	}
?>