<?php
$dbConnFailed = "
<html>
<style>
	<style type=\"text/css\">
		p {
			color: #fff;
			font-family: Helvetica, Whitney;
		}
	</style>
	<p>Connection to the database failed, Please contact an administrator.</p>
	</html>";
$alreadyUsed = "
<html>
<style>
	<style type=\"text/css\">
		p {
			color: #fff;
			font-family: Helvetica, Whitney;
		}
	</style>
	<p>We're sorry but your token has already been used to invite someone! If you think this is in error, please contact an administrator</p>
	</html>";
$tokenIsBlocked = "
<html>
<style>
	<style type=\"text/css\">
		p {
			color: #fff;
			font-family: Helvetica, Whitney;
		}
	</style>
	<p>Your token has been blocked from accessing the service. If you think this is in error, please contact an admininstrator.</p>
	</html>";
$invalidToken = "
<html>
<style>
	<style type=\"text/css\">
		p {
			color: #fff;
			font-family: Helvetica, Whitney;
		}
	</style>
	<p>Your token is NOT authorized to use this service. If you think this is in error, please contact an administrator</p>
	</html>";
?>
