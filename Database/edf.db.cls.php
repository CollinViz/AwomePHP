<?php

class edf{

public function checkLogin($strUserName,$strPassword){
    global $concustomercontrol;
    $stmt = $concustomercontrol->prepare("SELECT * FROM edf 
                                WHERE UserName=? and Password=?");
    $strUserName = sprintf("%-45.45s", $strUserName);
    $strPassword = sprintf("%-45.45s", $strPassword);
    $stmt->bind_param("ss",$strUserName,$strPassword);
    $stmt->execute();
    //echo  "[".$strUserName."]";
    return  $stmt->get_result();
}


}

?>