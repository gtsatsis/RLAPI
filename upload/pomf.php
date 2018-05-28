<?php

/**
 * PHP version 5
 *
 * @category  File_Uploading
 * @package   RLME\RLAPI
 * @author    Samuel Simão <samuel@pomaire.com.br>
 * @author    George Tsatsis <admin@ratelimited.me>
 * @copyright 2017-2018 RATELIMITED, LLC
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @version   GIT: <git_id>
 * @link      https://ratelimited.me
 * @see       S3Client
 * @since     File available since RLAPI 2.0
 */

 /**
  * Set PHP to report errors (any nature)
  *
  * @todo Remove when in production to avoid attacks and exploits
  */
/**ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

/* This project requires the following files: */
require '../vendor/autoload.php'; // Composer Auto-loader
require '../lib/authorize2.php'; // Authorization Library
require '../lib/log-lib.php'; // Logging Library
require '../lib/filename.php'; // Filename-related functions
require '../speechlines.inc.php'; // Speech Lines ($dbConnFailed etc)
require '../../../S3APICredStore/s3Credentials.inc.php'; // S3 API Credentials
require '../../../rl1-pgdbcreds.inc.php'; // Database Credentials

/* Define Filesizes in bytes for convenience 
(Code taken from StackOverflow: https://stackoverflow.com/a/14758827/8156177 */
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

/* Killswitch Mode / Maintenance Mode */
$killSwitch = false;
if($killSwitch == true){
echo json_encode(array('success' => false, 'files' => 'Killswitch Mode Active; Possible maintenance', 'details' => 'Killswitch Mode Active; Possible maintenance'));
	die();
}

// Pass all the GET parameters to an array
parse_str($_SERVER['QUERY_STRING'], $get_array);

// Extract the token (?key) to the $token variable
$token = $_GET['key'];

/* If no token is provided, report to the user and die */
if (is_null($token) or !isset($token)) {
    echo $noToken;
    return;
}

// Initialize AWS credentials with values from the previously required file
$credentials = new Aws\Credentials\Credentials($s3APIKey, $s3APISecret);

// Authenticate using the provided token
authenticate($token);

if($allowed === false) {
    echo $tokenIsBlocked;
}

/* If the token is allowed, create s3 client and start processing files */
if ($allowed === true) {
    $s3 = new Aws\S3\S3Client(
        [
            'version' => 'latest', // Latest S3 version
            'region'  => 'us-east-1', // The service's region
            'endpoint' => 'http://127.0.0.1:9000', // API to point to
            'credentials' => $credentials, // Credentials (s3Credentials.inc.php)
            //'signature'  => 'v4',
            'use_path_style_endpoint' => true // Minio Compatible (https://minio.io)
        ]
    );

    /*  
        If $_FILES is empty, then the user has not posted any files. 
        Die with an error to prevent PHP warnings 
    */
    if(empty($_FILES)) {
        echo "{
  \"success\": false,
  \"errorcode\": 405,
  \"description\": \"You need to supply files to be upload using HTTP POST (files[])!\"
}";
        return;
    }

    /**
     * Foreach loop to process files
     * 
     * @todo Better error handling and reporting
     */
    foreach ($_FILES['files']['name'] as $files) {
	$filesize = implode($_FILES['files']['size']);
 	if($donorLevel == "free" && $filesize > 104857600){
	echo "{\"success\": false,\"errorcode\": 402,\"description\": \"Sorry, but this file is too big for your donation tier of: Free. Please donate in order to upload bigger files\"}";
	die();}
	if($donorLevel == "platinum" && $filesize > 262144000){
	echo "{\"success\": false,\"errorcode\": 402,\"description\": \"Sorry, but this file is too big for your donation tier of: Platinum. Please donate in order to upload bigger files\"}";
	die();}
 	if($donorLevel == "gold" && $filesize > 524288000){
	echo "{\"success\": false,\"errorcode\": 402,\"description\": \"Sorry, but this file is too big for your donation tier of: Gold. Please donate in order to upload bigger files.\"}";
	die();}
        /*
            |-------------------------------------------------------|
            |Code taken from StackExchange                          |
            |Permalink: https://stackoverflow.com/a/5439548/8156177 |
            |-------------------------------------------------------|
         */

         // Get the uploaded file's extension
        $extension = pathinfo($files, PATHINFO_EXTENSION);

        $fileName = generateFileName($extension);

        $switch = false;
        while($switch === false) {
            if(isUnique($fileName)) {
                $switch = true;
            } else {
                $switch = false;
                $fileName = generateFileName($extension);
            }
        }

        /**
         * Little hack to convert from array to string by imploding with no 
         * glue string to avoid "illegal string offset"
         * 
         * @todo Replace by something less "hacky"
         */
        $tmpName = implode('', $_FILES['files']['tmp_name']);

        /**
         * Move file to a temporary, unaccessible location.
         * 
         * @todo Make the path configurable
         */
        move_uploaded_file($tmpName, "/d2/RLTemp/" . $fileName);
	
        /* Create array with file data */
        $fileNames = array(
            'success' => true, // If the user got here, we had success    
            'files' => array( // Add files to an array
                        array(
                            // Filename (on the server)
                            'url' => $fileName,
                            // The name of the uploaded file (the user's file name)
                            'name' => implode($_FILES['files']['name']),
                            /* Hashes (md5 and sha1) */
                            'hash_md5' => md5_file('/d2/RLTemp/'.$fileName),
                            'hash_sha1' => sha1_file('/d2/RLTemp/'.$fileName)
                            )
                        )
		);
		
		/* Create file hashes (md5 and sha1) */
		$md5 = md5_file('/d2/RLTemp/'.$fileName);
		$sha1 = sha1_file('/d2/RLTemp/'.$fileName);

        /* Put the file in the Minio/S3 bucket */
        $result = $s3->putObject(
            array(
            'Bucket' => 'owoapi', // Bucket name
            'Key'    => $fileName, // Key = File name (on the server)
            'SourceFile'   => "/d2/RLTemp/" . $fileName, // The file to be put
            'ACL'    => 'public-read' // Access Control List set to public read
            )
        );

        // Delete the file from the temporary location
        unlink("/d2/RLTemp/" . $fileName);

        // Print the array as JSON for ShareX compatibility
		echo json_encode($fileNames);
		
		// Sanitize original filename
		$removeFromStr[] = "'";
		$removeFromStr[] = "=";
		$origFileName = str_replace( $removeFromStr, "", implode($_FILES['files']['name']) );
		// Log to database
		logtoDB($token,$fileName,$origFileName,time(),$md5,$sha1);

    } 
}
