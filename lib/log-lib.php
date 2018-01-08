<?php
require('../../../../rl1-pgdbcreds.inc.php');

function logtodb($token,$fileName,$originalFileName,$timestampOfUpload,$md5Hash,$SHA1Hash){
	require('../../../../rl1-pgdbcreds.inc.php');
	$logQuery = "INSERT INTO logs (token, filename, originalfilename, timestamp, md5hash, sha1hash) VALUES ('$token', '$fileName', '$originalFileName', '$timestampOfUpload', '$md5Hash', '$SHA1Hash')";
	$logResult = pg_exec($database, $logQuery);
}
?>

