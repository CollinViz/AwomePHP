<?php
//$BASE_PATH = dirname(__FILE__);
//$DEPENDS_PATH  = ".".$BASE_PATH;
//$DEPENDS_PATH .= ";".$BASE_PATH."/Include";
//ini_set("include_path", ini_get("include_path").";".$DEPENDS_PATH);

require_once 'html.php';
require_once 'config.php';
require_once 'include/mysql2javaS.php';
require_once 'include/sqlcode.php';
require_once 'Database/edf.db.cls.php';
require_once 'Database/audit.db.cls.php';
class DBDeleteGUI extends html {
	public $OUT_PUT_TYPE="ANGULARJS5";
	protected $CLASSNAME="DBDeleteGUI";
	protected $AJAX_SCRIP="ajax.php";
    protected $B_DEBUG=false;

    public function deleteCooperative(){
        $o = array("OK"=>"OK","message"=>$this->Cooperative_ID);
				$oAudit = new Audit();
				$oAudit->del_record_sp("Cooperative",$this->Cooperative_ID,$this->EDF_ID);

        echo mysql2javaS::Array2JavaSArray($o);
    }

    public function deleteEnterprise(){
        $o = array("OK"=>"OK","message"=>$this->Enterprise_ID);

				$oAudit = new Audit();
				$oAudit->del_record_sp("Enterprise",$this->Enterprise_ID,$this->EDF_ID);

        echo mysql2javaS::Array2JavaSArray($o);
    }
    public function deleteEntrepreneur(){
        $o = array("OK"=>"OK","message"=>$this->Entrepreneur_ID);

				$oAudit = new Audit();
				$oAudit->del_record_sp("Entrepreneur",$this->Entrepreneur_ID,$this->EDF_ID);


        echo mysql2javaS::Array2JavaSArray($o);
    }
    public function deleteAssociation(){
        $o = array("OK"=>"OK","message"=>$this->Association_ID);

				$oAudit = new Audit();
				$oAudit->del_record_sp("Association",$this->Association_ID,$this->EDF_ID);

        echo mysql2javaS::Array2JavaSArray($o);
    }
		public function deleteEnterprise_Visits(){
        $o = array("OK"=>"OK","message"=>$this->enterprise_visit_id);

				$oAudit = new Audit();
				$oAudit->del_record_sp("Enterprise_Visits",$this->enterprise_visit_id,$this->EDF_ID);

        echo mysql2javaS::Array2JavaSArray($o);
    }
		public function deleteCooperative_Visits(){
        $o = array("OK"=>"OK","message"=>$this->cooperative_visit_id);

				$oAudit = new Audit();
				$oAudit->del_record_sp("Cooperative_Visits",$this->cooperative_visit_id,$this->EDF_ID);

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
