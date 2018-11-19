<?php
// $BASE_PATH = dirname(__FILE__);
// $DEPENDS_PATH  = ".;".$BASE_PATH;
// $DEPENDS_PATH .= ";".$BASE_PATH."/Include";
// ini_set("include_path", ini_get("include_path").";".$DEPENDS_PATH);

require_once 'html.php'; 
require_once 'config.php'; 
require_once 'include/mysql2javaS.php';
require_once 'include/sqlcode.php'; 
require_once 'Database/customers.db.cls.php';
class ListBasicGUI extends html {
	public $OUT_PUT_TYPE="ANGULARJS5";
	protected $CLASSNAME="LoginGUI";
	protected $AJAX_SCRIP="ajax.php";
    protected $B_DEBUG=false; 
    
    public function listCustomers(){
        $customer = new customers();

        echo mysql2javaS::mysqlRow2JavaSArray($customer->listcustomers());

    }
    public function listCustomersByID(){
        $customer = new customers();

        echo mysql2javaS::Array2JavaSArray($customer->listcustomers($this->id)->fetch_assoc());

    }
    public function CreateCustomer(){
        $o = array("OK"=>"Saved");
        $customer = new customers();
        //Check username must be unique
        $NumUsed = $customer->checkUserNameUsed($this->Username,$this->id)->fetch_assoc();
        if($NumUsed["num"]>0){
            $o["error"]="Username already used";
        }else{
            if($this->id==-1){
             
                $customer->createcustomers($this->id,
                                         $this->Name, 
                                         $this->Address,  
                                         $this->Username, 
                                         $this->Password,
                                         $this->Flag);
            }else{
    
                $customer->updatecustomers($this->id,
                                         $this->Name, 
                                         $this->Address , 
                                         $this->Username, 
                                         $this->Password,
                                         $this->Flag);
            }
        }
        
        
        
        echo mysql2javaS::Array2JavaSArray( $o );
    }
    public function updateCustomer(){
        $customer = new customers();

        $customer->updatecustomers($this->id,
                                     $this->Name, 
                                     $this->Address , 
                                     $this->Username, 
                                     $this->Password,
                                     $this->Flag);
        $o = array("OK"=>"Saved");
        echo mysql2javaS::Array2JavaSArray( $o );
    }
    public function DeleteCustomer(){
        $customer = new customers();

        $customer->deletecustomers($this->id );
        $o = array("OK"=>"Delete");
        echo mysql2javaS::Array2JavaSArray( $o );
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