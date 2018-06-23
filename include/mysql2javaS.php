<?php

class mysql2javaS{
	public static function Array2JavaSArray($ArrayIn){
		return "\n".json_encode($ArrayIn)."";
	}
	public static function mysqlRow2JavaSArray($rsIn){
		$strOutPutBuffe ="\n[";
		if($rsIn){			
			$intRowNumber = 0;
			while($row = $rsIn->fetch_assoc()){
				$strOutPutBuffe.= "{";
				foreach ($row as $FileName => $value) {
					$strOutPutBuffe.= sprintf("\"%s\":%s,",$FileName,mysql2javaS::cleanValue($value));
				}
				$strOutPutBuffe.= sprintf("\"__row\":%s},\n",$intRowNumber);
				$intRowNumber++;
			}		
			if($intRowNumber>0){
				$strOutPutBuffe = substr($strOutPutBuffe, 0,strlen($strOutPutBuffe)-2)."]\n";
			}else{
				$strOutPutBuffe.="{}]\n";
			} 			
		}else{
			//$strOutPutBuffe = sprintf("\n%s=[\n",$varOut);
			$strOutPutBuffe.="{}]\n"; 
		}
		return $strOutPutBuffe;
	}  
	public static function mssqlRow2JavaSArray($rsIn,$varOut){
		$strOutPutBuffe = sprintf("\n%s=[\n",$varOut);
		$intRowNumber = 0;
		while($row = mssql_fetch_assoc($rsIn)){
			$strOutPutBuffe.= "\n{";
			foreach ($row as $FileName => $value) {
				$strOutPutBuffe.= sprintf("%s:'%s',",$FileName,mysql2javaS::cleanValue($value));
			}
			$strOutPutBuffe.= sprintf("__row:%s},\n",$intRowNumber);
			$intRowNumber++;
		}
		if($intRowNumber>0){
			$strOutPutBuffe = substr($strOutPutBuffe, 0,strlen($strOutPutBuffe)-2)."];\n";
		}else{
			$strOutPutBuffe.="{}];\n";
		} 
		return $strOutPutBuffe;
	}
	public static function odbcRow2JavaSArray($rsIn,$varOut){
		$strOutPutBuffe = sprintf("\n%s=[\n",$varOut);
		$intRowNumber = 0;
		while($row = odbc_fetch_array($rsIn)){
			
			$strOutPutBuffe.= "\n{";
			foreach ($row as $FileName => $value) {
				$strOutPutBuffe.= sprintf("%s:'%s',",$FileName,mysql2javaS::cleanValue($value));
			}
			$strOutPutBuffe.= sprintf("__row:%s},\n",$intRowNumber);
			$intRowNumber++;
		}
		if($intRowNumber>0){
			$strOutPutBuffe = substr($strOutPutBuffe, 0,strlen($strOutPutBuffe)-2)."];\n";
		}else{
			$strOutPutBuffe.="{}];\n";
		} 
		return $strOutPutBuffe;
	}
	private static function cleanValue($valueIn){
		//$afind=array("\n","\r","\t");
		//$aWith=array("\\n","\\r","\\t");
		
		return json_encode($valueIn); //str_replace($afind,$aWith,$valueIn);
	}

}

?>