<?php
/**
 * HTML class used to start HTMLobject and View state
 * 
 * @author Collin Visser
 * 
 */
class html{
	
	/**
	 * Buffer of java script
	 *
	 * @var string
	 */
	private $strJAVABUFFER;
	/**
	 * Set the path the the Guded debuger
	 *
	 * @var string
	 */
	private $DEBUG_GUDEB_PATH='/Gubed/StartSession.php?gbdScript=';
	
	/**
	 * Set the type of out put from the class
	 *
	 * @var string
	 */
	public $OUT_PUT_TYPE='HTML';
	/**
	 * Set the directory where the Ajax.php file is
	 *
	 * @var string
	 */
	protected $AJAX_SCRIP='/ajax.php';
	/**
	 * Set the of the Sesstion
	 *
	 * @var string
	 */
	protected $CLASSNAME;
	/**
	 * Set if the debuging window is show
	 *
	 * @var string
	 */
	protected $B_DEBUG = false;
	/**
	 * HTML vars
	**/
	public $objHML;
	public $m_json;
	private $m_bJavaoutput = false;
	
	//JAVA Scrip Function
	/**
	 * Add a function to call when the Script runs in the Browser
	 *
	 * @param string $fuction
	 */
	function addCallFunction($fuction){		
		$this->javabufferadd($fuction.(strpos($fuction,";")==false?';':''));
	}	
	/**
	 * Set value or changes selection on select box or set the inner html of a html object
	 *
	 * @param string $HTMLObjName
	 * @param string $value
	 */
	function addsetHtmlObjValue($HTMLObjName,$value){
		$this->javabufferadd("__updateobjvalue(\"".$HTMLObjName."\",\"".$this->java_escapeString($value)."\");\n");
	}
	/**
	 * Set a attabut of a html object
	 *
	 * @param string $HTMLObjName
	 * @param string $ElementName
	 * @param string $value
	 */
	function addUpdateHtmlObjElement($HTMLObjName,$ElementName,$value){
		$this->javabufferadd("__updateElement(\"".$HTMLObjName."\",\"".$ElementName."\",\"".$this->java_escapeString($value)."\");\n");
	}
	/**
	 * Adds a Event Handeler for a html object
	 *
	 * @param string $HTMLObjName
	 * @param string $EventName
	 * @param string $functionDef
	 */
	function addUpdateHtmlObjEvent($HTMLObjName,$EventName,$functionDef){
		$this->javabufferadd("__updateHtmlObjEvent(\"".$HTMLObjName."\",\"".$EventName."\",\"".$this->java_escapeString($functionDef)."\");\n");
	}
	
	/**
	 * Create a list of options to a select object
	 *
	 * @param String $SelectboxName
	 * @param string $value
	 * @param string $Datadeliminater
	 * @param string $KeyDeliminater
	 * @param string $SelecedKey
	 */
	function addsetSelectboxOptions($SelectboxName,$value,$Datadeliminater,$KeyDeliminater="",$SelecedKey=""){
		$this->javabufferadd("__setSelectboxOptions(\"".$SelectboxName."\",\"".$this->java_escapeString($value)."\",\"".$Datadeliminater."\",\"".$KeyDeliminater."\",\"".$SelecedKey."\");\n");
	}
	/**
	 * Show or Hides a htmlObject using style.display
	 *
	 * @param string $HTMLObjName Object name
	 * @param string $HideShow hide or show
	 */
	function addHideShowHtmlOBJ($HTMLObjName,$HideShow="hide"){
		$this->javabufferadd("__hideshowhtml(\"".$HTMLObjName."\",\"".$HideShow."\");\n");
	}
	
