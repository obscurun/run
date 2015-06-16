<?php
// ****************************************************************************************************************************
class Action{
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	function __construct(){
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	static public function registerAccess(){
		if(Run::ACTION_LOG !== true) return false;
		$actionmsg = Run::$control->string->cropStr($actionmsg, 1400);
		if(Run::ACTION_LOG === true && Run::ACTION_LOG_IS_ADMIN == true){
			Run::$mysql->query(
				"INSERT INTO ". Config::DB ."log_access 
				(fk_admin, table_ref, fk_table_ref, action, description, ip, date_insert, status) 
				VALUES
				('". (int)Run::$control->session->get(array('LOGIN', 'USER', 'pk_admin')) ."', '$table', '$fk_table', '$actionID', '$actionmsg','". $_SERVER['REMOTE_ADDR'] ."','". Date::$TODAY['DATETIME'] ."', '$status') ",
				__LINE__, __FUNCTION__, __CLASS__, __FILE__
			);
		}
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	static public function logRun($table, $fk_table, $actionID, $actionmsg, $status='3'){
		$actionmsg = Run::$control->string->cropStr($actionmsg, 1400);
		if(Run::ACTION_LOG === true)	Run::$mysql->query("INSERT INTO ". Config::DB ."logs (fk_admin, table_ref, fk_table_ref, action, description, ip, date_insert, status) VALUES('". (int)Run::$control->session->get(array('LOGIN', 'USER', 'pk_admin')) ."', '$table', '$fk_table', '$actionID', '$actionmsg','". $_SERVER['REMOTE_ADDR'] ."','". Date::$TODAY['DATETIME'] ."', '$status') ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	static public function logAdmin($table, $fk_table, $actionID, $actionmsg, $status='2'){
		$actionmsg = Run::$control->string->cropStr($actionmsg, 1400);
		if(Run::ACTION_LOG === true)	Run::$mysql->query("INSERT INTO ". Config::DB ."log_admins (fk_admin, table_ref, fk_table_ref, action, description, ip, date_insert, status) VALUES('". (int)Run::$control->session->get(array('LOGIN', 'USER', 'pk_admin')) ."', '$table', '$fk_table', '$actionID', '$actionmsg','". $_SERVER['REMOTE_ADDR'] ."','". Date::$TODAY['DATETIME'] ."', '$status') ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
	static public function logUsers($table, $fk_table, $actionID, $actionmsg, $status='2'){
		$actionmsg = Run::$control->string->cropStr($actionmsg, 1400);
		if(Run::ACTION_LOG === true)	Run::$mysql->query("INSERT INTO ". Config::DB ."log_users (fk_user, table_ref, fk_table_ref, action, description, ip, date_insert, status) VALUES('". (int)Run::$control->session->get(array('LOGIN', 'USER', 'pk_user')) ."', '$table', '$fk_table', '$actionID', '$actionmsg','". $_SERVER['REMOTE_ADDR'] ."','". Date::$TODAY['DATETIME'] ."', '$status') ", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
	//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
}
// ****************************************************************************************************************************

?>