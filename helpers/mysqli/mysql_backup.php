<?php
require_once("iam_mysql/iam_backup.php");

// ****************************************************************************************************************************
class MysqlBackup{
	//*************************************************************************************************************************
	function __construct($output=false, $struct_only=false, $output=false, $compress=true){
		$connections = Config::getConnectionData();
		$db_host = $connections[Mysql::$active]['host'];
		$db_name = $connections[Mysql::$active]['name'];
		$db_user = $connections[Mysql::$active]['user'];
		$db_pass = $connections[Mysql::$active]['pass'];

		if($output == false) $output = "backup_". date('d_m_Y') .".sql.gz";

		$backup = new iam_backup($db_host, $db_name, $db_user, $db_pass, $struct_only, $output, $compress, './'.$output);
		$backup->perform_backup();
	}
	//*************************************************************************************************************************
	//-------------------------------------------------------------------------------------------------------------------------
}
// ****************************************************************************************************************************
?>