<?php
$BASE_PATH = dirname(__FILE__);
$DEPENDS_PATH  = ".".$PATH_DM.$BASE_PATH;
$DEPENDS_PATH .= $PATH_DM.$BASE_PATH."/Include";
ini_set("include_path", ini_get("include_path").$PATH_DM.$DEPENDS_PATH);

require_once 'html.php'; 
require_once 'config.php'; 
require_once 'include/mysql2javaS.php';
require_once 'include/sqlcode.php';  
require_once 'series.cls.php';


class ReportsGUI extends html {
	public $OUT_PUT_TYPE="ANGULARJS5";
	protected $CLASSNAME="ReportsGUI";
	protected $AJAX_SCRIP="ajax.php";
    protected $B_DEBUG=false; 


    public function listCountOFentrepreneurAndenterprise(){
        global $concustomercontrol;
        $oSeries = new Series();

        $oSeries->addXLable("Entrepreneurs");
        $oSeries->addXLable("Enterprises");

        $stmt = $concustomercontrol->prepare("SELECT Province,COUNT(*) as count 
                                              FROM entrepreneur_view WHERE Country_ID=?
                                              GROUP BY Province ");
        $intNum = $this->Country_ID+0;
        $stmt->bind_param("i",$intNum);
        $stmt->execute();
        $stmt->bind_result($Province, $count);
        while ($stmt->fetch()) {
            $oSeries->addData2Series($Province, 'Entrepreneurs',$count);
        }
        $stmt->close();

        $stmt = $concustomercontrol->prepare("SELECT Province,COUNT(*) as count 
                                              FROM enterprise_base_view WHERE Country_ID=?
                                              GROUP BY Province ");
        $stmt->bind_param("i",$intNum);
        $stmt->execute();
        $stmt->bind_result($Province, $count);
        while ($stmt->fetch()) {
            $oSeries->addData2Series($Province, 'Enterprises',$count);
        }
        $stmt->close();

        echo $oSeries->exportJasonSeries(); 
    }
    public function getSexCount(){
        global $concustomercontrol;
        $intNum = $this->Country_ID+0;
        //Get Sex 
        $stmt = $concustomercontrol->prepare("SELECT sex,COUNT(*) as count 
                                              FROM entrepreneur_view WHERE Country_ID=?
                                              GROUP BY sex");
        $stmt->bind_param("i",$intNum);
        $stmt->execute();
        $stmt->bind_result($sex, $count);
        $aUserCount = [];
        while ($stmt->fetch()) {
            $aUserCount["{$sex}"]=$count;
        }
        $stmt->close();
        echo json_encode($aUserCount);
    }
    public function getAgeByGroup(){
        global $concustomercontrol;
        $oSeries = new Series();

        $intNum = $this->Country_ID+0;
        $strProvince = $this->Province.''; 
        $stmt = $concustomercontrol->prepare(sprintf("SELECT Province,IFNULL(SUM( Age_20),0) as Age_20,
                                                    IFNULL(SUM( Age_29 ),0) as Age_29,
                                                    IFNULL(SUM( Age_39 ),0) as Age_39,
                                                    IFNULL(SUM( Age_49 ),0) as Age_49,
                                                    IFNULL(SUM( Age_59 ),0) as Age_59,
                                                    IFNULL(SUM( Age_69 ),0) as Age_69,
                                                    IFNULL(SUM( Age_69_Plus ),0) as Age_69_Plus
                                                    FROM enterprise_base_view WHERE Country_ID=? %s
                                                    GROUP BY Province",($strProvince==""?"":" AND Province_ID=?")));
        if($this->Province==""){
            $stmt->bind_param("i",$intNum);
        }else{
            $stmt->bind_param("ii",$intNum,$strProvince);
        }
        
        $stmt->execute();
        $stmt->bind_result($Province, $Age_20,$Age_29,$Age_39,$Age_49,$Age_59,$Age_69,$Age_69_Plus);
        while ($stmt->fetch()) {
            $oSeries->addData2Series($Province, '< 20 Years',$Age_20);
            $oSeries->addData2Series($Province, '20-29 Years',$Age_29);
            $oSeries->addData2Series($Province, '30-39 Years',$Age_39);
            $oSeries->addData2Series($Province, '40-49 Years',$Age_49);
            $oSeries->addData2Series($Province, '50-59 Years',$Age_59);
            $oSeries->addData2Series($Province, '60-69 Years',$Age_69);
            $oSeries->addData2Series($Province, '> 69 Years',$Age_69_Plus);
        }
        $stmt->close();

        echo $oSeries->exportJasonSeries();
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