<?php
Run::loadLibrary("phpexcel");
// ****************************************************************************************************************************
class ModelExportToExcel{
	
	public $columns	= array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","V","W","U","T","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AV","AW","AU","AT","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BV","BW","BU","BT","BX","BY","BZ");//
	public $lista	= array();
	public $model	= "";
	public $time_initial	= "";
	public $time_final	= "";
	
	//*************************************************************************************************************************
	function __construct($model=false,$lista=array()){
		$this->model = $model;
		$this->lista = $lista;
		$this->time_initial = microtime(true);
	}
	//*************************************************************************************************************************
	function download($lista=array()){
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		// Set properties
		//Debug::print_r($this->lista);
		$objPHPExcel->getProperties()->setCreator("SCCON")
									 ->setLastModifiedBy(Run::$control->session->get(array('LOGIN', 'USER', 'nome'))." ".Run::$control->session->get(array('LOGIN', 'USER', 'sobrenome')) )
									 ->setTitle("Relatório da tabela ".$this->model->SETTINGS['TABLE'])
									 ->setSubject( "Gerado em ". date("d/m/Y") ." às ".  date("H:i:s"))
									 ->setDescription("Registros Totais: ".count($this->lista))
									 ->setKeywords("")
									 ->setCategory("Relatório SCCON");
		$headerStyle = array(
			'font' => array(
				'bold' => true,
			)
		);
		$trendiStyle = array(
			'font' => array(
				'bold' => false,
				'size' => "15"
			)

		);
		$nome_relatorio = ( isset($this->model->SETTINGS['NICK_NAME']) ) ? $this->model->SETTINGS['NICK_NAME'] : $this->model->SETTINGS['TABLE'] ;
		$sheet = $objPHPExcel->getActiveSheet();
		$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1", ("Relatório - ". $nome_relatorio . " - Gerado em ". date("d/m/Y") ." às ".  date("H:i:s")));
		$sheet->getStyle('A1')->applyFromArray($trendiStyle);
		$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getStyle('A1:'. $this->columns[count($this->lista[0])] .'1')->getFill()
		->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('3EB1E2')->setRGB('3EB1E2');

		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

		if(isset($this->lista[0])){
			$n=0;
			$objPHPExcel->setActiveSheetIndex(0);
			$sheet = $objPHPExcel->getActiveSheet();
			foreach($this->lista[0] as $field => $value){
			//	$ordenado	= ($this->lista['ordem'] == $field)					? ' ordem_'.$this->lista['contramodo']	: "";            
			//	$classe		= ($field == $this->model->SETTINGS['PK']) 		? " class='id $ordenado'" 			: " class='$ordenado'";
				$width		= isset($value['size'])							? $value['size']					: 100;
				$width		= $width/4;
				if((int)$width <=5) $width = 15;
				$label		= isset($value['label'])						? $value['label']					: $field;

	            if(is_array($value)){
					foreach($value as $f => $v){
						if(is_array($v)){
			            	foreach($v as $field_multiple => $value_multiple){
				            	$width		= isset($value_multiple['size'])		? $value_multiple['size']		:	$width;
				            	if(isset($value_multiple['label'])){
				            		$label	= $value_multiple['label'];
				            		$field	= $field_multiple;
				            		break;
				            	}
				            }
						}
			            if($label != $field) break;
			        }
	            }
				if(count($value) || is_array($value)){
						$sheet->setCellValue($this->columns[$n].'2', Run::$control->string->mixed_to_utf8($label));
						
						$sheet->getColumnDimension($this->columns[$n])->setWidth((int)$width);
						//$sheet->getColumnDimension($this->columns[$n])->setRowHeight(20);
						$sheet->getRowDimension(2)->setRowHeight(15);
						$sheet->getStyle($this->columns[$n].'2')->applyFromArray($headerStyle);
				}
				$n++;
			}			
			$row=3;
			$col=0;
			foreach($this->lista as $key => $fields){				
				foreach($fields as $field => $value){
					if(is_array($value)){
						$n=0;
						$val = "";
						foreach($value as $f => $v){
							if(is_array($v)){
								foreach($v as $field_multiple => $value_multiple){
										$val .= $value_multiple['value'] ;
										if(count($value) > 1 && $n < count($value)-1) $val .=", " ;
										$n++;
						        }
					        }
				        }
					}
					if($val != "") $value['value'] = $val;
					else if( $value['value_label'] != "") $value['value'] = $value['value_label'];
			        //$sheet->setCellValueByColumnAndRow($col, $row, $value['value']);
					if($value['value'] != "") $sheet->setCellValue($this->columns[$col].$row, $value['value']);			
			        $col++;	
				}
				$col=0;
				$row++;
			}
		}else{
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', utf8_encode("Lista não disponível."));
		}
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle(utf8_encode($this->model->SETTINGS['TABLE']));
			
		
		$t = (microtime(true) - $this->time_initial) ;
		//$sheet->setCellValueByColumnAndRow(0, ($row++)+1, "Processado em: ".$t);
		$objPHPExcel->getProperties()->setKeywords("Gerado em ".number_format((float)$t, 3, '.', '')." seg. " );

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //application/vnd.openxmlformats-officedocument.spreadsheetml.sheet  //  application/vnd.ms-excel
		header('Content-Disposition: attachment;filename="relatorio_'.$this->model->SETTINGS['TABLE'].'_'. date("dmY_His") .'.xlsx"');
		header('Cache-Control: max-age=0');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

		Action::logAdmin(Config::DB."admins", (int)Run::$control->session->get(array('LOGIN', 'USER', 'pk_admin')), 7, 'Download de relatório '.$this->model->SETTINGS['TABLE'], 1);
	
		exit;
		
	}
}

?>