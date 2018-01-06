<?php
// This project requires:
require('../vendor/autoload.php'); // Composer Autoloader
require('../speechlines.inc.php'); // Speech Lines ($dbConnFailed etc)
require('../../../../pgdbcreds.inc.php'); // Database Credentials

function authenticate($token){
	global $allowed;
	require('../../../../pgdbcreds.inc.php'); // Database Credentials
		preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i", $token, $filteredToken);
		$tokencomparison = pg_prepare($database, "fetch-token-by-token", "SELECT * FROM tokens WHERE token = $1");
		$tokencomparisonresult = pg_execute($database, "fetch-token-by-token", $filteredToken);
		$tokenrow = pg_fetch_object($tokencomparisonresult);

		

		if ($tokenrow)
		{
			$uid = $tokenrow->user_id;
			$userGet = "SELECT * FROM users WHERE id = '$uid'";
			$userResult = pg_query($database, $userGet);
			$userRow = pg_fetch_object($userResult);
			$isblocked = $userRow->is_blocked;
			if ($isblocked == 't'){
				echo $tokenIsBlocked;
				$allowed = "false";
				}elseif($isblocked == 'f' || null){
					$allowed = "true";
				}
			}	
}
?>