	public function addDatePicker($HTMLObjName,$format="yy-mm-dd"){
		$strBuffer = "$(document).ready(function() {";
		$strBuffer.= "$('#".$HTMLObjName."').datepicker({bgiframe: true,dateFormat: \"" . $format . "\"});";
		$strBuffer.= "});"; 
		$this->javabufferadd($strBuffer);
	}
	/**
	 * Removes all Char that javascript dos not like
	 *
	 * @param unknown_type $str
	 * @return unknown
	 */
	function java_escapeString($str){         
			$js_escape = array("\r" => '\r',"\n" => '\n',"\t" => '\t',"'"=> "\\'",'"'=> '\"','\\' => '\\\\');
			return strtr($str,$js_escape);
	}
	/**
	 * Add a message box to the Java buffer	
	 * @param $message
	 * @return void
	 */	
	public function addMessageBox($message,$functionOK="$(this).dialog('close');"){
		//$this->javabufferadd("alert('".$this->java_escapeString($message)."');");
		
		$strBuffer = "$(document).ready(function() {";
		$strBuffer.= "$('#_Alert_Message').attr('title','Alert message');";
		$strBuffer.= "$('#_Alert_Message').text('".$this->java_escapeString($message)."');";
		$strBuffer.= "$('#_Alert_Message').dialog({ buttons: { \"Ok\": function() { ".$functionOK." } } });";
		$strBuffer.= "});";
		$this->javabufferadd($strBuffer);
	}
	public function addMessageBoxAjax($message,$functionOK="$(this).dialog('close');"){
			
		//$strBuffer= "$('#_Alert_Message').attr('title','Alert message');";
		//$strBuffer.= "$('#_Alert_Message').text('".$this->java_escapeString($message)."');";
		//$strBuffer.= "$('#_Alert_Message').dialog({ buttons: { \"Ok\": function() { ".$functionOK." } } });";
		
		$this->javabufferadd("alert('".$this->java_escapeString($message)."')");
	}
	/**
	 * Returns a javascript ready message box	
	 * @param $message
	 * @return string
	 */
	public function getMessageBox($message){
		return $this->getJavaHead("alert('".$this->java_escapeString($message)."')");
	}
	
	
	/**
	 * Adds header add footer code for Javascript to run
	 *
	 * @param string $script
	 * @return javascripString
	 */
	public function getJavaHead($script){
		if($this->OUT_PUT_TYPE=="HTML"){			
			return "<script language=\"JavaScript\" > \n".$script."\n</script>";
		}else{
			return $script;
		}
	}
	/**
	 * Returns all javascript buffer
	 *
	 * @return string
	 */
	public function javabuffer(){
		//echo "OUT_PUT_TYPE=". $this->OUT_PUT_TYPE;
		$this->m_bJavaoutput=true;
		if($this->OUT_PUT_TYPE=="HTML"){			
			return "<script language=\"JavaScript\" >//function used by html.php\n if(window.__isviewstate){__onload();}else{alert('Error loading php viewstate');}document.__classname=\"".$this->java_escapeString($this->CLASSNAME)."\";document.__ajaxpath=\"".$this->java_escapeString($this->AJAX_SCRIP)."\"; \n".$this->strJAVABUFFER."\n</script>";
		}elseif($this->OUT_PUT_TYPE=="AJAX"){
			//echo "\njavabuffer [".$this->OUT_PUT_TYPE."]\n-----".$this->strJAVABUFFER;
			return "@@@START_PHPVIEWAJAX@@@".$this->strJAVABUFFER."@@@END_PHPVIEWAJAX@@@";
		}elseif($this->OUT_PUT_TYPE=="ANGULARJS"){
			return "<script language=\"JavaScript\" >//function used by html.php\ndocument.__classname=\"".$this->java_escapeString($this->CLASSNAME)."\";document.__ajaxpath=\"".$this->java_escapeString($this->AJAX_SCRIP)."\"; \n".$this->strJAVABUFFER."\n</script>";
		}else{
			return $this->strJAVABUFFER;
		}			
	}
	/**
	 * Add javascript to the Javascript buffer
	 * It is not recommended that this function get used directly
	 * rather use the add* function 
	 * @param unknown_type $strJavaBuff
	 */
	public function javabufferadd($strJavaBuff){
		$this->strJAVABUFFER.="\t".$strJavaBuff."\n";
		
	}
	
