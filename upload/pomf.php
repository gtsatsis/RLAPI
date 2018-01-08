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
    'endpoint' => 'http://127.0.0.1:9000/',
    'credentials' => $credentials,
    //'signature'  => 'v4',
    'use_path_style_endpoint' => true // Minio Compatibility (https://minio.io)
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

	$fileNames = array(
	'success' => true,
	'files' => array(
	array(
	 'url' => $fileName,
	 'name' => implode($_FILES['files']['name']),
	 'hash_md5' => md5_file('/d2/RLTemp/'.$fileName),
	 'hash_sha1' => sha1_file('/d2/RLTemp/'.$fileName)
	)
       )
      );

	 $result = $s3->putObject(array(
		'Bucket' => 'owoapi',
		'Key'    => $fileName,
		'SourceFile'   => "/d2/RLTemp/" . $fileName,
		'ACL'	=> 'public-read'
	));

	unlink("/d2/RLTemp/" . $fileName);
	echo json_encode($fileNames);

}

}elseif($allowed == "false"){
	echo $tokenIsBlocked;
}else{
	echo $invalidToken;
}
?>
