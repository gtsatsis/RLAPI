<?php
require('../../../rl1-pgdbcreds.inc.php');

function logtodb($token,$fileName,$originalFileName,$timestampOfUpload,$md5Hash,$SHA1Hash){
	require('../../../rl1-pgdbcreds.inc.php');
	$logQuery =  pg_prepare($database, "logQuery", "INSERT INTO logs (token, filename, originalfilename, timestamp, md5hash, sha1hash) VALUES ('$1', '$2', '$3', '$4', '$5', '$6')");
	$logResult = pg_execute($database, "logQuery", array($token, $fileName, $originalFileName, $timestampOfUpload, $md5Hash, $SHA1Hash));
}
?>

