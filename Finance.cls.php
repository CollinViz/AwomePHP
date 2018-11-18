<?php
$BASE_PATH = dirname(__FILE__);
$DEPENDS_PATH  = ".".$PATH_DM.$BASE_PATH;
$DEPENDS_PATH .= $PATH_DM.$BASE_PATH."/Include";
ini_set("include_path", ini_get("include_path").$PATH_DM.$DEPENDS_PATH);

require_once 'html.php'; 
require_once 'config.php'; 
require_once 'include/mysql2javaS.php';
require_once 'include/sqlcode.php'; 
require_once 'Database/finance.db.cls.php';
class FinanceGUI extends html {
	public $OUT_PUT_TYPE="ANGULARJS5";
	protected $CLASSNAME="FinanceGUI";
	protected $AJAX_SCRIP="ajax.php";
    protected $B_DEBUG=false; 
     
    public function deleteFinance(){
        $o = array("OK"=>"OK","message"=>" ");
        if($this->Enterprise_ID !=""){
            $ofin = new finance();
            $ofin->delete($this->Enterprise_ID,$this->Enterprise_Visit_ID);
        } 
        echo mysql2javaS::Array2JavaSArray($o);
          
	}
	public function deleteFinanceCooperative(){
        $o = array("OK"=>"OK","message"=>"OK ");
        if($this->Cooperative_ID !=""){
            $ofin = new finance();
            $ofin->deleteCooperative($this->Cooperative_ID,$this->Cooperative_Visit_ID);
        } 
        echo mysql2javaS::Array2JavaSArray($o);
          
    }

    public function __construct() {
		//run the construct on the Main Class
		html::__construct();
	
		//  NB NB NB
		//If not post back don't use cach
		//  NB NB NB
		if(!$this->isPOST()){
	
		}
		$this->lazyEvent();
	
		$this->savehtml();
	}
}




?>