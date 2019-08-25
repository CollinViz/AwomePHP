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
require_once 'FileSystem.cls.php';
//Write upload file
//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//print_r($_FILES);

$oFileSys = new FileSystem();

$oFileSys->uploadFile("document");

?>
