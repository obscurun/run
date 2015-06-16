<?php
// ****************************************************************************************************************************
class OrderData{
	//*************************************************************************************************************************
	function OrderData(){
		Debug::log("Iniciando Core/Form/OrderTables.", __LINE__, __FUNCTION__, __CLASS__, __FILE__);
	}

	//*************************************************************************************************************************
	public function getOrderedTables($data, $schema){
		$tables_order_ref = $this->prepareOrdertables($schema);
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		if(count($tables_order_ref ) < 1){
			Error::show(0, "tables_order_ref:: Não foram geradas ordens a serem gravadas");
		}
		//-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -
		return $tables_order_ref ;
	}
	//*************************************************************************************************************************
	private function prepareOrdertables($schema){
		$orderTables = array();
		foreach($schema['from'] as $k => $table){
			if($table['save'] !== false){
				$refTable = $table['table_nick'] != "" ? $table['table_nick'] : $table['table'] ;
				$orderTables[$refTable]["pk"] 	 = $table['pk'];
				$orderTables[$refTable]["table"] = $table['table'];
				$orderTables[$refTable]["joineds"] = $this->recursiveOrderJoinCheck($schema['join'], $table['table'], $table['table_nick']);
			}
		}
		return $orderTables;
	}
	//*************************************************************************************************************************
	private function recursiveOrderJoinCheck($joins, $table, $table_nick){
		$orderTables = array();
		foreach($joins as $k => $joinTable){
			if($joinTable['save'] !== false){
				if($joinTable['table_ref'] == $table || $joinTable['table_ref'] == $table_nick){
					$nameTable = $joinTable['table_nick'] != "" ? $joinTable['table_nick'] : $joinTable['table'];
					$orderTables[$nameTable]["pk"] 	  		= $joinTable['pk'];
					$orderTables[$nameTable]["pk_ref"] 		= $joinTable['pk_ref'];
					$orderTables[$nameTable]["fk_ref"] 		= $joinTable['fk_ref'];
					$orderTables[$nameTable]["table"] 		= $joinTable['table'];
					$orderTables[$nameTable]["multiple"]	= $joinTable['multiple'];
					$orderTables[$nameTable]["status_name"] = $joinTable['status_name'];
					$orderTables[$nameTable]["joineds"] 	= $this->recursiveOrderJoinCheck($joins, $joinTable['table'], $joinTable['table_nick']);
				}
			}
		}
		return $orderTables;
	}
	//*************************************************************************************************************************
}
// ############################################################################################################################
?>