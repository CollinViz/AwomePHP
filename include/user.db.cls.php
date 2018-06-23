<?php
require_once 'sqlcode.php';

class userdb {
	
	public function validateUser($email,$password){
		$strSQL = sprintf("SELECT
					`users`.`idUsers`,
					`users`.`Name`,
					`users`.`email`,
					`users`.`password`
					FROM `users`
					WHERE `users`.`email`='%s' AND `users`.`password`='%s';",$email,$password);
		echo $strSQL;
		return mysql_query($strSQL);
	}
}

?>