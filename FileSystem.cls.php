<?php
// $BASE_PATH = dirname(__FILE__);
// $DEPENDS_PATH  = ".".$PATH_DM.$BASE_PATH;
// $DEPENDS_PATH .= $PATH_DM.$BASE_PATH."/Include";
// ini_set("include_path", ini_get("include_path").$PATH_DM.$DEPENDS_PATH);

require_once 'html.php';
require_once 'config.php';
require_once 'include/mysql2javaS.php';
require_once 'include/sqlcode.php';
require_once 'Database/finance.db.cls.php';

class FileSystem extends html
{
    public $OUT_PUT_TYPE="ANGULARJS5";
    protected $CLASSNAME="FileSystem";
    protected $AJAX_SCRIP="ajax.php";
    protected $B_DEBUG=false;

    public function listFile(){
        global $FILE_SYSTEM_ROOT;
        if($this->ID!=""){
          $strFolder = $FILE_SYSTEM_ROOT.DIRECTORY_SEPARATOR.$this->ID;
          if(is_dir( $strFolder )){
            $allFiles = array_diff(scandir($strFolder . "/"), [".", ".."]);

            $out = array_values($allFiles);
            echo mysql2javaS::Array2JavaSArray($out);
          }
        }
    }
    public function deleteFile(){
      global $FILE_SYSTEM_ROOT;

      if($this->ID!=""){
        $target_file = $FILE_SYSTEM_ROOT.DIRECTORY_SEPARATOR.$this->ID.DIRECTORY_SEPARATOR.$this->FileName;
        if(is_dir( $FILE_SYSTEM_ROOT.DIRECTORY_SEPARATOR.$this->ID )){
          if(is_file($target_file)){
            unlink($target_file);
          }
        }
      }
      $this->listFile();
    }
    public function uploadFile($FileIndexName){
      global $FILE_SYSTEM_ROOT;
      $o = array("OK"=>"OK","message"=>" ");

      if($this->ID!="" && isset($_FILES[$FileIndexName])){
        $file_tmp = $_FILES[$FileIndexName]['tmp_name'];
        $target_file = $FILE_SYSTEM_ROOT.DIRECTORY_SEPARATOR.$this->ID.DIRECTORY_SEPARATOR.$_FILES[$FileIndexName]["name"];
        if(!is_dir( $FILE_SYSTEM_ROOT.DIRECTORY_SEPARATOR.$this->ID )){
          if(!mkdir($FILE_SYSTEM_ROOT.DIRECTORY_SEPARATOR.$this->ID)){
            $o["OK"]="NOK";
            $o["message"]="Cannot Create Folder";
            return;
          }
        }
        if(!move_uploaded_file($file_tmp,$target_file)){
          $o["OK"]="NOK";
          $o["message"]="Cannot Create File ".$file_tmp." -> ".$target_file;
        }else{
          $o["message"]=$target_file;
        }
      }else{
        $o["OK"]="NOK";
        $o["message"]="ID Not Found";
      }
      echo mysql2javaS::Array2JavaSArray($o);
    }
    public function __construct()
    {
        //run the construct on the Main Class
        html::__construct();

        //  NB NB NB
        //If not post back don't use cach
        //  NB NB NB
        if (!$this->isPOST()) {
        }
        $this->lazyEvent();

        $this->savehtml();
    }
}
