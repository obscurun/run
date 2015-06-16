<?php
// ****************************************************************************************************************************
class File{
	//*************************************************************************************************************************
	function File(){

	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function saveFile($fileTmp, $fileName, $path="", $prefix=""){
		$pathFile = "";
		if(substr($path, -1) != "/") $path .= "/";
		$pathFile = FILES_PATH.$path;

		if (!file_exists($pathFile)) {
			//Debug::p($fileTmp, $pathFile);
			mkdir($pathFile, 0777, true);
		}
		$pathFile .= $prefix.$fileName;
		//Debug::p($fileTmp, $pathFile);
		$save = rename($fileTmp, $pathFile);
		if($save) return true;
		else return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function moveUploadedFile($fileOri, $fileName, $path="", $prefix=""){
		$pathFile = "";
		if(substr($path, -1) != "/") $path .= "/";
		$pathFile = FILES_PATH.$path;

		if(!file_exists($pathFile)) {
			mkdir($pathFile, 0777, true);
		}
		$pathFile .= $prefix.$fileName;
		//Debug::print_r($fileTmp);
		//Debug::print_r($pathFile);
		$save = move_uploaded_file($fileOri, $pathFile);
		if($save) return true;
		else return false;
	}
	//-------------------------------------------------------------------------------------------------------------------------
	public function deleteFile($fileOri){
		return unlink($fileOri);
	}
	//-------------------------------------------------------------------------------------------------------------------------
}
// ****************************************************************************************************************************
?>