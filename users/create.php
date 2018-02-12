<?php
/* This project requires the following files: */
require '../vendor/autoload.php'; // Composer Auto-loader
require '../lib/authorize2.php'; // Authorization Library
require '../speechlines.inc.php'; // Speech Lines ($dbConnFailed etc)
require '../../../rl1-pgdbcreds.inc.php'; // Database Credentials
/* Composer Usage */
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

/* Define the token variable*/
$token = $_POST['key'];

/* If no token is provided, report to the user and die */
if (is_null($token) or !isset($token)) {
    echo $noToken;
    return;
}

authenticate($token);

if($isAdmin == true){
  $uuid4 = Uuid::uuid4();
  print_r($uuid4); //Debug
  
}else{
return "Unauthorized";
}
?>
