<?php
require('../../../../pgdbcreds.inc.php');

function logtoDB($token,$fileName,$originalFileName,$timestampOfUpload,$md5Hash,$SHA1Hash){
	$logQuery = "INSERT INTO logs (token, filename, originalfilename, timestamp, md5hash, sha1hash) VALUES ('$token', '$fileName', '$originalFileName', '$timestampOfUpload', '$md5Hash', '$SHA1Hash')";
	$logResult = pg_exec($database, $logQuery);
}
?>

