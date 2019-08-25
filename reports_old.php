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
        $oSeries->addXLable("Cooperative");
        $intNum = $this->Country_ID+0;
        $stmt = $concustomercontrol->query(sprintf("SELECT municipality,COUNT(*) as count 
                                              FROM entrepreneur_view 
                                              WHERE Country_ID=%s and Province='Limpopo'
                                              GROUP BY municipality ",$intNum));
        
         
         
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Entrepreneurs',$row[1]);
        } 
        $stmt = $concustomercontrol->query(sprintf("SELECT municipality,COUNT(*) as count 
                                              FROM enterprise_base_view 
                                              WHERE Country_ID=%s  and Province='Limpopo'
                                              GROUP BY municipality ",$intNum));
         
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Enterprises',$row[1]);
        } 

        $stmt = $concustomercontrol->query(sprintf("SELECT municipality,COUNT(*) as count 
                                              FROM cooperative_base_view 
                                              WHERE Country_ID=%s  and Province='Limpopo'
                                              GROUP BY municipality ",$intNum));
         
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Cooperative',$row[1]);
        } 

        echo $oSeries->exportJasonSeries(); 
    }
    public function getSexCount(){
        global $concustomercontrol;
        $intNum = $this->Country_ID+0;
        //Get Sex 
        $stmt = $concustomercontrol->query(sprintf("SELECT sex,COUNT(*) as count 
                                              FROM entrepreneur_view WHERE Country_ID=%s AND Province_ID=5
                                              GROUP BY sex",$intNum));
        
        $aUserCount = [];
        while ($row=$stmt->fetch_array()) {
            $aUserCount[sprintf("%s",$row[0])]=$row[1];
        } 
        echo json_encode($aUserCount);
    }
    public function getAgeByGroup(){
        global $concustomercontrol;
        $oSeries = new Series();

        $intNum = $this->Country_ID+0;
        $strProvince = $this->Province.''; 
        $stmt = $concustomercontrol->query(sprintf("SELECT Province,IFNULL(SUM( Age_20),0) as Age_20,
                                                    IFNULL(SUM( Age_29 ),0) as Age_29,
                                                    IFNULL(SUM( Age_39 ),0) as Age_39,
                                                    IFNULL(SUM( Age_49 ),0) as Age_49,
                                                    IFNULL(SUM( Age_59 ),0) as Age_59,
                                                    IFNULL(SUM( Age_69 ),0) as Age_69,
                                                    IFNULL(SUM( Age_69_Plus ),0) as Age_69_Plus
                                                    FROM enterprise_base_view WHERE Country_ID=%s %s
                                                    GROUP BY Province",$intNum,($strProvince==""?" AND Province_ID=5 ":sprintf(" AND Province_ID=%s",$strProvince))));
         
        
         while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], '< 20 Years',$row[1]);
            $oSeries->addData2Series($row[0], '20-29 Years',$row[2]);
            $oSeries->addData2Series($row[0], '30-39 Years',$row[3]);
            $oSeries->addData2Series($row[0], '40-49 Years',$row[4]);
            $oSeries->addData2Series($row[0], '50-59 Years',$row[5]);
            $oSeries->addData2Series($row[0], '60-69 Years',$row[6]);
            $oSeries->addData2Series($row[0], '> 69 Years',$row[7]);
        } 

        echo $oSeries->exportJasonSeries();
    }

    public function enterprise_count_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM enterprise_count_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Enterprise',$row[1]); 
        } 

        echo $oSeries->exportJasonSeries();
    }
    public function entrepreneur_count_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM entrepreneur_count_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Entrepreneur',$row[0]); 
        }
        $stmt->close();

        echo $oSeries->exportJasonSeries();
    }
    public function jobs_created_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM jobs_created_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Enterprise',$row[1]); 
        } 

        echo $oSeries->exportJasonSeries();
    }
    public function education_level_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM education_level_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], $row[1],$row[2]); 
        } 

        echo $oSeries->exportJasonSeries();
    }

    public function training(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM not_trained_view");
 

        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Not trained", "",$row[0]); 
        } 

        $stmt = $concustomercontrol->query("SELECT * FROM trained_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Trained", "",$row[0]); 
        } 

        echo $oSeries->exportJasonSeries();
        
    }

    public function popular_training_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $result = $concustomercontrol->query("SELECT * FROM popular_training_view");

         

        while ($row=$result->fetch_array()) {
            $oSeries->addData2Series($row[0], $row[0],$row[1]); 
        } 

        echo $oSeries->exportJasonSeries();
    }
    public function owners_employees_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $result = $concustomercontrol->query("SELECT * FROM owners_employees_view");
 
        while ($row=$result->fetch_array()) {
            $oSeries->addData2Series($row[0], "Owners",$row[1]); 
            $oSeries->addData2Series($row[0], "Full time",$row[2]); 
            $oSeries->addData2Series($row[0], "Part time",$row[3]); 
        } 
        echo $oSeries->exportJasonSeries();
    }
    public function female_male_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM female_male_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Female Owners',$row[1]); 
            $oSeries->addData2Series($row[0], 'Male Owners',$row[2]); 
        } 

        echo $oSeries->exportJasonSeries();
    }
    public function income_expense_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM income_expense_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Average Income',$row[1]); 
            $oSeries->addData2Series($row[0], 'Average Expenses',$row[2]); 
            $oSeries->addData2Series($row[0], 'Average Profit',$row[3]); 
        } 

        echo $oSeries->exportJasonSeries();
    }
    public function premise_ownership_view(){
        global $concustomercontrol;
        $oSeries = new Series();

        $stmt = $concustomercontrol->query("SELECT * FROM premise_ownership_view");
 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], $row[0],$row[1]);  
        } 

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