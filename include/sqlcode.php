<?php

class sqlcode {
	//Place to keep all the vars for the SQL statments
	var $arSQLData=array();
	var $strTableName;

	function __construct($TableName=''){
		$this->setTableName($TableName);
	}
	function add($fieldName,$HTTP_Var,$fieldType,$default=''){


		$fieldType = strtoupper($fieldType);
		$strvalue = $this->getValue($HTTP_Var,$fieldType,$default);

		$this->arSQLData[]= array("fieldname"=>$fieldName,"value"=>$strvalue,"fieldtype"=>$fieldType);
		//this->$arSQLData[1]['value']=$strvalue;
		//this->$arSQLData[1]['fieldtype']=fieldtype;
	}
	function addValue($fieldName,$Value,$fieldType){


		$fieldType = strtoupper($fieldType);
		$strvalue = $this->getValue("NOTSET",$fieldType,$Value);

		$this->arSQLData[]= array("fieldname"=>$fieldName,"value"=>$strvalue,"fieldtype"=>$fieldType);
		//this->$arSQLData[1]['value']=$strvalue;
		//this->$arSQLData[1]['fieldtype']=fieldtype;
	}

	function getValue($http_var,$fieldtype,$default){
		global $concustomercontrol;
		$value = "";
		//find the value

		if(strtoupper($http_var)=="NOTSET"){
			$value =   $default;
		}else{
			if(isset($$http_var)){
				$value= $$http_var;
			}else{//maby the php.ini file dosn't have global vars set
				if(!getenv("register_globals") || strtoupper(getenv("register_globals"))=="OFF"){
						
					if(array_key_exists($http_var,$_GET)){
						$value =  $_GET[$http_var];
					}elseif(array_key_exists($http_var,$_POST)){
						$value=  $_POST[$http_var];
					}else{
						$value=  $default;
					}
				}else{
					$value=  $default;
				}
			}
		}



		//formate it correctly
		switch($fieldtype){
			case "STR":
				//$value = ereg_replace("\\"," ",$value);
				//$value = ereg_replace("'","`",$value);
				$value=$concustomercontrol->escape_string($value);
				return "'$value'";
				break;
			case "NUM":
				return "$value";
				break;
			default:
				//$value = ereg_replace("'","`",$value);
				$value=$concustomercontrol->escape_string($value);
				return "'$value'";
				break;
		}
	}

	function setTableName($TableName){
		$this->strTableName=$TableName;
	}

	function sqlInsert($TableName = '',$ON_DUPLICATE_KEY_UPDATE=''){

		if ($TableName==''){
			$TableName = $this->strTableName;
		}

		$strFieldname = "";
		$strValues = "";

		for($i=0;$i<count($this->arSQLData);$i++){
			$strFieldname .= $this->arSQLData[$i]['fieldname'].",";
			$strValues .= $this->arSQLData[$i]['value'].",";
		}
		if($ON_DUPLICATE_KEY_UPDATE!=""){
			$ON_DUPLICATE_KEY_UPDATE =" ON DUPLICATE KEY UPDATE ".$ON_DUPLICATE_KEY_UPDATE;
		}
		return "INSERT INTO $TableName (".substr($strFieldname,0,strlen($strFieldname)-1).")
				VALUES (".substr($strValues,0,strlen($strValues)-1).") ".$ON_DUPLICATE_KEY_UPDATE.";";
	}

	function sqlupdate($WhereStatment ,$TableName = ''){

		if ($TableName==''){
			$TableName = $this->strTableName;
		}
		$strFieldname = "";
		$strValues = "";

		for($i=0;$i<count($this->arSQLData);$i++){
			$strFieldname .= $this->arSQLData[$i]['fieldname']."=".$this->arSQLData[$i]['value'].",";
		}
		return "UPDATE $TableName SET ".substr($strFieldname,0,strlen($strFieldname)-1)."\n WHERE $WhereStatment;";

	}
	function sqldelete($WhereStatment ,$TableName = ''){

		if ($TableName==''){
			$TableName = $this->strTableName;
		}

		return "DELETE FROM $TableName \nWHERE $WhereStatment;";

	}
}

?>