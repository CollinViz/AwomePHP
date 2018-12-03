<?php
header('Access-Control-Allow-Origin: *'); 
#TYPE_OS = WIN LINUX
$TYPE_OS=(PATH_SEPARATOR==':'?'LINUX':'WIN');
$PATH_DM =PATH_SEPARATOR;$PATH_FD="\\\\";if($TYPE_OS=='WIN'){	$PATH_DM =PATH_SEPARATOR;	$PATH_FD="\\\\";}else{	$PATH_DM =PATH_SEPARATOR;	$PATH_FD="//";}

$BASE_PATH = dirname(__FILE__);
$DEPENDS_PATH  = ".".$PATH_DM."".$BASE_PATH;
$DEPENDS_PATH .= "".$PATH_DM."".$BASE_PATH."/include";
ini_set("include_path", ini_get("include_path")."".$PATH_DM."".$DEPENDS_PATH);


require_once 'config.php';
require_once 'login.cls.php';
require_once 'Finance.cls.php';
require_once 'reports.php';



$data = file_get_contents("php://input");

$objData = json_decode($data);
//print_r($objData); 
$arAJXClass = array();
$arAJXClass["LoginGUI"] = "login.cls.php"; 
$arAJXClass["FinanceGUI"] = "Finance.cls.php"; 
$arAJXClass["ReportsGUI"] = "reports.php"; 
$arAJXClass["DBDeleteGUI"] = "dbdelete.cls.php"; 

if(isset($objData->__class)){
	if( isset($arAJXClass[$objData->__class])){
		require_once($arAJXClass[$objData->__class]);
		eval('$ajexObj =  new '.$objData->__class."();");
		$ajexObj->m_json=$objData;
		$ajexObj->OUT_PUT_TYPE="ANGULARJS5";
		$ajexObj->lazyEvent();  
	}else{
		echo "Class not found ".$objData->__class;
	}
}else{
	echo "Class not found ".$objData->__class;
} 
?>