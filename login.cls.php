<?php
$BASE_PATH = dirname(__FILE__);
$DEPENDS_PATH  = ".;".$BASE_PATH;
$DEPENDS_PATH .= ";".$BASE_PATH."/Include";
ini_set("include_path", ini_get("include_path").";".$DEPENDS_PATH);

require_once 'html.php'; 
require_once 'config.php'; 
require_once 'include/mysql2javaS.php';
require_once 'include/sqlcode.php'; 
require_once 'Database/edf.db.cls.php';
class LoginGUI extends html {
	public $OUT_PUT_TYPE="ANGULARJS5";
	protected $CLASSNAME="LoginGUI";
	protected $AJAX_SCRIP="ajax.php";
    protected $B_DEBUG=false; 
     
    public function checkLogin(){
        $o = array("OK"=>"NOK","message"=>"Username and password not correct");
        if($this->UserName !="" && $this->Password !=""){
            $oEDF = new edf();
            $rsInfo = $oEDF->checkLogin($this->UserName,$this->Password);
            if($row = $rsInfo->fetch_assoc()){
                if($row["Active"]=="1" || $row["Active"]=="Y"){
                    echo mysql2javaS::Array2JavaSArray( $row );
                    return;
                } 
            } 
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