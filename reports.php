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

        $strWhere = $this->getEnterpriseFilter();

        //$stmt = $concustomercontrol->query("SELECT * FROM real_jobs_created_view");
        
        $strSQL = sprintf("SELECT  Municipality, SUM((Female_FT_3500_Plus + Female_PT_160_Plus)) AS `Female_Jobs`,
                            SUM((Male_FT_3500_Plus + Male_PT_160_Plus)) AS `Male_Jobs`,
                            SUM((((((((((((Female_FT_2500 + Female_FT_3000) + Female_FT_3500) + Female_FT_3500_Plus) + Female_PT_160) + Female_PT_160_Plus) + Male_FT_2500) + Male_FT_3000) + Male_FT_3500) + Male_FT_3500_Plus) + Male_PT_160) + Male_PT_160_Plus)) AS `Total_Employed`,
                            SUM((((Female_FT_3500_Plus + Female_PT_160_Plus) + Male_FT_3500_Plus) + Male_PT_160_Plus)) AS `Total_Jobs`,
                            COUNT(0) AS `Total Enterprises`
                            FROM enterprise_base_view where  %s
                            GROUP BY municipality",$strWhere);                                   
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n"); 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Female  (>R3,500/m and R160/d)",$row[0], $row[1]);
            $oSeries->addData2Series("Male       (>R3,500/m and R160/d)",$row[0], $row[2]); 
            $oSeries->addData2Series("Total Jobs  (>R3,500/m and R160/d)",$row[0], $row[4]);
            $oSeries->addData2Series("Total Employed (All wage scales)", $row[0], $row[3]); 
            $oSeries->addData2Series("Total Enterprises",$row[0], $row[5]);
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function getAgeByGroup(){
        global $concustomercontrol;
        $oSeries = new Series();
         
        $strWhere = $this->getEnterpriseFilter();
       
        $strSQL = sprintf("SELECT Municipality,IFNULL(SUM( Age_20),0) as Age_20,
                            IFNULL(SUM( Age_29 ),0) as Age_29,
                            IFNULL(SUM( Age_39 ),0) as Age_39,
                            IFNULL(SUM( Age_49 ),0) as Age_49,
                            IFNULL(SUM( Age_59 ),0) as Age_59,
                            IFNULL(SUM( Age_69 ),0) as Age_69,
                            IFNULL(SUM( Age_69_Plus ),0) as Age_69_Plus
                            FROM enterprise_base_view where  %s
                            GROUP BY municipality",$strWhere);                                   
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
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
    public function female_male_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEnterpriseFilter();
                
        //$stmt = $concustomercontrol->query(sprintf("SELECT * FROM female_male_view WHERE Country_ID=%s %s",$intNum,($strProvince==""?" AND Province_ID like '%' ":sprintf(" AND Province_ID=%s",$strProvince)))); 
                                         
        $strSQL = sprintf("SELECT municipality, 
                        SUM(`enterprise_base_view`.`Female_Owners`) AS `Female_Owners`,
                        SUM(`enterprise_base_view`.`Male_Owners`) AS `Male_Owners`,
                        `enterprise_base_view`.`Country_ID` AS `Country_ID`,
                        `enterprise_base_view`.`Province_ID` AS `Province_ID`,
                        `enterprise_base_view`.`Municipality_ID` AS `Municipality_ID`
                        FROM  `enterprise_base_view` where  %s
                        GROUP BY municipality",$strWhere); 
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series('Female Owners',$row[0], $row[1]); 
            $oSeries->addData2Series('Male Owners',$row[0], $row[2]); 
        } 

        echo $oSeries->exportJasonSeries();
    }
    public function income_expense_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEnterpriseFilter();
        //$stmt = $concustomercontrol->query("SELECT * FROM income_expense_view");
      
        $strSQL = sprintf("SELECT   `enterprise_base_view`.`Municipality` AS `municipality`,
                        AVG((`enterprise_base_view`.`Avg_Sales` + `enterprise_base_view`.`Avg_Other_Income`)) AS `Average_Income`,
                        AVG(`enterprise_base_view`.`Avg_Expenditure`) AS `Average_Expenses`,
                        AVG(`enterprise_base_view`.`Avg_Profit`) AS `Average_Profit`
                        FROM  `enterprise_base_view` where  %s
                        GROUP BY municipality",$strWhere); 
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series('Average Income',$row[0], $row[1]); 
            $oSeries->addData2Series('Average Expenses',$row[0], $row[2]); 
            $oSeries->addData2Series('Average Profit',$row[0], $row[3]); 
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function registration_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEnterpriseFilter();
        
        /*$stmt = $concustomercontrol->query("select municipality, sum(Registered_Y_N), count(*) 
                                            from enterprise_base_view   where province_id = 5
                                            group by municipality");*/
 
        $strSQL = sprintf("select municipality, sum(Registered_Y_N), count(*) 
                          from enterprise_base_view where  %s
                        GROUP BY municipality",$strWhere); 
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Registered",$row[0], $row[1]);
            $oSeries->addData2Series("Total Enterprises",$row[0], $row[2]); 
        } 

        
        echo $oSeries->exportJasonSeries();
        
    }
    
    public function premise_ownership_view(){
        global $concustomercontrol;
        $oSeries = new Series();
         
        $strWhere = $this->getEnterpriseFilter();
        
        //$stmt = $concustomercontrol->query("SELECT * FROM premise_ownership_view");
        
        $strSQL = sprintf("SELECT municipality,
                            sum(IF(Premise_Own = 'Owned', 1, 0) ) AS `Owned`,
                            sum(IF(Premise_Own = 'Rented', 1, 0) ) AS `Rented`,
                            sum(IF(Premise_Own = 'Co-tenant', 1, 0) ) AS `Co-tenant`,
                            sum(IF(Premise_Own = 'Home Based', 1, 0) ) AS `Home_Based`,
                            sum(IF(Premise_Own = 'Government Premises', 1, 0) ) AS `Government_Premises`,
                            sum(IF(Premise_Own = 'Other', 1, 0)) AS `Other` , count(*)
                            FROM enterprise_base_view where  %s
                            GROUP BY municipality",$strWhere); 
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series('Owned',$row[0], $row[1]); 
            $oSeries->addData2Series('Rented',$row[0], $row[2]); 
            $oSeries->addData2Series('Co-Tenant',$row[0], $row[3]);
            $oSeries->addData2Series('Home Based',$row[0], $row[4]);
            $oSeries->addData2Series('Government Premises',$row[0], $row[5]);
            $oSeries->addData2Series('Other',$row[0], $row[6]); 
            $oSeries->addData2Series('Total Enterprises',$row[0], $row[7]);  
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function owners_employees_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEnterpriseFilter();
        
        //$result = $concustomercontrol->query("SELECT * FROM owners_employees_view");
 
        $strSQL = sprintf("SELECT `enterprise_base_view`.`Municipality` AS `municipality`,
                        SUM((`enterprise_base_view`.`Female_Owners` + `enterprise_base_view`.`Male_Owners`)) AS `Owners`,
                        SUM((((((((`enterprise_base_view`.`Female_FT_2500` + `enterprise_base_view`.`Female_FT_3000`) + `enterprise_base_view`.`Female_FT_3500`) + `enterprise_base_view`.`Female_FT_3500_Plus`) + `enterprise_base_view`.`Male_FT_2500`) + `enterprise_base_view`.`Male_FT_3000`) + `enterprise_base_view`.`Male_FT_3500`) + `enterprise_base_view`.`Male_FT_3500_Plus`)) AS `Full time`,
                        SUM((((`enterprise_base_view`.`Female_PT_160` + `enterprise_base_view`.`Female_PT_160_Plus`) + `enterprise_base_view`.`Male_PT_160`) + `enterprise_base_view`.`Male_PT_160_Plus`)) AS `Part time`
                        FROM `enterprise_base_view` where  %s
                        GROUP BY municipality",$strWhere); 
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            
            $oSeries->addData2Series("Owners",$row[0], $row[1]); 
            $oSeries->addData2Series("Full time", $row[0],$row[2]); 
            $oSeries->addData2Series("Part time", $row[0], $row[3]); 
        } 
        echo $oSeries->exportJasonSeries();
    }
    
    public function startup_funds_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEnterpriseFilter();
        
        /*$stmt = $concustomercontrol->query("select municipality, sum(funds_savings), sum(Funds_Group), sum(Funds_Family), sum(Funds_Grant), sum(Funds_External), 
                                            sum(Funds_Friends), sum(Funds_Other), count(*)
                                            from enterprise_base_view  where province_id = 5
                                            group by municipality");*/
        
        $strSQL = sprintf("select municipality, sum(funds_savings), sum(Funds_Group), sum(Funds_Family), sum(Funds_Grant), sum(Funds_External), 
                                            sum(Funds_Friends), sum(Funds_Other), count(*)
                                            from enterprise_base_view where  %s
                                            GROUP BY municipality",$strWhere); 
        $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Savings",$row[0], $row[1]);
            $oSeries->addData2Series("Group",$row[0], $row[2]); 
            $oSeries->addData2Series("Family", $row[0], $row[3]); 
            $oSeries->addData2Series("Grant",$row[0], $row[4]);
            $oSeries->addData2Series("External",$row[0], $row[5]);
            $oSeries->addData2Series("Friends",$row[0], $row[6]);
            $oSeries->addData2Series("Other Sources",$row[0], $row[7]);
            $oSeries->addData2Series("Total Enterprises",$row[0], $row[8]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function assets_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEnterpriseFilter();
        
        /*$stmt = $concustomercontrol->query("select municipality, sum(Assets_Land), sum(Assets_Buildings), sum(Assets_Water), sum(Assets_Machines), sum(Assets_Car), sum(Assets_Truck),  
                                            sum(Assets_Van), sum(Assets_Bicycle), sum(Assets_Motorbike), sum(Assets_Trailer),sum(Assets_Other), count(*)
                                                from enterprise_base_view   where province_id = 5
                                                group by municipality ");*/
        
        $strSQL = sprintf("select municipality, sum(Assets_Land), sum(Assets_Buildings), sum(Assets_Water), sum(Assets_Machines), sum(Assets_Car), sum(Assets_Truck),  
                                            sum(Assets_Van), sum(Assets_Bicycle), sum(Assets_Motorbike), sum(Assets_Trailer),sum(Assets_Other), count(*)
                                                from enterprise_base_view where  %s
                                            GROUP BY municipality",$strWhere);                                   
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n"); 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Land",$row[0], $row[1]);
            $oSeries->addData2Series("Buildings",$row[0], $row[2]); 
            $oSeries->addData2Series("Water", $row[0], $row[3]); 
            $oSeries->addData2Series("Machines",$row[0], $row[4]);
            $oSeries->addData2Series("Car",$row[0], $row[5]);
            $oSeries->addData2Series("Truck",$row[0], $row[6]);
            $oSeries->addData2Series("Van",$row[0], $row[7]);
            $oSeries->addData2Series("Bicycle",$row[0], $row[8]);
            $oSeries->addData2Series("Motorbike",$row[0], $row[9]);
            $oSeries->addData2Series("Trailer",$row[0], $row[10]); 
            $oSeries->addData2Series("Other Assets",$row[0], $row[11]); 
            $oSeries->addData2Series("Total Enterprises",$row[0], $row[12]);  
        } 

        echo $oSeries->exportJasonSeries();
    }
    public function sectors_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
        $strWhere = $this->getEnterpriseFilter();

        $strSQL = sprintf("SELECT Municipality AS `Municipality`,
                                            IFNULL(SUM(Sec_Agri),0) AS `Agriculture`, IFNULL(SUM(Sec_Manu),0) AS `Manufacturing`, IFNULL(SUM(Sec_Retail),0) AS `Retail`,
                                            IFNULL(SUM(Sec_Minerals),0) AS `Minerals`, IFNULL(SUM(Sec_Arts),0) AS `Arts_and_Crafts`, IFNULL(SUM(Sec_General),0) AS `General_Services`, 
                                            IFNULL(SUM(Sec_Other),0) AS `Other`, count(*)
                                            FROM `enterprise_base_view` where  %s
                                            GROUP BY municipality",$strWhere);                                   
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n"); 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Agriculture",$row[0], $row[1]);
            $oSeries->addData2Series("Manufacturing",$row[0], $row[2]); 
            $oSeries->addData2Series("Retail", $row[0], $row[3]); 
            $oSeries->addData2Series("Minerals",$row[0], $row[4]);
            $oSeries->addData2Series("Arts n Crafts",$row[0], $row[5]);
            $oSeries->addData2Series("General Services",$row[0], $row[6]);
            $oSeries->addData2Series("Other Sector",$row[0], $row[7]);
            $oSeries->addData2Series("Total Enterprises",$row[0], $row[8]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function loans_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEnterpriseFilter();
        
        /*$stmt = $concustomercontrol->query("select municipality, sum(finance_count), sum(amount_issued), sum(repay_amount), sum(amount_outstanding), count(*)
                                            from enterprise_loans_count_view where province_id = 5
                                            group by municipality");  */
        
        $strSQL = sprintf("SELECT Municipality, sum(IF(b.finance_ID IS NOT NULL, 1, 0)) AS `Finance_Count`,
                        sum(b.Amount_Issued) AS `amount_issued`,
                        sum(b.Repay_Amount) AS `repay_amount`,
                        sum(b.Amount_Outstanding) AS `amount_outstanding`, count(*)
                        FROM enterprise_base_view 
                            LEFT JOIN finance b ON enterprise_base_view.Enterprise_ID = b.Enterprise_ID where  %s
                        GROUP BY municipality",$strWhere);                                   
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n"); 
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("No of Loans",$row[0], $row[1]);
            //$oSeries->addData2Series("Total Amount Loaned",$row[0], $row[2]); 
            //$oSeries->addData2Series("Total Repay Amount", $row[0], $row[3]); 
            //$oSeries->addData2Series("Total Amount Outstanding",$row[0], $row[4]);
            $oSeries->addData2Series("Total Enterprises",$row[0], $row[5]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function education_level_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        $strWhere = $this->getEntrepreneurFilter();
        
        //$stmt = $concustomercontrol->query("SELECT * FROM education_level_view");
        
        $strSQL = sprintf("SELECT `entrepreneur_view`.`Municipality` AS `municipality`,
                            `entrepreneur_view`.`Education_Level` AS `education_level`,
                            COUNT(0) AS `Total`
                            FROM
                            `entrepreneur_view` where  %s
                            GROUP BY municipality, education_level",$strWhere);                                   
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
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

    public function sectors(){
        global $concustomercontrol;
        $oSeries = new Series();

        $intNum = $this->Country_ID+0;
        $strProvince = $this->Province.''; 
        $stmt = $concustomercontrol->query(sprintf("SELECT Province,
                                                    IFNULL(SUM(`enterprise_base_view`.`Sec_Agri`),0) AS `Agriculture`,
                                                    IFNULL(SUM(`enterprise_base_view`.`Sec_Manu`),0) AS `Manufacturing`,
                                                    IFNULL(SUM(`enterprise_base_view`.`Sec_Retail`),0) AS `Retail`,
                                                    IFNULL(SUM(`enterprise_base_view`.`Sec_Minerals`),0) AS `Minerals`,
                                                    IFNULL(SUM(`enterprise_base_view`.`Sec_Arts`),0) AS `Arts_and_Crafts`,
                                                    IFNULL(SUM(`enterprise_base_view`.`Sec_General`),0) AS `General_Services`,
                                                    IFNULL(SUM(`enterprise_base_view`.`Sec_Other`),0) AS `Other`
                                                    FROM enterprise_base_view WHERE Country_ID=%s %s
                                                    GROUP BY Province",$intNum,($strProvince==""?" AND Province_ID=5 ":sprintf(" AND Province_ID=%s",$strProvince))));
         
        
         while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[0], 'Agriculture',$row[1]);
            $oSeries->addData2Series($row[0], 'Manufacturing',$row[2]);
            $oSeries->addData2Series($row[0], 'Retail',$row[3]);
            $oSeries->addData2Series($row[0], 'Minerals',$row[4]);
            $oSeries->addData2Series($row[0], 'Arts and Crafts',$row[5]);
            $oSeries->addData2Series($row[0], 'General_Services',$row[6]);
            $oSeries->addData2Series($row[0], 'Other',$row[7]);
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
    
    
    
    
    
    
    
    
    public function entrepreneur_income_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
        $strWhere = $this->getEntrepreneurFilter();
              
        
        $strSQL = sprintf("select municipality, min(Income_Before_Awome), avg(Income_Before_Awome), max(Income_Before_Awome), sum(Income_Before_Awome), count(*)
                            from entrepreneur_view where  %s
                            GROUP BY municipality",$strWhere);                                   
         $stmt = $concustomercontrol->query($strSQL); 

        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Minimun Income",$row[0], $row[1]);
            $oSeries->addData2Series("Average Income",$row[0], $row[2]); 
            $oSeries->addData2Series("Maximum Income", $row[0], $row[3]); 
            $oSeries->addData2Series("Total Income)",$row[0], $row[4]);
            $oSeries->addData2Series("Total Entrepreneurs",$row[0], $row[5]);  
        } 
        echo $oSeries->exportJasonSeries();
    }
    
    public function support_received_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
        $strWhere = $this->getEntrepreneurFilter();

        //$stmt = $concustomercontrol->query("select municipality, sum(support_grant), sum(support_any_grant), sum(support_pension), sum(support_family), 
        //                                    sum(support_savings), sum(Support_NA), sum(Support_other), count(*)
        //                                    from entrepreneur_view where province_id = 5
        //                                    group by municipality");
 
        $strSQL = sprintf("select municipality, sum(support_grant), sum(support_any_grant), sum(support_pension), sum(support_family), 
                                            sum(support_savings), sum(Support_NA), sum(Support_other), count(*)
                                            from entrepreneur_view where  %s
                                            GROUP BY municipality",$strWhere);
         $stmt = $concustomercontrol->query($strSQL);
         
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Grant",$row[0], $row[1]);
            $oSeries->addData2Series("Any Grant",$row[0], $row[2]); 
            $oSeries->addData2Series("Pension", $row[0], $row[3]); 
            $oSeries->addData2Series("Family",$row[0], $row[4]);
            $oSeries->addData2Series("Savings",$row[0], $row[5]);
            $oSeries->addData2Series("Not Applicable",$row[0], $row[6]);
            $oSeries->addData2Series("Other Sources",$row[0], $row[7]);
            $oSeries->addData2Series("Total Entrepreneurs",$row[0], $row[8]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function technology_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
        $strWhere = $this->getEntrepreneurFilter();

       //$stmt = $concustomercontrol->query(sprintf("select municipality, sum(Access_Regular_phone), sum(access_smart_phone), sum(access_computer), sum(access_internet),count(*)
       //                                             from entrepreneur_view where Country_ID=%s %s
       //                                             GROUP BY municipality",$intNum,($strProvince==""?" AND Province_ID=5 ":sprintf(" AND Province_ID=%s",$strProvince))));
        
        $strSQL = sprintf("select municipality, sum(Access_Regular_phone), sum(access_smart_phone), sum(access_computer), sum(access_internet),count(*)
                                                    from entrepreneur_view where  %s
                                            GROUP BY municipality",$strWhere);
         $stmt = $concustomercontrol->query($strSQL);
         
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Regular Phone",$row[0], $row[1]);
            $oSeries->addData2Series("Smart Phone",$row[0], $row[2]); 
            $oSeries->addData2Series("Computer", $row[0], $row[3]); 
            $oSeries->addData2Series("Internet",$row[0], $row[4]);
            $oSeries->addData2Series("Total Entrepreneurs",$row[0], $row[5]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
     
     public function challenges_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
         $strWhere = $this->getEntrepreneurFilter();
        
       // $stmt = $concustomercontrol->query("select municipality, sum(Challenge_Education),sum(Challenge_Family),sum(Challenge_Health),sum(Challenge_Disability),sum(Challenge_GBV),sum(Challenge_NA),sum(Challenge_Other),count(*)
       //                                     from entrepreneur_challenges_pivot_view where province_id = 5
       //                                     group by municipality");
         $strSQL = sprintf("select municipality, sum(Challenge_Education),sum(Challenge_Family),sum(Challenge_Health),sum(Challenge_Disability),sum(Challenge_GBV),sum(Challenge_NA),sum(Challenge_Other),count(*)
                                                    from entrepreneur_challenges_pivot_view where  %s
                                            GROUP BY municipality",$strWhere);
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Education",$row[0], $row[1]);
            $oSeries->addData2Series("Family",$row[0], $row[2]); 
            $oSeries->addData2Series("Health", $row[0], $row[3]); 
            $oSeries->addData2Series("Disability",$row[0], $row[4]);
            $oSeries->addData2Series("GBV",$row[0], $row[5]);
            $oSeries->addData2Series("Not Applicable",$row[0], $row[6]);
            $oSeries->addData2Series("Other Challenges",$row[0], $row[7]);
            $oSeries->addData2Series("Total Entrepreneurs",$row[0], $row[8]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function race_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
        $strWhere = $this->getEntrepreneurFilter();
        
        $strSQL = sprintf("SELECT municipality, race,   COUNT(0)
                            FROM entrepreneur_view where  %s
                            GROUP BY municipality, race",$strWhere);
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[1],$row[0], $row[2]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function sex_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
         $strWhere = $this->getEntrepreneurFilter();
               
         $strSQL = sprintf("SELECT municipality, sex,   COUNT(0)
                            FROM entrepreneur_view where  %s
                            GROUP BY municipality, sex",$strWhere);
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[1],$row[0], $row[2]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function marital_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
         $strWhere = $this->getEntrepreneurFilter();
              
         $strSQL = sprintf("SELECT municipality, marital_status,   COUNT(0)
                            FROM entrepreneur_view where  %s
                            GROUP BY municipality, marital_status",$strWhere);
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series($row[1],$row[0], $row[2]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    
    public function age_view(){
        global $concustomercontrol;
        $oSeries = new Series();
        
         $strWhere = $this->getEntrepreneurFilter();
              
         $strSQL = sprintf("select municipality, 
		                    sum(if(year(curdate()) - year(birth_date) < 20, 1 , 0)) as 'Below_20',
                            sum(if(year(curdate()) - year(birth_date) between 20 and 29, 1 , 0)) as '20-29',
                            sum(if(year(curdate()) - year(birth_date) between 30 and 39, 1 , 0) )as '30-39',
                            sum(if(year(curdate()) - year(birth_date) between 40 and 49, 1 , 0)) as '40-49',
                            sum(if(year(curdate()) - year(birth_date) between 50 and 59, 1 , 0)) as '50-59',
                            sum(if(year(curdate()) - year(birth_date) between 60 and 69, 1 , 0)) as 'Below_30',
                            sum(if(year(curdate()) - year(birth_date) > 69, 1 , 0)) as 'Over_69', count(*)
                        from entrepreneur_view where  %s
                        GROUP BY municipality",$strWhere);
         $stmt = $concustomercontrol->query($strSQL);
        //echo('//Sql Stmt = '. $strSQL."\n");
        while ($row=$stmt->fetch_array()) {
            $oSeries->addData2Series("Below 20",$row[0], $row[1]);
            $oSeries->addData2Series("20 to 29",$row[0], $row[2]); 
            $oSeries->addData2Series("30 to 39", $row[0], $row[3]); 
            $oSeries->addData2Series("40 to 49",$row[0], $row[4]);
            $oSeries->addData2Series("50 to 59",$row[0], $row[5]);
            $oSeries->addData2Series("60 to 69",$row[0], $row[6]);
            $oSeries->addData2Series("Over 69",$row[0], $row[7]);
            $oSeries->addData2Series("Total Entrepreneurs",$row[0], $row[8]);
              
        } 

        echo $oSeries->exportJasonSeries();
    }
    private function getEnterpriseFilter(){
          
     $strWhere = sprintf("Country_ID=%s",$this->Country_ID+0);
        if( $this->Province.''!="Select"){
             $strWhere.=sprintf(" AND  Province_ID='%d' ",$this->Province.'');
        }
        if( $this->DistrictMetro.''!="Select"){
             $strWhere.=sprintf(" AND  District_Metro_ID='%d' ",$this->DistrictMetro.'');
        }
        if( $this->Municipality.''!="Select"){
             $strWhere.=sprintf(" AND  Municipality_ID='%d' ",$this->Municipality.'');
        }
        
        if( $this->Sector!="Select" && $this->Sector!=""){
            //echo('im in sectors');
            //echo('Sector = '. $this->Sector."\n");
            if( $this->Sector=="2"){
             $strWhere.=sprintf(" AND sec_agri =1 ");
            }
            if( $this->Sector=="3"){
             $strWhere.=sprintf(" AND sec_manu =1 ");
            }
            if( $this->Sector=="4"){
             $strWhere.=sprintf(" AND sec_retail =1 ");
            }
            if( $this->Sector=="5"){
             $strWhere.=sprintf(" AND sec_minerals =1 ");
            }
            if( $this->Sector=="6"){
             $strWhere.=sprintf(" AND sec_arts =1 ");
            }
            if( $this->Sector=="7"){
             $strWhere.=sprintf(" AND sec_general =1 ");
            }
            if( $this->Sector=="8"){
             $strWhere.=sprintf(" AND sec_other =1 ");
            }
            
        }
        
        if( $this->SubSector!="Select" && $this->SubSector!=""){
            if( $this->SubSector=="Agriculture"){
             $strWhere.=sprintf(" AND sec_agric =1 ");
            }
            if( $this->SubSector=="Manufacturing"){
             $strWhere.=sprintf(" AND sec_agric =1 ");
            }
            if( $this->SubSector=="Retail"){
             $strWhere.=sprintf(" AND sec_agric =1 ");
            }
            if( $this->SubSector=="Mining"){
             $strWhere.=sprintf(" AND sec_agric =1 ");
            }
            if( $this->SubSector=="Arts and Crafts"){
             $strWhere.=sprintf(" AND sec_agric =1 ");
            }
            if( $this->SubSector=="General Services"){
             $strWhere.=sprintf(" AND sec_agric =1 ");
            }
            if( $this->SubSector=="Other"){
             $strWhere.=sprintf(" AND sec_agric =1 ");
            }
        }
        if( $this->LegalStructure!="Select" && $this->LegalStructure!=""){
             $strWhere.=sprintf(" AND  Legal_Structure ='%s' ",$this->LegalStructure);
        }
                
        if( $this->YearsOperating!="Select" && $this->YearsOperating!=""){
            if( $this->YearsOperating=="1"){
             $strWhere.=sprintf(" AND year(curdate()) - year_established = 1 ");
            }
            if( $this->YearsOperating=="2"){
             $strWhere.=sprintf(" AND year(curdate()) - year_established = 2 ");
            }
            if( $this->YearsOperating=="3"){
             $strWhere.=sprintf(" AND year(curdate()) - year_established = 3 ");
            }
            if( $this->YearsOperating=="4"){
             $strWhere.=sprintf(" AND year(curdate()) - year_established = 4 ");
            }
            if( $this->YearsOperating=="5"){
             $strWhere.=sprintf(" AND year(curdate()) - year_established = 5 ");
            }
            if( $this->YearsOperating=="6"){
             $strWhere.=sprintf(" AND year(curdate()) - year_established = 6 ");
            }
            if( $this->YearsOperating=="Over 6"){
             $strWhere.=sprintf(" AND year(curdate()) - year_established > 6 ");
            }
        }
        if( $this->Wages!="Select" && $this->Wages!=""){
            if( $this->Wages=="Below R160/d"){
             $strWhere.=sprintf(" AND (Female_PT_160  = 1 or Male_PT_160 = 1) ");
            }
            if( $this->Wages=="Over R160/d"){
             $strWhere.=sprintf(" AND (Female_PT_160_Plus = 1 or Male_PT_160_Plus = 1) ");
            }
            if( $this->Wages=="Below R2,500"){
             $strWhere.=sprintf(" AND (Female_FT_2500 =1 or Male_FT_2500 = 1) ");
            }
            if( $this->Wages=="R2,501-R3,000"){
             $strWhere.=sprintf(" AND (Female_FT_3000 =1 or Male_FT_3000 = 1) ");
            }
            if( $this->Wages=="R3,001-R3,500"){
             $strWhere.=sprintf(" AND (Female_FT_3500 =1 or Male_FT_3500 = 1) ");
            }
            if( $this->Wages=="Over R3,500"){
             $strWhere.=sprintf(" AND (Female_FT_3500_Plus =1 or Male_FT_3500_Plus = 1) ");
            }    
        }
        if( $this->Income!="Select" && $this->Income!=""){
            if( $this->Income=="R0-R3,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income between 0 and 3000 ");
            }
            if( $this->Income=="R3,001-R6,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income between 3001 and 6000 ");
            }
            if( $this->Income=="R6,001-R9,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income between 6001 and 9000 ");
            }
            if( $this->Income=="R9,001-R12,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income between 9001 and 12000 ");
            }
            if( $this->Income=="R12,001-R15,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income between 12001 and 15000 ");
            }
            if( $this->Income=="R15,001-R18,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income between 15001 and 18000 ");
            }
            if( $this->Income=="R18,001-R21,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income between 18001 and 21000 ");
            }
            if( $this->Income=="Over R21,000"){
             $strWhere.=sprintf(" AND avg_sales + avg_other_income > 21000 ");
            }  
        }
        
        if( $this->Trainer!="Select" && $this->Trainer!=""){
             $strWhere.=sprintf(" AND  Responsible_Trainer='%s' ",$this->Trainer);
        }
        
        if( $this->Sex!="Select" && $this->Sex!=""){
            if( $this->Sex=="Female Only"){
             $strWhere.=sprintf(" AND  females_owners > 0");
            }
            if( $this->Sex=="Both"){
             $strWhere.=sprintf(" AND (females_owners > 0 or male_owners > 0) ");
            }
            if( $this->Sex=="Male Only"){
             $strWhere.=sprintf(" AND males_owners > 0 ");
            }
            // $strWhere.=sprintf(" AND  sex='%s' ",$this->Sex);
        }
        
        if( $this->Age!="Select" && $this->Age!=""){
            if( $this->Age=="Below 20"){
            // $strWhere.=sprintf(" AND  year(curdate()) - year(birth_date) < 20");
             $strWhere.=sprintf(" AND  enterprise_id in (select enterprise_id from enterprise_member where entrepreneur_id in (select entrepreneur_id from entrepreneur where year(curdate()) - year(birth_date) < 20 ))");
            }
            if( $this->Age=="Over 69"){
             //$strWhere.=sprintf(" AND  year(curdate()) - year(birth_date) > 69");
             $strWhere.=sprintf(" AND  enterprise_id in (select enterprise_id from enterprise_member where entrepreneur_id in (select entrepreneur_id from entrepreneur where year(curdate()) - year(birth_date) > 69 ))");
            }
            if( strpos($this->Age, '-') !== false){
             $arr = explode("-", $this->Age);
             //$strWhere.=sprintf(" AND year(curdate()) - year(birth_date) between'%d' AND '%d' ",$arr[0],$arr[1]);
             $strWhere.=sprintf(" AND  enterprise_id in (select enterprise_id from enterprise_member where entrepreneur_id in (select entrepreneur_id from entrepreneur where year(curdate()) - year(birth_date) between '%d' AND '%d' ))",$arr[0],$arr[1]);
            } 
        } 
        return $strWhere;
     }
     
    private function getEntrepreneurFilter(){
          
     $strWhere = sprintf("Country_ID=%s",$this->Country_ID+0);
        if( $this->Province.''!="Select"){
             $strWhere.=sprintf(" AND  Province_ID='%d' ",$this->Province.'');
        }
        if( $this->DistrictMetro.''!="Select"){
             $strWhere.=sprintf(" AND  District_Metro_ID='%d' ",$this->DistrictMetro.'');
        }
        if( $this->Municipality.''!="Select"){
             $strWhere.=sprintf(" AND  Municipality_ID='%d' ",$this->Municipality.'');
        }
        if( $this->Sex!="Select" && $this->Sex!=""){
             $strWhere.=sprintf(" AND  sex='%s' ",$this->Sex);
        }
        if( $this->Race!="Select" && $this->Race!=""){
             $strWhere.=sprintf(" AND  race='%s' ",$this->Race);
        }
        if( $this->Age!="Select" && $this->Age!=""){
            if( $this->Age=="Below 20"){
             $strWhere.=sprintf(" AND  year(curdate()) - year(birth_date) < 20");
            }
            if( $this->Age=="Over 69"){
             $strWhere.=sprintf(" AND  year(curdate()) - year(birth_date) > 69");
            }
            if( strpos($this->Age, '-') !== false){
             $arr = explode("-", $this->Age);
             $strWhere.=sprintf(" AND year(curdate()) - year(birth_date) between'%d' AND '%d' ",$arr[0],$arr[1]);
            } 
        } 
        if( $this->EducationLevel!="Select" && $this->EducationLevel!=""){
             $strWhere.=sprintf(" AND  Education_Level='%s' ",$this->EducationLevel);
        }
        if( $this->MaritalStatus!="Select" && $this->MaritalStatus!=""){
             $strWhere.=sprintf(" AND  Marital_Status='%s' ",$this->MaritalStatus);
        }
        if( $this->Trainer!="Select" && $this->Trainer!=""){
             $strWhere.=sprintf(" AND  Responsible_Trainer='%s' ",$this->Trainer);
        }
        if( $this->NoOfChildren!="Select" && $this->NoOfChildren!=""){
            if ( $this->NoOfChildren=="Over 10"){
               $strWhere.=sprintf(" AND  Children > 10");
            }
            else{
             $strWhere.=sprintf(" AND  Children='%s' ",$this->NoOfChildren);
             }
        }
        if( $this->NoOfPeopleHousehold!="Select" && $this->NoOfPeopleHousehold!=""){
             if ( $this->NoOfPeopleHousehold=="Over 10"){
               $strWhere.=sprintf(" AND  People_in_Household > 10");
            }
            else{
             $strWhere.=sprintf(" AND  People_in_Household='%s' ",$this->NoOfPeopleHousehold);
            } 
        }
        if( $this->NoOfPeopleSupported!="Select" && $this->NoOfPeopleSupported!=""){
        if ( $this->NoOfPeopleSupported=="Over 10"){
               $strWhere.=sprintf(" AND  People_Supported > 10");
            }
            else{
             $strWhere.=sprintf(" AND  People_Supported='%s' ",$this->NoOfPeopleSupported);
            } 
        }
        return $strWhere;
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