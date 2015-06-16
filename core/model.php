<?php
require(RUN_PATH."core/model/mysql.php");
require(RUN_PATH."core/model/mysql_query.php");
//*****************************************************************************************************************************
class Model{
	public 	 	 	 $mysql				= "";
	public 	 	 	 $postgre			= "";
	public 		 	 $sqlite			= "";
	public 			 $query				= "";
	public 			 $form				= "";
	protected static $connectionData	= array();
	//*************************************************************************************************************************
	function Model(){
		Debug::log("Iniciando Core/Model.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$this->startMysql();
	}
	//*************************************************************************************************************************
	function startMysql(){
		if(Run::MYSQL === true){
			$this->dataBase = Mysql::getInstance();
			$this->query 	= new MysqlQuery();
		}
	}
	//*************************************************************************************************************************
	static public function getInstance(){
		if(Run::MYSQL === true){
			return Mysql::getInstance();
		}else{
			return false;
		}
	}
	//*************************************************************************************************************************
	static public function setConnectionData($host,$name,$user,$pass,$index){
		self::$connectionData[$index]["host"] 		= $host;
		self::$connectionData[$index]["name"] 		= $name;
		self::$connectionData[$index]["user"]  		= $user;
		self::$connectionData[$index]["pass"]  		= $pass;
		Debug::log("setConnectionData($host/$name/$user/$pass/$index)", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}
	//*************************************************************************************************************************
	static public function getConnectionData(){
		return self::$connectionData;
	}
}
//*****************************************************************************************************************************
?>