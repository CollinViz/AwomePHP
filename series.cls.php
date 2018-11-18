<?php
class Series{
	private $DataSeries = array();
	private $Name2Indo = array();
	private $NameXrow = array();
	function addXLable($Name){
		if(!in_array($Name,$this->NameXrow) )
			$this->NameXrow[] = $Name;
	}
	function addNewSeries($Name){
		$this->DataSeries[]= array("name"=>$Name,"data"=>array());
		$this->Name2Indo[$Name] = count($this->DataSeries)-1;
	}
	function addData2Series($Name,$XLable,$Data){
		if(!isset($this->Name2Indo[$Name])){
			$this->addNewSeries($Name);
		}
		$this->addXLable($XLable);
		$index = array_search($XLable,$this->NameXrow);
		if($index>count($this->DataSeries[$this->Name2Indo[$Name]]["data"])){
			for($x=count($this->DataSeries[$this->Name2Indo[$Name]]["data"]);$x<$index;$x++){
				$this->DataSeries[$this->Name2Indo[$Name]]["data"][$x] =0;
			}
		}
		$this->DataSeries[$this->Name2Indo[$Name]]["data"][$index] =floatval($Data);
	}
	function exportJasonDataSeries(){
		return json_encode($this->DataSeries);
	}
	function exportJasonXNames(){
		return json_encode($this->NameXrow);
	}
	function exportJasonSeries(){
		return json_encode(["NameXrow"=>$this->NameXrow,"DataSeries"=>$this->DataSeries]);
	} 
}


?>