	/**
	 * USED ONLY IN THE CONSTRUCTION FUNCTIONS
	 * Test to see if there is a post back
	 *
	 * @return bool
	 */
	public function isPOST(){
		if(count($_POST)>0){
			return true;
		}
		return false;
	}
	/**
	 * outputs debuging info 
	 *
	 * @param string $message
	 */
	public function debug_html($message){
		echo "\n<!--\n\t".$message;		
		echo "\n-->";

	}
	/**
	 * USED ONLY IN THE CONSTRUCTION FUNCTIONS
	 *
	 * @return bool
	 */
	public function isEventFunction(){
		//if(isset($this->__get["__call"])){
			if($this->__get("__call")!=""){
				return true;
			}
		//}
		return false;
	}
	/**
	 * USED ONLY IN THE CONSTRUCTION FUNCTIONS
	 *
	 * @return string
	 */
	public function getEventFunctionName(){
		$func_escape = array("\\'" => "'");
		if($this->__get("__call")!=""){			
			$strFuctionName =strtr($this->__get("__call"),$func_escape);			  
			if(strrpos($strFuctionName,"(") ===false){ //no Argements
			$strMM = '$this->'.$strFuctionName."();";	
			}else{
				$strMM = '$this->'.$strFuctionName.";";	
			}
			return $strMM;
		}
		return "";
	}
	
