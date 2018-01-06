<?php
// This project requires:
require('../vendor/autoload.php'); // Composer Autoloader
require('../lib/authorize2.php'); // Authorization Library
require('../speechlines.inc.php'); // Speech Lines ($dbConnFailed etc)
require('../../../../S3APICredStore/s3Credentials.inc.php'); // S3 API Creds
require('../../../../pgdbcreds.inc.php'); // Database Credentials
// Set variables


parse_str($_SERVER['QUERY_STRING'], $get_array);
$token = $get_array['key'];

$credentials = new Aws\Credentials\Credentials($s3APIKey, $s3APISecret);

// Code Starts Here
authenticate($token);

if($allowed == "true"){
	$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => 'http://127.0.0.1:9000',
    'credentials' => $credentials,
    's3ForcePathStyle' => true // Minio Compatibility (https://minio.io)
]);

foreach ($_FILES['files']['name'] as $files) {

	/* 
		|-------------------------------------------------------|
		|Code taken from StackExchange							|
		|Permalink: https://stackoverflow.com/a/5439548/8156177|
		|-------------------------------------------------------|
	*/

 	$fileName = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, 6);
	echo $fileName;
}

print_r($_FILES);


// Send a PutObject request and get the result object.
/*$result = $s3->putObject([
    'Bucket' => 'owoapi',
    'Key'    => $fileName,
    'Body'   => $_FILES['files[]']
]);*/

echo "If this has worked, the filename should be" . $fileName . "if not, you've actually managed to fuck up for like the 100th(thousand) time.....";

}elseif($allowed == "false"){
	echo $tokenIsBlocked;
}else{
	echo $invalidToken;
}
?>
