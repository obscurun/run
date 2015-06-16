<?php
// ****************************************************************************************************************************
// A classe Mysql utiliza o conceito de Singleton e só será instanciada uma vez.
// Para criar a instancia deverá ser utilizado o método getInstance()
// Exemplo: $conexao = Mysql::getInstance($dadosConexao);
//
// $dadosConexao deve ser um array com os dados host,user,password e dbname
// O array deve conter um indice, pois a classe permite a conexão com diversos banco de dados distintos
// Exemplo:
// $dadosConexao['server1']["host"]
// $dadosConexao['server1']["name"]
// $dadosConexao['server1']["user"]
// $dadosConexao['server1']["pass"]

// $dadosConexao['server2']["host"]
// $dadosConexao['server2']["name"]
// $dadosConexao['server2']["user"]
// $dadosConexao['server2']["pass"]
// ############################################################################################################################
class Mysql{
	static private $instance;
	static private $connection 	= array();
	static public  $active	 	= false;
	//*************************************************************************************************************************
	private function __construct(){
		$connectionData = Model::getConnectionData();
		if(count($connectionData)>0){
			Debug::log("Iniciando Mysqli.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
			foreach($connectionData as $conn => $data){
				if(self::$active == false){
					self::$active = $conn;
				}
				$host = $data["host"];
				$name = $data["name"];
				$user = $data["user"];
				$pass = $data["pass"];
				if($host && $name && $user){
					//echo "$host, $user, $pass, $name";
					self::$connection[$conn] = new mysqli($host, $user, $pass, $name); 
					self::$connection[$conn]->set_charset("utf8");
					if(self::$connection[$conn]->connect_error){
						ob_flush();
				        flush();
						Error::show(5200, "Erro ao conectar ao Mysqli. Código:". Run::$control->string->encoding(self::$connection[$conn]->connect_errno .' -- Mensagem:'. self::$connection[$conn]->connect_error), __FILE__, __LINE__, '' );
						return -2;
					} else{ self::$connection[$conn]->set_charset("utf8"); }
				}else{
					Error::show(5200, "Não conecta no MYSQLI: ".$host ." / ". $name ." / ". $user ." / ", __FILE__, __LINE__, '');
					return -2;
				}
			}
		}else{
			Error::show(0, "Dados de conexão não foram definidos ", __FILE__, __LINE__, '');
			exit;
		}
	}
	//*************************************************************************************************************************
	static public function getInstance(){
		Debug::log("getInstance Mysql.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(!isset(self::$instance) || !is_object(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
	}
	//*************************************************************************************************************************
	//-------------------------------------------------------------------------------------------------------------------------
	public function getMysqlError(){
		return self::$connection[self::$active]->error;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getMysqlWarning(){
		$war = "";
		if (mysqli_warning_count(self::$connection[self::$active])) { 
			$e = mysqli_get_warnings(self::$connection[self::$active]); 
		    $war .= "<b>Aviso:</b>"; 
		   	do{ 
		    	$war .= " $e->errno - $e->message\n<br>"; 
		   	}while($e->next()); 
		} 
		return $war;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function query($sql, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__, $conn=false){
		if($conn == false) $conn = self::$active;
		Debug::log("Mysql->query: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if(isset(self::$connection[$conn])){
			//echo "<br><br>sql: ".$sql;
			$sql = $this->treatSpecials($sql);
			$result = self::$connection[$conn]->query($sql);
			//echo "resultado: ".$result;
			if($result){
				return $result;
			}
			else{
				Debug::log("Mysql->query: ERRO: ".self::$connection[$conn]->error ." na linha ".$_line." / ".$_function." / ".$_class." ", $this->_line, $this->_function, $this->_class, $this->_file);
				Error::sqlError("Erro ao executar QUERY."." na linha ".$_line." / ".$_function." / ".$_class." ", self::$connection[$conn]->error, $sql);
				return -1;
			}
		} else{
			Error::show(5552, "A conexão ". $conn." não está disponível. Por favor, tente mais tarde.", $_file, $_line, '');
			return -2;
		}
	}
	public function transactionQuery($sql){
		return self::$connection[self::$active]->query($sql);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getID($conn=false){
		if($conn == false) $conn = self::$active;
		return mysqli_insert_id(self::$connection[$conn]);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function getAffecteds($conn=false){
		if($conn == false) $conn = self::$active;
		return mysqli_affected_rows(self::$connection[$conn]);
	}
	//-------------------------------------------------------------------------------------------------------------------------
    public function autocommit($val){
        Debug::log("Mysql->autocommit", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        mysqli_autocommit(self::$connection[self::$active], $val);
        return;
    }
    //-------------------------------------------------------------------------------------------------------------------------
    public function commit(){
        Debug::log("Mysql->autocommit", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        return mysqli_commit(self::$connection[self::$active]);
    }
    //-------------------------------------------------------------------------------------------------------------------------
    public function rollback(){
        Debug::log("Mysql->rollback", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
        return mysqli_rollback(self::$connection[self::$active]);
    }
    //-------------------------------------------------------------------------------------------------------------------------
	public function multi_query($sql, $_line=__LINE__, $_function=__FUNCTION__, $_class=__CLASS__, $_file=__FILE__, $conn=false){
		if($conn == false) $conn = self::$active;
		Debug::log("Mysql->multi_query: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		$return = array();
		$result = false;
		if((self::$connection[$conn])){
			if (mysqli_multi_query(self::$connection[$conn], $sql)) {
				do {
					if ($result = mysqli_store_result(self::$connection[$conn])) {
						while ($row = mysqli_fetch_row($result)) {
							array_push($return, $row[0]);
						}
						mysqli_free_result($result);
					}
					if (mysqli_more_results(self::$connection[$conn])){
						//printf("-----------------\n");
					}
				} while (mysqli_next_result(self::$connection[$conn]));
				return $return;
			}
			else{
				Error::sqlError("Erro ao executar QUERY."." na linha ".$_line." / ".$_function." / ".$_class." ", self::$connection[$conn]->error, $result);
				return -2;
			}
		} else{
			Error::show(5553, "A conexão ".$conn." não está disponível. Por favor, tente mais tarde. (".self::$connection[$conn].")", $_file, $_line, '');
			return -2;
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function escape($str, $conn=false){
		if($conn == false) $conn = self::$active;
		Debug::log("Mysql->escape: $sql", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		return self::$connection[$conn]->real_escape_string($str);
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function treatSpecials($str){
		$value = $str;
		/*
		$value = str_replace("“", "\\\"", $value);
		$value = str_replace("“", "\\\"", $value);
		$value = str_replace("”", "\\\"", $value);
		$value = str_replace("”", "\\\"", $value);
		$value = str_replace("–", "-", $value);
		$value = str_replace("–", "-", $value);
		$value = ereg_replace( chr(149), "&#8226;", $value );    # bullet •
		$value = ereg_replace( chr(150), "&ndash;", $value );    # en dash
		$value = ereg_replace( chr(151), "&mdash;", $value );    # em dash
		$value = ereg_replace( chr(153), "&#8482;", $value );    # trademark
		$value = ereg_replace( chr(169), "&copy;", $value );    # copyright mark
		$value = ereg_replace( chr(174), "&reg;", $value );        # registration mark
		*/
		return $value;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function close($connection_name=false){
		if($connection_name == false) $connection_name = self::$active;
		Debug::log("Mysql->close: $connection_name", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
		if($connection_name){
			if($this->connection[$connection_name]){
				$this->connection[$connection_name]->close();
				$this->connection[$connection_name] = false;
			}
		}else{
			foreach($this->connection as $nome=>$inst){
				$this->connection[$nome]->close();
				$this->connection[$nome] = false;
			}
		}
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function restartInstance(){
		$this->__construct();
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ############################################################################################################################
// http://br3.php.net/manual/en/mysqli.connect.php
// http://websec.wordpress.com/2010/03/19/exploiting-hard-filtered-sql-injections/
?>