	/**
	* @return void
	* @param string $OUTPUTTYPE HTML or XML or AJEX
	* @desc Set the type of output HTML , XML or AJEX
	*/
	public function setOutPutType($OUTPUTTYPE){
		$this->OUT_PUT_TYPE=$OUTPUTTYPE;
	}
	/**
	 * serialize object to Session
	 *
	 */
	protected function savehtml() {	
	  $_SESSION[$this->CLASSNAME] = serialize($this->objHML);
	}
	protected function cleandebugoutput($MixData){
		if(is_array($MixData)){
			$strBuffer = "Array {\n";
			foreach ($MixData as $dataKey => $dataValue){
				$strBuffer.="\t$dataKey = ";
				if(is_array($dataValue)){
					$this->cleandebugoutput($dataValue);
				}else{
					$strBuffer.="".(string)$dataValue."\n";
				}
			}
			return "<pre>".$this->java_escapeString(htmlentities($strBuffer."\n }"))." </pre>";
		}else{
			return $this->java_escapeString(htmlentities((string)$MixData));
		}
	}
	public function email($EmailBody,$ToAddress,$Subject,$FromAddress='odex@eberspaecher.com'){
	
		$to = $ToAddress;
 
		
		$headers = "From: " . strip_tags($FromAddress) . "\r\n";
		$headers .= "Reply-To: ". strip_tags($FromAddress) . "\r\n"; 
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
		mail($ToAddress, $Subject, $EmailBody, $headers);
	}
	//dont use now but might need
	public function __ondebug(){
				
	}
	/**
	 * Construction	
	 * @return void
	 */
	public function __construct() {		
		$this->scriptruntime= microtime(true);

		session_id();		
		if ( ! session_id() ) {
		   session_start();
		}
				
		$this->strJAVABUFFER="//JAVA BUFFER CREATED BY HTML CLASS\n";
		if(isset($_SESSION[$this->CLASSNAME] )){			
			$this->objHML = unserialize($_SESSION[$this->CLASSNAME]);
		}else{
			$this->objHML = new viewstate_html();
			$_SESSION[$this->CLASSNAME] = serialize($this->objHML);
			
		}
		if(!$this->isPOST()){
			$this->objHML = new viewstate_html();
		}
		if($this->__get("__OUTPUTTYPE")!=""){
			$this->OUT_PUT_TYPE = $this->__get("__OUTPUTTYPE");
		}

	}
	function __destruct() {		
		//debug
		
		if($this->OUT_PUT_TYPE =="AJAX"){
			//if($this->m_bJavaoutput==false){
				echo $this->javabuffer();
			//}
		}
		if($this->OUT_PUT_TYPE =="ANGULARJS"){
			//if($this->m_bJavaoutput==false){
			echo $this->javabuffer();
			//}
		}
		 
		if($this->OUT_PUT_TYPE =='HTML'){
			if($this->m_bJavaoutput==false){
				echo $this->javabuffer();
			}
			
			if($this->B_DEBUG==true){
				$strServerURL = $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
				$strServerURL = substr($strServerURL,0,strrpos($strServerURL,"/"));
				$intTogel = 1;
				$strdebug="var y = \"<style type='text/css'> ";
				$strdebug.=".mainhd {";
				$strdebug.="font-family: Arial, Helvetica, sans-serif;";
				$strdebug.="font-style: normal;font-size: 11px;";
				$strdebug.="color: #FFFFFF;";
				$strdebug.="background-color: #999999;";
				$strdebug.="text-transform: uppercase;";
				$strdebug.="ext-decoration: underline;";
				$strdebug.="}";
				$strdebug.=".C1 {";
				$strdebug.="font-family: Arial, Helvetica, sans-serif;";
				$strdebug.="background-color: #CCCCCC;font-size: 10px;";
				$strdebug.="}";
				$strdebug.=".C2 {";
				$strdebug.="font-family: Arial, Helvetica, sans-serif;";
				$strdebug.="background-color: #FFFFFF;font-size: 10px;";
				$strdebug.="}";
				$strdebug.="</style>\";";
				$strdebug.="var x = \"<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0> ";
				$strdebug.="<table width=100% border=0 cellspacing=0 cellpadding=5>";
				$strdebug.= "<tr><td colspan=4 align=right>";
				if($this->__IsGubeddebug!="1"){
					$strdebug.="<input name=cmddebug type=button id=cmddebug value='debug this page' onclick=\\\"window.opener.document.__timerX = window.opener.setInterval('__debugurl()',10);\\\">";
				}
				$strdebug.="</td></tr>";
				$strdebug.= "<tr class='mainhd'><td colspan=4>Event Run : <b>".$this->__call."</b></td></tr>";
				$strdebug.= "<tr class='mainhd'><td colspan=4>POST</td></tr>";
				foreach ($_POST as $key => $value) {
					if(substr($key,0,2)!="__"){
				   		$strdebug.= "<tr class='C".$intTogel."' name='post' id='post'><td></td><td>$key</td><td>".$this->cleandebugoutput($value)."</td> <td></td></tr>";
				   		$intTogel = ($intTogel==1?2:1);
					}
				}
				$intTogel = 1;
				$strdebug.= "<tr class='mainhd'><td colspan=4>GET</td></tr>";
				foreach ($_GET as $key => $value) {
				   $strdebug.= "<tr class='C".$intTogel."' name='get' id='get'><td></td><td>$key</td><td>".$this->cleandebugoutput($value)."</td> <td></td></tr>";
				   $intTogel = ($intTogel==1?2:1);
				}
				$intTogel = 1;
				if(is_array($this->objHML->arHTMLObject)){
					$strdebug.= "<tr class='mainhd'><td colspan=4>View State</td></tr>";
					foreach ($this->objHML->arHTMLObject as $key => $value) {
					   $strdebug.= "<tr class='C".$intTogel."' name='view' id='view'><td></td><td>$key</td><td>".$this->cleandebugoutput($value["value"])." </td><td>TTL:".$value["numused"]."</td></tr>";
					   $intTogel = ($intTogel==1?2:1);
					}
				}
				$strdebug.= "<tr class='mainhd'><td colspan=4>Run time  : <b>". (microtime(true)-$this->scriptruntime)." s</b></td></tr>";
				$strdebug.= "<tr class='mainhd'><td colspan=4>AJAX Scrip  : <textarea name='txtajax' id='txtajax' cols='50' rows='5'></textarea></td></tr>";
				
				$strdebug.="</table>";
				$strdebug.="</body>\";";
				$strdebug.="var wndDebug = _debugWindow('','_debug',400,300,'yes','center');";			
				$strdebug.="wndDebug.document.write(y);";
				$strdebug.="wndDebug.document.write(x);";
				$strdebug.="wndDebug.document.bgColor=\"lightblue\";";
				$strdebug.="wndDebug.document.close(); ";
				$strdebug.="document.__debugwin = wndDebug; ";
				$strdebug.="document.__Hasdebugwin = true; ";				
				$strdebug.="document.__GubeddebugUrl= 'HTTP://".$_SERVER["SERVER_NAME"].$this->DEBUG_GUDEB_PATH."';";
				$strdebug.="document._IsGubeddebug = '".$this->__IsGubeddebug."'; ";
				$strdebug.="document.__serverurl= 'HTTP://".$strServerURL."/';";
				
				echo $this->getJavaHead($strdebug);
			}
		}
	}
	/**
	 * Used to get HTML Vars	
	 * @param $member
	 * @return uncknown
	 */
	public function __get($member) {
		//echo "__get ".$member."\n";
		if($this->objHML->_isset($member)){
			return $this->objHML->$member;
		}
        if (isset($_REQUEST[$member])) {
			//echo "Found ".$member ." VAUE ".$_POST[$member];
            return $_REQUEST[$member];
        }
        //echo "test json\n";
        //print_r($this->m_json);
        if(isset($this->m_json->$member)){
        	//echo "\nfound $member\n";
        	return $this->m_json->$member;
        }
        if(isset($this->m_json->{$member})){
        	echo "found $member";
        	return $this->m_json->{$member};
        }
        //$aJ = (array)$this->m_json;
        //print_r($this->m_json->key());
        //if(isset($aJ[$member])){
        //	return $aJ[$member];
        //}
		return "";
    }
    public function lazyEvent(){
    	//need to cheack if we need to run a Event
    	if($this->isEventFunction()){
    		$strMM = $this->getEventFunctionName();
    		eval($strMM);
    	}
    }
    public function __set($member, $value) {
    	if (isset($_REQUEST[$member])) {
			$_REQUEST[$member] = $value;			
        }else{
			if(is_object($this->objHML)){
        		$this->objHML->__set($member,$value);
			}
        } 
    }
	function __call($m, $a){
		if($this->OUT_PUT_TYPE =='HTML'){
       		print "Method $m not found:\n";
       		var_dump($a);
		}
		if($this->OUT_PUT_TYPE =='AJEX'){
			print " AJEX Method $m not found:\n";
       		var_dump($a);
		}
       return "";
   }
   
}


