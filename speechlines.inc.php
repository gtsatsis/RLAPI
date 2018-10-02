<?php
$dbConnFailed = "
{
  \"success\": false,
  \"errorcode\": 500,
  \"description\": \"Connection to the database failed, Please contact an administrator.\"
}";
$tokenIsBlocked = "
{
  \"success\": false,
  \"errorcode\": 403,
  \"description\": \"Your token has been blocked from accessing the service. If you think this is in error, please contact an admininstrator.\"
}";
$invalidToken = "
{
  \"success\": false,
  \"errorcode\": 403,
  \"description\": \"Your token is NOT authorized to use this service. If you think this is in error, please contact an administrator.\"
}";

$noToken = "
{
  \"success\": false,
  \"errorcode\": 401,
  \"description\": \"You need to provide a token! (?key parameter)\"
}";
?>
