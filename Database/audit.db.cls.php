<?php

class Audit {

    public function del_record_sp($myTable,$myTable_ID,$myEDF){
        global $concustomercontrol;
        $stmt = $concustomercontrol->prepare("call del_record_sp (?,?,?);");

        $stmt->bind_param("sii",$myTable,$myTable_ID,$myEDF);
        $stmt->execute();
    }
}
?>