class viewstate_html{
	public $arHTMLObject;
	private $MAX_MEM_TTL=50;
	/**
	 * Return a Var from it intername 
	 * or it return HTTP POST	
	 * @param $member
	 * @return uncknown
	 */
	public function __get($member) {		
		if(isset($this->arHTMLObject[$member])){
			$this->arHTMLObject[$member]["numused"] = 0;
			return $this->arHTMLObject[$member]["value"];
		}		
		return ""; //html::__get($member);        		
    }

    public function __set($member, $value) {
    	if(!isset($this->arHTMLObject[$member])){	
    		$this->arHTMLObject[$member] = array();
    	}
    	$this->arHTMLObject[$member]["numused"] =0;
		if(is_array($value)){
			$this->arHTMLObject[$member]["value"]=$value;
		}else{
			$this->arHTMLObject[$member]["value"]=(string)$value;
		}
		
    }
	public function  _isset($varname){
		return isset($this->arHTMLObject[$varname]);
	}
	function __sleep(){
		if(is_array($this->arHTMLObject)){
			foreach ($this->arHTMLObject as $key => $value) {
			   $this->arHTMLObject[$key]["numused"]=$value["numused"]+1;
			   if($value["numused"]>$this->MAX_MEM_TTL){
			   		//unset($this->arHTMLObject[$key]);
			   }
			}
		}
		return array("arHTMLObject");
	}
}
?>