<?php
class Render{
	private $listing = "";
	private $form = "";
	private $view = "";
	public	$linkHeader = "?";
// ********************************************************************************************************************************
	public function tableLister($class="listing",$style="",$definitions='cellpadding="0" cellspacing="0" border="0"', $typeIntern = "default", $noAction=false, $getSearch=""){
		$this->model->DATA_INT['contramodo'] = $this->model->DATA_INT['modo'] == "ASC"?"DESC":"ASC";
		//$this->listing = "\r\n"." <div id='_LISTING_REF'\r\n ></div>";
		if(!isset(Template::$STRUCTURE['ACTIVE']['LIST'])) Template::$STRUCTURE['ACTIVE']['LIST'] = "";
		if(!isset(Template::$STRUCTURE['ACTIVE']['VIEW'])) Template::$STRUCTURE['ACTIVE']['VIEW'] = "";
		if(!isset(Template::$STRUCTURE['ACTIVE']['FORM'])) Template::$STRUCTURE['ACTIVE']['FORM'] = "";
		$this->listing = "\r\n"." <div id='_LISTING_REF'\r\n refLIST='". Template::$STRUCTURE['ACTIVE']['LIST'] ."'\r\n refVIEW='". Template::$STRUCTURE['ACTIVE']['VIEW'] ."'\r\n refFORM='". Template::$STRUCTURE['ACTIVE']['FORM']."'></div>";
		
		if($noAction == true) $class .=" no_click";
		$this->listing .= "\r\n".'<table class="'.$class.'" '.$definitions.' style="'.$style.'">';
		//create table head
		$this->listing .= "\r\n\t<thead>";
		$this->listing .= "\r\n\t<tr>";
		//Debug::print_r($this->model->DATA_LIST);
		if(isset($this->model->DATA_LIST[0])){
        foreach($this->model->DATA_LIST[0] as $field => $value){
            $ordenado	= ($this->model->DATA_INT['ordem'] == $field)	? ' ordem_'.$this->model->DATA_INT['contramodo']	:	"";
            $classe		= ($field == $this->model->SETTINGS['PK']) 		? " id $ordenado " 	: " $ordenado ";
            $width		= isset($value['size'])							? $value['size']			:	'';
            $label		= isset($value['label'])						? $value['label']			:	$field;
			foreach($this->model->SETTINGS['JOINS'] as $k => $table){
			//	if(($table['TABLE_NICK'] ==  $label || $table['TABLE'] ==  $label) && count($this->model->DATA_LIST[0][$label]) > 0){ continue 2; break;}
			}

            if(is_array($value) && isset($value['treatView']) && $value['treatView'] != "onlyClass"){
				foreach($value as $f => $v){
					if(is_array($v)){
		            	foreach($v as $field_multiple => $value_multiple){
			            	$width		= isset($value_multiple['size'])		? $value_multiple['size']		:	'';
			            	$ordenado	= ($this->model->DATA_INT['ordem'] == $field_multiple)	? ' ordem_'.$this->model->DATA_INT['contramodo']	:	"";
			            	$classe		= isset($value_multiple['size'])		? " class='id $ordenado'" 	: " class='$ordenado'";
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
			//if(!isset($value[0])){
				if(count($value) || is_array($value)){
					if(isset($value['treatView']) && $value['treatView'] == "onlyClass") continue;
					$this->listing .= "\r\n\t\t".'<th class="'.$classe.'"  width="'.$width.'" id="'.$field.'" field="'. $field .'"><a title="Ordenar"  href="'.$this->linkHeader;
					if($this->linkHeader == "?"){
						if($this->model->DATA_INT['busca'] != '') 		$this->listing .= 'busca='. $this->model->DATA_INT['busca'];
						if(Run::$control->getGet('campo') != '') 		$this->listing .= '&campo='. Run::$control->getGet('campo');
						if(Run::$control->getGet('periodo') != '') 		$this->listing .= '&periodo='. Run::$control->getGet('periodo');
						if(Run::$control->getGet('status_pro') != '') 	$this->listing .= '&status_pro='. Run::$control->getGet('status_pro');
						if(Run::$control->getGet('fk_template_busca') != '') 	$this->listing .= '&fk_template_busca='. Run::$control->getGet('fk_template_busca');
						if(isset($this->model->DATA_INT['filtro'])) 	$this->listing .= '&filtro='. $this->model->DATA_INT['filtro'] ;

						if(is_array($value)){
							foreach($value as $f => $v){
								if(is_array($v)){
				            	 foreach($v as $field_multiple => $value_multiple){
					            	$field	=	$field_multiple;
					            	break;
					             }
								}
					            break;
					        }
					        $this->listing .= '&ordem='. $field;
			            }
						else $this->listing .= '&ordem='. $field;

						if(isset($this->model->DATA_INT['modo'])) 	$this->listing .= '&modo='. $this->model->DATA_INT['contramodo'];
					}
						$this->listing .= '" >'.$label.'</th>';
				}
			//}
		}
		}
		else{
			$this->listing .= "\r\n\t<tr><td>Não existem dados para serem exibidos na página ". RouterBase::getLastLevel(2) ."</td></tr>";
		}
		if($noAction != true){
			$this->listing .= "\r\n\t\t".'<th class="tools"> Opções </th>';
		}
        $this->listing .= "\r\n\t</tr>";
        $this->listing .= "\r\n\t</thead>";

        $this->listing .= "\r\n\t<tbody>";

		$i=0;
        foreach($this->model->DATA_LIST as $key => $fields){
			$noActionClass = ($typeIntern=="arquivos_imagens") ? " class='noaction' ":" ";

			$classe = "";
			if($typeIntern=="imovel"){
	          	$d_now = Run::$control->date->fullConversion(Date::$TODAY['DATETIME']);
	          	$d = Run::$control->date->fullConversion($fields["date_insert"]['value']);
	          	//$d_now = Run::$control->date->fullConversion(Date::$TODAY['DATETIME']);
	          	//$d = Run::$control->date->fullConversion("15/09/2014 16:37:00 ");
	          	//$d_n = Run::$control->date->fullConversion($classe);			 
				$mktime = $d_now['MKTIME'] - $d['MKTIME'];
				if($mktime > 604590) $classe .= " cadastro_7dias ";
				else if($mktime > 259116) $classe .= " cadastro_3dias ";
				else if($mktime > 172744) $classe .= " cadastro_2dias ";
				else if($mktime > 86372) $classe .= " cadastro_1dia ";
				else if($mktime < 86362) $classe .= " cadastro_hoje ";
				$classe .= " status_".$fields["status_pro"]['value'];

	          	$d = Run::$control->date->fullConversion($fields["date_update"]['value']);				 
				$mktime = $d_now['MKTIME'] - $d['MKTIME'];
				if($mktime > 604590) $classe .= " atualizado_7dias ";
				else if($mktime > 259116) $classe .= " atualizado_3dias ";
				else if($mktime > 172744) $classe .= " atualizado_2dias ";
				else if($mktime > 86372) $classe .= " atualizado_1dia ";
				else if($mktime < 86362) $classe .= " atualizado_hoje ";
			}else{				
				$classe .= " status_".$fields["status"]['value'];
			}
			$title="";
			foreach($fields as $fClass => $vClass){
				if(is_array($vClass)){
	            	if(isset($vClass['treatView']) && $vClass['treatView'] == "onlyClass"){
						if( isset($vClass['value_label']) && $vClass['value_label'] != ""){
					        $classe .= " ".$fClass." ".$vClass['value_label'];
							$title	= $vClass['value_label'];
						}else{
							$classe .= " ".$fClass." ".$vClass['value'];
							$title	= $vClass['value'];
						}
	            	} 
				}
	        }
			$this->listing .= "\r\n\t<tr id='". $fields[$this->model->SETTINGS['PK']]['value'] ."' title='$title' class='$classe' $noActionClass>";
				$classe="";
				foreach($fields as $field => $value){
					if(isset($value['treatView']) && $value['treatView'] == "onlyClass") continue;
					if($typeIntern == "arquivos") $this->cellsArquivos($fields, $field, $value, $i);
					else if($typeIntern == "arquivos_imagens") $this->cellsArquivosImagens($fields, $field, $value, $i);
					else $this->listing .= $this->cellsDefault($fields, $field, $value, $i, $typeIntern, $title); //"<td></td>"//
				}
			if($noAction !== true){
				$this->listing .= "\r\n\t\t".'<td class="tools '.$classe.'" ref="'.$noAction.'" ><div><a href="#Visualizar" class="view" id="TOOLS_VIEW"> <span title="Visualizar" class="_t_view" >&nbsp;</span> </a> <a href="#Editar" class="edit" id="TOOLS_EDIT"> <span title="Editar" class="_t_edit" >&nbsp;</span> </a> <a href="#Deletar" class="delete" id="TOOLS_DEL"> <span title="Deletar" class="_t_delete" >&nbsp;</span> </a></div></td>';
			}
			$this->listing .= "</tr>";
			$i++;
        }
        $this->listing .= "\r\n\t</tbody>";
        $this->listing .= "\r\n</table>";
		echo $this->listing;
	}
// ********************************************************************************************************************************
	public function cellsDefault($fields, $field, $value, $i, $typeIntern, $title=""){
		$classe = ($field == $this->model->SETTINGS['PK']) ? "id" : "";
		$val = (isset($value['value'])) ? $value['value'] : "" ;
		if(isset($value['treatView']) && $value['treatView'] == "maskReal") $val = Run::$control->string->maskReal($val);

		$title = (isset($value['title'])) ? $value['title'] :  $title ;
		if(is_array($value)){
			$n=0;
			foreach($value as $f => $v){
				if(is_array($v)){
					foreach($v as $field_multiple => $value_multiple){
							$val .= $value_multiple['value'] ;
							if(count($value) > 1 && $n < count($value)-1) $val .=", " ;
							$title .= $val ;
							$n++;
			        }
		        }
	        }
		}
		if( isset($value['value_label']) && $value['value_label'] != ""){
	        $classe .= " ".$field."_".$val;
			$val = $value['value_label'];
	        $title = $val;
		}
		 return "\r\n\t\t".'<td class="'.$classe.'" title="'.$title.'">'.$val.'</td>';
	}
// ********************************************************************************************************************************
	public function cellsArquivos($fields, $field, $value, $i){
		$classe = ($field == $this->model->SETTINGS['PK']) ? "id" : "";
		if($field == "status"){
//			if($value['value'] == "1") $value['value'] = "Ativo";
//			else $value['value'] = "Inativo";
		}
		if($field == "nome"){
			if($this->model->DATA_LIST[$i]['extensao']['value'] == "jpg" || $this->model->DATA_LIST[$i]['extensao']['value'] == "gif" || $this->model->DATA_LIST[$i]['extensao']['value'] == "png") $value['value'] = "<img src=\"".Config::$PATH."../pags/view/files/kit_".$value['value']."\" />".$value['value'];
		}
		if(isset($value['value'])) $this->listing .= "\r\n\t\t".'<td class="'.$classe.'">'.$value['value'].'</td>';		
	}
// ********************************************************************************************************************************
	public function cellsArquivosImagens($fields, $field, $value, $i){
		$classe = ($field == $this->model->SETTINGS['PK']) ? "id" : "";
		if($field == "status"){
//			if($value['value'] == "1") $value['value'] = "Ativo";
//			else $value['value'] = "Inativo";
		}
		$val = (isset($value['value'])) ? $value['value'] : "" ;
		$title = (isset($value['title'])) ? $value['title'] : $field ;
		if(is_array($value[0])){
			foreach($value[0] as $field_multiple => $value_multiple){
					$val .= $value_multiple['value'].", " ;
					$title = $value_multiple['title'] ;
	        }
		}
		if($field == "nome"){
			if($this->model->DATA_LIST[$i]['extensao']['value'] == "jpg" || $this->model->DATA_LIST[$i]['extensao']['value'] == "gif" || $this->model->DATA_LIST[$i]['extensao']['value'] == "png") $value['value'] = "<img src=\"".Config::$PATH."../pags/view/files/kit_".$value['value']."\" />".$value['value'];
		}
		if($val) $this->listing .= "\r\n\t\t".'<td class="'.$classe.'"><a href="#" onclick=\'seleciona("pags/view/files/'.$this->model->DATA_LIST[$i]['nome']['value'].'");\'>'.$val.'</a></td>';		
	}
// ********************************************************************************************************************************
}
?>