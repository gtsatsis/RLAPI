<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This project requires:
require('../vendor/autoload.php'); // Composer Autoloader
require('../lib/authorize2.php'); // Authorization Library
require('../speechlines.inc.php'); // Speech Lines ($dbConnFailed etc)
require('../../../../S3APICredStore/s3Credentials.inc.php'); // S3 API Creds
require('../../../../pgdbcreds.inc.php'); // Database Credentials
// Set variables


parse_str($_SERVER['QUERY_STRING'], $get_array);
$token = $get_array['key'];

if(is_null($token) or !isset($token)) {
	echo $noToken;
	return;
}

$credentials = new Aws\Credentials\Credentials($s3APIKey, $s3APISecret);

// Code Starts Here
authenticate($token);

if($allowed == "true"){
	$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1',
    'endpoint' => 'http://192.168.25.14:9000',
    'credentials' => $credentials,
    's3ForcePathStyle' => true // Minio Compatibility (https://minio.io)
]);

foreach ($_FILES['files']['name'] as $files) {

	/* 
		|-------------------------------------------------------|
		|Code taken from StackExchange							|
		|Permalink: https://stackoverflow.com/a/5439548/8156177 |
		|-------------------------------------------------------|
	*/
	 $extension = pathinfo($files, PATHINFO_EXTENSION); // get file extension
	 $fileName = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 6)), 0, 6) . "." . $extension; // generate name and concatenate extension
	 $tmpName = implode('', $_FILES['files']['tmp_name']);
	 move_uploaded_file($tmpName, "/d2/RLTemp/" . $fileName);

	 echo $fileName;

	 $result = $s3->putObject([
		'Bucket' => 'owoapi',
		'Key'    => $fileName,
		'Body'   => fopen("/d2/RLTemp/" . $fileName, "r")
	]);

	unlink("/d2/RLTemp/" . $fileName);


}

}elseif($allowed == "false"){
	echo $tokenIsBlocked;
}else{
	echo $invalidToken;
}
?>
