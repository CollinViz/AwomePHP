<?php

class finance {

    public function delete($Enterprise_ID,$Enterprise_Visit_ID){
        global $concustomercontrol;
        if($Enterprise_Visit_ID!=""){
            $stmt = $concustomercontrol->prepare("DELETE FROM finance 
                                    WHERE Enterprise_ID=? and Enterprise_Visit_ID=?");
            
            $stmt->bind_param("ii",$Enterprise_ID,$Enterprise_Visit_ID);
            $stmt->execute();
        }else{
            $stmt = $concustomercontrol->prepare("DELETE FROM finance 
                                    WHERE Enterprise_ID=? and Enterprise_Visit_ID is null");
            
            $stmt->bind_param("i",$Enterprise_ID);
            $stmt->execute();
        }
    
    //echo  "[".$strUserName."]";
    //return  $stmt->get_result();
    }
    
    public function deleteCooperative($Cooperative_ID,$Cooperative_Visit_ID){
        global $concustomercontrol;
        if($Cooperative_Visit_ID!=""){
            $stmt = $concustomercontrol->prepare("DELETE FROM cooperative_finance 
                                    WHERE Cooperative_ID=? and Cooperative_Visit_ID=?");
            
            $stmt->bind_param("ii",$Cooperative_ID,$Cooperative_Visit_ID);
            $stmt->execute();
        }else{
            $stmt = $concustomercontrol->prepare("DELETE FROM cooperative_finance 
                                    WHERE Cooperative_ID=? and Cooperative_Visit_ID is null");
            
            $stmt->bind_param("i",$Cooperative_ID);
            $stmt->execute();
        }
    
    //echo  "[".$strUserName."]";
    //return  $stmt->get_result();
    }
}



?>